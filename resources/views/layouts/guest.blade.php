<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartRT Vision') }} — {{ $title ?? 'Masuk' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        /* ─── Sidebar ─── */
        .auth-sidebar-bg {
            background: linear-gradient(145deg, #1e3a5f 0%, #0f2744 55%, #080f1e 100%);
            position: relative;
            overflow: hidden;
        }

        /* Dot grid */
        .auth-dot-grid {
            background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px);
            background-size: 26px 26px;
            mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, black 20%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, black 20%, transparent 75%);
        }

        /* Glassmorphism Cards pada sidebar */
        .glass-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.37);
            transition: transform 0.3s ease, background 0.3s ease, border-color 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-3px);
            border-color: rgba(99,140,255,0.35);
        }
        .glass-icon {
            background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Floating icons di sidebar */
        .floating-icon {
            position: absolute;
            animation: iconFloat 25s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes iconFloat {
            0%, 100% { transform: translate(0,0) rotate(0deg); }
            25%       { transform: translate(14px,-22px) rotate(5deg); }
            50%       { transform: translate(-10px,-38px) rotate(-4deg); }
            75%       { transform: translate(-18px,-14px) rotate(3deg); }
        }

        /* Fade in up */
        .fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.2,0.8,0.2,1) both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── Form Panel ─── */
        .input-premium {
            width: 100%;
            padding: 13px 16px 13px 46px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-premium:hover  { border-color: #cbd5e1; }
        .input-premium:focus  {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
        }
        .input-premium::placeholder { color: #94a3b8; font-weight: 400; }
        .input-premium.no-icon { padding-left: 16px; }

        /* Tombol submit */
        .btn-premium {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px -6px rgba(37,99,235,0.55);
            position: relative; overflow: hidden;
        }
        .btn-premium:hover  { background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%); transform: translateY(-2px); box-shadow: 0 14px 28px -8px rgba(37,99,235,0.55); }
        .btn-premium:active { transform: translateY(0); }

        /* Tab Switch */
        .tab-wrap {
            display: flex; gap: 4px; padding: 4px;
            background: #f1f5f9;
            border-radius: 14px; margin-bottom: 28px;
        }
        .tab-item {
            flex: 1; padding: 10px;
            border-radius: 10px; text-align: center;
            font-size: 13px; font-weight: 700;
            text-decoration: none; transition: all 0.2s;
            color: #64748b;
        }
        .tab-item.active { background: #fff; color: #0f172a; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
        .tab-item:hover:not(.active) { color: #334155; }

        /* Google btn */
        .btn-google {
            width: 100%; padding: 13px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            background: #fff; border: 1.5px solid #e2e8f0;
            border-radius: 14px; color: #1e293b;
            font-size: 13px; font-weight: 700;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-google:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-1px); }

        /* Divider */
        .divider { display:flex; align-items:center; gap:12px; margin: 20px 0; }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:#e2e8f0; }
        .divider span { font-size: 10px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; }

        .auth-label { display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 7px; }
        .auth-error { font-size: 12px; color: #ef4444; margin-top: 5px; font-weight: 600; }
        .icon-field { position: relative; }
        .icon-field svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); pointer-events: none; }

        @media (prefers-reduced-motion: reduce) {
            .floating-icon, .fade-in-up { animation: none; }
            .fade-in-up { opacity: 1; }
        }
    </style>
</head>
<body class="h-full bg-white selection:bg-blue-100 selection:text-blue-900">

    <div class="flex h-full min-h-screen">

        <!-- ── LEFT: Form Panel ── -->
        <main class="w-full lg:w-1/2 flex flex-col px-6 bg-white overflow-y-auto min-h-screen relative">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center pt-10 pb-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                        <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <span style="font-size:18px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;">SmartRT Vision</span>
                </div>
            </div>

            <div class="w-full max-w-md mx-auto my-auto py-10">
                {{ $slot }}

                <!-- Corporate Watermark -->
                <div class="pt-8 mt-4 border-t border-slate-100 flex flex-col items-center gap-2 opacity-40 hover:opacity-100 transition-opacity duration-500">
                    <div class="flex items-center gap-2">
                        <div style="width:18px;height:18px;background:#f1f5f9;border-radius:5px;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:10px;height:10px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <span style="font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.2em;">Powered by</span>
                    </div>
                    <a href="https://sekawanputrapratama.com/" target="_blank" rel="noopener noreferrer" style="font-size:10px;font-weight:800;color:#64748b;text-decoration:none;letter-spacing:0.1em;text-transform:uppercase;">PT. Sekawan Putra Pratama</a>
                </div>
            </div>
        </main>

        <!-- ── RIGHT: Sidebar ── -->
        <aside class="hidden lg:flex lg:w-1/2 auth-sidebar-bg relative flex-col justify-between p-16 overflow-hidden">

            <!-- Glow blobs -->
            <div class="absolute inset-0 opacity-40 pointer-events-none">
                <div class="absolute top-[-10%] right-[-10%] w-[55%] h-[55%] rounded-full blur-[130px]" style="background: rgba(59,130,246,0.25);"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[45%] h-[45%] rounded-full blur-[110px]" style="background: rgba(99,102,241,0.2);"></div>
                <div class="absolute top-1/2 left-1/2 w-[30%] h-[30%] rounded-full blur-[80px] -translate-x-1/2 -translate-y-1/2" style="background: rgba(139,92,246,0.15);"></div>
            </div>

            <!-- Dot grid texture -->
            <div class="absolute inset-0 auth-dot-grid pointer-events-none"></div>

            <!-- Floating RT icons -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Rumah -->
                <div class="floating-icon top-[12%] left-[8%] text-blue-400/20" style="animation-duration:28s;">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                </div>
                <!-- KTP/ID -->
                <div class="floating-icon top-[38%] right-[12%] text-indigo-400/15" style="animation-duration:33s;animation-delay:-6s;">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zM7.5 7C8.88 7 10 8.12 10 9.5S8.88 12 7.5 12 5 10.88 5 9.5 6.12 7 7.5 7zM13 17H4v-1c0-1.67 3.33-2.5 5-2.5s5 .83 5 2.5v1zm4-2h-2v-1h2v1zm0-3h-4v-1h4v1zm0-3h-4V8h4v1z"/></svg>
                </div>
                <!-- Chart -->
                <div class="floating-icon bottom-[18%] left-[15%] text-blue-300/20" style="animation-duration:22s;animation-delay:-12s;">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                </div>
                <!-- Keluarga/Orang -->
                <div class="floating-icon top-[62%] left-[3%] text-indigo-300/10" style="animation-duration:38s;animation-delay:-18s;">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <!-- Bintang/AI -->
                <div class="floating-icon bottom-[8%] right-[8%] text-blue-400/15" style="animation-duration:30s;animation-delay:-4s;">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L9.5 8.5 2 12l7.5 3.5L12 23l2.5-7.5L22 12l-7.5-3.5z"/></svg>
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10 h-full flex flex-col">

                <!-- Logo -->
                <div class="mb-12 fade-in-up flex items-center gap-4">
                    <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;overflow:hidden;backdrop-filter:blur(8px);">
                        <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <div>
                        <p style="font-size:20px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;">SmartRT Vision</p>
                        <p style="font-size:10px;font-weight:600;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.15em;">Platform RT Digital</p>
                    </div>
                </div>

                <!-- Slot konten sidebar dari halaman login/register -->
                <div class="flex-1 flex flex-col justify-center">
                    {{ $sidebar ?? '' }}
                </div>

                <!-- Footer -->
                <div class="mt-auto pt-8 border-t border-white/10 fade-in-up">
                    <p style="font-size:10px;color:rgba(255,255,255,0.35);font-weight:600;text-transform:uppercase;letter-spacing:0.15em;line-height:1.8;">
                        &copy; {{ date('Y') }} <strong style="color:rgba(255,255,255,0.5);">PT. Sekawan Putra Pratama</strong>.<br>
                        Seluruh Hak Cipta Dilindungi.
                    </p>
                </div>
            </div>
        </aside>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('invalid', function(e) {
            e.target.setCustomValidity('');
            if (!e.target.validity.valid) {
                let fieldName = '';
                const id = e.target.id;
                if (id) { const lbl = document.querySelector(`label[for="${id}"]`); if (lbl) fieldName = lbl.innerText.trim(); }
                if (!fieldName && e.target.placeholder) { fieldName = e.target.placeholder.replace(/Contoh:|contoh:|\.\.\./g,'').trim(); }
                if (fieldName) { fieldName = fieldName.split('\n')[0].replace(/\*|:/g,'').trim(); }
                if (e.target.validity.valueMissing) { e.target.setCustomValidity(`${fieldName || 'Kolom ini'} wajib diisi.`); }
                else if (e.target.validity.typeMismatch && e.target.type==='email') { e.target.setCustomValidity('Format email tidak valid.'); }
            }
        }, true);
        document.addEventListener('input', function(e) { e.target.setCustomValidity(''); });
    </script>
</body>
</html>
