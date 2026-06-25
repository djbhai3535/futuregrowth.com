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
                        <input type="number" name="signup_bonus_amount" value="{{ setting('signup_bonus_amount', 10) }}" class="form-control bg-transparent border-secondary text-white">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Max Bonus Users Limit</label>
                        <input type="number" name="max_bonus_users" value="{{ setting('max_bonus_users', 1000) }}" class="form-control bg-transparent border-secondary text-white">
                    </div>
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
                    <label class="form-label text-muted small fw-bold">Contact Us Info</label>
                    <textarea name="contact_content" class="form-control bg-dark border-secondary text-white" rows="3">{{ setting('contact_content', '') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3 text-primary border-bottom border-secondary pb-2">Promotional Banners</h5>
                <div class="mb-3">
                    <label class="form-label text-muted small">Promo Banner 1 (e.g. Signup Bonus)</label>
                    <input type="text" name="promo_banner_1" class="form-control bg-dark border-secondary text-white" value="{{ $settings['promo_banner_1']->value ?? 'Free $10 Signup Bonus Available!' }}">
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
