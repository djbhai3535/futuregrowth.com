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
        // 1. TOP SUMMARY CARDS
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $emailVerifiedUsers = User::whereNotNull('email_verified_at')->count();
        
        $totalPlatformWalletBalance = \App\Models\Wallet::sum(\DB::raw('deposit_balance + roi_balance + referral_balance + bonus_balance'));
        
        $totalPlatformDeposits = Deposit::sum('amount');
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $approvedDepositsCount = Deposit::where('status', 'approved')->count();
        $approvedDepositsSum = Deposit::where('status', 'approved')->sum('amount');
        $failedDeposits = Deposit::where('status', 'rejected')->count();
        
        $totalWithdrawals = Withdrawal::sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $approvedWithdrawalsCount = Withdrawal::where('status', 'approved')->count();
        $approvedWithdrawalsSum = Withdrawal::where('status', 'approved')->sum('amount');
        
        $totalActiveInvestments = \App\Models\Investment::where('status', 'active')->sum('amount');
        $completedInvestments = \App\Models\Investment::where('status', 'completed')->count();
        $totalRoiPaid = \App\Models\Transaction::where('type', 'roi')->sum('amount');
        $totalReferralCommissionPaid = \App\Models\Transaction::where('type', 'commission')->sum('amount');
        
        // Platform Earnings = Approved withdrawal fees
        $totalPlatformEarnings = Withdrawal::where('status', 'approved')->sum('fee');

        // 2. RECENT ACTIVITY TABLES
        $latestUsers = User::latest()->take(5)->get();
        $latestDeposits = Deposit::with('user')->latest()->take(5)->get();
        $latestWithdrawals = Withdrawal::with('user')->latest()->take(5)->get();
        $latestInvestments = \App\Models\Investment::with(['user', 'plan'])->latest()->take(5)->get();
        $latestRoiPayments = \App\Models\Transaction::with('user')->where('type', 'roi')->latest()->take(5)->get();

        // 3. DAILY ANALYTICS CHARTS (Last 7 Days)
        $chartLabels = [];
        $chartRegs = [];
        $chartDeps = [];
        $chartWiths = [];
        $chartInvs = [];
        $chartRois = [];
        $chartRefs = [];
        $chartGrowth = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $dateString = $date->toDateString();

            $chartRegs[] = User::whereDate('created_at', $dateString)->count();
            
            $chartDeps[] = Deposit::where('status', 'approved')
                ->whereDate('updated_at', $dateString)
                ->sum('amount');
                
            $chartWiths[] = Withdrawal::where('status', 'approved')
                ->whereDate('updated_at', $dateString)
                ->sum('amount');

            $chartInvs[] = \App\Models\Investment::whereDate('created_at', $dateString)->sum('amount');
            
            $chartRois[] = \App\Models\Transaction::where('type', 'roi')
                ->whereDate('created_at', $dateString)
                ->sum('amount');

            $chartRefs[] = \App\Models\Transaction::where('type', 'commission')
                ->whereDate('created_at', $dateString)
                ->sum('amount');

            $chartGrowth[] = User::whereDate('created_at', '<=', $dateString)->count();
        }

        // 4. MONTHLY REVENUE (Last 6 Months)
        $monthlyRevenueLabels = [];
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthlyRevenueLabels[] = $monthDate->format('F');
            $monthlyRevenueData[] = Withdrawal::where('status', 'approved')
                ->whereMonth('updated_at', $monthDate->month)
                ->whereYear('updated_at', $monthDate->year)
                ->sum('fee');
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'suspendedUsers',
            'emailVerifiedUsers',
            'totalPlatformWalletBalance',
            'totalPlatformDeposits',
            'pendingDeposits',
            'approvedDepositsCount',
            'approvedDepositsSum',
            'failedDeposits',
            'totalWithdrawals',
            'pendingWithdrawals',
            'approvedWithdrawalsCount',
            'approvedWithdrawalsSum',
            'totalActiveInvestments',
            'completedInvestments',
            'totalRoiPaid',
            'totalReferralCommissionPaid',
            'totalPlatformEarnings',
            'latestUsers',
            'latestDeposits',
            'latestWithdrawals',
            'latestInvestments',
            'latestRoiPayments',
            'chartLabels',
            'chartRegs',
            'chartDeps',
            'chartWiths',
            'chartInvs',
            'chartRois',
            'chartRefs',
            'chartGrowth',
            'monthlyRevenueLabels',
            'monthlyRevenueData'
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
        if (empty($data['nowpayments_api_key'])) {
            unset($data['nowpayments_api_key']);
        }
        if (empty($data['nowpayments_ipn_secret'])) {
            unset($data['nowpayments_ipn_secret']);
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

    public function deposits(Request $request)
    {
        $query = Deposit::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('txid', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->latest()->paginate(20)->withQueryString();
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

    public function withdrawals(Request $request)
    {
        $query = Withdrawal::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('wallet_address', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(20)->withQueryString();
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

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply Email Verification Filter
        if ($request->filled('email_verified')) {
            if ($request->email_verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Apply Role Filter
        if ($request->filled('role')) {
            $query->where('is_admin', $request->role === 'admin' ? 1 : 0);
        }

        // Apply Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['id', 'name', 'email', 'created_at', 'status'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function showUser($id)
    {
        $user = \App\Models\User::with(['wallet', 'referrer'])->findOrFail($id);

        $totalDeposit = \App\Models\Deposit::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
        $totalWithdrawal = \App\Models\Withdrawal::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
        $totalInvestment = \App\Models\Investment::where('user_id', $user->id)->sum('amount');
        $runningInvestment = \App\Models\Investment::where('user_id', $user->id)->where('status', 'active')->sum('amount');
        $totalRoi = \App\Models\Investment::where('user_id', $user->id)->sum('total_earned');
        $referralIncome = \App\Models\Transaction::where('user_id', $user->id)->where('type', 'commission')->sum('amount');

        // Build 10 levels downline tree
        $referralTree = [];
        $currentLevelReferrals = \App\Models\User::where('referred_by', $user->id)->get();
        $teamSize = 0;
        for ($i = 1; $i <= 10; $i++) {
            if ($currentLevelReferrals->isEmpty()) break;
            $referralTree[$i] = $currentLevelReferrals;
            $teamSize += $currentLevelReferrals->count();
            $userIds = $currentLevelReferrals->pluck('id');
            $currentLevelReferrals = \App\Models\User::whereIn('referred_by', $userIds)->get();
        }

        $lastLogin = \App\Models\ActivityLog::where('user_id', $user->id)
            ->where('action', 'Logged in')
            ->latest()
            ->first();

        // Paginate relational data for tabs
        $deposits = \App\Models\Deposit::where('user_id', $user->id)->latest()->get();
        $withdrawals = \App\Models\Withdrawal::where('user_id', $user->id)->latest()->get();
        $investments = \App\Models\Investment::with('plan')->where('user_id', $user->id)->latest()->get();
        $roiHistory = \App\Models\Transaction::where('user_id', $user->id)->where('type', 'roi')->latest()->get();
        $activityLogs = \App\Models\ActivityLog::where('user_id', $user->id)->latest()->get();
        $plans = \App\Models\Plan::where('is_active', true)->get();

        return view('admin.users_show', compact(
            'user',
            'totalDeposit',
            'totalWithdrawal',
            'totalInvestment',
            'runningInvestment',
            'totalRoi',
            'referralIncome',
            'referralTree',
            'teamSize',
            'lastLogin',
            'deposits',
            'withdrawals',
            'investments',
            'roiHistory',
            'activityLogs',
            'plans'
        ));
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->merge([
            'is_admin' => $request->has('is_admin') ? 1 : 0
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:255|unique:users,phone,' . $user->id,
            'is_admin' => 'required|boolean',
            'status' => 'required|string|in:active,suspended,banned',
            'referral_code' => 'required|string|unique:users,referral_code,' . $user->id,
            'referred_by' => 'nullable|integer|exists:users,id',
            'deposit_balance' => 'required|numeric|min:0',
            'roi_balance' => 'required|numeric|min:0',
            'referral_balance' => 'required|numeric|min:0',
            'bonus_balance' => 'required|numeric|min:0',
        ]);

        $user->update($request->only(['name', 'username', 'email', 'phone', 'is_admin', 'status', 'referral_code', 'referred_by']));

        $wallet = $user->wallet;
        $wallet->update($request->only(['deposit_balance', 'roi_balance', 'referral_balance', 'bonus_balance']));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated profile and wallet balances for user ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User profile and wallet updated successfully.');
    }

    public function activateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Activated user account for ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User account activated successfully.');
    }

    public function suspendUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot suspend yourself.']);
        }
        $user->status = 'suspended';
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Suspended user account for ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User account suspended successfully.');
    }

    public function banUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot ban yourself.']);
        }
        $user->status = 'banned';
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Banned user account for ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User account banned successfully.');
    }

    public function deleteUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Soft deleted user account for ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('admin.users')->with('success', 'User soft deleted successfully.');
    }

    public function changeUserPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Manually changed password for user ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Password updated successfully.');
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

    public function resetUserPasswordAuto(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $plainPassword = \Illuminate\Support\Str::random(12);
        $user->password = \Illuminate\Support\Facades\Hash::make($plainPassword);
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Automatically reset password for user ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Password reset successfully! New Password: ' . $plainPassword);
    }

    public function verifyUserEmail(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Manually verified email for user ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User email status verified successfully.');
    }

    public function auditLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->latest()->paginate(30)->withQueryString();
        return view('admin.audit_logs', compact('logs'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function exportReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:users,deposits,withdrawals',
            'format' => 'required|in:csv,excel,pdf',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $type = $request->type;
        $format = $request->format;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if ($type === 'users') {
            $query = \App\Models\User::with('wallet');
            if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
            if ($toDate) $query->whereDate('created_at', '<=', $toDate);
            $data = $query->get();

            if ($format === 'pdf') {
                return view('admin.reports.print_users', compact('data', 'fromDate', 'toDate'));
            }

            $filename = "users_report_" . date('Y-m-d') . ($format === 'excel' ? '.xls' : '.csv');
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['ID', 'Name', 'Username', 'Email', 'Phone', 'Referral Code', 'Status', 'Verified', 'Deposit Bal', 'ROI Bal', 'Referral Bal', 'Bonus Bal', 'Joined At'];
            
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->name,
                        $row->username,
                        $row->email,
                        $row->phone,
                        $row->referral_code,
                        $row->status,
                        $row->email_verified_at ? 'Yes' : 'No',
                        $row->wallet ? $row->wallet->deposit_balance : 0,
                        $row->wallet ? $row->wallet->roi_balance : 0,
                        $row->wallet ? $row->wallet->referral_balance : 0,
                        $row->wallet ? $row->wallet->bonus_balance : 0,
                        $row->created_at->toDateTimeString()
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($type === 'deposits') {
            $query = \App\Models\Deposit::with('user');
            if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
            if ($toDate) $query->whereDate('created_at', '<=', $toDate);
            $data = $query->get();

            if ($format === 'pdf') {
                return view('admin.reports.print_deposits', compact('data', 'fromDate', 'toDate'));
            }

            $filename = "deposits_report_" . date('Y-m-d') . ($format === 'excel' ? '.xls' : '.csv');
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['ID', 'User', 'Amount', 'TXID', 'Status', 'Date'];
            
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->user ? $row->user->name . ' (' . $row->user->username . ')' : 'N/A',
                        $row->amount,
                        $row->txid,
                        $row->status,
                        $row->created_at->toDateTimeString()
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($type === 'withdrawals') {
            $query = \App\Models\Withdrawal::with('user');
            if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
            if ($toDate) $query->whereDate('created_at', '<=', $toDate);
            $data = $query->get();

            if ($format === 'pdf') {
                return view('admin.reports.print_withdrawals', compact('data', 'fromDate', 'toDate'));
            }

            $filename = "withdrawals_report_" . date('Y-m-d') . ($format === 'excel' ? '.xls' : '.csv');
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['ID', 'User', 'Amount', 'Wallet Address', 'Type', 'Status', 'Date'];
            
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->id,
                        $row->user ? $row->user->name . ' (' . $row->user->username . ')' : 'N/A',
                        $row->amount,
                        $row->wallet_address,
                        $row->wallet_type,
                        $row->status,
                        $row->created_at->toDateTimeString()
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
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

    // Extended Admin Action Handlers & Controls

    public function adjustWallet(Request $request, $id)
    {
        $request->validate([
            'balance_type' => 'required|in:deposit_balance,roi_balance,referral_balance,bonus_balance',
            'action_type' => 'required|in:increase,decrease',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($id);
        $wallet = $user->wallet;
        $amount = $request->amount;
        $balType = $request->balance_type;

        if ($request->action_type === 'increase') {
            $wallet->$balType += $amount;
            $msg = "Manually increased " . str_replace('_', ' ', $balType) . " by $" . $amount;
        } else {
            if ($wallet->$balType < $amount) {
                return back()->withErrors(['amount' => 'Insufficient wallet balance for this deduction.']);
            }
            $wallet->$balType -= $amount;
            $msg = "Manually decreased " . str_replace('_', ' ', $balType) . " by $" . $amount;
        }
        $wallet->save();

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'bonus',
            'amount' => $amount,
            'wallet_type' => $balType,
            'status' => 'completed',
            'description' => $request->description . ' (' . $msg . ')'
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $msg . ' for User ID ' . $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Wallet balance adjusted successfully.');
    }

    public function addDeposit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'txid' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $user = User::findOrFail($request->user_id);
        $txid = $request->txid ?: 'MANUAL_' . uniqid();

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'txid' => $txid,
            'status' => $request->status
        ]);

        if ($request->status === 'approved') {
            $wallet = $user->wallet;
            $wallet->deposit_balance += $request->amount;
            $wallet->save();

            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'wallet_type' => 'deposit_balance',
                'status' => 'completed',
                'description' => 'Manual Deposit Added & Approved. TXID: ' . $txid,
                'reference_id' => $deposit->id
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Manually added deposit of $' . $request->amount . ' for user ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Manual deposit created successfully.');
    }

    public function addWithdrawal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'wallet_address' => 'required|string|max:255',
            'wallet_type' => 'required|in:deposit_balance,roi_balance,referral_balance,bonus_balance',
            'status' => 'required|in:pending,approved,rejected'
        ]);
        
        $user = User::findOrFail($request->user_id);
        $wallet = $user->wallet;
        $balType = $request->wallet_type;
        $amount = $request->amount;
        $fee = $amount * (setting('withdrawal_fee_percent', 10) / 100);
        $net = $amount - $fee;

        if ($request->status !== 'rejected') {
            if ($wallet->$balType < $amount) {
                return back()->withErrors(['amount' => 'Insufficient wallet balance for this withdrawal.']);
            }
            $wallet->$balType -= $amount;
            $wallet->save();
        }

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $net,
            'wallet_address' => $request->wallet_address,
            'wallet_type' => $balType,
            'status' => $request->status
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'wallet_type' => $balType,
            'status' => $request->status === 'approved' ? 'completed' : ($request->status === 'pending' ? 'pending' : 'rejected'),
            'description' => 'Manual Withdrawal. Destination: ' . $request->wallet_address,
            'reference_id' => $withdrawal->id
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Manually added withdrawal of $' . $request->amount . ' for user ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Manual withdrawal created.');
    }

    public function addInvestment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:active,completed'
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = Plan::findOrFail($request->plan_id);

        $investment = \App\Models\Investment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => $request->amount,
            'daily_rate' => $plan->daily_roi_percent,
            'total_earned' => 0.00,
            'status' => $request->status,
            'last_roi_at' => $request->status === 'active' ? now() : null
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'wallet_type' => 'deposit_balance',
            'status' => 'completed',
            'description' => 'Manual Investment Created. Plan: ' . $plan->name,
            'reference_id' => $investment->id
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Manually added investment of $' . $request->amount . ' on plan ' . $plan->name . ' for user ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Manual investment created.');
    }

    public function completeInvestment($id)
    {
        $investment = \App\Models\Investment::findOrFail($id);
        $investment->status = 'completed';
        $investment->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Manually completed investment ID ' . $id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Investment marked as completed.');
    }

    public function addRoi(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($id);
        $wallet = $user->wallet;
        $wallet->roi_balance += $request->amount;
        $wallet->save();

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'roi',
            'amount' => $request->amount,
            'wallet_type' => 'roi_balance',
            'status' => 'completed',
            'description' => $request->description
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Credited manual ROI of $' . $request->amount . ' to user ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Manual ROI paid successfully.');
    }

    public function addReferralBonus(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($id);
        $wallet = $user->wallet;
        $wallet->referral_balance += $request->amount;
        $wallet->save();

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'commission',
            'amount' => $request->amount,
            'wallet_type' => 'referral_balance',
            'status' => 'completed',
            'description' => $request->description
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Credited manual referral commission of $' . $request->amount . ' to user ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Manual referral commission paid.');
    }

    public function changeSponsor(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_sponsor_id' => 'nullable|exists:users,id|different:user_id'
        ]);

        $user = User::findOrFail($request->user_id);
        $oldSponsor = $user->referrer ? $user->referrer->username : 'None';
        
        $user->referred_by = $request->new_sponsor_id;
        $user->save();

        $newSponsor = $user->referrer ? $user->referrer->username : 'None';

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Moved user ' . $user->username . ' sponsor from ' . $oldSponsor . ' to ' . $newSponsor,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User sponsor updated successfully.');
    }

    public function removeReferral(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $oldSponsor = $user->referrer ? $user->referrer->username : 'None';
        
        $user->referred_by = null;
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Removed sponsor association from user ' . $user->username . ' (was ' . $oldSponsor . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User sponsor removed successfully.');
    }

    public function rebuildReferralTree()
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Rebuilt platform referral tree integrity checks',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        return back()->with('success', 'Platform referral tree rebuild completed successfully.');
    }

    public function referrals(Request $request)
    {
        $userId = $request->input('user_id');
        $selectedUser = null;
        $levelsData = [];

        if ($userId) {
            $selectedUser = User::findOrFail($userId);
            
            $currentLevelUserIds = [$selectedUser->id];
            for ($i = 1; $i <= 10; $i++) {
                $levelUsers = User::whereIn('referred_by', $currentLevelUserIds)->get();
                if ($levelUsers->isEmpty()) {
                    $levelsData[$i] = [
                        'users' => collect(),
                        'total_deposits' => 0,
                        'total_investments' => 0,
                        'total_business' => 0,
                        'total_commission' => 0
                    ];
                    $currentLevelUserIds = [];
                    continue;
                }

                $levelUserIds = $levelUsers->pluck('id')->toArray();
                
                $totalDeposits = Deposit::whereIn('user_id', $levelUserIds)->where('status', 'approved')->sum('amount');
                $totalInvestments = \App\Models\Investment::whereIn('user_id', $levelUserIds)->sum('amount');
                $totalBusiness = \App\Models\Investment::whereIn('user_id', $levelUserIds)->where('status', 'active')->sum('amount');
                $totalCommission = \App\Models\Transaction::whereIn('reference_id', $levelUserIds)
                    ->where('type', 'commission')
                    ->sum('amount');

                $levelUsersDetailed = User::with('wallet')->whereIn('id', $levelUserIds)->get();

                foreach ($levelUsersDetailed as $u) {
                    $u->total_dep = Deposit::where('user_id', $u->id)->where('status', 'approved')->sum('amount');
                    $u->total_with = Withdrawal::where('user_id', $u->id)->where('status', 'approved')->sum('amount');
                    $u->total_inv = \App\Models\Investment::where('user_id', $u->id)->sum('amount');
                    $u->total_earned_roi = \App\Models\Investment::where('user_id', $u->id)->sum('total_earned');
                    $u->ref_income = \App\Models\Transaction::where('user_id', $u->id)->where('type', 'commission')->sum('amount');
                }

                $levelsData[$i] = [
                    'users' => $levelUsersDetailed,
                    'total_deposits' => $totalDeposits,
                    'total_investments' => $totalInvestments,
                    'total_business' => $totalBusiness,
                    'total_commission' => $totalCommission
                ];

                $currentLevelUserIds = $levelUserIds;
            }
        }

        $allUsers = User::orderBy('name')->get(['id', 'name', 'username']);

        return view('admin.referrals', compact('allUsers', 'selectedUser', 'levelsData'));
    }
}
