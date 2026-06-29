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
    <header :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-200' : 'bg-transparent border-b border-transparent'" class="fixed top-0 inset-x-0 z-40 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('marketing.home') }}" class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 bg-indigo-600 text-white">
                    <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-sm sm:text-base truncate text-slate-900">{{ config('app.name', 'SmartRT Vision') }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-indigo-600 transition-colors">Fitur</a>
                <a href="#cara-kerja" class="hover:text-indigo-600 transition-colors">Cara Kerja</a>
                <a href="#harga" class="hover:text-indigo-600 transition-colors">Harga</a>
                <a href="#faq" class="hover:text-indigo-600 transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-bold text-slate-600 hover:text-indigo-600 px-3 py-2 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary !py-2 !px-4 text-xs sm:text-sm whitespace-nowrap">Daftar Gratis</a>
                
                <button type="button" @click="navOpen = !navOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg x-show="!navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="navOpen" x-transition @click="navOpen = false" class="md:hidden border-t px-4 sm:px-6 py-3 flex flex-col gap-1 text-sm font-semibold bg-white border-slate-100 text-slate-600">
            <a href="#fitur" class="px-2 py-2.5 rounded-lg hover:bg-slate-50 hover:text-indigo-600">Fitur</a>
            <a href="#cara-kerja" class="px-2 py-2.5 rounded-lg hover:bg-slate-50 hover:text-indigo-600">Cara Kerja</a>
            <a href="#harga" class="px-2 py-2.5 rounded-lg hover:bg-slate-50 hover:text-indigo-600">Harga</a>
            <a href="#faq" class="px-2 py-2.5 rounded-lg hover:bg-slate-50 hover:text-indigo-600">FAQ</a>
            <a href="{{ route('login') }}" class="px-2 py-2.5 rounded-lg hover:bg-slate-50 hover:text-indigo-600">Masuk</a>
        </div>
    </header>

    <!-- ===================== HERO (ENTERPRISE) ===================== -->
    <section id="hero" class="relative min-h-[100dvh] flex items-center justify-center overflow-hidden bg-[#f8f9fc] pt-16">
        <!-- Subtle Grid Pattern -->
        <div class="absolute inset-0" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px; opacity: 0.3;"></div>
        <!-- Gradient Fades for Grid -->
        <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-[#f8f9fc] to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-[#f8f9fc] to-transparent"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-5 sm:px-6 text-center py-20">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold text-indigo-700 bg-indigo-100/80 border border-indigo-200 mb-6 sm:mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                <span>Platform Enterprise-Grade</span>
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black leading-tight sm:leading-[1.1] tracking-tight text-slate-900">
                Data Warga Otomatis.<br>
                <span class="text-indigo-600">Manajemen Transparan.</span>
            </h1>
            <p class="mt-5 sm:mt-6 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
                Tinggalkan cara manual. Ekstrak data Kartu Keluarga dengan AI, kelola iuran otomatis, dan hadirkan portal mandiri untuk warga dalam satu dashboard yang aman dan andal.
            </p>
            <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}" class="btn-primary justify-center text-sm sm:text-base px-8 py-3.5 shadow-xl shadow-indigo-500/20">
                    Mulai Gratis 14 Hari
                </a>
                <a href="#cara-kerja" class="text-sm font-bold text-slate-700 hover:text-indigo-600 flex items-center gap-2 transition-colors">
                    Pelajari Fiturnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            
            <!-- Social Proof -->
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 reveal delay-100">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm" src="https://i.pravatar.cc/100?img=1" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm" src="https://i.pravatar.cc/100?img=2" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm" src="https://i.pravatar.cc/100?img=3" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm" src="https://i.pravatar.cc/100?img=4" alt="User">
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700 shadow-sm z-10">500+</div>
                </div>
                <div class="text-sm font-semibold text-slate-600 flex flex-col sm:items-start text-center sm:text-left">
                    <div class="flex text-amber-400 text-lg">★★★★★</div>
                    <span>Dipercaya 500+ RT/RW di Indonesia</span>
                </div>
            </div>

            <!-- Mockup Dashboard UI -->
            <div class="mt-16 sm:mt-20 relative max-w-4xl mx-auto reveal delay-200">
                <div class="relative bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden transform-gpu hover:-translate-y-1 transition-transform duration-500">
                    <!-- Browser Window Header -->
                    <div class="h-10 border-b border-slate-100 flex items-center px-5 gap-2 bg-slate-50/50">
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                    </div>
                    <!-- Mockup Body -->
                    <div class="p-6 sm:p-10 flex flex-col md:flex-row gap-8 items-center bg-white text-left">
                        <!-- Simulated KTP Upload -->
                        <div class="w-full md:w-1/3 aspect-[1.5] rounded-xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 flex flex-col items-center justify-center gap-3 p-4 relative">
                            <div class="absolute inset-0 bg-indigo-100/50 animate-pulse rounded-xl"></div>
                            <svg class="w-10 h-10 text-indigo-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="text-sm font-bold text-indigo-700 relative z-10 text-center">Menganalisis Dokumen...</span>
                        </div>
                        <!-- Simulated Form Filling -->
                        <div class="w-full md:w-2/3 space-y-4">
                            <div class="h-4 w-32 bg-slate-200 rounded animate-pulse"></div>
                            <div class="h-12 w-full bg-slate-50 rounded-lg border border-slate-200 flex items-center px-4 shadow-sm">
                                <span class="text-sm font-mono text-slate-800 border-r border-slate-300 pr-2 mr-2 blink-cursor">Aditya Novaldy</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="h-12 w-full bg-slate-50 rounded-lg border border-slate-200 flex items-center px-4 shadow-sm">
                                    <span class="text-sm font-mono text-slate-800">32731xxxxxxxx</span>
                                </div>
                                <div class="h-12 w-full bg-slate-50 rounded-lg border border-slate-200 flex items-center px-4 shadow-sm">
                                    <span class="text-sm font-mono text-slate-800">Laki-laki</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== LIVE AI DEMO ===================== -->
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
    <section id="cara-kerja" class="max-w-5xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24">
        <div class="text-center max-w-xl mx-auto mb-14 reveal">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Cara Kerja</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Siap Pakai dalam 3 Langkah</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
            @php $steps = [
                ['n' => '01', 'title' => 'Daftar Workspace RT', 'desc' => 'Isi nama RT dan buat akun pengurus. Trial 14 hari langsung aktif, tanpa kartu kredit.', 'color' => '#6366f1'],
                ['n' => '02', 'title' => 'Foto KK atau Import Excel', 'desc' => 'Upload foto Kartu Keluarga satu-per-satu (AI yang baca), atau import data lama lewat Excel sekaligus.', 'color' => '#0891b2'],
                ['n' => '03', 'title' => 'Warga Langsung Bisa Akses', 'desc' => 'Portal mandiri warga otomatis aktif di alamat RT Anda sendiri — siap dipakai untuk cek NIK, ajukan surat, dan lainnya.', 'color' => '#059669'],
            ]; @endphp
            @foreach($steps as $s)
            <div class="relative">
                <span class="text-5xl font-black" style="color: {{ $s['color'] }}; opacity: 0.15;">{{ $s['n'] }}</span>
                <h3 class="text-base font-bold text-gray-900 mt-1">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed">{{ $s['desc'] }}</p>
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
    <footer class="bg-[#0a0915] text-slate-400 pt-16 pb-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden bg-[#0a0915] border border-white/20">
                            <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                        </div>
                        <span class="text-sm font-bold text-white">{{ config('app.name', 'SmartRT Vision') }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-4 leading-relaxed">
                        Platform SaaS untuk pengurus RT/RW — ekstraksi data KK otomatis dengan AI, portal mandiri warga, dan transparansi kas.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Produk</p>
                    <ul class="space-y-3 text-xs">
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="#harga" class="hover:text-white transition-colors">Harga</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Akun</p>
                    <ul class="space-y-3 text-xs">
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Gratis</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk Pengurus</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Tentang</p>
                    <ul class="space-y-3 text-xs">
                        <li class="text-slate-500">Dikembangkan oleh PT. Sekawan Putra Pratama</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} <strong class="text-slate-200 font-semibold">PT. Sekawan Putra Pratama</strong>. Seluruh Hak Cipta Dilindungi.
                </p>
                <div class="flex items-center gap-4 text-xs text-slate-500">
                    <a href="{{ url('/kebijakan-privasi') }}" class="hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
                    <span>·</span>
                    <a href="{{ url('/syarat-dan-ketentuan') }}" class="hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
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
