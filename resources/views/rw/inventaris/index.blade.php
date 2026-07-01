<x-rw-app-layout title="Manajemen Inventaris & Aset">
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'katalog' }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Inventaris & Aset RT</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola barang inventaris dan pantau status peminjaman warga</p>
            </div>
            
            <!-- Tabs Navigation (Segmented Control Style) -->
            <div class="flex items-center p-1 bg-gray-100/80 rounded-xl w-full sm:w-auto border border-gray-200/50">
                <button @click="activeTab = 'katalog'" 
                        :class="activeTab === 'katalog' ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 border border-transparent'"
                        class="flex-1 sm:flex-none px-5 py-2 text-sm font-semibold rounded-lg transition-all">
                    Katalog Barang
                </button>
                <button @click="activeTab = 'peminjaman'" 
                        :class="activeTab === 'peminjaman' ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 border border-transparent'"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2 text-sm font-semibold rounded-lg transition-all">
                    Data Peminjaman
                    @php $pendingCount = $borrowings->where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-bold shadow-sm shadow-rose-200">{{ $pendingCount }}</span>
                    @endif
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- TAB 1: KATALOG BARANG -->
        <div x-show="activeTab === 'katalog'" class="space-y-6 animate-fade-in" style="display: none;">
            
            <!-- Form Tambah Barang -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v3H4c-1.103 0-2 .897-2 2v11c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM10 4h4v3h-4V4zm10 16H4V9h16v11z"/></svg>
                </div>
                
                <h2 class="text-base font-bold text-gray-900 mb-5 relative">Tambah Barang Baru</h2>
                <form action="{{ route('rw.inventaris.store') }}" method="POST" class="relative">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama Barang</label>
                            <input type="text" name="name" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm" placeholder="Contoh: Tenda Hajatan">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Total Stok / Unit</label>
                            <input type="number" name="total_quantity" required min="1" value="1" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kondisi</label>
                            <select name="condition" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-medium text-gray-700">
                                <option value="baik">Kondisi Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2 h-[42px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Simpan Barang
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- List Barang Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($inventories as $inv)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                        <div class="p-5 flex-1 relative">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-base font-bold text-gray-900 pr-4">{{ $inv->name }}</h3>
                                @if($inv->condition == 'baik')
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-50 text-emerald-700 font-bold uppercase tracking-wide border border-emerald-200">Baik</span>
                                @elseif($inv->condition == 'rusak_ringan')
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-amber-50 text-amber-700 font-bold uppercase tracking-wide border border-amber-200">Ringan</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-rose-50 text-rose-700 font-bold uppercase tracking-wide border border-rose-200">Berat</span>
                                @endif
                            </div>
                            
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="bg-gray-50/80 border border-gray-100 rounded-xl p-3 text-center relative overflow-hidden">
                                    <div class="absolute top-0 right-0 opacity-5 -mt-2 -mr-2"><svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></div>
                                    <span class="block text-2xl font-black text-gray-800">{{ $inv->available_quantity }}</span>
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mt-0.5">Tersedia</span>
                                </div>
                                <div class="bg-indigo-50/80 border border-indigo-100 rounded-xl p-3 text-center relative overflow-hidden">
                                    <div class="absolute top-0 right-0 opacity-10 -mt-2 -mr-2 text-indigo-500"><svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg></div>
                                    <span class="block text-2xl font-black text-indigo-700">{{ $inv->borrowed_quantity }}</span>
                                    <span class="block text-[10px] font-bold text-indigo-500 uppercase tracking-wide mt-0.5">Dipinjam</span>
                                </div>
                            </div>
                            <div class="mt-3 text-center text-[11px] text-gray-500 font-medium">
                                Total Aset: <span class="text-gray-900 font-bold">{{ $inv->total_quantity }} Unit</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 p-3 bg-gray-50/50 flex gap-2">
                            <button x-data @click="$dispatch('open-modal', 'edit-modal-{{ $inv->id }}')" class="flex-1 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <form action="{{ route('rw.inventaris.destroy', $inv) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus aset ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-1.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Edit -->
                    <x-modal name="edit-modal-{{ $inv->id }}" focusable>
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-5">Edit Inventaris</h2>
                            <form action="{{ route('rw.inventaris.update', $inv) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama Barang</label>
                                    <input type="text" name="name" value="{{ $inv->name }}" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Total Stok Keseluruhan</label>
                                    <input type="number" name="total_quantity" value="{{ $inv->total_quantity }}" required min="1" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                                    <p class="text-[10px] text-gray-400 mt-1">Hanya kurangi angka ini jika barang fisik benar-benar hilang/dibuang.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kondisi Barang</label>
                                    <select name="condition" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                                        <option value="baik" {{ $inv->condition == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak_ringan" {{ $inv->condition == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak_berat" {{ $inv->condition == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-200 border-dashed shadow-sm">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Katalog Inventaris Kosong</h3>
                        <p class="text-xs text-gray-500">Silakan gunakan formulir di atas untuk mendaftarkan aset pertama.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 2: DATA PEMINJAMAN -->
        <div x-show="activeTab === 'peminjaman'" class="space-y-4 animate-fade-in" style="display: none;">
            
            @if($borrowings->isNotEmpty())
            <!-- DESKTOP ENTERPRISE TABLE -->
            <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
                <table class="min-w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Barang & Jumlah</th>
                            <th class="px-6 py-4">Tenggat Waktu</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Opsi Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @foreach($borrowings as $b)
                        <tr class="hover:bg-gray-50/50 transition-colors group align-top">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ substr($b->borrower_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block">{{ $b->borrower_name }}</span>
                                        <span class="text-[10px] text-gray-500 font-mono mt-0.5 block flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $b->borrower_contact }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900 block">{{ $b->inventory->name }}</span>
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold tracking-wide bg-indigo-50 text-indigo-600 border border-indigo-200 mt-1.5">
                                    {{ $b->quantity }} Unit
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs">
                                    <p class="text-gray-500 mb-1">Ambil: <span class="font-semibold text-gray-800">{{ $b->borrow_date->format('d M Y') }}</span></p>
                                    <p class="text-gray-500">Kembali: <span class="font-semibold text-gray-800">{{ $b->expected_return_date->format('d M Y') }}</span></p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($b->status === 'approved')
                                    <span class="inline-flex px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-[10px] font-bold uppercase tracking-wide">Dipinjam</span>
                                @elseif($b->status === 'returned')
                                    <span class="inline-flex px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase tracking-wide">Selesai Dikembalikan</span>
                                @elseif($b->status === 'rejected')
                                    <span class="inline-flex px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold uppercase tracking-wide">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Izin
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($b->status === 'pending')
                                    <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <form action="{{ route('rw.inventaris.borrowings.approve', $b) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm shadow-emerald-200 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Izinkan
                                            </button>
                                        </form>
                                        <form action="{{ route('rw.inventaris.borrowings.reject', $b) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @elseif($b->status === 'approved')
                                    <form action="{{ route('rw.inventaris.borrowings.return', $b) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm shadow-indigo-200 inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                            Tandai Dikembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 font-semibold italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- MOBILE COMPACT CARDS -->
            <div class="md:hidden space-y-3">
                @foreach($borrowings as $b)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                    <!-- Status Indicator Bar -->
                    @php
                        $barColor = match($b->status) {
                            'approved' => 'bg-blue-500',
                            'returned' => 'bg-emerald-500',
                            'rejected' => 'bg-rose-500',
                            default => 'bg-amber-500',
                        };
                    @endphp
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $barColor }}"></div>
                    
                    <div class="p-4 pl-5">
                        <div class="flex justify-between items-start gap-3 mb-2.5">
                            <div>
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200 mb-1.5">{{ $b->inventory->name }} - {{ $b->quantity }} Unit</span>
                                <h3 class="font-bold text-gray-900 leading-tight">{{ $b->borrower_name }}</h3>
                                <p class="text-[10px] text-gray-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $b->borrower_contact }}
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                @if($b->status === 'approved')
                                    <span class="inline-flex px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-[10px] font-bold uppercase">Dipinjam</span>
                                @elseif($b->status === 'returned')
                                    <span class="inline-flex px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold uppercase">Selesai</span>
                                @elseif($b->status === 'rejected')
                                    <span class="inline-flex px-2 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded text-[10px] font-bold uppercase">Ditolak</span>
                                @else
                                    <span class="inline-flex px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-bold uppercase">Pending</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100 flex justify-between items-center text-[11px] text-gray-600">
                            <div><span class="font-semibold text-gray-400 block mb-0.5">Mulai Pinjam</span> {{ $b->borrow_date->format('d M Y') }}</div>
                            <div class="text-right"><span class="font-semibold text-gray-400 block mb-0.5">Rencana Kembali</span> {{ $b->expected_return_date->format('d M Y') }}</div>
                        </div>
                    </div>

                    @if($b->status === 'pending')
                    <div class="p-3 bg-gray-50/50 border-t border-gray-50 flex gap-2 pl-5">
                        <form action="{{ route('rw.inventaris.borrowings.approve', $b) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[11px] font-bold transition-colors shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Izinkan Pinjam
                            </button>
                        </form>
                        <form action="{{ route('rw.inventaris.borrowings.reject', $b) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-1.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 rounded-lg text-[11px] font-bold transition-colors shadow-sm">
                                Tolak
                            </button>
                        </form>
                    </div>
                    @elseif($b->status === 'approved')
                    <div class="p-3 bg-gray-50/50 border-t border-gray-50 flex gap-2 pl-5">
                        <form action="{{ route('rw.inventaris.borrowings.return', $b) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[11px] font-bold transition-colors shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Tandai Telah Dikembalikan
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            @else
            <!-- ZERO STATE -->
            <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Peminjaman</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">Warga dapat meminjam aset RT melalui portal publik mereka, dan riwayatnya akan muncul di sini.</p>
            </div>
            @endif

        </div>
        
    </div>
</x-rw-app-layout>

<style>
/* Utility for smooth tab fading */
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
