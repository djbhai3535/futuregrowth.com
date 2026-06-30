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
        self::loadDynamicMailConfig();
    }

    /**
     * Dynamically override mail configuration with database values
     */
    public static function loadDynamicMailConfig(): void
    {
        \Illuminate\Support\Facades\Log::info('loadDynamicMailConfig: Started execution.');
        
        // Force default mailer to smtp unconditionally
        config(['mail.default' => 'smtp']);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                \Illuminate\Support\Facades\Log::info('loadDynamicMailConfig: settings table exists.');
                
                // Clear cached configurations to read live database settings
                \Illuminate\Support\Facades\Cache::forget('setting_smtp_host');
                \Illuminate\Support\Facades\Cache::forget('setting_smtp_username');
                \Illuminate\Support\Facades\Cache::forget('setting_smtp_password');
                \Illuminate\Support\Facades\Cache::forget('setting_smtp_port');
                \Illuminate\Support\Facades\Cache::forget('setting_smtp_encryption');
                \Illuminate\Support\Facades\Cache::forget('setting_smtp_from_address');
                \Illuminate\Support\Facades\Cache::forget('setting_site_name');

                $smtpHost = setting('smtp_host');
                $smtpUser = setting('smtp_username');
                $smtpPass = setting('smtp_password');

                \Illuminate\Support\Facades\Log::info("loadDynamicMailConfig: Loaded values - Host: '{$smtpHost}', User: '{$smtpUser}', Pass length: " . strlen((string)$smtpPass));

                if ($smtpHost && $smtpUser && $smtpPass) {
                    \Illuminate\Support\Facades\Log::info('loadDynamicMailConfig: SMTP conditions met. Overriding configurations.');
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

                    // Purge resolved mailer instances to apply configurations on the fly
                    \Illuminate\Support\Facades\Mail::purge();
                    \Illuminate\Support\Facades\Log::info('loadDynamicMailConfig: Mailers purged successfully.');
                } else {
                    \Illuminate\Support\Facades\Log::warning('loadDynamicMailConfig: SMTP credentials check failed. Missing values.');
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('loadDynamicMailConfig: settings table does NOT exist.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('loadDynamicMailConfig: Exception encountered: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
