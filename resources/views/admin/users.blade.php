@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-people-fill me-2"></i> User Directory</h2>
        <p class="text-muted small">Manage, filter, sort, audit, and inspect platform users.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-bar-graph"></i> Export Reports</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<!-- Search & Filters -->
<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <form action="{{ route('admin.users') }}" method="GET" class="row g-3">
        <!-- Search input -->
        <div class="col-md-4">
            <label class="form-label text-muted small fw-bold">Search</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search name, username, email, phone..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Status</label>
            <select name="status" class="form-select bg-dark border-secondary text-white">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
        </div>

        <!-- Verification Filter -->
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Email Status</label>
            <select name="email_verified" class="form-select bg-dark border-secondary text-white">
                <option value="">All</option>
                <option value="verified" {{ request('email_verified') === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="unverified" {{ request('email_verified') === 'unverified' ? 'selected' : '' }}>Unverified</option>
            </select>
        </div>

        <!-- Role Filter -->
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Role</label>
            <select name="role" class="form-select bg-dark border-secondary text-white">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <!-- Sort By -->
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Sort By</label>
            <div class="input-group">
                <select name="sort_by" class="form-select bg-dark border-secondary text-white">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Reg Date</option>
                    <option value="id" {{ request('sort_by') === 'id' ? 'selected' : '' }}>ID</option>
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                </select>
                <select name="sort_order" class="form-select bg-dark border-secondary text-white" style="max-width: 65px;">
                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>↓</option>
                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>↑</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Reset Filters</a>
            <button type="submit" class="btn btn-warning fw-bold px-4">Apply Filters</button>
        </div>
    </form>
</div>

<!-- Users Table Card -->
<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0 small">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Details</th>
                    <th>Referrals</th>
                    <th>Balances</th>
                    <th>Finances (Dep / With / Inv / ROI)</th>
                    <th>Verification & Status</th>
                    <th>Last Login</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $wallet = $user->wallet;
                        $totalBalance = $wallet ? ($wallet->deposit_balance + $wallet->roi_balance + $wallet->referral_balance + $wallet->bonus_balance) : 0;
                        
                        // Finances calculations
                        $totalDep = \App\Models\Deposit::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
                        $totalWith = \App\Models\Withdrawal::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
                        $totalInv = \App\Models\Investment::where('user_id', $user->id)->sum('amount');
                        $totalEarnedRoi = \App\Models\Investment::where('user_id', $user->id)->sum('total_earned');
                        $refIncome = \App\Models\Transaction::where('user_id', $user->id)->where('type', 'commission')->sum('amount');

                        // Team size levels calculation
                        $teamSize = 0;
                        $currentLevelReferrals = \App\Models\User::where('referred_by', $user->id)->get();
                        for ($i = 1; $i <= 10; $i++) {
                            if ($currentLevelReferrals->isEmpty()) break;
                            $teamSize += $currentLevelReferrals->count();
                            $userIds = $currentLevelReferrals->pluck('id');
                            $currentLevelReferrals = \App\Models\User::whereIn('referred_by', $userIds)->get();
                        }

                        // Last login log
                        $loginLog = $user->activityLogs()->where('action', 'Logged in')->latest()->first();
                    @endphp
                    <tr>
                        <td><strong class="text-warning">#{{ $user->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm me-3 text-white" style="width: 38px; height: 38px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); flex-shrink: 0;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-white">{{ $user->name }} <span class="text-info small">({{ $user->username }})</span></div>
                                    <small class="text-muted d-block">{{ $user->email }}</small>
                                    <small class="text-muted d-block">{{ $user->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><span class="text-muted">Code:</span> <code class="text-warning fw-bold">{{ $user->referral_code }}</code></div>
                            <div><span class="text-muted">Upline:</span> <small class="text-white">{{ $user->referrer ? $user->referrer->username : 'None' }}</small></div>
                            <div><span class="text-muted">Team Size:</span> <span class="badge bg-secondary">{{ $teamSize }}</span></div>
                        </td>
                        <td>
                            <div><span class="text-muted">Total:</span> <strong class="text-white">${{ number_format($totalBalance, 2) }}</strong></div>
                            <div><span class="text-muted">Bonus:</span> <strong class="text-warning">${{ number_format($wallet ? $wallet->bonus_balance : 0, 2) }}</strong></div>
                        </td>
                        <td>
                            <div><span class="text-muted">Deposits:</span> <strong class="text-success">${{ number_format($totalDep, 2) }}</strong></div>
                            <div><span class="text-muted">Withdrawals:</span> <strong class="text-danger">${{ number_format($totalWith, 2) }}</strong></div>
                            <div><span class="text-muted">Investments:</span> <strong class="text-info">${{ number_format($totalInv, 2) }}</strong></div>
                            <div><span class="text-muted">ROI / Ref Inc:</span> <strong class="text-warning">${{ number_format($totalEarnedRoi, 2) }}</strong> / <strong class="text-primary">${{ number_format($refIncome, 2) }}</strong></div>
                        </td>
                        <td>
                            <div class="mb-1">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5"><i class="bi bi-shield-check"></i> Verified</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-0.5"><i class="bi bi-shield-exclamation"></i> Unverified</span>
                                @endif
                            </div>
                            <div>
                                @if($user->status === 'active')
                                    <span class="badge bg-success text-white px-2 py-0.5">Active</span>
                                @elseif($user->status === 'suspended')
                                    <span class="badge bg-warning text-dark px-2 py-0.5">Suspended</span>
                                @else
                                    <span class="badge bg-danger text-white px-2 py-0.5">Banned</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($loginLog)
                                <div class="text-white">{{ $loginLog->created_at->format('M d, Y') }}</div>
                                <small class="text-muted d-block">{{ $loginLog->created_at->format('H:i') }} ({{ $loginLog->ip_address }})</small>
                            @else
                                <span class="text-muted small">No record</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info" title="View Profile & Manage Tabs"><i class="bi bi-eye"></i></a>
                                
                                @if($user->id !== Auth::id())
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end glass-card border-secondary">
                                            <li>
                                                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success"><i class="bi bi-check-circle me-2"></i> Activate Account</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-warning"><i class="bi bi-pause-circle me-2"></i> Suspend Account</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-slash-circle me-2"></i> Ban User</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.verify-email', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-info"><i class="bi bi-envelope-check me-2"></i> Verify Email</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.reset-password-auto', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to generate a new random password for this user?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-light"><i class="bi bi-key me-2"></i> Reset Password (Auto)</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                            <li>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to soft delete this user?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger fw-bold"><i class="bi bi-trash me-2"></i> Soft Delete User</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 mb-2 d-block opacity-50"></i>
                            No users matched your query.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
