<x-guest-layout>
    @if (session('status'))
        <div class="mb-5 flex items-center gap-2 text-sm px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-900">Selamat datang kembali 👋</h2>
        <p class="text-gray-500 text-sm mt-1">Masuk untuk mengelola data warga RT Anda.</p>
    </div>

    <!-- Tab switch -->
    <div class="flex gap-1 p-1 rounded-xl mb-6 bg-gray-100">
        <a href="{{ route('login') }}"
           class="flex-1 py-2 rounded-lg text-center text-sm font-semibold bg-white text-gray-900 shadow-sm">
            Masuk
        </a>
        <a href="{{ route('register') }}"
           class="flex-1 py-2 rounded-lg text-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="label" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', request()->cookie('remembered_email')) }}" required autofocus
                   class="input-auth" placeholder="nama@email.com">
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="label mb-0" for="password">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">Lupa password?</a>
                @endif
            </div>
            <input id="password" name="password" type="password" required
                   class="input-auth" placeholder="Masukkan password">
            @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 rounded text-indigo-600 border-gray-300" {{ old('remember') || request()->cookie('remembered_email') ? 'checked' : '' }}>
            <label for="remember_me" class="text-sm text-gray-600 cursor-pointer">Ingat saya</label>
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition-all"
                style="background: #4f46e5;"
                onmouseover="this.style.background='#4338ca'; this.style.boxShadow='0 4px 16px rgba(79,70,229,0.3)'"
                onmouseout="this.style.background='#4f46e5'; this.style.boxShadow='none'">
            Masuk ke Sistem
        </button>
    </form>

    <div class="mt-6 flex items-center justify-between">
        <span class="w-1/5 border-b border-gray-200 lg:w-1/4"></span>
        <span class="text-xs text-center text-gray-500 uppercase font-semibold">atau masuk dengan</span>
        <span class="w-1/5 border-b border-gray-200 lg:w-1/4"></span>
    </div>

    <div class="mt-6">
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-gray-300 rounded-xl bg-white hover:bg-gray-50 transition-colors shadow-sm text-sm font-semibold text-gray-700">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            Google
        </a>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Daftar di sini</a>
    </p>
</x-guest-layout>
