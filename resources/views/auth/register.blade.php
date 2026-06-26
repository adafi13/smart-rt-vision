<x-guest-layout>
    <div class="mb-7">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Trial 14 hari, tanpa kartu kredit
        </span>
        <h2 class="text-2xl font-bold text-gray-900">Daftarkan RT Anda</h2>
        <p class="text-gray-500 text-sm mt-1">Buat workspace RT sendiri dan mulai kelola data warga dalam hitungan menit.</p>
    </div>

    <!-- Tab switch -->
    <div class="flex gap-1 p-1 rounded-xl mb-6 bg-gray-100">
        <a href="{{ route('login') }}"
           class="flex-1 py-2 rounded-lg text-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            Masuk
        </a>
        <a href="{{ route('register') }}"
           class="flex-1 py-2 rounded-lg text-center text-sm font-semibold bg-white text-gray-900 shadow-sm">
            Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="label" for="tenant_name">Nama RT / Lingkungan</label>
            <input id="tenant_name" name="tenant_name" type="text" value="{{ old('tenant_name') }}" required
                   class="input-auth" placeholder="Contoh: RT 022 Sukaragam">
            @error('tenant_name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label" for="name">Nama Lengkap Anda</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="input-auth" placeholder="Nama pengurus RT">
            @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   class="input-auth" placeholder="nama@email.com">
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label" for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="input-auth" placeholder="Minimal 8 karakter">
            @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label" for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="input-auth" placeholder="Ulangi password">
            @error('password_confirmation') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition-all"
                style="background: #4f46e5;"
                onmouseover="this.style.background='#4338ca'; this.style.boxShadow='0 4px 16px rgba(79,70,229,0.3)'"
                onmouseout="this.style.background='#4f46e5'; this.style.boxShadow='none'">
            Buat Akun
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Masuk di sini</a>
    </p>
</x-guest-layout>
