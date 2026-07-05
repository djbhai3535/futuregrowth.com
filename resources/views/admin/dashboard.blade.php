@extends('layouts.app')

@section('content')
<style>
    .metric-group-title {
        font-size: 0.85rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 700;
        border-left: 3px solid #eab308;
        padding-left: 10px;
        margin-bottom: 15px;
        margin-top: 20px;
    }
    .quick-action-btn {
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.03);
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
        background: rgba(234, 179, 8, 0.1);
        border-color: rgba(234, 179, 8, 0.4);
        color: #eab308 !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-shield-check me-2"></i> Command Center</h2>
        <p class="text-muted small">Platform-wide statistics, dynamic analytics, and administrative operations.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-bar-graph"></i> Reports Console</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm fw-bold text-dark"><i class="bi bi-arrow-clockwise"></i> Refresh Dashboard</a>
    </div>
</div>

<!-- ==========================================
     1. PLATFORM SUMMARY METRICS
     ========================================== -->

<!-- Users Overview Group -->
<div class="metric-group-title mt-0" data-aos="fade-right">User Account Metrics</div>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative border-primary border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Users</p>
            <h3 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $totalUsers }}">{{ number_format($totalUsers, 0) }}</span></h3>
            <i class="bi bi-people position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-primary opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative border-success border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Active Users</p>
            <h3 class="text-success fw-bold mb-0"><span class="countup" data-val="{{ $activeUsers }}">{{ number_format($activeUsers, 0) }}</span></h3>
            <i class="bi bi-person-check position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-success opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-3 position-relative border-danger border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Suspended Users</p>
            <h3 class="text-danger fw-bold mb-0"><span class="countup" data-val="{{ $suspendedUsers }}">{{ number_format($suspendedUsers, 0) }}</span></h3>
            <i class="bi bi-person-x position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-danger opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
        <div class="glass-card p-3 position-relative border-info border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Verified Emails</p>
            <h3 class="text-info fw-bold mb-0"><span class="countup" data-val="{{ $emailVerifiedUsers }}">{{ number_format($emailVerifiedUsers, 0) }}</span></h3>
            <i class="bi bi-shield-check position-absolute top-50 end-0 translate-middle-y me-3 fs-1 text-info opacity-25"></i>
        </div>
    </div>
</div>

<!-- Financial Balances & Earnings Group -->
<div class="metric-group-title" data-aos="fade-right">Platform Balances & Revenue</div>
<div class="row g-3 mb-4">
    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-4 position-relative border-warning border-opacity-25 overflow-hidden neon-glow-primary">
            <p class="text-muted small fw-bold mb-1 text-uppercase">Total Platform Wallet Balance</p>
            <h2 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalPlatformWalletBalance }}">{{ number_format($totalPlatformWalletBalance, 2) }}</span></h2>
            <i class="bi bi-wallet2 position-absolute top-50 end-0 translate-middle-y me-4 display-4 text-warning opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-4 position-relative border-success border-opacity-25 overflow-hidden neon-glow-success">
            <p class="text-muted small fw-bold mb-1 text-uppercase">Total Net Platform Earnings (Fees)</p>
            <h2 class="text-success fw-bold mb-0">$<span class="countup" data-val="{{ $totalPlatformEarnings }}">{{ number_format($totalPlatformEarnings, 2) }}</span></h2>
            <i class="bi bi-piggy-bank position-absolute top-50 end-0 translate-middle-y me-4 display-4 text-success opacity-25"></i>
        </div>
    </div>
</div>

<!-- Deposits Metrics Group -->
<div class="metric-group-title" data-aos="fade-right">USDT Deposits Summary</div>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative border-secondary border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Deposits Sum</p>
            <h4 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalPlatformDeposits }}">{{ number_format($totalPlatformDeposits, 2) }}</span></h4>
            <i class="bi bi-arrow-down-circle position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-muted opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative border-warning border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Pending Deposits</p>
            <h4 class="text-warning fw-bold mb-0"><span class="countup" data-val="{{ $pendingDeposits }}">{{ number_format($pendingDeposits, 0) }}</span></h4>
            <i class="bi bi-clock-history position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-warning opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-3 position-relative border-success border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Approved Deposits</p>
            <h4 class="text-success fw-bold mb-0"><span class="countup" data-val="{{ $approvedDepositsCount }}">{{ number_format($approvedDepositsCount, 0) }}</span> <span class="fs-6 text-muted">(${{ number_format($approvedDepositsSum, 0) }})</span></h4>
            <i class="bi bi-check-circle position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-success opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
        <div class="glass-card p-3 position-relative border-danger border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Failed/Rejected Deposits</p>
            <h4 class="text-danger fw-bold mb-0"><span class="countup" data-val="{{ $failedDeposits }}">{{ number_format($failedDeposits, 0) }}</span></h4>
            <i class="bi bi-x-circle position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-danger opacity-25"></i>
        </div>
    </div>
