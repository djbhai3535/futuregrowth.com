@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-white mb-0"><i class="bi bi-file-earmark-pdf text-primary me-2"></i> Document Center</h2>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#uploadDocModal"><i class="bi bi-cloud-upload"></i> Upload PDF</button>
    </div>
</div>

<div class="glass-card p-4 border-primary border-opacity-25">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th class="text-muted text-uppercase small">Title</th>
                    <th class="text-muted text-uppercase small">Type</th>
                    <th class="text-muted text-uppercase small">Status</th>
                    <th class="text-muted text-uppercase small">Date Uploaded</th>
                    <th class="text-muted text-uppercase small text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
                    <td class="text-white fw-bold">
                        <i class="bi bi-file-earmark-text text-info me-2"></i> {{ $doc->title }}
                    </td>
                    <td class="text-uppercase small">{{ $doc->type }}</td>
                    <td>
                        @if($doc->is_active)
                            <span class="badge bg-success">Visible</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $doc->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-eye"></i> View</a>
                        <form action="{{ route('admin.documents.update', $doc->id) }}" method="POST" class="d-inline me-2">
                            @csrf
                            <input type="hidden" name="is_active" value="{{ $doc->is_active ? '0' : '1' }}">
                            <button type="submit" class="btn btn-sm btn-outline-warning">{{ $doc->is_active ? 'Hide' : 'Show' }}</button>
                        </form>
                        <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this document permanently?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No documents uploaded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocModal" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title text-white fw-bold">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Document Title</label>
                        <input type="text" name="title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Terms and Conditions 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">File (PDF, DOCX)</label>
                        <input type="file" name="file" class="form-control bg-dark border-secondary text-white" accept=".pdf,.doc,.docx" required>
                        <div class="form-text text-muted">Max file size: 10MB</div>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium">Upload File</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
