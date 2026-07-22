@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center" data-aos="fade-down">
        <div class="col-lg-8">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>

            <div class="glass-card p-5">
                <h1 class="fw-bold mb-4 text-white border-bottom border-secondary border-opacity-50 pb-3"><i class="bi bi-wallet2 text-primary me-2"></i> {{ $title }}</h1>
                
                <div class="text-muted mb-5" style="line-height: 1.8;">
                    {!! nl2br(e($content)) !!}
                </div>

                <!-- 6-step Guide UI -->
                <h4 class="fw-bold text-white mb-4"><i class="bi bi-list-ol text-warning me-2"></i> Step-by-Step Deposit Guide</h4>
                <div class="position-relative border-start border-warning border-opacity-50 ms-3 ps-4">
                    <!-- Step 1 -->
                    <div class="position-relative pb-4">
                        <div class="position-absolute bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 28px; height: 28px; left: -38px; top: 0; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);">1</div>
                        <h5 class="text-white fw-bold mb-1">Create Account</h5>
                        <p class="text-muted small">Register a free account on FutureGrowth.tech and complete your email verification process to secure your dashboard.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="position-relative pb-4">
                        <div class="position-absolute bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 28px; height: 28px; left: -38px; top: 0; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);">2</div>
                        <h5 class="text-white fw-bold mb-1">Choose Investment</h5>
                        <p class="text-muted small">Select an investment tier (Starter, Growth, Professional, Elite) matching your capital targets.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="position-relative pb-4">
                        <div class="position-absolute bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 28px; height: 28px; left: -38px; top: 0; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);">3</div>
                        <h5 class="text-white fw-bold mb-1">Generate Deposit</h5>
                        <p class="text-muted small">Input deposit amount from the Deposit panel on your dashboard. Use either our automatic NOWPayments gateway or manual wallet options.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="position-relative pb-4">
                        <div class="position-absolute bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 28px; height: 28px; left: -38px; top: 0; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);">4</div>
                        <h5 class="text-white fw-bold mb-1">Send Payment</h5>
                        <p class="text-muted small">Transfer the exact USDT (TRC20/BEP20) amount to the generated payment address. Ensure accuracy to avoid blockchain delays.</p>
                    </div>

                    <!-- Step 5 -->
                    <div class="position-relative pb-4">
                        <div class="position-absolute bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 28px; height: 28px; left: -38px; top: 0; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);">5</div>
                        <h5 class="text-white fw-bold mb-1">Wait for Confirmation</h5>
                        <p class="text-muted small">Automated API payments are credited instantly, while manual payments are verified and approved by the system administration within minutes.</p>
                    </div>

                    <!-- Step 6 -->
                    <div class="position-relative">
                        <div class="position-absolute bg-warning rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 28px; height: 28px; left: -38px; top: 0; box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);">6</div>
                        <h5 class="text-white fw-bold mb-1">Investment Activated</h5>
                        <p class="text-muted small">Your capital is active! You will begin receiving daily ROI payouts dynamically credited straight into your wallet every 24 hours.</p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top border-secondary border-opacity-50 text-center">
                    <p class="text-muted small mb-0">Last updated: {{ date('F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
