<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Plan;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\SupportTicket;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $depositsPending = Deposit::where('status', 'pending')->count();
        $withdrawalsPending = Withdrawal::where('status', 'pending')->count();
        $totalInvestments = \App\Models\Investment::where('status', 'active')->sum('amount');

        // Dynamic 7-day approved deposits and withdrawals for growth matrix chart
        $chartLabels = [];
        $chartDeposits = [];
        $chartWithdrawals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $dateString = $date->toDateString();
            
            $chartDeposits[] = Deposit::where('status', 'approved')
                ->whereDate('updated_at', $dateString)
                ->sum('amount');
                
            $chartWithdrawals[] = Withdrawal::where('status', 'approved')
                ->whereDate('updated_at', $dateString)
                ->sum('amount');
        }

        return view('admin.dashboard', compact(
            'usersCount', 
            'depositsPending', 
            'withdrawalsPending', 
            'totalInvestments',
            'chartLabels',
            'chartDeposits',
            'chartWithdrawals'
        ));
    }

    public function settings()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', 'site_logo', 'site_favicon', 'footer_logo', 'site_og_image']);
        
        // Filter out empty secret keys
        if (empty($data['smtp_password'])) {
            unset($data['smtp_password']);
        }
        if (empty($data['recaptcha_secret_key'])) {
            unset($data['recaptcha_secret_key']);
        }

        // Handle File Uploads
        $fileKeys = ['site_logo', 'site_favicon', 'footer_logo', 'site_og_image'];
        foreach ($fileKeys as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store('site_assets', 'public');
                Setting::updateOrCreate(
                    ['key' => $fileKey],
                    ['value' => $path]
                );
                \Illuminate\Support\Facades\Cache::forget("setting_{$fileKey}");
            }
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            \Illuminate\Support\Facades\Cache::forget("setting_{$key}");
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated platform settings',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Settings updated successfully. Changes are live instantly!');
    }

    public function deposits()
    {
        $deposits = Deposit::with('user')->latest()->paginate(20);
        return view('admin.deposits', compact('deposits'));
    }

    public function approveDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        if ($deposit->status !== 'pending') return back();
        
        $deposit->status = 'approved';
        $deposit->save();

        $wallet = $deposit->user->wallet;
        $wallet->deposit_balance += $deposit->amount;
        $wallet->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Approved deposit ID ' . $deposit->id . ' for user ' . $deposit->user->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        \App\Models\Transaction::create([
            'user_id' => $deposit->user_id,
            'type' => 'deposit',
            'amount' => $deposit->amount,
            'wallet_type' => 'deposit_balance',
            'status' => 'completed',
            'description' => 'Deposit Approved. TXID: ' . $deposit->txid,
            'reference_id' => $deposit->id
        ]);

        return back()->with('success', 'Deposit approved successfully.');
    }

    public function rejectDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->status = 'rejected';
        $deposit->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Rejected deposit ID ' . $deposit->id . ' for user ' . $deposit->user->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Deposit rejected.');
    }

    public function withdrawals()
    {
        $withdrawals = Withdrawal::with('user')->latest()->paginate(20);
        return view('admin.withdrawals', compact('withdrawals'));
    }

    public function approveWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->status = 'approved';
        $withdrawal->save();

        \App\Models\Transaction::where('type', 'withdrawal')
            ->where('reference_id', $withdrawal->id)
            ->update([
                'status' => 'completed',
                'description' => 'Withdrawal approved. Sent to ' . $withdrawal->wallet_address
            ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Approved withdrawal ID ' . $withdrawal->id . ' for user ' . $withdrawal->user->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Withdrawal approved.');
    }

    public function rejectWithdrawal($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->status = 'rejected';
        $withdrawal->save();

        // Refund to dynamic wallet type
        $wallet = $withdrawal->user->wallet;
        $walletType = $withdrawal->wallet_type ?? 'roi_balance';
        $wallet->$walletType += $withdrawal->amount;
        $wallet->save();

        \App\Models\Transaction::where('type', 'withdrawal')
            ->where('reference_id', $withdrawal->id)
            ->update([
                'status' => 'rejected',
                'description' => 'Withdrawal rejected and refunded'
            ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Rejected withdrawal ID ' . $withdrawal->id . ' for user ' . $withdrawal->user->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Withdrawal rejected and refunded.');
    }

    public function users(Request $request)
    {
        $query = \App\Models\User::with('wallet');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus($id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot ban yourself.']);
        }
        
        $user->status = $user->status === 'active' ? 'banned' : 'active';
        $user->save();
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Changed user status for ID ' . $user->id . ' to ' . $user->status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        return back()->with('success', 'User status updated successfully.');
    }

    public function resetUserPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        $user = \App\Models\User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Reset password for user ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return back()->with('success', 'User password reset successfully.');
    }

    public function plans()
    {
        $plans = \App\Models\Plan::all();
        return view('admin.plans', compact('plans'));
    }

    public function togglePlan($id)
    {
        $plan = \App\Models\Plan::findOrFail($id);
        $plan->status = $plan->status === 'active' ? 'inactive' : 'active';
        $plan->save();
        return back()->with('success', 'Plan status toggled.');
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'min_roi' => 'required|numeric|min:0',
            'max_roi' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        \App\Models\Plan::create($request->all());
        return back()->with('success', 'Investment Plan created successfully.');
    }

    public function updatePlan(Request $request, $id)
    {
        $plan = \App\Models\Plan::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|min:0',
            'min_roi' => 'required|numeric|min:0',
            'max_roi' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $plan->update($request->all());
        return back()->with('success', 'Investment Plan updated successfully.');
    }

    public function destroyPlan($id)
    {
        $plan = \App\Models\Plan::findOrFail($id);
        $plan->delete();
        return back()->with('success', 'Investment Plan deleted successfully.');
    }

    public function tickets()
    {
        $tickets = SupportTicket::with('user')->latest()->paginate(20);
        return view('admin.tickets', compact('tickets'));
    }

    public function replyTicket(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string']);
        $ticket = SupportTicket::findOrFail($id);
        
        $ticket->reply = $request->reply;
        $ticket->status = 'answered';
        $ticket->save();

        return back()->with('success', 'Reply sent to user.');
    }

    public function closeTicket($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();

        return back()->with('success', 'Ticket closed.');
    }

    // FAQs Management
    public function faqs()
    {
        $faqs = \App\Models\Faq::orderBy('sort_order')->get();
        return view('admin.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'numeric'
        ]);

        \App\Models\Faq::create($request->all());
        return back()->with('success', 'FAQ added successfully.');
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = \App\Models\Faq::findOrFail($id);
        $faq->update($request->all());
        return back()->with('success', 'FAQ updated.');
    }

    public function destroyFaq($id)
    {
        \App\Models\Faq::findOrFail($id)->delete();
        return back()->with('success', 'FAQ deleted.');
    }

    // Documents Management
    public function documents()
    {
        $documents = \App\Models\Document::latest()->get();
        return view('admin.documents', compact('documents'));
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        \App\Models\Document::create([
            'title' => $request->title,
            'file_path' => $path,
            'type' => $request->file('file')->getClientOriginalExtension(),
            'is_active' => true
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function updateDocument(Request $request, $id)
    {
        $doc = \App\Models\Document::findOrFail($id);
        $doc->is_active = $request->has('is_active');
        $doc->save();
        return back()->with('success', 'Document status updated.');
    }

    public function destroyDocument($id)
    {
        $doc = \App\Models\Document::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
        return back()->with('success', 'Document deleted.');
    }
}
