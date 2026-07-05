@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="tech-bg-container py-5 border-bottom border-secondary border-opacity-25 relative">
    <div class="tech-grid-overlay"></div>
    <div id="tsparticles-about" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0; opacity: 0.3;"></div>
    <div class="container position-relative" style="z-index: 1;">
        <div class="row justify-content-center text-center mb-5" data-aos="fade-down">
            <div class="col-lg-8">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 mb-3 fw-bold"><i class="bi bi-cpu-fill me-1"></i> AI-Powered Fintech</span>
                <h1 class="display-4 fw-bold mb-3">About <span class="text-gradient">Our Platform</span></h1>
                <p class="lead text-muted">{{ setting('about_us_text', setting('trust_section_text', 'Our platform is built on transparency and security, providing a sustainable AI-driven investment ecosystem for global users.')) }}</p>
            </div>
        </div>

        <!-- Platform Statistics -->
        <div class="row g-4 mb-5" data-aos="fade-up">
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center h-100 neon-glow-primary hover-lift">
                    <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                    <h3 class="fw-bold mb-1"><span class="countup" data-val="{{ $stats['users'] }}">0</span>+</h3>
                    <p class="text-muted small text-uppercase mb-0">Global Users</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center h-100 neon-glow-success hover-lift">
                    <i class="bi bi-safe-fill display-4 text-success mb-3"></i>
                    <h3 class="fw-bold mb-1">$<span class="countup" data-val="{{ $stats['deposits'] }}">0</span></h3>
                    <p class="text-muted small text-uppercase mb-0">Total Deposited</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center h-100 border-info border-opacity-25 hover-lift">
                    <i class="bi bi-graph-up-arrow display-4 text-info mb-3"></i>
                    <h3 class="fw-bold mb-1">$<span class="countup" data-val="{{ $stats['investments'] }}">0</span></h3>
                    <p class="text-muted small text-uppercase mb-0">Active Portfolios</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="glass-card p-4 text-center h-100 border-warning border-opacity-25 hover-lift">
                    <i class="bi bi-cash-stack display-4 text-warning mb-3"></i>
                    <h3 class="fw-bold mb-1">$<span class="countup" data-val="{{ $stats['withdrawals'] }}">0</span></h3>
                    <p class="text-muted small text-uppercase mb-0">Total Withdrawals</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Security & Trust Indicators -->
    <div class="row mb-5 text-center g-4" data-aos="fade-up">
        <div class="col-12 mb-3">
            <h2 class="fw-bold"><span class="text-gradient">Enterprise-Grade</span> Security</h2>
            <p class="text-muted">Your funds and data are protected by state-of-the-art blockchain technology.</p>
        </div>
        <div class="col-md-4">
            <div class="p-4 rounded-4 bg-dark border border-success border-opacity-25 h-100">
                <i class="bi bi-shield-lock text-success fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold text-white">AES-256 Encryption</h5>
                <p class="text-muted small">All data transmitted and stored is cryptographically secured.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 rounded-4 bg-dark border border-primary border-opacity-25 h-100">
                <i class="bi bi-hdd-network text-primary fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold text-white">Cold Storage</h5>
                <p class="text-muted small">Majority of digital assets are held safely offline.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 rounded-4 bg-dark border border-warning border-opacity-25 h-100">
                <i class="bi bi-lightning-charge text-warning fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold text-white">DDoS Protection</h5>
                <p class="text-muted small">Advanced mitigation ensuring 99.9% platform uptime.</p>
            </div>
        </div>
    </div>

    <!-- How It Works / Ecosystem -->
    <div class="row align-items-center mb-5 py-5 border-top border-secondary border-opacity-25" data-aos="fade-right">
        <div class="col-lg-6 mb-4 mb-lg-0 pe-lg-5">
            <h2 class="fw-bold mb-4">The <span class="text-gradient">Intelligent Ecosystem</span></h2>
            
            <div class="d-flex mb-4">
                <div class="bg-primary bg-opacity-10 rounded text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="bi bi-1-circle fs-3"></i></div>
                <div>
                    <h5 class="text-white fw-bold mb-1">Algorithmic Distribution</h5>
                    <p class="text-muted small mb-0">Our AI-driven engine distributes daily ROI automatically every 24 hours.</p>
                </div>
            </div>
            <div class="d-flex mb-4">
                <div class="bg-success bg-opacity-10 rounded text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="bi bi-2-circle fs-3"></i></div>
                <div>
                    <h5 class="text-white fw-bold mb-1">3X Sustainability Protocol</h5>
                    <p class="text-muted small mb-0">Investments cap at a strict 300% return, guaranteeing long-term platform health.</p>
                </div>
            </div>
            <div class="d-flex mb-4">
                <div class="bg-info bg-opacity-10 rounded text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="bi bi-3-circle fs-3"></i></div>
                <div>
                    <h5 class="text-white fw-bold mb-1">10-Level Matrix Rewards</h5>
                    <p class="text-muted small mb-0">Build a global network and earn multi-tier commissions instantly.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="glass-card p-4 border-info border-opacity-25 position-relative overflow-hidden">
                <div class="tech-grid-overlay"></div>
                <h4 class="fw-bold text-center mb-4 mt-2 text-info">Platform Roadmap</h4>
                <div class="position-relative border-start border-info ms-3 ps-4 pb-2 mb-3">
                    <div class="position-absolute bg-info rounded-circle" style="width: 12px; height: 12px; left: -6px; top: 0; box-shadow: 0 0 10px #0dcaf0;"></div>
                    <h6 class="text-white fw-bold mb-1">Q1: Infrastructure & Core</h6>
                    <p class="text-muted small">Launch of AI routing engine and AES security.</p>
                </div>
                <div class="position-relative border-start border-info ms-3 ps-4 pb-2 mb-3">
                    <div class="position-absolute bg-info rounded-circle" style="width: 12px; height: 12px; left: -6px; top: 0; box-shadow: 0 0 10px #0dcaf0;"></div>
                    <h6 class="text-white fw-bold mb-1">Q2: Global Expansion</h6>
                    <p class="text-muted small">Multi-tier network integration and community growth.</p>
                </div>
                <div class="position-relative ms-3 ps-4 pb-2">
                    <div class="position-absolute bg-secondary rounded-circle" style="width: 12px; height: 12px; left: -6px; top: 0;"></div>
                    <h6 class="text-muted fw-bold mb-1">Q3: Advanced Analytics</h6>
                    <p class="text-muted small">Real-time prediction charts and dynamic algorithms.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Center -->
    <div class="row justify-content-center mb-5 border-top border-secondary border-opacity-25 pt-5" data-aos="fade-up">
        <div class="col-lg-10">
            <div class="glass-card p-5 border-danger border-opacity-25">
                <div class="text-center mb-4">
                    <h2 class="fw-bold"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Official Document Center</h2>
                    <p class="text-muted">Review our official company documentation, policies, and compensation plans.</p>
                </div>
                
                <div class="row g-3">
                    @forelse($documents as $doc)
                    <div class="col-md-6">
                        <div class="p-3 border border-secondary border-opacity-50 rounded bg-dark d-flex justify-content-between align-items-center hover-lift" style="transition: 0.3s;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text fs-3 text-info me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0 text-white">{{ $doc->title }}</h6>
                                    <small class="text-muted text-uppercase">{{ $doc->type }}</small>
                                </div>
                            </div>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> PDF</a>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted">
                        <p>No official documents have been uploaded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="row justify-content-center pt-5 border-top border-secondary border-opacity-25" data-aos="fade-up">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <h2 class="fw-bold"><i class="bi bi-patch-question text-warning me-2"></i> Knowledge Base (FAQ)</h2>
            </div>
            
            <div class="accordion accordion-flush glass-card rounded overflow-hidden" id="faqAccordion">
                @forelse($faqs as $index => $faq)
                <div class="accordion-item bg-transparent border-secondary border-opacity-25">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                            {{ $faq->question }}
                        </button>
                    </h2>
                    <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted pt-0 pb-4" style="line-height: 1.8;">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <p>No FAQs available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.3s, box-shadow 0.3s; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.2) !important; }
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.0/countUp.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-engine@2/tsparticles.engine.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-basic@2/tsparticles.basic.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-interaction-particles-links@2/tsparticles.interaction.particles.links.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-move-base@2/tsparticles.move.base.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-shape-circle@2/tsparticles.shape.circle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-color@2/tsparticles.updater.color.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-opacity@2/tsparticles.updater.opacity.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-updater-size@2/tsparticles.updater.size.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        document.querySelectorAll('.countup').forEach(el => {
            const val = parseFloat(el.getAttribute('data-val'));
            const countUpInst = new countUp.CountUp(el, val, { duration: 2.5 });
            if (!countUpInst.error) countUpInst.start();
        });

        // tsParticles
        if (typeof tsParticles !== 'undefined') {
            await loadBaseMover(tsParticles);
            await loadCircleShape(tsParticles);
            await loadColorUpdater(tsParticles);
            await loadOpacityUpdater(tsParticles);
            await loadSizeUpdater(tsParticles);
            await loadParticlesLinksInteraction(tsParticles);
            await loadBasic(tsParticles);

            tsParticles.load("tsparticles-about", {
                fpsLimit: 60,
                particles: {
                    number: { value: 60, density: { enable: true, value_area: 800 } },
                    color: { value: ["#3b82f6", "#8b5cf6"] },
                    links: { enable: true, color: "#3b82f6", distance: 150, opacity: 0.3, width: 1 },
                    move: { enable: true, speed: 1, direction: "none", random: true, straight: false, outModes: { default: "bounce" } },
                    size: { value: { min: 1, max: 3 } },
                    opacity: { value: { min: 0.2, max: 0.6 } }
                },
                interactivity: {
                    detectsOn: "canvas",
                    events: { onHover: { enable: true, mode: "grab" }, resize: true },
                    modes: { grab: { distance: 140, links: { opacity: 0.5 } } }
                },
                retina_detect: true
            });
        }
    });
</script>
@endpush
