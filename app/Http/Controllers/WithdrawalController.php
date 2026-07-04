<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function create()
    {
        $minWithdrawal = setting('min_withdrawal', 10);
        $maxWithdrawal = setting('max_withdrawal', 10000);
        $feePercent = setting('withdrawal_fee_percent', 5);
        $user = Auth::user();
        $wallet = $user->wallet;
        $totalAvailable = $wallet->roi_balance + $wallet->referral_balance; // Assuming deposit/bonus cannot be withdrawn

        return view('dashboard.withdrawals.create', compact('minWithdrawal', 'maxWithdrawal', 'feePercent', 'totalAvailable'));
    }

    public function store(Request $request)
    {
        $minWithdrawal = setting('min_withdrawal', 10);
        $maxWithdrawal = setting('max_withdrawal', 10000);
        $feePercent = setting('withdrawal_fee_percent', 5);
        
        $request->validate([
            'amount' => 'required|numeric|min:' . $minWithdrawal . '|max:' . $maxWithdrawal,
            'wallet_address' => 'required|string',
            'balance_type' => 'required|in:roi_balance,referral_balance'
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;
        $amount = $request->amount;
        $type = $request->balance_type;

        if ($wallet->$type < $amount) {
            return back()->withErrors(['amount' => 'Insufficient balance in selected wallet.']);
        }

        // Deduct balance
        $wallet->$type -= $amount;
        $wallet->save();

        // Calculate Fee
        $fee = ($amount * $feePercent) / 100;
        $netAmount = $amount - $fee;

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $netAmount,
            'wallet_address' => $request->wallet_address,
            'wallet_type' => $type,
            'status' => 'pending'
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'wallet_type' => $type,
            'status' => 'pending',
            'description' => 'Withdrawal request submitted. Fee: $' . number_format($fee, 2) . ', Net: $' . number_format($netAmount, 2),
            'reference_id' => $withdrawal->id
        ]);

        // Send email notifications to administrators
        $admins = \App\Models\User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            try {
                \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\AdminWithdrawalNotificationMail($withdrawal));
            } catch (\Exception $e) {
                // Fail-safe to keep platform stable if mail server credentials are incorrect/offline
            }
        }

        return redirect()->route('dashboard.history')->with('success', 'Withdrawal request submitted.');
    }
}
