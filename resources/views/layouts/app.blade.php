<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('seo_meta_title', setting('site_name', config('app.name', 'FutureGrowth.tech'))) }}</title>
    <meta name="description" content="{{ setting('seo_meta_description', 'Intelligent USDT Automated ROI Platform') }}">
    @if(setting('site_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('site_og_image')) }}">
    @endif
    <!-- Favicon -->
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.5);
            --bg-dark: #09090b; /* Even darker for premium contrast */
            --card-bg: rgba(24, 24, 27, 0.6);
            --text-muted: #a1a1aa;
            --glass-border: rgba(255, 255, 255, 0.08);
            --neon-green: #10b981;
            --neon-purple: #8b5cf6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.08), transparent 25%);
            background-attachment: fixed;
            color: #f8fafc;
            overflow-x: hidden;
            padding-bottom: 70px; /* Space for mobile nav */
        }
        
        /* Premium Glassmorphism */
        .glass-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.03), transparent);
            transform: skewX(-20deg);
            transition: 0.5s;
        }
        .glass-card:hover::before {
            left: 150%;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255,255,255,0.15);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.4), 0 0 15px rgba(59, 130, 246, 0.1);
        }

        /* Neon Glows */
        .neon-glow-primary { box-shadow: 0 0 15px var(--primary-glow); }
        .neon-glow-success { box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
        .neon-border-primary { border: 1px solid var(--primary-color) !important; }

        /* Animated Buttons */
        .btn-premium {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.3s ease;
        }
        .btn-premium::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 0;
        }
        .btn-premium:hover::after { opacity: 1; }
        .btn-premium:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
            color: #fff;
        }
        .btn-pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }

        /* Gradients */
        .text-gradient {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Tech Background Animations */
        .tech-bg-container { position: relative; overflow: hidden; z-index: 1; }
        .tech-grid-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
            background-size: 20px 20px; z-index: -1; opacity: 0.3; animation: grid-move 20s linear infinite;
        }
        .tech-data-stream {
            position: absolute; top: -100%; left: 50%; width: 2px; height: 100px;
            background: linear-gradient(to bottom, transparent, rgba(16, 185, 129, 0.8), transparent);
            animation: data-drop 3s linear infinite; z-index: -1;
        }
        .tech-data-stream-2 { left: 20%; animation-delay: 1s; animation-duration: 4s; }
        .tech-data-stream-3 { left: 80%; animation-delay: 2s; animation-duration: 2.5s; }
        @keyframes grid-move { 0% { transform: translateY(0); } 100% { transform: translateY(20px); } }
        @keyframes data-drop { 0% { top: -100px; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 100%; opacity: 0; } }
        
        
        /* Navbar */
        .navbar-premium {
            background: rgba(9, 9, 11, 0.8) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--glass-border);
        }

        /* Mobile Nav */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(24, 24, 27, 0.9);
            backdrop-filter: blur(15px);
            border-top: 1px solid var(--glass-border);
            z-index: 1030;
            padding: 0.5rem 0;
        }
        .mobile-nav-item {
            text-align: center;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: color 0.2s;
        }
        .mobile-nav-item i { font-size: 1.25rem; margin-bottom: 2px; }
        .mobile-nav-item.active { color: var(--primary-color); }
        @media (max-width: 991px) {
            .mobile-bottom-nav { display: flex; justify-content: space-around; }
            .navbar-desktop-links { display: none !important; }
            body { padding-bottom: 80px; }
        }

        /* Help FAB Widget */
        .support-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.5);
            cursor: pointer;
            z-index: 1040;
            transition: transform 0.3s;
        }
        .support-fab:hover { transform: scale(1.1) rotate(10deg); }
        @media (max-width: 991px) { .support-fab { bottom: 80px; right: 15px; } }

        /* WhatsApp Button */
        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366, #128C7E);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
            color: white;
        }
        .whatsapp-glow {
            animation: whatsapp-pulse 2s infinite;
        }
        @keyframes whatsapp-pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }

        /* Toast positioning */
        .toast-container { z-index: 1050; }
    </style>
