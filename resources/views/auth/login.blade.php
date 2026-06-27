<x-guest-layout>

    {{-- ═══════ DEFAULT SLOT (Form Panel) ═══════ --}}
    @if (session('status'))
        <div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:12px;padding:12px 16px;border-radius:12px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;font-weight:600;">
            <svg style="width:15px;height:15px;flex-shrink:0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('status') }}
        </div>
    @endif

    @php
        $globalErrors = $errors->except(['email', 'password']);
    @endphp
    @if ($globalErrors->any())
        <div style="margin-bottom:20px;padding:12px 16px;border-radius:12px;background:#fff1f2;border:1px solid #fecdd3;">
            @foreach ($globalErrors->all() as $error)
                <p style="font-size:12px;color:#be123c;font-weight:600;margin:0 0 4px;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Heading -->
    <div style="margin-bottom:28px;">
        <h1 style="font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin:0 0 6px;line-height:1.2;">Masuk ke SmartRT Vision</h1>
        <p style="font-size:14px;color:#64748b;margin:0;font-weight:500;">Lanjutkan pengelolaan data warga dan laporan RT Anda.</p>
    </div>

    <!-- Tab switch -->
    <div class="tab-wrap">
        <a href="{{ route('login') }}" class="tab-item active">Masuk</a>
        <a href="{{ route('register') }}" class="tab-item">Daftar</a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" id="loginForm" style="display:flex;flex-direction:column;gap:18px;">
        @csrf

        <!-- Email -->
        <div>
            <label class="auth-label" for="email">Alamat Email</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input id="email" name="email" type="email"
                       value="{{ old('email', request()->cookie('remembered_email')) }}"
                       required autofocus
                       class="auth-input"
                       placeholder="nama@email.com">
            </div>
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;">
                <label class="auth-label" style="margin-bottom:0;" for="password">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:12px;font-weight:700;color:#2563eb;text-decoration:none;">Lupa kata sandi?</a>
                @endif
            </div>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <input id="password" name="password" type="password" required
                       class="auth-input"
                       style="padding-right:48px;"
                       placeholder="••••••••">
                <button type="button" onclick="togglePassword('password', this)"
                        style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;">
                    <svg class="eye-open" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="eye-closed" style="width:18px;height:18px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me -->
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
            <input id="remember_me" name="remember" type="checkbox"
                   style="width:16px;height:16px;border-radius:4px;accent-color:#2563eb;cursor:pointer;"
                   {{ old('remember') || request()->cookie('remembered_email') ? 'checked' : '' }}>
            <span style="font-size:13px;color:#64748b;font-weight:500;">Ingat sesi saya</span>
        </label>

        <!-- Submit -->
        <button type="submit" id="loginBtn" class="auth-btn" style="margin-top:4px;">
            <span id="btnText" style="display:flex;align-items:center;justify-content:center;gap:8px;">
                Masuk Ke Sistem
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
            <span id="btnLoading" style="display:none;align-items:center;justify-content:center;gap:8px;">
                <svg style="width:18px;height:18px;animation:loginSpin 1s linear infinite;" fill="none" viewBox="0 0 24 24"><circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Memproses...
            </span>
        </button>
    </form>

    <!-- Divider -->
    <div class="auth-divider"><span>atau opsi lain</span></div>

    <!-- Magic Link -->
    <a href="{{ route('magic-link.request') }}" class="auth-btn" style="background:#f8fafc;color:#0f172a;border:1px solid #e2e8f0;margin-bottom:12px;">
        <svg style="width:18px;height:18px;color:#3b82f6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span>Masuk dengan Magic Link</span>
    </a>

    <!-- Google -->
    <a href="{{ route('google.login') }}" class="auth-google-btn">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" style="width:18px;height:18px;" alt="Google">
        <span>Lanjutkan dengan Google</span>
    </a>

    <!-- Register link -->
    <div style="text-align:center;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
        <p style="font-size:13px;color:#64748b;font-weight:500;margin:0;">
            Belum memiliki akun?
            <a href="{{ route('register') }}" style="color:#2563eb;font-weight:700;text-decoration:none;">Buat Akun RT &rarr;</a>
        </p>
    </div>

    <style>@keyframes loginSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            document.getElementById('btnText').style.display = 'none';
            const loading = document.getElementById('btnLoading');
            loading.style.display = 'flex';
        });
    </script>

    {{-- ═══════ NAMED SLOT: Sidebar (harus langsung di dalam komponen, TIDAK dalam @push) ═══════ --}}
    <x-slot name="sidebar">
        <div style="display:flex;flex-direction:column;gap:28px;">

            <!-- Heading -->
            <div class="fade-in-up">
                <h2 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.15;margin:0 0 12px;">
                    Kelola <span style="color:#60a5fa;">Warga RT</span><br>Lebih Cerdas.
                </h2>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;line-height:1.7;margin:0;">
                    Platform terintegrasi untuk mengelola data warga, keuangan RT, pelaporan, inventaris, dan layanan mandiri.
                </p>
            </div>

            <!-- Stats -->
            <div style="display:flex;align-items:center;gap:20px;" class="fade-in-up">
                <div>
                    <p style="font-size:22px;font-weight:900;color:#fff;line-height:1;margin:0;">AI<span style="color:#60a5fa;">✦</span></p>
                    <p style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.15em;margin-top:5px;">Gemini AI</p>
                </div>
                <span style="width:1px;height:32px;background:rgba(255,255,255,0.1);"></span>
                <div>
                    <p style="font-size:22px;font-weight:900;color:#fff;line-height:1;margin:0;">100<span style="color:#60a5fa;">%</span></p>
                    <p style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.15em;margin-top:5px;">Transparan</p>
                </div>
                <span style="width:1px;height:32px;background:rgba(255,255,255,0.1);"></span>
                <div>
                    <p style="font-size:22px;font-weight:900;color:#fff;line-height:1;margin:0;">4.9<span style="color:#60a5fa;">/5</span></p>
                    <p style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.15em;margin-top:5px;">Rating</p>
                </div>
            </div>

            <!-- Feature Cards -->
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                    <div class="glass-icon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:20px;height:20px;color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Data Warga Aman</h4>
                        <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">Seluruh data KK terenkripsi dan terlindungi</p>
                    </div>
                </div>
                <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                    <div class="glass-icon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:20px;height:20px;color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Keuangan Transparan</h4>
                        <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">Laporan kas real-time dapat diakses warga</p>
                    </div>
                </div>
                <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                    <div class="glass-icon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:20px;height:20px;color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Ekstraksi AI Otomatis</h4>
                        <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">Upload foto KK, data langsung terisi AI</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-guest-layout>
