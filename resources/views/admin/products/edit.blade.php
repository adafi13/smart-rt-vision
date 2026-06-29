<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.umkm.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Edit UMKM Warga</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui informasi produk atau jasa UMKM.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.umkm.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 mb-1">Informasi UMKM</h3>
                <p class="text-sm text-slate-500">Perbarui kolom di bawah ini. Kolom bertanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
            </div>

            <div class="p-8 space-y-8">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Nama Produk / Jasa <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required 
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('nama_produk') border-red-500 @enderror">
                    @error('nama_produk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('kategori') border-red-500 @enderror">
                            <option value="">Pilih Kategori...</option>
                            <option value="Makanan & Minuman" {{ old('kategori', $product->kategori) == 'Makanan & Minuman' ? 'selected' : '' }}>Makanan & Minuman</option>
                            <option value="Jasa" {{ old('kategori', $product->kategori) == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                            <option value="Kerajinan" {{ old('kategori', $product->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                            <option value="Sembako" {{ old('kategori', $product->kategori) == 'Sembako' ? 'selected' : '' }}>Sembako</option>
                            <option value="Lainnya" {{ old('kategori', $product->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Harga -->
                    <div x-data="{ 
                        rawVal: '{{ old('harga', $product->harga) }}', 
                        formattedVal: '{{ old('harga', $product->harga) ? number_format(old('harga', $product->harga), 0, '', '.') : '' }}',
                        formatNumber() {
                            let val = this.formattedVal.replace(/\D/g, '');
                            this.rawVal = val;
                            this.formattedVal = val ? new Intl.NumberFormat('id-ID').format(val) : '';
                        }
                    }">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Harga (Rp)</label>
                        <input type="text" x-model="formattedVal" @input="formatNumber" 
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm font-bold text-indigo-600 @error('harga') border-red-500 @enderror"
                            placeholder="Kosongkan jika tidak pasti">
                        <input type="hidden" name="harga" x-model="rawVal">
                        @error('harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Penjual Info -->
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-900 mb-4">Informasi Penjual</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Penjual <span class="text-red-500">*</span></label>
                            <input type="text" name="penjual" value="{{ old('penjual', $product->penjual) }}" required class="w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                            @error('penjual') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">No. WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $product->whatsapp) }}" required class="w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                            @error('whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi Produk (Opsional)</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Foto Sampul -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Ganti Foto Produk</label>
                    @if($product->foto)
                        <div class="mb-3 relative inline-block rounded-xl overflow-hidden border border-slate-200">
                            <img src="{{ Storage::url($product->foto) }}" alt="Foto Lama" class="w-48 h-32 object-cover">
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mb-2">Pilih file baru jika ingin mengganti gambar (Opsional).</p>
                    @endif
                    <input type="file" name="foto" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                    <p class="text-xs text-slate-400 mt-2">Maksimal 2MB. Format: JPG, PNG, GIF.</p>
                    @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Tersedia / Ready -->
                <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_ready" value="1" id="is_ready" {{ old('is_ready', $product->is_ready) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 bg-white border-emerald-300 rounded focus:ring-emerald-500">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="is_ready" class="font-bold text-emerald-900 cursor-pointer">Status: Siap Jual / Tersedia</label>
                        <p class="text-emerald-700 mt-0.5">Hilangkan centang jika stok produk sedang kosong.</p>
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
                <a href="{{ route('admin.umkm.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
