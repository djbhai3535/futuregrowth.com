@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6" data-aos="fade-up">
        <div class="glass-card p-4 p-md-5 border-danger border-opacity-50 text-center">
            <h3 class="fw-bold text-white mb-3">Admin Security Verification</h3>
            <p class="text-muted mb-4">A 2-factor authentication code has been sent to your administrator email. Please enter it below to access the secure administrative control deck.</p>

            @if(session('error'))
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger text-center mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.2fa.verify') }}">
                @csrf
                <div class="mb-4">
                    <input type="text" name="code" class="form-control bg-dark border-secondary text-white text-center fs-2 fw-bold" style="letter-spacing: 10px;" placeholder="123456" maxlength="6" required autofocus>
                    @error('code')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                @if(setting('enable_recaptcha', false))
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key') }}" data-theme="dark"></div>
                </div>
                @endif

                <button type="submit" class="btn btn-premium w-100 mb-4 py-3 fs-5 btn-pulse shadow-lg" style="background: linear-gradient(135deg, #ef4444, #b91c1c); border-color: #dc2626;">Verify & Unlock Dashboard <i class="bi bi-shield-lock-fill ms-2"></i></button>
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
