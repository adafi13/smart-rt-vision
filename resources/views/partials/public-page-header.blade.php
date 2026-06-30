    <section class="pt-36 pb-16 px-4 sm:px-6 relative overflow-hidden" style="background: radial-gradient(120% 100% at 50% 0%, #1e1b4b 0%, #0f0d24 55%, #0a0915 100%);">
        <!-- Ambient lighting glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full pointer-events-none bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-500/10 via-transparent to-transparent"></div>
        
        <div class="max-w-3xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-none">{{ $title }}</h1>
            @isset($subtitle)
                <p class="text-sm sm:text-base text-slate-400 mt-4 max-w-2xl mx-auto leading-relaxed">{{ $subtitle }}</p>
            @endisset
        </div>
        
        <div class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
    </section>
