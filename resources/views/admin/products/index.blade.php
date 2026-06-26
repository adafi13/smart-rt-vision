<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Pasar Warga (UMKM)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Produk/jasa warga yang tampil di halaman depan publik</p>
            </div>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-produk')" class="btn-primary w-full sm:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk
            </button>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau penjual..." class="input-field flex-1">
            <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50">Cari</button>
        </form>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse($products as $p)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3">
                <p class="text-sm font-bold text-gray-900 truncate">{{ $p->nama_produk }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $p->penjual }}</p>
                @if($p->harga)
                <p class="text-xs font-semibold text-indigo-600 mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                @endif
                <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] {{ $p->is_ready ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $p->is_ready ? 'Tersedia' : 'Tidak tersedia' }}</span>
                <div class="flex items-center gap-2 mt-2">
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-produk-{{ $p->id }}')" class="text-xs font-medium text-gray-500 hover:text-indigo-600">Edit</button>
                    <form action="{{ route('admin.umkm.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs font-medium text-gray-400 hover:text-red-500">Hapus</button>
                    </form>
                </div>
            </div>

            <x-modal name="edit-produk-{{ $p->id }}" focusable>
                <form action="{{ route('admin.umkm.update', $p) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3">
                    @csrf @method('PUT')
                    <h2 class="text-base font-bold text-gray-900 mb-2">Edit Produk UMKM</h2>
                    <div>
                        <label class="label">Nama Produk</label>
                        <input type="text" name="nama_produk" required value="{{ $p->nama_produk }}" class="input-field">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="label">Nama Penjual</label>
                            <input type="text" name="penjual" required value="{{ $p->penjual }}" class="input-field">
                        </div>
                        <div>
                            <label class="label">No. WhatsApp</label>
                            <input type="text" name="whatsapp" required value="{{ $p->whatsapp }}" class="input-field">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="label">Harga (Rp, opsional)</label>
                            <input type="number" name="harga" min="0" step="500" value="{{ $p->harga }}" class="input-field">
                        </div>
                        <div>
                            <label class="label">Kategori</label>
                            <input type="text" name="kategori" required value="{{ $p->kategori }}" class="input-field">
                        </div>
                    </div>
                    <div>
                        <label class="label">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" rows="2" class="input-field">{{ $p->deskripsi }}</textarea>
                    </div>
                    <div>
                        <label class="label">Foto (opsional)</label>
                        @if($p->foto)
                            <a href="{{ asset('storage/'.$p->foto) }}" target="_blank" class="block text-xs text-indigo-600 font-medium mb-1.5">Lihat foto saat ini</a>
                        @endif
                        <input type="file" name="foto" accept="image/*" class="input-field">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_ready" value="1" class="rounded" @checked($p->is_ready)>
                        Tersedia / siap jual
                    </label>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </x-modal>
            @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-100 shadow-sm py-14 text-center text-sm text-gray-400">Belum ada produk UMKM.</div>
            @endforelse
        </div>
        @if($products->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $products->links() }}</div>
        @endif
    </div>

    <x-modal name="tambah-produk" focusable>
        <form action="{{ route('admin.umkm.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3">
            @csrf
            <h2 class="text-base font-bold text-gray-900 mb-2">Tambah Produk UMKM</h2>
            <div>
                <label class="label">Nama Produk</label>
                <input type="text" name="nama_produk" required class="input-field">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="label">Nama Penjual</label>
                    <input type="text" name="penjual" required class="input-field">
                </div>
                <div>
                    <label class="label">No. WhatsApp</label>
                    <input type="text" name="whatsapp" required class="input-field" placeholder="08xxxxxxxxxx">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="label">Harga (Rp, opsional)</label>
                    <input type="number" name="harga" min="0" step="500" class="input-field">
                </div>
                <div>
                    <label class="label">Kategori</label>
                    <input type="text" name="kategori" required class="input-field" placeholder="Makanan, Jasa, Pakaian">
                </div>
            </div>
            <div>
                <label class="label">Deskripsi (opsional)</label>
                <textarea name="deskripsi" rows="2" class="input-field"></textarea>
            </div>
            <div>
                <label class="label">Foto (opsional)</label>
                <input type="file" name="foto" accept="image/*" class="input-field">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_ready" value="1" checked class="rounded">
                Tersedia / siap jual
            </label>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
