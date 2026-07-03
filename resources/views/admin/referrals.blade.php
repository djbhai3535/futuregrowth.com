@extends('layouts.app')

@section('content')
<style>
    .level-header {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .level-header:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    .level-badge {
        font-size: 0.9rem;
        font-weight: 700;
        min-width: 90px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-diagram-3 me-2"></i> Referral Tree Explorer</h2>
        <p class="text-muted small">Eager-loaded 10-level downline statistics and sponsor relationship inspector.</p>
    </div>
    <div>
        <form action="{{ route('admin.users.rebuild-tree') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-warning fw-bold"><i class="bi bi-shield-check"></i> Rebuild Integrity Tree</button>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Sponsor Explorer Control -->
    <div class="col-12" data-aos="fade-up">
        <div class="glass-card p-4">
            <h5 class="fw-bold text-white mb-3"><i class="bi bi-search me-2 text-warning"></i> Select Downline Node</h5>
            <form action="{{ route('admin.referrals') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-8 col-lg-9">
                    <label class="form-label text-muted small fw-bold">Search User Profile</label>
                    <select class="form-select bg-dark text-white border-secondary select2-search" name="user_id" required>
                        <option value="">-- Choose User --</option>
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}" {{ isset($selectedUser) && $selectedUser->id === $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->username }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-3">
                    <button type="submit" class="btn btn-warning w-100 fw-bold text-dark"><i class="bi bi-diagram-2"></i> Map Downlines</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($selectedUser))
<div class="row g-4 mb-4" data-aos="fade-up">
    <!-- Active Node Summary -->
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100 text-center">
            <div class="avatar-container mb-3 d-inline-block">
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm" style="width: 80px; height: 80px; margin: 0 auto;">
                    {{ strtoupper(substr($selectedUser->name, 0, 2)) }}
                </div>
            </div>
            <h4 class="fw-bold text-white mb-1">{{ $selectedUser->name }}</h4>
            <p class="text-warning small mb-3">@&nbsp;{{ $selectedUser->username }}</p>
            
            <hr class="border-secondary my-3">
            
            <div class="text-start">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Current Sponsor:</span> <strong class="text-white">{{ $selectedUser->referrer ? $selectedUser->referrer->name . ' (@' . $selectedUser->referrer->username . ')' : 'None' }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Total Directs:</span> <strong class="text-white">{{ \App\Models\User::where('referred_by', $selectedUser->id)->count() }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Status:</span> <span class="badge bg-{{ $selectedUser->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($selectedUser->status) }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Wallet Balance:</span> <strong class="text-success">${{ number_format($selectedUser->wallet->deposit_balance + $selectedUser->wallet->roi_balance + $selectedUser->wallet->referral_balance + $selectedUser->wallet->bonus_balance, 2) }}</strong></div>
            </div>

            <hr class="border-secondary my-3">

            <!-- Sponsor Management Actions -->
            <h6 class="fw-bold text-white text-start mb-3"><i class="bi bi-person-gear text-warning"></i> Sponsor Management</h6>
            
            <!-- Update Sponsor -->
            <form action="{{ route('admin.users.change-sponsor') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                <div class="input-group input-group-sm">
                    <select class="form-select bg-dark text-white border-secondary" name="new_sponsor_id" required>
                        <option value="">Move to Sponsor...</option>
                        @foreach($allUsers as $u)
                            @if($u->id !== $selectedUser->id)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->username }})</option>
                            @endif
                        @endforeach
                    </select>
                    <button class="btn btn-warning fw-bold text-dark" type="submit">Update</button>
                </div>
            </form>

            <!-- Disconnect Sponsor -->
            <form action="{{ route('admin.users.remove-referral') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold" onclick="return confirm('Are you sure you want to disconnect sponsor from this user?')"><i class="bi bi-person-dash"></i> Disconnect Sponsor</button>
            </form>
        </div>
    </div>

    <!-- Levels Downline Details -->
    <div class="col-lg-8">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold text-white mb-4"><i class="bi bi-diagram-3-fill text-warning me-2"></i> 10-Level Downline Statistics</h5>
            
            <div class="accordion border-0" id="referralAccordion">
                @for($l = 1; $l <= 10; $l++)
                    @php
                        $levelData = $levelsData[$l];
                    @endphp
                    <div class="accordion-item bg-dark border border-secondary border-opacity-25 rounded mb-3 overflow-hidden">
                        
                        <div class="accordion-header" id="headingLevel{{ $l }}">
                            <div class="level-header d-flex flex-wrap align-items-center justify-content-between p-3" data-bs-toggle="collapse" data-bs-target="#collapseLevel{{ $l }}">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark level-badge">LEVEL {{ $l }}</span>
                                    <span class="text-white fw-bold">({{ $levelData['users']->count() }} Users)</span>
                                </div>
                                <div class="d-flex gap-4 text-end d-none d-md-flex">
                                    <div><small class="text-muted d-block small">Deposits</small><strong class="text-white">${{ number_format($levelData['total_deposits'], 0) }}</strong></div>
                                    <div><small class="text-muted d-block small">Investments</small><strong class="text-info">${{ number_format($levelData['total_investments'], 0) }}</strong></div>
                                    <div><small class="text-muted d-block small">Business Volume</small><strong class="text-success">${{ number_format($levelData['total_business'], 0) }}</strong></div>
                                    <div><small class="text-muted d-block small">Commissions</small><strong class="text-warning">${{ number_format($levelData['total_commission'], 0) }}</strong></div>
                                </div>
                                <i class="bi bi-chevron-down text-warning"></i>
                            </div>
                        </div>

                        <div id="collapseLevel{{ $l }}" class="accordion-collapse collapse" data-bs-parent="#referralAccordion">
                            <div class="accordion-body bg-black bg-opacity-25 border-top border-secondary border-opacity-25 p-3">
                                
                                @if($levelData['users']->isEmpty())
                                    <p class="text-muted small text-center mb-0">No downline users registered at level {{ $l }}</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-dark table-hover align-middle mb-0 small" style="font-size: 0.82rem;">
                                            <thead>
                                                <tr>
                                                    <th>Name (Username)</th>
                                                    <th>Contact</th>
                                                    <th>Join Date</th>
                                                    <th>Status</th>
                                                    <th>Balance</th>
                                                    <th>Deposits</th>
                                                    <th>Withdrawals</th>
                                                    <th>ROI</th>
                                                    <th>Commissions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($levelData['users'] as $user)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $user->id) }}" class="fw-bold text-warning d-block">{{ $user->name }}</a>
                                                        <small class="text-muted">@&nbsp;{{ $user->username }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="d-block">{{ $user->email }}</span>
                                                        <small class="text-muted">{{ $user->phone }}</small>
                                                    </td>
                                                    <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                                                    <td><span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($user->status) }}</span></td>
                                                    <td class="fw-bold text-white">${{ number_format($user->wallet->deposit_balance + $user->wallet->roi_balance + $user->wallet->referral_balance + $user->wallet->bonus_balance, 2) }}</td>
                                                    <td class="text-success">${{ number_format($user->total_dep, 0) }}</td>
                                                    <td class="text-danger">${{ number_format($user->total_with, 0) }}</td>
                                                    <td class="text-warning">${{ number_format($user->total_earned_roi, 0) }}</td>
                                                    <td class="text-info">${{ number_format($user->ref_income, 0) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endif

@endsection
