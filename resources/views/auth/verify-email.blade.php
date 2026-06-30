@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 p-md-5 border-warning border-opacity-50 text-center">
            <h3 class="fw-bold text-white mb-3">Email Verification Required</h3>
            <p class="text-muted mb-4">We have sent a 6-digit verification code to your email. Please enter it below to activate your account and claim your <strong>${{ setting('signup_bonus_amount', 7) }}</strong> Signup Bonus.</p>

            @if(session('success'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success text-success text-center mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ session('user_id') ?? $userId ?? '' }}">
                
                <div class="mb-4">
                    <input type="text" name="code" class="form-control bg-dark border-secondary text-white text-center fs-2 fw-bold" style="letter-spacing: 10px;" placeholder="123456" maxlength="6" required autofocus>
                    @error('code')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                    @error('user_id')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                @if(setting('enable_recaptcha', false))
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key') }}" data-theme="dark"></div>
                </div>
                @endif

                <button type="submit" class="btn btn-premium w-100 mb-4 py-3 fs-5 btn-pulse shadow-lg">Verify & Activate Account <i class="bi bi-patch-check ms-2"></i></button>
            </form>

            <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                @csrf
                <input type="hidden" name="user_id" value="{{ session('user_id') ?? $userId ?? '' }}">
                <span class="text-muted small">Didn't receive the code?</span>
                <button type="submit" class="btn btn-link text-info fw-bold p-0 small text-decoration-none ms-1">Resend Code</button>
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
