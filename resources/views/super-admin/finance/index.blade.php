<x-super-admin-layout title="Laporan Keuangan">
    <div class="space-y-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Laporan Keuangan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan pendapatan dan revenue platform SmartRT Vision.</p>
        </div>

        {{-- Revenue Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">MRR</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['mrr'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Estimasi bulanan</p>
            </div>
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Bulan Ini</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['revenueThisMonth'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Revenue bulan ini</p>
            </div>
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #64748b, #475569);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">Bulan Lalu</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['revenueLastMonth'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Perbandingan</p>
            </div>
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
                <p class="text-xs font-bold text-white/70 uppercase tracking-wider mb-1">All Time</p>
                <p class="text-2xl font-black">Rp{{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">Total seluruh waktu</p>
            </div>
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Revenue 12 Bulan Terakhir</h2>
            <canvas id="financeChart" height="80"></canvas>
        </div>

        {{-- Breakdown per Paket --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900">Revenue per Paket</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($stats['byPlan'] as $row)
                <div class="px-5 py-3.5 flex items-center justify-between text-sm">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $row->plan?->name ?? 'Paket Dihapus' }}</p>
                        <p class="text-xs text-gray-400">{{ $row->count }} transaksi</p>
                    </div>
                    <p class="font-black text-gray-900">Rp{{ number_format($row->total, 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada data transaksi.</div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('financeChart')?.getContext('2d');
        if (!ctx) return;

        const labels = @json(array_column($stats['months'], 'label'));
        const data   = @json(array_column($stats['months'], 'total'));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue',
                    data,
                    backgroundColor: 'rgba(99, 102, 241, 0.75)',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: c => 'Rp' + c.raw.toLocaleString('id-ID') } }
                },
                scales: {
                    y: {
                        ticks: { callback: v => 'Rp' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v.toLocaleString('id-ID')), color: '#9ca3af', font: { size: 11 } },
                        grid: { color: '#f3f4f6' }, border: { display: false }
                    },
                    x: { ticks: { color: '#9ca3af', font: { size: 11 } }, grid: { display: false }, border: { display: false } }
                }
            }
        });
    });
    </script>
    @endpush
</x-super-admin-layout>
