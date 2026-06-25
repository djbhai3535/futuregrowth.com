@extends('layouts.app')

@section('content')
<style>
    /* Promo Banner */
    .promo-banner { overflow: hidden; white-space: nowrap; background: linear-gradient(90deg, rgba(59,130,246,0.1), rgba(139,92,246,0.1)); border: 1px solid rgba(59,130,246,0.2); padding: 10px 0; border-radius: 12px; }
    .marquee-content { display: inline-block; animation: marquee 20s linear infinite; }
    @keyframes marquee { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
    .marquee-item { display: inline-block; margin-right: 50px; font-weight: 600; font-size: 0.9rem; }
    
    .promo-card { transition: transform 0.3s ease; }
    .promo-card:hover { transform: translateX(5px); }
</style>

<!-- Promotional Marquee -->
<div class="promo-banner mb-4 text-white" data-aos="fade-down">
    <div class="marquee-content">
        <span class="marquee-item"><i class="bi bi-gift text-warning me-1"></i> {{ setting('promo_banner_1', 'Free $10 Signup Bonus Available!') }}</span>
        <span class="marquee-item"><i class="bi bi-rocket-takeoff text-primary me-1"></i> {{ setting('promo_banner_2', 'Build Your Team & Earn up to 10 Levels of Rewards!') }}</span>
        <span class="marquee-item"><i class="bi bi-graph-up-arrow text-success me-1"></i> {{ setting('promo_banner_3', '3X Return on all Investment Plans!') }}</span>
        <span class="marquee-item"><i class="bi bi-whatsapp text-success me-1"></i> {{ setting('promo_banner_4', 'Join our WhatsApp Community!') }}</span>
    </div>
</div>

<!-- Tech Particle Background -->
<div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1;"></div>

<div class="row align-items-center" style="min-height: 70vh;">
    <!-- Promotional Left Column -->
    <div class="col-lg-6 d-none d-lg-block" data-aos="fade-right">
        <div class="pe-5">
            <h1 class="fw-bold mb-4">The Next Generation of <span class="text-gradient">Crypto Investments</span></h1>
            <p class="text-muted fs-5 mb-5">Join thousands of users earning daily returns securely and transparently. No trading experience required.</p>
            
            <div class="d-flex flex-column gap-3">
                <div class="glass-card p-3 promo-card d-flex align-items-center border-success border-opacity-25">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3"><i class="bi bi-cash-coin text-success fs-4"></i></div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">Instant Daily ROI</h5>
                        <p class="text-muted small mb-0">Profits distributed directly to your wallet every 24 hours.</p>
                    </div>
                </div>
                
                <div class="glass-card p-3 promo-card d-flex align-items-center border-info border-opacity-25">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3"><i class="bi bi-diagram-3-fill text-info fs-4"></i></div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">10-Level Team Rewards</h5>
                        <p class="text-muted small mb-0">Earn up to {{ setting('direct_reward_percent', 20) }}% direct commission instantly on referrals.</p>
                    </div>
                </div>
                
                <div class="glass-card p-3 promo-card d-flex align-items-center border-warning border-opacity-25">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3"><i class="bi bi-shield-check text-warning fs-4"></i></div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">Secure & Transparent</h5>
                        <p class="text-muted small mb-0">Enterprise-grade security protecting your funds 24/7.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Login Form Column -->
    <div class="col-lg-6" data-aos="fade-left">
        <div class="glass-card p-4 p-md-5 neon-glow-primary border-primary border-opacity-50 position-relative">
            <!-- Decorative blur -->
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="bi bi-fingerprint display-1 text-primary"></i>
            </div>
            
            <div class="text-center mb-4 position-relative">
                <h3 class="fw-bold text-white">Welcome Back</h3>
                <p class="text-muted">Login to access your premium dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Email Address</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="name@example.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <label class="form-label text-muted small text-uppercase fw-bold">Password</label>
                        <a href="#" class="small text-decoration-none text-info fw-bold">Forgot Password?</a>
                    </div>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-dark border-secondary text-primary"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input border-secondary" id="rememberMe">
                    <label class="form-check-label text-muted small" for="rememberMe">Remember my device</label>
                </div>

                <button type="submit" class="btn btn-premium w-100 mb-3 py-3 fs-5 btn-pulse shadow-lg">Secure Login <i class="bi bi-shield-lock ms-2"></i></button>
                
                <div class="text-center mt-4">
                    <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                        <span class="text-muted small">Don't have an account?</span><br>
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none fs-5 mt-1 d-inline-block">Create Free Account</a>
                        <div class="text-warning small fw-bold mt-1"><i class="bi bi-gift-fill me-1"></i> Claim your ${{ setting('signup_bonus_amount', 10) }} Bonus today!</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- tsParticles Engine -->
<script src="https://cdn.jsdelivr.net/npm/tsparticles-engine@2/tsparticles.engine.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-basic@2/tsparticles.basic.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-interaction-particles-links@2/tsparticles.interaction.particles.links.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-move-base@2/tsparticles.move.base.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-shape-circle@2/tsparticles.shape.circle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-color@2/tsparticles.updater.color.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-opacity@2/tsparticles.updater.opacity.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-size@2/tsparticles.updater.size.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    // Load required tsParticles plugins
    await loadBaseMover(tsParticles);
    await loadCircleShape(tsParticles);
    await loadColorUpdater(tsParticles);
    await loadOpacityUpdater(tsParticles);
    await loadSizeUpdater(tsParticles);
    await loadParticlesLinksInteraction(tsParticles);
    await loadBasic(tsParticles);

    tsParticles.load("tsparticles", {
        fpsLimit: 60,
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: ["#3b82f6", "#8b5cf6", "#10b981", "#38bdf8"] },
            links: { enable: true, color: "#3b82f6", distance: 150, opacity: 0.6, width: 2 },
            move: { enable: true, speed: 1.5, direction: "none", random: true, straight: false, outModes: { default: "bounce" } },
            size: { value: { min: 2, max: 5 } },
            opacity: { value: { min: 0.3, max: 0.8 }, animation: { enable: true, speed: 1, minimumValue: 0.3 } }
        },
        interactivity: {
            detectsOn: "canvas",
            events: {
                onHover: { enable: true, mode: "grab" },
                resize: true
            },
            modes: {
                grab: { distance: 140, links: { opacity: 0.5 } }
            }
        },
        retina_detect: true
    });
});
</script>
@endpush