</div>

<!-- Withdrawals Summary Group -->
<div class="metric-group-title" data-aos="fade-right">USDT Withdrawals Summary</div>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative border-secondary border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Withdrawals Sum</p>
            <h4 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalWithdrawals }}">{{ number_format($totalWithdrawals, 2) }}</span></h4>
            <i class="bi bi-arrow-up-circle position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-muted opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative border-warning border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Pending Withdrawals</p>
            <h4 class="text-warning fw-bold mb-0"><span class="countup" data-val="{{ $pendingWithdrawals }}">{{ number_format($pendingWithdrawals, 0) }}</span></h4>
            <i class="bi bi-hourglass-split position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-warning opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-3 position-relative border-success border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Approved Withdrawals</p>
            <h4 class="text-success fw-bold mb-0"><span class="countup" data-val="{{ $approvedWithdrawalsCount }}">{{ number_format($approvedWithdrawalsCount, 0) }}</span> <span class="fs-6 text-muted">(${{ number_format($approvedWithdrawalsSum, 0) }})</span></h4>
            <i class="bi bi-wallet2 position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-success opacity-25"></i>
        </div>
    </div>
</div>

<!-- Investments Summary Group -->
<div class="metric-group-title" data-aos="fade-right">Investments & Commissions Overview</div>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-3 position-relative border-info border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Active Investments</p>
            <h4 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalActiveInvestments }}">{{ number_format($totalActiveInvestments, 2) }}</span></h4>
            <i class="bi bi-activity position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-info opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-3 position-relative border-success border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Completed Investments</p>
            <h4 class="text-success fw-bold mb-0"><span class="countup" data-val="{{ $completedInvestments }}">{{ number_format($completedInvestments, 0) }}</span></h4>
            <i class="bi bi-patch-check-fill position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-success opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-3 position-relative border-warning border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total ROI Paid</p>
            <h4 class="text-warning fw-bold mb-0">$<span class="countup" data-val="{{ $totalRoiPaid }}">{{ number_format($totalRoiPaid, 2) }}</span></h4>
            <i class="bi bi-graph-up-arrow position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-warning opacity-25"></i>
        </div>
    </div>
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
        <div class="glass-card p-3 position-relative border-primary border-opacity-25 overflow-hidden">
            <p class="text-muted small fw-bold mb-1">Total Referral Commissions</p>
            <h4 class="text-primary fw-bold mb-0">$<span class="countup" data-val="{{ $totalReferralCommissionPaid }}">{{ number_format($totalReferralCommissionPaid, 2) }}</span></h4>
            <i class="bi bi-diagram-3 position-absolute top-50 end-0 translate-middle-y me-3 fs-2 text-primary opacity-25"></i>
        </div>
    </div>
</div>

<!-- ==========================================
     2. ADMIN ANALYTICS & QUICK ACTIONS
     ========================================== -->

