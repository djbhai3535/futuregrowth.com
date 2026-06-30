<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Bootstrap the console kernel so Laravel's environment is loaded
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

if ($argc < 2) {
    echo "Usage: php scratch/test_smtp.php <target-email-address>\n";
    exit(1);
}

$to = $argv[1];

echo "====================================================\n";
echo "FutureGrowth.tech SMTP Connection Diagnostic Tool\n";
echo "====================================================\n\n";

echo "Active Mailer Configuration:\n";
echo "  Default Mailer: " . config('mail.default') . "\n";
echo "  SMTP Host:      " . config('mail.mailers.smtp.host') . "\n";
echo "  SMTP Port:      " . config('mail.mailers.smtp.port') . "\n";
echo "  SMTP Username:  " . config('mail.mailers.smtp.username') . "\n";
echo "  SMTP Encryption:" . config('mail.mailers.smtp.encryption') . "\n";
echo "  From Address:   " . config('mail.from.address') . "\n";
echo "  From Name:      " . config('mail.from.name') . "\n\n";

echo "Sending diagnostic email to: " . $to . "...\n";

try {
    Mail::raw('Congratulations! If you are reading this email, your FutureGrowth.tech SMTP mail configuration is connecting and delivering successfully!', function ($message) use ($to) {
        $message->to($to)
                ->subject('FutureGrowth.tech SMTP Verification Successful!');
    });
    echo "✅ SUCCESS: The test email was sent successfully! Please check your Gmail Inbox/Spam folder.\n";
} catch (\Exception $e) {
    echo "❌ ERROR: SMTP delivery failed!\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
