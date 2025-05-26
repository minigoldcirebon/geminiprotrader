# Panduan Konfigurasi Email

## Status Saat Ini
Aplikasi saat ini menggunakan **log driver** untuk email karena server SMTP `mail.geminiprotrader.com` tidak dapat dijangkau.

## Pengujian Email

### 1. Test Email via Web Interface
- Buka `/admin/settings/email`
- Klik tombol "Send Test" 
- Email akan ditulis ke log file di `storage/logs/laravel.log`

### 2. Test Email via Command Line
```bash
php artisan email:test test@example.com
```

## Konfigurasi Email Driver

### Log Driver (Saat Ini Aktif)
```env
MAIL_MAILER=log
```
- Email ditulis ke file log
- Berguna untuk development dan testing
- Tidak mengirim email sesungguhnya

### SMTP Driver (Untuk Production)
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## Troubleshooting

### Error: "Connection could not be established"
1. **Periksa hostname SMTP** - Pastikan server dapat dijangkau
2. **Periksa port** - Coba port 587 (TLS) atau 465 (SSL)
3. **Periksa firewall** - Pastikan port tidak diblokir
4. **Periksa credentials** - Username dan password harus benar

### Alternatif SMTP Providers
- **Gmail**: smtp.gmail.com:587 (TLS)
- **SendGrid**: smtp.sendgrid.net:587 (TLS)
- **Mailgun**: smtp.mailgun.org:587 (TLS)

### Testing dengan Log Driver
Untuk melihat email yang dikirim dengan log driver:
```bash
Get-Content storage\logs\laravel.log | Select-Object -Last 20
```

## Konfigurasi yang Direkomendasikan

### Untuk Development
```env
MAIL_MAILER=log
```

### Untuk Production
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Your App Name"
```

## Next Steps
1. Verifikasi server `mail.geminiprotrader.com` atau gunakan provider lain
2. Update konfigurasi di `.env`
3. Test email functionality
4. Switch ke SMTP driver untuk production
