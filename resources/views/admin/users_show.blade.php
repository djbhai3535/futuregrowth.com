@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-person-badge me-2"></i> User Card: {{ $user->username }}</h2>
        <p class="text-muted small">ID: #{{ $user->id }} | Registered on {{ $user->created_at->format('M d, Y H:i') }}</p>
    </div>
    <a href="{{ route('admin.users') }}" class="btn btn-outline-light btn-sm fw-bold"><i class="bi bi-arrow-left"></i> Back to Directory</a>
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
            <p class="text-muted small mb-3">@&nbsp;{{ $user->username }}</p>
            
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

        <!-- Administrative Account Controls -->
        <div class="glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3 text-warning"><i class="bi bi-gear-fill me-2"></i> Account Access Actions</h5>
            
            <div class="d-flex flex-column gap-2">
                @if($user->status !== 'active')
                    <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 btn-sm fw-bold"><i class="bi bi-person-check me-2"></i> Activate Account</button>
                    </form>
                @endif
                @if($user->status !== 'suspended')
                    <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100 btn-sm fw-bold text-dark"><i class="bi bi-person-exclamation me-2"></i> Suspend Account</button>
                    </form>
                @endif
                @if($user->status !== 'banned')
                    <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to BAN this user?')">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 btn-sm fw-bold"><i class="bi bi-person-dash me-2"></i> Ban Account</button>
                    </form>
                @endif
                
                @if(!$user->email_verified_at)
                    <form action="{{ route('admin.users.verify-email', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-info w-100 btn-sm fw-bold"><i class="bi bi-envelope-check me-2"></i> Manually Verify Email</button>
                    </form>
                @endif

                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to DELETE this user completely? This action cannot be undone.')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 btn-sm fw-bold"><i class="bi bi-trash me-2"></i> Delete Account</button>
                </form>
            </div>
        </div>

        <!-- Security & Password Card -->
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3 text-warning"><i class="bi bi-shield-lock-fill me-2"></i> Security Shield & Password</h5>
            
            <form action="{{ route('admin.users.change-password', $user->id) }}" method="POST" class="mb-3">
                @csrf
                <div class="mb-2">
                    <label class="form-label text-muted small fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control form-control-sm bg-dark border-secondary text-white" required minlength="8">
                </div>
                <div class="mb-2">
                    <label class="form-label text-muted small fw-bold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-sm bg-dark border-secondary text-white" required minlength="8">
                </div>
                <button type="submit" class="btn btn-warning w-100 btn-sm fw-bold text-dark">Update Password</button>
            </form>

            <form action="{{ route('admin.users.reset-password-auto', $user->id) }}" method="POST" onsubmit="return confirm('Generate random password for this user?')">
                @csrf
                <button type="submit" class="btn btn-outline-info w-100 btn-sm fw-bold"><i class="bi bi-key me-2"></i> Auto-Generate Password</button>
            </form>
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
                    <button class="nav-link text-white" id="referrals-tab" data-bs-toggle="tab" data-bs-target="#referrals" type="button" role="tab"><i class="bi bi-diagram-3 me-1"></i> Referrals</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="roi-tab" data-bs-toggle="tab" data-bs-target="#roi" type="button" role="tab"><i class="bi bi-graph-up me-1"></i> ROI Logs</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab"><i class="bi bi-journal-text me-1"></i> Activity Logs</button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="userProfileTabsContent">
                
                <!-- PROFILE & WALLET BALANCE ADJUSTMENTS -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="mb-4">
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

                        <h5 class="fw-bold mb-3 text-success">Modify Wallet Balances (Absolute Values)</h5>
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

                        <button type="submit" class="btn btn-warning fw-bold px-4 text-dark">Save Changes</button>
                    </form>

                    <hr class="border-secondary opacity-25 my-4">

                    <!-- Custom Wallet adjustments (Increase/Decrease) -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-25">
                                <h6 class="fw-bold text-white mb-3"><i class="bi bi-wallet2 text-warning me-2"></i> Manual Wallet Adjustments</h6>
                                <form action="{{ route('admin.users.adjust-wallet', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">Select Wallet Type</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="balance_type" required>
                                            <option value="deposit_balance">Deposit Wallet</option>
                                            <option value="roi_balance">ROI Wallet</option>
                                            <option value="referral_balance">Referral Wallet</option>
                                            <option value="bonus_balance">Bonus Wallet</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">Action</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="action_type" required>
                                            <option value="increase">Increase Balance (+)</option>
                                            <option value="decrease">Decrease Balance (-)</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">USDT Amount</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm bg-dark text-white border-secondary" name="amount" required min="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Audit Description/Reason</label>
                                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="description" placeholder="e.g. Deposit correction" required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark w-100">Apply Adjustment</button>
                                </form>
                            </div>
                        </div>

                        <!-- Manual ROI & Referral Payouts -->
                        <div class="col-md-6">
                            <div class="p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-white mb-3"><i class="bi bi-cash-stack text-success me-2"></i> Pay Manual ROI & Commission</h6>
                                    
                                    <!-- ROI payout -->
                                    <form action="{{ route('admin.users.add-roi', $user->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        <div class="row g-2 align-items-end">
                                            <div class="col-5">
                                                <label class="form-label text-muted small fw-bold">ROI ($)</label>
                                                <input type="number" step="0.01" class="form-control form-control-sm bg-dark text-white border-secondary" name="amount" required>
                                            </div>
                                            <div class="col-5">
                                                <label class="form-label text-muted small fw-bold">Description</label>
                                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="description" placeholder="Daily profit" required>
                                            </div>
                                            <div class="col-2">
                                                <button type="submit" class="btn btn-sm btn-success w-100 fw-bold"><i class="bi bi-check"></i></button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Referral Commission payout -->
                                    <form action="{{ route('admin.users.add-referral-bonus', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="row g-2 align-items-end">
                                            <div class="col-5">
                                                <label class="form-label text-muted small fw-bold">Referral ($)</label>
                                                <input type="number" step="0.01" class="form-control form-control-sm bg-dark text-white border-secondary" name="amount" required>
                                            </div>
                                            <div class="col-5">
                                                <label class="form-label text-muted small fw-bold">Description</label>
                                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="description" placeholder="L1 commission" required>
                                            </div>
                                            <div class="col-2">
                                                <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold"><i class="bi bi-check"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- USER DEPOSITS -->
                <div class="tab-pane fade" id="deposits" role="tabpanel">
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3 text-success">Deposits History</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle mb-0 small">
                                    <thead>
                                        <tr>
                                            <th>TXID</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deposits as $dep)
                                            <tr>
                                                <td><code class="text-warning">{{ Str::limit($dep->txid, 16) }}</code></td>
                                                <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $dep->status === 'approved' ? 'success' : ($dep->status === 'pending' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $dep->status === 'approved' ? 'success' : ($dep->status === 'pending' ? 'warning' : 'danger') }} border border-{{ $dep->status === 'approved' ? 'success' : ($dep->status === 'pending' ? 'warning' : 'danger') }} border-opacity-25">
                                                        {{ ucfirst($dep->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">{{ $dep->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">No deposits submitted yet</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Manual deposit form -->
                        <div class="col-md-5">
                            <div class="p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-25">
                                <h6 class="fw-bold text-success mb-3"><i class="bi bi-plus-circle me-2"></i> Add Manual Deposit</h6>
                                <form action="{{ route('admin.deposits.manual') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">USDT Amount</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm bg-dark text-white border-secondary" name="amount" required min="0.01">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">Transaction Hash (TXID)</label>
                                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="txid" placeholder="Optional hash">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Initial Status</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="status" required>
                                            <option value="approved">Approved (Immediately credits wallet)</option>
                                            <option value="pending">Pending</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success fw-bold w-100">Add Deposit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- USER WITHDRAWALS -->
                <div class="tab-pane fade" id="withdrawals" role="tabpanel">
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3 text-danger">Withdrawals History</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle mb-0 small">
                                    <thead>
                                        <tr>
                                            <th>Wallet Address</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($withdrawals as $with)
                                            <tr>
                                                <td><code class="text-info">{{ Str::limit($with->wallet_address, 16) }}</code></td>
                                                <td class="fw-bold text-danger">${{ number_format($with->amount, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $with->status === 'approved' ? 'success' : ($with->status === 'pending' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $with->status === 'approved' ? 'success' : ($with->status === 'pending' ? 'warning' : 'danger') }} border border-{{ $with->status === 'approved' ? 'success' : ($with->status === 'pending' ? 'warning' : 'danger') }} border-opacity-25">
                                                        {{ ucfirst($with->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">{{ $with->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">No withdrawals requested yet</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Manual withdrawal form -->
                        <div class="col-md-5">
                            <div class="p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-25">
                                <h6 class="fw-bold text-danger mb-3"><i class="bi bi-dash-circle me-2"></i> Add Manual Withdrawal</h6>
                                <form action="{{ route('admin.withdrawals.manual') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">USDT Amount</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm bg-dark text-white border-secondary" name="amount" required min="0.01">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">USDT Destination Wallet Address</label>
                                        <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="wallet_address" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">Deduct from Wallet</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="wallet_type" required>
                                            <option value="deposit_balance">Deposit Wallet</option>
                                            <option value="roi_balance">ROI Wallet</option>
                                            <option value="referral_balance">Referral Wallet</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Initial Status</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="status" required>
                                            <option value="approved">Approved (Immediately deducts wallet)</option>
                                            <option value="pending">Pending (Deducts wallet)</option>
                                            <option value="rejected">Rejected (No wallet action)</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-danger fw-bold w-100">Add Withdrawal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- USER INVESTMENTS -->
                <div class="tab-pane fade" id="investments" role="tabpanel">
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3 text-info">Investments history</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle mb-0 small" style="font-size: 0.82rem;">
                                    <thead>
                                        <tr>
                                            <th>Plan</th>
                                            <th>Invested</th>
                                            <th>ROI Earned</th>
                                            <th>Status</th>
                                            <th>Started</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($investments as $inv)
                                            <tr>
                                                <td class="fw-bold text-white">{{ $inv->plan ? $inv->plan->name : 'Custom Plan' }}</td>
                                                <td class="fw-bold text-info">${{ number_format($inv->amount, 2) }}</td>
                                                <td class="text-success">${{ number_format($inv->total_earned, 2) }}</td>
                                                <td><span class="badge bg-{{ $inv->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($inv->status) }}</span></td>
                                                <td class="text-muted">{{ $inv->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    @if($inv->status === 'active')
                                                        <form action="{{ route('admin.investments.complete', $inv->id) }}" method="POST" onsubmit="return confirm('Complete this investment?')" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-xs btn-outline-warning py-0.5 px-1.5 fw-bold" style="font-size:0.75rem;">Complete</button>
                                                        </form>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center text-muted py-4">No investments found</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Manual Investment Form -->
                        <div class="col-md-5">
                            <div class="p-3 bg-dark bg-opacity-25 rounded border border-secondary border-opacity-25">
                                <h6 class="fw-bold text-info mb-3"><i class="bi bi-plus-square me-2"></i> Add Manual Investment</h6>
                                <form action="{{ route('admin.investments.manual') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">Select Plan</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="plan_id" required>
                                            @foreach($plans as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }} (Min: ${{ $p->min_deposit }} | ROI: {{ $p->daily_roi_percent }}%)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-muted small fw-bold">Investment Amount ($)</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm bg-dark text-white border-secondary" name="amount" required min="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">Initial Status</label>
                                        <select class="form-select form-select-sm bg-dark text-white border-secondary" name="status" required>
                                            <option value="active">Active (Earning Daily ROI)</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-info fw-bold w-100">Activate Investment</button>
                                </form>
                            </div>
                        </div>
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
