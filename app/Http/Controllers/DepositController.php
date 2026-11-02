<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Show the manual deposit submission form.
     */
    public function create()
    {
        $trc20Address = setting('admin_usdt_trc20_address', setting('admin_usdt_address', 'ADDRESS_NOT_CONFIGURED'));
        $bep20Address = setting('admin_usdt_bep20_address', setting('admin_usdt_address', 'ADDRESS_NOT_CONFIGURED'));

        $trc20Enabled = (bool) setting('enable_trc20_deposits', true);
        $bep20Enabled = (bool) setting('enable_bep20_deposits', true);

        $minDeposit = setting('min_deposit', 25);
        $maxDeposit = setting('max_deposit', 50000);

        return view('dashboard.deposits.create', compact(
            'trc20Address',
            'bep20Address',
            'trc20Enabled',
            'bep20Enabled',
            'minDeposit',
            'maxDeposit'
        ));
    }

    /**
     * Store a new manual deposit request.
     * Balance is NOT credited here — only after admin approval.
     */
    public function store(Request $request)
    {
        $min = setting('min_deposit', 25);
        $max = setting('max_deposit', 50000);

        $request->validate([
            'network' => 'required|in:trc20,bep20',
            'amount'  => "required|numeric|min:{$min}|max:{$max}",
            'txid'    => [
                'required',
                'string',
                'min:10',
                'max:255',
                'unique:deposits,txid',
            ],
        ], [
            'txid.unique' => 'This Transaction ID has already been submitted. If you believe this is an error, contact support.',
        ]);

        Deposit::create([
            'user_id'  => Auth::id(),
            'amount'   => $request->amount,
            'txid'     => trim($request->txid),
            'network'  => $request->network,
            'credited' => false,
            'status'   => 'pending',
        ]);

        \App\Models\ActivityLog::create([
            'user_id'    => Auth::id(),
            'action'     => 'Submitted manual deposit request. Network: ' . strtoupper($request->network) . ' | Amount: $' . $request->amount . ' | TXID: ' . $request->txid,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('dashboard.history')
            ->with('success', 'Deposit request submitted successfully! An admin will review your TXID and credit your account after verification.');
    }
}
