<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartRT Vision') }} — Masuk</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #fff; }

        /* ── Layout Wrapper ── */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT: Form Panel ── */
        .auth-form-panel {
            width: 100%;
            max-width: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }
        @media (min-width: 1024px) {
            .auth-form-panel { width: 50%; max-width: 50%; }
        }

        .auth-form-inner {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            padding: 48px 24px;
        }

        /* ── RIGHT: Sidebar Panel ── */
        .auth-sidebar-panel {
            display: none;
        }
        @media (min-width: 1024px) {
            .auth-sidebar-panel {
                display: flex;
                width: 50%;
                flex-direction: column;
                justify-content: space-between;
                padding: 56px 56px 48px;
                overflow: hidden;
                position: relative;
                background: linear-gradient(145deg, #1e3a5f 0%, #0f2744 55%, #080f1e 100%);
            }
        }

        /* Dot grid */
        .auth-sidebar-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px);
            background-size: 26px 26px;
            -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, black 20%, transparent 75%);
            mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, black 20%, transparent 75%);
            pointer-events: none;
            z-index: 0;
        }

        /* Glow blobs */
        .sidebar-glow {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .sidebar-glow-1 { top: -15%; right: -10%; width: 55%; height: 55%; background: rgba(59,130,246,0.2); filter: blur(120px); }
        .sidebar-glow-2 { bottom: -10%; left: -10%; width: 45%; height: 45%; background: rgba(99,102,241,0.18); filter: blur(110px); }

        /* Floating icons */
        .float-icon {
            position: absolute;
            pointer-events: none;
            z-index: 0;
            animation: authIconFloat 25s ease-in-out infinite;
        }
        @keyframes authIconFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(12px, -20px) rotate(4deg); }
            50% { transform: translate(-8px, -35px) rotate(-3deg); }
            75% { transform: translate(-15px, -12px) rotate(2deg); }
        }

        /* Sidebar content */
        .sidebar-content { position: relative; z-index: 1; }

        /* Glass cards */
        .glass-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .glass-card:hover { transform: translateY(-2px); border-color: rgba(96,165,250,0.3); }
        .glass-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
            border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* Fade in up */
        .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.2,0.8,0.2,1) both; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(22px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Mobile Logo ── */
        .auth-mobile-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 28px 24px 8px;
        }
        @media (min-width: 1024px) { .auth-mobile-logo { display: none; } }

        /* ── Form Elements ── */
        .auth-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .auth-input:hover { border-color: #cbd5e1; }
        .auth-input:focus { border-color: #3b82f6; background: #fff; box-shadow: 0 0 0 4px rgba(59,130,246,0.12); }
        .auth-input::placeholder { color: #94a3b8; font-weight: 400; }
        .auth-input.no-icon { padding-left: 16px; }

        .auth-btn {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff; font-size: 15px; font-weight: 700;
            border: none; border-radius: 12px; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px -4px rgba(37,99,235,0.5);
        }
        .auth-btn:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-1px); box-shadow: 0 10px 24px -6px rgba(37,99,235,0.55); }
        .auth-btn:active { transform: none; }

        .auth-google-btn {
            width: 100%; padding: 13px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            background: #fff; border: 1.5px solid #e2e8f0;
            border-radius: 12px; color: #1e293b;
            font-size: 13px; font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none; transition: all 0.2s; cursor: pointer;
        }
        .auth-google-btn:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-1px); }

        .tab-wrap {
            display: flex; gap: 4px; padding: 4px;
            background: #f1f5f9; border-radius: 12px;
            margin-bottom: 24px;
        }
        .tab-item {
            flex: 1; padding: 9px; border-radius: 8px;
            text-align: center; font-size: 13px; font-weight: 700;
            text-decoration: none; color: #64748b;
            transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .tab-item.active { background: #fff; color: #0f172a; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
        .tab-item:hover:not(.active) { color: #334155; }

        .auth-label { display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px; }
        .auth-error { font-size: 12px; color: #ef4444; margin-top: 5px; font-weight: 600; }

        .icon-field { position: relative; }
        .icon-field > svg:first-child,
        .icon-field > img:first-child {
            position: absolute; left: 14px;
            top: 50%; transform: translateY(-50%);
            pointer-events: none; z-index: 1;
        }

        .auth-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
        }
        .auth-divider::before, .auth-divider::after {
            content: ''; flex: 1; height: 1px; background: #e2e8f0;
        }
        .auth-divider span { font-size: 10px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; }

        .auth-watermark {
            padding-top: 28px; margin-top: 24px;
            border-top: 1px solid #f1f5f9;
            display: flex; flex-direction: column; align-items: center;
            gap: 6px; opacity: 0.45; transition: opacity 0.5s;
        }
        .auth-watermark:hover { opacity: 1; }

        @keyframes authSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class="auth-wrapper">

    <!-- ═══ LEFT: Form Panel ═══ -->
    <div class="auth-form-panel">

        <!-- Mobile Logo -->
        <div class="auth-mobile-logo">
            <div style="width:36px;height:36px;border-radius:10px;overflow:hidden;background:linear-gradient(135deg,#2563eb,#7c3aed);">
                <img src="{{ asset('logo.png') }}" alt="SmartRT" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <span style="font-size:16px;font-weight:900;color:#0f172a;letter-spacing:-0.02em;">SmartRT Vision</span>
        </div>

        <div class="auth-form-inner">
            {{ $slot }}

            <!-- Watermark -->
            <div class="auth-watermark">
                <div style="display:flex;align-items:center;gap:6px;">
                    <svg style="width:14px;height:14px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span style="font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.2em;">Powered by</span>
                </div>
                <a href="https://sekawanputrapratama.com/" target="_blank" rel="noopener noreferrer"
                   style="font-size:10px;font-weight:800;color:#64748b;text-decoration:none;letter-spacing:0.1em;text-transform:uppercase;">
                    PT. Sekawan Putra Pratama
                </a>
            </div>
        </div>
    </div>

    <!-- ═══ RIGHT: Sidebar Panel ═══ -->
    <div class="auth-sidebar-panel">
        <!-- Decorative -->
        <div class="sidebar-glow sidebar-glow-1"></div>
        <div class="sidebar-glow sidebar-glow-2"></div>
        <!-- Floating icons -->
        <div class="float-icon" style="top:12%;left:8%;color:rgba(96,165,250,0.18);animation-duration:28s;">
            <svg width="56" height="56" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        </div>
        <div class="float-icon" style="top:40%;right:10%;color:rgba(139,92,246,0.15);animation-duration:33s;animation-delay:-6s;">
            <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zM7.5 7C8.88 7 10 8.12 10 9.5S8.88 12 7.5 12 5 10.88 5 9.5 6.12 7 7.5 7zM13 17H4v-1c0-1.67 3.33-2.5 5-2.5s5 .83 5 2.5v1zm4-2h-2v-1h2v1zm0-3h-4v-1h4v1zm0-3h-4V8h4v1z"/></svg>
        </div>
        <div class="float-icon" style="bottom:22%;left:12%;color:rgba(59,130,246,0.18);animation-duration:22s;animation-delay:-11s;">
            <svg width="44" height="44" fill="currentColor" viewBox="0 0 24 24"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
        </div>
        <div class="float-icon" style="bottom:8%;right:8%;color:rgba(96,165,250,0.12);animation-duration:30s;animation-delay:-4s;">
            <svg width="52" height="52" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L9.5 8.5 2 12l7.5 3.5L12 23l2.5-7.5L22 12l-7.5-3.5z"/></svg>
        </div>

        <!-- Logo -->
        <div class="sidebar-content fade-in-up" style="display:flex;align-items:center;gap:14px;margin-bottom:40px;">
            <div style="width:48px;height:48px;border-radius:13px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div>
                <p style="font-size:18px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;">SmartRT Vision</p>
                <p style="font-size:9px;font-weight:600;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.15em;margin-top:2px;">Platform RT Digital</p>
            </div>
        </div>

        <!-- Sidebar slot content -->
        <div class="sidebar-content" style="flex:1;display:flex;flex-direction:column;justify-content:center;">
            {{ $sidebar ?? '' }}
        </div>

        <!-- Footer -->
        <div class="sidebar-content fade-in-up" style="margin-top:32px;padding-top:24px;border-top:1px solid rgba(255,255,255,0.08);">
            <p style="font-size:10px;color:rgba(255,255,255,0.3);font-weight:600;text-transform:uppercase;letter-spacing:0.15em;line-height:1.8;">
                &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.45);">PT. Sekawan Putra Pratama</strong>.<br>
                Seluruh Hak Cipta Dilindungi.
            </p>
        </div>
    </div>

</div>

@stack('scripts')
<script>
    document.addEventListener('invalid', function(e) {
        e.target.setCustomValidity('');
        if (!e.target.validity.valid) {
            let field = '';
            const lbl = e.target.id ? document.querySelector('label[for="' + e.target.id + '"]') : null;
            if (lbl) field = lbl.innerText.trim().replace(/\*|:/g,'').split('\n')[0].trim();
            if (e.target.validity.valueMissing) e.target.setCustomValidity((field || 'Kolom ini') + ' wajib diisi.');
            else if (e.target.validity.typeMismatch && e.target.type === 'email') e.target.setCustomValidity('Format email tidak valid.');
        }
    }, true);
    document.addEventListener('input', function(e) { e.target.setCustomValidity(''); });
</script>
</body>
</html>
