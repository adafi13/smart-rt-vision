<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SmartRT Vision — Platform SaaS terbaik untuk pengurus RT/RW: ekstraksi data KK otomatis dengan AI, portal mandiri warga, manajemen keuangan transparan, dan administrasi digital.">
    <meta name="keywords" content="aplikasi rt rw, software pengurus rt, bayar iuran rt, aplikasi kas rt, platform warga rt, manajemen rukun tetangga, ekstraksi kk ai, smart rt, rt digital, aplikasi warga">
    <meta name="author" content="Sekawan Putra Pratama">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'SmartRT Vision') }} — Kelola RT Lebih Mudah dengan AI">
    <meta property="og:description" content="Platform SaaS terbaik untuk pengurus RT/RW. Ekstraksi data KK otomatis, portal mandiri warga, dan manajemen keuangan transparan.">
    <meta property="og:image" content="{{ asset('logo.png') }}">
    <meta property="og:site_name" content="{{ config('app.name', 'SmartRT Vision') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ config('app.name', 'SmartRT Vision') }} — Kelola RT Lebih Mudah dengan AI">
    <meta name="twitter:description" content="Platform SaaS terbaik untuk pengurus RT/RW. Ekstraksi data KK otomatis, portal mandiri warga, dan manajemen keuangan transparan.">
    <meta name="twitter:image" content="{{ asset('logo.png') }}">

    <title>{{ config('app.name', 'SmartRT Vision') }} — Kelola RT Lebih Mudah dengan AI</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "SoftwareApplication",
      "name": "SmartRT Vision",
      "operatingSystem": "WebBrowser",
      "applicationCategory": "BusinessApplication",
      "offers": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "IDR"
      },
      "description": "Platform SaaS cerdas untuk pengurus RT/RW. Fitur ekstraksi data KK otomatis dengan AI, manajemen iuran, dan portal warga.",
      "creator": {
        "@@type": "Organization",
        "name": "Sekawan Putra Pratama"
      }
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0a0915">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.error('SW Error:', err));
            });
        }
    </script>

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { background: #f8f9fc; color: #0f172a; }

        .glass-dark { background: rgba(15,15,25,0.55); backdrop-filter: blur(14px) saturate(160%); -webkit-backdrop-filter: blur(14px) saturate(160%); }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 13px 24px; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; box-shadow: 0 8px 20px -6px rgba(99,102,241,0.5); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 28px -6px rgba(99,102,241,0.6); }
        .btn-ghost { background: rgba(255,255,255,0.08); color: white; padding: 13px 24px; border-radius: 12px; font-size: 14px; font-weight: 700; border: 1px solid rgba(255,255,255,0.25); cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-ghost:hover { background: rgba(255,255,255,0.16); }

        .card { background: white; border-radius: 24px; border: 1px solid #f1f1f4; box-shadow: 0 2px 10px -4px rgba(15,23,42,0.04); transition: all .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -16px rgba(79,70,229,0.18); }

        .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1); }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        /* Pricing card: smooth transition for scale/opacity/shadow changes */
        .pricing-card { transition: transform 0.35s cubic-bezier(.2,.8,.2,1), opacity 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease; }
        .pricing-card:hover { border-color: #818cf8 !important; box-shadow: 0 0 0 3px rgba(99,102,241,0.15), 0 6px 24px rgba(99,102,241,0.12) !important; opacity: 1 !important; transform: scale(1.01) !important; z-index: 5; }

        #hero3d-canvas { display: block; width: 100%; height: 100%; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        @keyframes bounce-y { 0%,100% { transform: translateY(0); } 50% { transform: translateY(8px); } }
        .bounce-y { animation: bounce-y 2s ease-in-out infinite; }

        @keyframes blink { 50% { border-color: transparent; } }
        .blink-cursor { animation: blink 1s step-end infinite; border-right: 2px solid #34d399; }
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }
        .perspective-1000 { perspective: 1000px; }
        .rotate-x-12 { transform: rotateX(12deg); }
        .hover\:rotate-x-0:hover { transform: rotateX(0deg); }

        details > summary { list-style: none; cursor: pointer; }
        details > summary::-webkit-details-marker { display: none; }
        details[open] .faq-chevron { transform: rotate(180deg); }
    </style>
</head>
<body x-data="{ navOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- ===================== NAVBAR ===================== -->
    <header :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-200' : 'glass-dark border-b border-white/10'" class="fixed top-0 inset-x-0 z-40 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('marketing.home') }}" class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 transition-colors duration-300" :class="scrolled ? 'bg-slate-100 border border-slate-200' : 'bg-white/10 backdrop-blur border border-white/20'">
                    <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-sm sm:text-base truncate transition-colors duration-300" :class="scrolled ? 'text-slate-900' : 'text-white'">{{ config('app.name', 'SmartRT Vision') }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium transition-colors duration-300" :class="scrolled ? 'text-slate-600' : 'text-slate-300'">
                <a href="#fitur" class="transition-colors" :class="scrolled ? 'hover:text-indigo-600' : 'hover:text-white'">Fitur</a>
                <a href="#cara-kerja" class="transition-colors" :class="scrolled ? 'hover:text-indigo-600' : 'hover:text-white'">Cara Kerja</a>
                <a href="#harga" class="transition-colors" :class="scrolled ? 'hover:text-indigo-600' : 'hover:text-white'">Harga</a>
                <a href="#faq" class="transition-colors" :class="scrolled ? 'hover:text-indigo-600' : 'hover:text-white'">FAQ</a>
            </nav>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-semibold px-3 py-2 transition-colors duration-300" :class="scrolled ? 'text-slate-600 hover:text-indigo-600' : 'text-slate-300 hover:text-white'">Masuk</a>
                
                <a href="{{ route('register') }}" :class="scrolled ? 'btn-primary' : 'btn-ghost'" class="!py-2 !px-3 sm:!px-4 text-xs sm:text-sm whitespace-nowrap transition-all duration-300">Daftar RT</a>

                <a href="{{ route('register.rw') }}" :class="scrolled ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/30'" class="!py-2 !px-3 sm:!px-4 text-xs sm:text-sm whitespace-nowrap transition-all duration-300 rounded-xl font-bold hidden sm:flex items-center gap-1">Daftar RW <span class="text-[10px] bg-emerald-500 text-white px-1.5 py-0.5 rounded-full ml-1">GRATIS</span></a>
                
                <button type="button" @click="navOpen = !navOpen" class="md:hidden p-2 rounded-lg transition-colors duration-300" :class="scrolled ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 hover:text-white hover:bg-white/10'">
                    <svg x-show="!navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="navOpen" x-transition @click="navOpen = false" class="md:hidden border-t px-4 sm:px-6 py-3 flex flex-col gap-1 text-sm font-medium transition-colors duration-300" :class="scrolled ? 'bg-white border-slate-100 text-slate-600' : 'glass-dark border-white/10 text-slate-300'">
            <a href="#fitur" class="px-2 py-2.5 rounded-lg transition-colors" :class="scrolled ? 'hover:bg-slate-50 hover:text-indigo-600' : 'hover:bg-white/5 hover:text-white'">Fitur</a>
            <a href="#cara-kerja" class="px-2 py-2.5 rounded-lg transition-colors" :class="scrolled ? 'hover:bg-slate-50 hover:text-indigo-600' : 'hover:bg-white/5 hover:text-white'">Cara Kerja</a>
            <a href="#harga" class="px-2 py-2.5 rounded-lg transition-colors" :class="scrolled ? 'hover:bg-slate-50 hover:text-indigo-600' : 'hover:bg-white/5 hover:text-white'">Harga</a>
            <a href="#faq" class="px-2 py-2.5 rounded-lg transition-colors" :class="scrolled ? 'hover:bg-slate-50 hover:text-indigo-600' : 'hover:bg-white/5 hover:text-white'">FAQ</a>
            <a href="{{ route('login') }}" class="px-2 py-2.5 rounded-lg transition-colors" :class="scrolled ? 'hover:bg-slate-50 hover:text-indigo-600' : 'hover:bg-white/5 hover:text-white'">Masuk</a>
            <a href="{{ route('register.rw') }}" class="px-3 py-2.5 mt-2 text-center rounded-lg transition-colors bg-emerald-500/10 text-emerald-500 font-bold border border-emerald-500/20" :class="scrolled ? 'hover:bg-emerald-500/20' : 'hover:bg-emerald-500/30'">Daftar RW (Gratis)</a>
        </div>
    </header>

    <!-- ===================== HERO ===================== -->
    <section id="hero" class="relative min-h-[100dvh] flex items-center overflow-hidden" style="background: radial-gradient(120% 100% at 50% 0%, #1e1b4b 0%, #0f0d24 55%, #0a0915 100%);">
        <canvas id="hero3d-canvas" class="absolute inset-0 pointer-events-none"></canvas>
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(60% 50% at 50% 100%, rgba(10,9,21,0.9), transparent);"></div>
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(55% 65% at 50% 42%, rgba(10,9,21,0.65) 0%, rgba(10,9,21,0.25) 55%, transparent 75%);"></div>

        <!-- Single responsive layout -->
        <div class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-40 pb-16 lg:pt-40 lg:pb-24 flex flex-col lg:flex-row lg:items-center lg:gap-16">

            <!-- LEFT: Text Content -->
            <div class="flex-1 flex flex-col items-center text-center lg:items-start lg:text-left">
                <p class="text-xs sm:text-sm font-semibold text-indigo-300 tracking-widest uppercase mb-4">
                    Platform manajemen RT/RW — dari data warga hingga kas, dalam satu sistem.
                </p>

                <h1 class="text-4xl sm:text-5xl lg:text-[5.5rem] font-black leading-[1.05] tracking-tight text-white mb-5">
                    Data Warga<br>
                    <span style="color: #818cf8;">Otomatis.</span><br>
                    <span style="color: #818cf8;">Manajemen</span><br>
                    <span class="text-white">Transparan.</span>
                </h1>

                <p class="text-sm sm:text-base lg:text-lg text-slate-400 leading-relaxed max-w-lg mb-8">
                    Tinggalkan cara manual. Ekstrak data KK dengan AI, kelola iuran otomatis, dan hadirkan portal warga — tanpa perlu tim IT.
                </p>

                <!-- CTA Buttons -->
                <div class="mb-10 mt-2 w-full max-w-xs sm:max-w-none">
                    <a href="{{ route('register') }}" class="flex sm:inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-4 rounded-2xl font-bold text-sm text-slate-900 bg-indigo-400 hover:bg-indigo-300 transition-all hover:scale-[1.05] shadow-lg shadow-indigo-500/30">
                        UJI COBA 14 HARI →
                    </a>
                </div>

                <!-- Social Proof -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6 w-full">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=11" alt="">
                            <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=12" alt="">
                            <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=13" alt="">
                            <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=14" alt="">
                        </div>
                        <div>
                            <div class="text-[11px] text-amber-400">★★★★★</div>
                            <div class="text-[11px] text-slate-400 font-medium">500+ RT aktif</div>
                        </div>
                    </div>
                    <div class="w-px h-6 bg-white/10 hidden sm:block"></div>
                    <div>
                        <div class="text-base font-black text-white">10K+</div>
                        <div class="text-[11px] text-slate-400">data warga/bulan</div>
                    </div>
                    <div class="w-px h-6 bg-white/10 hidden sm:block"></div>
                    <div>
                        <div class="text-base font-black text-white">4.9/5</div>
                        <div class="text-[11px] text-slate-400">dari pengguna aktif</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Mockup Dashboard UI -->
            <div class="flex-1 mt-12 lg:mt-0 reveal delay-200">
                <div class="relative rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-indigo-500/20" style="background: rgba(15,15,25,0.85); backdrop-filter: blur(20px);">
                    <div class="h-9 border-b border-white/10 flex items-center px-4 gap-2 bg-white/5">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                        <div class="ml-3 flex-1 h-4 bg-white/5 rounded max-w-[200px]"></div>
                        <div class="text-[10px] px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold">AI Aktif</div>
                    </div>
                    <div class="p-5 sm:p-7 flex flex-col gap-4">
                        <div class="text-[11px] font-bold text-slate-300 uppercase tracking-widest">Data Warga Baru</div>
                        <div class="aspect-[2.5] rounded-xl border border-dashed border-indigo-500/30 bg-indigo-500/5 flex flex-col items-center justify-center gap-2 p-4 relative overflow-hidden">
                            <div class="absolute inset-0 bg-indigo-500/10 animate-pulse"></div>
                            <svg class="w-7 h-7 text-indigo-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            <span class="text-[11px] font-semibold text-indigo-300 relative z-10">Menganalisis Kartu Keluarga...</span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="h-9 w-full bg-slate-800/70 rounded-lg border border-white/5 flex items-center px-3">
                                <span class="text-xs font-mono text-emerald-400 blink-cursor">Aditya Novaldy</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2.5">
                                <div class="h-9 bg-slate-800/70 rounded-lg border border-white/5 flex items-center px-3">
                                    <span class="text-xs font-mono text-emerald-400">32731xxxxxxxx</span>
                                </div>
                                <div class="h-9 bg-slate-800/70 rounded-lg border border-white/5 flex items-center px-3">
                                    <span class="text-xs font-mono text-emerald-400">Laki-laki</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full w-3/4 bg-gradient-to-r from-indigo-500 to-emerald-400 rounded-full"></div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono">75%</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <a href="#fitur" class="absolute bottom-6 inset-x-0 flex justify-center text-slate-500 bounce-y z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
    </section>


    <section id="demo" class="max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-16">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6 md:p-10 reveal" x-data="aiDemo()">
            <div class="text-center mb-8">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-widest bg-rose-50 px-3 py-1 rounded-full">Interactive Demo</span>
                <h2 class="text-2xl font-black text-gray-900 mt-4">Coba Kehebatan AI Kami</h2>
                <p class="text-sm text-gray-500 mt-2">Klik tombol di bawah untuk melihat simulasi bagaimana AI membaca KTP dalam hitungan detik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <!-- KTP Area -->
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="w-full max-w-xs aspect-[1.6] rounded-xl border-2 border-dashed transition-all duration-500 flex flex-col items-center justify-center p-4 relative overflow-hidden" :class="state === 'idle' ? 'border-indigo-300 bg-white cursor-pointer hover:bg-indigo-50' : 'border-indigo-500 bg-indigo-50'" @click="startDemo()">
                        
                        <template x-if="state === 'idle'">
                            <div class="text-center">
                                <svg class="w-10 h-10 text-indigo-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span class="text-sm font-bold text-indigo-600 block">Pindai KTP Contoh</span>
                            </div>
                        </template>

                        <template x-if="state === 'scanning'">
                            <div class="text-center relative z-10">
                                <svg class="w-10 h-10 text-indigo-500 mx-auto mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-sm font-bold text-indigo-600 block">Mengekstrak Data...</span>
                            </div>
                        </template>
                        
                        <template x-if="state === 'done'">
                            <div class="text-center">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-bold text-emerald-600 block">Berhasil!</span>
                            </div>
                        </template>

                        <!-- Scan Line Animation -->
                        <div x-show="state === 'scanning'" class="absolute inset-x-0 h-1 bg-indigo-500 shadow-[0_0_15px_3px_rgba(99,102,241,0.5)] z-0" style="animation: scan 1.5s ease-in-out infinite alternate;"></div>
                    </div>
                </div>

                <!-- Result Area -->
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nama Lengkap</label>
                        <div class="h-11 w-full bg-white rounded-lg border border-slate-200 flex items-center px-4 overflow-hidden">
                            <span class="text-sm font-mono font-semibold" :class="state === 'done' ? 'text-slate-800' : 'text-slate-300'" x-text="result.nama"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">NIK</label>
                            <div class="h-11 w-full bg-white rounded-lg border border-slate-200 flex items-center px-4 overflow-hidden">
                                <span class="text-sm font-mono font-semibold" :class="state === 'done' ? 'text-slate-800' : 'text-slate-300'" x-text="result.nik"></span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Pekerjaan</label>
                            <div class="h-11 w-full bg-white rounded-lg border border-slate-200 flex items-center px-4 overflow-hidden">
                                <span class="text-sm font-mono font-semibold" :class="state === 'done' ? 'text-slate-800' : 'text-slate-300'" x-text="result.pekerjaan"></span>
                            </div>
                        </div>
                    </div>
                    <button @click="resetDemo()" x-show="state === 'done'" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline mt-2">Coba Ulang Demo</button>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== FITUR (bento grid) ===================== -->
    <section id="fitur" class="max-w-6xl mx-auto px-4 sm:px-6 py-20 sm:py-24">
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Fitur Unggulan</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Semua yang RT Anda Butuhkan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 reveal">
            <div class="card card-hover p-6 md:col-span-2 md:row-span-2 flex flex-col" style="background: linear-gradient(135deg,#ffffff,#eef2ff);">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 bg-white shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Ekstraksi AI dari Foto KK</h3>
                <p class="text-sm text-gray-600 mt-2 leading-relaxed flex-1">Tidak perlu mengetik manual satu-satu. Cukup foto atau scan Kartu Keluarga, AI Google Gemini langsung membaca dan mengisi seluruh data anggota keluarga secara terstruktur — pengurus tinggal verifikasi dan simpan.</p>
                <span class="inline-flex items-center gap-1.5 mt-4 text-xs font-semibold text-indigo-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Hemat puluhan jam input data manual
                </span>
            </div>

            <div class="card card-hover p-6">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg,#ecfeff,#cffafe);">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Portal Mandiri Warga</h3>
                <p class="text-xs text-gray-500 mt-1.5">Cek NIK, ajukan surat, cek iuran, lapor keluhan — warga bisa akses sendiri, kapan saja.</p>
            </div>

            <div class="card card-hover p-6">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg,#ecfdf5,#d1fae5);">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 8v2m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Transparansi Kas</h3>
                <p class="text-xs text-gray-500 mt-1.5">Iuran dan pengeluaran tercatat rapi, bisa dilihat seluruh warga secara real-time.</p>
            </div>

            <div class="card card-hover p-6">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg,#fef2f2,#fee2e2);">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Surat Otomatis</h3>
                <p class="text-xs text-gray-500 mt-1.5">Warga ajukan surat online, pengurus approve, cetak PDF langsung jadi.</p>
            </div>

            <div class="card card-hover p-6">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg,#f5f3ff,#ede9fe);">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">Import Excel</h3>
                <p class="text-xs text-gray-500 mt-1.5">Sudah punya data lama? Upload Excel, ratusan warga langsung masuk sistem.</p>
            </div>
        </div>
    </section>

    <!-- ===================== CARA KERJA ===================== -->
    <style>
        .perspective-container { perspective: 1200px; }
        .card-3d {
            transform-style: preserve-3d;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .card-3d:hover {
            transform: translateY(-15px) rotateX(8deg) rotateY(-8deg);
        }
        .layer-1 { transform: translateZ(20px); transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        .layer-2 { transform: translateZ(40px); transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        .layer-3 { transform: translateZ(60px); transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        
        .card-3d:hover .layer-1 { transform: translateZ(40px); }
        .card-3d:hover .layer-2 { transform: translateZ(70px); }
        .card-3d:hover .layer-3 { transform: translateZ(100px) scale(1.05) rotateY(-10deg); }
    </style>
    <section id="cara-kerja" class="max-w-6xl mx-auto px-4 sm:px-6 py-24 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-1/4 left-0 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl -z-10 pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl -z-10 pointer-events-none"></div>

        <div class="text-center max-w-2xl mx-auto mb-20 reveal">
            <span class="inline-block py-1.5 px-4 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600 uppercase tracking-widest mb-4 shadow-sm">Cara Kerja</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                Siap Pakai dalam <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-500">3 Langkah Mudah</span>
            </h2>
            <p class="mt-5 text-base sm:text-lg text-gray-500">Otomatisasi seluruh administrasi RT Anda dengan mudah. Tidak perlu pusing belajar software rumit atau bayar server.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 reveal perspective-container">
            @php $steps = [
                ['n' => '01', 'title' => 'Daftar Workspace', 'desc' => 'Isi nama RT dan buat akun pengurus. Trial 14 hari langsung aktif, tanpa kartu kredit.', 'c_text' => 'text-indigo-500', 'h_text' => 'group-hover:text-indigo-50', 'grad' => 'from-indigo-400 to-indigo-600', 'shad' => 'shadow-indigo-500/30', 'h_shad' => 'hover:shadow-indigo-500/20', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                ['n' => '02', 'title' => 'Foto KK / Import', 'desc' => 'Upload foto Kartu Keluarga satu-per-satu (AI yang baca), atau import data lama lewat Excel sekaligus.', 'c_text' => 'text-purple-500', 'h_text' => 'group-hover:text-purple-50', 'grad' => 'from-purple-400 to-purple-600', 'shad' => 'shadow-purple-500/30', 'h_shad' => 'hover:shadow-purple-500/20', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'],
                ['n' => '03', 'title' => 'Portal Aktif', 'desc' => 'Portal mandiri warga otomatis aktif di alamat RT Anda sendiri — siap dipakai untuk cek NIK & ajukan surat.', 'c_text' => 'text-emerald-500', 'h_text' => 'group-hover:text-emerald-50', 'grad' => 'from-emerald-400 to-emerald-600', 'shad' => 'shadow-emerald-500/30', 'h_shad' => 'hover:shadow-emerald-500/20', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ]; @endphp
            
            @foreach($steps as $s)
            <div class="h-full cursor-pointer">
                <!-- 3D Card -->
                <div class="card-3d relative h-full bg-white rounded-[2rem] p-8 sm:p-10 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl {{ $s['h_shad'] }} group overflow-hidden">
                    
                    <!-- Decorative Background Number (Layer 0) -->
                    <div class="absolute -top-4 -right-2 text-8xl font-black text-slate-50 transition-colors duration-500 {{ $s['h_text'] }} select-none pointer-events-none -z-10">
                        {{ $s['n'] }}
                    </div>

                    <!-- 3D Floating Icon (Layer 3) -->
                    <div class="layer-3 relative z-10 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br {{ $s['grad'] }} flex items-center justify-center mb-8 shadow-lg {{ $s['shad'] }}">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $s['icon'] }}"/></svg>
                    </div>

                    <!-- Content (Layer 1 & 2) -->
                    <div class="relative z-10">
                        <div class="layer-2 mb-4">
                            <span class="text-sm font-bold {{ $s['c_text'] }} tracking-wider">LANGKAH {{ $s['n'] }}</span>
                            <h3 class="text-xl sm:text-2xl font-black text-gray-900 mt-1">{{ $s['title'] }}</h3>
                        </div>
                        <p class="layer-1 text-sm sm:text-base text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
                    </div>
                    
                    <!-- Bottom Glow Line -->
                    <div class="absolute inset-x-0 bottom-0 h-1.5 bg-gradient-to-r {{ $s['grad'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ===================== TESTIMONI & STATISTIK ===================== -->
    <section id="testimoni" class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-24">
        <div class="text-center max-w-xl mx-auto mb-14 reveal">
            <span class="text-xs font-bold text-cyan-600 uppercase tracking-widest bg-cyan-50 px-3 py-1 rounded-full">Kata Mereka</span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mt-4">Dipercaya oleh <span class="text-cyan-500">Pengurus RT Modern</span></h2>
            <p class="text-sm text-gray-500 mt-3">Lihat pengalaman nyata dari Ketua RT dan pengurus yang sudah merasakan kemudahan SmartRT dalam keseharian mereka.</p>
        </div>

        <!-- Ulasan Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 reveal">
            <!-- Card 1 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-sm mb-4">
                        ★★★★★
                    </div>
                    <p class="text-sm text-slate-600 italic leading-relaxed">
                        "Dulu rekap uang kas dan iuran warga selalu bikin pusing tiap akhir bulan. Semenjak pakai SmartRT, semua transparan dan warga bisa pantau sendiri dari HP mereka. Sangat direkomendasikan!"
                    </p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm">
                        BS
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Bapak Susanto</h4>
                        <p class="text-xs text-slate-500">Ketua RT 04 - Mawar Regency</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-sm mb-4">
                        ★★★★★
                    </div>
                    <p class="text-sm text-slate-600 italic leading-relaxed">
                        "Fitur ekstrak foto KK dengan AI benar-benar keajaiban! Ribuan data warga masuk ke sistem hanya dalam hitungan menit tanpa capek mengetik. Pekerjaan sekretaris jadi sangat ringan."
                    </p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold text-sm">
                        AW
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Ibu Ayu Wulandari</h4>
                        <p class="text-xs text-slate-500">Sekretaris RW 08 - Griya Asri</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-sm mb-4">
                        ★★★★★
                    </div>
                    <p class="text-sm text-slate-600 italic leading-relaxed">
                        "Warga sekarang tidak perlu ketok rumah malam-malam cuma buat minta surat pengantar. Tinggal klik dari Portal Warga, otomatis jadi PDF. Sistem yang sangat modern!"
                    </p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold text-sm">
                        HD
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Bapak Hendra</h4>
                        <p class="text-xs text-slate-500">Ketua RT 01 - Bukit Permai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 reveal delay-100">
            <!-- Stat 1 -->
            <div class="bg-white rounded-3xl p-6 text-center border border-slate-100 shadow-lg shadow-slate-200/30">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">500<span class="text-lg text-gray-400 font-bold">+</span></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">RT Terdaftar</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white rounded-3xl p-6 text-center border border-slate-100 shadow-lg shadow-slate-200/30">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">50<span class="text-lg text-gray-400 font-bold">K+</span></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Data Warga</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white rounded-3xl p-6 text-center border border-slate-100 shadow-lg shadow-slate-200/30">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">99<span class="text-lg text-gray-400 font-bold">.9%</span></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Uptime Server</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white rounded-3xl p-6 text-center border border-slate-100 shadow-lg shadow-slate-200/30">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">5<span class="text-lg text-gray-400 font-bold">mnt</span></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Setup Akun</p>
            </div>
        </div>
    </section>

    <!-- ===================== HARGA ===================== -->
    {{-- Inject plan data as a global JS variable (safe from HTML encoding issues) --}}
    <script>
        window.__pricingPlans = {!! json_encode($plans->map(fn($p) => ['slug' => $p->slug, 'name' => $p->name, 'max_kk' => $p->max_kk])->values()) !!};
    </script>
    <section id="harga" class="max-w-6xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24" x-data="pricingSlider()">
        <div class="text-center max-w-xl mx-auto mb-8 reveal">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Harga</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Paket Sesuai Kebutuhan RT Anda</h2>
            <p class="text-sm text-gray-500 mt-2">Semua paket sudah termasuk portal warga &amp; transparansi kas. Trial 14 hari di semua tier.</p>
        </div>

        <!-- Toggle Bulanan / Tahunan -->
        <div class="flex justify-center items-center gap-4 mb-10 reveal">
            <span style="font-size:14px; font-weight:700; cursor:pointer; transition:color 0.2s;"
                  :style="!isYearly ? 'color:#0f172a;' : 'color:#94a3b8;'"
                  @click="isYearly = false">
                Bulanan
            </span>

            <button type="button" @click="isYearly = !isYearly"
                    style="position:relative; display:inline-flex; align-items:center; width:48px; height:26px; border-radius:99px; border:none; cursor:pointer; transition:background-color 0.3s ease; outline:none; padding:3px;"
                    :style="isYearly ? 'background-color:#10b981;' : 'background-color:#cbd5e1;'">
                <span style="display:inline-block; width:20px; height:20px; background-color:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.15); transition:transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);"
                      :style="isYearly ? 'transform:translateX(22px);' : 'transform:translateX(0);'"></span>
            </button>

            <div style="display:flex; align-items:center; gap:8px; cursor:pointer;" @click="isYearly = true">
                <span style="font-size:14px; font-weight:700; transition:color 0.2s;"
                      :style="isYearly ? 'color:#0f172a;' : 'color:#94a3b8;'">
                    Tahunan
                </span>
            </div>
        </div>

        <!-- Slider Kalkulator -->
        <div class="max-w-md mx-auto mb-12 reveal bg-white p-6 rounded-2xl border border-slate-200 shadow-lg shadow-slate-100">
            <label class="block text-sm font-bold text-gray-700 text-center mb-4">
                Estimasi Jumlah Warga (KK) di RT Anda:<br>
                <span class="text-indigo-600 text-3xl font-black block mt-2"><span x-text="kkCount"></span> <span class="text-lg">KK</span></span>
            </label>
            <input id="kk-slider" type="range" min="10" max="500" step="5" x-model.number="kkCount" @input="updateRecommendation()" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600 outline-none">
            <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-3 uppercase tracking-wider">
                <span>10 KK</span>
                <span id="kk-slider-max-label">500+ KK</span>
            </div>
            <p class="text-xs text-center text-slate-500 mt-4 font-medium">Paket yang direkomendasikan: <strong class="text-indigo-600" x-text="recommendedName"></strong></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 reveal">
            @foreach($plans as $plan)
            <div class="pricing-card rounded-2xl border p-6 relative bg-white"
                 :data-recommended="recommendedPlan === '{{ $plan->slug }}'"
                 :style="recommendedPlan === '{{ $plan->slug }}'
                    ? 'border-color:#6366f1; box-shadow:0 0 0 4px rgba(99,102,241,0.18),0 8px 30px rgba(99,102,241,0.15); transform:scale(1.04); z-index:10; opacity:1;'
                    : 'border-color:#e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,0.06); transform:scale(0.97); opacity:0.55;'">
                @if($plan->is_popular)
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-[11px] font-bold text-white whitespace-nowrap" style="background: linear-gradient(135deg,#6366f1,#a855f7);">PALING POPULER</span>
                @endif
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">{{ $plan->name }}</p>
                {{-- HARGA --}}
                {{-- Bulanan --}}
                <div x-show="!isYearly">
                    <p class="text-3xl font-black text-gray-900 mt-2">Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">/bulan</p>
                </div>

                {{-- Tahunan (tampilkan harga coret + harga hemat) --}}
                <div x-show="isYearly" style="display:none;">
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <p class="text-3xl font-black text-gray-900">Rp {{ number_format($plan->price_yearly, 0, ',', '.') }}</p>
                        <span style="font-size:10px; font-weight:800; padding:2px 8px; border-radius:99px; background:#dcfce7; color:#16a34a; white-space:nowrap;">HEMAT Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <p class="text-xs text-gray-400">/tahun</p>
                        <span class="text-xs line-through text-gray-300">Rp {{ number_format($plan->price_monthly * 12, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-emerald-600 font-bold mt-1">≈ Rp {{ number_format(intdiv($plan->price_yearly, 12), 0, ',', '.') }}/bln</p>
                </div>

                <ul class="mt-5 space-y-2.5">
                    {{-- Batas KK --}}
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $plan->isUnlimitedKk() ? 'Kartu Keluarga tanpa batas' : 'Hingga ' . number_format($plan->max_kk) . ' Kartu Keluarga' }}
                    </li>
                    
                    {{-- Batas AI --}}
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $plan->isUnlimitedAi() ? 'Scan Ekstraksi AI tanpa batas' : number_format($plan->max_ai_extractions_per_month) . ' Scan Ekstraksi AI / bln' }}
                    </li>
                    
                    {{-- Batas Pengurus --}}
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $plan->isUnlimitedUsers() ? 'Akun Pengurus tanpa batas' : 'Hingga ' . number_format($plan->max_users) . ' Akun Pengurus' }}
                    </li>

                    {{-- Akses Penuh --}}
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Akses Penuh ke Seluruh Fitur Modul
                    </li>
                </ul>

                <a href="{{ route('register') }}" class="block w-full mt-6 py-2.5 rounded-xl text-sm font-semibold text-center transition-all {{ $plan->is_popular ? 'text-white' : 'text-indigo-600 bg-indigo-50 hover:bg-indigo-100' }}" @if($plan->is_popular) style="background: linear-gradient(135deg,#6366f1,#a855f7);" @endif >
                    Coba Gratis 14 Hari
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ===================== FAQ ===================== -->
    <section id="faq" class="max-w-2xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24">
        <div class="text-center mb-10 reveal">
            <span class="text-xs font-bold text-purple-600 uppercase tracking-widest">FAQ</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Pertanyaan Umum</h2>
        </div>

        <div class="space-y-3 reveal">
            @php $faqs = [
                ['q' => 'Apakah data warga aman?', 'a' => 'Foto KK disimpan di penyimpanan privat, hanya bisa diakses pengurus yang login. Data antar-RT juga terisolasi penuh — RT lain tidak bisa melihat data RT Anda sama sekali.'],
                ['q' => 'Bagaimana foto KK diproses AI?', 'a' => 'Foto dikirim ke Google Gemini API untuk diekstrak otomatis menjadi data terstruktur. Hasilnya tetap perlu diverifikasi pengurus sebelum disimpan, jadi tetap akurat.'],
                ['q' => 'Apakah warga perlu install aplikasi?', 'a' => 'Tidak. Portal warga berbasis web, bisa diakses langsung dari browser HP tanpa instalasi apa pun.'],
                ['q' => 'Bisa pindah dari sistem pencatatan manual?', 'a' => 'Bisa. Gunakan fitur Import Excel untuk memasukkan data warga lama sekaligus, atau foto satu-per-satu dengan AI untuk data baru.'],
                ['q' => 'Bagaimana kalau ingin berhenti berlangganan?', 'a' => 'Anda bisa berhenti kapan saja tanpa kontrak jangka panjang. Data Anda tetap bisa diekspor ke Excel/PDF sebelum berhenti.'],
            ]; @endphp
            @foreach($faqs as $f)
            <details class="card p-5 group">
                <summary class="flex items-center justify-between gap-3">
                    <span class="text-sm font-semibold text-gray-900">{{ $f['q'] }}</span>
                    <svg class="faq-chevron w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <p class="text-sm text-gray-500 mt-3 leading-relaxed">{{ $f['a'] }}</p>
            </details>
            @endforeach
        </div>
    </section>

    <!-- ===================== CTA BAND ===================== -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
        <div class="rounded-3xl p-8 sm:p-12 text-center relative overflow-hidden reveal" style="background: linear-gradient(135deg, #1e1b4b, #4338ca 60%, #6d28d9);">
            <h2 class="text-2xl sm:text-3xl font-black text-white">Siap digitalisasi RT Anda?</h2>
            <p class="text-indigo-200 text-sm mt-2 max-w-md mx-auto">Mulai trial 14 hari sekarang, tanpa kartu kredit.</p>
            <a href="{{ route('register') }}" class="btn-primary mt-6 justify-center inline-flex">Daftar Gratis Sekarang</a>
        </div>
    </section>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-12 font-sans border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 md:gap-8 gap-y-10 lg:gap-12">
                <!-- COL 1 -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 rounded bg-white flex items-center justify-center p-1">
                            <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-contain">
                        </div>
                        <span class="text-sm font-bold text-white tracking-wide">{{ config('app.name', 'SmartRT Vision') }}</span>
                    </div>
                    <p class="text-[13px] text-slate-500 leading-relaxed pr-4">
                        Platform manajemen warga modern yang dirancang untuk mempercepat pelayanan, memastikan ketersediaan data, dan menyederhanakan pelaporan operasional RT/RW.
                    </p>
                </div>

                <!-- COL 2 -->
                <div>
                    <h4 class="text-[11px] font-bold text-white uppercase tracking-wider mb-5">Menu Utama</h4>
                    <ul class="space-y-3.5 text-[13px]">
                        <li><a href="#fitur" class="text-slate-400 hover:text-white transition-colors">Fitur Sistem</a></li>
                        <li><a href="#cara-kerja" class="text-slate-400 hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="#harga" class="text-slate-400 hover:text-white transition-colors">Paket & Harga</a></li>
                        <li><a href="#faq" class="text-slate-400 hover:text-white transition-colors">Tanya Jawab (FAQ)</a></li>
                    </ul>
                </div>

                <!-- COL 3 -->
                <div>
                    <h4 class="text-[11px] font-bold text-white uppercase tracking-wider mb-5">Pengurus RT</h4>
                    <ul class="space-y-3.5 text-[13px]">
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition-colors">Masuk Dashboard</a></li>
                        <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-white transition-colors">Daftar / Uji Coba Gratis</a></li>
                        <li><a href="https://wa.me/6285156412702" target="_blank" class="text-slate-400 hover:text-white transition-colors">Hubungi Bantuan (WA)</a></li>
                    </ul>
                </div>
            </div>

            <!-- FOOTER BOTTOM -->
            <div class="border-t border-slate-800 mt-16 pt-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <p class="text-[12px] text-slate-400 mb-1.5">
                        &copy; {{ date('Y') }} <span class="text-slate-300 font-semibold">PT. Sekawan Putra Pratama</span>. Seluruh Hak Cipta Dilindungi.
                    </p>
                    <p class="text-[11px] text-slate-500 max-w-2xl">
                        SmartRT Vision adalah platform sistem informasi terintegrasi yang dikembangkan oleh PT. Sekawan Putra Pratama untuk mendukung digitalisasi warga.
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="https://www.instagram.com/sekawanputrapratama" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://sekawanputrapratama.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        function aiDemo() {
            return {
                state: 'idle', // idle, scanning, done
                result: {
                    nama: '',
                    nik: '',
                    pekerjaan: ''
                },
                startDemo() {
                    if (this.state !== 'idle') return;
                    
                    this.state = 'scanning';
                    this.result = { nama: '', nik: '', pekerjaan: '' };
                    
                    // Simulate API delay
                    setTimeout(() => {
                        this.state = 'done';
                        this.typeText('nama', 'ADITYA NOVALDY', 50);
                        this.typeText('nik', '3273151234567890', 50);
                        this.typeText('pekerjaan', 'SOFTWARE ENGINEER', 50);
                    }, 2000);
                },
                typeText(key, text, speed) {
                    let i = 0;
                    const interval = setInterval(() => {
                        this.result[key] += text.charAt(i);
                        i++;
                        if (i >= text.length) clearInterval(interval);
                    }, speed);
                },
                resetDemo() {
                    this.state = 'idle';
                    this.result = { nama: '', nik: '', pekerjaan: '' };
                }
            }
        }

        function pricingSlider() {
            // Read plan data from global variable (injected from Blade/PHP above)
            const plansData = window.__pricingPlans || [];

            // Sort plans ascending by max_kk (null/0 = unlimited, always last = highest tier)
            const sortedPlans = [...plansData].sort((a, b) => {
                if (!a.max_kk && !b.max_kk) return 0;
                if (!a.max_kk) return 1;   // unlimited → last
                if (!b.max_kk) return -1;
                return a.max_kk - b.max_kk;
            });

            // Compute slider max dynamically:
            // highest limited plan max_kk + 30% buffer (so user can drag past it into unlimited tier)
            const limitedPlans = sortedPlans.filter(p => p.max_kk);
            const highestLimit = limitedPlans.length > 0
                ? Math.max(...limitedPlans.map(p => p.max_kk))
                : 500;
            const sliderMax = Math.round(highestLimit * 1.3 / 10) * 10; // round to nearest 10

            return {
                isYearly: false,
                kkCount: 150,
                recommendedPlan: sortedPlans[0]?.slug ?? '',
                recommendedName: sortedPlans[0]?.name ?? '',

                getRecommendation(count) {
                    // Find cheapest LIMITED plan whose max_kk covers the count
                    const limitedMatch = sortedPlans.find(p => p.max_kk && p.max_kk >= count);
                    if (limitedMatch) return limitedMatch;
                    // No limited plan covers it → recommend unlimited (highest tier)
                    return sortedPlans[sortedPlans.length - 1];
                },

                init() {
                    // Set slider max dynamically
                    const slider = document.getElementById('kk-slider');
                    const label  = document.getElementById('kk-slider-max-label');
                    if (slider) slider.max = sliderMax;
                    if (label)  label.textContent = sliderMax + '+ KK';

                    const rec = this.getRecommendation(this.kkCount);
                    this.recommendedPlan = rec.slug;
                    this.recommendedName = rec.name;
                    this.$watch('kkCount', (val) => {
                        const r = this.getRecommendation(Number(val));
                        this.recommendedPlan = r.slug;
                        this.recommendedName = r.name;
                    });
                },

                updateRecommendation() {
                    const r = this.getRecommendation(Number(this.kkCount));
                    this.recommendedPlan = r.slug;
                    this.recommendedName = r.name;
                },
            };
        }
    </script>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6285156412702?text=Halo%20Admin%20SmartRT,%20saya%20ingin%20bertanya%20seputar%20harga%20paket." target="_blank" rel="noopener noreferrer" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-4 rounded-full shadow-2xl shadow-green-500/40 hover:scale-110 hover:shadow-green-500/60 transition-all flex items-center justify-center group" aria-label="Tanya Harga ke Sales">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        <span class="absolute right-full mr-4 bg-white text-slate-800 text-xs font-bold py-2 px-3 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Hubungi Sales</span>
    </a>
</body>
</html>
