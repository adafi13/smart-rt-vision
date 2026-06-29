<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.documents.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Edit Info Dokumen</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui detail atau file untuk arsip dokumen ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 mb-1">Informasi Dokumen</h3>
                <p class="text-sm text-slate-500">Perbarui kolom di bawah ini. Kolom bertanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
            </div>

            <div class="p-8 space-y-8">
                <!-- Nama Dokumen -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Nama Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $document->title) }}" required placeholder="Contoh: SK Kelurahan 2026"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('title') border-red-500 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('category') border-red-500 @enderror">
                            <option value="sk" {{ old('category', $document->category) == 'sk' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                            <option value="notulen" {{ old('category', $document->category) == 'notulen' ? 'selected' : '' }}>Notulen Rapat</option>
                            <option value="laporan" {{ old('category', $document->category) == 'laporan' ? 'selected' : '' }}>Laporan (Keuangan/Kegiatan)</option>
                            <option value="surat_masuk" {{ old('category', $document->category) == 'surat_masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="surat_keluar" {{ old('category', $document->category) == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            <option value="umum" {{ old('category', $document->category) == 'umum' ? 'selected' : '' }}>Umum / Lainnya</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- File Dokumen -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Ganti File (Opsional)</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                        <p class="text-[11px] text-slate-400 mt-2">Biarkan kosong jika tidak ingin mengganti file lama. Maks 10MB.</p>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Deskripsi Tambahan -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi Tambahan (Opsional)</label>
                    <textarea name="description" rows="3" placeholder="Keterangan singkat..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('description') border-red-500 @enderror">{{ old('description', $document->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status Publik -->
                <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_public" value="1" id="is_public" {{ old('is_public', $document->is_public) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 bg-white border-emerald-300 rounded focus:ring-emerald-500">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="is_public" class="font-bold text-emerald-900 cursor-pointer">Bisa dilihat & diunduh oleh Warga (Publik)</label>
                        <p class="text-emerald-700 mt-0.5">Centang kotak ini jika dokumen boleh diakses bebas oleh seluruh warga.</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-900">Perhatian</p>
                        <p class="text-xs text-blue-700 mt-1">Perubahan yang Anda lakukan akan langsung tersimpan. Dokumen publik akan otomatis diperbarui di Portal Warga.</p>
                    </div>
                </div>

            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('admin.documents.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
