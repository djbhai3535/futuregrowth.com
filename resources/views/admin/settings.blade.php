@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-gear-fill me-2"></i> System Settings</h2>
    <p class="text-muted">Change ROI, Referral commissions, limits, and more instantly.</p>
</div>

<div class="glass-card p-4">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- ROI Settings -->
            <div class="col-md-6">
                <h5 class="fw-bold mb-3 text-info border-bottom border-secondary pb-2">Platform Defaults</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Minimum Withdrawal ($)</label>
                        <input type="number" step="0.01" name="min_withdrawal" class="form-control bg-dark border-secondary text-white" value="{{ setting('min_withdrawal', 10) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Maximum Withdrawal ($)</label>
                        <input type="number" step="0.01" name="max_withdrawal" class="form-control bg-dark border-secondary text-white" value="{{ setting('max_withdrawal', 10000) }}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Minimum Deposit ($)</label>
                        <input type="number" step="0.01" name="min_deposit" class="form-control bg-dark border-secondary text-white" value="{{ setting('min_deposit', 25) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Maximum Deposit ($)</label>
                        <input type="number" step="0.01" name="max_deposit" class="form-control bg-dark border-secondary text-white" value="{{ setting('max_deposit', 50000) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Withdrawal Fee Percentage (%)</label>
                    <input type="number" step="0.1" name="withdrawal_fee_percent" class="form-control bg-dark border-secondary text-white" value="{{ setting('withdrawal_fee_percent', 5) }}">
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Sign-up Bonus Amount ($)</label>
                        <input type="number" name="signup_bonus" value="{{ setting('signup_bonus', 7) }}" class="form-control bg-transparent border-secondary text-white">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Max Bonus Users Limit</label>
                        <input type="number" name="max_bonus_users" value="{{ setting('max_bonus_users', 1000) }}" class="form-control bg-transparent border-secondary text-white">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Global Daily ROI Percentage (%)</label>
                    <input type="number" step="0.01" name="daily_roi_percent" class="form-control bg-dark border-secondary text-white" value="{{ setting('daily_roi_percent', 1.0) }}">
                    <div class="form-text text-muted small">Overrides all plan rates (e.g. 1.0 = 1% per day). Leave 0 or blank to use plan default ranges.</div>
                </div>

                <h5 class="fw-bold mb-3 border-bottom border-secondary pb-2">Platform Features</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Investment Return Multiplier (e.g. 3 for 3X)</label>
                        <input type="number" name="investment_return_multiplier" value="{{ setting('investment_return_multiplier', 3) }}" class="form-control bg-transparent border-secondary text-white">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Enable Return Limit</label>
                        <select name="enable_return_multiplier" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('enable_return_multiplier', 1) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ setting('enable_return_multiplier', 1) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted small text-uppercase fw-bold">WhatsApp Community Link</label>
                        <input type="text" name="whatsapp_community_link" value="{{ setting('whatsapp_community_link', '') }}" class="form-control bg-transparent border-secondary text-white" placeholder="https://chat.whatsapp.com/...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Show WhatsApp Button</label>
                        <select name="whatsapp_button_enabled" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('whatsapp_button_enabled', 1) == 1 ? 'selected' : '' }}>Show</option>
                            <option value="0" {{ setting('whatsapp_button_enabled', 1) == 0 ? 'selected' : '' }}>Hide</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Referral Settings -->
            <div class="col-md-6">
                <h5 class="fw-bold mb-3 text-success border-bottom border-secondary pb-2">Referral Commissions (%)</h5>
                <div class="mb-3">
                    <label class="form-label text-muted small text-warning">Direct Reward (Instant)</label>
                    <input type="number" step="0.1" name="direct_reward_percent" class="form-control bg-dark border-warning text-white" value="{{ $settings['direct_reward_percent']->value ?? 20 }}">
                </div>
                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 1</label>
                        <input type="number" step="0.1" name="referral_level_1" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_1']->value ?? 5 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 2</label>
                        <input type="number" step="0.1" name="referral_level_2" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_2']->value ?? 4 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 3</label>
                        <input type="number" step="0.1" name="referral_level_3" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_3']->value ?? 3 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 4</label>
                        <input type="number" step="0.1" name="referral_level_4" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_4']->value ?? 3 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 5</label>
                        <input type="number" step="0.1" name="referral_level_5" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_5']->value ?? 2 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 6</label>
                        <input type="number" step="0.1" name="referral_level_6" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_6']->value ?? 2 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 7</label>
                        <input type="number" step="0.1" name="referral_level_7" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_7']->value ?? 1 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 8</label>
                        <input type="number" step="0.1" name="referral_level_8" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_8']->value ?? 1 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 9</label>
                        <input type="number" step="0.1" name="referral_level_9" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_9']->value ?? 1 }}">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label text-muted small">Level 10</label>
                        <input type="number" step="0.1" name="referral_level_10" class="form-control bg-dark border-secondary text-white" value="{{ $settings['referral_level_10']->value ?? 1 }}">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3 text-info border-bottom border-secondary pb-2">CMS & Page Content</h5>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Homepage Announcement Bar</label>
                    <input type="text" name="announcement_bar" class="form-control bg-dark border-secondary text-white" value="{{ setting('announcement_bar', '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Homepage Intro Text</label>
                    <textarea name="homepage_about_text" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('homepage_about_text', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Trust Section Text</label>
                    <textarea name="trust_section_text" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('trust_section_text', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">About Us Page Content</label>
                    <textarea name="about_us_text" class="form-control bg-dark border-secondary text-white" rows="4">{{ setting('about_us_text', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Deposit Instructions</label>
                    <textarea name="deposit_instructions" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('deposit_instructions', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Terms & Conditions</label>
                    <textarea name="terms_content" class="form-control bg-dark border-secondary text-white" rows="4">{{ setting('terms_content', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Privacy Policy</label>
                    <textarea name="privacy_content" class="form-control bg-dark border-secondary text-white" rows="4">{{ setting('privacy_content', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Risk Disclosure</label>
                    <textarea name="risk_content" class="form-control bg-dark border-secondary text-white" rows="4">{{ setting('risk_content', '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Contact Us Info Description</label>
                    <textarea name="contact_content" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('contact_content', 'For general enquiries, partnership proposals, or technical support, please contact us through any of the channels below.') }}</textarea>
                </div>
                
                <h5 class="fw-bold mb-3 mt-4 text-warning border-bottom border-secondary pb-2">About Us Detailed Content Blocks</h5>
                <div class="row g-3">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted small fw-bold">Company Introduction</label>
                        <textarea name="about_us_intro" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_intro', 'FutureGrowth.tech is a globally recognized decentralized investment ecosystem designed to leverage next-generation artificial intelligence algorithms. By automating asset allocation and digital arbitrage, we bridge the gap between traditional finance and blockchain economies, enabling sustainable, low-risk capital appreciation for retail and institutional clients alike.') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Our Mission</label>
                        <textarea name="about_us_mission" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_mission', 'To democratize access to high-yield cryptocurrency assets and provide a secure, automated passive income engine. We strive to maintain absolute computational transparency and long-term liquidity reserves, ensuring every participant benefits from the digital economy.') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Our Vision</label>
                        <textarea name="about_us_vision" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_vision', 'To establish FutureGrowth.tech as the global standard for smart wealth generation, setting the benchmark for decentralized finance protocols with multi-level networking, secure cold storage vaults, and an uncompromisable 3X return sustainability model.') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Why Choose Us</label>
                        <textarea name="about_us_why_choose_us" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_why_choose_us', 'We stand out through our fully automated daily ROI model, verified secure smart contracts, dynamic SMTPS email delivery systems, instant USDT deposit confirmations, and a multi-level referral network matrix. Our users enjoy reliable returns without manual interference or hidden operational fees.') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Investment Philosophy</label>
                        <textarea name="about_us_philosophy" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_philosophy', 'Our philosophy is rooted in risk mitigation and community-oriented growth. Rather than chasing volatile, speculative spikes, our platform focuses on consistent daily yields, secure liquidity backing, and referral-driven network expansion to secure multi-generational wealth.') }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small fw-bold">Core Technology Description</label>
                        <textarea name="about_us_technology" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_technology', 'We integrate advanced machine learning models, real-time blockchain analytics APIs, and automatic yield farming algorithms to achieve optimized return distribution on stablecoin assets.') }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small fw-bold">Security First Policy Description</label>
                        <textarea name="about_us_security_policy" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_security_policy', 'All client balances are backed 1:1, smart audits are performed continuously, and manual wallets remain strictly managed under offline activity logs.') }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted small fw-bold">Global Community Description</label>
                        <textarea name="about_us_community_desc" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_community_desc', 'Our users connect via real-time WhatsApp and Telegram channels, creating an active peer-to-peer network that supports and validates referral growth.') }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted small fw-bold">Future Goals & Closing Statement</label>
                        <textarea name="about_us_future_goals" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('about_us_future_goals', 'As we progress along our roadmap, we aim to integrate cross-chain asset swaps, expand downline support to 15 levels, and launch local language support centers globally. We are committed to building the future of automated investment together with you.') }}</textarea>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 mt-4 text-warning border-bottom border-secondary pb-2">Contact Details & Location</h5>
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Company Corporate Email</label>
                        <input type="email" name="company_email" class="form-control bg-dark border-secondary text-white" value="{{ setting('company_email', 'hello@futuregrowth.tech') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Support Desk Email</label>
                        <input type="email" name="support_email" class="form-control bg-dark border-secondary text-white" value="{{ setting('support_email', 'support@futuregrowth.tech') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Support WhatsApp Number</label>
                        <input type="text" name="support_whatsapp" class="form-control bg-dark border-secondary text-white" value="{{ setting('support_whatsapp', '+1234567890') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Telegram Link</label>
                        <input type="text" name="telegram_link" class="form-control bg-dark border-secondary text-white" value="{{ setting('telegram_link', 'https://t.me/futuregrowthtech') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Office Address</label>
                        <input type="text" name="office_address" class="form-control bg-dark border-secondary text-white" value="{{ setting('office_address', '123 Wall Street, New York, NY, USA') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Business Hours</label>
                        <input type="text" name="business_hours" class="form-control bg-dark border-secondary text-white" value="{{ setting('business_hours', 'Monday - Friday: 09:00 - 18:00 UTC') }}">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3 text-primary border-bottom border-secondary pb-2">Promotional Banners</h5>
                <div class="mb-3">
                    <label class="form-label text-muted small">Promo Banner 1 (e.g. Signup Bonus)</label>
                    <input type="text" name="promo_banner_1" class="form-control bg-dark border-secondary text-white" value="{{ $settings['promo_banner_1']->value ?? 'Free $' . setting('signup_bonus', 7) . ' Signup Bonus Available!' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Promo Banner 2 (e.g. Team Rewards)</label>
                    <input type="text" name="promo_banner_2" class="form-control bg-dark border-secondary text-white" value="{{ $settings['promo_banner_2']->value ?? 'Build Your Team & Earn up to 10 Levels of Rewards!' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Promo Banner 3 (e.g. ROI / Limits)</label>
                    <input type="text" name="promo_banner_3" class="form-control bg-dark border-secondary text-white" value="{{ $settings['promo_banner_3']->value ?? '3X Return on all Investment Plans!' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Promo Banner 4 (e.g. WhatsApp CTA)</label>
                    <input type="text" name="promo_banner_4" class="form-control bg-dark border-secondary text-white" value="{{ $settings['promo_banner_4']->value ?? 'Join our WhatsApp Community!' }}">
                </div>
            </div>
        </div>

        <!-- SMTP & Security Settings -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3 text-danger border-bottom border-secondary pb-2">SMTP Mail Server Configuration</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control bg-dark border-secondary text-white" value="{{ setting('smtp_host', 'smtp.gmail.com') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold">SMTP Port</label>
                        <input type="number" name="smtp_port" class="form-control bg-dark border-secondary text-white" value="{{ setting('smtp_port', 587) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">SMTP Encryption</label>
                        <select name="smtp_encryption" class="form-control bg-dark border-secondary text-white">
                            <option value="tls" {{ setting('smtp_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ setting('smtp_encryption', 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ setting('smtp_encryption', 'tls') == 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Sender Email Address</label>
                        <input type="email" name="smtp_from_address" class="form-control bg-dark border-secondary text-white" value="{{ setting('smtp_from_address', 'hello@futuregrowth.tech') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">SMTP Username</label>
                        <input type="text" name="smtp_username" class="form-control bg-dark border-secondary text-white" value="{{ setting('smtp_username', '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">SMTP Password</label>
                        <input type="password" name="smtp_password" class="form-control bg-dark border-secondary text-white" placeholder="Leave empty to keep existing password">
                    </div>
                <h5 class="fw-bold mb-3 text-info border-bottom border-secondary pb-2">Payment Gateway Settings (NOWPayments)</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Enable / Disable Gateway</label>
                        <select name="nowpayments_enabled" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('nowpayments_enabled', 1) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ setting('nowpayments_enabled', 1) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Sandbox / Live Mode</label>
                        <select name="nowpayments_sandbox_mode" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('nowpayments_sandbox_mode', 0) == 1 ? 'selected' : '' }}>Sandbox Mode (Testing)</option>
                            <option value="0" {{ setting('nowpayments_sandbox_mode', 0) == 0 ? 'selected' : '' }}>Live Mode (Production)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Default Coin</label>
                        <input type="text" name="nowpayments_default_coin" class="form-control bg-dark border-secondary text-white" value="{{ setting('nowpayments_default_coin', 'usdt') }}" placeholder="e.g. usdt">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Enable TRC20 Network</label>
                        <select name="nowpayments_enable_trc20" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('nowpayments_enable_trc20', 1) == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ setting('nowpayments_enable_trc20', 1) == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Enable BEP20 Network</label>
                        <select name="nowpayments_enable_bep20" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('nowpayments_enable_bep20', 1) == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ setting('nowpayments_enable_bep20', 1) == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Default Selected Network</label>
                        <select name="nowpayments_default_network" class="form-control bg-dark border-secondary text-white">
                            <option value="trc20" {{ setting('nowpayments_default_network', 'trc20') === 'trc20' ? 'selected' : '' }}>USDT (TRC20)</option>
                            <option value="bep20" {{ setting('nowpayments_default_network', 'trc20') === 'bep20' ? 'selected' : '' }}>USDT (BEP20)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">NOWPayments API Key</label>
                        <input type="password" name="nowpayments_api_key" class="form-control bg-dark border-secondary text-white" placeholder="Leave empty to keep existing key">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">IPN Secret</label>
                        <input type="password" name="nowpayments_ipn_secret" class="form-control bg-dark border-secondary text-white" placeholder="Leave empty to keep existing secret">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Minimum Deposit ($)</label>
                        <input type="number" step="0.01" name="nowpayments_min_deposit" class="form-control bg-dark border-secondary text-white" value="{{ setting('nowpayments_min_deposit', 25) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Maximum Deposit ($)</label>
                        <input type="number" step="0.01" name="nowpayments_max_deposit" class="form-control bg-dark border-secondary text-white" value="{{ setting('nowpayments_max_deposit', 50000) }}">
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-warning border-bottom border-secondary pb-2">Security, Maintenance & 2FA</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Custom Secret Admin URL Prefix</label>
                        <input type="text" name="admin_secret_path" class="form-control bg-dark border-secondary text-white" value="{{ setting('admin_secret_path', 'admin-fg-secure') }}" placeholder="e.g. secure-admin-fg">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Maintenance Mode</label>
                        <select name="maintenance_mode" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('maintenance_mode', 0) == 1 ? 'selected' : '' }}>ON (Offline for Visitors)</option>
                            <option value="0" {{ setting('maintenance_mode', 0) == 0 ? 'selected' : '' }}>OFF (Online)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Email Verification</label>
                        <select name="enable_email_verification" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('enable_email_verification', 1) == 1 ? 'selected' : '' }}>Enabled (OTP Required)</option>
                            <option value="0" {{ setting('enable_email_verification', 1) == 0 ? 'selected' : '' }}>Disabled (Instant Login)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Google reCAPTCHA v2</label>
                        <select name="enable_recaptcha" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('enable_recaptcha', 0) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ setting('enable_recaptcha', 0) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">reCAPTCHA Site Key</label>
                        <input type="text" name="recaptcha_site_key" class="form-control bg-dark border-secondary text-white" value="{{ setting('recaptcha_site_key', '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">reCAPTCHA Secret Key</label>
                        <input type="password" name="recaptcha_secret_key" class="form-control bg-dark border-secondary text-white" placeholder="Leave empty to keep existing key">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Admin Two-Factor Auth (2FA)</label>
                        <select name="enable_admin_2fa" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('enable_admin_2fa', 0) == 1 ? 'selected' : '' }}>Enabled (SMTP Required)</option>
                            <option value="0" {{ setting('enable_admin_2fa', 0) == 0 ? 'selected' : '' }}>Disabled (Bypass for Testing)</option>
                        </select>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-success border-bottom border-secondary pb-2">WhatsApp Community System</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Enable / Disable WhatsApp Banner</label>
                        <select name="enable_whatsapp_banner" class="form-control bg-dark border-secondary text-white">
                            <option value="1" {{ setting('enable_whatsapp_banner', 1) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ setting('enable_whatsapp_banner', 1) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">WhatsApp Community Link</label>
                        <input type="text" name="whatsapp_community_link" class="form-control bg-dark border-secondary text-white" value="{{ setting('whatsapp_community_link', '') }}" placeholder="https://chat.whatsapp.com/...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">WhatsApp Support Number</label>
                        <input type="text" name="support_whatsapp" class="form-control bg-dark border-secondary text-white" value="{{ setting('support_whatsapp', '') }}" placeholder="+1234567890">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Button Text</label>
                        <input type="text" name="whatsapp_button_text" class="form-control bg-dark border-secondary text-white" value="{{ setting('whatsapp_button_text', 'Join WhatsApp Community') }}" placeholder="Join WhatsApp Community">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label text-muted small fw-bold">Banner Text</label>
                        <input type="text" name="whatsapp_banner_text" class="form-control bg-dark border-secondary text-white" value="{{ setting('whatsapp_banner_text', 'Stay updated with announcements, deposit confirmations, promotions, support, investment news.') }}" placeholder="Stay updated with announcements, deposit confirmations...">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3 text-success border-bottom border-secondary pb-2">Website Settings</h5>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Company / Site Name</label>
                        <input type="text" name="site_name" class="form-control bg-dark border-secondary text-white" value="{{ setting('site_name', config('app.name', 'Premium Crypto Invest')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Copyright Text</label>
                        <input type="text" name="copyright_text" class="form-control bg-dark border-secondary text-white" value="{{ setting('copyright_text', 'CryptoInvest Platform. All rights reserved.') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted small fw-bold">Footer Text</label>
                        <textarea name="footer_text" class="form-control bg-dark border-secondary text-white" rows="2">{{ setting('footer_text', '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">SEO Meta Title</label>
                        <input type="text" name="seo_meta_title" class="form-control bg-dark border-secondary text-white" value="{{ setting('seo_meta_title', '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">SEO Meta Description</label>
                        <input type="text" name="seo_meta_description" class="form-control bg-dark border-secondary text-white" value="{{ setting('seo_meta_description', '') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Website Logo</label>
                        <input type="file" name="site_logo" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        @if(setting('site_logo'))
                            <div class="mt-2 bg-black p-2 rounded d-inline-block"><img src="{{ Storage::url(setting('site_logo')) }}" style="max-height: 40px;"></div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Favicon</label>
                        <input type="file" name="site_favicon" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        @if(setting('site_favicon'))
                            <div class="mt-2 bg-black p-2 rounded d-inline-block"><img src="{{ Storage::url(setting('site_favicon')) }}" style="max-height: 40px;"></div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Footer Logo</label>
                        <input type="file" name="footer_logo" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        @if(setting('footer_logo'))
                            <div class="mt-2 bg-black p-2 rounded d-inline-block"><img src="{{ Storage::url(setting('footer_logo')) }}" style="max-height: 40px;"></div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Open Graph Image (SEO)</label>
                        <input type="file" name="site_og_image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                        @if(setting('site_og_image'))
                            <div class="mt-2 bg-black p-2 rounded d-inline-block"><img src="{{ Storage::url(setting('site_og_image')) }}" style="max-height: 40px;"></div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small">Support Email</label>
                        <input type="email" name="support_email" class="form-control bg-dark border-secondary text-white" value="{{ setting('support_email', 'support@example.com') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Support WhatsApp</label>
                        <input type="text" name="support_whatsapp" class="form-control bg-dark border-secondary text-white" value="{{ setting('support_whatsapp', '+1234567890') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">USDT Deposit Address (TRC20)</label>
                        <input type="text" name="admin_usdt_address" class="form-control bg-dark border-secondary text-white" value="{{ setting('admin_usdt_address', 'TXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Telegram Link</label>
                        <input type="url" name="telegram_link" class="form-control bg-dark border-secondary text-white" value="{{ setting('telegram_link', '#') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Twitter/X Link</label>
                        <input type="url" name="twitter_link" class="form-control bg-dark border-secondary text-white" value="{{ setting('twitter_link', '#') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Facebook Link</label>
                        <input type="url" name="facebook_link" class="form-control bg-dark border-secondary text-white" value="{{ setting('facebook_link', '#') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Instagram Link</label>
                        <input type="url" name="instagram_link" class="form-control bg-dark border-secondary text-white" value="{{ setting('instagram_link', '#') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-secondary text-end">
            <button type="submit" class="btn btn-warning fw-bold px-5">Save Settings</button>
        </div>
    </form>
</div>
@endsection
