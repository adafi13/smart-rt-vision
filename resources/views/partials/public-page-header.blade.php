    <section class="pt-32 pb-14 px-4 sm:px-6" style="background: radial-gradient(120% 100% at 50% 0%, #1e1b4b 0%, #0f0d24 55%, #0a0915 100%);">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-3xl sm:text-4xl font-black text-white">{{ $title }}</h1>
            @isset($subtitle)
                <p class="text-sm text-slate-400 mt-3">{{ $subtitle }}</p>
            @endisset
        </div>
    </section>
