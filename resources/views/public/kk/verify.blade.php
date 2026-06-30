<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Verifikasi data Kartu Keluarga mandiri.">
    <title>Verifikasi Data KK · {{ $tenant->name ?? 'SmartRT Vision' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { font-family: 'Outfit', sans-serif; box-sizing: border-box; }
        body { background: #f8f9fc; color: #0f172a; }
        .glass-dark { background: rgba(15,15,25,0.85); backdrop-filter: blur(14px) saturate(160%); -webkit-backdrop-filter: blur(14px) saturate(160%); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-12">

    @include('partials.public-nav')

    @include('partials.public-page-header', [
        'title' => 'Verifikasi Data Anda',
        'subtitle' => 'Tinjau hasil pembacaan AI di bawah ini. Pastikan semua data sudah sesuai dengan dokumen asli Anda.',
    ])

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        
        <!-- Premium Stepper Progress UI -->
        <div class="max-w-md mx-auto mb-12">
            <div class="flex items-center justify-between relative">
                <!-- Line background -->
                <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-200/80 z-0"></div>
                <!-- Line active progress -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-indigo-600 z-0" style="width: 50%;"></div>

                <!-- Step 1 (Completed) -->
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-md transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500">Unggah KK</span>
                </div>
                <!-- Step 2 (Active) -->
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-indigo-100 transition-all duration-300">2</div>
                    <span class="text-[11px] font-bold text-slate-800">Verifikasi</span>
                </div>
                <!-- Step 3 (Inactive) -->
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-bold text-sm shadow-sm transition-all duration-300">3</div>
                    <span class="text-[11px] font-semibold text-slate-400">Selesai</span>
                </div>
            </div>
        </div>
        @if(session('error'))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium shadow-sm mb-6">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div>
                    <h4 class="font-bold mb-0.5 text-rose-900">Pendaftaran Gagal</h4>
                    <p class="text-rose-700 text-xs">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('warga.kk.store', ['tenant' => $tenant->slug]) }}" method="POST">
            @csrf
            <input type="hidden" name="foto_path" value="{{ $fotoPath ?? '' }}">

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

                <!-- Column Kiri: Pratinjau Dokumen Asli (Sticky) -->
                <div class="lg:col-span-2">
                    <div class="sticky top-20 space-y-4">
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Dokumen Asli</h3>
                                @if($fotoPath)
                                <a href="{{ asset('storage/'.$fotoPath) }}" target="_blank" class="text-xs text-indigo-600 hover:underline flex items-center gap-1 font-bold">
                                    Buka Tab Baru <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                @endif
                            </div>
                            @if($fotoPath)
                                @if(\Illuminate\Support\Str::endsWith($fotoPath, '.pdf'))
                                    <iframe src="{{ asset('storage/'.$fotoPath) }}" class="w-full h-[35vh] lg:h-[65vh] rounded-2xl border border-slate-100 bg-slate-50"></iframe>
                                @else
                                    <div class="overflow-auto max-h-[35vh] lg:max-h-[65vh] rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-center p-2">
                                        <img src="{{ asset('storage/'.$fotoPath) }}" class="max-w-full h-auto object-contain cursor-zoom-in" onclick="window.open(this.src)" title="Klik untuk memperbesar">
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12 text-gray-400">
                                    <p class="text-xs">Dokumen tidak ditemukan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Column Kanan: Formulir Verifikasi (Kepala & Anggota) -->
                <div class="lg:col-span-3 space-y-4">
                    
                    <!-- Data Kepala Keluarga -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2.5 bg-slate-50">
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

                    <!-- Anggota Keluarga -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-100">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h2 class="text-sm font-semibold text-gray-900">Anggota Keluarga</h2>
                            </div>
                            @if(isset($data['anggota']))
                                <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-gray-500 font-medium">{{ count($data['anggota']) }} orang</span>
                            @endif
                        </div>

                        <div class="p-4 space-y-3 max-h-[580px] overflow-y-auto">
                            @if(isset($data['anggota']) && is_array($data['anggota']))
                                @foreach($data['anggota'] as $i => $anggota)
                                @php $warn = $warningsAnggota[$i] ?? []; @endphp
                                <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/50 space-y-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">#{{ $i+1 }}</span>
                                        <span class="text-xs font-medium text-slate-600">{{ $anggota['hubungan_keluarga'] ?? '' }}</span>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">NIK</label>
                                        <input type="text" name="anggota[{{ $i }}][nik]" value="{{ $anggota['nik'] ?? '' }}"
                                               class="w-full rounded-lg px-3 py-2 text-xs font-mono text-gray-900 outline-none border transition-all {{ isset($warn['nik']) ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white focus:border-indigo-400' }}">
                                        @if(isset($warn['nik'])) <p class="text-xs text-amber-600 mt-1">{{ implode(', ', (array)$warn['nik']) }}</p> @endif
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">Nama</label>
                                        <input type="text" name="anggota[{{ $i }}][nama]" value="{{ $anggota['nama'] ?? '' }}"
                                               class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-slate-200 bg-white focus:border-indigo-400 transition-all">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Gender</label>
                                            <select name="anggota[{{ $i }}][jenis_kelamin]"
                                                     class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border transition-all {{ isset($warn['jenis_kelamin']) ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white focus:border-indigo-400' }}">
                                                 <option value="Laki-laki" {{ ($anggota['jenis_kelamin']??'')==='Laki-laki'?'selected':'' }}>Laki-laki</option>
                                                 <option value="Perempuan" {{ ($anggota['jenis_kelamin']??'')==='Perempuan'?'selected':'' }}>Perempuan</option>
                                             </select>
                                             @if(isset($warn['jenis_kelamin'])) <p class="text-[10px] text-amber-600 mt-0.5">{{ implode(', ', (array)$warn['jenis_kelamin']) }}</p> @endif
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Tgl. Lahir</label>
                                             <input type="date" name="anggota[{{ $i }}][tanggal_lahir]" value="{{ $anggota['tanggal_lahir'] ?? '' }}"
                                                    class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border transition-all {{ isset($warn['tanggal_lahir']) ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white focus:border-indigo-400' }}">
                                             @if(isset($warn['tanggal_lahir'])) <p class="text-[10px] text-amber-600 mt-0.5">{{ implode(', ', (array)$warn['tanggal_lahir']) }}</p> @endif
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Tempat Lahir</label>
                                            <input type="text" name="anggota[{{ $i }}][tempat_lahir]" value="{{ $anggota['tempat_lahir'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-slate-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Agama</label>
                                             <input type="text" name="anggota[{{ $i }}][agama]" value="{{ $anggota['agama'] ?? '' }}"
                                                    class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border transition-all {{ isset($warn['agama']) ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white focus:border-indigo-400' }}">
                                             @if(isset($warn['agama'])) <p class="text-[10px] text-amber-600 mt-0.5">{{ implode(', ', (array)$warn['agama']) }}</p> @endif
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Pendidikan</label>
                                            <input type="text" name="anggota[{{ $i }}][pendidikan]" value="{{ $anggota['pendidikan'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-slate-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Pekerjaan</label>
                                            <input type="text" name="anggota[{{ $i }}][pekerjaan]" value="{{ $anggota['pekerjaan'] ?? '' }}"
                                                   class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border border-slate-200 bg-white focus:border-indigo-400 transition-all">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Status Kawin</label>
                                             <input type="text" name="anggota[{{ $i }}][status_perkawinan]" value="{{ $anggota['status_perkawinan'] ?? '' }}"
                                                    class="w-full rounded-lg px-3 py-2 text-xs text-gray-900 outline-none border transition-all {{ isset($warn['status_perkawinan']) ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white focus:border-indigo-400' }}">
                                             @if(isset($warn['status_perkawinan'])) <p class="text-[10px] text-amber-600 mt-0.5">{{ implode(', ', (array)$warn['status_perkawinan']) }}</p> @endif
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
                <a href="{{ route('warga.kk.upload', ['tenant' => $tenant->slug]) }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all bg-emerald-600 hover:bg-emerald-700 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Ajukan Pendaftaran KK
                </button>
            </div>
        </form>
    </main>

    @include('partials.public-footer')
</body>
</html>
