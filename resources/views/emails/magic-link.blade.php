<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; color: #334155; line-height: 1.6; }
        .wrapper { width: 100%; max-width: 600px; margin: 0 auto; padding: 40px 20px; box-sizing: border-box; }
        .card { background-color: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
        .logo { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: block; text-decoration: none; }
        .logo span { color: #3b82f6; }
        h1 { font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px; }
        p { font-size: 15px; margin-top: 0; margin-bottom: 24px; color: #475569; }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff; font-weight: 600; font-size: 15px; text-decoration: none; padding: 14px 28px; border-radius: 8px; margin-bottom: 24px; text-align: center; }
        .footer { margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #94a3b8; }
        .muted { color: #94a3b8; font-size: 13px; margin-bottom: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <a href="{{ config('app.url') }}" class="logo">SmartRT <span>Vision</span></a>
            
            <h1>Halo, {{ $user->name ?? 'Pengguna' }}!</h1>
            
            <p>Anda menerima email ini karena kami menerima permintaan login menggunakan Magic Link untuk akun Anda di sistem SmartRT Vision.</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">Masuk Tanpa Sandi Sekarang</a>
            </div>
            
            <p>Tautan masuk ini hanya berlaku selama 15 menit dan hanya bisa digunakan satu kali.</p>
            
            <p>Jika Anda tidak pernah meminta akses ini, abaikan email ini dan akun Anda akan tetap aman.</p>
            
            <p style="margin-bottom: 0;">Salam hangat,<br><strong>Tim SmartRT Vision</strong></p>
            
            <div class="footer">
                <p class="muted">Jika Anda kesulitan mengklik tombol, salin dan tempel URL berikut ke peramban web Anda:</p>
                <p class="muted" style="word-break: break-all; color: #3b82f6;">{{ $url }}</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px; color: #94a3b8; font-size: 12px;">
            &copy; {{ date('Y') }} SmartRT Vision. Semua Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
