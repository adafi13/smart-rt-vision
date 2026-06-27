<x-guest-layout>
    <div style="margin-bottom:24px;">
        <h2 style="font-size:20px;font-weight:800;color:#0f172a;letter-spacing:-0.02em;margin:0 0 6px;">Buat Kata Sandi Baru</h2>
        <p style="font-size:14px;color:#64748b;margin:0;">Masukkan kata sandi baru Anda di bawah ini untuk akun <strong style="color:#334155;">{{ $request->email }}</strong>.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" style="display:flex;flex-direction:column;gap:18px;">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

        <div>
            <label class="auth-label" for="password">Kata Sandi Baru</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <input id="password" name="password" type="password" required autofocus autocomplete="new-password"
                       class="auth-input" placeholder="Minimal 8 karakter">
            </div>
            @error('password') <p class="auth-error" style="margin-top:6px;">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="auth-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <div class="icon-field">
                <svg style="width:18px;height:18px;color:#94a3b8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="auth-input" placeholder="Ulangi kata sandi baru">
            </div>
            @error('password_confirmation') <p class="auth-error" style="margin-top:6px;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="auth-btn" style="margin-top:4px;">
            <span style="display:flex;align-items:center;justify-content:center;gap:8px;">
                Simpan & Masuk
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </span>
        </button>
    </form>

    {{-- ═══════ NAMED SLOT: Sidebar ═══════ --}}
    <x-slot name="sidebar">
        <div style="display:flex;flex-direction:column;gap:28px;">
            <div class="fade-in-up">
                <h2 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.15;margin:0 0 12px;">
                    Amankan<br><span style="color:#60a5fa;">Akun Anda.</span>
                </h2>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;line-height:1.7;margin:0;">
                    Gunakan kombinasi huruf, angka, dan simbol untuk memastikan akun pengurus RT Anda tidak mudah dibobol.
                </p>
            </div>
        </div>
    </x-slot>
</x-guest-layout>
