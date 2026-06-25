@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-white mb-0"><i class="bi bi-question-circle text-primary me-2"></i> Manage FAQs</h2>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addFaqModal"><i class="bi bi-plus-circle"></i> Add FAQ</button>
    </div>
</div>

<div class="glass-card p-4 border-primary border-opacity-25">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th class="text-muted text-uppercase small">Order</th>
                    <th class="text-muted text-uppercase small">Question</th>
                    <th class="text-muted text-uppercase small">Status</th>
                    <th class="text-muted text-uppercase small text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                <tr>
                    <td class="fw-bold text-white">{{ $faq->sort_order }}</td>
                    <td class="text-white fw-bold">{{ $faq->question }}</td>
                    <td>
                        @if($faq->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">Edit</button>
                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this FAQ?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>

                <!-- Edit FAQ Modal -->
                <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1" data-bs-theme="dark">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content glass-card">
                            <div class="modal-header border-secondary border-opacity-25">
                                <h5 class="modal-title text-white fw-bold">Edit FAQ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase">Question</label>
                                        <input type="text" name="question" class="form-control bg-dark border-secondary text-white" value="{{ $faq->question }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase">Answer</label>
                                        <textarea name="answer" class="form-control bg-dark border-secondary text-white" rows="4" required>{{ $faq->answer }}</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label text-muted small text-uppercase">Sort Order</label>
                                            <input type="number" name="sort_order" class="form-control bg-dark border-secondary text-white" value="{{ $faq->sort_order }}" required>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label text-muted small text-uppercase">Status</label>
                                            <select name="is_active" class="form-select bg-dark border-secondary text-white">
                                                <option value="1" {{ $faq->is_active ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !$faq->is_active ? 'selected' : '' }}>Hidden</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-secondary border-opacity-25">
                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-premium">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No FAQs added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title text-white fw-bold">Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Question</label>
                        <input type="text" name="question" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Answer</label>
                        <textarea name="answer" class="form-control bg-dark border-secondary text-white" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted small text-uppercase">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control bg-dark border-secondary text-white" value="0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted small text-uppercase">Status</label>
                            <select name="is_active" class="form-select bg-dark border-secondary text-white">
                                <option value="1">Active</option>
                                <option value="0">Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium">Save FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
