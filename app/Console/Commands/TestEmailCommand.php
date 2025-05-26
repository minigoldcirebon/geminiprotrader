<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Send a test email to verify email configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing email configuration...');
        $this->info('SMTP Host: ' . config('mail.mailers.smtp.host'));
        $this->info('SMTP Port: ' . config('mail.mailers.smtp.port'));
        $this->info('SMTP Encryption: ' . config('mail.mailers.smtp.encryption'));
        $this->info('From Email: ' . config('mail.from.address'));
        
        // Store original configuration
        $originalConfig = config('mail.mailers.smtp');
        
        // Test different configurations
        $configurations = [
            ['port' => 465, 'encryption' => 'ssl'],
            ['port' => 587, 'encryption' => 'tls'], 
            ['port' => 587, 'encryption' => 'starttls'],
            ['port' => 25, 'encryption' => null],
        ];
        
        foreach ($configurations as $config) {
            $this->info("\n--- Testing with Port {$config['port']}, Encryption: " . ($config['encryption'] ?? 'none') . " ---");
            
            try {
                // Create a new mailer instance with custom configuration
                $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory();
                
                $dsn = 'smtp://' . 
                       urlencode(config('mail.mailers.smtp.username')) . ':' . 
                       urlencode(config('mail.mailers.smtp.password')) . '@' . 
                       config('mail.mailers.smtp.host') . ':' . $config['port'];
                
                if ($config['encryption']) {
                    $dsn .= '?encryption=' . $config['encryption'];
                }
                
                $transportInstance = $transport->create(\Symfony\Component\Mailer\Transport\Dsn::fromString($dsn));
                $mailer = new \Symfony\Component\Mailer\Mailer($transportInstance);
                
                // Create email message
                $email_msg = (new \Symfony\Component\Mime\Email())
                    ->from(config('mail.from.address'))
                    ->to($email)
                    ->subject('Test Email from ' . config('mail.from.name'))
                    ->text('This is a test email. Configuration: Port ' . $config['port'] . ', Encryption: ' . ($config['encryption'] ?? 'none'));
                
                $mailer->send($email_msg);
                
                $this->info('✅ SUCCESS! Email sent with port ' . $config['port'] . ' and encryption ' . ($config['encryption'] ?? 'none'));
                
                // Update .env with working configuration
                $this->updateEnvFile([
                    'MAIL_PORT' => $config['port'],
                    'MAIL_ENCRYPTION' => $config['encryption'] ?? '',
                ]);
                
                $this->info('✅ Updated .env file with working configuration');
                return Command::SUCCESS;
                
            } catch (\Exception $e) {
                $this->error('❌ Failed with port ' . $config['port'] . ': ' . $e->getMessage());
            }
        }
        
        $this->error('All SMTP configurations failed. Please check your email server settings.');
        $this->info('You may need to:');
        $this->info('1. Verify the SMTP server hostname is correct');
        $this->info('2. Check if your hosting provider allows SMTP connections');
        $this->info('3. Verify username and password are correct');
        $this->info('4. Check if firewall is blocking the connection');
        return Command::FAILURE;
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
    }
}
