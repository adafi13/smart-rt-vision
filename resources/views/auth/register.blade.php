<x-guest-layout>

    {{-- ═══════ DEFAULT SLOT (Form Panel) ═══════ --}}
    @if ($errors->any())
        <div style="margin-bottom:20px;padding:12px 16px;border-radius:12px;background:#fff1f2;border:1px solid #fecdd3;">
            @foreach ($errors->all() as $error)
                <p style="font-size:12px;color:#be123c;font-weight:600;margin:0 0 4px;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Badge + Heading -->
    <div style="margin-bottom:28px;">
        <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:100px;background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:14px;">
            <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
            <span style="font-size:11px;font-weight:700;color:#166534;letter-spacing:0.05em;text-transform:uppercase;">Trial 14 hari gratis</span>
        </div>
        <h1 style="font-size:28px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin:0 0 6px;line-height:1.2;">Daftarkan RT Anda 🏘️</h1>
        <p style="font-size:14px;color:#64748b;margin:0;font-weight:500;">Buat workspace RT dan mulai kelola data warga dalam hitungan menit.</p>
    </div>

    <!-- Tab switch -->
    <div class="tab-wrap">
        <a href="{{ route('login') }}" class="tab-item">Masuk</a>
        <a href="{{ route('register') }}" class="tab-item active">Daftar</a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:16px;">
        @csrf

        <!-- Nama RT -->
        <div>
            <label class="auth-label" for="tenant_name">Nama RT / Lingkungan</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                <input id="tenant_name" name="tenant_name" type="text" value="{{ old('tenant_name') }}" required
                       class="auth-input" placeholder="Contoh: RT 022 Sukaragam">
            </div>
            @error('tenant_name') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <!-- Nama Lengkap -->
        <div>
            <label class="auth-label" for="name">Nama Lengkap Anda</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                       class="auth-input" placeholder="Nama pengurus RT">
            </div>
            @error('name') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="auth-label" for="email">Alamat Email</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="auth-input" placeholder="nama@email.com">
            </div>
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <script>
        function checkPasswordStrength(val) {
            const meter = document.getElementById('password-meter');
            if (val.length > 0) {
                meter.style.display = 'block';
            } else {
                meter.style.display = 'none';
                return;
            }
            
            let strength = 0;
            const hasLength = val.length >= 8;
            const hasNumber = /\d/.test(val);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(val);
            
            if (hasLength) strength += 1;
            if (hasNumber) strength += 1;
            if (hasSpecial) strength += 1;
            if (val.length >= 12 && hasNumber && hasSpecial && /[A-Z]/.test(val)) strength += 1; // Extra point for very strong
            
            // Update checklist UI
            const updateCheck = (id, isValid) => {
                const el = document.getElementById(id);
                const circle = el.querySelector('circle');
                const check = el.querySelector('path');
                
                if (isValid) {
                    el.style.color = '#10b981';
                    circle.style.fill = '#10b981';
                    circle.style.stroke = '#10b981';
                    check.style.display = 'block';
                    check.style.stroke = '#ffffff';
                    el.parentElement.style.color = '#0f172a';
                } else {
                    el.style.color = '#94a3b8';
                    circle.style.fill = 'none';
                    circle.style.stroke = 'currentColor';
                    check.style.display = 'none';
                    el.parentElement.style.color = '#64748b';
                }
            };
            
            updateCheck('req-length', hasLength);
            updateCheck('req-number', hasNumber);
            updateCheck('req-special', hasSpecial);
            
            // Update Bars UI
            const bars = [
                document.getElementById('bar-1'),
                document.getElementById('bar-2'),
                document.getElementById('bar-3'),
                document.getElementById('bar-4')
            ];
            
            // Reset colors
            bars.forEach(b => b.style.background = '#e2e8f0');
            
            // Set colors based on strength
            const colors = ['#ef4444', '#f59e0b', '#10b981', '#059669'];
            for (let i = 0; i < strength; i++) {
                bars[i].style.background = colors[strength - 1] || '#10b981';
            }
        }
        </script>

        <!-- Password row -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
                <label class="auth-label" for="password">Password</label>
                <div class="icon-field">
                    <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="auth-input" placeholder="Min. 8 karakter" onkeyup="checkPasswordStrength(this.value)">
                </div>
                
                <!-- Password Strength Meter -->
                <div id="password-meter" style="margin-top: 8px; display: none;">
                    <div style="display: flex; gap: 4px; height: 4px; margin-bottom: 8px;">
                        <div id="bar-1" style="flex: 1; background: #e2e8f0; border-radius: 4px; transition: all 0.3s ease;"></div>
                        <div id="bar-2" style="flex: 1; background: #e2e8f0; border-radius: 4px; transition: all 0.3s ease;"></div>
                        <div id="bar-3" style="flex: 1; background: #e2e8f0; border-radius: 4px; transition: all 0.3s ease;"></div>
                        <div id="bar-4" style="flex: 1; background: #e2e8f0; border-radius: 4px; transition: all 0.3s ease;"></div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px; font-size: 11px; color: #64748b; font-weight: 500;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg id="req-length" style="width:12px;height:12px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"/></svg>
                            Minimal 8 karakter
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg id="req-number" style="width:12px;height:12px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"/></svg>
                            Mengandung angka
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg id="req-special" style="width:12px;height:12px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"/></svg>
                            Karakter spesial (!@#$%)
                        </div>
                    </div>
                </div>
                
                @error('password') <p class="auth-error" style="margin-top:6px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="auth-label" for="password_confirmation">Konfirmasi</label>
                <div class="icon-field">
                    <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="auth-input" placeholder="Ulangi password">
                </div>
                @error('password_confirmation') <p class="auth-error" style="margin-top:6px;">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Persetujuan Legalitas -->
        <div style="margin-top:4px;">
            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;">
                <input type="checkbox" name="terms" required
                       style="margin-top:2px;width:16px;height:16px;border-radius:4px;border:1px solid #cbd5e1;accent-color:#2563eb;">
                <span style="font-size:12px;color:#64748b;line-height:1.5;">
                    Saya setuju dengan 
                    <a href="#" style="color:#2563eb;font-weight:600;text-decoration:none;">Syarat & Ketentuan</a> serta 
                    <a href="#" style="color:#2563eb;font-weight:600;text-decoration:none;">Kebijakan Privasi</a> SmartRT Vision.
                </span>
            </label>
            @error('terms') <p class="auth-error" style="margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        <!-- Cloudflare Turnstile -->
        <div style="margin-top:8px;">
            <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY', '1x00000000000000000000AA') }}" data-theme="light"></div>
            @error('cf-turnstile-response') <p class="auth-error" style="margin-top:4px;">Verifikasi keamanan gagal. Silakan coba lagi.</p> @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="auth-btn" style="margin-top:4px;">
            <span style="display:flex;align-items:center;justify-content:center;gap:8px;">
                Buat Akun Gratis
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
        </button>
    </form>

    <div style="text-align:center;margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9;">
        <p style="font-size:13px;color:#64748b;font-weight:500;margin:0;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#2563eb;font-weight:700;text-decoration:none;">Masuk di sini</a>
        </p>
    </div>

    {{-- ═══════ NAMED SLOT: Sidebar (harus langsung di dalam komponen, TIDAK dalam @push) ═══════ --}}
    <x-slot name="sidebar">
        <div style="display:flex;flex-direction:column;gap:28px;">

            <!-- Heading -->
            <div class="fade-in-up">
                <h2 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.15;margin:0 0 12px;">
                    Mulai perjalanan<br><span style="color:#60a5fa;">digital RT Anda.</span>
                </h2>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;line-height:1.7;margin:0;">
                    Ratusan RT sudah membuktikan manfaatnya. Bergabunglah sekarang secara gratis.
                </p>
            </div>

            <!-- Steps -->
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                    <div style="width:32px;height:32px;border-radius:10px;background:rgba(96,165,250,0.2);border:1px solid rgba(96,165,250,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="font-size:13px;font-weight:900;color:#60a5fa;">1</span>
                    </div>
                    <div>
                        <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Daftar Akun</h4>
                        <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">Isi nama RT dan email Anda dalam 30 detik</p>
                    </div>
                </div>
                <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                    <div style="width:32px;height:32px;border-radius:10px;background:rgba(96,165,250,0.2);border:1px solid rgba(96,165,250,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="font-size:13px;font-weight:900;color:#60a5fa;">2</span>
                    </div>
                    <div>
                        <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Upload Data KK</h4>
                        <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">AI kami otomatis membaca dan mengekstrak data</p>
                    </div>
                </div>
                <div class="glass-card fade-in-up" style="padding:14px 18px;border-radius:14px;display:flex;align-items:center;gap:14px;">
                    <div style="width:32px;height:32px;border-radius:10px;background:rgba(96,165,250,0.2);border:1px solid rgba(96,165,250,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="font-size:13px;font-weight:900;color:#60a5fa;">3</span>
                    </div>
                    <div>
                        <h4 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">Aktifkan Portal</h4>
                        <p style="font-size:10px;color:rgba(255,255,255,0.4);font-weight:500;margin:0;">Warga langsung bisa mengakses layanan online</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-guest-layout>
