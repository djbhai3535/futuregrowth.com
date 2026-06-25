<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_name', config('app.name', 'Premium Crypto Invest')) }}</title>
    <!-- Favicon -->
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #3b82f6;
            --bg-dark: #09090b; 
            --card-bg: rgba(24, 24, 27, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #f8fafc;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.08), transparent 25%);
            background-attachment: fixed;
        }

        /* Nav */
        .navbar-premium { background: rgba(9, 9, 11, 0.8) !important; backdrop-filter: blur(15px); border-bottom: 1px solid var(--glass-border); }
        
        /* Typography */
        .text-gradient { background: linear-gradient(135deg, #60a5fa, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Glass Cards */
        .glass-card { background-color: var(--card-bg); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 1.25rem; transition: all 0.3s ease; }
        .glass-card:hover { transform: translateY(-5px); border-color: rgba(255,255,255,0.15); box-shadow: 0 15px 35px rgba(0,0,0,0.4); }
        
        /* Buttons */
        .btn-premium { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; border-radius: 0.75rem; font-weight: 600; padding: 0.75rem 1.5rem; color: #fff; transition: all 0.3s ease; }
        .btn-premium:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4); color: #fff; }
        
        /* Hero Section */
        .hero-section { min-height: 80vh; display: flex; align-items: center; position: relative; }
        .hero-glow { position: absolute; top: 20%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, rgba(0,0,0,0) 70%); z-index: -1; }
        
        /* Promo Banner */
        .promo-banner { overflow: hidden; white-space: nowrap; background: linear-gradient(90deg, rgba(59,130,246,0.1), rgba(139,92,246,0.1)); border-bottom: 1px solid rgba(59,130,246,0.2); padding: 10px 0; }
        .marquee-content { display: inline-block; animation: marquee 25s linear infinite; font-weight: 600; font-size: 0.9rem; }
        @keyframes marquee { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
        .marquee-item { display: inline-block; margin-right: 50px; }

        .feature-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
    </style>
</head>
<body>

    <!-- Promotional Marquee -->
    <div class="promo-banner text-white">
        <div class="marquee-content">
            <span class="marquee-item"><i class="bi bi-gift text-warning me-1"></i> {{ setting('promo_banner_1', 'Free $10 Signup Bonus Available!') }}</span>
            <span class="marquee-item"><i class="bi bi-rocket-takeoff text-primary me-1"></i> {{ setting('promo_banner_2', 'Build Your Team & Earn up to 10 Levels of Rewards!') }}</span>
            <span class="marquee-item"><i class="bi bi-graph-up-arrow text-success me-1"></i> {{ setting('promo_banner_3', '3X Return on all Investment Plans!') }}</span>
            <span class="marquee-item"><i class="bi bi-whatsapp text-success me-1"></i> {{ setting('promo_banner_4', 'Join our WhatsApp Community!') }}</span>
        </div>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-premium sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fs-4" href="#">
                @if(setting('site_logo'))
                    <img src="{{ Storage::url(setting('site_logo')) }}" alt="{{ setting('site_name', 'Logo') }}" style="height: 35px;">
                @else
                    <i class="bi bi-layers-fill text-primary"></i> <span class="fw-bold">CRYPTO</span><span class="fw-light">INVEST</span>
                @endif
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto fw-bold">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#plans">Investment Plans</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#referral">Referral Program</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#faq">FAQ</a></li>
                </ul>
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-premium">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-premium rounded-pill px-4">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center tech-bg-container">
        <!-- Tech Particle Background -->
        <div id="tsparticles" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>
        <div class="hero-glow"></div>
        <div class="container position-relative" style="z-index: 1;" data-aos="fade-up" data-aos-duration="1000">
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 mb-4 fw-bold">
                <i class="bi bi-stars me-1"></i> The #1 Rated Crypto Investment Platform
            </span>
            <h1 class="display-3 fw-black mb-4" style="font-weight: 900; letter-spacing: -1px;">
                Multiply Your Wealth<br>With <span class="text-gradient">Intelligent ROI</span>
            </h1>
            <p class="lead text-muted mx-auto mb-5" style="max-width: 600px;">
                {{ setting('homepage_about_text', 'Earn secure daily profits, build a massive 10-level referral team, and achieve up to a 300% (3X) return on your investments automatically.') }}
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-premium btn-lg px-5 py-3 shadow-lg fs-5">Start Investing Now <i class="bi bi-arrow-right ms-2"></i></a>
                @if(setting('whatsapp_button_enabled') && setting('whatsapp_community_link'))
                <a href="{{ setting('whatsapp_community_link') }}" class="btn btn-outline-success btn-lg px-4 py-3 fw-bold rounded-3" target="_blank">
                    <i class="bi bi-whatsapp me-2"></i> Join Community
                </a>
                @endif
            </div>

            <!-- Trust Building Visuals & Global Stats -->
            <div class="row justify-content-center mt-5 pt-5 border-top border-secondary border-opacity-25" data-aos="fade-up" data-aos-delay="300">
                <div class="col-md-3 col-6 mb-4">
                    <div class="mb-2"><i class="bi bi-shield-lock-fill fs-2 text-success" style="filter: drop-shadow(0 0 10px rgba(16,185,129,0.5));"></i></div>
                    <p class="text-success small text-uppercase fw-bold mb-1">AES-256 Encrypted</p>
                    <h5 class="fw-bold text-white mb-0">Secure Platform</h5>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="mb-2"><i class="bi bi-cpu-fill fs-2 text-primary" style="filter: drop-shadow(0 0 10px rgba(59,130,246,0.5));"></i></div>
                    <p class="text-primary small text-uppercase fw-bold mb-1">Smart AI Tech</p>
                    <h5 class="fw-bold text-white mb-0">Automated ROI</h5>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="mb-2"><i class="bi bi-activity fs-2 text-info" style="filter: drop-shadow(0 0 10px rgba(14,165,233,0.5));"></i></div>
                    <p class="text-info small text-uppercase fw-bold mb-1">Real-Time Data</p>
                    <h5 class="fw-bold text-white mb-0">Live Analytics</h5>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="mb-2"><i class="bi bi-diagram-3-fill fs-2 text-warning" style="filter: drop-shadow(0 0 10px rgba(245,158,11,0.5));"></i></div>
                    <p class="text-warning small text-uppercase fw-bold mb-1">10-Level Matrix</p>
                    <h5 class="fw-bold text-white mb-0">Global Community</h5>
                </div>
            </div>
            
            <div class="row justify-content-center mt-3 pt-4 border-top border-secondary border-opacity-25" data-aos="fade-up" data-aos-delay="400">
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="fw-bold text-white mb-0"><span class="countup" data-val="{{ $stats['users'] }}">0</span>+</h2>
                    <p class="text-muted small text-uppercase fw-bold">Active Investors</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="fw-bold text-success mb-0">$<span class="countup" data-val="{{ $stats['deposits'] }}">0</span></h2>
                    <p class="text-muted small text-uppercase fw-bold">Total Deposited</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="fw-bold text-info mb-0">{{ $stats['levels'] }}</h2>
                    <p class="text-muted small text-uppercase fw-bold">Referral Levels</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <h2 class="fw-bold text-warning mb-0">{{ $stats['multiplier'] }}%</h2>
                    <p class="text-muted small text-uppercase fw-bold">Max Return</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Investment Plans -->
    <section id="plans" class="py-5 bg-black bg-opacity-50">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-down">
                <h2 class="fw-bold display-5">Premium <span class="text-gradient">Investment Plans</span></h2>
                <p class="text-muted">Choose the perfect tier for your financial goals. All plans feature automatic 24-hour daily ROI and a strict 300% max payout cap to guarantee long-term platform sustainability.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <!-- Starter Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-card p-5 h-100 position-relative text-center">
                        <h4 class="fw-bold text-white mb-3">Starter Tier</h4>
                        <div class="mb-4">
                            <span class="fs-1 fw-bold text-primary">1.5%</span><span class="text-muted">/daily</span>
                        </div>
                        <ul class="list-unstyled text-start mb-5 text-muted">
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Min Deposit: <b>$50</b></li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Max Deposit: <b>$999</b></li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> 24/7 Automated ROI</li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> 3X Max Return Cap ($2,997)</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 py-3 fw-bold">Choose Starter</a>
                    </div>
                </div>

                <!-- Professional Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-card p-5 h-100 position-relative text-center border-primary border-opacity-50" style="box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);">
                        <div class="position-absolute top-0 start-50 translate-middle badge bg-primary px-3 py-2 rounded-pill fw-bold">MOST POPULAR</div>
                        <h4 class="fw-bold text-white mb-3">Pro Tier</h4>
                        <div class="mb-4">
                            <span class="fs-1 fw-bold text-gradient">2.5%</span><span class="text-muted">/daily</span>
                        </div>
                        <ul class="list-unstyled text-start mb-5 text-muted">
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Min Deposit: <b>$1,000</b></li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Max Deposit: <b>$4,999</b></li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Priority Support</li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> 3X Max Return Cap ($14,997)</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-premium w-100 py-3 fw-bold">Choose Pro</a>
                    </div>
                </div>

                <!-- VIP Plan -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="glass-card p-5 h-100 position-relative text-center border-warning border-opacity-25">
                        <h4 class="fw-bold text-white mb-3 text-warning">VIP Tier</h4>
                        <div class="mb-4">
                            <span class="fs-1 fw-bold text-warning">4.0%</span><span class="text-muted">/daily</span>
                        </div>
                        <ul class="list-unstyled text-start mb-5 text-muted">
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Min Deposit: <b>$5,000</b></li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Max Deposit: <b>Unlimited</b></li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Dedicated Account Manager</li>
                            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> 3X Max Return Cap</li>
                        </ul>
                        <a href="{{ route('register') }}" class="btn btn-outline-warning w-100 py-3 fw-bold">Choose VIP</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Referral Program -->
    <section id="referral" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <h2 class="fw-bold display-5 mb-4">Massive <span class="text-gradient">Team Rewards</span></h2>
                    <p class="text-muted fs-5 mb-4">Don't just invest—build an empire. Our platform features an unparalleled 10-Level deep referral structure. Earn commissions instantly whenever anyone in your downline makes a deposit.</p>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-4">
                            <i class="bi bi-person-fill-up text-primary fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Level 1: {{ setting('direct_reward_percent', 20) }}% Direct Bonus</h4>
                            <p class="text-muted small mb-0">Instantly credited to your withdrawable wallet.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle me-4">
                            <i class="bi bi-diagram-3-fill text-info fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Levels 2-10: Downline Bonus</h4>
                            <p class="text-muted small mb-0">Earn a percentage of every deposit made deep in your network.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="glass-card p-4 text-center border-info border-opacity-25" style="box-shadow: 0 0 30px rgba(14, 165, 233, 0.1);">
                        <i class="bi bi-share-fill display-1 text-info opacity-50 mb-4"></i>
                        <h3 class="fw-bold mb-3">Ready to build your team?</h3>
                        <p class="text-muted mb-4">Create an account to get your unique referral link.</p>
                        <a href="{{ route('register') }}" class="btn btn-premium w-100 py-3 fs-5">Get Your Link</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-down">
                <h2 class="fw-bold display-5">Frequently Asked <span class="text-gradient">Questions</span></h2>
            </div>
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-lg-8">
                    <div class="accordion accordion-flush glass-card rounded overflow-hidden" id="faqAccordion">
                        @forelse($faqs as $index => $faq)
                        <div class="accordion-item bg-transparent border-secondary border-opacity-25">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted pt-0 pb-4">
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
    </section>

    <!-- WhatsApp CTA -->
    @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
    <section class="py-5 bg-dark">
        <div class="container py-4 text-center" data-aos="zoom-in">
            <h2 class="fw-bold text-white mb-3"><i class="bi bi-whatsapp text-success me-2"></i> Join Our Global Community</h2>
            <p class="text-muted fs-5 mb-4 max-w-2xl mx-auto">Connect with thousands of investors, get real-time updates, and receive 24/7 support directly from our expert team.</p>
            <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-whatsapp rounded-pill px-5 py-3 fs-5 fw-bold whatsapp-glow">
                Join WhatsApp Group Now
            </a>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-black py-5 border-top border-secondary border-opacity-25">
        <div class="container text-center">
            <div class="row mb-4 text-center text-md-start">
                <div class="col-md-4 mb-4">
                    <a class="navbar-brand fs-3 mb-3 d-inline-block" href="#">
                        @if(setting('footer_logo'))
                            <img src="{{ Storage::url(setting('footer_logo')) }}" alt="{{ setting('site_name', 'Logo') }}" style="height: 35px;">
                        @elseif(setting('site_logo'))
                            <img src="{{ Storage::url(setting('site_logo')) }}" alt="{{ setting('site_name', 'Logo') }}" style="height: 35px;">
                        @else
                            <i class="bi bi-layers-fill text-primary"></i> <span class="fw-bold text-white">CRYPTO</span><span class="fw-light text-white">INVEST</span>
                        @endif
                    </a>
                    <p class="text-muted small">Secure, Transparent, and Automated Cryptocurrency Investments.</p>
                    <div class="d-flex gap-3 mt-3 justify-content-center justify-content-md-start">
                        <a href="{{ setting('twitter_link', '#') }}" target="_blank" class="text-muted text-decoration-none hover-white"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="{{ setting('telegram_link', '#') }}" target="_blank" class="text-muted text-decoration-none hover-white"><i class="bi bi-telegram fs-5"></i></a>
                        <a href="{{ setting('facebook_link', '#') }}" target="_blank" class="text-muted text-decoration-none hover-white"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="{{ setting('instagram_link', '#') }}" target="_blank" class="text-muted text-decoration-none hover-white"><i class="bi bi-instagram fs-5"></i></a>
                        @if(setting('whatsapp_button_enabled') && setting('whatsapp_community_link'))
                            <a href="{{ setting('whatsapp_community_link') }}" class="text-success text-decoration-none hover-white" target="_blank"><i class="bi bi-whatsapp fs-5"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-muted text-decoration-none hover-white">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-muted text-decoration-none hover-white">Document Center</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-muted text-decoration-none hover-white">Contact Support</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-muted text-decoration-none hover-white">Terms & Conditions</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-muted text-decoration-none hover-white">Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('risk') }}" class="text-muted text-decoration-none hover-white">Risk Disclosure</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-25 pt-4">
                <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ setting('copyright_text', 'CryptoInvest Platform. All rights reserved.') }}</p>
            </div>
        </div>
    </footer>

    <style>
        .hover-white:hover { color: white !important; transition: 0.3s; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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
        AOS.init({ duration: 800, once: true });
        document.addEventListener('DOMContentLoaded', async function() {
            document.querySelectorAll('.countup').forEach(el => {
                new countUp.CountUp(el, parseFloat(el.getAttribute('data-val')), { duration: 2.5 }).start();
            });
            
            // tsParticles for Hero
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
                    events: { onHover: { enable: true, mode: "grab" }, resize: true },
                    modes: { grab: { distance: 140, links: { opacity: 0.5 } } }
                },
                retina_detect: true
            });
        });
    </script>
</body>
</html>
