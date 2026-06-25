@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Transaction History</h2>
    <p class="text-muted">All your financial records in one place.</p>
</div>

<div class="glass-card p-4">
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
