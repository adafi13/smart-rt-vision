<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Pengeluaran Kas RT</h1>
                <p class="text-sm text-gray-500 mt-0.5">Catat pengeluaran kas RT</p>
            </div>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-pengeluaran')" class="btn-primary w-full sm:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengeluaran
            </button>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan atau kategori..." class="input-field flex-1">
            <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50">Cari</button>
        </form>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto w-full">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100" style="background:#fafafa;">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Keterangan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap hidden sm:table-cell">Tanggal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $e->keterangan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">{{ $e->kategori }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-rose-600 whitespace-nowrap">Rp {{ number_format($e->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $e->tanggal_keluar->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-pengeluaran-{{ $e->id }}')" class="text-xs font-medium text-gray-500 hover:text-indigo-600 px-2 py-1.5 rounded-lg">Edit</button>
                            <form action="{{ route('admin.pengeluaran.destroy', $e) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium text-gray-400 hover:text-red-500 px-2 py-1.5 rounded-lg">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <x-modal name="edit-pengeluaran-{{ $e->id }}" focusable>
                        <form action="{{ route('admin.pengeluaran.update', $e) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3">
                            @csrf @method('PUT')
                            <h2 class="text-base font-bold text-gray-900 mb-2">Edit Pengeluaran</h2>
                            <div>
                                <label class="label">Keterangan</label>
                                <input type="text" name="keterangan" required value="{{ $e->keterangan }}" class="input-field">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="label">Jumlah (Rp)</label>
                                    <input type="number" name="jumlah" required min="0" step="1000" value="{{ $e->jumlah }}" class="input-field">
                                </div>
                                <div>
                                    <label class="label">Tanggal Keluar</label>
                                    <input type="date" name="tanggal_keluar" required value="{{ $e->tanggal_keluar->format('Y-m-d') }}" class="input-field">
                                </div>
                            </div>
                            <div>
                                <label class="label">Kategori</label>
                                <input type="text" name="kategori" required value="{{ $e->kategori }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Bukti Nota (opsional)</label>
                                @if($e->bukti_nota)
                                    <a href="{{ asset('storage/'.$e->bukti_nota) }}" target="_blank" class="block text-xs text-indigo-600 font-medium mb-1.5">Lihat bukti saat ini</a>
                                @endif
                                <input type="file" name="bukti_nota" accept="image/*" class="input-field">
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                                <button type="submit" class="btn-primary">Simpan</button>
                            </div>
                        </form>
                    </x-modal>
                    @empty
                    <tr><td colspan="5" class="py-14 text-center text-sm text-gray-400">Belum ada data pengeluaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $expenses->links() }}</div>
        @endif
    </div>

    <x-modal name="tambah-pengeluaran" focusable>
        <form action="{{ route('admin.pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3">
            @csrf
            <h2 class="text-base font-bold text-gray-900 mb-2">Tambah Pengeluaran</h2>
            <div>
                <label class="label">Keterangan</label>
                <input type="text" name="keterangan" required class="input-field" placeholder="Bayar sampah Februari">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="label">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" required min="0" step="1000" class="input-field">
                </div>
                <div>
                    <label class="label">Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" required class="input-field">
                </div>
            </div>
            <div>
                <label class="label">Kategori</label>
                <input type="text" name="kategori" required class="input-field" placeholder="Keamanan, Kebersihan, Sosial, Operasional">
            </div>
            <div>
                <label class="label">Bukti Nota (opsional)</label>
                <input type="file" name="bukti_nota" accept="image/*" class="input-field">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
