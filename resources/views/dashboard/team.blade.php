@extends('layouts.app')

@section('content')
<style>
    .metric-card {
        background: linear-gradient(135deg, rgba(24, 24, 27, 0.6) 0%, rgba(9, 9, 11, 0.8) 100%);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        border-color: rgba(59, 130, 246, 0.3);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1);
    }
    .text-gradient-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .text-gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .text-gradient-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .level-badge {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    .table-responsive {
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2 class="fw-bold mb-1">Referral Network</h2>
        <p class="text-muted mb-0">Build your team and earn commissions across 10 levels of distribution.</p>
    </div>
    <div class="glass-card px-4 py-2 border-primary border-opacity-25 rounded-pill d-flex align-items-center gap-2">
        <i class="bi bi-link-45deg text-primary fs-4"></i>
        <span class="text-muted small">My Referral Link:</span>
        <strong class="text-white small">{{ Auth::user()->referral_code }}</strong>
        <button class="btn btn-sm btn-primary rounded-pill px-3 py-1 ms-2" onclick="navigator.clipboard.writeText('{{ route('register', ['ref' => Auth::user()->referral_code]) }}'); alert('Link copied to clipboard!');">Copy</button>
    </div>
</div>

<!-- Summary Stats Cards Grid -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <!-- Total Team Members -->
    <div class="col-lg-3 col-md-6">
        <div class="metric-card p-4 d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                <i class="bi bi-people-fill fs-3"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 text-uppercase fw-bold">Total Team</p>
                <h3 class="fw-bold text-white mb-0">{{ number_format($teamSize) }}</h3>
                <span class="text-muted small">All 10 Levels</span>
            </div>
        </div>
    </div>
    
    <!-- Direct Referrals -->
    <div class="col-lg-3 col-md-6">
        <div class="metric-card p-4 d-flex align-items-center gap-3">
            <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                <i class="bi bi-person-fill-check fs-3"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 text-uppercase fw-bold">Direct Referrals</p>
                <h3 class="fw-bold text-white mb-0">{{ number_format($directReferralsCount) }}</h3>
                <span class="text-info small">Level 1 Members</span>
            </div>
        </div>
    </div>

    <!-- Team Investment Volume -->
    <div class="col-lg-3 col-md-6">
        <div class="metric-card p-4 d-flex align-items-center gap-3">
            <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                <i class="bi bi-wallet2 fs-3"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 text-uppercase fw-bold">Team Volume</p>
                <h3 class="fw-bold text-success mb-0">${{ number_format($teamVolume, 2) }}</h3>
                <span class="text-muted small">Active Downline Deposits</span>
            </div>
        </div>
    </div>

    <!-- Lifetime Earnings -->
    <div class="col-lg-3 col-md-6">
        <div class="metric-card p-4 d-flex align-items-center gap-3">
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                <i class="bi bi-trophy-fill fs-3"></i>
            </div>
            <div>
                <p class="text-muted small mb-0 text-uppercase fw-bold">Total Earnings</p>
                <h3 class="fw-bold text-warning mb-0">${{ number_format($lifetimeEarnings, 2) }}</h3>
                <span class="text-muted small">Lifetime Payouts</span>
            </div>
        </div>
    </div>
</div>

<!-- Timeframe Stats Row -->
<div class="row g-3 mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="col-md-4">
        <div class="glass-card p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small fw-bold text-uppercase">Today's Earnings</span>
            <strong class="text-success fs-5">${{ number_format($todayEarnings, 2) }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small fw-bold text-uppercase">Weekly Earnings</span>
            <strong class="text-primary fs-5">${{ number_format($weeklyEarnings, 2) }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small fw-bold text-uppercase">Monthly Earnings</span>
            <strong class="text-warning fs-5">${{ number_format($monthlyEarnings, 2) }}</strong>
        </div>
    </div>
</div>

<!-- Level Matrix Breakdown -->
<div class="glass-card p-4 mb-4" data-aos="fade-up" data-aos-delay="150">
    <div class="border-bottom border-secondary pb-3 mb-3">
        <h4 class="fw-bold mb-0">10-Level Downline Statistics</h4>
        <p class="text-muted small mb-0">Performance breakdown of your downline referrals level-by-level.</p>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Level</th>
                    <th class="text-end">Commissions (%)</th>
                    <th class="text-end">Number of Users</th>
                    <th class="text-end">Total Deposits</th>
                    <th class="text-end">Total Investments</th>
                    <th class="text-end">Total Team Business</th>
                    <th class="text-end">Referral Earnings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($levelsData as $ld)
                <tr>
                    <td>
                        <span class="badge level-badge rounded px-3 py-1 fw-bold">Level {{ $ld['level'] }}</span>
                    </td>
                    <td class="text-end fw-bold text-primary">{{ $ld['percent'] }}%</td>
                    <td class="text-end text-white">{{ number_format($ld['total_users']) }}</td>
                    <td class="text-end text-success fw-bold">${{ number_format($ld['total_deposits'], 2) }}</td>
                    <td class="text-end text-white fw-bold">${{ number_format($ld['total_investments'], 2) }}</td>
                    <td class="text-end text-info fw-bold">${{ number_format($ld['team_business'], 2) }}</td>
                    <td class="text-end text-warning fw-bold">${{ number_format($ld['referral_earnings'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Direct Referral Details -->
<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="200">
    <div class="border-bottom border-secondary pb-3 mb-3">
        <h4 class="fw-bold mb-0">Direct Referrals (Level 1 Details)</h4>
        <p class="text-muted small mb-0">List of users who registered directly using your link.</p>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>User</th>
                    <th>Username</th>
                    <th>Joined Date</th>
                    <th class="text-end">Active Investments</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $ref)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($ref->name, 0, 1)) }}
                            </div>
                            <span class="fw-bold text-white">{{ $ref->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $ref->username }}</td>
                    <td class="text-muted small">{{ $ref->created_at->format('M d, Y') }}</td>
                    <td class="fw-bold text-success text-end">${{ number_format($ref->investments()->where('status', 'active')->sum('amount'), 2) }}</td>
                    <td>
                        @if($ref->status == 'active')
                            <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger">Banned</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                        No direct referrals found. Share your link to start building your network!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
