@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-white mb-0"><i class="bi bi-layers text-primary me-2"></i> Investment Plans</h2>
            <button class="btn btn-premium px-4" data-bs-toggle="modal" data-bs-target="#createPlanModal"><i class="bi bi-plus-lg me-2"></i> Create New Plan</button>
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
                            <th class="text-muted small text-uppercase">Plan Name</th>
                            <th class="text-muted small text-uppercase">Min - Max Deposit</th>
                            <th class="text-muted small text-uppercase">Daily ROI (%)</th>
                            <th class="text-muted small text-uppercase">Status</th>
                            <th class="text-muted small text-uppercase text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                        <tr>
                            <td class="fw-bold text-white">{{ $plan->name }}</td>
                            <td><span class="text-success">${{ number_format($plan->min_amount) }}</span> - <span class="text-info">${{ number_format($plan->max_amount) }}</span></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $plan->min_roi }}% - {{ $plan->max_roi }}%</span></td>
                            <td>
                                @if($plan->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.plans.toggle', $plan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $plan->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }} me-1" title="Toggle Status">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#editPlanModal{{ $plan->id }}" title="Edit Plan">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Plan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Plan Modal -->
                        <div class="modal fade" id="editPlanModal{{ $plan->id }}" tabindex="-1" data-bs-theme="dark">
                            <div class="modal-dialog">
                                <div class="modal-content glass-card border-0">
                                    <div class="modal-header border-secondary border-opacity-25">
                                        <h5 class="modal-title fw-bold text-white">Edit Plan: {{ $plan->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Plan Name</label>
                                                <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ $plan->name }}" required>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label text-muted small">Min Deposit ($)</label>
                                                    <input type="number" name="min_amount" class="form-control bg-dark border-secondary text-white" value="{{ $plan->min_amount }}" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted small">Max Deposit ($)</label>
                                                    <input type="number" name="max_amount" class="form-control bg-dark border-secondary text-white" value="{{ $plan->max_amount }}" required>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label text-muted small">Min ROI (%)</label>
                                                    <input type="number" step="0.1" name="min_roi" class="form-control bg-dark border-secondary text-white" value="{{ $plan->min_roi }}" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted small">Max ROI (%)</label>
                                                    <input type="number" step="0.1" name="max_roi" class="form-control bg-dark border-secondary text-white" value="{{ $plan->max_roi }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Status</label>
                                                <select name="status" class="form-select bg-dark border-secondary text-white" required>
                                                    <option value="active" {{ $plan->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $plan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-secondary border-opacity-25">
                                            <button type="submit" class="btn btn-premium w-100 fw-bold">Update Plan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No investment plans found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Plan Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-plus-circle text-primary me-2"></i> Create New Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.plans.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Plan Name</label>
                        <input type="text" name="name" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small">Min Deposit ($)</label>
                            <input type="number" name="min_amount" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small">Max Deposit ($)</label>
                            <input type="number" name="max_amount" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small">Min ROI (%)</label>
                            <input type="number" step="0.1" name="min_roi" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small">Max ROI (%)</label>
                            <input type="number" step="0.1" name="max_roi" class="form-control bg-dark border-secondary text-white" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Status</label>
                        <select name="status" class="form-select bg-dark border-secondary text-white" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="submit" class="btn btn-premium w-100 fw-bold">Create Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
