@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Withdraw Funds</h2>
    <p class="text-muted">Request a payout to your external USDT wallet.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="glass-card p-4">
            <div class="mb-4 p-3 rounded border border-warning border-opacity-25 bg-warning bg-opacity-10 text-warning d-flex justify-content-between align-items-center">
                <div>
                    <strong class="d-block small text-uppercase"><i class="bi bi-clock-history me-1"></i> Processing Policy</strong>
                    <span class="small opacity-75">Processed after admin approval.</span>
                </div>
                <div class="text-end">
                    <span class="small d-block text-muted">Estimated Time</span>
                    <strong class="text-warning">3 Days</strong>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4 p-3 rounded" style="background: rgba(255,255,255,0.05);">
                <span class="text-muted">Total Available:</span>
                <span class="fw-bold text-success fs-5">${{ number_format($totalAvailable, 2) }}</span>
            </div>

            <form action="{{ route('dashboard.withdrawals.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Withdraw From</label>
                    <select name="balance_type" class="form-select bg-dark border-secondary text-white focus-ring" required>
                        <option value="roi_balance">ROI Wallet (${{ number_format(auth()->user()->wallet->roi_balance, 2) }})</option>
                        <option value="referral_balance">Referral Wallet (${{ number_format(auth()->user()->wallet->referral_balance, 2) }})</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Amount (USD) <span class="text-warning fw-normal">(Min: ${{ $minWithdrawal }} | Max: ${{ $maxWithdrawal }} | Fee: {{ $feePercent }}%)</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted">$</span>
                        <input type="number" step="0.01" min="{{ $minWithdrawal }}" max="{{ $maxWithdrawal }}" name="amount" class="form-control bg-transparent border-secondary text-white focus-ring" placeholder="Enter amount..." required>
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i> A fee of {{ $feePercent }}% will be deducted from your payout request.</small>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Your USDT (TRC20) Address</label>
                    <input type="text" name="wallet_address" class="form-control bg-transparent border-secondary text-white focus-ring font-monospace" required>
                </div>
                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark"><i class="bi bi-arrow-up-circle me-1"></i> Request Withdrawal</button>
            </form>
        </div>
    </div>
</div>
@endsection