</head>
<body>
    @if(setting('announcement_bar'))
        <div class="bg-warning text-dark text-center py-2 fw-bold small" style="letter-spacing: 0.5px; z-index: 1040; position: relative;">
            <i class="bi bi-megaphone-fill me-2"></i> {{ setting('announcement_bar') }}
        </div>
    @endif
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-premium sticky-top">
        <div class="container">
            <a class="navbar-brand fs-4" href="{{ route('dashboard') }}">
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
                <ul class="navbar-nav me-auto navbar-desktop-links">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.profile') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.profile') }}">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.investments') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.investments') }}">Investments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.deposits') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.deposits') }}">Deposit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.withdrawals') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.withdrawals') }}">Withdraw</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.history') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.history') }}">Earnings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.team') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.team') }}">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpCenterModal">Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.settings') ? 'active text-primary fw-bold' : '' }}" href="{{ route('dashboard.settings') }}">Settings</a>
                    </li>
                    @if(auth()->user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                    </li>
                    @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link fw-bold">Login</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="btn btn-premium ms-3">Get Started</a></li>
                    @else
                        <li class="nav-item me-2 d-none d-lg-block">
                            <a class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-bold btn-pulse" href="#" onclick="copyToClipboard('{{ url('/register?ref='.auth()->user()->referral_code) }}', this)">
                                <i class="bi bi-person-plus-fill"></i> Invite Friends
                            </a>
                        </li>
                        @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
                            <li class="nav-item me-3 d-none d-lg-block">
                                <a class="btn btn-whatsapp rounded-pill btn-sm px-3 fw-bold whatsapp-glow" href="{{ setting('whatsapp_community_link') }}" target="_blank">
                                    <i class="bi bi-whatsapp"></i> Community
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-pill neon-glow-primary bg-dark border border-secondary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" style="transition: 0.3s;">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" class="rounded-circle shadow-sm" style="width: 32px; height: 32px; object-fit: cover;" alt="Avatar">
                                @else
                                    <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 32px; height: 32px; color: white; background: linear-gradient(135deg, #3b82f6, #8b5cf6);">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="fw-bold text-white small">{{ auth()->user()->username ?? auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end glass-card border-primary border-opacity-50 shadow-lg rounded-4 mt-3 p-2" style="min-width: 260px; animation: slideDown 0.3s ease;">
                                <!-- User Status & Balance Preview -->
                                <li class="px-3 py-3 text-center border-bottom border-secondary border-opacity-25 mb-2 position-relative overflow-hidden rounded-3 bg-dark">
                                    <div class="tech-grid-overlay" style="opacity: 0.1;"></div>
                                    <div class="mb-2">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3"><i class="bi bi-shield-check me-1"></i> Active Account</span>
                                    </div>
                                    @php
                                        $headerWallet = \App\Models\Wallet::where('user_id', auth()->id())->first();
                                        $headerTotal = $headerWallet ? ($headerWallet->deposit_balance + $headerWallet->roi_balance + $headerWallet->referral_balance + $headerWallet->bonus_balance) : 0;
                                    @endphp
                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Total Portfolio Value</small>
                                    <h4 class="text-white fw-bold mb-0 mt-1">$<span class="text-gradient">{{ number_format($headerTotal, 2) }}</span></h4>
                                </li>
                                
                                <li><a class="dropdown-item text-white rounded-3 py-2 custom-hover d-flex align-items-center" href="{{ route('dashboard.profile') }}"><div class="bg-primary bg-opacity-10 p-2 rounded me-3"><i class="bi bi-person-circle text-primary"></i></div> <span class="fw-bold small">My Profile</span></a></li>
                                <li><a class="dropdown-item text-white rounded-3 py-2 custom-hover d-flex align-items-center" href="{{ route('dashboard.settings') }}"><div class="bg-success bg-opacity-10 p-2 rounded me-3"><i class="bi bi-shield-lock text-success"></i></div> <span class="fw-bold small">Security Settings</span></a></li>
                                <li><a class="dropdown-item text-white rounded-3 py-2 custom-hover d-flex align-items-center" href="{{ route('dashboard.team') }}"><div class="bg-info bg-opacity-10 p-2 rounded me-3"><i class="bi bi-people text-info"></i></div> <span class="fw-bold small">Referral Center</span></a></li>
                                <li><a class="dropdown-item text-white rounded-3 py-2 custom-hover d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#helpCenterModal"><div class="bg-warning bg-opacity-10 p-2 rounded me-3"><i class="bi bi-headset text-warning"></i></div> <span class="fw-bold small">Support Center</span></a></li>
                                
                                <li><hr class="dropdown-divider border-secondary border-opacity-25 my-2"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold rounded-3 py-2 custom-hover d-flex align-items-center"><div class="bg-danger bg-opacity-10 p-2 rounded me-3"><i class="bi bi-power text-danger"></i></div> <span class="small">Secure Logout</span></button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation (Auth Only) -->
    @auth
    <div class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('dashboard.investments') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.investments') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Invest</span>
        </a>
        <a href="{{ route('dashboard.team') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.team') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Team</span>
        </a>
        <a href="{{ route('dashboard.history') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.history') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Earnings</span>
        </a>
        @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
        <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="mobile-nav-item text-success">
            <i class="bi bi-whatsapp"></i>
            <span>Community</span>
        </a>
        @endif
        <a href="{{ route('dashboard.profile') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profile</span>
        </a>
    </div>

    <!-- Removed Mobile Profile Modal in favor of direct routing to Profile Page -->
    @endauth

    <main class="container py-4">
        <!-- Dynamic WhatsApp Community Banner -->
        @auth
            @if(setting('enable_whatsapp_banner', 1) == 1 && setting('whatsapp_community_link'))
                <div class="whatsapp-sticky-banner py-3 px-4 mb-4 glass-card border-success border-opacity-25" style="background: rgba(37, 211, 102, 0.04); position: relative; overflow: hidden; border-radius: 1rem; border-color: rgba(37, 211, 102, 0.25) !important;">
                    <div class="tech-grid-overlay" style="opacity: 0.05;"></div>
                    <div class="row align-items-center g-3">
                        <div class="col-md-9 d-flex align-items-start gap-3">
                            <div class="bg-success bg-opacity-15 p-3 rounded-circle text-success shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; flex-shrink: 0; animation: whatsapp-pulse 2s infinite;">
                                <i class="bi bi-whatsapp fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-success d-flex align-items-center gap-2">
                                    💬 {{ setting('whatsapp_banner_title', 'Join our Official WhatsApp Community') }}
                                </h6>
                                <p class="text-muted small mb-0">
                                    {{ setting('whatsapp_banner_text', 'Stay updated with announcements, deposit confirmations, promotions, support, investment news.') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-whatsapp rounded-pill px-4 fw-bold shadow-lg whatsapp-glow">
                                <i class="bi bi-whatsapp me-2"></i> {{ setting('whatsapp_button_text', 'Join WhatsApp Community') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Global Toasts for Success/Errors -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            @if(session('success'))
                <div class="toast show align-items-center text-white bg-success border-0" role="alert" data-bs-delay="3000">
                    <div class="d-flex">
                        <div class="toast-body fw-bold">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif
            @if($errors->any())
                <div class="toast show align-items-center text-white bg-danger border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body fw-bold">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error: {{ $errors->first() }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 bg-dark border-top border-secondary border-opacity-25 mt-5">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
                    @if(setting('footer_logo'))
                        <img src="{{ Storage::url(setting('footer_logo')) }}" alt="{{ setting('site_name', 'Logo') }}" style="max-height: 30px;" class="mb-2 d-block mx-auto mx-md-0">
                    @endif
                    <span class="text-muted small d-block">&copy; {{ date('Y') }} {{ setting('copyright_text', 'FutureGrowth.tech. All rights reserved.') }}</span>
                    @if(setting('footer_text'))
                        <p class="text-muted small mb-0 mt-1" style="max-width: 450px;">{{ setting('footer_text') }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="{{ route('about') }}" class="text-muted small text-decoration-none me-3 hover-white">About Us</a>
                    <a href="{{ route('terms') }}" class="text-muted small text-decoration-none me-3 hover-white">Terms</a>
                    <a href="{{ route('privacy') }}" class="text-muted small text-decoration-none me-3 hover-white">Privacy</a>
                    <a href="{{ route('risk') }}" class="text-muted small text-decoration-none hover-white">Risk Disclosure</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Support FAB & Modal -->
    <div class="support-fab" data-bs-toggle="modal" data-bs-target="#helpCenterModal">
        <i class="bi bi-headset"></i>
    </div>

    <!-- Help Center Modal -->
    <div class="modal fade" id="helpCenterModal" tabindex="-1" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content glass-card border-0">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-robot text-primary me-2"></i> Help Center</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="nav nav-pills mb-4 nav-fill" id="helpTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active bg-transparent border border-secondary text-white fw-bold custom-hover" id="faq-tab" data-bs-toggle="pill" data-bs-target="#faq" type="button" role="tab"><i class="bi bi-question-circle text-info me-2"></i> FAQ</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button class="nav-link bg-transparent border border-secondary text-white fw-bold custom-hover" id="ticket-tab" data-bs-toggle="pill" data-bs-target="#ticket" type="button" role="tab"><i class="bi bi-envelope text-warning me-2"></i> Open Ticket</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link bg-transparent border border-secondary text-white fw-bold custom-hover" id="guide-tab" data-bs-toggle="pill" data-bs-target="#guide" type="button" role="tab"><i class="bi bi-book text-success me-2"></i> Guide</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="helpTabContent">
                        <!-- FAQ Tab -->
                        <div class="tab-pane fade show active" id="faq" role="tabpanel">
                            <div class="accordion accordion-flush bg-transparent" id="faqAccordion">
                                <div class="accordion-item bg-transparent border-secondary border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            How does the 3X Return work?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted small">Your active investments earn daily ROI up to a maximum of 300% (3X) of your initial deposit. Once it reaches 3X, the plan is marked as completed.</div>
                                    </div>
                                </div>
                                <div class="accordion-item bg-transparent border-secondary border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            How do team referrals work?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted small">You earn a percentage of deposits made by users you invite, spanning up to 10 levels deep based on the current platform rewards.</div>
                                    </div>
                                </div>
                                <div class="accordion-item bg-transparent border-secondary">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-transparent text-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                            When can I withdraw?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted small">Withdrawals can be requested anytime your ROI or Commission balance exceeds the minimum withdrawal amount of ${{ setting('min_withdrawal', 10) }}.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Tab -->
                        <div class="tab-pane fade" id="ticket" role="tabpanel">
                            @auth
                            <form action="{{ route('dashboard.tickets.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Subject</label>
                                    <select name="subject" class="form-select bg-dark border-secondary text-white" required>
                                        <option value="Deposit Issue">Deposit Issue</option>
                                        <option value="Withdrawal Issue">Withdrawal Issue</option>
                                        <option value="Investment / ROI Issue">Investment / ROI Issue</option>
                                        <option value="Referral / Team Issue">Referral / Team Issue</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Message</label>
                                    <textarea name="message" rows="3" class="form-control bg-dark border-secondary text-white" required placeholder="Describe your issue..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Screenshot Attachment (Optional)</label>
                                    <input type="file" name="screenshot" class="form-control bg-dark border-secondary text-white" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-premium w-100 fw-bold">Submit Ticket</button>
                            </form>
                            
                            <hr class="border-secondary my-4">
                            <h6 class="fw-bold text-white mb-3"><i class="bi bi-clock-history text-info me-2"></i> My Previous Tickets</h6>
                            
                            @php
                                $myTickets = \App\Models\SupportTicket::where('user_id', auth()->id())->latest()->take(5)->get();
                            @endphp
                            
                            <div class="list-group list-group-flush rounded bg-transparent">
                                @forelse($myTickets as $t)
                                    <div class="list-group-item bg-dark border-secondary text-white mb-2 rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-info small">{{ $t->subject }}</span>
                                            @if($t->status === 'open')
                                                <span class="badge bg-warning text-dark">Open</span>
                                            @elseif($t->status === 'answered')
                                                <span class="badge bg-success">Answered</span>
                                            @else
                                                <span class="badge bg-secondary">Closed</span>
                                            @endif
                                        </div>
                                        <p class="small text-muted mb-2"><strong>You:</strong> {{ $t->message }}</p>
                                        @if($t->screenshot_path)
                                            <div class="mb-2">
                                                <a href="{{ Storage::url($t->screenshot_path) }}" target="_blank" class="small text-info text-decoration-none">
                                                    <i class="bi bi-image me-1"></i> View Screenshot
                                                </a>
                                            </div>
                                        @endif
                                        @if($t->reply)
                                            <div class="p-2 bg-success bg-opacity-10 border border-success border-opacity-25 rounded small text-white">
                                                <strong class="text-success">Support:</strong> {{ $t->reply }}
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center text-muted small py-3">No support tickets found.</div>
                                @endforelse
                            </div>
                            @else
                            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning">
                                Please <a href="{{ route('login') }}" class="alert-link text-warning fw-bold">login</a> to submit a support ticket.
                            </div>
                            @endauth
                        </div>

                        <!-- Guide Tab -->
                        <div class="tab-pane fade" id="guide" role="tabpanel">
                            <div class="p-3 bg-dark border border-secondary rounded">
                                <h6 class="fw-bold text-white mb-2"><i class="bi bi-1-circle text-primary me-2"></i> Step 1: Deposit</h6>
                                <p class="text-muted small mb-3">Navigate to the Deposits page and transfer funds to the provided crypto address. Submit your TXID for admin approval.</p>
                                
                                <h6 class="fw-bold text-white mb-2"><i class="bi bi-2-circle text-success me-2"></i> Step 2: Invest</h6>
                                <p class="text-muted small mb-3">Go to the Investments page and choose a plan. Your deposit balance will be used to activate the plan.</p>
                                
                                <h6 class="fw-bold text-white mb-2"><i class="bi bi-3-circle text-warning me-2"></i> Step 3: Earn & Withdraw</h6>
                                <p class="text-muted small mb-0">ROI is distributed daily automatically. Request a withdrawal anytime from your available balance.</p>
                            </div>
                        </div>
                    </div>

                    @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
                    <div class="mt-4 pt-4 border-top border-secondary text-center">
                        <p class="small text-muted mb-2">Need immediate live help?</p>
                        <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-whatsapp rounded-pill px-4 fw-bold whatsapp-glow w-100">
                            <i class="bi bi-whatsapp"></i> Chat with Support on WhatsApp
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        .custom-hover:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.2); transform: translateX(5px); }
        .custom-hover { transition: all 0.2s; }
    </style>

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- CountUp JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.0/countUp.min.js"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize AOS Animations
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });

        // Smart Copy function with Native Toast
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                // Show tiny success animation on button
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2-all"></i> Copied!';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-premium', 'btn-outline-primary');
                
                // Native Toast Generation for UX
                const toastHtml = `
                    <div class="toast show align-items-center text-white bg-success border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body fw-bold"><i class="bi bi-link-45deg me-2"></i> Referral Link Copied Successfully!</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>`;
                document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHtml);
                
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-premium');
                    // Remove toast after 3s
                    const toasts = document.querySelectorAll('.toast');
                    if(toasts.length > 0) toasts[toasts.length - 1].remove();
                }, 3000);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
