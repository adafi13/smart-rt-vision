<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Berita &amp; Pengumuman</h1>
                <p class="text-sm text-gray-500 mt-0.5">Tampil di halaman depan publik</p>
            </div>
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-berita')" class="btn-primary w-full sm:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tulis Berita
            </button>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="input-field flex-1">
            <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50">Cari</button>
        </form>

        <div class="space-y-3">
            @forelse($newsList as $n)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[11px] font-semibold text-indigo-500 uppercase">{{ $n->kategori }}</span>
                        @if($n->is_penting)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600">PENTING</span>
                        @endif
                    </div>
                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $n->judul }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($n->isi, 100) }}</p>
                    <p class="text-[11px] text-gray-400 mt-1.5">{{ $n->created_at->translatedFormat('d F Y') }}</p>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-berita-{{ $n->id }}')" class="text-xs font-medium text-gray-500 hover:text-indigo-600 px-2 py-1.5 rounded-lg whitespace-nowrap">Edit</button>
                    <form action="{{ route('admin.berita.destroy', $n) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs font-medium text-gray-400 hover:text-red-500 px-2 py-1.5 rounded-lg whitespace-nowrap">Hapus</button>
                    </form>
                </div>
            </div>

            <x-modal name="edit-berita-{{ $n->id }}" focusable>
                <form action="{{ route('admin.berita.update', $n) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3">
                    @csrf @method('PUT')
                    <h2 class="text-base font-bold text-gray-900 mb-2">Edit Berita / Pengumuman</h2>
                    <div>
                        <label class="label">Judul</label>
                        <input type="text" name="judul" required value="{{ $n->judul }}" class="input-field">
                    </div>
                    <div>
                        <label class="label">Kategori</label>
                        <select name="kategori" required class="input-field">
                            @foreach(['Pengumuman', 'Berita', 'Undangan'] as $kat)
                                <option @selected($n->kategori === $kat)>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Isi</label>
                        <textarea name="isi" required rows="4" class="input-field">{{ $n->isi }}</textarea>
                    </div>
                    <div>
                        <label class="label">Gambar (opsional)</label>
                        @if($n->gambar)
                            <a href="{{ asset('storage/'.$n->gambar) }}" target="_blank" class="block text-xs text-indigo-600 font-medium mb-1.5">Lihat gambar saat ini</a>
                        @endif
                        <input type="file" name="gambar" accept="image/*" class="input-field">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_penting" value="1" class="rounded" @checked($n->is_penting)>
                        Tandai sebagai penting (highlight merah)
                    </label>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </x-modal>
            @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-14 text-center text-sm text-gray-400">Belum ada berita.</div>
            @endforelse
        </div>
        @if($newsList->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $newsList->links() }}</div>
        @endif
    </div>

    <x-modal name="tambah-berita" focusable>
        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3">
            @csrf
            <h2 class="text-base font-bold text-gray-900 mb-2">Tulis Berita / Pengumuman</h2>
            <div>
                <label class="label">Judul</label>
                <input type="text" name="judul" required class="input-field">
            </div>
            <div>
                <label class="label">Kategori</label>
                <select name="kategori" required class="input-field">
                    <option value="">Pilih kategori</option>
                    <option>Pengumuman</option>
                    <option>Berita</option>
                    <option>Undangan</option>
                </select>
            </div>
            <div>
                <label class="label">Isi</label>
                <textarea name="isi" required rows="4" class="input-field"></textarea>
            </div>
            <div>
                <label class="label">Gambar (opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="input-field">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_penting" value="1" class="rounded">
                Tandai sebagai penting (highlight merah)
            </label>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="submit" class="btn-primary">Publikasikan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
