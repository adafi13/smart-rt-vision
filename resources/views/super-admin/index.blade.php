<x-super-admin-layout title="Platform Overview">
<div class="space-y-6">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Platform Overview</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau performa, pendapatan, dan kesehatan platform SmartRT Vision secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-full">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                Sistem Online
            </span>
            <span class="text-xs text-gray-400">{{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== ROW 1: TENANT STATUS CARDS ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

        {{-- Total Tenant --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <a href="{{ route('super-admin.tenants.index') }}" class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity">Lihat →</a>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Total Tenant RT</p>
            @if($stats['new_this_month'] > 0)
            <p class="text-[11px] text-emerald-600 font-semibold mt-1.5">+{{ $stats['new_this_month'] }} bulan ini</p>
            @endif
        </div>

        {{-- RT Aktif --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                    {{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}%
                </span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">RT Aktif Berlangganan</p>
        </div>

        {{-- Trial --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Trial</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['trial'] }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Masa Percobaan</p>
        </div>

        {{-- Expired/Suspend --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">Churn {{ $stats['churn_rate'] }}%</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['expired'] }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">Expired / Suspended</p>
        </div>
    </div>

    {{-- ===== ROW 2: REVENUE + PLATFORM STATS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- MRR --}}
        <div class="lg:col-span-1 rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 -bottom-6 w-16 h-16 bg-white/10 rounded-full"></div>
            <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Est. MRR</p>
            <p class="text-2xl font-black">Rp{{ $stats['mrr'] >= 1000000 ? number_format($stats['mrr']/1000000, 1, ',', '.') . 'jt' : number_format($stats['mrr'], 0, ',', '.') }}</p>
            <p class="text-[11px] text-white/60 mt-2">Monthly Recurring Revenue</p>
        </div>

        {{-- Revenue Bulan Ini --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Revenue Bulan Ini</p>
            <p class="text-2xl font-black">Rp{{ $stats['revenue_this_month'] >= 1000000 ? number_format($stats['revenue_this_month']/1000000, 1, ',', '.') . 'jt' : number_format($stats['revenue_this_month'], 0, ',', '.') }}</p>
            <div class="flex items-center gap-1 mt-2">
                @if($stats['revenue_growth'] >= 0)
                    <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span class="text-[11px] text-white/80 font-bold">+{{ $stats['revenue_growth'] }}% vs bulan lalu</span>
                @else
                    <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    <span class="text-[11px] text-white/80 font-bold">{{ $stats['revenue_growth'] }}% vs bulan lalu</span>
                @endif
            </div>
        </div>

        {{-- Total Revenue All Time --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full"></div>
            <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Total Revenue</p>
            <p class="text-2xl font-black">Rp{{ $stats['total_revenue'] >= 1000000 ? number_format($stats['total_revenue']/1000000, 1, ',', '.') . 'jt' : number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-[11px] text-white/50 mt-2">Sejak platform diluncurkan</p>
        </div>

        {{-- Platform Stats --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Platform Stats</p>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Total KK Terdaftar</span>
                    <span class="text-xs font-black text-gray-900">{{ number_format($stats['total_families']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Total Anggota</span>
                    <span class="text-xs font-black text-gray-900">{{ number_format($stats['total_members']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Aktivitas Hari Ini</span>
                    <span class="text-xs font-black text-indigo-600">{{ number_format($stats['logs_today']) }} log</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Churn Rate</span>
                    <span class="text-xs font-black {{ $stats['churn_rate'] > 5 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $stats['churn_rate'] }}%</span>
                </div>
                <div class="flex justify-between items-center border-t border-gray-100 pt-2.5 mt-2.5">
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        AI Scans (Beban)
                    </span>
                    <div class="text-right">
                        <span class="text-xs font-black text-purple-600">{{ number_format($stats['total_ai_scans']) }}x</span>
                        <p class="text-[9px] font-bold text-gray-400 mt-0.5">Est. Rp{{ number_format($stats['total_ai_scans'] * 15, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ROW 3: CHART + EXPIRING + TOP TENANTS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-black text-gray-900">Grafik Revenue</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Pendapatan 6 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-sm bg-indigo-500 inline-block"></span>
                    <span class="text-[11px] text-gray-500">Revenue (Rp)</span>
                </div>
            </div>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        {{-- Right Column: Expiring + Quick Actions --}}
        <div class="space-y-4">

            {{-- Hampir Expired --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-black text-gray-900">⚠️ Hampir Expired</h2>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">7 Hari</span>
                </div>
                @if($expiringTenants->isEmpty())
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-600">Semua Aman!</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Tidak ada RT yang akan expired.</p>
                </div>
                @else
                <div class="space-y-2.5">
                    @foreach($expiringTenants->take(5) as $exp)
                    @php
                        $expDate = $exp->trial_ends_at ?? $exp->activeSubscription()?->current_period_end;
                        $daysLeft = $expDate ? (int) now()->diffInDays($expDate, false) : 0;
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $exp->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $expDate?->translatedFormat('d M Y') }}</p>
                        </div>
                        <span class="ml-2 px-2 py-0.5 rounded-full text-[11px] font-black flex-shrink-0 {{ $daysLeft <= 2 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $daysLeft }}h lagi
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-sm font-black text-gray-900 mb-3">⚡ Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('super-admin.tenants.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 transition-colors group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                        </div>
                        <span class="text-xs font-semibold">Kelola Semua Tenant</span>
                    </a>
                    <a href="{{ route('super-admin.audit-logs.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 text-gray-700 hover:text-slate-900 transition-colors group">
                        <div class="w-8 h-8 bg-slate-50 group-hover:bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944"/></svg>
                        </div>
                        <span class="text-xs font-semibold">Lihat Audit Log</span>
                    </a>
                    <a href="{{ route('super-admin.announcements.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-amber-50 text-gray-700 hover:text-amber-700 transition-colors group">
                        <div class="w-8 h-8 bg-amber-50 group-hover:bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6"/></svg>
                        </div>
                        <span class="text-xs font-semibold">Buat Pengumuman</span>
                    </a>
                    <a href="{{ route('super-admin.finance.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-emerald-50 text-gray-700 hover:text-emerald-700 transition-colors group">
                        <div class="w-8 h-8 bg-emerald-50 group-hover:bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2"/></svg>
                        </div>
                        <span class="text-xs font-semibold">Laporan Keuangan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ROW 4: TOP TENANTS + RECENT REGISTRATIONS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Top Active Tenants --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-black text-gray-900">🏆 Top RT Teraktif</h2>
                <span class="text-[10px] text-gray-400 font-medium">by jumlah KK</span>
            </div>
            <div class="space-y-3">
                @forelse($topTenants as $i => $tt)
                @php $maxCount = $topTenants->first()->families_count ?: 1; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black text-gray-400 w-4">{{ $i+1 }}</span>
                            <a href="{{ route('super-admin.show', $tt) }}" class="text-xs font-bold text-gray-800 hover:text-indigo-600 transition-colors truncate max-w-[140px]">{{ $tt->name }}</a>
                        </div>
                        <span class="text-[11px] font-black text-indigo-700">{{ $tt->families_count }} KK</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ ['bg-indigo-500','bg-blue-400','bg-sky-400','bg-cyan-400','bg-teal-400'][$i] ?? 'bg-indigo-400' }}" style="width: {{ round(($tt->families_count / $maxCount) * 100) }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Belum ada data.</p>
                @endforelse
            </div>
        </div>

        {{-- Pendaftar Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-50">
                <div>
                    <h2 class="text-sm font-black text-gray-900">Pendaftar Terbaru</h2>
                    <p class="text-[11px] text-gray-400">{{ $stats['new_this_month'] }} RT baru mendaftar bulan ini</p>
                </div>
                <a href="{{ route('super-admin.tenants.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 transition-colors">
                    Lihat Semua →
                </a>
            </div>

            {{-- Desktop table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/40">
                            <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Nama RT</th>
                            <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">KK</th>
                            <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Paket</th>
                            <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($tenants as $tenant)
                        @php
                            $statusMap = [
                                'trial'     => ['label' => 'Trial',     'dot' => 'bg-amber-400',  'text' => 'text-amber-700',  'bg' => 'bg-amber-50  border-amber-100'],
                                'active'    => ['label' => 'Aktif',     'dot' => 'bg-emerald-400','text' => 'text-emerald-700','bg' => 'bg-emerald-50 border-emerald-100'],
                                'expired'   => ['label' => 'Expired',   'dot' => 'bg-rose-400',   'text' => 'text-rose-700',   'bg' => 'bg-rose-50   border-rose-100'],
                                'suspended' => ['label' => 'Suspended', 'dot' => 'bg-gray-400',   'text' => 'text-gray-600',   'bg' => 'bg-gray-50   border-gray-100'],
                            ];
                            $s = $statusMap[$tenant->status] ?? $statusMap['expired'];
                            $plan = $tenant->subscriptions->first()?->plan;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('super-admin.show', $tenant) }}" class="font-bold text-gray-800 hover:text-indigo-600 transition-colors text-sm">{{ $tenant->name }}</a>
                                <p class="text-[10px] text-gray-400 font-mono">{{ $tenant->slug }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-black min-w-[28px]">{{ $tenant->families_count }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-bold {{ $s['text'] }} {{ $s['bg'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs text-gray-600 font-medium">{{ $plan?->name ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-xs text-gray-400 whitespace-nowrap">{{ $tenant->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-10 text-center text-sm text-gray-400">Belum ada pendaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="sm:hidden divide-y divide-gray-50">
                @forelse($tenants as $tenant)
                @php
                    $statusMap = [
                        'trial'     => ['label' => 'Trial',     'text' => 'text-amber-700',  'bg' => 'bg-amber-50'],
                        'active'    => ['label' => 'Aktif',     'text' => 'text-emerald-700','bg' => 'bg-emerald-50'],
                        'expired'   => ['label' => 'Expired',   'text' => 'text-rose-700',   'bg' => 'bg-rose-50'],
                        'suspended' => ['label' => 'Suspended', 'text' => 'text-gray-600',   'bg' => 'bg-gray-50'],
                    ];
                    $s = $statusMap[$tenant->status] ?? $statusMap['expired'];
                @endphp
                <div class="px-4 py-3 flex items-center justify-between">
                    <div>
                        <a href="{{ route('super-admin.show', $tenant) }}" class="text-sm font-bold text-gray-800">{{ $tenant->name }}</a>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $tenant->families_count }} KK · {{ $tenant->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="text-[11px] font-bold px-2 py-1 rounded-lg {{ $s['text'] }} {{ $s['bg'] }}">{{ $s['label'] }}</span>
                </div>
                @empty
                <div class="py-8 text-center text-sm text-gray-400">Belum ada pendaftar.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart')?.getContext('2d');
    if (!ctx) return;

    const revenueData = @json(array_column($revenueChartData, 'total'));
    const monthLabels = @json(array_column($revenueChartData, 'label'));
    const hasRevenue  = revenueData.some(v => v > 0);

    // Gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctx, {
        type: hasRevenue ? 'bar' : 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: revenueData,
                backgroundColor: revenueData.map((v, i) => i === revenueData.length - 1 ? 'rgba(99,102,241,0.9)' : 'rgba(99,102,241,0.4)'),
                borderColor: '#6366f1',
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => '  Rp ' + ctx.raw.toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => val >= 1000000 ? 'Rp' + (val/1000000).toFixed(1)+'jt' : val === 0 ? 'Rp0' : 'Rp' + val.toLocaleString('id-ID'),
                        font: { size: 10 },
                        color: '#9ca3af',
                    },
                    grid: { color: '#f3f4f6' },
                    border: { display: false }
                },
                x: {
                    ticks: { font: { size: 10 }, color: '#9ca3af' },
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
</x-super-admin-layout>
