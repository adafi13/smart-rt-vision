<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Portal resmi warga — statistik, transparansi kas, dan layanan mandiri RT.">
    <title>{{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }} · Portal Warga</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    <section id="hero" class="relative min-h-[100dvh] flex items-center overflow-hidden bg-slate-900">
        <!-- Abstract Animated Background -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="ambient-glow w-[600px] h-[600px] bg-indigo-600/40 -top-[200px] -right-[100px] animate-float"></div>
            <div class="ambient-glow w-[500px] h-[500px] bg-purple-600/30 bottom-[100px] -left-[100px] animate-float-delayed"></div>
            <div class="ambient-glow w-[400px] h-[400px] bg-cyan-500/20 top-[30%] left-[40%] animate-float" style="animation-duration: 9s;"></div>
            
            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNykiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-5 sm:px-6 text-center py-20 sm:py-24 mt-10">
            <!-- Premium Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold text-indigo-100 border border-indigo-400/20 bg-white/5 backdrop-blur-md mb-8 shadow-2xl">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Portal Cerdas Warga &middot; {{ strtoupper($tenant->name ?? config('app.name', 'SmartRT Vision')) }}</span>
            </div>
            
            <!-- Hero Typography -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-[1.1] tracking-tight text-white mb-6">
                Sistem Layanan Mandiri <br class="hidden sm:block"/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-cyan-400 drop-shadow-sm">{{ $tenant->name ?? 'Lingkungan RT/RW' }}</span>
            </h1>
            <p class="text-base sm:text-xl text-slate-300 max-w-2xl mx-auto font-medium leading-relaxed mb-10">
                Lupakan cara lama. Pantau kas RT secara real-time, ajukan surat pengantar, laporkan keluhan, dan cek jadwal ronda dari genggaman tangan Anda.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#layanan" class="btn-gradient w-full sm:w-auto">
                    <span>Jelajahi Layanan Warga</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </a>
                <a href="#kas" class="btn-outline-glow w-full sm:w-auto">
                    Buka Laporan Kas
                </a>
            </div>

            <!-- Floating Stats Bar -->
            <div class="mt-16 sm:mt-24 mx-auto inline-flex flex-wrap items-center justify-center gap-6 sm:gap-10 px-8 py-5 rounded-3xl glass-dark shadow-2xl">
                <div class="text-left">
                    <p class="text-2xl sm:text-3xl font-black text-white">{{ number_format($total_kk) }}</p>
                    <p class="text-xs text-indigo-200 font-semibold uppercase tracking-wider mt-1">Kartu Keluarga</p>
                </div>
                <div class="w-px h-12 bg-white/10 hidden sm:block"></div>
                <div class="text-left">
                    <p class="text-2xl sm:text-3xl font-black text-white">{{ number_format($total_warga) }}</p>
                    <p class="text-xs text-indigo-200 font-semibold uppercase tracking-wider mt-1">Total Penduduk</p>
                </div>
                <div class="w-px h-12 bg-white/10 hidden md:block"></div>
                <div class="text-left hidden md:block">
                    <div class="flex items-center gap-2">
                        <p class="text-2xl sm:text-3xl font-black text-white whitespace-nowrap">Rp {{ number_format($saldo_kas, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-xs text-emerald-300 font-semibold uppercase tracking-wider mt-1">Saldo Kas Aktif</p>
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
                ['modal'=>'cek-iuran','title'=>'Riwayat Pembayaran','desc'=>'Pantau riwayat pembayaran iuran kebersihan/keamanan Anda.','color'=>'#059669','bg'=>'bg-emerald-50','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 8v2'],
                ['modal'=>'kirim-laporan','title'=>'Pusat Bantuan & Keluhan','desc'=>'Laporkan masalah infrastruktur, keamanan, atau kebersihan.','color'=>'#dc2626','bg'=>'bg-rose-50','icon'=>'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z'],
                ['modal'=>'cek-laporan','title'=>'Lacak Tiket Bantuan','desc'=>'Cek status penyelesaian laporan yang telah Anda kirimkan.','color'=>'#d97706','bg'=>'bg-amber-50','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['modal'=>'lapor-peristiwa','title'=>'Catatan Sipil Warga','desc'=>'Laporkan peristiwa kelahiran, kematian, atau warga pindah.','color'=>'#7c3aed','bg'=>'bg-purple-50','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
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

    <!-- ===================== MODALS ( ALPINE ) ===================== -->
    <template x-if="modal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-transition.opacity style="background: rgba(15,23,42,0.8); backdrop-filter: blur(8px);">
            <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 max-h-[90vh] overflow-y-auto shadow-2xl relative" @click.away="modal = null" x-transition.scale.95>
                
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
                        'cek-iuran': 'Cek Riwayat Iuran',
                        'kirim-laporan': 'Lapor Keluhan',
                        'cek-laporan': 'Lacak Tiket',
                        'lapor-peristiwa': 'Catat Peristiwa',
                    }[modal]"></h3>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Lengkapi form di bawah ini untuk melanjutkan.</p>
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

            </div>
        </div>
    </template>

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
            if (json.found !== undefined) {
                if (json.found) {
                    showResult(resultBox, true, `<div class="font-bold text-lg mb-1">${json.nama}</div> <div class="opacity-80">Terverifikasi sebagai warga (Status: ${json.status})</div>`);
                } else {
                    showResult(resultBox, false, '<b>Gagal:</b> NIK tidak ditemukan dalam database RT ini.');
                }
                return;
            }
            
            if (json.data && Array.isArray(json.data)) {
                if (json.data.length === 0) {
                    showResult(resultBox, false, 'Belum ada catatan iuran untuk NIK tersebut.');
                    return;
                }
                let rows = json.data.map(d => `<div class="flex justify-between border-b border-emerald-100 py-2"><span class="opacity-80">${d.jenis} (${d.periode})</span><span class="font-bold">Rp ${d.jumlah}</span></div>`).join('');
                document.getElementById(resultBox).innerHTML = `<div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-900 text-sm"><div class="font-bold text-base mb-3">Rekap Iuran ${json.nama}</div>${rows}</div>`;
                return;
            }

            showResult(resultBox, !!json.success, json.message || 'Data berhasil diproses.');
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
</body>
</html>
