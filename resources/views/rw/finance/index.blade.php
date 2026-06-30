<x-rw-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Keuangan (Kas Gabungan RW)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola iuran dari RT dan pencatatan pengeluaran operasional RW</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'pemasukan' }">
        
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-600/20 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-indigo-100 mb-1">Saldo Kas RW</p>
                    <h3 class="text-3xl font-bold tracking-tight">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
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
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pemasukan (Iuran RT)</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pengeluaran</p>
                        <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'pemasukan'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'pemasukan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pemasukan'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">
                Iuran dari RT (Pemasukan)
            </button>
            <button @click="activeTab = 'pengeluaran'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'pengeluaran', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pengeluaran'}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors">
                Pengeluaran Kas RW
            </button>
        </div>

        <!-- PEMASUKAN TAB -->
        <div x-show="activeTab === 'pemasukan'" class="space-y-4">
            <div class="flex justify-end">
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-iuran')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Iuran RT
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Dari RT</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($contributions as $c)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($c->date)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $c->tenant->name }}</td>
                            <td class="px-6 py-4">{{ $c->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-medium text-emerald-600">+ Rp {{ number_format($c->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('rw.finance.contributions.destroy', $c) }}" method="POST" onsubmit="return confirm('Hapus data iuran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada data iuran RT.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $contributions->links() }}
        </div>

        <!-- PENGELUARAN TAB -->
        <div x-show="activeTab === 'pengeluaran'" x-cloak class="space-y-4">
            <div class="flex justify-end">
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-pengeluaran')" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Pengeluaran
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Bukti</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($expenses as $e)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($e->date)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $e->description }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($e->receipt_path)
                                    <a href="{{ Storage::url($e->receipt_path) }}" target="_blank" class="text-indigo-600 hover:underline font-semibold text-xs">Lihat</a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-rose-600">- Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('rw.finance.expenses.destroy', $e) }}" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada data pengeluaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- MODAL CATAT IURAN RT -->
    <x-modal name="tambah-iuran" maxWidth="md">
        <form action="{{ route('rw.finance.contributions.store') }}" method="POST" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 mb-4">Catat Pemasukan (Iuran RT)</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Dari RT <span class="text-red-500">*</span></label>
                    <select name="tenant_id" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                        <option value="">Pilih RT...</option>
                        @foreach($rts as $rt)
                            <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan</label>
                    <input type="text" name="description" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="Contoh: Iuran Wajib Bulan Juni">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold text-sm">Simpan Pemasukan</button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL CATAT PENGELUARAN -->
    <x-modal name="tambah-pengeluaran" maxWidth="md">
        <form action="{{ route('rw.finance.expenses.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 mb-4">Catat Pengeluaran RW</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nominal (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan <span class="text-red-500">*</span></label>
                    <input type="text" name="description" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="Contoh: Biaya rapat bulanan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti Struk (Opsional)</label>
                    <input type="file" name="receipt" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold text-sm">Simpan Pengeluaran</button>
            </div>
        </form>
    </x-modal>
</x-rw-app-layout>
