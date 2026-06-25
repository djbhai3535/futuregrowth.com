@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-white mb-0"><i class="bi bi-headset text-primary me-2"></i> Support Tickets</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success fw-bold">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="glass-card p-4 border-primary border-opacity-25">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted small text-uppercase">Ticket ID</th>
                            <th class="text-muted small text-uppercase">User</th>
                            <th class="text-muted small text-uppercase">Subject</th>
                            <th class="text-muted small text-uppercase">Status</th>
                            <th class="text-muted small text-uppercase">Date</th>
                            <th class="text-muted small text-uppercase text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td class="fw-bold text-white">#{{ $ticket->id }}</td>
                            <td>
                                <span class="d-block text-white">{{ $ticket->user->name }}</span>
                                <small class="text-muted">{{ $ticket->user->email }}</small>
                            </td>
                            <td><span class="text-info">{{ $ticket->subject }}</span></td>
                            <td>
                                @if($ticket->status === 'open')
                                    <span class="badge bg-warning text-dark">Open</span>
                                @elseif($ticket->status === 'answered')
                                    <span class="badge bg-success">Answered</span>
                                @else
                                    <span class="badge bg-secondary">Closed</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#replyModal{{ $ticket->id }}">
                                    <i class="bi bi-reply"></i> Reply
                                </button>
                                @if($ticket->status !== 'closed')
                                <form action="{{ route('admin.tickets.close', $ticket->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Close Ticket">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Reply Modal -->
                        <div class="modal fade" id="replyModal{{ $ticket->id }}" tabindex="-1" data-bs-theme="dark">
                            <div class="modal-dialog">
                                <div class="modal-content glass-card border-0">
                                    <div class="modal-header border-secondary border-opacity-25">
                                        <h5 class="modal-title fw-bold text-white">Reply to Ticket #{{ $ticket->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-4 bg-dark border border-secondary rounded p-3 text-white">
                                            <p class="text-muted small mb-1">User Message:</p>
                                            {{ $ticket->message }}
                                        </div>

                                        @if($ticket->screenshot_path)
                                        <div class="mb-4 bg-dark border border-secondary rounded p-3 text-center">
                                            <p class="text-muted small text-start mb-2">Attached Screenshot:</p>
                                            <a href="{{ Storage::url($ticket->screenshot_path) }}" target="_blank">
                                                <img src="{{ Storage::url($ticket->screenshot_path) }}" class="img-fluid rounded border border-secondary" style="max-height: 250px;" alt="Screenshot">
                                            </a>
                                        </div>
                                        @endif
                                        
                                        @if($ticket->reply)
                                        <div class="mb-4 bg-dark border border-success rounded p-3 text-white">
                                            <p class="text-success small fw-bold mb-1">Admin Reply:</p>
                                            {{ $ticket->reply }}
                                        </div>
                                        @endif

                                        <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Your Reply</label>
                                                <textarea name="reply" rows="4" class="form-control bg-dark border-secondary text-white" required placeholder="Type your response here...">{{ $ticket->reply }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-premium w-100 fw-bold">Send Reply</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No support tickets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tickets->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
