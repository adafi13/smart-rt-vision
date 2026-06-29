<x-super-admin-layout title="Billing & Transaksi">
    <div class="max-w-6xl space-y-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Billing & Transaksi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola dan pantau semua transaksi pembayaran dari tenant</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-black text-gray-900">{{ number_format($stats['total']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Transaksi</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($stats['paid'], 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Pendapatan (Paid)</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-black text-amber-600">{{ number_format($stats['pending']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Menunggu Pembayaran</p>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama RT..." class="input-field flex-1">
            <select name="status" class="input-field max-w-xs" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending_payment', 'active', 'expired', 'cancelled'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Cari</button>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Trx ID</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tenant (RT)</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Paket</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Dibuat / Dibayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                    @php 
                        $badge = match($trx->status) {
                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'expired', 'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-gray-50 text-gray-600 border-gray-200',
                        }; 
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                            #{{ $trx->id }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900">{{ $trx->tenant->name }}</p>
                            <p class="text-xs text-gray-500">{{ $trx->tenant->slug }}</p>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $trx->plan->name ?? '-' }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badge }}">
                                {{ str_replace('_', ' ', $trx->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="font-bold text-gray-900">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                            @if($trx->amount == 0 && $trx->status == 'active')
                                <span class="text-[10px] text-gray-400 font-medium">Bypass / Manual</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm">
                            <p class="text-gray-900">{{ $trx->created_at->translatedFormat('d M Y H:i') }}</p>
                            @if($trx->paid_at)
                                <p class="text-xs text-emerald-600 font-semibold mt-0.5">Paid: {{ $trx->paid_at->translatedFormat('d M Y H:i') }}</p>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-sm text-gray-400">Belum ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="px-4 py-3 bg-white border border-gray-100 shadow-sm rounded-2xl">{{ $transactions->links() }}</div>
        @endif
    </div>
</x-super-admin-layout>
