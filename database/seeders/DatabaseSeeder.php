<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Plan;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'System Admin',
                'username' => 'admin',
                'phone' => '+1234567890',
                'password' => Hash::make('password123'),
                'referral_code' => Str::random(10),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
        Wallet::firstOrCreate(['user_id' => $admin->id]);

        // 2. Default Settings
        $settings = [
            'min_withdrawal' => ['value' => '10', 'type' => 'integer'],
            'max_withdrawal' => ['value' => '10000', 'type' => 'integer'],
            'min_deposit' => ['value' => '25', 'type' => 'integer'],
            'max_deposit' => ['value' => '50000', 'type' => 'integer'],
            'withdrawal_fee_percent' => ['value' => '5', 'type' => 'integer'],
            'signup_bonus' => ['value' => '7', 'type' => 'integer'],
            'max_bonus_users' => ['value' => '1000', 'type' => 'integer'],
            'direct_reward_percent' => ['value' => '20', 'type' => 'integer'],
            'referral_level_1' => ['value' => '5', 'type' => 'integer'],
            'referral_level_2' => ['value' => '4', 'type' => 'integer'],
            'referral_level_3' => ['value' => '3', 'type' => 'integer'],
            'referral_level_4' => ['value' => '3', 'type' => 'integer'],
            'referral_level_5' => ['value' => '2', 'type' => 'integer'],
            'referral_level_6' => ['value' => '2', 'type' => 'integer'],
            'referral_level_7' => ['value' => '1', 'type' => 'integer'],
            'referral_level_8' => ['value' => '1', 'type' => 'integer'],
            'referral_level_9' => ['value' => '1', 'type' => 'integer'],
            'referral_level_10' => ['value' => '1', 'type' => 'integer'],
            'admin_usdt_address' => ['value' => 'TXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'type' => 'string'],
            'enable_return_multiplier' => ['value' => '1', 'type' => 'boolean'],
            'investment_return_multiplier' => ['value' => '3', 'type' => 'integer'],
            
            // Community Button settings
            'whatsapp_community_link' => ['value' => 'https://chat.whatsapp.com/invite', 'type' => 'string'],
            'whatsapp_button_enabled' => ['value' => '1', 'type' => 'boolean'],
            'telegram_link' => ['value' => 'https://t.me/futuregrowthtech', 'type' => 'string'],
            'community_button_text' => ['value' => 'Join Telegram Community', 'type' => 'string'],
            'community_button_enabled' => ['value' => '1', 'type' => 'boolean'],
            
            'site_name' => ['value' => 'FutureGrowth.tech', 'type' => 'string'],
            'copyright_text' => ['value' => 'FutureGrowth.tech. All rights reserved.', 'type' => 'string'],
            'footer_text' => ['value' => 'FutureGrowth.tech is a premier AI-powered investment ecosystem providing sustainable automated daily returns.', 'type' => 'string'],
            'about_us_text' => ['value' => 'Our platform is built on absolute transparency and advanced cryptographic security, providing a sustainable AI-driven investment ecosystem for global clients.', 'type' => 'string'],
            'deposit_instructions' => ['value' => 'Only send USDT (TRC20) to this address. Send screenshot/TXID for manual approval.', 'type' => 'string'],
            'announcement_bar' => ['value' => 'FutureGrowth.tech Official Launch: Start earning daily passive profits now!', 'type' => 'string'],
            'support_email' => ['value' => 'support@futuregrowth.tech', 'type' => 'string'],
            'support_whatsapp' => ['value' => '+1234567890', 'type' => 'string'],
            'twitter_link' => ['value' => 'https://x.com/futuregrowthtech', 'type' => 'string'],
            'facebook_link' => ['value' => 'https://facebook.com/futuregrowthtech', 'type' => 'string'],
            'instagram_link' => ['value' => 'https://instagram.com/futuregrowthtech', 'type' => 'string'],

            // SMTP Settings
            'smtp_host' => ['value' => 'smtp.gmail.com', 'type' => 'string'],
            'smtp_port' => ['value' => '587', 'type' => 'integer'],
            'smtp_username' => ['value' => 'your-email@gmail.com', 'type' => 'string'],
            'smtp_password' => ['value' => 'your-app-password', 'type' => 'string'],
            'smtp_encryption' => ['value' => 'tls', 'type' => 'string'],
            'smtp_from_address' => ['value' => 'hello@futuregrowth.tech', 'type' => 'string'],

            // Admin URL Secret
            'admin_secret_path' => ['value' => 'admin-fg-secure', 'type' => 'string'],

            // Maintenance Mode
            'maintenance_mode' => ['value' => '0', 'type' => 'boolean'],

            // Verification & Security
            'enable_email_verification' => ['value' => '1', 'type' => 'boolean'],
            'enable_recaptcha' => ['value' => '0', 'type' => 'boolean'],
            'recaptcha_site_key' => ['value' => 'site_key_here', 'type' => 'string'],
            'recaptcha_secret_key' => ['value' => 'secret_key_here', 'type' => 'string'],

            // Global Daily ROI Setting
            'daily_roi_percent' => ['value' => '1.0', 'type' => 'decimal'],

            // Enable Admin 2FA
            'enable_admin_2fa' => ['value' => '0', 'type' => 'boolean'],

            // CMS & Page content additions
            'homepage_about_text' => ['value' => 'Earn secure daily profits, build a massive 10-level referral team, and achieve up to a 300% (3X) return on your investments automatically.', 'type' => 'string'],
            'trust_section_text' => ['value' => 'FutureGrowth.tech is engineered to deliver institutional-grade security, lightning-fast execution, and complete platform transparency.', 'type' => 'string'],
            'terms_content' => ['value' => '1. All investments are subject to a strict 300% (3X) return multiplier protocol. Once reached, plans are marked complete.\n2. Withdrawals are processed promptly in accordance with our 3-business-days protocol.\n3. Account duplication or fraudulent self-referrals will lead to instant account suspension.', 'type' => 'string'],
            'privacy_content' => ['value' => '1. We collect email, phone, and IP addresses solely for platform operation, security audits, and support ticket validation.\n2. All user data is secured using AES-256 standard encryption.\n3. We never distribute user details to third-party services.', 'type' => 'string'],
            'risk_content' => ['value' => 'Cryptocurrency asset trading and automated yield investing carry high risks of price volatility. Clients should perform due diligence and invest responsibly.', 'type' => 'string'],
            'contact_content' => ['value' => 'For general enquiries, partnership proposals, or technical support, please contact us through any of the channels below.', 'type' => 'string'],
            'company_email' => ['value' => 'hello@futuregrowth.tech', 'type' => 'string'],
            'office_address' => ['value' => '123 Wall Street, New York, NY, USA', 'type' => 'string'],
            'business_hours' => ['value' => 'Monday - Friday: 09:00 - 18:00 UTC', 'type' => 'string'],
            'seo_meta_title' => ['value' => 'FutureGrowth.tech | Premium AI Crypto Investments', 'type' => 'string'],
            'seo_meta_description' => ['value' => 'FutureGrowth.tech is a premier AI-powered investment ecosystem providing sustainable automated daily returns.', 'type' => 'string'],

            // About Us Detail Blocks
            'about_us_intro' => ['value' => 'FutureGrowth.tech is a globally recognized decentralized investment ecosystem designed to leverage next-generation artificial intelligence algorithms. By automating asset allocation and digital arbitrage, we bridge the gap between traditional finance and blockchain economies, enabling sustainable, low-risk capital appreciation for retail and institutional clients alike.', 'type' => 'string'],
            'about_us_mission' => ['value' => 'To democratize access to high-yield cryptocurrency assets and provide a secure, automated passive income engine. We strive to maintain absolute computational transparency and long-term liquidity reserves, ensuring every participant benefits from the digital economy.', 'type' => 'string'],
            'about_us_vision' => ['value' => 'To establish FutureGrowth.tech as the global standard for smart wealth generation, setting the benchmark for decentralized finance protocols with multi-level networking, secure cold storage vaults, and an uncompromisable 3X return sustainability model.', 'type' => 'string'],
            'about_us_why_choose_us' => ['value' => 'We stand out through our fully automated daily ROI model, verified secure smart contracts, dynamic SMTPS email delivery systems, instant USDT deposit confirmations, and a multi-level referral network matrix. Our users enjoy reliable returns without manual interference or hidden operational fees.', 'type' => 'string'],
            'about_us_philosophy' => ['value' => 'Our philosophy is rooted in risk mitigation and community-oriented growth. Rather than chasing volatile, speculative spikes, our platform focuses on consistent daily yields, secure liquidity backing, and referral-driven network expansion to secure multi-generational wealth.', 'type' => 'string'],
            'about_us_technology' => ['value' => 'We integrate advanced machine learning models, real-time blockchain analytics APIs, and automatic yield farming algorithms to achieve optimized return distribution on stablecoin assets.', 'type' => 'string'],
            'about_us_security_policy' => ['value' => 'All client balances are backed 1:1, smart audits are performed continuously, and manual wallets remain strictly managed under offline activity logs.', 'type' => 'string'],
            'about_us_community_desc' => ['value' => 'Our users connect via real-time WhatsApp and Telegram channels, creating an active peer-to-peer network that supports and validates referral growth.', 'type' => 'string'],
            'about_us_future_goals' => ['value' => 'As we progress along our roadmap, we aim to integrate cross-chain asset swaps, expand downline support to 15 levels, and launch local language support centers globally. We are committed to building the future of automated investment together with you.', 'type' => 'string'],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(['key' => $key], $data);
        }

        // 3. Default Plans
        $plans = [
            [
                'name' => 'Starter',
                'min_amount' => 25,
                'max_amount' => 100,
                'min_roi' => 1.5,
                'max_roi' => 3.0,
                'status' => 'active'
            ],
            [
                'name' => 'Growth',
                'min_amount' => 101,
                'max_amount' => 500,
                'min_roi' => 2.0,
                'max_roi' => 4.0,
                'status' => 'active'
            ],
            [
                'name' => 'Professional',
                'min_amount' => 501,
                'max_amount' => 1000,
                'min_roi' => 2.5,
                'max_roi' => 4.5,
                'status' => 'active'
            ],
            [
                'name' => 'Elite',
                'min_amount' => 1001,
                'max_amount' => 5000,
                'min_roi' => 3.5,
                'max_roi' => 6.0,
                'status' => 'active'
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
