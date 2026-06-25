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

<div class="row g-4">
    @foreach($plans as $plan)
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100 d-flex flex-column position-relative overflow-hidden">
            @if($plan->name == 'VIP')
                <div class="position-absolute top-0 end-0 bg-warning text-dark fw-bold px-3 py-1 rounded-bl" style="border-bottom-left-radius: 1rem;">POPULAR</div>
            @endif
            <div class="p-4 text-center border-bottom border-secondary border-opacity-50">
                <h4 class="fw-bold text-white mb-0">{{ $plan->name }}</h4>
            </div>
            <div class="p-4 flex-grow-1">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Min Deposit</span>
                    <span class="fw-bold">${{ number_format($plan->min_amount, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Max Deposit</span>
                    <span class="fw-bold">${{ number_format($plan->max_amount, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4 p-2 rounded bg-info bg-opacity-10 border border-info border-opacity-25">
                    <span class="text-info">Daily ROI</span>
                    <span class="fw-bold text-info">{{ $plan->min_roi }}% - {{ $plan->max_roi }}%</span>
                </div>

                <form action="{{ route('dashboard.investments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-dark border-secondary text-muted">$</span>
                        <input type="number" step="0.01" min="{{ $plan->min_amount }}" max="{{ $plan->max_amount }}" name="amount" class="form-control bg-transparent border-secondary text-white focus-ring" placeholder="Amount" required>
                    </div>
                    <button class="btn btn-primary w-100 fw-bold py-2">Invest Now</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
