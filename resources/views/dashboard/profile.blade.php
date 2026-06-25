@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><i class="bi bi-person-circle text-primary me-2"></i> My Profile</h2>
        </div>

        <div class="glass-card p-4 p-md-5 border-primary border-opacity-25">
            <div class="text-center mb-5">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle d-block mx-auto mb-3 border border-secondary" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-lg mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; color: white; background: linear-gradient(135deg, #3b82f6, #8b5cf6);">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <h4 class="fw-bold text-white">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-0">Member since {{ auth()->user()->created_at->format('M Y') }}</p>
                <div class="mt-2">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-shield-check me-1"></i> Account Active</span>
                </div>
            </div>

            <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label text-muted small text-uppercase fw-bold">Upload Avatar Profile Picture</label>
                        <input type="file" name="avatar" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Full Name</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ auth()->user()->name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Username</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-at"></i></span>
                            <input type="text" class="form-control bg-dark border-secondary text-muted" value="{{ auth()->user()->username }}" readonly disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ auth()->user()->email }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Phone Number</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control bg-dark border-secondary text-white" value="{{ auth()->user()->phone }}" required>
                        </div>
                    </div>
                    <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-25 text-end">
                        <button type="submit" class="btn btn-premium px-4"><i class="bi bi-save me-2"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
