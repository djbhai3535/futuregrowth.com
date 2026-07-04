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
        $colors = [
            'Starter' => ['icon' => 'bi-lightning-charge-fill', 'gradient' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)', 'bg' => 'rgba(59, 130, 246, 0.1)', 'color' => '#3b82f6'],
            'Growth' => ['icon' => 'bi-rocket-takeoff-fill', 'gradient' => 'linear-gradient(135deg, #a855f7 0%, #7e22ce 100%)', 'bg' => 'rgba(168, 85, 247, 0.1)', 'color' => '#a855f7'],
            'Professional' => ['icon' => 'bi-gem', 'gradient' => 'linear-gradient(135deg, #ec4899 0%, #be185d 100%)', 'bg' => 'rgba(236, 72, 153, 0.1)', 'color' => '#ec4899'],
            'Elite' => ['icon' => 'bi-shield-shaded', 'gradient' => 'linear-gradient(135deg, #10b981 0%, #047857 100%)', 'bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#10b981']
        ];
        $config = $colors[$plan->name] ?? ['icon' => 'bi-box-fill', 'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)', 'bg' => 'rgba(245, 158, 11, 0.1)', 'color' => '#f59e0b'];
        
        $isPopular = ($plan->name === 'Growth' || $plan->name === 'Professional');
        $borderClass = $isPopular ? 'border-primary border-opacity-50' : 'border-secondary border-opacity-25';
    @endphp
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
        <div class="glass-card h-100 d-flex flex-column position-relative overflow-hidden hover-scale {{ $borderClass }}" style="border-radius: 1.25rem;">
            @if($isPopular)
                <div class="position-absolute top-0 end-0 text-white fw-bold px-3 py-1 rounded-bl" style="border-bottom-left-radius: 1rem; font-size: 0.7rem; background: {{ $config['gradient'] }}; letter-spacing: 1px;">POPULAR</div>
            @endif
            <div class="p-4 text-center border-bottom border-secondary border-opacity-20 position-relative">
                <!-- Glowing Circle Background -->
                <div class="position-absolute start-50 top-50 translate-middle rounded-circle opacity-10" style="width: 100px; height: 100px; background: {{ $config['color'] }}; filter: blur(25px);"></div>
                
                <div class="p-3 rounded-circle d-inline-flex mb-3" style="background: {{ $config['bg'] }}; color: {{ $config['color'] }}; border: 1px solid rgba(255,255,255,0.05);">
                    <i class="bi {{ $config['icon'] }} fs-3"></i>
                </div>
                <h4 class="fw-bold text-white mb-0" style="letter-spacing: 0.5px;">{{ $plan->name }}</h4>
            </div>
            
            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>Min Deposit</span>
                        <strong class="text-white">${{ number_format($plan->min_amount, 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>Max Deposit</span>
                        <strong class="text-white">${{ number_format($plan->max_amount, 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-4 p-3 rounded text-center small border border-opacity-20" style="background: {{ $config['bg'] }}; border-color: {{ $config['color'] }}; color: {{ $config['color'] }}">
                        <span class="fw-bold">Daily ROI Yield</span>
                        <strong class="fs-6">{{ $plan->min_roi }}% - {{ $plan->max_roi }}%</strong>
                    </div>
                </div>

                <form action="{{ route('dashboard.investments.store') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <div class="input-group mb-3 shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-muted">$</span>
                        <input type="number" step="0.01" min="{{ $plan->min_amount }}" max="{{ $plan->max_amount }}" name="amount" class="form-control bg-transparent border-secondary text-white focus-ring" placeholder="Enter Amount" required>
                    </div>
                    <button class="btn w-100 fw-bold py-2 text-white" style="background: {{ $config['gradient'] }}; border: none; border-radius: 0.75rem; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='none';">Invest Now</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
    .hover-scale { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .hover-scale:hover { transform: scale(1.03) translateY(-5px) !important; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4), 0 0 15px rgba(255,255,255,0.05) !important; }
</style>
@endsection
