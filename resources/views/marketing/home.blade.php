<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SmartRT Vision — platform SaaS untuk pengurus RT/RW: ekstraksi data KK otomatis dengan AI, portal mandiri warga, dan transparansi kas.">
    <title>{{ config('app.name', 'SmartRT Vision') }} — Kelola RT Lebih Mudah dengan AI</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

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
                
                <a href="{{ route('register') }}" :class="scrolled ? 'btn-primary' : 'btn-ghost'" class="!py-2 !px-3 sm:!px-4 text-xs sm:text-sm whitespace-nowrap transition-all duration-300">Daftar Gratis</a>
                
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
        </div>
    </header>

    <!-- ===================== HERO 3D ===================== -->
    <section id="hero" class="relative min-h-[100dvh] flex items-center overflow-hidden" style="background: radial-gradient(120% 100% at 50% 0%, #1e1b4b 0%, #0f0d24 55%, #0a0915 100%);">
        <canvas id="hero3d-canvas" class="absolute inset-0 pointer-events-none"></canvas>
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(60% 50% at 50% 100%, rgba(10,9,21,0.9), transparent);"></div>
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(55% 65% at 50% 42%, rgba(10,9,21,0.65) 0%, rgba(10,9,21,0.25) 55%, transparent 75%);"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-5 sm:px-6 text-center py-20 sm:py-24">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-[11px] sm:text-xs font-semibold text-indigo-200 border border-indigo-400/30 bg-indigo-500/10 mb-5 sm:mb-6 whitespace-nowrap">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                <span>Didukung Google Gemini AI</span>
            </span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-[1.15] sm:leading-[1.1] tracking-tight text-white">
                Foto KK-nya,
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-cyan-300 bg-clip-text text-transparent">Biar AI yang Ketik</span>
            </h1>
            <p class="mt-4 sm:mt-5 text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto">
                Platform digital untuk pengurus RT/RW: data warga terisi otomatis dari foto Kartu Keluarga, lengkap dengan portal mandiri warga dan transparansi kas — tanpa perlu tim IT.
            </p>
            <div class="mt-7 sm:mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}" class="btn-primary justify-center">
                    Mulai Gratis 14 Hari
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="#cara-kerja" class="btn-ghost justify-center">Lihat Cara Kerjanya</a>
            </div>
            
            <!-- Social Proof -->
            <div class="mt-8 flex flex-col items-center justify-center gap-3 reveal delay-100">
                <div class="flex -space-x-2">
                    <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=1" alt="User">
                    <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=2" alt="User">
                    <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=3" alt="User">
                    <img class="w-8 h-8 rounded-full border-2 border-[#0f0d24] object-cover" src="https://i.pravatar.cc/100?img=4" alt="User">
                    <div class="w-8 h-8 rounded-full border-2 border-[#0f0d24] bg-indigo-600 flex items-center justify-center text-[10px] font-bold text-white shadow-inner z-10">+500</div>
                </div>
                <div class="text-xs font-medium text-slate-400">
                    <span class="text-amber-400">★★★★★</span> Telah dipercaya oleh 500+ Pengurus RT & RW
                </div>
            </div>

            <!-- Mockup Dashboard UI -->
            <div class="mt-14 relative max-w-4xl mx-auto reveal delay-200 perspective-1000">
                <div class="relative rounded-t-2xl sm:rounded-t-3xl overflow-hidden border border-white/10 shadow-2xl shadow-indigo-500/20 transform-gpu rotate-x-12 scale-95 origin-bottom translate-y-4 hover:rotate-x-0 hover:scale-100 hover:translate-y-0 transition-all duration-700 ease-out" style="background: rgba(15,15,25,0.8); backdrop-filter: blur(20px);">
                    <!-- MacOS Window Header -->
                    <div class="h-8 sm:h-10 border-b border-white/10 flex items-center px-4 gap-2 bg-white/5">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                    </div>
                    <!-- Mockup Body -->
                    <div class="p-4 sm:p-8 flex flex-col md:flex-row gap-6 items-center">
                        <!-- Simulated KTP Upload -->
                        <div class="w-full md:w-1/3 aspect-[1.6] rounded-xl border border-dashed border-indigo-500/30 bg-indigo-500/5 flex flex-col items-center justify-center gap-3 p-4 relative overflow-hidden">
                            <div class="absolute inset-0 bg-indigo-500/10 animate-pulse"></div>
                            <svg class="w-8 h-8 text-indigo-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="text-xs font-semibold text-indigo-300 relative z-10">Menganalisis KTP...</span>
                        </div>
                        <!-- Simulated Form Filling -->
                        <div class="w-full md:w-2/3 space-y-3">
                            <div class="h-4 w-24 bg-slate-700/50 rounded animate-pulse"></div>
                            <div class="h-10 w-full bg-slate-800/50 rounded-lg border border-white/5 flex items-center px-4">
                                <span class="text-sm font-mono text-emerald-400 border-r border-emerald-400/30 pr-2 mr-2 blink-cursor">Aditya Novaldy</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="h-10 w-full bg-slate-800/50 rounded-lg border border-white/5 flex items-center px-4">
                                    <span class="text-sm font-mono text-emerald-400">32731xxxxxxxx</span>
                                </div>
                                <div class="h-10 w-full bg-slate-800/50 rounded-lg border border-white/5 flex items-center px-4">
                                    <span class="text-sm font-mono text-emerald-400">Laki-laki</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#fitur" class="absolute bottom-6 sm:bottom-8 inset-x-0 flex justify-center text-slate-500 bounce-y z-10">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
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
    <section id="harga" class="max-w-6xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24" x-data="pricingSlider()">
        <div class="text-center max-w-xl mx-auto mb-8 reveal">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Harga</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Paket Sesuai Kebutuhan RT Anda</h2>
            <p class="text-sm text-gray-500 mt-2">Semua paket sudah termasuk portal warga &amp; transparansi kas. Trial 14 hari di semua tier.</p>
        </div>

        <!-- Toggle Bulanan / Tahunan -->
        <div class="flex justify-center mb-8 reveal">
            <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex relative shadow-inner border border-slate-200/60">
                <button type="button" @click="isYearly = false" class="relative z-10 px-6 py-2.5 text-sm font-bold rounded-xl transition-colors duration-300" :class="!isYearly ? 'text-indigo-700' : 'text-slate-500 hover:text-slate-800'">
                    Bulanan
                </button>
                <button type="button" @click="isYearly = true" class="relative z-10 px-6 py-2.5 text-sm font-bold rounded-xl transition-colors duration-300 flex items-center gap-2" :class="isYearly ? 'text-indigo-700' : 'text-slate-500 hover:text-slate-800'">
                    Tahunan
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 text-emerald-700">HEMAT 2 BULAN</span>
                </button>
                <div class="absolute top-1.5 bottom-1.5 left-1.5 w-[calc(50%-6px)] bg-white rounded-xl shadow-sm border border-slate-200 transition-transform duration-300 ease-out z-0" :class="isYearly ? 'translate-x-full w-[calc(50%+45px)]' : 'translate-x-0'"></div>
            </div>
        </div>

        <!-- Slider Kalkulator -->
        <div class="max-w-md mx-auto mb-12 reveal bg-white p-6 rounded-2xl border border-slate-200 shadow-lg shadow-slate-100">
            <label class="block text-sm font-bold text-gray-700 text-center mb-4">
                Estimasi Jumlah Warga (KK) di RT Anda:<br>
                <span class="text-indigo-600 text-3xl font-black block mt-2"><span x-text="kkCount"></span> <span class="text-lg">KK</span></span>
            </label>
            <input type="range" min="10" max="300" step="5" x-model.number="kkCount" @input="updateRecommendation()" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600 outline-none">
            <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-3 uppercase tracking-wider">
                <span>10 KK</span>
                <span>300+ KK</span>
            </div>
            <p class="text-xs text-center text-slate-500 mt-4 font-medium">Paket yang direkomendasikan: <strong class="text-indigo-600" x-text="recommendedName"></strong></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 reveal">
            @foreach($plans as $plan)
            <div class="rounded-2xl border p-6 relative transition-all duration-300 bg-white" 
                 :class="recommendedPlan === '{{ $plan->slug }}' ? 'border-indigo-500 ring-4 ring-indigo-500/20 shadow-2xl shadow-indigo-200/50 scale-[1.03] z-10' : 'border-gray-100 shadow-sm opacity-60 hover:opacity-100 scale-95 hover:scale-100'">
                @if($plan->is_popular)
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-[11px] font-bold text-white whitespace-nowrap" style="background: linear-gradient(135deg,#6366f1,#a855f7);">PALING POPULER</span>
                @endif
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">{{ $plan->name }}</p>
                <p class="text-3xl font-black text-gray-900 mt-2" x-show="!isYearly">Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}</p>
                <p class="text-3xl font-black text-gray-900 mt-2" x-show="isYearly" style="display: none;">Rp {{ number_format($plan->price_yearly, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400" x-show="!isYearly">/bulan</p>
                <p class="text-xs text-gray-400" x-show="isYearly" style="display: none;">/tahun</p>

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

                <a href="{{ route('register') }}" class="block w-full mt-6 py-2.5 rounded-xl text-sm font-semibold text-center transition-all {{ $plan->is_popular ? 'text-white' : 'text-indigo-600 bg-indigo-50 hover:bg-indigo-100' }}" @if($plan->is_popular) style="background: linear-gradient(135deg,#6366f1,#a855f7);" @endif>
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
            return {
                isYearly: false,
                kkCount: 150,
                recommendedPlan: 'growth',
                recommendedName: 'Growth',
                getRecommendation(count) {
                    if (count <= 50)  return { plan: 'starter', name: 'Starter' };
                    if (count <= 200) return { plan: 'growth',  name: 'Growth'  };
                    return             { plan: 'premium', name: 'Premium' };
                },
                init() {
                    const rec = this.getRecommendation(this.kkCount);
                    this.recommendedPlan = rec.plan;
                    this.recommendedName = rec.name;
                    this.$watch('kkCount', (val) => {
                        const r = this.getRecommendation(Number(val));
                        this.recommendedPlan = r.plan;
                        this.recommendedName = r.name;
                    });
                },
                updateRecommendation() {
                    const r = this.getRecommendation(Number(this.kkCount));
                    this.recommendedPlan = r.plan;
                    this.recommendedName = r.name;
                },
            }
        }
    </script>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6285156412702?text=Halo%20Admin%20SmartRT,%20saya%20ingin%20bertanya%20seputar%20harga%20paket." target="_blank" rel="noopener noreferrer" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-4 rounded-full shadow-2xl shadow-green-500/40 hover:scale-110 hover:shadow-green-500/60 transition-all flex items-center justify-center group" aria-label="Tanya Harga ke Sales">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        <span class="absolute right-full mr-4 bg-white text-slate-800 text-xs font-bold py-2 px-3 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Hubungi Sales</span>
    </a>
</body>
</html>
