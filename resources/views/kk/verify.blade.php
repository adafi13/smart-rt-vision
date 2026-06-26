<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kk.upload') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-gray-900">Verifikasi Data KK</h1>
                <p class="text-sm text-gray-500 mt-0.5">Tinjau hasil ekstraksi AI sebelum disimpan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl">
        <form action="{{ route('kk.store') }}" method="POST">
            @csrf
            <input type="hidden" name="foto_path" value="{{ $fotoPath ?? '' }}">

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

                <!-- KK Info (3/5) -->
                <div class="lg:col-span-3 space-y-4">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2.5 bg-gray-50">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-indigo-100">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-900">Data Kepala Keluarga</h2>
                        </div>

                        <div class="p-5">
                            @if(!empty($warningsKk))
                                <div class="flex items-start gap-2.5 px-4 py-3.5 rounded-xl mb-5 bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <ul class="space-y-0.5 text-xs">
                                        @foreach($warningsKk as $msgs)
                                            @foreach((array)$msgs as $m)<li>{{ $m }}</li>@endforeach
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @php
                            $inp = 'w-full rounded-lg px-3.5 py-2.5 text-sm text-gray-900 outline-none transition-all border focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 bg-white';
                            $inpW = 'w-full rounded-lg px-3.5 py-2.5 text-sm text-gray-900 outline-none transition-all border border-amber-300 bg-amber-50 focus:border-amber-400 focus:ring-2 focus:ring-amber-100';
                            $lbl = 'block text-xs font-medium text-gray-600 mb-1.5';
                            @endphp

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="{{ $lbl }}">Nomor KK</label>
                                    <input type="text" name="nomor_kk" value="{{ old('nomor_kk', $data['nomor_kk'] ?? '') }}"
                                           class="{{ isset($warningsKk['nomor_kk']) ? $inpW : $inp }}" style="border-color: #e5e7eb;" required>
                                </div>
                                <div>
                                    <label class="{{ $lbl }}">Nama Kepala Keluarga</label>
                                    <input type="text" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga', $data['nama_kepala_keluarga'] ?? '') }}"
                                           class="{{ $inp }}" style="border-color: #e5e7eb;" required>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $lbl }}">Alamat</label>
                                    <input type="text" name="alamat" value="{{ old('alamat', $data['alamat'] ?? '') }}"
                                           class="{{ $inp }}" style="border-color: #e5e7eb;">
                                </div>
                                @foreach([['rt','RT'],['rw','RW'],['desa_kelurahan','Desa/Kelurahan'],['kecamatan','Kecamatan'],['kabupaten_kota','Kabupaten/Kota'],['provinsi','Provinsi'],['kode_pos','Kode Pos']] as [$n,$l])
                                <div>
                                    <label class="{{ $lbl }}">{{ $l }}</label>
                                    <input type="text" name="{{ $n }}" value="{{ old($n, $data[$n] ?? '') }}"
                                           class="{{ $inp }}" style="border-color: #e5e7eb;">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Members (2/5) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-100">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h2 class="text-sm font-semibold text-gray-900">Anggota Keluarga</h2>
                            </div>
                            @if(isset($data['anggota']))
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500 font-medium">{{ count($data['anggota']) }} orang</span>
                            @endif
                        </div>

                        <div class="p-4 space-y-3 max-h-[580px] overflow-y-auto">
                            @if(isset($data['anggota']) && is_array($data['anggota']))
                                @foreach($data['anggota'] as $i => $anggota)
                                @php $warn = $warningsAnggota[$i] ?? []; @endphp
                                <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 space-y-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">#{{ $i+1 }}</span>
                                        <span class="text-xs font-medium text-gray-600">{{ $anggota['hubungan_keluarga'] ?? '' }}</span>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">NIK</label>
                                        <input type="text" name="anggota[{{ $i }}][nik]" value="{{ $anggota['nik'] ?? '' }}"
                                               class="w-full rounded-lg px-3 py-2 text-xs font-mono text-gray-900 outline-none border transition-all {{ isset($warn['nik']) ? 'border-amber-300 bg-amber-50' : 'border-gray-200 bg-white focus:border-indigo-400' }}">
                                        @if(isset($warn['nik'])) <p class="text-xs text-amber-600 mt-1">{{ implode(', ', (array)$warn['nik']) }}</p> @endif
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">Nama</label>
                                        <input type="text" name="anggota[{{ $i }}][nama]" value="{{ $anggota['nama'] ?? '' }}"
                                               class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Gender</label>
                                            <select name="anggota[{{ $i }}][jenis_kelamin]"
                                                    class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                                <option value="Laki-laki" {{ ($anggota['jenis_kelamin']??'')==='Laki-laki'?'selected':'' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ ($anggota['jenis_kelamin']??'')==='Perempuan'?'selected':'' }}>Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Tgl. Lahir</label>
                                            <input type="date" name="anggota[{{ $i }}][tanggal_lahir]" value="{{ $anggota['tanggal_lahir'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Tempat Lahir</label>
                                            <input type="text" name="anggota[{{ $i }}][tempat_lahir]" value="{{ $anggota['tempat_lahir'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Agama</label>
                                            <input type="text" name="anggota[{{ $i }}][agama]" value="{{ $anggota['agama'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Pendidikan</label>
                                            <input type="text" name="anggota[{{ $i }}][pendidikan]" value="{{ $anggota['pendidikan'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Pekerjaan</label>
                                            <input type="text" name="anggota[{{ $i }}][pekerjaan]" value="{{ $anggota['pekerjaan'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Status Kawin</label>
                                            <input type="text" name="anggota[{{ $i }}][status_perkawinan]" value="{{ $anggota['status_perkawinan'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-gray-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                    </div>
                                    <input type="hidden" name="anggota[{{ $i }}][hubungan_keluarga]" value="{{ $anggota['hubungan_keluarga'] ?? '' }}">
                                </div>
                                @endforeach
                            @else
                                <p class="text-sm text-center text-gray-400 py-8">Tidak ada anggota terdeteksi.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 mt-4">
                <a href="{{ route('kk.upload') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all"
                        style="background: #059669;"
                        onmouseover="this.style.background='#047857'; this.style.boxShadow='0 4px 12px rgba(5,150,105,0.3)'"
                        onmouseout="this.style.background='#059669'; this.style.boxShadow='none'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data Terverifikasi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
