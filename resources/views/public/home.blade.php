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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { background: #f8f9fc; color: #0f172a; }

        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(16px) saturate(160%); -webkit-backdrop-filter: blur(16px) saturate(160%); }
        .glass-dark { background: rgba(15,15,25,0.55); backdrop-filter: blur(14px) saturate(160%); -webkit-backdrop-filter: blur(14px) saturate(160%); }

        .btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 12px 22px; border-radius: 12px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; box-shadow: 0 8px 20px -6px rgba(99,102,241,0.5); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px -6px rgba(99,102,241,0.6); }
        .btn-ghost { background: rgba(255,255,255,0.08); color: white; padding: 12px 22px; border-radius: 12px; font-size: 14px; font-weight: 600; border: 1px solid rgba(255,255,255,0.25); cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-ghost:hover { background: rgba(255,255,255,0.16); transform: translateY(-2px); }

        .input-field { width: 100%; padding: 11px 14px; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 14px; outline: none; background: white; color: #111827; transition: all 0.15s; }
        .input-field:focus { border-color: #818cf8; box-shadow: 0 0 0 4px rgba(99,102,241,0.12); }
        .label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }

        .card { background: white; border-radius: 24px; border: 1px solid #f1f1f4; box-shadow: 0 2px 10px -4px rgba(15,23,42,0.04); transition: all .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -16px rgba(79,70,229,0.18); }

        .action-card { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; padding: 20px; border-radius: 20px; border: 1px solid #f1f1f4; background: white; transition: all .25s cubic-bezier(.2,.8,.2,1); cursor: pointer; text-align: left; width: 100%; position: relative; overflow: hidden; }
        .action-card::after { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity .25s; background: radial-gradient(120px 120px at 20% 0%, rgba(99,102,241,0.10), transparent); }
        .action-card:hover { transform: translateY(-5px); box-shadow: 0 24px 40px -18px rgba(79,70,229,0.22); border-color: #e0e7ff; }
        .action-card:hover::after { opacity: 1; }

        .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1); }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        #hero3d-canvas { display: block; width: 100%; height: 100%; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        @keyframes bounce-y { 0%,100% { transform: translateY(0); } 50% { transform: translateY(8px); } }
        .bounce-y { animation: bounce-y 2s ease-in-out infinite; }
    </style>
</head>
<body x-data="{ modal: null }" @keydown.escape.window="modal = null">

    <!-- ===================== NAVBAR ===================== -->
    @include('partials.public-nav')

    <!-- ===================== HERO 3D ===================== -->
    <section id="hero" class="relative min-h-[100dvh] flex items-center overflow-hidden" style="background: radial-gradient(120% 100% at 50% 0%, #1e1b4b 0%, #0f0d24 55%, #0a0915 100%);">
        <canvas id="hero3d-canvas" class="absolute inset-0 pointer-events-none"></canvas>
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(60% 50% at 50% 100%, rgba(10,9,21,0.9), transparent);"></div>
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(55% 65% at 50% 42%, rgba(10,9,21,0.65) 0%, rgba(10,9,21,0.25) 55%, transparent 75%);"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-5 sm:px-6 text-center py-20 sm:py-24">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-[11px] sm:text-xs font-semibold text-indigo-200 border border-indigo-400/30 bg-indigo-500/10 mb-5 sm:mb-6 max-w-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                <span class="truncate">Portal Resmi Warga &middot; {{ \Illuminate\Support\Str::limit($tenant->name ?? config('app.name', 'SmartRT Vision'), 28) }}</span>
            </span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-[1.15] sm:leading-[1.1] tracking-tight text-white">
                Transparansi &amp; Layanan Warga,
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-cyan-300 bg-clip-text text-transparent">Lebih Mudah &amp; Cepat</span>
            </h1>
            <p class="mt-4 sm:mt-5 text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto">
                Cek status kependudukan, ajukan surat, pantau kas RT, dan laporkan keluhan — semua mandiri, tanpa perlu datang ke sekretariat.
            </p>
            <div class="mt-7 sm:mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#layanan" class="btn-primary justify-center">
                    Lihat Layanan Mandiri
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </a>
                <a href="#kas" class="btn-ghost justify-center">Lihat Transparansi Kas</a>
            </div>

            <div class="mt-10 sm:mt-12 inline-flex flex-wrap items-center justify-center gap-3 sm:gap-4 px-4 sm:px-5 py-3 rounded-2xl glass-dark border border-white/10 max-w-full">
                <div class="px-2 sm:px-3 text-left">
                    <p class="text-lg sm:text-xl font-extrabold text-white">{{ number_format($total_kk) }}</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-400 whitespace-nowrap">Kartu Keluarga</p>
                </div>
                <div class="w-px h-8 bg-white/10"></div>
                <div class="px-2 sm:px-3 text-left">
                    <p class="text-lg sm:text-xl font-extrabold text-white">{{ number_format($total_warga) }}</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-400 whitespace-nowrap">Warga Aktif</p>
                </div>
                <div class="w-px h-8 bg-white/10 hidden sm:block"></div>
                <div class="px-2 sm:px-3 text-left hidden sm:block">
                    <p class="text-lg sm:text-xl font-extrabold text-white whitespace-nowrap">Rp {{ number_format($saldo_kas, 0, ',', '.') }}</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-400">Saldo Kas RT</p>
                </div>
            </div>
        </div>

        <a href="#stats" class="absolute bottom-6 sm:bottom-8 inset-x-0 flex justify-center text-slate-500 bounce-y z-10">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
    </section>

    <!-- ===================== STATS ===================== -->
    <section id="stats" class="max-w-6xl mx-auto px-4 sm:px-6 pt-16 sm:pt-20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 reveal">
            @php $stats = [
                ['label'=>'Total KK','value'=>$total_kk,'color'=>'#6366f1','bg'=>'linear-gradient(135deg,#eef2ff,#e0e7ff)','icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3'],
                ['label'=>'Total Warga','value'=>$total_warga,'color'=>'#059669','bg'=>'linear-gradient(135deg,#ecfdf5,#d1fae5)','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label'=>'Laki-laki','value'=>$laki_laki,'color'=>'#2563eb','bg'=>'linear-gradient(135deg,#eff6ff,#dbeafe)','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label'=>'Perempuan','value'=>$perempuan,'color'=>'#db2777','bg'=>'linear-gradient(135deg,#fdf2f8,#fce7f3)','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ]; @endphp
            @foreach($stats as $s)
            <div class="card card-hover p-5 sm:p-6">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center mb-4" style="background: {{ $s['bg'] }};">
                    <svg class="w-5 h-5" style="color: {{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $s['icon'] }}"/></svg>
                </div>
                <p class="text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($s['value']) }}</p>
                <p class="text-xs sm:text-sm font-semibold text-gray-500 mt-1">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ===================== LAYANAN MANDIRI ===================== -->
    <section id="layanan" class="max-w-6xl mx-auto px-4 sm:px-6 py-20 sm:py-24">
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Layanan Mandiri</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Urus Keperluan Anda Sendiri</h2>
            <p class="text-sm text-gray-500 mt-2">Cukup masukkan NIK — tanpa perlu akun, tanpa perlu datang ke sekretariat.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 reveal">
            @php $actions = [
                ['modal'=>'cek-nik','label'=>'Cek Status NIK','color'=>'#6366f1','bg'=>'linear-gradient(135deg,#eef2ff,#e0e7ff)','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['modal'=>'ajukan-surat','label'=>'Ajukan Surat','color'=>'#0891b2','bg'=>'linear-gradient(135deg,#ecfeff,#cffafe)','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['modal'=>'cek-iuran','label'=>'Cek Iuran','color'=>'#059669','bg'=>'linear-gradient(135deg,#ecfdf5,#d1fae5)','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 8v2'],
                ['modal'=>'kirim-laporan','label'=>'Lapor Keluhan','color'=>'#dc2626','bg'=>'linear-gradient(135deg,#fef2f2,#fee2e2)','icon'=>'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z'],
                ['modal'=>'cek-laporan','label'=>'Cek Status Laporan','color'=>'#d97706','bg'=>'linear-gradient(135deg,#fffbeb,#fef3c7)','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['modal'=>'lapor-peristiwa','label'=>'Lapor Peristiwa','color'=>'#7c3aed','bg'=>'linear-gradient(135deg,#f5f3ff,#ede9fe)','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ]; @endphp
            @foreach($actions as $a)
            <button type="button" @click="modal = '{{ $a['modal'] }}'" class="action-card">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center relative z-10" style="background: {{ $a['bg'] }};">
                    <svg class="w-[18px] h-[18px]" style="color: {{ $a['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $a['icon'] }}"/></svg>
                </div>
                <span class="text-xs sm:text-sm font-bold text-gray-800 relative z-10">{{ $a['label'] }}</span>
            </button>
            @endforeach
        </div>
    </section>

    <!-- ===================== TRANSPARANSI KAS ===================== -->
    <section id="kas" class="max-w-6xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24">
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Transparansi</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Kas RT Terbuka untuk Warga</h2>
            <p class="text-sm text-gray-500 mt-2">Setiap pemasukan dan pengeluaran kas tercatat dan dapat dipantau bersama.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 reveal">
            <div class="card card-hover p-6" style="background: linear-gradient(135deg,#ffffff,#f0fdf4);">
                <p class="text-xs font-semibold text-gray-500">Total Pemasukan (Iuran)</p>
                <p class="text-2xl sm:text-3xl font-black text-emerald-600 break-all sm:break-normal mt-2">Rp {{ number_format($total_masuk, 0, ',', '.') }}</p>
            </div>
            <div class="card card-hover p-6" style="background: linear-gradient(135deg,#ffffff,#fff1f2);">
                <p class="text-xs font-semibold text-gray-500">Total Pengeluaran</p>
                <p class="text-2xl sm:text-3xl font-black text-rose-600 break-all sm:break-normal mt-2">Rp {{ number_format($total_keluar, 0, ',', '.') }}</p>
            </div>
            <div class="card card-hover p-6" style="background: linear-gradient(135deg,#ffffff,#eef2ff);">
                <p class="text-xs font-semibold text-gray-500">Saldo Kas Saat Ini</p>
                <p class="text-2xl sm:text-3xl font-black text-indigo-600 break-all sm:break-normal mt-2">Rp {{ number_format($saldo_kas, 0, ',', '.') }}</p>
            </div>
        </div>

        @if(count($chart_labels))
        <div class="card mt-4 p-6 reveal">
            <p class="text-xs font-bold text-gray-700 mb-4 uppercase tracking-wide">Komposisi Pengeluaran per Kategori</p>
            <div class="relative h-[240px] w-full max-w-md mx-auto">
                <canvas id="expenseChart"></canvas>
            </div>
        </div>
        @endif
    </section>

    <!-- ===================== WARTA ===================== -->
    <section id="warta" class="max-w-6xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24">
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Informasi</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Warta &amp; Pengumuman</h2>
        </div>
        @if($warta->count())
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 reveal">
            @foreach($warta as $n)
            <div class="card card-hover p-5 {{ $n->is_penting ? 'ring-2 ring-rose-200' : '' }}">
                @if($n->is_penting)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 mb-2">PENTING</span>
                @endif
                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wide">{{ $n->kategori }}</span>
                <p class="text-sm font-bold text-gray-900 mt-1.5">{{ $n->judul }}</p>
                <p class="text-xs text-gray-500 mt-1.5 line-clamp-3">{{ Str::limit($n->isi, 120) }}</p>
                <p class="text-[10px] text-gray-400 mt-3">{{ $n->created_at->translatedFormat('d F Y') }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="card p-10 text-center text-sm text-gray-400 reveal">
            Belum ada berita atau pengumuman saat ini.
        </div>
        @endif
    </section>

    <!-- ===================== UMKM ===================== -->
    <section id="umkm" class="max-w-6xl mx-auto px-4 sm:px-6 py-4 pb-20 sm:pb-24">
        <div class="text-center max-w-xl mx-auto mb-12 reveal">
            <span class="text-xs font-bold text-purple-600 uppercase tracking-widest">UMKM</span>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">Pasar Warga</h2>
            <p class="text-sm text-gray-500 mt-2">Dukung usaha tetangga sendiri.</p>
        </div>
        @if($produk->count())
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 reveal">
            @foreach($produk as $p)
            <div class="card card-hover overflow-hidden">
                <div class="h-28 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                    @if($p->foto)
                        <img src="{{ asset('storage/'.$p->foto) }}" class="w-full h-full object-cover" alt="{{ $p->nama_produk }}">
                    @else
                        <svg class="w-9 h-9 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    @endif
                </div>
                <div class="p-3.5">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ $p->nama_produk }}</p>
                    <p class="text-[11px] text-gray-500 truncate">{{ $p->penjual }}</p>
                    @if($p->harga)
                    <p class="text-xs font-bold text-indigo-600 mt-1.5">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    @endif
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->whatsapp) }}" target="_blank" class="mt-2.5 inline-flex items-center justify-center gap-1 w-full text-[11px] font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl py-2 transition-colors">
                        Hubungi WA
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="card p-10 text-center text-sm text-gray-400 reveal">
            Belum ada produk UMKM yang didaftarkan saat ini.
        </div>
        @endif
    </section>

    <!-- ===================== CTA BAND ===================== -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
        <div class="rounded-3xl p-8 sm:p-12 text-center relative overflow-hidden reveal" style="background: linear-gradient(135deg, #1e1b4b, #4338ca 60%, #6d28d9);">
            <h2 class="text-2xl sm:text-3xl font-black text-white">Ada keperluan administrasi?</h2>
            <p class="text-indigo-200 text-sm mt-2 max-w-md mx-auto">Ajukan surat atau laporkan keluhan langsung dari sini, tanpa antre.</p>
            <button type="button" @click="modal = 'ajukan-surat'" class="btn-primary mt-6 justify-center">Ajukan Surat Sekarang</button>
        </div>
    </section>

    <!-- ===================== FOOTER ===================== -->
    @include('partials.public-footer')

    <!-- ===================== MODALS ===================== -->
    <template x-if="modal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(10,9,21,0.6); backdrop-filter: blur(4px);" @click.self="modal = null">
            <div class="glass rounded-3xl w-full max-w-md p-5 sm:p-7 max-h-[90vh] overflow-y-auto shadow-2xl border border-white/40" @click.stop>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-extrabold text-gray-900" x-text="{
                        'cek-nik': 'Cek Status NIK',
                        'ajukan-surat': 'Ajukan Surat',
                        'cek-iuran': 'Cek Riwayat Iuran',
                        'kirim-laporan': 'Lapor Keluhan Warga',
                        'cek-laporan': 'Cek Status Laporan',
                        'lapor-peristiwa': 'Lapor Peristiwa (Lahir/Wafat)',
                    }[modal]"></h3>
                    <button @click="modal = null" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <!-- Cek NIK -->
                <div x-show="modal === 'cek-nik'">
                    <form onsubmit="return submitForm(event, 'cek-nik-form', '{{ route('cek-nik') }}', 'GET')" id="cek-nik-form">
                        <label class="label">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field" placeholder="16 digit NIK">
                        <button type="submit" class="btn-primary w-full justify-center mt-4">Cek Sekarang</button>
                    </form>
                    <div id="cek-nik-result" class="mt-4 text-sm"></div>
                </div>

                <!-- Ajukan Surat -->
                <div x-show="modal === 'ajukan-surat'">
                    <form onsubmit="return submitForm(event, 'ajukan-surat-form', '{{ route('ajukan-surat') }}', 'POST')" id="ajukan-surat-form" class="space-y-3">
                        <div>
                            <label class="label">NIK</label>
                            <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field">
                        </div>
                        <div>
                            <label class="label">Jenis Surat</label>
                            <select name="jenis_surat" required class="input-field">
                                <option value="">Pilih jenis surat</option>
                                <option>Surat Pengantar KTP</option>
                                <option>Surat Pengantar KK</option>
                                <option>Surat Pengantar SKCK</option>
                                <option>Surat Keterangan Domisili</option>
                                <option>Surat Keterangan Usaha</option>
                                <option>Surat Pengantar Nikah</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Keperluan</label>
                            <textarea name="keperluan" required maxlength="500" rows="3" class="input-field" placeholder="Jelaskan keperluan surat ini..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center">Kirim Permohonan</button>
                    </form>
                    <div id="ajukan-surat-result" class="mt-4 text-sm"></div>
                </div>

                <!-- Cek Iuran -->
                <div x-show="modal === 'cek-iuran'">
                    <form onsubmit="return submitForm(event, 'cek-iuran-form', '{{ route('cek-iuran') }}', 'GET')" id="cek-iuran-form">
                        <label class="label">NIK</label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field">
                        <button type="submit" class="btn-primary w-full justify-center mt-4">Cek Riwayat</button>
                    </form>
                    <div id="cek-iuran-result" class="mt-4 text-sm"></div>
                </div>

                <!-- Kirim Laporan -->
                <div x-show="modal === 'kirim-laporan'">
                    <form onsubmit="return submitFormData(event, 'kirim-laporan-form', '{{ route('kirim-laporan') }}')" id="kirim-laporan-form" class="space-y-3">
                        <div>
                            <label class="label">NIK</label>
                            <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field">
                        </div>
                        <div>
                            <label class="label">Kategori</label>
                            <select name="kategori" required class="input-field">
                                <option value="">Pilih kategori</option>
                                <option>Keamanan</option>
                                <option>Kebersihan</option>
                                <option>Fasilitas</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Isi Laporan</label>
                            <textarea name="laporan" required maxlength="1000" rows="3" class="input-field"></textarea>
                        </div>
                        <div>
                            <label class="label">Foto Bukti (maks 2MB)</label>
                            <input type="file" name="foto" accept="image/*" required class="input-field">
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center">Kirim Laporan</button>
                    </form>
                    <div id="kirim-laporan-result" class="mt-4 text-sm"></div>
                </div>

                <!-- Cek Laporan -->
                <div x-show="modal === 'cek-laporan'">
                    <form onsubmit="return submitForm(event, 'cek-laporan-form', '{{ route('cek-laporan') }}', 'GET')" id="cek-laporan-form">
                        <label class="label">NIK</label>
                        <input type="text" name="nik" required pattern="\d{16}" maxlength="16" class="input-field">
                        <button type="submit" class="btn-primary w-full justify-center mt-4">Cek Status</button>
                    </form>
                    <div id="cek-laporan-result" class="mt-4 text-sm"></div>
                </div>

                <!-- Lapor Peristiwa -->
                <div x-show="modal === 'lapor-peristiwa'">
                    <form onsubmit="return submitFormData(event, 'lapor-peristiwa-form', '{{ route('lapor-peristiwa') }}')" id="lapor-peristiwa-form" class="space-y-3">
                        <div>
                            <label class="label">Jenis Peristiwa</label>
                            <select name="jenis_laporan" required class="input-field">
                                <option value="">Pilih jenis</option>
                                <option>Kelahiran</option>
                                <option>Kematian</option>
                                <option>Pernikahan</option>
                                <option>Pindah Datang</option>
                                <option>Pindah Keluar</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Nama Subjek</label>
                            <input type="text" name="nama_subjek" required class="input-field">
                        </div>
                        <div>
                            <label class="label">NIK Subjek (jika ada)</label>
                            <input type="text" name="nik_subjek" maxlength="16" class="input-field">
                        </div>
                        <div>
                            <label class="label">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" required class="input-field">
                        </div>
                        <div>
                            <label class="label">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="input-field"></textarea>
                        </div>
                        <div>
                            <label class="label">Dokumen Pendukung (opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="input-field">
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center">Kirim Laporan</button>
                    </form>
                    <div id="lapor-peristiwa-result" class="mt-4 text-sm"></div>
                </div>
            </div>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showResult(boxId, ok, message) {
            const box = document.getElementById(boxId);
            box.innerHTML = `<div class="px-3 py-2.5 rounded-xl ${ok ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'}">${message}</div>`;
        }

        async function submitForm(event, formId, url, method) {
            event.preventDefault();
            const form = document.getElementById(formId);
            const resultBox = formId.replace('-form', '-result');
            const data = new FormData(form);

            try {
                let res;
                if (method === 'GET') {
                    const params = new URLSearchParams(data).toString();
                    res = await fetch(`${url}?${params}`, { headers: { 'Accept': 'application/json' } });
                } else {
                    res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: data,
                    });
                }
                const json = await res.json();
                renderResult(resultBox, json);
            } catch (e) {
                showResult(resultBox, false, 'Terjadi kesalahan koneksi. Coba lagi.');
            }
            return false;
        }

        async function submitFormData(event, formId, url) {
            event.preventDefault();
            const form = document.getElementById(formId);
            const resultBox = formId.replace('-form', '-result');
            const data = new FormData(form);

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: data,
                });
                const json = await res.json();
                renderResult(resultBox, json);
                if (json.success || json.found !== undefined) form.reset();
            } catch (e) {
                showResult(resultBox, false, 'Terjadi kesalahan koneksi. Coba lagi.');
            }
            return false;
        }

        function renderResult(resultBox, json) {
            if (json.found !== undefined) {
                if (json.found) {
                    showResult(resultBox, true, `Terdaftar sebagai warga: <strong>${json.nama}</strong> &middot; Status: ${json.status}`);
                } else {
                    showResult(resultBox, false, 'NIK tidak ditemukan dalam pendataan warga.');
                }
                return;
            }
            if (json.success && Array.isArray(json.data)) {
                if (json.data.length === 0) {
                    showResult(resultBox, true, `Halo <strong>${json.nama}</strong>, belum ada riwayat data.`);
                    return;
                }
                let rows = json.data.map(d => {
                    if (d.jenis !== undefined) {
                        return `<div class="flex justify-between border-b border-gray-100 py-1.5"><span>${d.jenis} (${d.periode})</span><span class="font-semibold">${d.jumlah}</span></div>`;
                    }
                    return `<div class="border-b border-gray-100 py-2"><p class="font-semibold">${d.kategori} &middot; ${d.status}</p><p class="text-gray-500 text-xs mt-0.5">${d.isi}</p><p class="text-gray-400 text-[11px] mt-1">${d.tanggapan}</p></div>`;
                }).join('');
                document.getElementById(resultBox).innerHTML = `<div class="text-xs font-semibold text-gray-700 mb-2">Halo, ${json.nama}</div>${rows}`;
                return;
            }
            showResult(resultBox, !!json.success, json.message || 'Permintaan diproses.');
        }

        // Scroll-reveal animations
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        @if(count($chart_labels))
        new Chart(document.getElementById('expenseChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chart_labels) !!},
                datasets: [{
                    data: {!! json_encode($chart_values) !!},
                    backgroundColor: ['#6366f1', '#059669', '#db2777', '#d97706', '#0891b2', '#7c3aed'],
                    borderWidth: 0,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } } } }
        });
        @endif
    </script>
</body>
</html>
