<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function create()
    {
        $adminAddress = setting('admin_usdt_address', 'T_DEFAULT_ADDRESS_NOT_SET');
        return view('dashboard.deposits.create', compact('adminAddress'));
    }

    public function store(Request $request)
    {
        $min = setting('min_deposit', 25);
        $max = setting('max_deposit', 50000);
        $request->validate([
            'amount' => "required|numeric|min:{$min}|max:{$max}",
            'txid' => 'required|string|unique:deposits,txid'
        ]);

        Deposit::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'txid' => $request->txid,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard.history')->with('success', 'Deposit request submitted. Waiting for admin approval.');
    }
}
