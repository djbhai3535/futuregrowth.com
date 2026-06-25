@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-arrow-up-circle-fill me-2"></i> Withdrawals Center</h2>
        <p class="text-muted small">Process client withdrawal requests, pay to addresses, and manage logs.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
</div>

<div class="glass-card p-4" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Requested Amount</th>
                    <th>Fee</th>
                    <th>Net Payout</th>
                    <th>Destination Wallet</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $with)
                    <tr>
                        <td>
                            <h6 class="fw-bold text-white mb-0">{{ $with->user->name }}</h6>
                            <small class="text-muted d-block">{{ $with->user->email }} | {{ $with->user->username }}</small>
                        </td>
                        <td class="fw-bold text-white">${{ number_format($with->amount, 2) }}</td>
                        <td class="text-muted">${{ number_format($with->fee, 2) }}</td>
                        <td class="fw-bold text-warning">${{ number_format($with->net_amount, 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="max-width: 250px;">
                                <code class="text-info text-truncate" id="address-{{ $with->id }}" style="font-size: 0.85rem;">{{ $with->wallet_address }}</code>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" onclick="navigator.clipboard.writeText('{{ $with->wallet_address }}')" title="Copy Address"><i class="bi bi-copy" style="font-size: 0.75rem;"></i></button>
                                <a href="https://tronscan.org/#/address/{{ $with->wallet_address }}" target="_blank" class="btn btn-sm btn-outline-info py-0 px-1" title="View on TronScan"><i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i></a>
                            </div>
                        </td>
                        <td>
                            @if($with->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Approved</span>
                            @elseif($with->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i> Rejected</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="bi bi-clock-fill me-1"></i> Pending Payment</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $with->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-end">
                            @if($with->status === 'pending')
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('admin.withdrawals.approve', $with->id) }}" method="POST" onsubmit="return confirm('Has the transaction been executed to {{ $with->wallet_address }}? Click OK to confirm approval.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.withdrawals.reject', $with->id) }}" method="POST" onsubmit="return confirm('Reject this withdrawal and refund ${{ number_format($with->amount, 2) }} back to user\'s balance?')">
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
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-2 mb-2 d-block opacity-50"></i>
                            No withdrawal requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection
