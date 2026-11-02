@extends('layouts.app')

@section('content')
<div class="mb-4" data-aos="fade-down">
    <h2 class="fw-bold mb-1">Transaction History</h2>
    <p class="text-muted">All your financial records in one place.</p>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" data-aos="fade-up">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Pending Deposit Requests --}}
@if($pendingDeposits->count() > 0)
<div class="glass-card p-4 mb-4 border border-warning border-opacity-25" data-aos="fade-up">
    <h5 class="fw-bold mb-3 text-warning">
        <i class="bi bi-clock-fill me-2"></i>Pending Deposit Requests
        <span class="badge bg-warning text-dark ms-2">{{ $pendingDeposits->count() }}</span>
    </h5>
    <p class="text-muted small mb-3">The following deposits are awaiting admin verification. You will be notified once they are processed.</p>
    <div class="table-responsive">
        <table class="table table-dark table-sm align-middle mb-0">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Network</th>
                    <th>TXID</th>
                    <th>Submitted</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingDeposits as $dep)
                <tr>
                    <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                    <td>
                        @if(($dep->network ?? 'trc20') === 'bep20')
                            <span class="badge bg-warning text-dark">BEP20</span>
                        @else
                            <span class="badge bg-info text-dark">TRC20</span>
                        @endif
                    </td>
                    <td>
                        <code class="text-info" style="font-size:0.8rem;">{{ Str::limit($dep->txid, 30, '...') }}</code>
                    </td>
                    <td class="text-muted small">{{ $dep->created_at->format('M d, Y H:i') }}</td>
                    <td><span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                        <i class="bi bi-clock-fill me-1"></i>Pending Review
                    </span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Deposit History (Approved / Rejected) --}}
@if($depositHistory->count() > 0)
<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <h5 class="fw-bold mb-3 text-white"><i class="bi bi-arrow-down-circle me-2 text-success"></i>Deposit History</h5>
    <div class="table-responsive">
        <table class="table table-dark table-sm align-middle mb-0">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Network</th>
                    <th>TXID</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($depositHistory as $dep)
                <tr>
                    <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                    <td>
                        @if(($dep->network ?? 'trc20') === 'bep20')
                            <span class="badge bg-warning text-dark">BEP20</span>
                        @else
                            <span class="badge bg-info text-dark">TRC20</span>
                        @endif
                    </td>
                    <td>
                        <code class="text-info" style="font-size:0.8rem;">{{ Str::limit($dep->txid, 30, '...') }}</code>
                    </td>
                    <td class="text-muted small">{{ $dep->updated_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if($dep->status === 'approved')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i>Approved
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                <i class="bi bi-x-circle-fill me-1"></i>Rejected
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- All Transactions --}}
<div class="glass-card p-4" data-aos="fade-up">
    <h5 class="fw-bold mb-3 text-white"><i class="bi bi-list-ul me-2 text-info"></i>All Transactions</h5>
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Wallet</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td class="text-muted small">#{{ $tx->id }}</td>
                    <td>
                        @if($tx->type == 'deposit')
                            <span class="badge bg-success bg-opacity-10 text-success">Deposit</span>
                        @elseif($tx->type == 'withdrawal')
                            <span class="badge bg-danger bg-opacity-10 text-danger">Withdrawal</span>
                        @elseif($tx->type == 'roi')
                            <span class="badge bg-info bg-opacity-10 text-info">ROI</span>
                        @elseif($tx->type == 'commission')
                            <span class="badge bg-primary bg-opacity-10 text-primary">Commission</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning">Bonus</span>
                        @endif
                    </td>
                    <td class="fw-bold">${{ number_format($tx->amount, 2) }}</td>
                    <td class="text-muted small">{{ str_replace('_', ' ', Str::title($tx->wallet_type)) }}</td>
                    <td class="text-muted small">{{ $tx->description }}</td>
                    <td>
                        @if($tx->status == 'completed' || $tx->status == 'approved')
                            <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
                        @elseif($tx->status == 'pending')
                            <span class="text-warning small"><i class="bi bi-clock-fill me-1"></i> Pending</span>
                        @else
                            <span class="text-danger small"><i class="bi bi-x-circle-fill me-1"></i> Rejected</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
