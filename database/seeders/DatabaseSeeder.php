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
            'signup_bonus_amount' => ['value' => '7', 'type' => 'integer'],
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
