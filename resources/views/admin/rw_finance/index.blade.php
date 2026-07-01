<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Laporan Keuangan RW</h1>
            <p class="text-sm text-gray-500 mt-0.5">Transparansi pengelolaan dana (Pemasukan Iuran & Pengeluaran) oleh pengurus RW</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'pemasukan' }">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-600/20 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-indigo-100 mb-1">Saldo Kas RW (Aktual)</p>
                    <h3 class="text-3xl font-bold tracking-tight">Rp {{ number_format($saldoAkhirRW, 0, ',', '.') }}</h3>
                </div>
                <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none">
                    <svg class="w-32 h-32 -mr-8 -mb-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-1.08 2.4-1.73 0-.9-.96-1.44-2.5-1.92-2.03-.64-3.11-1.72-3.11-3.26 0-1.63 1.25-2.73 2.87-3.13V5h2.67v1.94c1.54.34 2.8 1.41 2.96 3.16h-1.94c-.15-1.07-.94-1.68-2.31-1.68-1.57 0-2.19.8-2.19 1.55 0 .76.6 1.34 2.49 1.9 2.22.65 3.12 1.76 3.12 3.33 0 1.93-1.44 2.84-3.13 3.09z"/></svg>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pemasukan RW</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPemasukanRW, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pengeluaran RW</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPengeluaranRW, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'pemasukan'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'pemasukan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pemasukan'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">
                Iuran Masuk (Pemasukan)
            </button>
            <button @click="activeTab = 'pengeluaran'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'pengeluaran', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pengeluaran'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">
                Riwayat Pengeluaran RW
            </button>
        </div>

        <!-- PEMASUKAN TAB -->
        <div x-show="activeTab === 'pemasukan'" class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Sumbangsih RT</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($contributions as $c)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($c->tanggal_bayar)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $c->tenant->name ?? 'RT Tidak Diketahui/Dihapus' }}</td>
                            <td class="px-6 py-4">{{ $c->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-medium text-emerald-600">+ Rp {{ number_format($c->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada data iuran masuk ke RW.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $contributions->links() }}
        </div>

        <!-- PENGELUARAN TAB -->
        <div x-show="activeTab === 'pengeluaran'" x-cloak class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Bukti Nota</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($expenses as $e)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($e->tanggal_keluar)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $e->keterangan }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($e->bukti_nota)
                                    <a href="{{ Storage::url($e->bukti_nota) }}" target="_blank" class="text-indigo-600 hover:underline font-semibold text-xs">Lihat Bukti</a>
                                @else
                                    <span class="text-gray-400 text-xs">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-rose-600">- Rp {{ number_format($e->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada data pengeluaran RW.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>
