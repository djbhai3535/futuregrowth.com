@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-arrow-down-circle-fill me-2"></i> Deposits Center</h2>
        <p class="text-muted small">Verify TXIDs and approve or reject client USDT deposits.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports') }}?type=deposits" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-bar-graph"></i> Export Deposits</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <form action="{{ route('admin.deposits') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label text-muted small fw-bold">Search Deposits</label>
            <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search by TXID, Amount, User name, email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted small fw-bold">Status Filter</label>
            <select name="status" class="form-select bg-dark border-secondary text-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-warning fw-bold flex-grow-1">Search & Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.deposits') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>TXID (Tron Network)</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $dep)
                    <tr>
                        <td>
                            @if($dep->user)
                                <h6 class="fw-bold text-white mb-0">{{ $dep->user->name }}</h6>
                                <small class="text-muted d-block">{{ $dep->user->email }} | {{ $dep->user->username }}</small>
                            @else
                                <span class="text-danger small">Deleted User</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="max-width: 280px;">
                                <code class="text-info text-truncate" id="txid-{{ $dep->id }}" style="font-size: 0.85rem;">{{ $dep->txid }}</code>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" onclick="navigator.clipboard.writeText('{{ $dep->txid }}')" title="Copy TXID"><i class="bi bi-copy" style="font-size: 0.75rem;"></i></button>
                                <a href="https://tronscan.org/#/transaction/{{ $dep->txid }}" target="_blank" class="btn btn-sm btn-outline-info py-0 px-1" title="Verify on TronScan"><i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i></a>
                            </div>
                        </td>
                        <td>
                            @if($dep->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Approved</span>
                            @elseif($dep->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i> Rejected</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="bi bi-clock-fill me-1"></i> Pending Approval</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $dep->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-end">
                            @if($dep->status === 'pending')
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST" onsubmit="return confirm('Confirm receipt of ${{ number_format($dep->amount, 2) }} and approve this deposit?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.deposits.reject', $dep->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this deposit request?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted small">Processed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-2 mb-2 d-block opacity-50"></i>
                            No deposit transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $deposits->links() }}
    </div>
</div>
@endsection
