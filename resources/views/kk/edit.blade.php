<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Data Keluarga</h1>
                <p class="text-sm text-gray-500 mt-1 font-mono">#{{ $family->nomor_kk }}</p>
            </div>
            <a href="{{ route('kk.show', $family) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Detail
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('kk.update', $family) }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-6 sm:p-8 space-y-8">
                <!-- Section 1: Identitas -->
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        Identitas Utama
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor KK <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_kk" required maxlength="16" minlength="16" value="{{ old('nomor_kk', $family->nomor_kk) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kepala Keluarga <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kepala_keluarga" required value="{{ old('nama_kepala_keluarga', $family->nama_kepala_keluarga) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Alamat -->
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Informasi Alamat
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Jalan/Blok/No)</label>
                            <textarea name="alamat" rows="2" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('alamat', $family->alamat) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">RT</label>
                                <input type="text" name="rt" maxlength="5" value="{{ old('rt', $family->rt) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">RW</label>
                                <input type="text" name="rw" maxlength="5" value="{{ old('rw', $family->rw) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos</label>
                                <input type="text" name="kode_pos" maxlength="10" value="{{ old('kode_pos', $family->kode_pos) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-5 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Desa / Kelurahan</label>
                                <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan', $family->desa_kelurahan) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan', $family->kecamatan) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kabupaten / Kota</label>
                                <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $family->kabupaten_kota) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                                <input type="text" name="provinsi" value="{{ old('provinsi', $family->provinsi) }}" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Status -->
                <div>
                    <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verifikasi Data
                    </h3>
                    <div class="max-w-md">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Keabsahan</label>
                        <select name="status_verifikasi" class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm cursor-pointer">
                            <option value="draft" @selected(old('status_verifikasi', $family->status_verifikasi) === 'draft')>⚠️ Draft (Menunggu Kelengkapan)</option>
                            <option value="terverifikasi" @selected(old('status_verifikasi', $family->status_verifikasi) === 'terverifikasi')>✅ Terverifikasi (Valid & Lengkap)</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('kk.show', $family) }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">Batalkan</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
