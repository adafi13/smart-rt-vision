<x-guest-layout>
    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:32px;transition:color 0.2s;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke halaman masuk
    </a>

    <div class="fade-in-up">
        <h2 style="font-size:32px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin:0 0 12px;line-height:1.2;">
            Masuk Tanpa Sandi ⚡
        </h2>
        <p style="font-size:14px;color:#64748b;font-weight:500;line-height:1.6;margin:0 0 32px;">
            Masukkan email Anda, dan kami akan mengirimkan tautan masuk khusus yang bisa langsung di-klik tanpa perlu mengingat kata sandi.
        </p>
    </div>

    @if (session('status'))
        <div class="fade-in-up" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px;">
            <svg style="width:20px;height:20px;flex-shrink:0;margin-top:2px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <div style="line-height:1.6;">{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('magic-link.send') }}" id="magicForm" class="fade-in-up" style="display:flex;flex-direction:column;gap:20px;animation-delay:0.1s;">
        @csrf
        <div>
            <label class="auth-label" for="email">Alamat Email</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="auth-input" placeholder="nama@email.com">
            </div>
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>
        
        <!-- Cloudflare Turnstile -->
        <div>
            <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY', '1x00000000000000000000AA') }}" data-theme="light"></div>
            @error('cf-turnstile-response') <p class="auth-error" style="margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" id="magicBtn" class="auth-btn" style="margin-top:4px;">
            <span id="btnText" style="display:flex;align-items:center;justify-content:center;gap:8px;">
                Kirim Magic Link
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
            <span id="btnLoading" style="display:none;align-items:center;justify-content:center;gap:8px;">
                <svg style="width:18px;height:18px;animation:loginSpin 1s linear infinite;" fill="none" viewBox="0 0 24 24"><circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Memproses...
            </span>
        </button>
    </form>

    <script>
        document.getElementById('magicForm').addEventListener('submit', function() {
            const btn = document.getElementById('magicBtn');
            btn.disabled = true;
            document.getElementById('btnText').style.display = 'none';
            const loading = document.getElementById('btnLoading');
            loading.style.display = 'flex';
        });
    </script>

    {{-- ═══════ NAMED SLOT: Sidebar ═══════ --}}
    <x-slot name="sidebar">
        <div style="display:flex;flex-direction:column;gap:28px;">
            <div class="fade-in-up">
                <h2 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.15;margin:0 0 12px;">
                    Cepat & <span style="color:#60a5fa;">Aman.</span>
                </h2>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;line-height:1.7;margin:0;">
                    Lupakan kata sandi Anda. Magic Link adalah cara paling aman untuk masuk ke akun tanpa risiko kata sandi dicuri.
                </p>
            </div>

            <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                <div class="glass-icon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:20px;height:20px;color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Sekali Klik</h4>
                    <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">Langsung masuk tanpa perlu mengetik apapun</p>
                </div>
            </div>
        </div>
    </x-slot>
</x-guest-layout>
