@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-people-fill me-2"></i> User Directory</h2>
        <p class="text-muted small">View, search, ban/unban users, and manage account details.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
</div>

<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <form action="{{ route('admin.users') }}" method="GET" class="row g-3">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, username, email or phone..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-warning fw-bold flex-grow-1">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>User Detail</th>
                    <th>Wallet Balances</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm me-3 text-white" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #8b5cf6);">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold text-white mb-0">{{ $user->name }} <small class="text-info">({{ $user->username }})</small></h6>
                                    <small class="text-muted d-block">{{ $user->email }} | {{ $user->phone }}</small>
                                    @if($user->is_admin)
                                        <span class="badge bg-warning text-dark mt-1">Administrator</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $wallet = $user->wallet;
                            @endphp
                            @if($wallet)
                                <div class="row g-1" style="max-width: 320px; font-size: 0.8rem;">
                                    <div class="col-6"><span class="text-muted">Deposit:</span> <strong class="text-white">${{ number_format($wallet->deposit_balance, 2) }}</strong></div>
                                    <div class="col-6"><span class="text-success">ROI:</span> <strong class="text-success">${{ number_format($wallet->roi_balance, 2) }}</strong></div>
                                    <div class="col-6"><span class="text-primary">Referral:</span> <strong class="text-primary">${{ number_format($wallet->referral_balance, 2) }}</strong></div>
                                    <div class="col-6"><span class="text-warning">Bonus:</span> <strong class="text-warning">${{ number_format($wallet->bonus_balance, 2) }}</strong></div>
                                </div>
                            @else
                                <span class="text-danger small">No Wallet Setup</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle"></i> Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-x-circle"></i> Banned</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}" title="View Profile Detail"><i class="bi bi-eye"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#passModal{{ $user->id }}" title="Reset Password"><i class="bi bi-key"></i></button>
                                
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this user\'s access status?')">
                                        @csrf
                                        @if($user->status === 'active')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Ban User"><i class="bi bi-slash-circle"></i></button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Unban User"><i class="bi bi-check-circle"></i></button>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Details Modal -->
                    <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark border border-secondary border-opacity-50 rounded-4">
                                <div class="modal-header border-bottom border-secondary border-opacity-25">
                                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-person-badge text-warning me-2"></i> User Card: {{ $user->username }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 text-white">
                                    <div class="text-center mb-4">
                                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white fs-2 mb-2" style="width: 70px; height: 70px; background: linear-gradient(135deg, #3b82f6, #8b5cf6);">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                                        <span class="text-muted small">ID: #{{ $user->id }}</span>
                                    </div>
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Username</span> <span>{{ $user->username }}</span></li>
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Email Address</span> <span>{{ $user->email }}</span></li>
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Phone Number</span> <span>{{ $user->phone }}</span></li>
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Referral Code</span> <code class="text-warning fw-bold">{{ $user->referral_code }}</code></li>
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Upline ID</span> <span>{{ $user->referred_by ?? 'None (Direct registration)' }}</span></li>
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Total Investments</span> <span>${{ number_format(\App\Models\Investment::where('user_id', $user->id)->sum('amount'), 2) }}</span></li>
                                        <li class="list-group-item bg-transparent border-secondary border-opacity-25 text-white d-flex justify-content-between px-0"><span class="text-muted">Direct Referrals</span> <span>{{ \App\Models\User::where('referred_by', $user->id)->count() }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Password Modal -->
                    <div class="modal fade" id="passModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark border border-secondary border-opacity-50 rounded-4">
                                <div class="modal-header border-bottom border-secondary border-opacity-25">
                                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-key text-warning me-2"></i> Reset Password</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <p class="text-muted small mb-3">Resetting password for <strong>{{ $user->name }} ({{ $user->username }})</strong>.</p>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">New Password</label>
                                            <input type="password" name="password" class="form-control bg-dark border-secondary text-white" required minlength="8">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">Confirm New Password</label>
                                            <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" required minlength="8">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-secondary border-opacity-25">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-warning fw-bold">Reset Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
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