<div class="row g-4 mb-4">
    <!-- Platform Growth Analytics -->
    <div class="col-lg-8" data-aos="fade-right">
        <div class="glass-card p-4 border-warning border-opacity-25 tech-bg-container h-100">
            <div class="tech-grid-overlay"></div>
            
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 position-relative gap-2">
                <h5 class="fw-bold mb-0 text-white"><i class="bi bi-bar-chart-fill text-warning me-2"></i> Live Platform Analytics Matrix</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-warning active" onclick="switchChartDataset('regs')">Registrations</button>
                    <button type="button" class="btn btn-outline-warning" onclick="switchChartDataset('deps')">Deposits</button>
                    <button type="button" class="btn btn-outline-warning" onclick="switchChartDataset('withs')">Withdraws</button>
                    <button type="button" class="btn btn-outline-warning" onclick="switchChartDataset('invs')">Investments</button>
                    <button type="button" class="btn btn-outline-warning" onclick="switchChartDataset('rois')">ROI</button>
                    <button type="button" class="btn btn-outline-warning" onclick="switchChartDataset('refs')">Referrals</button>
                </div>
            </div>
            
            <div style="height: 320px; position: relative;">
                <canvas id="adminAnalyticsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Admin Actions -->
    <div class="col-lg-4" data-aos="fade-left">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-4 text-warning"><i class="bi bi-lightning-charge-fill me-2"></i> Quick Admin Actions</h5>
            
            <div class="row g-3">
                <div class="col-6">
                    <a href="{{ route('admin.users') }}" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-people text-warning fs-3 mb-1"></i>
                        <span class="small fw-bold">Manage Users</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.deposits') }}" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-arrow-down-circle text-success fs-3 mb-1"></i>
                        <span class="small fw-bold">Deposits</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.withdrawals') }}" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-arrow-up-circle text-danger fs-3 mb-1"></i>
                        <span class="small fw-bold">Withdrawals</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.plans') }}" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-box text-info fs-3 mb-1"></i>
                        <span class="small fw-bold">Investments</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.settings') }}?tab=nowpayments" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-wallet2 text-primary fs-3 mb-1"></i>
                        <span class="small fw-bold">NOWPayments</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.settings') }}?tab=smtp" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-envelope-open text-warning fs-3 mb-1"></i>
                        <span class="small fw-bold">SMTP Settings</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.settings') }}" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-gear text-light fs-3 mb-1"></i>
                        <span class="small fw-bold">Platform Settings</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.reports') }}" class="btn quick-action-btn w-100 py-3 text-center text-white rounded d-flex flex-column align-items-center">
                        <i class="bi bi-file-earmark-bar-graph text-success fs-3 mb-1"></i>
                        <span class="small fw-bold">Reports Panel</span>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('admin.audit-logs') }}" class="btn quick-action-btn w-100 py-2.5 text-center text-white rounded d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-journal-text text-info fs-5"></i>
                        <span class="small fw-bold">Audit Action Logs</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     3. RECENT ACTIVITY TABBED CONTAINER
     ========================================== -->

<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <h5 class="fw-bold mb-4 text-white"><i class="bi bi-activity text-warning me-2"></i> Real-time Platform Activity Logs</h5>
    
    <!-- Tab navigation -->
    <ul class="nav nav-tabs border-secondary mb-3" id="activityTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active text-white" id="users-tab" data-bs-toggle="tab" data-bs-target="#tab-users" type="button" role="tab"><i class="bi bi-person-plus me-1"></i> Latest Users</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white" id="deposits-tab" data-bs-toggle="tab" data-bs-target="#tab-deposits" type="button" role="tab"><i class="bi bi-arrow-down-circle me-1"></i> Latest Deposits</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white" id="withdraws-tab" data-bs-toggle="tab" data-bs-target="#tab-withdraws" type="button" role="tab"><i class="bi bi-arrow-up-circle me-1"></i> Latest Withdrawals</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white" id="investments-tab" data-bs-toggle="tab" data-bs-target="#tab-investments" type="button" role="tab"><i class="bi bi-box me-1"></i> Latest Investments</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white" id="roi-tab" data-bs-toggle="tab" data-bs-target="#tab-roi" type="button" role="tab"><i class="bi bi-graph-up-arrow me-1"></i> Latest ROI Payments</button>
        </li>
    </ul>
    
    <!-- Tab content -->
    <div class="tab-content" id="activityTabContent">
        
        <!-- Latest Users -->
        <div class="tab-pane fade show active" id="tab-users" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestUsers as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td><strong class="text-warning">{{ $u->username }}</strong></td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge bg-{{ $u->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($u->status) }}</span></td>
                            <td class="text-muted">{{ $u->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Latest Deposits -->
        <div class="tab-pane fade" id="tab-deposits" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>TXID</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestDeposits as $d)
                        <tr>
                            <td>{{ $d->user ? $d->user->username : 'Deleted User' }}</td>
                            <td class="fw-bold text-success">${{ number_format($d->amount, 2) }}</td>
                            <td><code class="text-info">{{ Str::limit($d->txid, 24) }}</code></td>
                            <td><span class="badge bg-{{ $d->status === 'approved' ? 'success' : ($d->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($d->status) }}</span></td>
                            <td class="text-muted">{{ $d->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No deposits found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Latest Withdrawals -->
        <div class="tab-pane fade" id="tab-withdraws" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Destination Address</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestWithdrawals as $w)
                        <tr>
                            <td>{{ $w->user ? $w->user->username : 'Deleted User' }}</td>
                            <td class="fw-bold text-danger">${{ number_format($w->amount, 2) }}</td>
                            <td><code class="text-info">{{ Str::limit($w->wallet_address, 24) }}</code></td>
                            <td><span class="badge bg-{{ $w->status === 'approved' ? 'success' : ($w->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($w->status) }}</span></td>
                            <td class="text-muted">{{ $w->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No withdrawals found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Latest Investments -->
        <div class="tab-pane fade" id="tab-investments" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Earnings</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestInvestments as $inv)
                        <tr>
                            <td>{{ $inv->user ? $inv->user->username : 'Deleted User' }}</td>
                            <td>{{ $inv->plan ? $inv->plan->name : 'Custom Plan' }}</td>
                            <td class="fw-bold text-info">${{ number_format($inv->amount, 2) }}</td>
                            <td class="text-success">${{ number_format($inv->total_earned, 2) }}</td>
                            <td><span class="badge bg-{{ $inv->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($inv->status) }}</span></td>
                            <td class="text-muted">{{ $inv->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-3 text-muted">No investments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Latest ROI Payments -->
        <div class="tab-pane fade" id="tab-roi" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestRoiPayments as $roi)
                        <tr>
                            <td>{{ $roi->user ? $roi->user->username : 'Deleted User' }}</td>
                            <td class="fw-bold text-success">+${{ number_format($roi->amount, 2) }}</td>
                            <td>{{ $roi->description }}</td>
                            <td class="text-muted">{{ $roi->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No ROI payments made yet.</td></tr>
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
    let analyticsChart;
    const chartLabels = {!! json_encode($chartLabels) !!};
    
    // Dataset maps
    const datasets = {
        regs: {
            label: 'Daily Registrations',
            data: {!! json_encode($chartRegs) !!},
            color: '#3b82f6',
            fillColor: 'rgba(59, 130, 246, 0.1)'
        },
        deps: {
            label: 'Daily Approved Deposits ($)',
            data: {!! json_encode($chartDeps) !!},
            color: '#10b981',
            fillColor: 'rgba(16, 185, 129, 0.1)'
        },
        withs: {
            label: 'Daily Approved Withdrawals ($)',
            data: {!! json_encode($chartWiths) !!},
            color: '#ef4444',
            fillColor: 'rgba(239, 68, 68, 0.1)'
        },
        invs: {
            label: 'Daily Investments Created ($)',
            data: {!! json_encode($chartInvs) !!},
            color: '#a855f7',
            fillColor: 'rgba(168, 85, 247, 0.1)'
        },
        rois: {
            label: 'Daily ROI Paid ($)',
            data: {!! json_encode($chartRois) !!},
            color: '#eab308',
            fillColor: 'rgba(234, 179, 8, 0.1)'
        },
        refs: {
            label: 'Daily Referral Commissions ($)',
            data: {!! json_encode($chartRefs) !!},
            color: '#06b6d4',
            fillColor: 'rgba(6, 182, 212, 0.1)'
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CountUp numbers
        const countElements = document.querySelectorAll('.countup');
        countElements.forEach(el => {
            const val = parseFloat(el.getAttribute('data-val'));
            const decimals = val % 1 !== 0 ? 2 : 0;
            const countUpInst = new countUp.CountUp(el, val, {
                decimalPlaces: decimals,
                duration: 2,
                useEasing: true,
            });
            if (!countUpInst.error) countUpInst.start();
        });

        // Initialize Analytics Chart
        const ctx = document.getElementById('adminAnalyticsChart').getContext('2d');
        analyticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: datasets.regs.label,
                    data: datasets.regs.data,
                    borderColor: datasets.regs.color,
                    backgroundColor: datasets.regs.fillColor,
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#0f172a',
                    pointBorderColor: datasets.regs.color,
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

    function switchChartDataset(key) {
        // Remove active class from all buttons
        const buttons = document.querySelectorAll('.btn-group button');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        event.target.classList.add('active');

        const activeSet = datasets[key];
        analyticsChart.data.datasets[0].label = activeSet.label;
        analyticsChart.data.datasets[0].data = activeSet.data;
        analyticsChart.data.datasets[0].borderColor = activeSet.color;
        analyticsChart.data.datasets[0].backgroundColor = activeSet.fillColor;
        analyticsChart.data.datasets[0].pointBorderColor = activeSet.color;
        analyticsChart.update();
    }
</script>
@endpush
