<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Iuran Warga (Kas Masuk)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Catat pembayaran iuran per KK</p>
            </div>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-iuran')" class="btn-primary w-full sm:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Iuran
            </button>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor KK atau nama kepala keluarga..." class="input-field flex-1">
            <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50">Cari</button>
        </form>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto w-full">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100" style="background:#fafafa;">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">KK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Jenis Iuran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap hidden sm:table-cell">Tgl Bayar</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $c)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $c->family->nama_kepala_keluarga }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $c->jenis_iuran }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $c->periode->translatedFormat('F Y') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-emerald-600 whitespace-nowrap">Rp {{ number_format($c->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $c->tanggal_bayar->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-iuran-{{ $c->id }}')" class="text-xs font-medium text-gray-500 hover:text-indigo-600 px-2 py-1.5 rounded-lg">Edit</button>
                            <form action="{{ route('admin.iuran.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data iuran ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium text-gray-400 hover:text-red-500 px-2 py-1.5 rounded-lg">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <x-modal name="edit-iuran-{{ $c->id }}" focusable>
                        <form action="{{ route('admin.iuran.update', $c) }}" method="POST" class="p-6 space-y-3">
                            @csrf @method('PUT')
                            <h2 class="text-base font-bold text-gray-900 mb-2">Edit Pembayaran Iuran</h2>
                            <div>
                                <label class="label">Kartu Keluarga</label>
                                <select name="family_id" required class="input-field">
                                    @foreach($families as $f)
                                        <option value="{{ $f->id }}" @selected($c->family_id === $f->id)>{{ $f->nomor_kk }} — {{ $f->nama_kepala_keluarga }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label">Jenis Iuran</label>
                                <input type="text" name="jenis_iuran" required value="{{ $c->jenis_iuran }}" class="input-field">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="label">Jumlah (Rp)</label>
                                    <input type="number" name="jumlah" required min="0" step="1000" value="{{ $c->jumlah }}" class="input-field">
                                </div>
                                <div>
                                    <label class="label">Periode</label>
                                    <input type="date" name="periode" required value="{{ $c->periode->format('Y-m-d') }}" class="input-field">
                                </div>
                            </div>
                            <div>
                                <label class="label">Tanggal Bayar</label>
                                <input type="date" name="tanggal_bayar" required value="{{ $c->tanggal_bayar->format('Y-m-d') }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Keterangan (opsional)</label>
                                <textarea name="keterangan" rows="2" class="input-field">{{ $c->keterangan }}</textarea>
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                                <button type="submit" class="btn-primary">Simpan</button>
                            </div>
                        </form>
                    </x-modal>
                    @empty
                    <tr><td colspan="6" class="py-14 text-center text-sm text-gray-400">Belum ada data iuran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contributions->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $contributions->links() }}</div>
        @endif
    </div>

    <x-modal name="tambah-iuran" focusable>
        <form action="{{ route('admin.iuran.store') }}" method="POST" class="p-6 space-y-3">
            @csrf
            <h2 class="text-base font-bold text-gray-900 mb-2">Tambah Pembayaran Iuran</h2>
            <div>
                <label class="label">Kartu Keluarga</label>
                <select name="family_id" required class="input-field">
                    <option value="">Pilih KK</option>
                    @foreach($families as $f)
                        <option value="{{ $f->id }}">{{ $f->nomor_kk }} — {{ $f->nama_kepala_keluarga }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Jenis Iuran</label>
                <input type="text" name="jenis_iuran" required class="input-field" placeholder="Sampah, Keamanan, Agustusan, dll">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="label">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" required min="0" step="1000" class="input-field">
                </div>
                <div>
                    <label class="label">Periode</label>
                    <input type="date" name="periode" required class="input-field">
                </div>
            </div>
            <div>
                <label class="label">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" required class="input-field">
            </div>
            <div>
                <label class="label">Keterangan (opsional)</label>
                <textarea name="keterangan" rows="2" class="input-field"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
