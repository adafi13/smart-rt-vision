<x-app-layout>
    <!-- Set background color to match enterprise clean look (light grayish white) -->
    <style>
        body { background-color: #f8fafc !important; }
        .ent-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02); }
        .ent-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; }
    </style>

    <div class="max-w-[1400px] mx-auto w-full px-2 sm:px-4 pb-10 mt-2">
        
        <!-- Header Banner (Pill Shape) -->
        <div class="w-full bg-indigo-600 rounded-[32px] px-6 py-8 sm:px-10 sm:py-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6 shadow-[0_8px_30px_rgba(79,70,229,0.2)]">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-indigo-200 mt-2 text-xs sm:text-sm font-semibold tracking-widest uppercase">
                    SMARTRT VISION &bull; {{ strtoupper(\Carbon\Carbon::now()->translatedFormat('l, d F Y')) }}
                </p>
            </div>
            
            @php
                $statusBg = 'bg-white/10 border-white/20';
                $statusIconBg = 'bg-white/20 text-white';
                $statusLabelColor = 'text-indigo-200';
                $statusTextColor = 'text-white';
                $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                $statusText = 'Berjalan Optimal';
                $animationClass = '';

                if (isset($activePanicAlerts) && count($activePanicAlerts) > 0) {
                    // Priority 1: Darurat
                    $statusBg = 'bg-red-500/30 border-red-400/50';
                    $statusIconBg = 'bg-red-500 text-white animate-pulse';
                    $statusLabelColor = 'text-red-200';
                    $statusTextColor = 'text-white';
                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                    $statusText = 'KEADAAN DARURAT!';
                    $animationClass = 'animate-pulse';
                } elseif ($aiLimit > 0 && $aiPercentage <= 10) {
                    // Priority 2: Kuota Kritis
                    $statusBg = 'bg-orange-500/20 border-orange-400/30';
                    $statusIconBg = 'bg-orange-500 text-white';
                    $statusLabelColor = 'text-orange-200';
                    $statusTextColor = 'text-orange-50';
                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>';
                    $statusText = "Sisa Kuota AI: $aiRemaining";
                } elseif ($pendingReports > 0) {
                    // Priority 3: Perhatian
                    $statusBg = 'bg-amber-400/20 border-amber-300/30';
                    $statusIconBg = 'bg-amber-400 text-amber-900';
                    $statusLabelColor = 'text-amber-200';
                    $statusTextColor = 'text-white';
                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>';
                    $statusText = "$pendingReports Menunggu diproses";
                }
            @endphp

            <div class="flex items-center gap-3 backdrop-blur-md border rounded-full px-5 py-3 {{ $statusBg }} {{ $animationClass }} transition-colors duration-500">
                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $statusIconBg }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest leading-tight {{ $statusLabelColor }}">STATUS SISTEM</p>
                    <p class="text-sm font-bold leading-tight {{ $statusTextColor }}">{{ $statusText }}</p>
                </div>
            </div>
        </div>


        <!-- Stat Cards Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-6 sm:mt-8">
            @php $stats = [
                ['label'=>'Total Warga','value'=>$totalWarga??0,'sub'=>'Jiwa tercatat','color'=>'text-gray-900','bg'=>'bg-gray-100','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label'=>'Total KK','value'=>$totalKk??0,'sub'=>'Kartu Keluarga','color'=>'text-indigo-600','bg'=>'bg-indigo-50','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label'=>'Laki-Laki','value'=>$totalLakiLaki??0,'sub'=>'Jiwa','color'=>'text-blue-600','bg'=>'bg-blue-50','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label'=>'Perempuan','value'=>$totalPerempuan??0,'sub'=>'Jiwa','color'=>'text-pink-600','bg'=>'bg-pink-50','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ]; @endphp

            @foreach($stats as $s)
            <div class="ent-card p-5 sm:p-6 flex flex-col justify-between h-full">
                <div class="flex items-center justify-between mb-4">
                    <p class="ent-label">{{ $s['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $s['bg'] }} {{ $s['color'] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-bold text-slate-800">{{ number_format($s['value']) }}</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-1">{{ $s['sub'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- PANIC ALERTS SECTION -->
        @if(isset($activePanicAlerts) && count($activePanicAlerts) > 0)
        <div class="mt-6 mb-2">
            <div class="bg-rose-50 border-2 border-rose-500 rounded-[20px] p-5 shadow-lg shadow-rose-500/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500 opacity-10 rounded-full -mr-10 -mt-10 animate-ping"></div>
                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div class="w-10 h-10 bg-rose-600 text-white rounded-full flex items-center justify-center animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-rose-700 leading-tight">DARURAT: {{ count($activePanicAlerts) }} Laporan Aktif!</h3>
                        <p class="text-sm font-medium text-rose-600">Harap segera hubungi pelapor dan tindak lanjuti laporan berikut.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 relative z-10">
                    @foreach($activePanicAlerts as $alert)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-rose-100 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span class="bg-rose-100 text-rose-700 text-xs font-bold px-2 py-1 rounded-md uppercase">{{ $alert->type }}</span>
                            <span class="text-xs font-medium text-slate-400">{{ $alert->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-800">{{ $alert->reporter_name }}</p>
                        <p class="text-xs text-slate-500 mb-3"><span class="font-medium">Lokasi:</span> {{ $alert->location }}</p>
                        <div class="mt-auto flex gap-2">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $alert->reporter_contact) }}" target="_blank" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 rounded-lg flex items-center justify-center gap-1 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WA Pelapor
                            </a>
                            <form action="{{ route('admin.panic.resolve', $alert) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="resolution_note" value="Selesai ditangani.">
                                <button type="submit" class="w-full bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-bold py-2 rounded-lg flex items-center justify-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Middle Section: Main Chart & AI Module -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            
            <!-- Main Chart (Demografi) -->
            <div class="lg:col-span-2 ent-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="ent-label text-indigo-600">GRAFIK DEMOGRAFI</p>
                        <h2 class="text-lg font-bold text-slate-800 mt-1">Sebaran Usia Warga</h2>
                    </div>
                    <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs font-semibold text-slate-500">
                        Total: {{ number_format($totalWarga) }} Jiwa
                    </div>
                </div>
                <div class="relative w-full flex-1 min-h-[280px]">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>

            <!-- AI Pulse Card (Enterprise Style) -->
            <div class="lg:col-span-1 bg-[#1e293b] rounded-2xl p-6 shadow-lg flex flex-col justify-between border border-slate-700">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="text-[9px] font-bold tracking-widest text-emerald-400 uppercase">VISION AI</span>
                        </div>
                    </div>
                    <p class="ent-label text-slate-400">MODUL KECERDASAN BUATAN</p>
                    <h3 class="text-2xl font-bold text-white mt-1 mb-2">Google Gemini</h3>
                    <p class="text-sm text-slate-400 font-medium leading-relaxed mb-6">
                        Sistem ekstraksi teks otomatis dari pindaian Kartu Keluarga. Mempercepat proses input data warga dengan akurasi tinggi.
                    </p>
                    
                    <!-- Progress bars styled like health pulse -->
                    <div class="space-y-3 mb-6">
                        <div>
                            <div class="flex justify-between text-xs font-bold text-slate-300 mb-1">
                                <span>AKURASI BACA</span>
                                <span class="text-emerald-400">98%</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                <div class="w-[98%] h-full bg-emerald-400 rounded-full"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold text-slate-300 mb-1">
                                <span>KECEPATAN PROSES</span>
                                <span class="text-indigo-400">&lt; 3 Detik</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                <div class="w-[85%] h-full bg-indigo-400 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('kk.upload') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 rounded-xl text-sm font-bold text-white transition-colors">
                    Upload & Pindai KK Baru
                </a>
            </div>
        </div>

        <!-- Bottom Section: Quick Actions & Secondary Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            
            <!-- Quick Actions List -->
            <div class="lg:col-span-1 ent-card p-0 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100">
                    <p class="ent-label">AKSES OPERASIONAL</p>
                    <h2 class="text-lg font-bold text-slate-800 mt-1">Tindakan Cepat</h2>
                </div>
                <div class="flex-1 flex flex-col">
                    @php $actions = [
                        ['href'=>route('kk.upload'),'label'=>'Pindai KK dengan AI','sub'=>'Proses KK otomatis','icon'=>'M12 4v16m8-8H4','color'=>'text-indigo-600'],
                        ['href'=>route('export.excel'),'label'=>'Unduh Rekap Excel','sub'=>'Format .xlsx spreadsheet','icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','color'=>'text-emerald-600'],
                        ['href'=>route('export.pdf'),'label'=>'Cetak Laporan PDF','sub'=>'Format dokumen cetak','icon'=>'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z','color'=>'text-rose-600'],
                        ['href'=>route('admin.laporan.index'),'label'=>'Tinjau Laporan Warga','sub'=>'Manajemen aduan & laporan','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','color'=>'text-blue-600'],
                    ]; @endphp

                    @foreach($actions as $idx => $a)
                    <a href="{{ $a['href'] }}" class="flex items-center justify-between p-4 px-6 hover:bg-slate-50 transition-colors {{ $idx !== count($actions)-1 ? 'border-b border-slate-50' : '' }}">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center {{ $a['color'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $a['icon'] }}"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $a['label'] }}</p>
                                <p class="text-xs font-medium text-slate-500">{{ $a['sub'] }}</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Education Chart -->
            <div class="lg:col-span-1 ent-card p-6 flex flex-col">
                <div class="mb-4 text-center">
                    <p class="ent-label">KOMPOSISI</p>
                    <h2 class="text-lg font-bold text-slate-800 mt-1">Tingkat Pendidikan</h2>
                </div>
                <div class="relative flex-1 min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="eduChart"></canvas>
                </div>
            </div>

            <!-- Gender Chart -->
            <div class="lg:col-span-1 ent-card p-6 flex flex-col">
                <div class="mb-4 text-center">
                    <p class="ent-label">DEMOGRAFI</p>
                    <h2 class="text-lg font-bold text-slate-800 mt-1">Komposisi Gender</h2>
                </div>
                <div class="relative flex-1 min-h-[220px] w-full flex items-center justify-center">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enterprise Chart Config
            Chart.defaults.font.family = "'Inter', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.font.size = 11;
            
            const tooltipConfig = {
                backgroundColor: '#ffffff',
                titleColor: '#1e293b',
                bodyColor: '#64748b',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
                boxPadding: 6,
                bodyFont: { weight: '600' },
                titleFont: { weight: 'bold' }
            };

            // 1. Grafik Umur (Bar) - Enterprise Style (Flat, Minimalist)
            new Chart(document.getElementById('ageChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($ageGroups)) !!},
                    datasets: [{
                        label: 'Jumlah Warga',
                        data: {!! json_encode(array_values($ageGroups)) !!},
                        backgroundColor: '#4f46e5',
                        hoverBackgroundColor: '#4338ca',
                        borderRadius: 4,
                        barPercentage: 0.4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: tooltipConfig
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            border: { display: false },
                            grid: { color: '#f1f5f9', drawTicks: false },
                            ticks: { precision: 0, padding: 12 }
                        }, 
                        x: { 
                            border: { display: false },
                            grid: { display: false },
                            ticks: { padding: 12, font: { weight: '600' } }
                        } 
                    }
                }
            });

            // 2. Grafik Pendidikan (Doughnut) - Professional Colors
            @php 
                $eduTop = array_slice($pendidikanStats, 0, 5); 
                if(count($pendidikanStats) > 5) $eduTop['Lainnya'] = array_sum(array_slice($pendidikanStats, 5));
            @endphp
            new Chart(document.getElementById('eduChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($eduTop)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($eduTop)) !!},
                        backgroundColor: ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#f43f5e', '#94a3b8'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    cutout: '75%',
                    layout: { padding: 0 },
                    plugins: { 
                        legend: { 
                            position: 'bottom', 
                            labels: { boxWidth: 10, usePointStyle: true, padding: 15, font: { weight: '600' } } 
                        },
                        tooltip: tooltipConfig
                    }
                }
            });

            // 3. Grafik Gender (Doughnut)
            new Chart(document.getElementById('genderChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [{
                        data: [{{ $totalLakiLaki }}, {{ $totalPerempuan }}],
                        backgroundColor: ['#3b82f6', '#ec4899'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    cutout: '75%',
                    layout: { padding: 0 },
                    plugins: { 
                        legend: { 
                            position: 'bottom', 
                            labels: { boxWidth: 10, usePointStyle: true, padding: 15, font: { weight: '600' } } 
                        },
                        tooltip: tooltipConfig
                    }
                }
            });
        });
    </script>
</x-app-layout>
