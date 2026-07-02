@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-journal-text me-2"></i> Administrative Audit Logs</h2>
        <p class="text-muted small">Real-time tracking of administrator actions and platform activities.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
</div>

<!-- Search Audit Logs -->
<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <form action="{{ route('admin.audit-logs') }}" method="GET" class="row g-3">
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search by activity name, IP address, administrator name..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-warning fw-bold flex-grow-1">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.audit-logs') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Audit Logs List -->
<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0 small">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>User / Administrator</th>
                    <th>Action Activity</th>
                    <th>IP Address</th>
                    <th>User Agent / Browser</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($log->user)
                                <div class="fw-bold text-white">{{ $log->user->name }}</div>
                                <small class="text-muted">({{ $log->user->username }})</small>
                                @if($log->user->is_admin)
                                    <span class="badge bg-warning text-dark mt-1" style="font-size: 0.7rem;">Admin</span>
                                @endif
                            @else
                                <span class="text-danger small">System / Guest</span>
                            @endif
                        </td>
                        <td class="fw-bold text-white">{{ $log->action }}</td>
                        <td><code class="text-info">{{ $log->ip_address }}</code></td>
                        <td class="text-muted small">{{ Str::limit($log->user_agent, 65) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-text fs-2 mb-2 d-block opacity-50"></i>
                            No audit log logs match your search queries.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
