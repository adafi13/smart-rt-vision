<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Peristiwa Warga</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola data kelahiran, kematian, pernikahan, & pindah domisili</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.peristiwa.index') }}" class="flex gap-2 w-full sm:max-w-xs">
                <select name="status" class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Status Verifikasi</option>
                    @foreach(['Pending', 'Terverifikasi', 'Ditolak'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>Status: {{ $s }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($lifeEvents->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Subjek / Keluarga</th>
                        <th class="px-6 py-4">Jenis Peristiwa</th>
                        <th class="px-6 py-4">Waktu & Keterangan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($lifeEvents as $le)
                    <tr class="hover:bg-gray-50/50 transition-colors group align-top">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ substr($le->nama_subjek, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 block">{{ $le->nama_subjek }}</span>
                                    @if($le->member)
                                    <span class="text-[10px] text-gray-500 mt-0.5 block">Dari Kk: <span class="font-semibold text-gray-700">{{ $le->member->family->nama_kepala_keluarga ?? $le->member->nama }}</span></span>
                                    @else
                                    <span class="text-[10px] text-gray-400 mt-0.5 block italic">Tanpa relasi KK</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $jenisColor = match($le->jenis_laporan) {
                                    'Kelahiran' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Kematian' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'Pernikahan' => 'bg-pink-50 text-pink-700 border-pink-200',
                                    default => 'bg-blue-50 text-blue-700 border-blue-200',
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide border {{ $jenisColor }}">
                                {{ $le->jenis_laporan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px] max-w-xs">
                            <p class="text-xs font-semibold text-gray-800 mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $le->tanggal_kejadian->translatedFormat('d F Y') }}
                            </p>
                            @if($le->keterangan)
                            <p class="text-[11px] text-gray-500 leading-relaxed">{{ Str::limit($le->keterangan, 60) }}</p>
                            @else
                            <p class="text-[11px] text-gray-400 italic">Tidak ada keterangan detail</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($le->status_verifikasi === 'Terverifikasi')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Terverifikasi
                                </span>
                            @elseif($le->status_verifikasi === 'Ditolak')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($le->bukti_dokumen)
                                <a href="{{ asset('storage/'.$le->bukti_dokumen) }}" target="_blank" class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors" title="Lihat Dokumen Bukti">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                </a>
                                @endif
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'verifikasi-{{ $le->id }}')" class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors" title="Verifikasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <form action="{{ route('admin.peristiwa.destroy', $le) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data peristiwa ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE COMPACT CARDS -->
        <div class="md:hidden space-y-3">
            @foreach($lifeEvents as $le)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <!-- Status Indicator Bar -->
                <div class="absolute left-0 top-0 bottom-0 w-1 
                    {{ $le->status_verifikasi === 'Terverifikasi' ? 'bg-emerald-500' : ($le->status_verifikasi === 'Ditolak' ? 'bg-rose-500' : 'bg-amber-500') }}">
                </div>
                
                <div class="p-4 pl-5">
                    <div class="flex justify-between items-start gap-3 mb-2.5">
                        <div>
                            @php
                                $jenisColorMb = match($le->jenis_laporan) {
                                    'Kelahiran' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Kematian' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'Pernikahan' => 'bg-pink-50 text-pink-700 border-pink-200',
                                    default => 'bg-blue-50 text-blue-700 border-blue-200',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border {{ $jenisColorMb }} mb-1.5">{{ $le->jenis_laporan }}</span>
                            <h3 class="font-bold text-gray-900 leading-tight">{{ $le->nama_subjek }}</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Tgl: {{ $le->tanggal_kejadian->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @if($le->status_verifikasi === 'Terverifikasi')
                                <span class="inline-flex px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold uppercase">Terverifikasi</span>
                            @elseif($le->status_verifikasi === 'Ditolak')
                                <span class="inline-flex px-2 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded text-[10px] font-bold uppercase">Ditolak</span>
                            @else
                                <span class="inline-flex px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-bold uppercase">Pending</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($le->keterangan)
                    <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100 mt-2">
                        <p class="text-[11px] text-gray-600">{{ $le->keterangan }}</p>
                    </div>
                    @endif
                </div>

                <div class="p-3 bg-gray-50/50 border-t border-gray-50 flex gap-2 pl-5">
                    @if($le->bukti_dokumen)
                        <a href="{{ asset('storage/'.$le->bukti_dokumen) }}" target="_blank" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[11px] font-bold rounded-lg transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Dokumen
                        </a>
                    @endif

                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'verifikasi-{{ $le->id }}')" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 text-indigo-700 text-[11px] font-bold rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verifikasi
                    </button>

                    <form action="{{ route('admin.peristiwa.destroy', $le) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data peristiwa ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-1.5 bg-rose-50 border border-rose-100 hover:bg-rose-100 text-rose-700 text-[11px] font-bold rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $lifeEvents->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Peristiwa</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Data laporan peristiwa kehidupan (kelahiran, kematian, pindah domisili) akan muncul di sini.</p>
        </div>
        @endif

    </div>

    <!-- MODAL VERIFIKASI -->
    @foreach($lifeEvents as $le)
    <x-modal name="verifikasi-{{ $le->id }}" focusable>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Verifikasi Peristiwa</h2>
                    <p class="text-xs text-gray-500">Subjek: <span class="font-semibold text-gray-700">{{ $le->nama_subjek }}</span></p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 mb-5 border border-gray-100 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Jenis</p>
                    <p class="font-semibold text-gray-900 text-sm mt-0.5">{{ $le->jenis_laporan }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Tanggal Kejadian</p>
                    <p class="font-semibold text-gray-900 text-sm mt-0.5">{{ $le->tanggal_kejadian->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            <form action="{{ route('admin.peristiwa.update', $le) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ubah Status Verifikasi</label>
                    <select name="status_verifikasi" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-medium">
                        <option value="Pending" @selected($le->status_verifikasi === 'Pending')>⌛ Pending (Menunggu Dokumen/Cek)</option>
                        <option value="Terverifikasi" @selected($le->status_verifikasi === 'Terverifikasi')>✅ Terverifikasi (Valid)</option>
                        <option value="Ditolak" @selected($le->status_verifikasi === 'Ditolak')>❌ Ditolak (Tidak Valid)</option>
                    </select>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Verifikasi</button>
                </div>
            </form>
        </div>
    </x-modal>
    @endforeach

</x-app-layout>
