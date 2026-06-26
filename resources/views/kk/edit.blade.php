<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Edit Kartu Keluarga</h1>
                <p class="text-sm text-gray-500 mt-0.5 font-mono">{{ $family->nomor_kk }}</p>
            </div>
            <a href="{{ route('kk.show', $family) }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-100">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form action="{{ route('kk.update', $family) }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Nomor KK</label>
                    <input type="text" name="nomor_kk" required maxlength="16" minlength="16" value="{{ old('nomor_kk', $family->nomor_kk) }}" class="input-field">
                </div>
                <div>
                    <label class="label">Nama Kepala Keluarga</label>
                    <input type="text" name="nama_kepala_keluarga" required value="{{ old('nama_kepala_keluarga', $family->nama_kepala_keluarga) }}" class="input-field">
                </div>
            </div>

            <div>
                <label class="label">Alamat</label>
                <textarea name="alamat" rows="2" class="input-field">{{ old('alamat', $family->alamat) }}</textarea>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="label">RT</label>
                    <input type="text" name="rt" maxlength="5" value="{{ old('rt', $family->rt) }}" class="input-field">
                </div>
                <div>
                    <label class="label">RW</label>
                    <input type="text" name="rw" maxlength="5" value="{{ old('rw', $family->rw) }}" class="input-field">
                </div>
                <div class="col-span-2 sm:col-span-2">
                    <label class="label">Kode Pos</label>
                    <input type="text" name="kode_pos" maxlength="10" value="{{ old('kode_pos', $family->kode_pos) }}" class="input-field">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Desa/Kelurahan</label>
                    <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan', $family->desa_kelurahan) }}" class="input-field">
                </div>
                <div>
                    <label class="label">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $family->kecamatan) }}" class="input-field">
                </div>
                <div>
                    <label class="label">Kabupaten/Kota</label>
                    <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $family->kabupaten_kota) }}" class="input-field">
                </div>
                <div>
                    <label class="label">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $family->provinsi) }}" class="input-field">
                </div>
            </div>

            <div>
                <label class="label">Status Verifikasi</label>
                <select name="status_verifikasi" class="input-field max-w-xs">
                    <option value="draft" @selected(old('status_verifikasi', $family->status_verifikasi) === 'draft')>Draft</option>
                    <option value="terverifikasi" @selected(old('status_verifikasi', $family->status_verifikasi) === 'terverifikasi')>Terverifikasi</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                <a href="{{ route('kk.show', $family) }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-app-layout>
