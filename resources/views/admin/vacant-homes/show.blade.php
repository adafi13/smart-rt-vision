<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vacant-homes.index') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-gray-900">Detail Penjagaan Rumah</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $vacantHome->pelapor_nama }} - {{ $vacantHome->alamat_rumah }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Informasi Rumah (Kiri) -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-900">Informasi Rumah</h2>
                        @if($vacantHome->status === 'Aktif')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-bold uppercase shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                KOSONG
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-[10px] font-bold uppercase shrink-0">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                SELESAI
                            </span>
                        @endif
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Pemilik / Pelapor</p>
                            <p class="font-bold text-gray-900">{{ $vacantHome->pelapor_nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Alamat / Blok</p>
                            <p class="font-medium text-gray-800">{{ $vacantHome->alamat_rumah }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Kontak Darurat</p>
                            <p class="font-mono text-gray-700 font-medium">{{ $vacantHome->nomor_wa }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Tanggal Pergi</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $vacantHome->tanggal_pergi->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-1">Estimasi Pulang</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $vacantHome->tanggal_pulang->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if($vacantHome->catatan_warga)
                        <div class="pt-2 border-t border-gray-50">
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Catatan Pesan Warga</p>
                            <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-sm text-amber-900 italic">
                                "{{ $vacantHome->catatan_warga }}"
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($vacantHome->status === 'Aktif')
                <div class="bg-indigo-50 rounded-2xl shadow-sm border border-indigo-100 p-5">
                    <h3 class="font-bold text-indigo-900 mb-2">Pencatatan Log Patroli</h3>
                    <p class="text-xs text-indigo-700 leading-relaxed mb-1">
                        Silakan rekap laporan kondisi rumah beserta foto bukti patroli harian yang dikirimkan oleh petugas keamanan / satpam melalui form <strong>Tambah Log Patroli Baru</strong> di bawah ini.
                    </p>
                </div>
                @endif
            </div>

            <!-- Log Patroli (Kanan) -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                
                @if($vacantHome->status === 'Aktif')
                <div id="patrolForm" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <h2 class="font-bold text-gray-900">Tambah Log Patroli Baru</h2>
                    </div>
                    <form action="{{ route('admin.vacant-homes.log.store', $vacantHome) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1">Foto Bukti Patroli <span class="text-rose-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-2">Gunakan kamera HP untuk memfoto bagian depan rumah secara langsung.</p>
                            <input type="file" name="foto_bukti" accept="image/*" capture="environment" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-200 rounded-xl bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-1">Catatan Kondisi (Opsional)</label>
                            <textarea name="catatan_petugas" rows="2" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm" placeholder="Contoh: Aman terkendali, gembok terkunci..."></textarea>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-sm transition-colors text-sm">Simpan Log</button>
                        </div>
                    </form>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-50">
                        <h2 class="font-bold text-gray-900">Riwayat Patroli</h2>
                    </div>
                    <div class="p-5">
                        @if($vacantHome->logs->isEmpty())
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-900">Belum Ada Patroli</p>
                                <p class="text-xs text-gray-500 mt-1">Satpam belum melakukan pengecekan ke rumah ini.</p>
                            </div>
                        @else
                            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                                @foreach($vacantHome->logs as $log)
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                    <!-- Marker -->
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-indigo-100 text-indigo-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 font-bold text-xs uppercase">
                                        {{ substr($log->petugas_nama, 0, 2) }}
                                    </div>
                                    <!-- Card -->
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl border border-gray-100 shadow-sm shadow-gray-200/50">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                            <div class="font-bold text-gray-900 text-sm">{{ $log->petugas_nama }}</div>
                                            <div class="text-[10px] font-medium text-gray-500">{{ $log->waktu_patroli->format('d M Y - H:i') }}</div>
                                        </div>
                                        @if($log->catatan_petugas)
                                            <p class="text-xs text-gray-600 mb-3 bg-gray-50 p-2 rounded-lg border border-gray-100">{{ $log->catatan_petugas }}</p>
                                        @endif
                                        <a href="{{ Storage::url($log->foto_bukti) }}" target="_blank" class="block rounded-lg overflow-hidden border border-gray-100 hover:opacity-90 transition-opacity">
                                            <img src="{{ Storage::url($log->foto_bukti) }}" alt="Foto Patroli" class="w-full h-32 sm:h-48 object-cover">
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
