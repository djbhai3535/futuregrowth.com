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
                config([
                    'mail.mailers.smtp.host' => setting('smtp_host', config('mail.mailers.smtp.host')),
                    'mail.mailers.smtp.port' => (int) setting('smtp_port', config('mail.mailers.smtp.port')),
                    'mail.mailers.smtp.username' => setting('smtp_username', config('mail.mailers.smtp.username')),
                    'mail.mailers.smtp.password' => setting('smtp_password', config('mail.mailers.smtp.password')),
                    'mail.mailers.smtp.encryption' => setting('smtp_encryption', config('mail.mailers.smtp.encryption')),
                    'mail.from.address' => setting('smtp_from_address', config('mail.from.address')),
                    'mail.from.name' => setting('site_name', config('mail.from.name')),
                ]);
            }
        } catch (\Exception $e) {
            // Avoid failing during setup/migrations
        }
    }
}
