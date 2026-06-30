<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dynamically load mail configuration from settings
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $smtpHost = setting('smtp_host');
                $smtpUser = setting('smtp_username');
                $smtpPass = setting('smtp_password');

                if ($smtpHost && $smtpUser && $smtpPass && $smtpUser !== 'your-email@gmail.com') {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $smtpHost,
                        'mail.mailers.smtp.port' => (int) setting('smtp_port', 587),
                        'mail.mailers.smtp.username' => $smtpUser,
                        'mail.mailers.smtp.password' => $smtpPass,
                        'mail.mailers.smtp.encryption' => setting('smtp_encryption', 'tls'),
                        'mail.from.address' => setting('smtp_from_address', 'hello@futuregrowth.tech'),
                        'mail.from.name' => setting('site_name', 'FutureGrowth.tech'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Avoid failing during setup/migrations
        }
    }
}
