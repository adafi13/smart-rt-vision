<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.berita.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Edit Berita</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui informasi postingan ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.berita.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 mb-1">Informasi Berita</h3>
                <p class="text-sm text-slate-500">Perbarui kolom di bawah ini. Kolom bertanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
            </div>

            <div class="p-8 space-y-8">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Judul Postingan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $news->judul) }}" required 
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('judul') border-red-500 @enderror">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required class="w-full md:w-1/2 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('kategori') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="pengumuman" {{ old('kategori', $news->kategori) == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="berita" {{ old('kategori', $news->kategori) == 'berita' ? 'selected' : '' }}>Berita</option>
                        <option value="kegiatan" {{ old('kategori', $news->kategori) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    </select>
                    @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Isi -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Isi / Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="isi" rows="6" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('isi') border-red-500 @enderror">{{ old('isi', $news->isi) }}</textarea>
                    @error('isi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Foto Sampul -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Foto Sampul</label>
                    @if($news->gambar)
                        <div class="mb-3 relative inline-block rounded-xl overflow-hidden border border-slate-200">
                            <img src="{{ Storage::url($news->gambar) }}" alt="Foto Lama" class="w-48 h-32 object-cover">
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mb-2">Pilih file baru jika ingin mengganti gambar (Opsional).</p>
                    @endif
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                    <p class="text-xs text-slate-400 mt-2">Maksimal 2MB. Format: JPG, PNG, GIF.</p>
                    @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Tandai Penting -->
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_penting" value="1" id="is_penting" {{ old('is_penting', $news->is_penting) ? 'checked' : '' }} class="w-4 h-4 text-amber-600 bg-white border-amber-300 rounded focus:ring-amber-500">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="is_penting" class="font-bold text-amber-900 cursor-pointer">Tandai Sangat Penting (Sorot)</label>
                        <p class="text-amber-700 mt-0.5">Postingan ini akan disorot dengan tanda khusus di Portal Warga agar segera dibaca.</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-900">Perhatian</p>
                        <p class="text-xs text-blue-700 mt-1">Perubahan yang Anda lakukan akan langsung tersimpan dan terlihat di Portal Warga.</p>
                    </div>
                </div>

            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('admin.berita.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
