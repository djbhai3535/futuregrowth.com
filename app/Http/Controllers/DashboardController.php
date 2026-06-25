<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        $totalBalance = $wallet->deposit_balance + $wallet->roi_balance + $wallet->referral_balance + $wallet->bonus_balance;
        
        $activeInvestmentsSum = \App\Models\Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');
            
        $activeInvestmentsList = \App\Models\Investment::with('plan')
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->take(5)
            ->get();

        $directReferralsCount = \App\Models\User::where('referred_by', $user->id)->count();

        // 10-Level Recursive Team Stats
        $teamSize = 0;
        $teamVolume = 0;
        $currentLevelReferrals = \App\Models\User::where('referred_by', $user->id)->get();
        for ($i = 1; $i <= 10; $i++) {
            if ($currentLevelReferrals->isEmpty()) break;
            $teamSize += $currentLevelReferrals->count();
            $userIds = $currentLevelReferrals->pluck('id');
            $teamVolume += \App\Models\Investment::whereIn('user_id', $userIds)->where('status', 'active')->sum('amount');
            $currentLevelReferrals = \App\Models\User::whereIn('referred_by', $userIds)->get();
        }

        $transactions = \App\Models\Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Analytics Chart Data (Last 7 Days Earnings)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('M d');
            $chartLabels[] = $date;
            $sum = \App\Models\Transaction::where('user_id', $user->id)
                ->whereIn('type', ['roi', 'commission'])
                ->whereDate('created_at', now()->subDays($i)->toDateString())
                ->sum('amount');
            $chartData[] = $sum;
        }

        return view('dashboard.index', compact(
            'wallet', 
            'totalBalance', 
            'activeInvestmentsSum', 
            'activeInvestmentsList',
            'directReferralsCount', 
            'teamSize',
            'teamVolume',
            'transactions',
            'chartLabels',
            'chartData'
        ));
    }

    public function team()
    {
        $user = Auth::user();
        $referrals = $user->referrals()->with('wallet')->get();
        return view('dashboard.team', compact('referrals'));
    }

    public function history()
    {
        $transactions = Auth::user()->transactions()->latest()->paginate(20);
        return view('dashboard.history', compact('transactions'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('tickets', 'public');
        }

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'screenshot_path' => $screenshotPath,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Opened a support ticket',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Support ticket submitted successfully. We will reply soon.');
    }
}
