    <header x-data="{ navOpen: false }" class="fixed top-0 inset-x-0 z-40 glass-dark border-b border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center overflow-hidden bg-white/10 backdrop-blur border border-white/20 flex-shrink-0">
                    <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-white text-sm sm:text-base truncate">{{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Layanan</a>
                <a href="{{ route('home') }}#kas" class="hover:text-white transition-colors">Transparansi Kas</a>
                <a href="{{ route('home') }}#warta" class="hover:text-white transition-colors">Warta</a>
                <a href="{{ route('home') }}#umkm" class="hover:text-white transition-colors">UMKM</a>
            </nav>

            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-white/10 hover:bg-white/20 border border-white/10 transition-colors whitespace-nowrap">
                    <span class="sm:hidden">Login</span>
                    <span class="hidden sm:inline">Login Pengurus</span>
                    <svg class="w-3.5 h-3.5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <button type="button" @click="navOpen = !navOpen" class="md:hidden p-2 rounded-xl text-slate-300 bg-white/5 hover:text-white hover:bg-white/10 border border-white/10 transition-colors">
                    <svg x-show="!navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="navOpen" class="w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div x-show="navOpen" x-transition @click="navOpen = false" class="md:hidden border-t border-white/10 px-4 sm:px-6 py-3 flex flex-col gap-1 text-sm font-medium text-slate-300">
            <a href="{{ route('home') }}#layanan" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Layanan Mandiri</a>
            <a href="{{ route('home') }}#kas" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Transparansi Kas</a>
            <a href="{{ route('home') }}#warta" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Warta</a>
            <a href="{{ route('home') }}#umkm" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">UMKM</a>
        </div>
    </header>
