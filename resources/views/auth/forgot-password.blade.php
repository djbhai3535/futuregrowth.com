@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 p-md-5 border-primary border-opacity-50">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-white">Forgot Password</h3>
                <p class="text-muted">Enter your email and we'll send you a verification code to reset your password</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success text-success text-center mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                @if(setting('enable_recaptcha', false))
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key') }}" data-theme="dark"></div>
                </div>
                @endif

                <button type="submit" class="btn btn-premium w-100 mb-3 py-3 fs-5">Send Reset Code <i class="bi bi-arrow-right-short ms-1"></i></button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-info text-decoration-none small fw-bold"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
            </div>
        </div>
    </div>
</div>

@if(setting('enable_recaptcha', false))
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif
@endsection
