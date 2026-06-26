<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-white">Buat Password Baru</h2>
        <p class="text-slate-400 text-sm mt-1.5">Masukkan password baru Anda di bawah ini.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </span>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                    class="w-full bg-slate-800/60 border border-slate-700/60 text-white placeholder-slate-500 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:border-blue-500/60 transition-all">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Password Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="w-full bg-slate-800/60 border border-slate-700/60 text-white placeholder-slate-500 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:border-blue-500/60 transition-all"
                    placeholder="Password baru">
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </span>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="w-full bg-slate-800/60 border border-slate-700/60 text-white placeholder-slate-500 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/60 focus:border-blue-500/60 transition-all"
                    placeholder="Ulangi password baru">
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition-all duration-200 hover:shadow-blue-500/40 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500/60">
            Simpan Password Baru
        </button>
    </form>
</x-guest-layout>
