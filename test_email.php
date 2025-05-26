<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Current mail driver: " . config('mail.default') . "\n";
echo "Testing email functionality...\n";

try {
    Mail::raw('This is a test email from PHP script', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Email from Gemini Pro Trader');
    });
    
    echo "✅ Email sent successfully!\n";
    echo "Check storage/logs/laravel.log for email content (if using log driver)\n";
} catch (Exception $e) {
    echo "❌ Failed to send email: " . $e->getMessage() . "\n";
}
