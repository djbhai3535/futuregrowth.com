@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center" data-aos="fade-down">
        <div class="col-lg-10">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>

            <div class="glass-card p-5">
                <h1 class="fw-bold mb-4 text-white border-bottom border-secondary border-opacity-50 pb-3"><i class="bi bi-envelope-at text-primary me-2"></i> {{ $title }}</h1>
                
                <p class="text-muted mb-5 fs-5">{{ $contactContent }}</p>

                <div class="row g-4">
                    <!-- Left Side: Contact Information Cards -->
                    <div class="col-md-6">
                        <div class="row g-3">
                            <!-- Support Email -->
                            <div class="col-12">
                                <div class="p-3 border border-secondary border-opacity-50 rounded bg-dark d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="bi bi-headset text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-white">Support Helpdesk</h6>
                                        <a href="mailto:{{ $supportEmail }}" class="text-primary text-decoration-none small">{{ $supportEmail }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Email -->
                            <div class="col-12">
                                <div class="p-3 border border-secondary border-opacity-50 rounded bg-dark d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="bi bi-building text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-white">Corporate Inquiries</h6>
                                        <a href="mailto:{{ $companyEmail }}" class="text-info text-decoration-none small">{{ $companyEmail }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="col-12">
                                <div class="p-3 border border-secondary border-opacity-50 rounded bg-dark d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="bi bi-whatsapp text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-white">WhatsApp Support</h6>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber) }}" target="_blank" class="text-success text-decoration-none small">{{ $whatsappNumber }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Telegram -->
                            <div class="col-12">
                                <div class="p-3 border border-secondary border-opacity-50 rounded bg-dark d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="bi bi-telegram text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-white">Telegram Community</h6>
                                        <a href="{{ $telegramLink }}" target="_blank" class="text-primary text-decoration-none small">Join Telegram Channel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Office Address & Hours -->
                    <div class="col-md-6">
                        <div class="p-4 border border-secondary border-opacity-50 rounded bg-dark h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-bold text-white mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i> Head Office</h5>
                                <p class="text-muted small leading-relaxed mb-4">{{ $officeAddress }}</p>

                                <h5 class="fw-bold text-white mb-3"><i class="bi bi-clock-fill text-warning me-2"></i> Business Hours</h5>
                                <p class="text-muted small leading-relaxed mb-0">{{ $businessHours }}</p>
                            </div>

                            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-bold">
                                    <i class="bi bi-check2-circle me-1"></i> Average Response Time: &lt; 2 Hours
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top border-secondary border-opacity-50 text-center">
                    <p class="text-muted small mb-0">Last updated: {{ date('F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
