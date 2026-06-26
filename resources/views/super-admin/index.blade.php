<x-super-admin-layout title="Platform Overview">
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-bold text-gray-900">Platform Overview</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau seluruh aktivitas, pendapatan, dan kesehatan platform SmartRT Vision.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700 font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Stats Row 1: 5 Kartu --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @php $topCards = [
                ['label' => 'Total Tenant', 'value' => $stats['total'], 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'bg' => 'bg-slate-100', 'color' => 'text-slate-700'],
                ['label' => 'RT Aktif', 'value' => $stats['active'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-100', 'color' => 'text-emerald-700'],
                ['label' => 'Expired / Suspend', 'value' => $stats['expired'], 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'bg' => 'bg-rose-100', 'color' => 'text-rose-700'],
                ['label' => 'Masa Trial', 'value' => $stats['trial'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-amber-100', 'color' => 'text-amber-700'],
                ['label' => 'Churn Rate Bln Ini', 'value' => $stats['churn_rate'].'%', 'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6', 'bg' => 'bg-indigo-100', 'color' => 'text-indigo-700'],
            ]; @endphp
            @foreach($topCards as $c)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $c['bg'] }}">
                    <svg class="w-5 h-5 {{ $c['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $c['icon'] }}"/></svg>
                </div>
                <div>
                    <p class="text-xl font-black text-gray-900">{{ $c['value'] }}</p>
                    <p class="text-xs text-gray-500 leading-tight">{{ $c['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Revenue Cards Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Est. MRR</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['mrr'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Pendapatan estimasi bulanan</p>
            </div>
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['revenue_this_month'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Revenue</p>
            </div>
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Total Revenue (All Time)</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Sejak platform diluncurkan</p>
            </div>
        </div>

        {{-- Chart + Expiring Widget --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Revenue Chart --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Revenue & Bulan Terakhir</h2>
                        <p class="text-xs text-gray-400">Pendapatan 6 bulan terakhir</p>
                    </div>
                </div>
                <canvas id="revenueChart" height="100"></canvas>
            </div>

            {{-- Hampir Expired Widget --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-900">Hampir Expired</h2>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">7 Hari</span>
                </div>
                @if($expiringTenants->isEmpty())
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-700">SEMUA AMAN</p>
                    <p class="text-xs text-gray-400 mt-1">Tidak ada RT yang akan expired dalam 7 hari.</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($expiringTenants->take(6) as $exp)
                    @php
                        $expDate = $exp->trial_ends_at ?? $exp->activeSubscription()?->current_period_end;
                        $daysLeft = $expDate ? now()->diffInDays($expDate, false) : 0;
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $exp->name }}</p>
                            <p class="text-xs text-gray-400">{{ $expDate?->translatedFormat('d M Y') }}</p>
                        </div>
                        <span class="ml-3 px-2 py-0.5 rounded-full text-xs font-bold {{ $daysLeft <= 2 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }} flex-shrink-0">
                            {{ $daysLeft }}h lagi
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Pendaftar Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mt-6">
            <div class="px-6 py-5 flex items-center justify-between">
                <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">PENDAFTAR TERBARU</h2>
                <a href="{{ route('super-admin.tenants.index') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 hover:underline flex items-center gap-1 transition-all">
                    Lihat Semua <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto pb-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white w-16">ID</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white w-full">NAMA RT</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white text-center whitespace-nowrap">JML KK</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white whitespace-nowrap">STATUS</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white whitespace-nowrap text-right">TERDAFTAR</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($tenants as $tenant)
                        @php
                            $badge = [
                                'trial'     => 'text-amber-500',
                                'active'    => 'text-emerald-500',
                                'expired'   => 'text-rose-500',
                                'suspended' => 'text-gray-400',
                            ][$tenant->status] ?? 'text-gray-400';
                            
                            $statusDot = [
                                'trial'     => 'bg-amber-500',
                                'active'    => 'bg-emerald-500',
                                'expired'   => 'bg-rose-500',
                                'suspended' => 'bg-gray-400',
                            ][$tenant->status] ?? 'bg-gray-400';
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors border-b border-gray-50/50 last:border-0">
                            <td class="px-6 py-4 text-xs font-mono text-gray-400 whitespace-nowrap">#{{ $tenant->id }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('super-admin.show', $tenant) }}" class="font-bold text-gray-800 hover:text-indigo-600 transition-colors">{{ $tenant->name }}</a>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center justify-center px-2 py-1 rounded bg-indigo-50 text-indigo-700 text-xs font-bold min-w-[28px]">{{ $tenant->families_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                    <span class="text-[10px] font-bold {{ $badge }} uppercase tracking-wider">{{ strtoupper($tenant->status) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap text-right">{{ $tenant->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                Belum ada pendaftar terbaru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart')?.getContext('2d');
        if (!ctx) return;

        // Revenue data pre-computed in controller
        const revenueData = @json(array_column($revenueChartData, 'total'));
        const monthLabels  = @json(array_column($revenueChartData, 'label'));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: revenueData,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
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
                        callbacks: {
                            label: ctx => 'Rp' + ctx.raw.toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: val => 'Rp' + (val >= 1000000 ? (val/1000000).toFixed(1)+'jt' : val.toLocaleString('id-ID')),
                            font: { size: 11 },
                            color: '#9ca3af',
                        },
                        grid: { color: '#f3f4f6' },
                        border: { display: false }
                    },
                    x: {
                        ticks: { font: { size: 11 }, color: '#9ca3af' },
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
