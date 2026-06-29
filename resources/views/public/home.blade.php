<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal resmi warga — statistik, transparansi kas, dan layanan mandiri RT.">
    <title>{{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }} · Portal Warga</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { font-family: 'Outfit', sans-serif; box-sizing: border-box; }
        html { scroll-behavior: smooth; background: #0f172a; }
        body { background: #f4f4f9; color: #1e293b; overflow-x: hidden; }

        /* Modern Utility Classes */
        .glass-panel { background: rgba(255,255,255,0.75); backdrop-filter: blur(20px) saturate(180%); border: 1px solid rgba(255,255,255,0.4); }
        .glass-dark { background: rgba(15,23,42,0.6); backdrop-filter: blur(20px) saturate(180%); border: 1px solid rgba(255,255,255,0.08); }
        
        .btn-gradient { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; padding: 14px 26px; border-radius: 99px; font-size: 15px; font-weight: 700; border: none; cursor: pointer; transition: all .3s cubic-bezier(.4,0,.2,1); display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 25px -5px rgba(79,70,229,0.5); text-decoration: none; position: relative; overflow: hidden; }
        .btn-gradient::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, #7c3aed, #4f46e5); opacity: 0; transition: opacity .3s; }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -5px rgba(79,70,229,0.6); }
        .btn-gradient:hover::before { opacity: 1; }
        .btn-gradient > * { position: relative; z-index: 10; }

        .btn-outline-glow { background: transparent; color: #fff; padding: 14px 26px; border-radius: 99px; font-size: 15px; font-weight: 700; border: 2px solid rgba(255,255,255,0.2); cursor: pointer; transition: all .3s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
        .btn-outline-glow:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.5); transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(255,255,255,0.1); }

        .input-field { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 14px; font-size: 14px; font-weight: 500; outline: none; background: #fff; color: #0f172a; transition: all 0.2s; }
        .input-field:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.15); }
        .label { display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 6px; letter-spacing: 0.5px; }

        /* Premium Cards */
        .premium-card { background: white; border-radius: 28px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px -5px rgba(15,23,42,0.03); transition: all .3s cubic-bezier(.4,0,.2,1); position: relative; overflow: hidden; }
        .premium-card:hover { transform: translateY(-6px); box-shadow: 0 25px 50px -12px rgba(99,102,241,0.15); border-color: #e0e7ff; }

        .service-card { display: flex; flex-direction: column; align-items: flex-start; gap: 14px; padding: 24px; border-radius: 24px; border: 1px solid #f1f5f9; background: white; transition: all .3s cubic-bezier(.4,0,.2,1); cursor: pointer; text-align: left; width: 100%; position: relative; overflow: hidden; }
        .service-card::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 60%); opacity: 0; transform: scale(0.5); transition: all .4s ease; pointer-events: none; }
        .service-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 25px 45px -15px rgba(99,102,241,0.15); border-color: #c7d2fe; z-index: 10; }
        .service-card:hover::after { opacity: 1; transform: scale(1); }

        /* Animations */
        .reveal { opacity: 0; transform: translateY(40px); transition: opacity .8s cubic-bezier(.2,.8,.2,1), transform .8s cubic-bezier(.2,.8,.2,1); }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        @keyframes float { 0%,100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(2deg); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 7s ease-in-out infinite; animation-delay: 2s; }

        /* Ambient Glows */
        .ambient-glow { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; z-index: 0; pointer-events: none; }
        
        @keyframes emergency-pulse {
            0% { box-shadow: 0 0 0 0 rgba(225, 29, 72, 0.7); }
            70% { box-shadow: 0 0 0 25px rgba(225, 29, 72, 0); }
            100% { box-shadow: 0 0 0 0 rgba(225, 29, 72, 0); }
        }
        .btn-panic { animation: emergency-pulse 1.5s infinite; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body x-data="{ modal: null }" @keydown.escape.window="modal = null">

    <!-- ===================== NAVBAR ===================== -->
    @include('partials.public-nav')

    <!-- ===================== HERO 3D DYNAMIC ===================== -->
    <section id="hero" class="relative min-h-[100dvh] flex items-center overflow-hidden bg-[#09090b]">
        <!-- Modern Clean Background Pattern -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <!-- Sleek Radial Gradient for depth (not generic blobs) -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(120,119,198,0.15),rgba(255,255,255,0))]"></div>
            <!-- Ultra-faint grid pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiPjxwYXRoIGQ9Ik0wIDI0VjBIMjQiLz48L2c+PC9zdmc+')] [mask-image:linear-gradient(to_bottom,white,transparent_80%)]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-5 sm:px-6 py-24 md:py-32 w-full mt-10 sm:mt-0">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-8">
                
                <!-- Left: Typography & Actions -->
                <div class="w-full lg:w-1/2 text-center lg:text-left flex flex-col items-center lg:items-start">
                    <!-- Premium Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[11px] font-bold text-indigo-200 border border-indigo-500/30 bg-indigo-500/10 backdrop-blur-md mb-6 transition-all hover:bg-indigo-500/20">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        <span>PORTAL CERDAS WARGA &middot; {{ strtoupper($tenant->name ?? config('app.name', 'SmartRT Vision')) }}</span>
                    </div>
                    
                    <h1 class="text-[2.5rem] sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-[1.1] tracking-tight text-white mb-6">
                        Layanan Mandiri <br class="hidden sm:block"/>
                        <span class="relative inline-block">
                            <span class="relative z-10 text-white">Warga</span>
                            <span class="absolute bottom-1.5 sm:bottom-2 left-0 w-full h-3 sm:h-4 bg-indigo-500/60 -z-10 -rotate-2 rounded-sm"></span>
                        </span> 
                        <span class="whitespace-nowrap text-slate-300">{{ $tenant->name ?? 'Lingkungan' }}</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg md:text-xl text-slate-400 font-medium leading-relaxed mb-10 max-w-xl">
                        Lupakan cara lama. Pantau kas RT secara real-time, ajukan surat pengantar, laporkan keluhan, dan cek jadwal ronda langsung dari genggaman tangan Anda.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        <a href="#layanan" class="group relative inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-slate-950 text-sm font-bold rounded-2xl overflow-hidden transition-transform hover:scale-105 active:scale-95">
                            <span class="relative z-10">Jelajahi Layanan</span>
                            <svg class="w-4 h-4 relative z-10 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            <div class="absolute inset-0 bg-slate-100 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </a>
                        <a href="#kas" class="inline-flex items-center justify-center px-8 py-4 bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:border-slate-700 text-white text-sm font-bold rounded-2xl transition-all hover:scale-105 active:scale-95">
                            Lihat Laporan Kas
                        </a>
                    </div>
                </div>

                <!-- Right: Bento Grid (Desktop) / Stacked (Mobile) -->
                <div class="w-full lg:w-1/2 relative mt-8 lg:mt-0">
                    <!-- Subtle glow behind the grid -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-indigo-500/20 blur-[100px] rounded-full pointer-events-none"></div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 gap-4 relative z-10">
                        
                        <!-- Pimpinan RT Card (Spans full width on mobile/desktop, or 2 cols) -->
                        @php 
                            $ketua = isset($rt_staffs) && $rt_staffs->count() > 0
                                ? ($rt_staffs->filter(fn($s) => stripos($s->position, 'ketua') !== false)->first() 
                                   ?? $rt_staffs->sortBy('order_level')->first())
                                : null;
                        @endphp
                        
                        <div class="col-span-2 sm:col-span-3 lg:col-span-2 p-5 sm:p-6 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl flex flex-col sm:flex-row items-center sm:items-start gap-5 sm:gap-6 hover:bg-slate-900/80 hover:border-slate-700 transition-colors">
                            <div class="relative flex-shrink-0">
                                @if($ketua && $ketua->photo)
                                    <img src="{{ asset('storage/'.$ketua->photo) }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-white/10" alt="{{ $ketua->name }}">
                                @else
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                @endif
                                <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-slate-900"></span>
                                </span>
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">{{ $ketua ? $ketua->position : 'Pimpinan Lingkungan' }}</p>
                                <p class="text-xl sm:text-2xl font-extrabold text-white leading-tight mb-2">{{ $ketua ? $ketua->name : ($tenant->name ?? 'Belum diisi') }}</p>
                                <div class="flex flex-col sm:flex-row items-center sm:items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-800 text-xs font-semibold text-slate-300 border border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Online
                                    </span>
                                    @if($ketua && $ketua->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ketua->phone) }}" target="_blank" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 transition-colors">
                                        Hubungi via WhatsApp <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stats Cards: Kartu Keluarga -->
                        <div class="col-span-1 p-5 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl flex flex-col justify-center text-center hover:bg-slate-900/80 transition-colors">
                            <p class="text-3xl sm:text-4xl font-black text-white mb-1">{{ number_format($total_kk) }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kepala Keluarga</p>
                        </div>

                        <!-- Stats Cards: Penduduk -->
                        <div class="col-span-1 p-5 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl flex flex-col justify-center text-center hover:bg-slate-900/80 transition-colors">
                            <p class="text-3xl sm:text-4xl font-black text-white mb-1">{{ number_format($total_warga) }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Penduduk</p>
                        </div>
                        
                        <!-- Stats Cards: Kas (Spans full width on mobile, 2 cols on desktop if needed, or fits in 3 col layout on tablet) -->
                        <div class="col-span-2 sm:col-span-1 lg:col-span-2 p-5 rounded-3xl bg-gradient-to-br from-indigo-900/50 to-slate-900/60 border border-indigo-500/20 backdrop-blur-xl flex flex-col justify-center text-center sm:text-left hover:border-indigo-500/40 transition-colors">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                                <div>
                                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1 flex items-center justify-center sm:justify-start gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Kas Transparan
                                    </p>
                                    <p class="text-2xl sm:text-3xl font-black text-white tracking-tight">Rp {{ number_format($saldo_kas, 0, ',', '.') }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 8v2"/></svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================== DEMOGRAFI (STATS) ===================== -->
    <section id="stats" class="max-w-7xl mx-auto px-4 sm:px-6 pt-24">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 reveal">
            @php $stats = [
                ['label'=>'Total KK Terdaftar','value'=>$total_kk,'color'=>'#4f46e5','bg'=>'bg-indigo-50','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3'],
                ['label'=>'Warga Penduduk','value'=>$total_warga,'color'=>'#059669','bg'=>'bg-emerald-50','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label'=>'Pria','value'=>$laki_laki,'color'=>'#2563eb','bg'=>'bg-blue-50','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label'=>'Wanita','value'=>$perempuan,'color'=>'#db2777','bg'=>'bg-pink-50','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ]; @endphp
            @foreach($stats as $s)
            <div class="premium-card p-6 flex flex-col items-center sm:items-start text-center sm:text-left">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 {{ $s['bg'] }}">
                    <svg class="w-7 h-7" style="color: {{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
                </div>
                <p class="text-3xl sm:text-4xl font-black text-slate-900 leading-none">{{ number_format($s['value']) }}</p>
                <p class="text-sm font-bold text-slate-500 mt-2">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ===================== LAYANAN MANDIRI (GRID) ===================== -->
    <section id="layanan" class="max-w-7xl mx-auto px-4 sm:px-6 py-24">
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12 reveal">
            <div class="max-w-2xl">
                <span class="inline-block py-1 px-3 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-widest mb-3">Pusat Layanan Warga</span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">Cepat, Mudah, Tanpa Antre</h2>
                <p class="text-base text-slate-500 mt-4 font-medium">Gunakan NIK Anda untuk mengakses layanan administrasi secara langsung.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 reveal">
            @php $actions = [
                ['modal'=>'cek-nik','title'=>'Cek Status Warga','desc'=>'Verifikasi status keanggotaan warga Anda di database RT.','color'=>'#6366f1','bg'=>'bg-indigo-50','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['modal'=>'ajukan-surat','title'=>'Pengajuan Surat Resmi','desc'=>'Minta surat pengantar RT untuk KTP, KK, SKCK, atau Nikah.','color'=>'#0891b2','bg'=>'bg-cyan-50','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['modal'=>'cek-surat','title'=>'Lacak Status Surat','desc'=>'Pantau sejauh mana surat pengantar Anda diproses RT.','color'=>'#7c3aed','bg'=>'bg-purple-50','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['modal'=>'cek-iuran','title'=>'Riwayat Pembayaran','desc'=>'Pantau riwayat pembayaran iuran kebersihan/keamanan Anda.','color'=>'#059669','bg'=>'bg-emerald-50','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 8v2'],
                ['modal'=>'kirim-laporan','title'=>'Pusat Bantuan & Keluhan','desc'=>'Laporkan masalah infrastruktur, keamanan, atau kebersihan.','color'=>'#dc2626','bg'=>'bg-rose-50','icon'=>'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z'],
                ['modal'=>'cek-laporan','title'=>'Lacak Tiket Bantuan','desc'=>'Cek status penyelesaian laporan yang telah Anda kirimkan.','color'=>'#d97706','bg'=>'bg-amber-50','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['modal'=>'titip-rumah','title'=>'Penjagaan Rumah Kosong','desc'=>'Lapor titip pengawasan rumah kosong saat sedang mudik/dinas.','color'=>'#4f46e5','bg'=>'bg-indigo-50','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['modal'=>'lapor-peristiwa','title'=>'Catatan Sipil Warga','desc'=>'Laporkan peristiwa kelahiran, kematian, atau warga pindah.','color'=>'#7c3aed','bg'=>'bg-purple-50','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['modal'=>'pinjam-inventaris-list','title'=>'Pinjam Inventaris RT','desc'=>'Lihat daftar inventaris barang RT yang dapat dipinjam gratis.','color'=>'#3b82f6','bg'=>'bg-blue-50','icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['modal'=>'lapor-kos','title'=>'Lapor Kos / Kontrakan','desc'=>'Lapor data penghuni kos atau kontrakan baru di wilayah RT.','color'=>'#6366f1','bg'=>'bg-indigo-50','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5'],
            ]; @endphp
            
            @foreach($actions as $a)
            <button type="button" @click="modal = '{{ $a['modal'] }}'" class="service-card group">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-2 {{ $a['bg'] }} transition-transform duration-300 group-hover:scale-110">
                    <svg class="w-7 h-7" style="color: {{ $a['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $a['icon'] }}"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $a['title'] }}</h3>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ $a['desc'] }}</p>
                </div>
                <div class="mt-auto pt-4 flex items-center text-sm font-bold" style="color: {{ $a['color'] }};">
                    Akses Layanan <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </button>
            @endforeach
        </div>
    </section>

    <!-- ===================== STRUKTUR ORGANISASI ===================== -->
    @if(isset($rt_staffs) && $rt_staffs->count() > 0)
    <section id="organisasi" class="max-w-7xl mx-auto px-4 sm:px-6 py-24">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="inline-block py-1 px-3 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-widest mb-3">Struktur Organisasi</span>
            <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">Jajaran Pengurus RT</h2>
            <p class="text-base text-slate-500 mt-4 font-medium">Kenali pengurus lingkungan Anda yang siap membantu berbagai keperluan warga.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 reveal">
            @foreach($rt_staffs as $staff)
            <div class="premium-card group overflow-hidden bg-white hover:bg-slate-50 transition-colors">
                <div class="aspect-square bg-slate-100 overflow-hidden relative">
                    @if($staff->photo)
                        <img src="{{ asset('storage/'.$staff->photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $staff->name }}">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-300">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    @endif
                    <!-- Gradient overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        @if($staff->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $staff->phone) }}" target="_blank" class="w-full text-center py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-colors flex items-center justify-center gap-1.5 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Hubungi
                        </a>
                        @endif
                    </div>
                </div>
                <div class="p-5 text-center">
                    <p class="text-sm font-bold text-indigo-600 mb-1 tracking-wide uppercase">{{ $staff->position }}</p>
                    <h3 class="text-lg font-black text-slate-900 leading-tight">{{ $staff->name }}</h3>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- ===================== TRANSPARANSI KAS ===================== -->
    <section id="kas" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 relative">
        <div class="absolute inset-0 bg-white -z-10 transform skew-y-2"></div>
        <div class="text-center max-w-2xl mx-auto mb-16 reveal relative z-10">
            <span class="inline-block py-1 px-3 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest mb-3">Keuangan Terbuka</span>
            <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">Transparansi Dana Warga</h2>
            <p class="text-base text-slate-500 mt-4 font-medium">Bebas kecurigaan. Setiap rupiah kas yang masuk dan keluar dapat Anda pantau secara langsung.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal relative z-10">
            <!-- Saldo Card (Credit Card Style) -->
            <div class="rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl flex flex-col h-full" style="background: linear-gradient(135deg, #0f172a, #334155);">
                <div class="absolute top-0 right-0 p-6 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/></svg>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Saldo Kas Aktif</p>
                <p class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-8">Rp {{ number_format($saldo_kas, 0, ',', '.') }}</p>
                
                <div class="flex items-center gap-2 mt-auto">
                    <span class="w-8 h-5 rounded bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center"><svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg></span>
                    <span class="text-xs font-bold text-slate-300">Aman & Terkendali</span>
                </div>
            </div>

            <!-- Pemasukan Card -->
            <div class="premium-card p-8 flex flex-col justify-center border-t-4 border-t-emerald-500 h-full text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Pemasukan</p>
                <p class="text-2xl sm:text-3xl font-black text-emerald-600">Rp {{ number_format($total_masuk, 0, ',', '.') }}</p>
            </div>

            <!-- Pengeluaran Card -->
            <div class="premium-card p-8 flex flex-col justify-center border-t-4 border-t-rose-500 h-full text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-rose-50 flex items-center justify-center text-rose-600 mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                <p class="text-2xl sm:text-3xl font-black text-rose-600">Rp {{ number_format($total_keluar, 0, ',', '.') }}</p>
            </div>
            
            @if(count($chart_labels))
            <div class="premium-card p-6 md:col-span-3 flex flex-col sm:flex-row items-center gap-8 mt-2">
                <div class="flex-1 w-full text-center sm:text-left">
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Analisis Pengeluaran</h4>
                    <p class="text-sm text-slate-500 font-medium">Berdasarkan grafik di samping, warga dapat melihat sektor mana saja yang menggunakan dana kas RT terbanyak bulan ini.</p>
                </div>
                <div class="relative h-[200px] w-[200px] flex-shrink-0 mx-auto">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- ===================== KALENDER AGENDA ===================== -->
    @if(isset($agendas) && $agendas->count() > 0)
    <section id="agenda" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60 bg-white">
        <div class="text-center mb-16">
            <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Kegiatan Lingkungan</h2>
            <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">Agenda Mendatang</h3>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">Jadwal kegiatan RT/RW, dari rapat, kerja bakti, hingga posyandu.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($agendas as $agenda)
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex flex-col items-center justify-center border border-slate-200 shadow-sm font-bold text-slate-700">
                        <span class="text-xs uppercase text-slate-400">{{ $agenda->start_time->format('M') }}</span>
                        <span class="text-lg leading-none">{{ $agenda->start_time->format('d') }}</span>
                    </div>
                    @if($agenda->type === 'rapat')
                        <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-md">Rapat</span>
                    @elseif($agenda->type === 'kerjabakti')
                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-md">Kerja Bakti</span>
                    @elseif($agenda->type === 'posyandu')
                        <span class="px-2.5 py-1 bg-pink-100 text-pink-700 text-xs font-bold rounded-md">Posyandu</span>
                    @else
                        <span class="px-2.5 py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-md">Umum</span>
                    @endif
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2">{{ $agenda->title }}</h4>
                @if($agenda->description)
                    <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $agenda->description }}</p>
                @endif
                <div class="flex flex-col gap-2 mt-auto">
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $agenda->start_time->format('H:i') }} {{ $agenda->end_time ? ' - ' . $agenda->end_time->format('H:i') : ' - Selesai' }}
                    </div>
                    @if($agenda->location)
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $agenda->location }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- ===================== BRANKAS DIGITAL ===================== -->
    @if(isset($public_documents) && $public_documents->count() > 0)
    <section id="dokumen" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60 bg-slate-50">
        <div class="text-center mb-16">
            <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Transparansi Informasi</h2>
            <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">Brankas Digital</h3>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">Arsip dokumen publik RT seperti SK, Laporan Keuangan, dan Notulen Rapat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($public_documents as $doc)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col hover:border-indigo-300 transition-colors shadow-sm">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-slate-900 truncate mb-1" title="{{ $doc->title }}">{{ $doc->title }}</h4>
                        <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-2">{{ str_replace('_', ' ', $doc->category) }}</p>
                        @if($doc->description)
                            <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $doc->description }}</p>
                        @endif
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-xs text-slate-400">{{ $doc->created_at->format('d M Y') }}</span>
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 hover:underline flex items-center gap-1">
                                Download <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- ===================== CCTV LINGKUNGAN ===================== -->
    @if(isset($active_cctvs) && $active_cctvs->count() > 0)
    <section id="cctv" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/20 to-rose-900/10 mix-blend-overlay"></div>
        <div class="text-center mb-16 relative z-10">
            <h2 class="text-sm font-black tracking-[0.2em] text-rose-400 uppercase mb-3 flex items-center justify-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Live Monitoring
            </h2>
            <h3 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-6">CCTV Lingkungan</h3>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto font-medium">Pantau keamanan area publik RT kita secara langsung dari mana saja.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative z-10">
            @foreach($active_cctvs as $cctv)
            <div class="bg-black/50 border border-slate-800 rounded-3xl overflow-hidden backdrop-blur-md shadow-2xl">
                <div class="aspect-video w-full bg-black relative">
                    @if(str_contains($cctv->stream_url, '<iframe'))
                        <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full border-none">
                            {!! $cctv->stream_url !!}
                        </div>
                    @elseif(str_starts_with($cctv->stream_url, 'ezopen://'))
                        @php
                            $ezvizToken = \App\Services\EzvizService::getAccessToken(app('currentTenant')->id);
                        @endphp
                        @if($ezvizToken)
                            <div id="ezviz-public-{{ $cctv->id }}" class="w-full h-full"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    if (typeof EZUIKit !== 'undefined') {
                                        new EZUIKit.EZUIPlayer({
                                            id: 'ezviz-public-{{ $cctv->id }}',
                                            autoplay: true,
                                            url: '{{ $cctv->stream_url }}',
                                            accessToken: '{{ $ezvizToken }}',
                                            decoderPath: '',
                                            width: '100%',
                                            height: '100%'
                                        });
                                    }
                                });
                            </script>
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-rose-400 bg-slate-900/50">
                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span class="text-sm font-semibold">Token EZVIZ Belum Diatur</span>
                            </div>
                        @endif
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 bg-slate-900/50">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <a href="{{ $cctv->stream_url }}" target="_blank" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-full font-bold text-sm transition-colors shadow-lg shadow-rose-500/30">
                                Buka Stream RTSP/HLS
                            </a>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-black/60 backdrop-blur-sm border border-white/10 text-white text-xs font-bold rounded-lg shadow-sm">
                            {{ $cctv->name }}
                        </span>
                    </div>
                </div>
                <div class="p-5 flex justify-between items-center bg-slate-900/80">
                    <div class="flex items-center gap-2 text-slate-300 text-sm font-medium">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $cctv->location ?? 'Lokasi tidak disebutkan' }}
                    </div>
                    <span class="flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- ===================== E-VOTING / MUSYAWARAH ===================== -->
    @if(isset($active_polls) && $active_polls->count() > 0)
    <section id="evoting" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60 bg-slate-50">
        <div class="text-center mb-16">
            <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Musyawarah Warga</h2>
            <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">E-Voting Aktif</h3>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">Sampaikan suara Anda untuk keputusan lingkungan kita. 1 NIK = 1 Suara.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($active_polls as $poll)
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-xl relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                <div class="absolute top-0 right-0 p-4">
                    <span class="px-3 py-1 bg-rose-100 text-rose-700 font-black text-xs rounded-full animate-pulse uppercase tracking-widest">Live Polling</span>
                </div>
                <h4 class="text-2xl font-black text-slate-900 mb-2 pr-20">{{ $poll->title }}</h4>
                <p class="text-slate-500 text-sm font-medium mb-6">{{ $poll->description }}</p>

                <form onsubmit="return submitForm(event, 'vote-form-{{ $poll->id }}', '{{ route('submit-vote', ['tenant' => $tenant->slug]) }}', 'POST')" id="vote-form-{{ $poll->id }}">
                    @csrf
                    <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                    
                    <div class="space-y-3 mb-6">
                        @foreach($poll->options as $option)
                        <label class="block relative border-2 border-slate-100 rounded-2xl p-4 cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                            <input type="radio" name="poll_option_id" value="{{ $option->id }}" required class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                            <span class="block font-bold text-slate-900">{{ $option->option_text }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Verifikasi NIK (Rahasia) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" placeholder="Masukkan 16 digit NIK" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 text-sm mb-4">
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors">
                        Kirim Suara
                    </button>
                    <div id="vote-result-{{ $poll->id }}" class="mt-4"></div>
                </form>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- ===================== JADWAL & ABSENSI RONDA ===================== -->
    <section id="ronda" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60 bg-white">
        <div class="text-center mb-16 reveal">
            <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Keamanan & Ketertiban</h2>
            <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">Jadwal Ronda Malam</h3>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">Cek jadwal tugas ronda Anda malam ini dan lakukan absensi kehadiran langsung di sini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Kolom Kiri: Daftar Petugas Hari Ini -->
            <div class="reveal">
                <h4 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Jadwal Malam Ini
                    <span class="text-sm font-bold text-slate-400 font-mono ml-auto">{{ now()->translatedFormat('l, d M Y') }}</span>
                </h4>

                @if(isset($ronda_schedules) && $ronda_schedules->count() > 0)
                    <div class="space-y-4">
                        @foreach($ronda_schedules as $schedule)
                        <div class="p-4 rounded-2xl border {{ $schedule->attendance ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 bg-white' }} flex items-center justify-between shadow-sm transition-all hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full {{ $schedule->attendance ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-600' }} flex flex-shrink-0 items-center justify-center font-bold text-lg border {{ $schedule->attendance ? 'border-emerald-200' : 'border-slate-200' }}">
                                    {{ substr($schedule->member->nama, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-slate-900">{{ $schedule->member->nama }}</h5>
                                    <p class="text-xs text-slate-500 font-mono">Alamat: {{ $schedule->member->family->alamat }}</p>
                                </div>
                            </div>
                            <div>
                                @if($schedule->attendance)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Hadir
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Menunggu
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 text-center">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <h5 class="text-sm font-bold text-slate-900 mb-1">Tidak Ada Jadwal</h5>
                        <p class="text-xs text-slate-500">Malam ini tidak ada jadwal ronda yang ditugaskan oleh pengurus RT.</p>
                    </div>
                @endif
            </div>

            <!-- Kolom Kanan: Form Absensi -->
            <div class="reveal">
                <div class="bg-indigo-600 rounded-3xl p-8 shadow-2xl shadow-indigo-600/20 relative overflow-hidden text-white">
                    <div class="absolute top-0 right-0 p-6 opacity-10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-2.33v8.02z"/></svg>
                    </div>
                    
                    <h4 class="text-2xl font-black mb-2 relative z-10">Absensi Kehadiran</h4>
                    <p class="text-indigo-200 text-sm mb-8 relative z-10">Petugas ronda malam ini silakan melakukan absensi di bawah ini.</p>

                    <form onsubmit="return submitAbsenRonda(event)" id="absen-ronda-form" class="relative z-10 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-indigo-200 mb-1">16 Digit NIK <span class="text-rose-400">*</span></label>
                            <input type="text" name="nik" required pattern="\d{16}" maxlength="16" placeholder="Masukkan NIK Anda" class="w-full bg-indigo-700/50 border border-indigo-500 rounded-xl px-4 py-3 text-white placeholder-indigo-300 focus:ring-2 focus:ring-white focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-indigo-200 mb-1">Lokasi Berjaga <span class="text-rose-400">*</span></label>
                            <input type="text" name="location" required value="Pos Kamling RT" class="w-full bg-indigo-700/50 border border-indigo-500 rounded-xl px-4 py-3 text-white placeholder-indigo-300 focus:ring-2 focus:ring-white focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-indigo-200 mb-1">Foto Selfie Kehadiran <span class="text-rose-400">*</span></label>
                            <div class="relative group">
                                <input type="file" name="foto" accept="image/*" capture="environment" required onchange="document.getElementById('foto-label').textContent = this.files[0] ? this.files[0].name : 'Buka Kamera / Pilih Foto'" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full bg-indigo-700/50 border-2 border-dashed border-indigo-400 rounded-xl p-6 text-center group-hover:bg-indigo-700 transition-colors">
                                    <svg class="w-8 h-8 text-indigo-300 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-sm font-bold text-white block truncate" id="foto-label">Buka Kamera / Pilih Foto</span>
                                    <span class="text-xs text-indigo-300 mt-1 block">Arahkan kamera ke wajah & pos kamling</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-white hover:bg-slate-50 text-indigo-700 text-sm font-black uppercase tracking-wider rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 mt-2 flex items-center justify-center gap-2">
                            <span>Kirim Absensi</span>
                            <div id="ronda-loading" class="hidden w-4 h-4 rounded-full border-2 border-indigo-700 border-t-transparent animate-spin"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== KALENDER AGENDA ===================== -->
    <section id="agenda" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Agenda & Kegiatan</h2>
                <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Kalender RT</h3>
            </div>
            <p class="text-slate-500 font-medium max-w-sm">Jadwal kegiatan rutin, rapat warga, kerja bakti, dan agenda penting lainnya di lingkungan kita.</p>
        </div>

        @if(isset($agendas) && $agendas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($agendas as $agenda)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-50 to-transparent rounded-bl-full -z-10 opacity-50"></div>
                
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex flex-col items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $agenda->start_time->format('M') }}</span>
                        <span class="text-lg font-black text-indigo-900 leading-none">{{ $agenda->start_time->format('d') }}</span>
                    </div>
                    <div>
                        <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md border {{ $agenda->type === 'rapat' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($agenda->type === 'kerjabakti' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                            {{ $agenda->type }}
                        </span>
                        <p class="text-xs font-semibold text-slate-400 mt-1.5">{{ $agenda->start_time->format('H:i') }} WIB - Selesai</p>
                    </div>
                </div>

                <h4 class="text-lg font-bold text-slate-900 mb-2 leading-tight group-hover:text-indigo-600 transition-colors">{{ $agenda->title }}</h4>
                <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $agenda->description ?? 'Tidak ada deskripsi.' }}</p>
                
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 bg-slate-50 py-2 px-3 rounded-lg border border-slate-100">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="truncate">{{ $agenda->location ?? 'Belum ditentukan' }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white border border-slate-200 border-dashed rounded-3xl p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Agenda</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto">Saat ini belum ada jadwal kegiatan warga yang direncanakan.</p>
        </div>
        @endif
    </section>

    <!-- ===================== BERITA & PENGUMUMAN ===================== -->
    <section id="warta" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Warta Warga</h2>
                <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Berita & Pengumuman</h3>
            </div>
            <p class="text-slate-500 font-medium max-w-sm">Dapatkan informasi, undangan, dan pengumuman terbaru langsung dari pengurus RT.</p>
        </div>

        @if(isset($warta) && $warta->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($warta as $news)
            <!-- Card Berita -->
            <div @click="modal = 'berita-{{ $news->id }}'" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 flex flex-col h-full group cursor-pointer relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-indigo-50 to-transparent rounded-bl-full -z-10 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md border {{ $news->kategori === 'Penting' || $news->is_penting ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200' }}">
                        {{ $news->kategori }}
                    </span>
                    <span class="text-xs font-semibold text-slate-400">{{ $news->created_at->translatedFormat('d M Y') }}</span>
                </div>
                
                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-tight">{{ $news->judul }}</h4>
                <p class="text-sm text-slate-500 mb-6 line-clamp-3 leading-relaxed flex-1">{{ Str::limit(strip_tags($news->isi), 150) }}</p>
                
                <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400">Oleh: Pengurus RT</span>
                    <div class="text-indigo-600 bg-indigo-50 p-2 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </div>

            <!-- Modal Detail Berita -->
            <div x-show="modal === 'berita-{{ $news->id }}'" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background Overlay -->
                    <div x-show="modal === 'berita-{{ $news->id }}'" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" 
                         @click="modal = null" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal Panel -->
                    <div x-show="modal === 'berita-{{ $news->id }}'" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         class="inline-block w-full max-w-2xl px-1 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:p-8 border border-slate-100">
                        
                        <!-- Close Button -->
                        <div class="absolute top-4 right-4 sm:top-6 sm:right-6">
                            <button @click="modal = null" type="button" class="text-slate-400 hover:text-slate-500 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                                <span class="sr-only">Close</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md border {{ $news->kategori === 'Penting' || $news->is_penting ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200' }}">
                                    {{ $news->kategori }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400">{{ $news->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                            
                            <h3 class="text-2xl font-black text-slate-900 mb-6 leading-tight">{{ $news->judul }}</h3>
                            
                            @if($news->gambar)
                                <div class="w-full h-64 sm:h-80 mb-6 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="prose prose-sm sm:prose-base prose-slate max-w-none text-slate-600 leading-relaxed custom-scrollbar max-h-[50vh] overflow-y-auto pr-2 pb-4">
                                {!! nl2br(e($news->isi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white border border-slate-200 border-dashed rounded-3xl p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3L22 4"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Pengumuman</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto">Informasi terbaru dari pengurus RT akan tampil di sini secara otomatis.</p>
        </div>
        @endif
    </section>

    <!-- ===================== PASAR WARGA (UMKM) ===================== -->
    <section id="umkm" class="max-w-7xl mx-auto px-4 sm:px-6 py-24 border-t border-slate-200/60">
        <div class="text-center mb-16">
            <h2 class="text-sm font-black tracking-[0.2em] text-indigo-600 uppercase mb-3">Pasar Warga</h2>
            <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">Dukung UMKM RT Kita.</h3>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">Beli produk dan jasa dari tetangga sendiri untuk memajukan ekonomi lingkungan.</p>
        </div>

        @if(isset($produk) && $produk->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($produk as $item)
            <div class="premium-card flex flex-col h-full overflow-hidden group">
                <div class="h-48 overflow-hidden bg-slate-100 relative">
                    @if($item->foto)
                        <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full text-[10px] font-bold text-indigo-600 uppercase tracking-wider shadow-sm">
                        {{ $item->kategori }}
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="text-lg font-bold text-slate-900 mb-1 line-clamp-1">{{ $item->nama_produk }}</h4>
                    <p class="text-sm font-medium text-slate-500 mb-3 line-clamp-2 flex-1">{{ $item->deskripsi }}</p>
                    <div class="flex items-end justify-between mt-auto pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Penjual</p>
                            <p class="text-sm font-bold text-slate-700">{{ $item->penjual }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-black text-indigo-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @if($item->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->whatsapp) }}?text=Halo%2C%20saya%20warga%20RT%20{{ substr(app('currentTenant')->name ?? '001', 0, 3) }}.%20Saya%20ingin%20memesan%20*{{ urlencode($item->nama_produk) }}*.%20Apakah%20masih%20tersedia%3F" target="_blank" class="mt-4 w-full bg-[#25D366] hover:bg-[#128C7E] text-white py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Pesan via WhatsApp
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 px-4 rounded-3xl border-2 border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h4 class="text-lg font-bold text-slate-900 mb-1">Belum ada UMKM</h4>
            <p class="text-sm text-slate-500 max-w-md mx-auto">Pengurus RT belum mendaftarkan produk atau jasa warga ke dalam sistem Pasar Warga.</p>
        </div>
        @endif
    </section>



    <!-- ===================== MODALS ( ALPINE ) ===================== -->
    <div x-show="modal !== null" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-transition.opacity style="background: rgba(15,23,42,0.8); backdrop-filter: blur(8px);">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 max-h-[90vh] overflow-y-auto shadow-2xl relative" @click.away="modal = null" x-transition.scale.95 x-show="modal !== null">
                
                <!-- Modal Close -->
                <button @click="modal = null" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Modal Headers dynamic -->
                <div class="mb-6 pr-8">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 text-indigo-600 bg-indigo-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900" x-text="{
                        'cek-nik': 'Validasi NIK',
                        'ajukan-surat': 'Form Pengajuan Surat',
                        'cek-iuran': 'Cek Riwayat Iuran & Tunggakan',
                        'kirim-laporan': 'Lapor Keluhan',
                        'cek-laporan': 'Lacak Tiket',
                        'lapor-peristiwa': 'Catat Peristiwa',
                        'trigger-panic': 'Laporan Darurat (PANIC)',
                        'pinjam-inventaris': 'Pinjam Barang Inventaris',
                        'cek-surat': 'Lacak Status Surat',
                        'pinjam-inventaris-list': 'Daftar Inventaris RT',
                        'lapor-kos': 'Lapor Warga Kos / Kontrakan',
                    }[modal]"></h3>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Lengkapi form di bawah ini untuk melanjutkan.</p>
                </div>

                <!-- FORM: List Inventaris Warga -->
                <div x-show="modal === 'pinjam-inventaris-list'">
                    <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                        @if(isset($inventories) && $inventories->count() > 0)
                            @foreach($inventories as $item)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex justify-between items-center gap-4">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-900 text-sm truncate">{{ $item->item_name }}</h4>
                                    @if($item->description)
                                        <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $item->description }}</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400 mt-2">
                                        Tersedia: <span class="font-black text-slate-700">{{ $item->available_quantity }} / {{ $item->total_quantity }} Unit</span>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($item->available_quantity > 0)
                                        <button type="button" @click="modal = 'pinjam-inventaris'; $nextTick(() => { document.getElementById('inventory_id_input').value = '{{ $item->id }}'; document.getElementById('inventory_name_display').value = '{{ $item->item_name }}'; document.getElementById('qty_input').max = {{ $item->available_quantity }}; })" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all active:scale-95 shadow-sm shadow-indigo-100">Pinjam</button>
                                    @else
                                        <span class="px-3.5 py-1.5 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold">Habis</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <p class="text-xs">Belum ada barang inventaris yang dapat dipinjam.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- FORM: Pinjam Inventaris -->
                <div x-show="modal === 'pinjam-inventaris'">
                    <form onsubmit="return submitForm(event, 'pinjam-inventaris-form', '{{ route('pinjam-inventaris', ['tenant' => $tenant->slug]) }}', 'POST')" id="pinjam-inventaris-form" class="space-y-4">
                        <input type="hidden" name="inventory_id" id="inventory_id_input">
                        <div>
                            <label class="label">Barang yang Dipinjam</label>
                            <input type="text" id="inventory_name_display" readonly class="input-field bg-slate-50 text-slate-500 font-bold border-slate-200">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Jumlah Pinjam</label>
                                <input type="number" name="quantity" id="qty_input" required min="1" value="1" class="input-field">
                            </div>
                            <div>
                                <label class="label">Rencana Kembali</label>
                                <input type="date" name="expected_return_date" required min="{{ date('Y-m-d') }}" class="input-field">
                            </div>
                        </div>
                        <div>
                            <label class="label">Nama Peminjam</label>
                            <input type="text" name="borrower_name" required class="input-field" placeholder="Nama Lengkap">
                        </div>
                        <div>
                            <label class="label">No WhatsApp (Aktif)</label>
                            <input type="text" name="borrower_contact" required class="input-field" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" @click="modal = 'pinjam-inventaris-list'" class="w-1/3 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all">Kembali</button>
                            <button type="submit" class="flex-1 py-3 btn-gradient">Ajukan Peminjaman</button>
                        </div>
                    </form>
                    <div id="pinjam-inventaris-result" class="mt-4"></div>
                </div>

                <!-- FORM: Lapor Warga Kos/Kontrakan -->
                <div x-show="modal === 'lapor-kos'">
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-sm mb-4">
                        <b>Perhatian:</b> Khusus bagi pemilik kos/kontrakan. Wajib melaporkan penghuni baru selambat-lambatnya 2x24 jam sejak kedatangan.
                    </div>
                    <form onsubmit="return submitFormData(event, 'lapor-kos-form', '{{ route('lapor.kos', ['tenant' => $tenant->slug]) }}')" id="lapor-kos-form" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label class="label">Nama Pemilik (Induk Kos)</label>
                            <input type="text" name="nama_pemilik" required class="input-field placeholder-slate-400" placeholder="Nama Lengkap Pemilik">
                        </div>
                        <div>
                            <label class="label">Alamat Kos / Blok Kontrakan</label>
                            <input type="text" name="alamat_kos" required class="input-field placeholder-slate-400" placeholder="Cth: Blok G3 No. 15">
                        </div>
                        <div class="border-t border-slate-100 my-4 pt-4">
                            <h4 class="text-sm font-bold text-slate-800 mb-3">Data Penghuni Baru</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="label">Nama Lengkap Penghuni</label>
                                    <input type="text" name="nama_penghuni" required class="input-field placeholder-slate-400" placeholder="Sesuai KTP">
                                </div>
                                <div>
                                    <label class="label">NIK Penghuni</label>
                                    <input type="text" name="nik_penghuni" required pattern="[0-9]{16}" maxlength="16" class="input-field placeholder-slate-400" placeholder="16 digit NIK">
                                </div>
                                <div>
                                    <label class="label">Upload Foto KTP Penghuni</label>
                                    <input type="file" name="foto_ktp" required accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                    <p class="text-[10px] text-slate-400 mt-1.5">*Maksimal 2MB (JPG/PNG)</p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-gradient w-full mt-2">Kirim Laporan</button>
                    </form>
                    <div id="lapor-kos-result" class="mt-4"></div>
                </div>

                <!-- FORM: Buku Tamu Digital (Security Gate) -->
                <div x-show="modal === 'guestbook'">
                    <div class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs mb-4">
                        <b>Pos Jaga Keamanan:</b> Harap catat identitas tamu dan plat kendaraan yang memasuki wilayah RT dengan benar.
                    </div>
                    <form id="guestbook-form" onsubmit="return submitForm(event, 'guestbook-form', '{{ route('submit-guestbook', ['tenant' => $tenant->slug]) }}', 'POST')" class="space-y-4">
                        @csrf
                        <div>
                            <label class="label">Nama Lengkap Tamu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_tamu" required placeholder="Bpk. Budi / Kurir Paket" class="input-field">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Plat Kendaraan</label>
                                <input type="text" name="plat_nomor" placeholder="B 1234 XYZ" class="input-field uppercase">
                            </div>
                            <div>
                                <label class="label">Tujuan Rumah <span class="text-red-500">*</span></label>
                                <input type="text" name="tujuan_rumah" required placeholder="Blok A1 No. 5" class="input-field">
                            </div>
                        </div>
                        <div>
                            <label class="label">Keperluan <span class="text-red-500">*</span></label>
                            <input type="text" name="keperluan" required placeholder="Antar Paket / Bertamu / Teknisi AC" class="input-field">
                        </div>
                        <div class="border-t border-slate-100 mt-4 pt-4">
                            <label class="label">PIN Keamanan (Otorisasi Satpam) <span class="text-red-500">*</span></label>
                            <input type="password" name="pin" required placeholder="****" class="w-full rounded-xl border-amber-200 bg-amber-50 focus:border-amber-500 focus:ring-amber-500 px-4 py-2.5 text-center tracking-[0.5em] text-lg font-bold">
                        </div>
                        <button type="submit" class="btn-gradient w-full mt-2">Catat Tamu Masuk</button>
                    </form>
                    <div id="guestbook-result" class="mt-4"></div>
                </div>

                <!-- FORM: Cek NIK -->
                <div x-show="modal === 'cek-nik'">
                    <form onsubmit="return submitForm(event, 'cek-nik-form', '{{ route('cek-nik', ['tenant' => $tenant->slug]) }}', 'GET')" id="cek-nik-form">
                        <label class="label">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field" placeholder="Masukkan 16 digit NIK">
                        <button type="submit" class="btn-gradient w-full mt-6">Cek Database</button>
                    </form>
                    <div id="cek-nik-result" class="mt-4"></div>
                </div>

                <!-- FORM: Panic Button -->
                <div x-show="modal === 'trigger-panic'">
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-sm mb-4">
                        <b>Peringatan:</b> Gunakan fitur ini hanya dalam keadaan darurat sesungguhnya (kejahatan, medis, kebakaran, dll). Laporan palsu dapat ditindaklanjuti.
                    </div>
                    <form onsubmit="return submitForm(event, 'trigger-panic-form', '{{ route('trigger-panic', ['tenant' => $tenant->slug]) }}', 'POST')" id="trigger-panic-form" class="space-y-4">
                        <div>
                            <label class="label text-rose-600">Nama Pelapor</label>
                            <input type="text" name="reporter_name" required class="input-field focus:ring-rose-500" placeholder="Nama Anda">
                        </div>
                        <div>
                            <label class="label text-rose-600">No HP / WhatsApp (Aktif)</label>
                            <input type="text" name="reporter_contact" required class="input-field focus:ring-rose-500" placeholder="Contoh: 08123456789">
                        </div>
                        <div>
                            <label class="label text-rose-600">Kategori Darurat</label>
                            <select name="type" required class="input-field focus:ring-rose-500">
                                <option value="Keamanan">Keamanan (Maling, Perampokan, dll)</option>
                                <option value="Medis">Darurat Medis / Ambulans</option>
                                <option value="Kebakaran">Kebakaran</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="label text-rose-600">Lokasi / Detail</label>
                            <textarea name="location" required rows="2" class="input-field focus:ring-rose-500" placeholder="Titik lokasi atau blok rumah"></textarea>
                        </div>
                        <button type="submit" class="w-full mt-2 bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                            🚨 KIRIM LAPORAN DARURAT
                        </button>
                    </form>
                    <div id="trigger-panic-result" class="mt-4"></div>
                </div>

                <!-- FORM: Ajukan Surat -->
                <div x-show="modal === 'ajukan-surat'">
                    <form onsubmit="return submitForm(event, 'ajukan-surat-form', '{{ route('ajukan-surat', ['tenant' => $tenant->slug]) }}', 'POST')" id="ajukan-surat-form" class="space-y-4">
                        <div>
                            <label class="label">NIK Pemohon</label>
                            <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field" placeholder="16 digit">
                        </div>
                        <div>
                            <label class="label">Jenis Pengantar</label>
                            <select name="jenis_surat" required class="input-field">
                                <option value="">Pilih surat</option>
                                <option>Surat Pengantar KTP</option>
                                <option>Surat Pengantar KK</option>
                                <option>Surat Pengantar SKCK</option>
                                <option>Surat Keterangan Domisili</option>
                                <option>Surat Keterangan Usaha</option>
                                <option>Surat Pengantar Nikah</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Keterangan / Keperluan</label>
                            <textarea name="keperluan" required rows="3" class="input-field" placeholder="Jelaskan untuk keperluan apa..."></textarea>
                        </div>
                        <button type="submit" class="btn-gradient w-full mt-2">Kirim Permohonan</button>
                    </form>
                    <div id="ajukan-surat-result" class="mt-4"></div>
                </div>

                <!-- FORM: Lapor Peristiwa -->
                <div x-show="modal === 'lapor-peristiwa'">
                    <form onsubmit="return submitFormData(event, 'lapor-peristiwa-form', '{{ route('lapor-peristiwa', ['tenant' => $tenant->slug]) }}')" id="lapor-peristiwa-form" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">NIK Pelapor (Keluarga)</label>
                                <input type="text" name="nik_subjek" required pattern="\d{16}" maxlength="16" class="input-field" placeholder="16 digit NIK">
                            </div>
                            <div>
                                <label class="label">Nama Subjek Peristiwa</label>
                                <input type="text" name="nama_subjek" required class="input-field" placeholder="Contoh: Budi Santoso / Bayi Laki-laki">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Kategori</label>
                                <select name="jenis_laporan" class="input-field" required>
                                    <option value="Kelahiran">Kelahiran</option>
                                    <option value="Kematian">Kematian</option>
                                    <option value="Pindah Masuk">Pindah Masuk</option>
                                    <option value="Pindah Keluar">Pindah Keluar</option>
                                </select>
                            </div>
                            <div>
                                <label class="label">Tanggal Peristiwa</label>
                                <input type="date" name="tanggal_kejadian" required class="input-field">
                            </div>
                        </div>
                        <div>
                            <label class="label">Deskripsi Lengkap</label>
                            <textarea name="keterangan" rows="2" required class="input-field resize-none" placeholder="Contoh: Telah lahir anak laki-laki / Warga pindah ke Jakarta Selatan"></textarea>
                        </div>
                        <div>
                            <label class="label">Dokumen Pendukung (Foto SKL/SKK)</label>
                            <input type="file" name="foto" accept="image/*" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-colors">
                        </div>
                        <button type="submit" class="btn-gradient w-full mt-4" style="background: linear-gradient(135deg, #7c3aed, #c026d3);">Kirim Laporan Peristiwa</button>
                    </form>
                    <div id="lapor-peristiwa-result" class="mt-4"></div>
                </div>
                <!-- FORM: Cek Iuran -->
                <div x-show="modal === 'cek-iuran'">
                    <form onsubmit="return submitForm(event, 'cek-iuran-form', '{{ route('cek-iuran', ['tenant' => $tenant->slug]) }}', 'GET')" id="cek-iuran-form">
                        <label class="label">Masukkan NIK</label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field" placeholder="16 digit NIK terdaftar">
                        <button type="submit" class="btn-gradient w-full mt-6">Tampilkan Riwayat</button>
                    </form>
                    <div id="cek-iuran-result" class="mt-4"></div>
                </div>

                <!-- FORM: Kirim Laporan -->
                <div x-show="modal === 'kirim-laporan'" x-data="{ role: 'warga' }">
                    <form onsubmit="return submitFormData(event, 'kirim-laporan-form', '{{ route('kirim-laporan', ['tenant' => $tenant->slug]) }}')" id="kirim-laporan-form" class="space-y-4">
                        
                        <div class="flex p-1.5 bg-slate-100 rounded-xl mb-4">
                            <button type="button" @click="role = 'warga'" :class="role === 'warga' ? 'bg-white shadow-sm font-bold text-slate-900' : 'text-slate-500 font-medium hover:text-slate-700'" class="flex-1 py-2 text-sm rounded-lg transition-all">Warga Terdaftar</button>
                            <button type="button" @click="role = 'guest'" :class="role === 'guest' ? 'bg-white shadow-sm font-bold text-slate-900' : 'text-slate-500 font-medium hover:text-slate-700'" class="flex-1 py-2 text-sm rounded-lg transition-all">Tamu / Umum</button>
                        </div>

                        <div x-show="role === 'warga'" x-transition>
                            <label class="label">NIK Warga</label>
                            <input type="text" name="nik" :required="role === 'warga'" pattern="\d{16}" maxlength="16" class="input-field" placeholder="Masukkan NIK">
                        </div>
                        
                        <div x-show="role === 'guest'" style="display: none;" class="space-y-4" x-transition>
                            <div>
                                <label class="label">Nama Lengkap</label>
                                <input type="text" name="reporter_name" :required="role === 'guest'" class="input-field">
                            </div>
                            <div>
                                <label class="label">No. WhatsApp</label>
                                <input type="text" name="reporter_phone" :required="role === 'guest'" class="input-field">
                            </div>
                        </div>

                        <div>
                            <label class="label">Kategori Laporan</label>
                            <select name="kategori" required class="input-field">
                                <option value="">Pilih masalah</option>
                                <option>Keamanan (Maling, dll)</option>
                                <option>Kebersihan (Sampah)</option>
                                <option>Fasilitas (Lampu Padam, Jalan Rusak)</option>
                                <option>Darurat</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Isi Keluhan</label>
                            <textarea name="laporan" required rows="3" class="input-field" placeholder="Jelaskan detailnya..."></textarea>
                        </div>
                        <div>
                            <label class="label">Foto Bukti (Opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="w-full text-sm font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <button type="submit" class="btn-gradient w-full mt-2">Submit Laporan</button>
                    </form>
                    <div id="kirim-laporan-result" class="mt-4"></div>
                </div>

                <!-- FORM: Cek Tiket Laporan -->
                <div x-show="modal === 'cek-laporan'">
                    <form onsubmit="return submitForm(event, 'cek-laporan-form', '{{ route('cek-laporan', ['tenant' => $tenant->slug]) }}', 'GET')" id="cek-laporan-form">
                        <label class="label">Nomor Tiket (Ticket ID)</label>
                        <input type="text" name="ticket_number" required class="input-field font-mono text-center tracking-widest text-lg uppercase" placeholder="TICKET-XXXX">
                        <button type="submit" class="btn-gradient w-full mt-6" style="background: linear-gradient(135deg, #d97706, #f59e0b);">Cek Status Penanganan</button>
                    </form>
                    <div id="cek-laporan-result" class="mt-4"></div>
                </div>

                <!-- FORM: Cek Status Surat -->
                <div x-show="modal === 'cek-surat'">
                    <form onsubmit="return submitForm(event, 'cek-surat-form', '{{ route('cek-surat', ['tenant' => $tenant->slug]) }}', 'GET')" id="cek-surat-form">
                        <label class="label">Masukkan NIK Pemohon</label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field text-center tracking-wider font-semibold text-lg" placeholder="16 digit NIK terdaftar">
                        <button type="submit" class="btn-gradient w-full mt-6" style="background: linear-gradient(135deg, #7c3aed, #8b5cf6);">Lacak Pengajuan Surat</button>
                    </form>
                    <div id="cek-surat-result" class="mt-4"></div>
                </div>

                <!-- FORM: Titip Rumah Kosong -->
                <div x-show="modal === 'titip-rumah'" x-data="{ activeTab: 'lapor' }">
                    <div class="flex bg-slate-100 p-1 rounded-xl mb-6">
                        <button @click="activeTab = 'lapor'" :class="{'bg-white shadow-sm text-indigo-700': activeTab === 'lapor', 'text-slate-500 hover:text-slate-700': activeTab !== 'lapor'}" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Form Lapor Baru</button>
                        <button @click="activeTab = 'cek'" :class="{'bg-white shadow-sm text-indigo-700': activeTab === 'cek', 'text-slate-500 hover:text-slate-700': activeTab !== 'cek'}" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">Cek Status Laporan</button>
                    </div>

                    <div x-show="activeTab === 'lapor'">
                        <form method="POST" action="{{ route('titip-rumah', ['tenant' => $tenant->slug]) }}" class="space-y-4">
                            @csrf
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-2">
                                <p class="text-xs text-indigo-800 leading-relaxed font-medium">Laporkan rumah kosong saat Anda mudik atau dinas luar kota. Keamanan RT akan memantau keamanan rumah Anda secara rutin dan melapor ke aplikasi.</p>
                            </div>
                            
                            <div>
                                <label class="label">Nama Lengkap Pemilik/Pelapor</label>
                                <input type="text" name="pelapor_nama" required class="input-field" placeholder="Nama Anda...">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="label">Alamat / Blok Rumah</label>
                                    <input type="text" name="alamat_rumah" required class="input-field" placeholder="Contoh: Blok B No 12">
                                </div>
                                <div class="col-span-2">
                                    <label class="label">Kontak Darurat (WhatsApp)</label>
                                    <input type="text" name="nomor_wa" required minlength="11" pattern="[0-9]+" title="Masukkan minimal 11 angka" class="input-field" placeholder="08...">
                                </div>
                                <div>
                                    <label class="label">Tgl Pergi</label>
                                    <input type="date" name="tanggal_pergi" required class="input-field" min="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label class="label">Rencana Pulang</label>
                                    <input type="date" name="tanggal_pulang" required class="input-field" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div>
                                <label class="label">Catatan Tambahan (Opsional)</label>
                                <textarea name="catatan_warga" rows="2" class="input-field" placeholder="Contoh: Kunci pagar titip ke Pak Satpam, dll..."></textarea>
                            </div>
                            <button type="submit" class="btn-gradient w-full mt-2" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">Kirim Laporan Titip Rumah</button>
                        </form>
                    </div>

                    <div x-show="activeTab === 'cek'">
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4">
                            <p class="text-xs text-slate-600 leading-relaxed font-medium">Masukkan nomor WhatsApp yang Anda gunakan saat mendaftar penitipan rumah untuk melacak status dan melihat log patroli dari satpam.</p>
                        </div>
                        <form onsubmit="return submitForm(event, 'cek-rumah-form', '{{ route('titip-rumah.track', ['tenant' => $tenant->slug]) }}', 'GET')" id="cek-rumah-form" class="space-y-4">
                            <div>
                                <label class="label">Nomor WhatsApp Anda</label>
                                <input type="text" name="nomor_wa" required minlength="11" pattern="[0-9]+" title="Masukkan minimal 11 angka" class="input-field" placeholder="08...">
                            </div>
                            <button type="submit" class="btn-gradient w-full mt-2" style="background: linear-gradient(135deg, #059669, #10b981);">Cek Log Patroli Satpam</button>
                        </form>
                        <div id="cek-rumah-result" class="mt-4"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FLOATING PANIC BUTTON (REDESIGNED FOR DANGER) -->
    <div class="fixed bottom-6 right-6 md:bottom-10 md:right-10 z-40 flex flex-col items-center gap-2">
        <!-- Floating label -->
        <div class="bg-rose-900 text-white text-[10px] md:text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg animate-bounce border border-rose-400">
            Tekan Jika Darurat!
        </div>
        
        <button @click.prevent.stop="modal = 'trigger-panic'" class="btn-panic w-20 h-20 md:w-24 md:h-24 bg-gradient-to-b from-rose-500 to-rose-700 hover:from-rose-600 hover:to-rose-800 text-white rounded-full flex flex-col items-center justify-center shadow-[0_10px_40px_rgba(225,29,72,0.6)] border-4 border-white transition-transform hover:scale-110 active:scale-95 group relative overflow-hidden" title="Tombol Darurat (Panic Button)">
            
            <!-- Diagonal hazard stripes effect inside button (subtle) -->
            <div class="absolute inset-0 opacity-10" style="background: repeating-linear-gradient(45deg, transparent, transparent 10px, #000 10px, #000 20px);"></div>
            
            <svg class="w-8 h-8 md:w-10 md:h-10 mb-0.5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="text-[11px] md:text-xs font-black uppercase tracking-widest relative z-10">SOS</span>
        </button>
    </div>



    <!-- FOOTER -->
    @include('partials.public-footer')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showResult(boxId, ok, message) {
            const box = document.getElementById(boxId);
            box.innerHTML = `<div class="p-4 rounded-2xl ${ok ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'} text-sm font-medium leading-relaxed">${message}</div>`;
        }

        async function submitForm(event, formId, url, method) {
            event.preventDefault();
            const form = document.getElementById(formId);
            const btn = form.querySelector('button[type="submit"]');
            const ogText = btn.innerHTML;
            btn.innerHTML = 'Memproses...'; btn.disabled = true;
            
            const resultBox = formId.replace('-form', '-result');
            const data = new FormData(form);

            try {
                let res;
                if (method === 'GET') {
                    const params = new URLSearchParams(data).toString();
                    res = await fetch(`${url}?${params}`, { headers: { 'Accept': 'application/json' } });
                } else {
                    res = await fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: data });
                }
                const json = await res.json();
                renderResult(resultBox, json);
                if(json.success) form.reset();
            } catch (e) {
                showResult(resultBox, false, 'Koneksi terputus. Silakan coba lagi.');
            }
            btn.innerHTML = ogText; btn.disabled = false;
            return false;
        }

        async function submitFormData(event, formId, url) {
            event.preventDefault();
            const form = document.getElementById(formId);
            const btn = form.querySelector('button[type="submit"]');
            const ogText = btn.innerHTML;
            btn.innerHTML = 'Memproses...'; btn.disabled = true;
            
            const resultBox = formId.replace('-form', '-result');
            const data = new FormData(form);

            try {
                const res = await fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: data });
                const json = await res.json();
                renderResult(resultBox, json);
                if (json.success || json.found !== undefined) form.reset();
            } catch (e) {
                showResult(resultBox, false, 'Gagal mengunggah data. Pastikan ukuran foto tidak terlalu besar.');
            }
            btn.innerHTML = ogText; btn.disabled = false;
            return false;
        }

        function renderResult(resultBox, json) {
            if (resultBox.startsWith('vote-result-') && json.success && json.options) {
                let optionsHtml = json.options.map(opt => {
                    return `
                        <div class="space-y-1 mb-3">
                            <div class="flex justify-between text-xs font-bold text-slate-700">
                                <span>${opt.text}</span>
                                <span>${opt.percentage}% (${opt.votes} suara)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000 ease-out" style="width: 0%;" x-init="setTimeout(() => $el.style.width = '${opt.percentage}%', 100)"></div>
                            </div>
                        </div>
                    `;
                }).join('');

                document.getElementById(resultBox).innerHTML = `
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl mt-4 text-left">
                        <div class="text-xs font-black text-indigo-800 uppercase tracking-widest mb-3">Hasil Voting Sementara:</div>
                        <div class="space-y-3">${optionsHtml}</div>
                        <p class="text-[10px] text-slate-400 font-bold mt-3 text-right">Total: ${json.total_votes} suara masuk</p>
                    </div>
                `;
                return;
            }

            if (json.found !== undefined) {
                if (json.found) {
                    showResult(resultBox, true, `<div class="font-bold text-lg mb-1">${json.nama}</div> <div class="opacity-80">Terverifikasi sebagai warga (Status: ${json.status})</div>`);
                } else {
                    showResult(resultBox, false, '<b>Gagal:</b> NIK tidak ditemukan dalam database RT ini.');
                }
                return;
            }
            
            if (json.data && Array.isArray(json.data)) {
                if (resultBox === 'cek-iuran-result') {
                    let arrearsHtml = '';
                    if (json.arrears && json.arrears.months_owed > 0) {
                        arrearsHtml = `
                            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-950 text-sm">
                                <div class="flex items-center gap-2 mb-2 text-rose-800 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    TUNGGAKAN PIUTANG KAS RT
                                </div>
                                <p class="mb-2 leading-relaxed font-medium">Anda memiliki <b>${json.arrears.months_owed} bulan</b> tunggakan di tahun ini sebesar <span class="font-black text-rose-700">${json.arrears.total_arrears}</span> (Tarif RT: ${json.arrears.nominal_iuran}/bln).</p>
                                <div class="text-xs opacity-90 leading-relaxed font-semibold">
                                    Bulan tertunggak: <span class="underline decoration-wavy decoration-rose-300 font-bold text-rose-800">${json.arrears.unpaid_months.join(', ')}</span>
                                </div>
                            </div>
                        `;
                    } else if (json.arrears) {
                        arrearsHtml = `
                            <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Luar Biasa! Pembayaran Iuran Keluarga Anda Lunas Bulan Ini.
                            </div>
                        `;
                    }

                    let rows = json.data.map(d => `<div class="flex justify-between border-b border-emerald-100/60 py-2"><span class="opacity-80">${d.jenis} (${d.periode})</span><span class="font-bold text-slate-800">Rp ${d.jumlah}</span></div>`).join('');
                    
                    document.getElementById(resultBox).innerHTML = `
                        <div class="p-5 bg-white rounded-3xl border border-slate-200 text-slate-700 text-sm text-left">
                            <div class="font-black text-base text-slate-900 mb-3 pb-3 border-b border-slate-100">Rekap Kas & Iuran: ${json.nama}</div>
                            ${arrearsHtml}
                            <div class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-2">5 Pembayaran Terakhir:</div>
                            <div class="space-y-1">${rows || '<div class="text-slate-400 text-xs italic">Belum ada riwayat pembayaran.</div>'}</div>
                        </div>
                    `;
                    return;
                }

                if (resultBox === 'cek-surat-result') {
                    if (json.data.length === 0) {
                        showResult(resultBox, false, 'Belum ada pengajuan surat resmi untuk NIK tersebut.');
                        return;
                    }
                    
                    let suratListHtml = json.data.map((s, idx) => {
                        let statusColor = s.status === 'Pending' ? 'bg-amber-100 text-amber-700' : (s.status === 'Diproses' ? 'bg-indigo-100 text-indigo-700' : (s.status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'));
                        
                        let timelineHtml = s.timeline.map(t => `
                            <div class="flex gap-2 items-start mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5"></div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-700 font-semibold leading-normal">${t.pesan}</p>
                                    <span class="text-[9px] text-slate-400 font-medium">${t.waktu}</span>
                                </div>
                            </div>
                        `).join('');

                        return `
                            <div class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-slate-900">${s.jenis_surat}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${statusColor}">${s.status}</span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3 font-medium">Keperluan: ${s.keperluan}</p>
                                <div class="border-t border-slate-200/60 pt-3">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Riwayat Proses:</span>
                                    ${timelineHtml}
                                </div>
                            </div>
                        `;
                    }).join('');

                    document.getElementById(resultBox).innerHTML = `
                        <div class="p-5 bg-white rounded-3xl border border-slate-200 text-left">
                            <div class="font-black text-base text-slate-900 mb-3 pb-3 border-b border-slate-100">Status Surat: ${json.nama}</div>
                            <div class="max-h-[50vh] overflow-y-auto pr-1">${suratListHtml}</div>
                        </div>
                    `;
                    return;
                }
            }

            if (json.data && json.data.timeline && Array.isArray(json.data.timeline)) {
                let timelineHtml = json.data.timeline.map(t => {
                    let attachmentHtml = t.attachment_url ? `
                        <div class="mt-2">
                            <a href="${t.attachment_url}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Foto Lampiran
                            </a>
                        </div>
                    ` : '';

                    if (t.is_system) {
                        return `
                        <div class="flex items-center gap-3 justify-center my-5">
                            <div class="h-px bg-slate-100 flex-1"></div>
                            <span class="text-[10px] font-bold text-slate-400 tracking-wide flex items-center gap-1.5 bg-slate-50 px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                ${t.pesan} &bull; ${t.waktu.split(', ')[1]}
                            </span>
                            <div class="h-px bg-slate-100 flex-1"></div>
                        </div>`;
                    } else if (t.is_admin) {
                        // Admin Reply (Left bubble)
                        return `
                        <div class="flex gap-3 mt-4 w-full">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 border border-indigo-200 flex-shrink-0 flex items-center justify-center text-indigo-700 font-bold text-xs shadow-sm">RT</div>
                            <div class="flex flex-col max-w-[85%]">
                                <div class="bg-white rounded-2xl rounded-tl-none px-4 py-3 text-sm text-slate-700 shadow-sm border border-slate-200">
                                    <p class="text-[10px] font-bold text-indigo-600 mb-1 opacity-90">${t.sender}</p>
                                    <p class="whitespace-pre-wrap">${t.pesan}</p>
                                    ${attachmentHtml}
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold mt-1 block ml-1">${t.waktu}</span>
                            </div>
                        </div>`;
                    } else {
                        // Warga Reply (Right bubble)
                        let rightAttachment = t.attachment_url ? `
                            <div class="mt-2">
                                <a href="${t.attachment_url}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-indigo-700/50 border border-indigo-400 rounded-lg text-[10px] font-bold text-indigo-50 hover:bg-indigo-700 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Foto Lampiran
                                </a>
                            </div>
                        ` : '';
                        
                        return `
                        <div class="flex gap-3 justify-end ml-auto max-w-[90%] mt-4">
                            <div class="flex flex-col items-end">
                                <div class="bg-indigo-600 shadow-sm rounded-2xl rounded-tr-none px-4 py-3 text-sm text-white border border-indigo-500">
                                    <p class="whitespace-pre-wrap">${t.pesan}</p>
                                    ${rightAttachment}
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold mt-1.5 block mr-1">${t.waktu}</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-500 font-bold text-xs shadow-sm border border-slate-200">W</div>
                        </div>`;
                    }
                }).join('');
                
                let statusColor = json.data.status === 'MENUNGGU' ? 'bg-amber-100 text-amber-700' : (json.data.status === 'DIPROSES' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700');
                
                let replyFormHtml = ``;
                if(json.data.status !== 'Selesai' && json.data.status !== 'Ditolak' && !json.data.no_reply_form) {
                    replyFormHtml = `
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <form onsubmit="return submitBalasan(event, '${json.data.ticket_number}')" id="form-balasan-${json.data.ticket_number}" class="flex gap-2 items-center bg-slate-50 p-1.5 rounded-xl border border-slate-200 focus-within:border-indigo-400 focus-within:ring-1 focus-within:ring-indigo-400 transition-all">
                                <input type="text" name="message" required placeholder="Ketik balasan..." class="w-full text-sm bg-transparent border-0 focus:ring-0 px-2 py-1 placeholder-slate-400">
                                <div class="relative group cursor-pointer flex-shrink-0">
                                    <input type="file" name="attachment" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Lampirkan Foto">
                                    <div class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </div>
                                </div>
                                <button type="submit" class="w-8 h-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: translateX(1px);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </form>
                            <div id="result-balasan-${json.data.ticket_number}" class="mt-2 text-xs"></div>
                        </div>
                    `;
                }

                document.getElementById(resultBox).innerHTML = `
                    <div class="p-5 bg-white shadow-sm rounded-2xl border border-slate-200 mt-2 text-left">
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-slate-100">
                            <span class="font-black text-slate-800">Perkembangan Laporan</span>
                            <span class="px-2.5 py-1 ${statusColor} text-xs font-black uppercase rounded-md tracking-wider">${json.data.status}</span>
                        </div>
                        <div class="pt-4 pb-2 px-3 max-h-[50vh] overflow-y-auto custom-scrollbar mb-2 bg-slate-50 rounded-2xl border border-slate-100 shadow-inner">
                            ${timelineHtml}
                        </div>
                        ${replyFormHtml}
                    </div>
                `;
                return;
            }

            showResult(resultBox, !!json.success, json.message || 'Data berhasil diproses.');
            
            if (json.whatsapp_url) {
                setTimeout(() => {
                    window.location.href = json.whatsapp_url;
                }, 1500);
            }
        }

        async function submitBalasan(event, ticketNumber) {
            event.preventDefault();
            const form = event.target;
            const btn = form.querySelector('button[type="submit"]');
            const ogContent = btn.innerHTML;
            btn.innerHTML = '...'; btn.disabled = true;
            
            const resultBox = document.getElementById(`result-balasan-${ticketNumber}`);
            resultBox.innerHTML = '<span class="text-indigo-600">Mengirim...</span>';
            
            const data = new FormData(form);
            data.append('ticket_number', ticketNumber);

            try {
                const url = '{{ route('balas-laporan', ['tenant' => $tenant->slug]) }}';
                const res = await fetch(url, { 
                    method: 'POST', 
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
                    body: data 
                });
                const json = await res.json();
                
                if (json.success) {
                    resultBox.innerHTML = '<span class="text-emerald-600 font-bold">Terkirim! Memuat ulang...</span>';
                    form.reset();
                    // Reload the chat by calling submitForm directly
                    setTimeout(() => {
                        submitForm({ preventDefault: () => {} }, 'cek-laporan-form', '{{ route('cek-laporan', ['tenant' => $tenant->slug]) }}', 'GET');
                    }, 500);
                } else {
                    resultBox.innerHTML = `<span class="text-rose-600 font-bold">Gagal: ${json.message || 'Terjadi kesalahan'}</span>`;
                }
            } catch (e) {
                resultBox.innerHTML = '<span class="text-rose-600 font-bold">Gagal: Koneksi terputus.</span>';
            }
            
            btn.innerHTML = ogContent; btn.disabled = false;
            return false;
        }

        async function submitAbsenRonda(event) {
            event.preventDefault();
            const form = event.target;
            const btn = form.querySelector('button[type="submit"]');
            const btnSpan = btn.querySelector('span');
            const loading = document.getElementById('ronda-loading');
            
            const ogContent = btnSpan.innerText;
            btnSpan.innerText = 'Mengirim...';
            btn.disabled = true;
            loading.classList.remove('hidden');
            
            // Try to get GPS location silently
            try {
                const position = await new Promise((resolve, reject) => {
                    if (!navigator.geolocation) {
                        reject(new Error('Geolocation not supported'));
                    } else {
                        navigator.geolocation.getCurrentPosition(resolve, reject, { timeout: 3000 });
                    }
                });
                // Append coordinates to the location field
                const locInput = form.querySelector('input[name="location"]');
                if (locInput) {
                    locInput.value = locInput.value + ` (${position.coords.latitude}, ${position.coords.longitude})`;
                }
            } catch (err) {
                // Ignore GPS errors and proceed with whatever text they typed
            }

            const data = new FormData(form);

            try {
                const url = '{{ route('absen-ronda', ['tenant' => $tenant->slug]) }}';
                const res = await fetch(url, { 
                    method: 'POST', 
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
                    body: data 
                });
                const json = await res.json();
                
                if (json.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: json.message || 'Absen Kehadiran Tercatat.',
                        icon: 'success',
                        confirmButtonText: 'Selesai',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: json.message || 'Terjadi kesalahan.',
                        icon: 'error',
                        confirmButtonColor: '#e11d48'
                    });
                }
            } catch (e) {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Koneksi terputus. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#e11d48'
                });
            }
            
            btnSpan.innerText = ogContent; 
            btn.disabled = false;
            loading.classList.add('hidden');
            return false;
        }

        // Intersection Observer for scroll animations
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        @if(count($chart_labels))
        new Chart(document.getElementById('expenseChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chart_labels) !!},
                datasets: [{
                    data: {!! json_encode($chart_values) !!},
                    backgroundColor: ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6'],
                    borderWidth: 0,
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '75%', 
                plugins: { 
                    legend: { display: false } 
                } 
            }
        });
        @endif
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    
    <!-- Load EZUIKIT for EZVIZ CCTV -->
    <script src="https://ezvizlife.com/ezui/ezuikit.js"></script>
</body>
</html>
