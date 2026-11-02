@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-arrow-down-circle-fill me-2"></i> Deposit Funds</h2>
            <p class="text-muted small">Send USDT to the company wallet below, then submit your Transaction ID (TXID) for admin verification.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left me-1"></i> Dashboard</a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- LEFT: Network selector & Wallet address card --}}
        <div class="col-lg-5" data-aos="fade-right">

            {{-- Step 1: Choose Network --}}
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3 text-info"><span class="badge bg-info text-dark me-2">1</span> Choose Network</h5>

                @if(!$trc20Enabled && !$bep20Enabled)
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No deposit networks are currently active. Please contact support.
                    </div>
                @else
                    <div class="d-flex gap-3">
                        @if($trc20Enabled)
                        <button type="button" class="btn btn-outline-success flex-grow-1 py-3 fw-bold network-btn active" data-network="trc20"
                            onclick="selectNetwork('trc20')">
                            <i class="bi bi-circle-fill me-1" style="color:#E8B84B;"></i> USDT <span class="badge bg-warning text-dark ms-1">TRC20</span>
                        </button>
                        @endif
                        @if($bep20Enabled)
                        <button type="button" class="btn btn-outline-warning flex-grow-1 py-3 fw-bold network-btn {{ !$trc20Enabled ? 'active' : '' }}" data-network="bep20"
                            onclick="selectNetwork('bep20')">
                            <i class="bi bi-circle-fill me-1" style="color:#F0B90B;"></i> USDT <span class="badge bg-warning text-dark ms-1">BEP20</span>
                        </button>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Step 2: Wallet Addresses --}}
            @if($trc20Enabled)
            <div class="glass-card p-4 mb-4 wallet-panel" id="wallet-trc20">
                <h5 class="fw-bold mb-3 text-success"><span class="badge bg-success me-2">2</span> Send USDT (TRC20) to this address</h5>
                <div class="text-center mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($trc20Address) }}&color=fff&bgcolor=1e293b"
                         class="img-fluid rounded" alt="TRC20 QR Code" style="max-width:160px;">
                    <p class="text-muted small mt-2 mb-0"><i class="bi bi-shield-check text-success me-1"></i> Scan with your USDT wallet app</p>
                </div>
                <div class="input-group mb-2">
                    <input type="text" class="form-control bg-dark border-secondary text-white text-center font-monospace small"
                           id="addr-trc20" value="{{ $trc20Address }}" readonly>
                    <button class="btn btn-outline-success" type="button" onclick="copyAddr('addr-trc20', this)" title="Copy address">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
                <div class="alert alert-warning py-2 mb-0 small">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Send only USDT on the TRON (TRC20) network.</strong> Sending any other coin or network will result in permanent loss.
                </div>
            </div>
            @endif

            @if($bep20Enabled)
            <div class="glass-card p-4 mb-4 wallet-panel {{ $trc20Enabled ? 'd-none' : '' }}" id="wallet-bep20">
                <h5 class="fw-bold mb-3 text-warning"><span class="badge bg-warning text-dark me-2">2</span> Send USDT (BEP20) to this address</h5>
                <div class="text-center mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($bep20Address) }}&color=fff&bgcolor=1e293b"
                         class="img-fluid rounded" alt="BEP20 QR Code" style="max-width:160px;">
                    <p class="text-muted small mt-2 mb-0"><i class="bi bi-shield-check text-warning me-1"></i> Scan with your USDT wallet app</p>
                </div>
                <div class="input-group mb-2">
                    <input type="text" class="form-control bg-dark border-secondary text-white text-center font-monospace small"
                           id="addr-bep20" value="{{ $bep20Address }}" readonly>
                    <button class="btn btn-outline-warning" type="button" onclick="copyAddr('addr-bep20', this)" title="Copy address">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
                <div class="alert alert-warning py-2 mb-0 small">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Send only USDT on the BNB Smart Chain (BEP20) network.</strong> Sending any other coin or network will result in permanent loss.
                </div>
            </div>
            @endif

        </div>

        {{-- RIGHT: Deposit Submission Form --}}
        <div class="col-lg-7" data-aos="fade-left">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-1 text-white"><span class="badge bg-primary me-2">3</span> Submit Your Deposit</h5>
                <p class="text-muted small mb-4">After sending USDT, fill in the form below. An admin will verify your TXID and credit your account.</p>

                @if($trc20Enabled || $bep20Enabled)
                <form action="{{ route('dashboard.deposits.store') }}" method="POST">
                    @csrf

                    {{-- Hidden network input (updated by JS) --}}
                    <input type="hidden" name="network" id="selected-network"
                           value="{{ $trc20Enabled ? 'trc20' : 'bep20' }}">

                    {{-- Selected network display --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">Selected Network</label>
                        <div class="form-control bg-dark border-secondary text-white d-flex align-items-center gap-2" id="network-display">
                            @if($trc20Enabled)
                                <i class="bi bi-circle-fill text-warning"></i>
                                <span id="network-label">USDT (TRC20)</span>
                            @else
                                <i class="bi bi-circle-fill text-warning"></i>
                                <span id="network-label">USDT (BEP20)</span>
                            @endif
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase fw-bold">
                            Deposit Amount (USDT)
                            <span class="text-warning fw-normal">Min: ${{ number_format($minDeposit, 0) }} | Max: ${{ number_format($maxDeposit, 0) }}</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted">$</span>
                            <input type="number" step="0.01" min="{{ $minDeposit }}" max="{{ $maxDeposit }}"
                                   name="amount" class="form-control bg-transparent border-secondary text-white"
                                   placeholder="e.g. 100.00" value="{{ old('amount') }}" required>
                        </div>
                        <small class="text-muted">Enter the exact USDT amount you sent.</small>
                    </div>

                    {{-- TXID --}}
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Transaction ID (TXID / Hash)</label>
                        <input type="text" name="txid"
                               class="form-control bg-transparent border-secondary text-white font-monospace"
                               placeholder="Paste your full transaction hash here..."
                               value="{{ old('txid') }}" required>
                        <small class="text-muted">
                            Find your TXID in your wallet's transaction history. It is a long alphanumeric string.
                        </small>
                    </div>

                    {{-- Info box --}}
                    <div class="alert alert-info py-2 mb-4 small">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Processing time:</strong> Deposits are typically reviewed and approved within 1–24 hours.
                        Each TXID can only be submitted once. Do not submit the same TXID twice.
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold">
                        <i class="bi bi-check2-circle me-2"></i> Submit Deposit for Verification
                    </button>
                </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Deposits are currently disabled. Please contact support.
                    </div>
                @endif

            </div>

            {{-- Tips card --}}
            <div class="glass-card p-4 mt-4" data-aos="fade-up">
                <h6 class="fw-bold text-muted mb-3"><i class="bi bi-lightbulb-fill text-warning me-2"></i> How It Works</h6>
                <ol class="text-muted small ps-3 mb-0">
                    <li class="mb-2">Choose your preferred network (TRC20 or BEP20).</li>
                    <li class="mb-2">Send the exact USDT amount to the company wallet address shown.</li>
                    <li class="mb-2">Copy your TXID from your wallet and paste it into the form above.</li>
                    <li class="mb-2">Submit the form — our team will verify and approve it within 24 hours.</li>
                    <li>Once approved, your deposit balance will be credited automatically.</li>
                </ol>
            </div>
        </div>

    </div>
</div>

<script>
function selectNetwork(network) {
    // Update hidden input
    document.getElementById('selected-network').value = network;

    // Update display label
    const labels = { trc20: 'USDT (TRC20)', bep20: 'USDT (BEP20)' };
    document.getElementById('network-label').textContent = labels[network] || network.toUpperCase();

    // Toggle wallet panels
    document.querySelectorAll('.wallet-panel').forEach(el => el.classList.add('d-none'));
    const panel = document.getElementById('wallet-' + network);
    if (panel) panel.classList.remove('d-none');

    // Toggle button active state
    document.querySelectorAll('.network-btn').forEach(btn => {
        btn.classList.remove('active', 'btn-success', 'btn-warning');
        btn.classList.add(btn.dataset.network === 'trc20' ? 'btn-outline-success' : 'btn-outline-warning');
    });
    const activeBtn = document.querySelector('.network-btn[data-network="' + network + '"]');
    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.classList.remove('btn-outline-success', 'btn-outline-warning');
        activeBtn.classList.add(network === 'trc20' ? 'btn-success' : 'btn-warning');
    }
}

function copyAddr(inputId, btn) {
    const val = document.getElementById(inputId).value;
    navigator.clipboard.writeText(val).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        btn.disabled = true;
        setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 2000);
    });
}

// Init first network
document.addEventListener('DOMContentLoaded', () => {
    const first = document.querySelector('.network-btn');
    if (first) selectNetwork(first.dataset.network);
});
</script>

<style>
.network-btn.active { box-shadow: 0 0 0 2px rgba(255,255,255,0.3); }
</style>
@endsection
