@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><i class="bi bi-gear-fill text-primary me-2"></i> Account Settings</h2>
        </div>

        <div class="glass-card p-4 p-md-5 border-warning border-opacity-25 mb-4">
            <h4 class="fw-bold text-white border-bottom border-secondary pb-3 mb-4"><i class="bi bi-shield-lock text-warning me-2"></i> Change Password</h4>
            
            <form action="{{ route('dashboard.password.update') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Current Password</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-warning"><i class="bi bi-unlock"></i></span>
                        <input type="password" name="current_password" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">New Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-dark border-secondary text-success"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-dark border-secondary text-white" required minlength="8">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Confirm New Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-dark border-secondary text-success"><i class="bi bi-shield-check"></i></span>
                            <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" required minlength="8">
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-premium px-4"><i class="bi bi-key me-2"></i> Update Password</button>
                </div>
            </form>
        </div>

        <div class="glass-card p-4 border-info border-opacity-25">
            <h5 class="fw-bold text-white mb-3"><i class="bi bi-bell text-info me-2"></i> Notification Preferences</h5>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input border-secondary" type="checkbox" id="emailNotif" checked>
                <label class="form-check-label text-muted" for="emailNotif">Email notifications for deposits/withdrawals</label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input border-secondary" type="checkbox" id="roiNotif" checked>
                <label class="form-check-label text-muted" for="roiNotif">Daily ROI distribution alerts</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input border-secondary" type="checkbox" id="teamNotif" checked>
                <label class="form-check-label text-muted" for="teamNotif">New team member registration alerts</label>
            </div>
        </div>
    </div>
</div>
@endsection
