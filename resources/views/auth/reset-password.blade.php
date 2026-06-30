@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 p-md-5 border-success border-opacity-50">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-white">Reset Password</h3>
                <p class="text-muted">Enter the 6-digit code sent to your email and your new password</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ old('email', request()->email) }}" required readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Verification Code (6-Digit)</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-key"></i></span>
                        <input type="text" name="code" class="form-control bg-dark border-secondary text-white text-center fw-bold fs-4" placeholder="123456" maxlength="6" required autofocus>
                    </div>
                    @error('code')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">New Password</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Confirm New Password</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
                    </div>
                </div>

                @if(setting('enable_recaptcha', false))
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key') }}" data-theme="dark"></div>
                </div>
                @endif

                <button type="submit" class="btn btn-premium w-100 mb-3 py-3 fs-5">Reset Password <i class="bi bi-shield-check ms-1"></i></button>
            </form>
        </div>
    </div>
</div>

@if(setting('enable_recaptcha', false))
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif
@endsection
