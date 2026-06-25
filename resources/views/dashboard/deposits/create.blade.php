@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Make a Deposit</h2>
    <p class="text-muted">Add funds to your account via USDT (TRC20).</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="glass-card p-4">
            <div class="alert border-info bg-info bg-opacity-10 text-info mb-4">
                <i class="bi bi-info-circle-fill me-2"></i> {{ setting('deposit_instructions', 'Only send USDT (TRC20) to this address.') }}
            </div>

            <ul class="nav nav-pills mb-4 nav-fill" id="depositTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active bg-transparent border border-secondary text-white fw-bold py-2 custom-hover-pill" id="auto-tab" data-bs-toggle="pill" data-bs-target="#auto-deposit" type="button" role="tab"><i class="bi bi-cpu text-info me-2"></i> Instant Auto Deposit</button>
                </li>
                <li class="nav-item ms-2" role="presentation">
                    <button class="nav-link bg-transparent border border-secondary text-white fw-bold py-2 custom-hover-pill" id="manual-tab" data-bs-toggle="pill" data-bs-target="#manual-deposit" type="button" role="tab"><i class="bi bi-wallet2 text-warning me-2"></i> Manual Deposit</button>
                </li>
            </ul>

            <div class="tab-content" id="depositTabContent">
                <!-- Automatic Deposit -->
                <div class="tab-pane fade show active" id="auto-deposit" role="tabpanel">
                    <form action="{{ route('dashboard.deposits.nowpayments') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold">Deposit Amount (USD) <span class="text-warning fw-normal">(Min: ${{ setting('min_deposit', 25) }} | Max: ${{ setting('max_deposit', 50000) }})</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-muted">$</span>
                                <input type="number" step="0.01" min="{{ setting('min_deposit', 25) }}" max="{{ setting('max_deposit', 50000) }}" name="amount" class="form-control bg-transparent border-secondary text-white focus-ring" placeholder="Enter amount to pay..." required>
                            </div>
                            <small class="text-muted mt-2 d-block"><i class="bi bi-shield-check text-info me-1"></i> Checkout securely using NOWPayments gateway. Instant confirmation.</small>
                        </div>
                        <button type="submit" class="btn btn-premium w-100 py-2 fw-bold"><i class="bi bi-lightning-charge me-1"></i> Pay with NOWPayments</button>
                    </form>
                </div>

                <!-- Manual Deposit -->
                <div class="tab-pane fade" id="manual-deposit" role="tabpanel">
                    <div class="mb-4 text-center p-4 rounded" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.2);">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $adminAddress }}&color=fff&bgcolor=1e293b" class="img-fluid rounded mb-3" alt="QR Code">
                        <h6 class="text-muted text-uppercase small mb-1">Company USDT (TRC20) Address</h6>
                        <div class="input-group">
                            <input type="text" class="form-control bg-dark border-secondary text-white text-center font-monospace" value="{{ $adminAddress }}" id="walletAddr" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard(document.getElementById('walletAddr').value, this)"><i class="bi bi-copy"></i></button>
                        </div>
                    </div>

                    <form action="{{ route('dashboard.deposits.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Deposit Amount (USD) <span class="text-warning fw-normal">(Min: ${{ setting('min_deposit', 25) }} | Max: ${{ setting('max_deposit', 50000) }})</span></label>
                            <input type="number" step="0.01" min="{{ setting('min_deposit', 25) }}" max="{{ setting('max_deposit', 50000) }}" name="amount" class="form-control bg-transparent border-secondary text-white focus-ring" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold">Transaction Hash (TXID)</label>
                            <input type="text" name="txid" class="form-control bg-transparent border-secondary text-white focus-ring" placeholder="Enter the 64-character hash" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold"><i class="bi bi-check2-circle me-1"></i> Submit For Approval</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .custom-hover-pill:hover { background: rgba(255,255,255,0.05) !important; border-color: var(--primary-color) !important; }
</style>
@endsection
