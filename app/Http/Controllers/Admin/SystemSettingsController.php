<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'admin_email' => config('mail.from.address'),
            'timezone' => config('app.timezone'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function general()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'admin_email' => config('mail.from.address'),
            'timezone' => config('app.timezone'),
            'default_currency' => config('app.currency', 'USD'),
            'items_per_page' => config('app.items_per_page', 20),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url',
            'admin_email' => 'required|email',
            'timezone' => 'required|string',
            'default_currency' => 'required|string|size:3',
            'items_per_page' => 'required|integer|min:10|max:100',
        ]);

        // Update .env file or database settings
        $this->updateEnvFile([
            'APP_NAME' => $request->site_name,
            'APP_URL' => $request->site_url,
            'MAIL_FROM_ADDRESS' => $request->admin_email,
            'APP_TIMEZONE' => $request->timezone,
            'APP_CURRENCY' => $request->default_currency,
            'APP_ITEMS_PER_PAGE' => $request->items_per_page,
        ]);

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    public function payment()
    {
        $settings = [
            'nowpayments_api_key' => config('services.nowpayments.api_key'),
            'nowpayments_ipn_key' => config('services.nowpayments.ipn_key'),
            'nowpayments_sandbox' => config('services.nowpayments.sandbox'),
            'payment_timeout' => config('services.nowpayments.timeout', 3600),
        ];

        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'nowpayments_api_key' => 'required|string',
            'nowpayments_ipn_key' => 'required|string',
            'nowpayments_sandbox' => 'boolean',
            'payment_timeout' => 'required|integer|min:300|max:86400',
        ]);

        $this->updateEnvFile([
            'NOWPAYMENTS_API_KEY' => $request->nowpayments_api_key,
            'NOWPAYMENTS_IPN_KEY' => $request->nowpayments_ipn_key,
            'NOWPAYMENTS_SANDBOX' => $request->boolean('nowpayments_sandbox') ? 'true' : 'false',
            'NOWPAYMENTS_TIMEOUT' => $request->payment_timeout,
        ]);

        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }

    public function email()
    {
        $settings = [
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'required_if:mail_driver,smtp|string',
            'mail_port' => 'required_if:mail_driver,smtp|integer',
            'mail_username' => 'required_if:mail_driver,smtp|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        $envUpdates = [
            'MAIL_MAILER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ];

        if ($request->filled('mail_password')) {
            $envUpdates['MAIL_PASSWORD'] = $request->mail_password;
        }

        $this->updateEnvFile($envUpdates);

        return redirect()->back()->with('success', 'Email settings updated successfully.');
    }    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mail_driver' => 'required|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        try {
            // Check if we're using log driver
            $currentDriver = config('mail.default');
            
            if ($currentDriver === 'log') {
                // For log driver, just send with current configuration
                Mail::raw(
                    "Test email berhasil dikirim!\n\n" .
                    "Konfigurasi yang digunakan:\n" .
                    "Driver: " . $request->mail_driver . "\n" .
                    "Host: " . $request->mail_host . "\n" .
                    "Port: " . $request->mail_port . "\n" .
                    "Encryption: " . $request->mail_encryption . "\n" .
                    "From: " . $request->mail_from_address . "\n\n" .
                    "Email ini ditulis ke log file karena menggunakan log driver.",
                    function ($message) use ($request) {
                        $message->to($request->email)
                                ->subject('Test Email dari ' . $request->mail_from_name);
                    }
                );
                
                return response()->json([
                    'success' => true,
                    'message' => 'Test email berhasil dikirim ke log! Periksa storage/logs/laravel.log untuk melihat isi email.'
                ]);
            }
            
            // For SMTP, test with different configurations
            $configurations = [
                ['port' => $request->mail_port, 'encryption' => $request->mail_encryption],
                ['port' => 587, 'encryption' => 'tls'],
                ['port' => 465, 'encryption' => 'ssl'],
                ['port' => 25, 'encryption' => null],
            ];
            
            foreach ($configurations as $config) {
                try {
                    // Temporarily configure mail settings for testing
                    $originalConfig = [
                        'mail.default' => config('mail.default'),
                        'mail.mailers.smtp' => config('mail.mailers.smtp'),
                        'mail.from' => config('mail.from'),
                    ];

                    // Set temporary mail configuration
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => $request->mail_host,
                        'mail.mailers.smtp.port' => (int)$config['port'],
                        'mail.mailers.smtp.encryption' => $config['encryption'],
                        'mail.mailers.smtp.username' => $request->mail_username,
                        'mail.mailers.smtp.password' => $request->mail_password,
                        'mail.from.address' => $request->mail_from_address,
                        'mail.from.name' => $request->mail_from_name,
                    ]);

                    // Clear any cached mail configuration
                    app()->forgetInstance('mail.manager');
                    app()->forgetInstance('mailer');

                    // Send test email
                    Mail::raw(
                        "Test email berhasil dikirim!\n\n" .
                        "Konfigurasi yang berhasil:\n" .
                        "Host: " . $request->mail_host . "\n" .
                        "Port: " . $config['port'] . "\n" .
                        "Encryption: " . ($config['encryption'] ?? 'none') . "\n" .
                        "Username: " . $request->mail_username,
                        function ($message) use ($request) {
                            $message->to($request->email)
                                    ->subject('Test Email dari ' . $request->mail_from_name);
                        }
                    );

                    // Restore original configuration
                    config($originalConfig);
                    app()->forgetInstance('mail.manager');
                    app()->forgetInstance('mailer');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Test email berhasil dikirim! Konfigurasi: Port ' . $config['port'] . ', Encryption: ' . ($config['encryption'] ?? 'none')
                    ]);
                    
                } catch (\Exception $configError) {
                    // Restore configuration and try next
                    if (isset($originalConfig)) {
                        config($originalConfig);
                        app()->forgetInstance('mail.manager');
                        app()->forgetInstance('mailer');
                    }
                    continue;
                }
            }
            
            // If all configurations fail
            throw new \Exception('Semua konfigurasi SMTP gagal. Server email mungkin tidak dapat dijangkau.');

        } catch (\Exception $e) {
            Log::error('Email test failed: ' . $e->getMessage(), [
                'email' => $request->email,
                'host' => $request->mail_host,
                'port' => $request->mail_port,
                'username' => $request->mail_username,
                'encryption' => $request->mail_encryption,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim test email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function security()
    {
        $settings = [
            'session_lifetime' => config('session.lifetime'),
            'password_timeout' => config('auth.password_timeout'),
            'max_login_attempts' => config('auth.throttle.max_attempts', 5),
            'lockout_duration' => config('auth.throttle.decay_minutes', 1),
            'force_https' => config('app.force_https', false),
            'two_factor_enabled' => config('auth.two_factor.enabled', false),
        ];

        return view('admin.settings.security', compact('settings'));
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'session_lifetime' => 'required|integer|min:1|max:10080',
            'password_timeout' => 'required|integer|min:1|max:1440',
            'max_login_attempts' => 'required|integer|min:1|max:20',
            'lockout_duration' => 'required|integer|min:1|max:60',
            'force_https' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ]);

        $this->updateEnvFile([
            'SESSION_LIFETIME' => $request->session_lifetime,
            'AUTH_PASSWORD_TIMEOUT' => $request->password_timeout,
            'AUTH_MAX_ATTEMPTS' => $request->max_login_attempts,
            'AUTH_LOCKOUT_DURATION' => $request->lockout_duration,
            'FORCE_HTTPS' => $request->boolean('force_https') ? 'true' : 'false',
            'TWO_FACTOR_ENABLED' => $request->boolean('two_factor_enabled') ? 'true' : 'false',
        ]);

        return redirect()->back()->with('success', 'Security settings updated successfully.');
    }

    public function maintenance()
    {
        $isDown = app()->isDownForMaintenance();
        
        return view('admin.settings.maintenance', compact('isDown'));
    }

    public function toggleMaintenance(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            $message = 'Maintenance mode disabled.';
        } else {
            $secret = $request->get('secret', 'admin-secret');
            Artisan::call('down', [
                '--secret' => $secret,
                '--render' => 'errors::503',
            ]);
            $message = 'Maintenance mode enabled.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function cache()
    {
        $cacheInfo = [
            'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes_cached' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'views_cached' => is_dir(storage_path('framework/views')) && count(glob(storage_path('framework/views/*.php'))) > 0,
            'cache_size' => $this->getCacheSize(),
        ];

        return view('admin.settings.cache', compact('cacheInfo'));
    }

    public function clearCache(Request $request)
    {
        try {
            $type = $request->get('type', 'all');

            switch ($type) {
                case 'config':
                    Artisan::call('config:clear');
                    $message = 'Configuration cache cleared successfully.';
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    $message = 'Route cache cleared successfully.';
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    $message = 'View cache cleared successfully.';
                    break;
                case 'application':
                    Artisan::call('cache:clear');
                    $message = 'Application cache cleared successfully.';
                    break;
                default:
                    Artisan::call('optimize:clear');
                    $message = 'All caches cleared successfully.';
            }

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to clear cache: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function optimizeCache(Request $request)
    {
        try {
            Artisan::call('optimize');
            $message = 'Application optimized successfully. Configuration, routes, and views have been cached.';
            
            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to optimize application: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to optimize application: ' . $e->getMessage());
        }
    }

    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $envContent);
        
        // Clear config cache to reload new values
        Artisan::call('config:clear');
    }

    private function getCacheSize()
    {
        $size = 0;
        $paths = [
            storage_path('framework/cache'),
            storage_path('framework/views'),
            base_path('bootstrap/cache'),
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $size += $this->getDirSize($path);
            }
        }

        return $this->formatBytes($size);
    }

    private function getDirSize($directory)
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}