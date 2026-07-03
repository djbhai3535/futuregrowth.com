@extends('layouts.app')

@section('content')
<style>
    /* Marquee Banner */
    .promo-banner {
        overflow: hidden;
        white-space: nowrap;
        background: linear-gradient(90deg, rgba(59,130,246,0.1), rgba(139,92,246,0.1));
        border: 1px solid rgba(59,130,246,0.2);
        padding: 10px 0;
        border-radius: 12px;
        position: relative;
    }
    .marquee-content {
        display: inline-block;
        animation: marquee 25s linear infinite;
        font-weight: 500;
        font-size: 0.9rem;
    }
    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .marquee-item { display: inline-block; margin-right: 50px; }
    
    /* Premium Progress */
    .progress-premium {
        height: 14px;
        background: rgba(0,0,0,0.3);
        border-radius: 10px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
        overflow: visible;
        position: relative;
    }
    .progress-bar-premium {
        background: linear-gradient(90deg, #3b82f6, #06b6d4, #10b981);
        background-size: 200% 200%;
        border-radius: 10px;
        position: relative;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.6);
        animation: gradient-shift 3s ease infinite;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .progress-bar-completed {
        background: linear-gradient(90deg, #eab308, #fbbf24);
        box-shadow: 0 0 15px rgba(234, 179, 8, 0.6);
    }
    .progress-bar-premium::after {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.2) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.2) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
        animation: progress-stripes 1s linear infinite;
        border-radius: 10px;
    }
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes progress-stripes {
        from { background-position: 1rem 0; }
        to { background-position: 0 0; }
    }
    
    .wallet-card-sm {
        transition: all 0.3s;
        cursor: default;
    }
    .wallet-card-sm:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.1) !important;
    }
</style>

<!-- Tech Particle Background for Dashboard -->
<div id="tsparticles" class="position-fixed top-0 start-0 w-100 h-100" style="z-index: -2; opacity: 0.5;"></div>

<!-- Promotional Banner -->
<div class="promo-banner mb-4 text-white" data-aos="fade-down">
    <div class="marquee-content">
        <span class="marquee-item"><i class="bi bi-gift text-warning me-1"></i> {{ setting('promo_banner_1', 'Free $' . setting('signup_bonus', 7) . ' Signup Bonus Available!') }}</span>
        <span class="marquee-item"><i class="bi bi-rocket-takeoff text-primary me-1"></i> {{ setting('promo_banner_2', 'Build Your Team & Earn up to 10 Levels of Rewards!') }}</span>
        <span class="marquee-item"><i class="bi bi-graph-up-arrow text-success me-1"></i> {{ setting('promo_banner_3', 'Daily ROI Distributed Automatically Every 24 Hours.') }}</span>
        <span class="marquee-item"><i class="bi bi-whatsapp text-success me-1"></i> {{ setting('promo_banner_4', 'Join our Official WhatsApp Community today!') }}</span>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
    <h2 class="fw-bold mb-0">Dashboard Overview</h2>
    <div class="d-flex align-items-center gap-2">
        @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
            <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-whatsapp rounded-pill px-4 fw-bold whatsapp-glow d-none d-md-inline-block">
                <i class="bi bi-whatsapp me-2"></i> Join Community
            </a>
        @endif
        <a href="{{ route('dashboard.investments') }}" class="btn btn-premium px-4"><i class="bi bi-rocket-takeoff me-2"></i> Invest Now</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Row 1: Core Financials -->
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative neon-glow-primary overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Wallet Balance</p>
            <h3 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalBalance }}">0</span></h3>
            <i class="bi bi-wallet2 position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-primary opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Deposits</p>
            <h3 class="text-success fw-bold mb-0">$<span class="countup" data-val="{{ $totalDeposits }}">0</span></h3>
            <i class="bi bi-arrow-down-circle position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-success opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Withdrawals</p>
            <h3 class="text-danger fw-bold mb-0">$<span class="countup" data-val="{{ $totalWithdrawals }}">0</span></h3>
            <i class="bi bi-arrow-up-circle position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-danger opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Earnings</p>
            <h3 class="text-warning fw-bold mb-0">$<span class="countup" data-val="{{ $totalEarnings }}">0</span></h3>
            <i class="bi bi-cash-stack position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-warning opacity-25"></i>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Row 2: Investment & ROI metrics -->
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Active Investments</p>
            <h4 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $activeInvestmentsSum }}">0</span></h4>
            <i class="bi bi-activity position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-info opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Completed Investments</p>
            <h4 class="text-success fw-bold mb-0"><span class="countup" data-val="{{ $completedInvestmentsCount }}">0</span> <span class="fs-6 text-muted">(${{ number_format($completedInvestmentsSum, 0) }})</span></h4>
            <i class="bi bi-patch-check position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-success opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">ROI Earned</p>
            <h4 class="text-warning fw-bold mb-0">$<span class="countup" data-val="{{ $roiEarned }}">0</span></h4>
            <i class="bi bi-graph-up-arrow position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-warning opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Referral Commission</p>
            <h4 class="text-primary fw-bold mb-0">$<span class="countup" data-val="{{ $referralCommission }}">0</span></h4>
            <i class="bi bi-diagram-3 position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-primary opacity-25"></i>
        </div>
    </div>
