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
        
        $levelsData = [];
        $currentLevelUserIds = [$user->id];
        $allDownlineUserIds = [];

        for ($i = 1; $i <= 10; $i++) {
            $levelUsers = \App\Models\User::whereIn('referred_by', $currentLevelUserIds)->get();
            
            if ($levelUsers->isEmpty()) {
                $levelsData[$i] = [
                    'level' => $i,
                    'percent' => $i === 1 ? setting('direct_reward_percent', 20) : setting('referral_level_' . $i, 1),
                    'is_direct' => $i === 1,
                    'total_users' => 0,
                    'active_users' => 0,
                    'inactive_users' => 0,
                    'total_investment' => 0.00,
                    'referral_earnings' => 0.00,
                    'pending_earnings' => 0.00,
                    'paid_earnings' => 0.00,
                ];
                $currentLevelUserIds = [];
                continue;
            }

            $levelUserIds = $levelUsers->pluck('id')->toArray();
            $allDownlineUserIds = array_merge($allDownlineUserIds, $levelUserIds);
            
            $activeUserIds = \App\Models\Investment::whereIn('user_id', $levelUserIds)
                ->where('status', 'active')
                ->pluck('user_id')
                ->unique()
                ->toArray();
                
            $totalUsers = count($levelUserIds);
            $activeCount = count($activeUserIds);
            $inactiveCount = $totalUsers - $activeCount;
            
            $totalInvestment = \App\Models\Investment::whereIn('user_id', $levelUserIds)
                ->where('status', 'active')
                ->sum('amount');
                
            $referralEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->whereIn('reference_id', $levelUserIds)
                ->sum('amount');
                
            $levelsData[$i] = [
                'level' => $i,
                'percent' => $i === 1 ? setting('direct_reward_percent', 20) : setting('referral_level_' . $i, 1),
                'is_direct' => $i === 1,
                'total_users' => $totalUsers,
                'active_users' => $activeCount,
                'inactive_users' => $inactiveCount,
                'total_investment' => $totalInvestment,
                'referral_earnings' => $referralEarnings,
                'pending_earnings' => 0.00,
                'paid_earnings' => $referralEarnings,
            ];
            
            $currentLevelUserIds = $levelUserIds;
        }

        // Summary Stats
        $teamSize = count($allDownlineUserIds);
        $directReferralsCount = $levelsData[1]['total_users'];
        
        $teamVolume = 0;
        $todayEarnings = 0;
        $weeklyEarnings = 0;
        $monthlyEarnings = 0;
        $lifetimeEarnings = 0;

        if ($teamSize > 0) {
            $teamVolume = \App\Models\Investment::whereIn('user_id', $allDownlineUserIds)
                ->where('status', 'active')
                ->sum('amount');
                
            $lifetimeEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->sum('amount');
                
            $todayEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->where('created_at', '>=', now()->startOfDay())
                ->sum('amount');
                
            $weeklyEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->where('created_at', '>=', now()->subDays(7))
                ->sum('amount');
                
            $monthlyEarnings = \App\Models\Transaction::where('user_id', $user->id)
                ->where('type', 'commission')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('amount');
        }

        // List direct referrals for detail section
        $referrals = $user->referrals()->with('wallet')->get();

        return view('dashboard.team', compact(
            'levelsData',
            'teamSize',
            'directReferralsCount',
            'teamVolume',
            'todayEarnings',
            'weeklyEarnings',
            'monthlyEarnings',
            'lifetimeEarnings',
            'referrals'
        ));
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
