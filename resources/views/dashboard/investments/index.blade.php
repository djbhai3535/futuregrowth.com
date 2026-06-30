@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Investment Plans</h2>
        <p class="text-muted">Choose a package to start earning daily ROI.</p>
    </div>
    <div class="text-end">
        <span class="text-muted small d-block">Available Balance</span>
        <h4 class="text-success fw-bold">${{ number_format($totalBalance, 2) }}</h4>
    </div>
</div>

<div class="row g-4 justify-content-center">
    @foreach($plans as $index => $plan)
    @php
        $isPopular = ($plan->name === 'Growth' || $plan->name === 'Professional');
        $borderClass = $isPopular ? 'border-primary border-opacity-50 shadow-lg' : 'border-secondary border-opacity-25';
        $btnClass = $isPopular ? 'btn-premium btn-pulse text-white' : 'btn-outline-primary text-white';
        $glowClass = $isPopular ? 'neon-glow-primary' : 'neon-glow-secondary';
        $icon = 'bi-rocket-takeoff-fill';
        if ($plan->name === 'Starter') $icon = 'bi-lightning-charge-fill';
        if ($plan->name === 'Professional') $icon = 'bi-gem';
        if ($plan->name === 'Elite') $icon = 'bi-shield-shaded';
    @endphp
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100 d-flex flex-column position-relative overflow-hidden hover-scale {{ $borderClass }} {{ $glowClass }}" style="background: linear-gradient(135deg, rgba(17, 24, 39, 0.4) 0%, rgba(9, 9, 11, 0.6) 100%); border-radius: 1.25rem;">
            @if($isPopular)
                <div class="position-absolute top-0 end-0 bg-primary text-white fw-bold px-3 py-1 rounded-bl" style="border-bottom-left-radius: 1rem; font-size: 0.75rem;">POPULAR</div>
            @endif
            <div class="p-4 text-center border-bottom border-secondary border-opacity-50">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex mb-3">
                    <i class="bi {{ $icon }} fs-3 text-primary"></i>
                </div>
                <h4 class="fw-bold text-white mb-0">{{ $plan->name }}</h4>
            </div>
            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>Min Deposit</span>
                        <span class="fw-bold text-white">${{ number_format($plan->min_amount, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>Max Deposit</span>
                        <span class="fw-bold text-white">${{ number_format($plan->max_amount, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 p-2 rounded bg-info bg-opacity-10 border border-info border-opacity-25 text-info small">
                        <span>Daily ROI</span>
                        <span class="fw-bold">{{ $plan->min_roi }}% - {{ $plan->max_roi }}%</span>
                    </div>
                </div>

                <form action="{{ route('dashboard.investments.store') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-dark border-secondary text-muted">$</span>
                        <input type="number" step="0.01" min="{{ $plan->min_amount }}" max="{{ $plan->max_amount }}" name="amount" class="form-control bg-transparent border-secondary text-white focus-ring" placeholder="Amount" required>
                    </div>
                    <button class="btn {{ $btnClass }} w-100 fw-bold py-2">Invest Now</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
    .hover-scale { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .hover-scale:hover { transform: scale(1.03) translateY(-5px) !important; }
    .neon-glow-primary { box-shadow: 0 0 20px rgba(59, 130, 246, 0.08); }
    .neon-glow-primary:hover { box-shadow: 0 0 35px rgba(59, 130, 246, 0.25); }
    .neon-glow-secondary { box-shadow: 0 0 20px rgba(139, 92, 246, 0.05); }
    .neon-glow-secondary:hover { box-shadow: 0 0 35px rgba(139, 92, 246, 0.18); }
</style>
@endsection