</div>

<!-- Row 3: Team Metrics & Share Link -->
<div class="row g-3 mb-4">
    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Direct Referrals</p>
            <h4 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $directReferralsCount }}">0</span></h4>
            <i class="bi bi-person-plus position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-info opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Team Size (10 Levels)</p>
            <h4 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $teamSize }}">0</span></h4>
            <i class="bi bi-people position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-warning opacity-25"></i>
        </div>
    </div>
</div>

<!-- Investment Analytics -->
<div class="row g-4 mb-4" data-aos="fade-up">
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100 border-primary border-opacity-25 tech-bg-container">
            <!-- Subtle Tech Background Effects -->
            <div class="tech-grid-overlay"></div>
            
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 position-relative gap-2">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Account Analytics Matrix</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" onclick="switchUserChart('invs')">Investments</button>
                    <button type="button" class="btn btn-outline-primary" onclick="switchUserChart('deps')">Deposits</button>
                    <button type="button" class="btn btn-outline-primary" onclick="switchUserChart('withs')">Withdrawals</button>
                    <button type="button" class="btn btn-outline-primary" onclick="switchUserChart('rois')">ROI History</button>
                    <button type="button" class="btn btn-outline-primary" onclick="switchUserChart('refs')">Referral Growth</button>
                </div>
            </div>
            <div style="height: 280px; position: relative;">
                <canvas id="userAnalyticsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <h5 class="fw-bold mb-4 text-white"><i class="bi bi-pie-chart-fill text-info me-2"></i> Portfolio Status</h5>
            <div class="mb-3">
                <div class="d-flex justify-content-between text-muted small mb-1"><span>Total Active Investments</span> <span class="text-white fw-bold">${{ number_format($activeInvestmentsSum, 2) }}</span></div>
                <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" style="width: {{ $activeInvestmentsSum > 0 ? 100 : 0 }}%"></div></div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between text-muted small mb-1"><span>Completed Investments</span> <span class="text-white fw-bold">${{ number_format($completedInvestmentsSum, 2) }}</span></div>
                <div class="progress" style="height: 6px;"><div class="progress-bar bg-warning" style="width: {{ $completedInvestmentsSum > 0 ? 100 : 0 }}%"></div></div>
            </div>
            <div class="p-3 mt-3 rounded bg-dark border border-success border-opacity-25">
                <p class="text-success small fw-bold mb-1"><i class="bi bi-graph-up-arrow me-1"></i> Total ROI Earned</p>
                <h4 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $roiEarned }}">0</span></h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Wallets Breakdown -->
    <div class="col-lg-8" data-aos="fade-right">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-safe2 text-primary me-2"></i> My Wallets</h5>
                <div>
                    <a href="{{ route('dashboard.deposits') }}" class="btn btn-sm btn-outline-success me-2 fw-bold"><i class="bi bi-arrow-down-circle"></i> Deposit</a>
                    <a href="{{ route('dashboard.withdrawals') }}" class="btn btn-sm btn-outline-light fw-bold"><i class="bi bi-arrow-up-circle"></i> Withdraw</a>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="wallet-card-sm p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Deposit Wallet</p>
                                <h4 class="mb-0 fw-bold text-white">$<span class="countup" data-val="{{ $wallet->deposit_balance }}">0</span></h4>
                            </div>
                            <i class="bi bi-box-arrow-in-down fs-1 text-muted opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="wallet-card-sm p-3 rounded" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-success small mb-1">ROI Wallet</p>
                                <h4 class="text-success mb-0 fw-bold">$<span class="countup" data-val="{{ $wallet->roi_balance }}">0</span></h4>
                            </div>
                            <i class="bi bi-graph-up fs-1 text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="wallet-card-sm p-3 rounded" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-primary small mb-1">Referral Wallet</p>
                                <h4 class="text-primary mb-0 fw-bold">$<span class="countup" data-val="{{ $wallet->referral_balance }}">0</span></h4>
                            </div>
                            <i class="bi bi-people fs-1 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="wallet-card-sm p-3 rounded" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-warning small mb-1">Bonus Wallet (Locked)</p>
                                <h4 class="text-warning mb-0 fw-bold">$<span class="countup" data-val="{{ $wallet->bonus_balance }}">0</span></h4>
                            </div>
                            <i class="bi bi-lock fs-1 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3X Progress -->
    <div class="col-lg-4" data-aos="fade-left">
        <div class="glass-card p-4 h-100 position-relative border-primary border-opacity-25 neon-glow-primary">
            <h5 class="fw-bold mb-4 text-white"><i class="bi bi-rocket-takeoff text-primary me-2"></i> Investment Progress (3X)</h5>
            @forelse($activeInvestmentsList as $inv)
                @php
                    $multiplier = setting('enable_return_multiplier', 1) ? setting('investment_return_multiplier', 3) : 999;
                    $maxReturn = $inv->amount * $multiplier;
                    $percentage = min(100, ($inv->total_earned / $maxReturn) * 100);
                @endphp
                <div class="mb-4">
                    <div class="d-flex justify-content-between small fw-bold mb-2">
                        <span class="text-white">{{ $inv->plan->name }} (${{ number_format($inv->amount, 0) }})</span>
                        @if($inv->status == 'completed')
                            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Completed</span>
                        @elseif($inv->status == 'pending')
                            <span class="badge bg-secondary text-white"><i class="bi bi-clock me-1"></i> Pending</span>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-50"><i class="bi bi-activity me-1"></i> Active</span>
                        @endif
                    </div>
                    <div class="progress progress-premium mb-2">
                        <div class="progress-bar-premium {{ $percentage >= 100 ? 'progress-bar-completed' : '' }}" role="progressbar" style="width: 0%" data-target-width="{{ $percentage }}%">
                            <span class="position-absolute end-0 me-2 text-white small fw-bold" style="line-height: 14px; text-shadow: 0 0 4px rgba(0,0,0,0.8);">{{ number_format($percentage, 1) }}%</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Earned: <span class="text-white">${{ number_format($inv->total_earned, 2) }}</span></small>
                        <small class="text-muted">Max: <span class="text-white">${{ number_format($maxReturn, 0) }}</span></small>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted mb-2 d-block"></i>
                    <p class="text-muted small">No active investments found.</p>
                    <a href="{{ route('dashboard.investments') }}" class="btn btn-sm btn-premium mt-2">Start Investing</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Referral System -->
    <div class="col-lg-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-3 text-white"><i class="bi bi-share-fill text-info me-2"></i> Referral Program</h5>
            <p class="text-muted small mb-3">Share your link to earn up to 10 levels of commissions instantly!</p>
            
            <div class="input-group mb-3 shadow-sm">
                <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-link-45deg"></i></span>
                <input type="text" class="form-control bg-dark border-secondary text-white" value="{{ route('register') }}?ref={{ auth()->user()->referral_code }}" id="refLink" readonly>
            </div>
            
            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-light flex-grow-1" type="button" onclick="copyToClipboard(document.getElementById('refLink').value, this)">
                    <i class="bi bi-copy"></i> Copy
                </button>
                <button class="btn btn-outline-info flex-grow-1 fw-bold" type="button" onclick="shareLink()">
                    <i class="bi bi-share"></i> Share
                </button>
            </div>
            <a href="#" class="btn btn-premium w-100 mb-4 fw-bold" onclick="copyToClipboard(document.getElementById('refLink').value, this)"><i class="bi bi-person-plus-fill"></i> Invite Friends Now</a>
            
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="p-2 rounded bg-dark border border-secondary text-center">
                        <small class="text-muted d-block">Direct Referrals</small>
                        <strong class="text-white">{{ $directReferralsCount }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded bg-dark border border-secondary text-center">
                        <small class="text-muted d-block">Total Team Size</small>
                        <strong class="text-white">{{ $teamSize }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded bg-dark border border-secondary text-center">
                        <small class="text-muted d-block">Referral Income</small>
                        <strong class="text-success">${{ number_format($wallet->referral_balance, 2) }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded bg-dark border border-secondary text-center">
                        <small class="text-muted d-block">Team Volume</small>
                        <strong class="text-info">${{ number_format($teamVolume, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-clock-history text-secondary me-2"></i> Recent Activity</h5>
                <a href="{{ route('dashboard.history') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Wallet</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                        <tr>
                            <td>
                                @if($tx->type == 'deposit')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-arrow-down me-1"></i> Deposit</span>
                                @elseif($tx->type == 'withdrawal')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-arrow-up me-1"></i> Withdrawal</span>
                                @elseif($tx->type == 'roi')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1"><i class="bi bi-graph-up me-1"></i> ROI</span>
                                @elseif($tx->type == 'commission')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-people me-1"></i> Commission</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="bi bi-gift me-1"></i> Bonus</span>
                                @endif
                            </td>
                            <td class="fw-bold text-white">${{ number_format($tx->amount, 2) }}</td>
                            <td class="text-muted small">{{ str_replace('_', ' ', Str::title($tx->wallet_type)) }}</td>
                            <td>
                                @if($tx->status == 'completed' || $tx->status == 'approved')
                                    <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
                                @elseif($tx->status == 'pending')
                                    <span class="text-warning small fw-bold"><i class="bi bi-clock-fill me-1"></i> Pending</span>
                                @else
                                    <span class="text-danger small fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Rejected</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $tx->created_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-receipt fs-2 mb-2 d-block opacity-50"></i>
                                No transactions found yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let userChart;
    const chartLabels = {!! json_encode($chartLabels) !!};

    const userDatasets = {
        invs: {
            label: 'Investments Created ($)',
            data: {!! json_encode($chartInvs) !!},
            color: '#a855f7',
            fillColor: 'rgba(168, 85, 247, 0.1)'
        },
        deps: {
            label: 'Approved Deposits ($)',
            data: {!! json_encode($chartDeps) !!},
            color: '#10b981',
            fillColor: 'rgba(16, 185, 129, 0.1)'
        },
        withs: {
            label: 'Approved Withdrawals ($)',
            data: {!! json_encode($chartWiths) !!},
            color: '#ef4444',
            fillColor: 'rgba(239, 68, 68, 0.1)'
        },
        rois: {
            label: 'Daily ROI Earned ($)',
            data: {!! json_encode($chartRois) !!},
            color: '#eab308',
            fillColor: 'rgba(234, 179, 8, 0.1)'
        },
        refs: {
            label: 'Referral Commissions ($)',
            data: {!! json_encode($chartRefs) !!},
            color: '#3b82f6',
            fillColor: 'rgba(59, 130, 246, 0.1)'
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CountUp numbers
        const countElements = document.querySelectorAll('.countup');
        countElements.forEach(el => {
            const val = parseFloat(el.getAttribute('data-val'));
            const decimals = val % 1 !== 0 ? 2 : 0;
            const countUp = new countUp.CountUp(el, val, {
                decimalPlaces: decimals,
                duration: 2,
                useEasing: true,
            });
            if (!countUp.error) countUp.start();
        });

        // Initialize user chart
        const ctx = document.getElementById('userAnalyticsChart').getContext('2d');
        userChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: userDatasets.invs.label,
                    data: userDatasets.invs.data,
                    borderColor: userDatasets.invs.color,
                    backgroundColor: userDatasets.invs.fillColor,
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#0f172a',
                    pointBorderColor: userDatasets.invs.color,
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });

    function switchUserChart(key) {
        // Remove active class from buttons
        const buttons = document.querySelectorAll('.btn-group button');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        event.target.classList.add('active');

        const activeSet = userDatasets[key];
        userChart.data.datasets[0].label = activeSet.label;
        userChart.data.datasets[0].data = activeSet.data;
        userChart.data.datasets[0].borderColor = activeSet.color;
        userChart.data.datasets[0].backgroundColor = activeSet.fillColor;
        userChart.data.datasets[0].pointBorderColor = activeSet.color;
        userChart.update();
    }

    // Native Share API
    function shareLink() {
        const url = document.getElementById('refLink').value;
        if (navigator.share) {
            navigator.share({
                title: 'Join my Crypto Team!',
                text: 'Sign up using my referral link and get a free bonus.',
                url: url,
            }).then(() => {
                // Show toast
                const toastHtml = `
                    <div class="toast show align-items-center text-white bg-info border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body fw-bold"><i class="bi bi-share me-2"></i> Link Shared Successfully!</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>`;
                document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHtml);
            }).catch((error) => console.log('Error sharing', error));
        } else {
            // Fallback to copy if native share not supported
            copyToClipboard(url, document.querySelector('.btn-outline-info'));
        }
    }
</script>

<!-- tsParticles Engine -->
<script src="https://cdn.jsdelivr.net/npm/tsparticles-engine@2/tsparticles.engine.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-basic@2/tsparticles.basic.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-interaction-particles-links@2/tsparticles.interaction.particles.links.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-move-base@2/tsparticles.move.base.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-shape-circle@2/tsparticles.shape.circle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-color@2/tsparticles.updater.color.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-opacity@2/tsparticles.updater.opacity.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-size@2/tsparticles.updater.size.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        await loadBaseMover(tsParticles);
        await loadCircleShape(tsParticles);
        await loadColorUpdater(tsParticles);
        await loadOpacityUpdater(tsParticles);
        await loadSizeUpdater(tsParticles);
        await loadParticlesLinksInteraction(tsParticles);
        await loadBasic(tsParticles);

        tsParticles.load("tsparticles", {
            fpsLimit: 60,
            particles: {
                number: { value: 40, density: { enable: true, value_area: 800 } },
                color: { value: ["#3b82f6", "#10b981", "#8b5cf6"] },
                links: { enable: true, color: "#3b82f6", distance: 150, opacity: 0.15, width: 1 },
                move: { enable: true, speed: 0.8, direction: "top", random: true, straight: false, outModes: { default: "out" } },
                size: { value: { min: 1, max: 3 } },
                opacity: { value: { min: 0.1, max: 0.3 } }
            },
            interactivity: {
                detectsOn: "canvas",
                events: { onHover: { enable: true, mode: "grab" }, resize: true },
                modes: { grab: { distance: 140, links: { opacity: 0.3 } } }
            },
            retina_detect: true
        });
    });
</script>
@endpush
