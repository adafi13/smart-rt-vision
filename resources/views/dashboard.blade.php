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
            
            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest leading-tight">STATUS SISTEM</p>
                    <p class="text-sm font-bold text-white leading-tight">Berjalan Optimal</p>
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
