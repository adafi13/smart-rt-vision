<x-guest-layout>
    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-8 font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke halaman masuk
    </a>

    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-900">Lupa password?</h2>
        <p class="text-gray-500 text-sm mt-1 leading-relaxed">Masukkan email Anda dan kami kirimkan tautan untuk mengatur ulang password.</p>
    </div>

    @if (session('status'))
        <div class="mb-5 flex items-start gap-2 text-sm px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="label" for="email">Alamat Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="input-auth" placeholder="nama@email.com">
            @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <button type="submit"
                class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition-all flex items-center justify-center gap-2"
                style="background: #4f46e5;"
                onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Kirim Tautan Reset
        </button>
    </form>
</x-guest-layout>
