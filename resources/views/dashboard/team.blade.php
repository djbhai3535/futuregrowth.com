@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">My Team</h2>
    <p class="text-muted">Track your direct referrals and team structure.</p>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Username</th>
                    <th>Joined</th>
                    <th>Active Investments</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $ref)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ substr($ref->name, 0, 1) }}
                            </div>
                            <span class="fw-bold">{{ $ref->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $ref->username }}</td>
                    <td class="text-muted small">{{ $ref->created_at->format('M d, Y') }}</td>
                    <td class="fw-bold text-success">${{ number_format($ref->investments()->where('status', 'active')->sum('amount'), 2) }}</td>
                    <td>
                        @if($ref->status == 'active')
                            <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger">Banned</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                        You don't have any referrals yet. Share your link to start earning!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
