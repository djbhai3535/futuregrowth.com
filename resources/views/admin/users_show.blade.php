@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-person-badge me-2"></i> User Card: {{ $user->username }}</h2>
        <p class="text-muted small">ID: #{{ $user->id }} | Registered on {{ $user->created_at->format('M d, Y H:i') }}</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Directory</a>
</div>

<div class="row g-4">
    <!-- User Quick Summary Side Card -->
    <div class="col-lg-4" data-aos="fade-right">
        <div class="glass-card p-4 text-center mb-4 position-relative overflow-hidden">
            <div class="tech-grid-overlay" style="opacity: 0.05;"></div>
            
            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white fs-1 mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #8b5cf6);">
                {{ substr($user->name, 0, 1) }}
            </div>
            
            <h4 class="fw-bold text-white mb-1">{{ $user->name }}</h4>
            <p class="text-muted small mb-3">@ {{ $user->username }}</p>
            
            <div class="mb-3">
                @if($user->status === 'active')
                    <span class="badge bg-success px-3 py-1">Active Account</span>
                @elseif($user->status === 'suspended')
                    <span class="badge bg-warning text-dark px-3 py-1">Suspended</span>
                @else
                    <span class="badge bg-danger px-3 py-1">Banned</span>
                @endif
                
                @if($user->email_verified_at)
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-1 ms-1"><i class="bi bi-shield-check"></i> Email Verified</span>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 ms-1"><i class="bi bi-shield-exclamation"></i> Email Unverified</span>
                @endif
            </div>

            <hr class="border-secondary opacity-25">

            <div class="row g-3 text-start small">
                <div class="col-6">
                    <span class="text-muted d-block">Wallet Balance</span>
                    <strong class="text-white fs-5">${{ number_format($user->wallet ? ($user->wallet->deposit_balance + $user->wallet->roi_balance + $user->wallet->referral_balance + $user->wallet->bonus_balance) : 0, 2) }}</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Total Investments</span>
                    <strong class="text-info fs-5">${{ number_format($totalInvestment, 2) }}</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Total Deposits</span>
                    <strong class="text-success fs-5">${{ number_format($totalDeposit, 2) }}</strong>
                </div>
                <div class="col-6">
                    <span class="text-muted d-block">Total Withdraws</span>
                    <strong class="text-danger fs-5">${{ number_format($totalWithdrawal, 2) }}</strong>
                </div>
            </div>
        </div>

        <!-- Security & Quick Controls Card -->
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-4 text-warning"><i class="bi bi-shield-lock-fill me-2"></i> Security Shield & Password</h5>
            
            <!-- Manual Change Password -->
            <form action="{{ route('admin.users.change-password', $user->id) }}" method="POST" class="mb-4">
                @csrf
                <h6 class="fw-bold text-white small mb-3">Change Password Manually</h6>
                <div class="mb-3">
                    <label class="form-label text-muted small">New Password</label>
                    <input type="password" name="password" class="form-control bg-dark border-secondary text-white" required minlength="8">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" required minlength="8">
                </div>
                <button type="submit" class="btn btn-warning w-100 btn-sm fw-bold">Update Password</button>
            </form>

            <hr class="border-secondary opacity-25 my-4">

            <!-- Reset password and email verification tools -->
            <div class="d-flex flex-column gap-2">
                <form action="{{ route('admin.users.reset-password-auto', $user->id) }}" method="POST" onsubmit="return confirm('Generate random password for this user?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-info w-100 btn-sm fw-bold"><i class="bi bi-key me-2"></i> Auto Generate Password</button>
                </form>

                @if(!$user->email_verified_at)
                    <form action="{{ route('admin.users.verify-email', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100 btn-sm fw-bold"><i class="bi bi-envelope-check me-2"></i> Manually Verify Email</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Details and Tabs Section -->
    <div class="col-lg-8" data-aos="fade-left">
        <div class="glass-card p-4">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs border-secondary mb-4" id="userProfileTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active text-white" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab"><i class="bi bi-person me-1"></i> Profile & Wallet</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="deposits-tab" data-bs-toggle="tab" data-bs-target="#deposits" type="button" role="tab"><i class="bi bi-arrow-down-circle me-1"></i> Deposits</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="withdrawals-tab" data-bs-toggle="tab" data-bs-target="#withdrawals" type="button" role="tab"><i class="bi bi-arrow-up-circle me-1"></i> Withdrawals</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="investments-tab" data-bs-toggle="tab" data-bs-target="#investments" type="button" role="tab"><i class="bi bi-box me-1"></i> Investments</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="referrals-tab" data-bs-toggle="tab" data-bs-target="#referrals" type="button" role="tab"><i class="bi bi-diagram-3 me-1"></i> Referral Tree</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="roi-tab" data-bs-toggle="tab" data-bs-target="#roi" type="button" role="tab"><i class="bi bi-graph-up me-1"></i> ROI History</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab"><i class="bi bi-journal-text me-1"></i> Activity Logs</button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="userProfileTabsContent">
                
                <!-- PROFILE & WALLET BALANCE ADJUSTMENTS -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        <h5 class="fw-bold mb-3 text-warning">Edit Profile Settings</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control bg-dark border-secondary text-white" value="{{ $user->username }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control bg-dark border-secondary text-white" value="{{ $user->phone }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Account Access Status</label>
                                <select name="status" class="form-select bg-dark border-secondary text-white">
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="banned" {{ $user->status === 'banned' ? 'selected' : '' }}>Banned</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Referral Code</label>
                                <input type="text" name="referral_code" class="form-control bg-dark border-secondary text-white" value="{{ $user->referral_code }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Referred By Upline ID</label>
                                <input type="number" name="referred_by" class="form-control bg-dark border-secondary text-white" value="{{ $user->referred_by }}" placeholder="None">
                            </div>
                            <div class="col-md-12">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="isAdminSwitch" {{ $user->is_admin ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="isAdminSwitch">Assign Administrator Status (Grants full admin panel control)</label>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3 text-success">Modify Wallet Balances</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label text-muted small fw-bold">Deposit Balance ($)</label>
                                <input type="number" step="0.01" name="deposit_balance" class="form-control bg-dark border-secondary text-white" value="{{ $user->wallet ? $user->wallet->deposit_balance : 0 }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label text-muted small fw-bold">ROI Balance ($)</label>
                                <input type="number" step="0.01" name="roi_balance" class="form-control bg-dark border-secondary text-white" value="{{ $user->wallet ? $user->wallet->roi_balance : 0 }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label text-muted small fw-bold">Referral Balance ($)</label>
                                <input type="number" step="0.01" name="referral_balance" class="form-control bg-dark border-secondary text-white" value="{{ $user->wallet ? $user->wallet->referral_balance : 0 }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label text-muted small fw-bold">Bonus Balance ($)</label>
                                <input type="number" step="0.01" name="bonus_balance" class="form-control bg-dark border-secondary text-white" value="{{ $user->wallet ? $user->wallet->bonus_balance : 0 }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning fw-bold px-4">Save Changes</button>
                    </form>
                </div>

                <!-- USER DEPOSITS -->
                <div class="tab-pane fade" id="deposits" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-success">Deposits Log</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th>TXID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $dep)
                                    <tr>
                                        <td><code class="text-warning">{{ $dep->txid }}</code></td>
                                        <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                                        <td>
                                            @if($dep->status === 'approved')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5">Approved</span>
                                            @elseif($dep->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-0.5">Pending</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-0.5">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $dep->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No deposits submitted yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- USER WITHDRAWALS -->
                <div class="tab-pane fade" id="withdrawals" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-danger">Withdrawals Log</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th>Wallet Address</th>
                                    <th>Amount</th>
                                    <th>Balance Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $with)
                                    <tr>
                                        <td><code class="text-info">{{ $with->wallet_address }}</code></td>
                                        <td class="fw-bold text-danger">${{ number_format($with->amount, 2) }}</td>
                                        <td><span class="text-muted">{{ $with->wallet_type }}</span></td>
                                        <td>
                                            @if($with->status === 'approved')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5">Approved</span>
                                            @elseif($with->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-0.5">Pending</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-0.5">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $with->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No withdrawals requested yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- USER INVESTMENTS -->
                <div class="tab-pane fade" id="investments" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-info">Investments History</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Amount Invested</th>
                                    <th>ROI Generated</th>
                                    <th>Last Payout</th>
                                    <th>Status</th>
                                    <th>Started At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($investments as $inv)
                                    <tr>
                                        <td class="fw-bold text-white">{{ $inv->plan ? $inv->plan->name : 'Custom Plan' }}</td>
                                        <td class="fw-bold text-info">${{ number_format($inv->amount, 2) }}</td>
                                        <td class="text-success">${{ number_format($inv->total_earned, 2) }}</td>
                                        <td class="text-muted">{{ $inv->last_roi_at ? $inv->last_roi_at->format('M d, H:i') : 'Never' }}</td>
                                        <td>
                                            @if($inv->status === 'active')
                                                <span class="badge bg-success">Running</span>
                                            @else
                                                <span class="badge bg-secondary">Completed</span>
                                            @endif
                                        </td>
                                        <td>{{ $inv->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No investments found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- REFERRAL DOWNLINE TREE -->
                <div class="tab-pane fade" id="referrals" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-warning">Multi-Level Downline Referral Tree <small class="text-muted fs-6">(Total Size: {{ $teamSize }})</small></h5>
                    
                    @if(count($referralTree) > 0)
                        <div class="accordion accordion-flush bg-transparent border-0" id="referralAccordion">
                            @foreach($referralTree as $level => $referrals)
                                <div class="accordion-item bg-dark border-secondary border-opacity-25 text-white">
                                    <h2 class="accordion-header" id="flush-headingLevel{{ $level }}">
                                        <button class="accordion-button collapsed bg-dark text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseLevel{{ $level }}">
                                            Level {{ $level }} Downline ({{ $referrals->count() }} Users)
                                        </button>
                                    </h2>
                                    <div id="flush-collapseLevel{{ $level }}" class="accordion-collapse collapse" data-bs-parent="#referralAccordion">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-dark table-hover align-middle mb-0 small">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>User</th>
                                                            <th>Joined Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($referrals as $ref)
                                                            <tr>
                                                                <td>#{{ $ref->id }}</td>
                                                                <td>{{ $ref->name }} (<strong>{{ $ref->username }}</strong>)</td>
                                                                <td class="text-muted">{{ $ref->created_at->format('Y-m-d') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-diagram-3 fs-2 mb-2 d-block opacity-50"></i>
                            This user does not have any direct or indirect referrals downlines.
                        </div>
                    @endif
                </div>

                <!-- ROI PAYOUT HISTORY -->
                <div class="tab-pane fade" id="roi" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-warning">Daily ROI Profit Disbursements</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Wallet Disbursed</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roiHistory as $roiLog)
                                    <tr>
                                        <td class="fw-bold text-success">+${{ number_format($roiLog->amount, 2) }}</td>
                                        <td class="text-white">{{ $roiLog->description }}</td>
                                        <td><code class="text-warning">{{ $roiLog->wallet_type }}</code></td>
                                        <td><span class="badge bg-success bg-opacity-10 text-success">Completed</span></td>
                                        <td>{{ $roiLog->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No ROI payout records yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- USER ACTIVITY LOGS -->
                <div class="tab-pane fade" id="logs" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-light">User Actions Audit Logs</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th>Action Log</th>
                                    <th>IP Address</th>
                                    <th>Device / User Agent</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $log)
                                    <tr>
                                        <td class="text-white fw-bold">{{ $log->action }}</td>
                                        <td><code class="text-info">{{ $log->ip_address }}</code></td>
                                        <td class="text-muted">{{ Str::limit($log->user_agent, 45) }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No activity logged for this user yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
