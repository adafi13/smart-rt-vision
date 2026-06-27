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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        details > summary { list-style: none; cursor: pointer; }
        details > summary::-webkit-details-marker { display: none; }
        details[open] .faq-chevron { transform: rotate(180deg); }
    </style>
</head>
<body x-data="{ navOpen: false }">

    <!-- ===================== NAVBAR ===================== -->
    <header class="fixed top-0 inset-x-0 z-40 glass-dark border-b border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('marketing.home') }}" class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center overflow-hidden bg-white/10 backdrop-blur border border-white/20 flex-shrink-0">
                    <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-white text-sm sm:text-base truncate">{{ config('app.name', 'SmartRT Vision') }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-300">
                <a href="#fitur" class="hover:text-white transition-colors">Fitur</a>
                <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
                <a href="#harga" class="hover:text-white transition-colors">Harga</a>
                <a href="#faq" class="hover:text-white transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="btn-ghost !py-2 !px-3 sm:!px-4 text-xs sm:text-sm whitespace-nowrap">Daftar Gratis</a>
                <button type="button" @click="navOpen = !navOpen" class="md:hidden p-2 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                    <svg x-show="!navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="navOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div x-show="navOpen" x-transition @click="navOpen = false" class="md:hidden border-t border-white/10 px-4 sm:px-6 py-3 flex flex-col gap-1 text-sm font-medium text-slate-300">
            <a href="#fitur" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Fitur</a>
            <a href="#cara-kerja" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Cara Kerja</a>
            <a href="#harga" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Harga</a>
            <a href="#faq" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">FAQ</a>
            <a href="{{ route('login') }}" class="px-2 py-2.5 rounded-lg hover:bg-white/5 hover:text-white transition-colors">Masuk</a>
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
            <p class="text-xs text-slate-500 mt-5">Tanpa kartu kredit &middot; Setup di bawah 5 menit &middot; Batalkan kapan saja</p>
        </div>

        <a href="#fitur" class="absolute bottom-6 sm:bottom-8 inset-x-0 flex justify-center text-slate-500 bounce-y z-10">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
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

    <!-- ===================== HARGA ===================== -->
    <section id="harga" class="max-w-6xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24">
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Harga</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Paket Sesuai Kebutuhan RT Anda</h2>
            <p class="text-sm text-gray-500 mt-2">Semua paket sudah termasuk portal warga &amp; transparansi kas. Trial 14 hari di semua tier.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 reveal">
            @foreach($plans as $plan)
            <div class="rounded-2xl border p-6 relative {{ $plan->is_popular ? 'border-indigo-400 shadow-lg shadow-indigo-100' : 'border-gray-100 shadow-sm' }} bg-white">
                @if($plan->is_popular)
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-[11px] font-bold text-white whitespace-nowrap" style="background: linear-gradient(135deg,#6366f1,#a855f7);">PALING POPULER</span>
                @endif
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">{{ $plan->name }}</p>
                <p class="text-3xl font-black text-gray-900 mt-2">Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400">/bulan</p>

                <ul class="mt-5 space-y-2.5">
                    {{-- Batas KK --}}
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $plan->isUnlimitedKk() ? 'Kartu Keluarga tanpa batas' : 'Hingga ' . number_format($plan->max_kk) . ' Kartu Keluarga' }}
                    </li>
                    
                    {{-- Batas AI --}}
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $plan->isUnlimitedAi() ? 'Ekstraksi AI tanpa batas' : number_format($plan->max_ai_extractions_per_month) . ' ekstraksi foto KK dengan AI / bulan' }}
                    </li>

                    {{-- Fitur Modul --}}
                    @php
                        $featureLabels = [
                            'data_kk' => 'Manajemen Data KK',
                            'data_warga' => 'Manajemen Data Warga',
                            'iuran_warga' => 'Pencatatan Iuran Warga',
                            'pengeluaran_kas' => 'Pencatatan Pengeluaran Kas',
                            'pengajuan_surat' => 'Layanan Pengajuan Surat',
                            'laporan_warga' => 'Sistem Pelaporan Warga',
                            'lapor_peristiwa' => 'Pencatatan Peristiwa Warga',
                            'berita_pengumuman' => 'Portal Berita & Pengumuman',
                            'pasar_umkm' => 'Pasar Warga (UMKM)',
                            'export_laporan' => 'Ekspor Laporan (Excel & PDF)',
                        ];
                        $planFeatures = is_array($plan->features) ? $plan->features : [];
                    @endphp

                    @foreach($featureLabels as $key => $label)
                        @if(!empty($planFeatures[$key]))
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $label }}
                        </li>
                        @endif
                    @endforeach
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
    </script>
</body>
</html>
