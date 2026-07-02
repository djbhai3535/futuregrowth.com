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

    public static function loadDynamicMailConfig(): void
    {
        // Force default mailer to smtp unconditionally
        config(['mail.default' => 'smtp']);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                
                $smtpHost = setting('smtp_host');
                $smtpUser = setting('smtp_username');
                $smtpPass = setting('smtp_password');
                $smtpPort = (int) setting('smtp_port', 587);
                $smtpEnc = setting('smtp_encryption', 'tls');

                if ($smtpHost && $smtpUser && $smtpPass) {
                    
                    $scheme = null;
                    if ($smtpPort === 465 || strtolower($smtpEnc) === 'ssl') {
                        $scheme = 'smtps';
                    }

                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.scheme' => $scheme,
                        'mail.mailers.smtp.host' => $smtpHost,
                        'mail.mailers.smtp.port' => $smtpPort,
                        'mail.mailers.smtp.username' => $smtpUser,
                        'mail.mailers.smtp.password' => $smtpPass,
                        'mail.mailers.smtp.encryption' => $smtpEnc,
                        'mail.from.address' => setting('smtp_from_address', 'hello@futuregrowth.tech'),
                        'mail.from.name' => setting('site_name', 'FutureGrowth.tech'),
                    ]);

                    // Purge resolved mailer instances to apply configurations on the fly
                    \Illuminate\Support\Facades\Mail::purge();
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('loadDynamicMailConfig: Exception encountered: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
