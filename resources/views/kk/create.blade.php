<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('kk.index') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-gray-900">Input KK Manual</h1>
                <p class="text-sm text-gray-500 mt-0.5">Tambahkan data Kartu Keluarga dan Anggota Keluarga tanpa AI</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6" x-data="{
        members: [
            {
                id: Date.now(), nik: '', nama: '', hubungan_keluarga: 'KEPALA KELUARGA',
                jenis_kelamin: 'Laki-laki', agama: 'ISLAM', tempat_lahir: '',
                tanggal_lahir: '', status_perkawinan: 'BELUM KAWIN', pekerjaan: '',
                nama_ayah: '', nama_ibu: ''
            }
        ],
        errors: {},
        addMember() {
            this.members.push({
                id: Date.now(), nik: '', nama: '', hubungan_keluarga: 'ANGGOTA KELUARGA',
                jenis_kelamin: 'Laki-laki', agama: 'ISLAM', tempat_lahir: '',
                tanggal_lahir: '', status_perkawinan: 'BELUM KAWIN', pekerjaan: '',
                nama_ayah: '', nama_ibu: ''
            });
        },
        removeMember(index) {
            this.members.splice(index, 1);
        },
        getVal(id) {
            const el = document.getElementById(id);
            return el ? el.value.trim() : '';
        },
        validate() {
            this.errors = {};
            // KK fields
            if (!this.getVal('nomor_kk'))        this.errors.nomor_kk = 'Nomor KK wajib diisi.';
            if (!this.getVal('nama_kepala'))      this.errors.nama_kepala = 'Nama Kepala Keluarga wajib diisi.';
            if (!this.getVal('f_alamat'))         this.errors.alamat = 'Alamat wajib diisi.';
            if (!this.getVal('f_rt'))             this.errors.rt = 'RT wajib diisi.';
            if (!this.getVal('f_rw'))             this.errors.rw = 'RW wajib diisi.';
            if (!this.getVal('f_desa'))           this.errors.desa = 'Desa / Kelurahan wajib diisi.';
            if (!this.getVal('f_kecamatan'))      this.errors.kecamatan = 'Kecamatan wajib diisi.';
            if (!this.getVal('f_kabupaten'))      this.errors.kabupaten = 'Kabupaten / Kota wajib diisi.';
            if (!this.getVal('f_provinsi'))       this.errors.provinsi = 'Provinsi wajib diisi.';
            if (!this.getVal('f_kodepos'))        this.errors.kode_pos = 'Kode Pos wajib diisi.';

            // Anggota fields
            for (let i = 0; i < this.members.length; i++) {
                const m = this.members[i];
                if (!m.nik.trim())            this.errors['nik_'+i]            = 'NIK wajib diisi.';
                if (!m.nama.trim())           this.errors['nama_'+i]           = 'Nama lengkap wajib diisi.';
                if (!m.tempat_lahir.trim())   this.errors['tempat_lahir_'+i]   = 'Tempat lahir wajib diisi.';
                if (!m.tanggal_lahir.trim())  this.errors['tanggal_lahir_'+i]  = 'Tanggal lahir wajib diisi.';
                if (!m.pekerjaan.trim())      this.errors['pekerjaan_'+i]      = 'Pekerjaan wajib diisi.';
                if (!m.nama_ayah.trim())      this.errors['nama_ayah_'+i]      = 'Nama ayah wajib diisi.';
                if (!m.nama_ibu.trim())       this.errors['nama_ibu_'+i]       = 'Nama ibu wajib diisi.';
            }
            return Object.keys(this.errors).length === 0;
        },
        submitForm(e) {
            if (this.validate()) {
                e.target.submit();
            } else {
                this.$nextTick(() => {
                    const el = document.querySelector('.alpine-err-msg');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        }
    }">
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kk.store') }}" method="POST" class="space-y-6" @submit.prevent="submitForm($event)">
            @csrf

            <!-- Informasi Kepala Keluarga -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Kepala Keluarga</h2>
                    <p class="text-xs text-gray-500">Semua kolom bertanda <span class="text-rose-500 font-bold">*</span> wajib diisi.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Nomor KK --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nomor KK <span class="text-rose-500">*</span></label>
                            <input type="text" id="nomor_kk" name="nomor_kk" value="{{ old('nomor_kk') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.nomor_kk ? '!border-red-400 ring-1 ring-red-200' : ''"
                                   placeholder="16 digit angka">
                            <p x-show="errors.nomor_kk" x-text="errors.nomor_kk" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- Nama Kepala Keluarga --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Nama Kepala Keluarga <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama_kepala" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.nama_kepala ? '!border-red-400 ring-1 ring-red-200' : ''"
                                   placeholder="Nama Lengkap">
                            <p x-show="errors.nama_kepala" x-text="errors.nama_kepala" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="f_alamat" name="alamat" value="{{ old('alamat') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.alamat ? '!border-red-400 ring-1 ring-red-200' : ''"
                                   placeholder="Jalan, Blok, Nomor">
                            <p x-show="errors.alamat" x-text="errors.alamat" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- RT & RW --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-1.5">RT <span class="text-rose-500">*</span></label>
                                <input type="text" id="f_rt" name="rt" value="{{ old('rt') }}"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                       :class="errors.rt ? '!border-red-400 ring-1 ring-red-200' : ''"
                                       placeholder="001">
                                <p x-show="errors.rt" x-text="errors.rt" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-1.5">RW <span class="text-rose-500">*</span></label>
                                <input type="text" id="f_rw" name="rw" value="{{ old('rw') }}"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                       :class="errors.rw ? '!border-red-400 ring-1 ring-red-200' : ''"
                                       placeholder="001">
                                <p x-show="errors.rw" x-text="errors.rw" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                            </div>
                        </div>

                        {{-- Desa / Kelurahan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Desa / Kelurahan <span class="text-rose-500">*</span></label>
                            <input type="text" id="f_desa" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.desa ? '!border-red-400 ring-1 ring-red-200' : ''">
                            <p x-show="errors.desa" x-text="errors.desa" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- Kecamatan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Kecamatan <span class="text-rose-500">*</span></label>
                            <input type="text" id="f_kecamatan" name="kecamatan" value="{{ old('kecamatan') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.kecamatan ? '!border-red-400 ring-1 ring-red-200' : ''">
                            <p x-show="errors.kecamatan" x-text="errors.kecamatan" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- Kabupaten / Kota --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Kabupaten / Kota <span class="text-rose-500">*</span></label>
                            <input type="text" id="f_kabupaten" name="kabupaten_kota" value="{{ old('kabupaten_kota') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.kabupaten ? '!border-red-400 ring-1 ring-red-200' : ''">
                            <p x-show="errors.kabupaten" x-text="errors.kabupaten" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Provinsi <span class="text-rose-500">*</span></label>
                            <input type="text" id="f_provinsi" name="provinsi" value="{{ old('provinsi') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.provinsi ? '!border-red-400 ring-1 ring-red-200' : ''">
                            <p x-show="errors.provinsi" x-text="errors.provinsi" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        {{-- Kode Pos --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1.5">Kode Pos <span class="text-rose-500">*</span></label>
                            <input type="text" id="f_kodepos" name="kode_pos" value="{{ old('kode_pos') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm"
                                   :class="errors.kode_pos ? '!border-red-400 ring-1 ring-red-200' : ''"
                                   placeholder="Contoh: 12345">
                            <p x-show="errors.kode_pos" x-text="errors.kode_pos" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Anggota Keluarga -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Anggota Keluarga</h2>
                        <p class="text-xs text-gray-500">Semua kolom wajib diisi termasuk Kepala Keluarga.</p>
                    </div>
                    <button type="button" @click="addMember()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-bold rounded-xl transition-colors border border-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Anggota
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <template x-for="(member, index) in members" :key="member.id">
                        <div class="p-5 bg-gray-50 border border-gray-200 rounded-2xl relative">
                            <button type="button" @click="removeMember(index)"
                                    class="absolute -top-3 -right-3 w-8 h-8 bg-white border border-gray-200 hover:border-rose-300 rounded-full flex items-center justify-center text-gray-400 hover:text-rose-600 hover:bg-rose-50 shadow-sm transition-all"
                                    title="Hapus Anggota">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold" x-text="index + 1"></div>
                                <h3 class="font-bold text-gray-900 text-sm" x-text="member.nama || 'Anggota Baru'"></h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                {{-- NIK --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">NIK <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="member.nik" :name="'anggota['+index+'][nik]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['nik_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''"
                                           placeholder="16 digit">
                                    <p x-show="errors['nik_'+index]" x-text="errors['nik_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                                {{-- Nama --}}
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="member.nama" :name="'anggota['+index+'][nama]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['nama_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''"
                                           placeholder="Sesuai KTP/KK">
                                    <p x-show="errors['nama_'+index]" x-text="errors['nama_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                                {{-- Status Hub. Keluarga --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Hub. Keluarga <span class="text-rose-500">*</span></label>
                                    <select x-model="member.hubungan_keluarga" :name="'anggota['+index+'][hubungan_keluarga]'" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="KEPALA KELUARGA">Kepala Keluarga</option>
                                        <option value="ISTRI">Istri</option>
                                        <option value="SUAMI">Suami</option>
                                        <option value="ANAK">Anak</option>
                                        <option value="MENANTU">Menantu</option>
                                        <option value="CUCU">Cucu</option>
                                        <option value="ORANG TUA">Orang Tua</option>
                                        <option value="MERTUA">Mertua</option>
                                        <option value="FAMILI LAIN">Famili Lain</option>
                                    </select>
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                    <select x-model="member.jenis_kelamin" :name="'anggota['+index+'][jenis_kelamin]'" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                {{-- Agama --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Agama <span class="text-rose-500">*</span></label>
                                    <select x-model="member.agama" :name="'anggota['+index+'][agama]'" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="ISLAM">Islam</option>
                                        <option value="KRISTEN">Kristen</option>
                                        <option value="KATHOLIK">Katholik</option>
                                        <option value="HINDU">Hindu</option>
                                        <option value="BUDDHA">Buddha</option>
                                        <option value="KONGHUCU">Konghucu</option>
                                    </select>
                                </div>

                                {{-- Tempat Lahir --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Tempat Lahir <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="member.tempat_lahir" :name="'anggota['+index+'][tempat_lahir]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['tempat_lahir_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''"
                                           placeholder="Kota / Kabupaten">
                                    <p x-show="errors['tempat_lahir_'+index]" x-text="errors['tempat_lahir_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                    <input type="date" x-model="member.tanggal_lahir" :name="'anggota['+index+'][tanggal_lahir]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['tanggal_lahir_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''">
                                    <p x-show="errors['tanggal_lahir_'+index]" x-text="errors['tanggal_lahir_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                                {{-- Status Perkawinan --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Perkawinan <span class="text-rose-500">*</span></label>
                                    <select x-model="member.status_perkawinan" :name="'anggota['+index+'][status_perkawinan]'" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="BELUM KAWIN">Belum Kawin</option>
                                        <option value="KAWIN">Kawin</option>
                                        <option value="CERAI HIDUP">Cerai Hidup</option>
                                        <option value="CERAI MATI">Cerai Mati</option>
                                    </select>
                                </div>

                                {{-- Pekerjaan --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Pekerjaan <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="member.pekerjaan" :name="'anggota['+index+'][pekerjaan]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['pekerjaan_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''"
                                           placeholder="Contoh: Wiraswasta">
                                    <p x-show="errors['pekerjaan_'+index]" x-text="errors['pekerjaan_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                                {{-- Nama Ayah --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Ayah <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="member.nama_ayah" :name="'anggota['+index+'][nama_ayah]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['nama_ayah_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''">
                                    <p x-show="errors['nama_ayah_'+index]" x-text="errors['nama_ayah_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                                {{-- Nama Ibu --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Ibu <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="member.nama_ibu" :name="'anggota['+index+'][nama_ibu]'"
                                           class="w-full rounded-lg border-gray-200 text-sm"
                                           :class="errors['nama_ibu_'+index] ? '!border-red-400 ring-1 ring-red-200' : ''">
                                    <p x-show="errors['nama_ibu_'+index]" x-text="errors['nama_ibu_'+index]" class="alpine-err-msg mt-1 text-xs text-red-600 font-semibold"></p>
                                </div>

                            </div>
                        </div>
                    </template>

                    <div x-show="members.length === 0" class="text-center py-10">
                        <p class="text-gray-400 text-sm font-medium">Belum ada anggota. Klik "Tambah Anggota" di atas.</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-2 pb-8">
                <a href="{{ route('kk.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gray-900 text-white font-bold hover:bg-gray-800 transition-colors shadow-sm text-sm">
                    Simpan Data KK
                </button>
            </div>
        </form>
    </div>

</x-app-layout>
