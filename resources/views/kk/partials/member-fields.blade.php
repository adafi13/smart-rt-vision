@php $member = $member ?? null; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">NIK</label>
        <input type="text" name="nik" required maxlength="16" minlength="16" value="{{ old('nik', $member?->nik) }}" class="input-field">
    </div>
    <div>
        <label class="label">Nama Lengkap</label>
        <input type="text" name="nama" required value="{{ old('nama', $member?->nama) }}" class="input-field">
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Jenis Kelamin</label>
        <select name="jenis_kelamin" required class="input-field">
            <option value="Laki-laki" @selected(old('jenis_kelamin', $member?->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
            <option value="Perempuan" @selected(old('jenis_kelamin', $member?->jenis_kelamin) === 'Perempuan')>Perempuan</option>
        </select>
    </div>
    <div>
        <label class="label">Hubungan Keluarga</label>
        <input type="text" name="hubungan_keluarga" required value="{{ old('hubungan_keluarga', $member?->hubungan_keluarga) }}" placeholder="Kepala Keluarga, Istri, Anak, dll" class="input-field">
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $member?->tempat_lahir) }}" class="input-field">
    </div>
    <div>
        <label class="label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $member?->tanggal_lahir) }}" class="input-field">
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Agama</label>
        <input type="text" name="agama" value="{{ old('agama', $member?->agama) }}" class="input-field">
    </div>
    <div>
        <label class="label">Status Perkawinan</label>
        <input type="text" name="status_perkawinan" value="{{ old('status_perkawinan', $member?->status_perkawinan) }}" class="input-field">
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Pendidikan</label>
        <input type="text" name="pendidikan" value="{{ old('pendidikan', $member?->pendidikan) }}" class="input-field">
    </div>
    <div>
        <label class="label">Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $member?->pekerjaan) }}" class="input-field">
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Kewarganegaraan</label>
        <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', $member?->kewarganegaraan ?? 'WNI') }}" class="input-field">
    </div>
    <div>
        <label class="label">Status Warga</label>
        <select name="status_warga" class="input-field">
            <option value="Aktif" @selected(old('status_warga', $member?->status_warga ?? 'Aktif') === 'Aktif')>Aktif</option>
            <option value="Pindah" @selected(old('status_warga', $member?->status_warga) === 'Pindah')>Pindah</option>
            <option value="Meninggal" @selected(old('status_warga', $member?->status_warga) === 'Meninggal')>Meninggal</option>
        </select>
    </div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Nama Ayah</label>
        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $member?->nama_ayah) }}" class="input-field">
    </div>
    <div>
        <label class="label">Nama Ibu</label>
        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $member?->nama_ibu) }}" class="input-field">
    </div>
</div>
