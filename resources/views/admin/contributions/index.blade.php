<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Iuran Warga (Kas Masuk)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Catat dan kelola pembayaran iuran per KK</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
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
        @if($errors->any())
            <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter, Search & Actions Section -->
        <div class="flex flex-col-reverse sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.iuran.index') }}" class="flex-1 flex gap-2 w-full sm:max-w-md">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari Nomor KK atau Nama..."
                           class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-900 placeholder-gray-400">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">Cari</button>
            </form>

            <div class="flex gap-2">
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-iuran')" class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Iuran
                </button>
            </div>
        </div>

        @if($contributions->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Keluarga</th>
                        <th class="px-6 py-4">Rincian Iuran</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4 text-center">Tgl Bayar</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($contributions as $c)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                    {{ substr($c->family->nama_kepala_keluarga, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 block">{{ $c->family->nama_kepala_keluarga }}</span>
                                    <span class="text-xs text-gray-500 font-mono">{{ $c->family->nomor_kk }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-800">{{ $c->jenis_iuran }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Periode: {{ $c->periode->translatedFormat('F Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Rp {{ number_format($c->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            {{ $c->tanggal_bayar->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-iuran-{{ $c->id }}')" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.iuran.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Hapus riwayat pembayaran iuran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE COMPACT CARDS -->
        <div class="md:hidden space-y-3">
            @foreach($contributions as $c)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-50 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ substr($c->family->nama_kepala_keluarga, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ $c->family->nama_kepala_keluarga }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $c->family->nomor_kk }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-sm font-bold text-emerald-600">Rp {{ number_format($c->jumlah, 0, ',', '.') }}</span>
                        <span class="text-[10px] text-gray-400">{{ $c->tanggal_bayar->format('d/m/y') }}</span>
                    </div>
                </div>

                <div class="p-4 bg-gray-50/50">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide">Jenis Iuran</span>
                            <span class="text-xs font-semibold text-gray-800">{{ $c->jenis_iuran }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide">Periode</span>
                            <span class="text-xs font-semibold text-gray-800">{{ $c->periode->translatedFormat('M Y') }}</span>
                        </div>
                    </div>

                    @if($c->keterangan)
                    <div class="mb-4">
                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide">Catatan</span>
                        <p class="text-xs text-gray-600">{{ $c->keterangan }}</p>
                    </div>
                    @endif

                    <div class="flex gap-2">
                        <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-iuran-{{ $c->id }}')" class="flex-1 flex items-center justify-center gap-1 py-2 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <form action="{{ route('admin.iuran.destroy', $c) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus riwayat pembayaran iuran ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-1 py-2 bg-rose-50 border border-rose-100 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $contributions->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Transaksi Iuran</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-5">Sistem belum mendeteksi riwayat pembayaran iuran. Klik "Tambah Iuran" untuk mencatat pembayaran warga.</p>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-iuran')" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Iuran Pertama
            </button>
        </div>
        @endif

    </div>

    <!-- MODAL TAMBAH IURAN -->
    <x-modal name="tambah-iuran" focusable>
        <form action="{{ route('admin.iuran.store') }}" method="POST" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Catat Pembayaran Kas</h2>
                    <p class="text-xs text-gray-500">Isi detail iuran yang diterima dari warga</p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kartu Keluarga (Pembayar) <span class="text-red-500">*</span></label>
                    <select name="family_id" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                        <option value="">-- Pilih Kepala Keluarga --</option>
                        @foreach($families as $f)
                            <option value="{{ $f->id }}">{{ $f->nomor_kk }} — {{ $f->nama_kepala_keluarga }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Iuran <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_iuran" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="Contoh: Kebersihan">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm font-medium">Rp</span>
                            </div>
                            <input type="number" name="jumlah" required min="0" step="1000" class="w-full pl-9 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-semibold text-emerald-700" placeholder="0">
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Periode Bulan <span class="text-red-500">*</span></label>
                        <input type="date" name="periode" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Bayar <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="Catatan tambahan bila ada..."></textarea>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Iuran</button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL EDIT IURAN DI LOOPING TERPISAH -->
    @foreach($contributions as $c)
    <x-modal name="edit-iuran-{{ $c->id }}" focusable>
        <form action="{{ route('admin.iuran.update', $c) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Ubah Data Iuran</h2>
                    <p class="text-xs text-gray-500">Perbarui rincian transaksi kas</p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kartu Keluarga (Pembayar) <span class="text-red-500">*</span></label>
                    <select name="family_id" required class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                        @foreach($families as $f)
                            <option value="{{ $f->id }}" @selected($c->family_id === $f->id)>{{ $f->nomor_kk }} — {{ $f->nama_kepala_keluarga }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Iuran <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_iuran" required value="{{ $c->jenis_iuran }}" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm font-medium">Rp</span>
                            </div>
                            <input type="number" name="jumlah" required min="0" step="1000" value="{{ $c->jumlah }}" class="w-full pl-9 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-semibold text-emerald-700">
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Periode Bulan <span class="text-red-500">*</span></label>
                        <input type="date" name="periode" required value="{{ $c->periode->format('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Bayar <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_bayar" required value="{{ $c->tanggal_bayar->format('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">{{ $c->keterangan }}</textarea>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </x-modal>
    @endforeach

</x-app-layout>
