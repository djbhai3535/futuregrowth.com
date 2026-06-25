<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Investment;
use App\Services\ReferralService;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function index()
    {
        $plans = Plan::where('status', 'active')->get();
        $wallet = Auth::user()->wallet;
        $totalBalance = $wallet->deposit_balance + $wallet->bonus_balance; // Allow investing from deposit or bonus
        
        return view('dashboard.investments.index', compact('plans', 'totalBalance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric'
        ]);

        $plan = Plan::find($request->plan_id);
        $amount = $request->amount;

        if ($amount < $plan->min_amount || $amount > $plan->max_amount) {
            return back()->withErrors(['amount' => 'Amount must be between $' . $plan->min_amount . ' and $' . $plan->max_amount]);
        }

        $user = Auth::user();
        $wallet = $user->wallet;

        // Deduct from deposit balance first, then bonus
        if ($wallet->deposit_balance >= $amount) {
            $wallet->deposit_balance -= $amount;
        } elseif (($wallet->deposit_balance + $wallet->bonus_balance) >= $amount) {
            $remaining = $amount - $wallet->deposit_balance;
            $wallet->deposit_balance = 0;
            $wallet->bonus_balance -= $remaining;
        } else {
            return back()->withErrors(['amount' => 'Insufficient funds.']);
        }

        $wallet->save();

        // Calculate dynamic ROI percent for this user
        $roiPercent = rand($plan->min_roi * 10, $plan->max_roi * 10) / 10;

        Investment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount' => $amount,
            'daily_roi_percent' => $roiPercent,
            'status' => 'active'
        ]);

        // Trigger referral multi-level commissions!
        $this->referralService->distributeCommission($user, $amount);

        return redirect()->route('dashboard')->with('success', 'Investment activated successfully! Commissions distributed to team.');
    }
}
