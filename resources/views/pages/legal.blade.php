@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center" data-aos="fade-down">
        <div class="col-lg-8">
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>

            <div class="glass-card p-5">
                <h1 class="fw-bold mb-4 text-white border-bottom border-secondary border-opacity-50 pb-3">{{ $title }}</h1>
                
                <div class="text-muted" style="line-height: 1.8;">
                    {!! nl2br(e($content)) !!}
                </div>
                
                <div class="mt-5 pt-4 border-top border-secondary border-opacity-50 text-center">
                    <p class="text-muted small mb-0">Last updated: {{ date('F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
