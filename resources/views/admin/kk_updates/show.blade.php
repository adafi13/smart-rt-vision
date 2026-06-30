<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ __('Review Pembaruan KK') }}
            </h2>
            <a href="{{ route('kk.updates.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($update->status === 'pending')
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 sm:rounded-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            Mohon periksa perubahan data KK warga di bawah ini. Jika disetujui, data anggota keluarga lama akan ditimpa/disinkronisasikan dengan data terbaru ini.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Foto KK -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-900">Foto KK Baru (Diunggah Warga)</h3>
                    </div>
                    <div class="p-6">
                        @if($update->foto_path)
                            <a href="{{ Storage::url($update->foto_path) }}" target="_blank">
                                <img src="{{ Storage::url($update->foto_path) }}" class="w-full h-auto rounded-xl border border-slate-200 hover:opacity-90 transition-opacity" alt="Foto KK">
                            </a>
                            <p class="text-xs text-slate-500 mt-2 text-center">Klik gambar untuk memperbesar</p>
                        @else
                            <div class="p-12 text-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                                <span class="text-slate-400">Tidak ada foto KK</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Dasar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-900">Informasi Keluarga (Baru)</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="sm:col-span-1">
                                <dt class="text-xs font-semibold text-slate-500 uppercase">Nomor KK</dt>
                                <dd class="mt-1 text-sm text-slate-900 font-bold">{{ $newData['nomor_kk'] ?? $family->nomor_kk }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-xs font-semibold text-slate-500 uppercase">Kepala Keluarga</dt>
                                <dd class="mt-1 text-sm text-slate-900 font-bold">{{ $newData['nama_kepala_keluarga'] ?? $family->nama_kepala_keluarga }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-semibold text-slate-500 uppercase">Alamat</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $newData['alamat'] ?? $family->alamat }} 
                                    RT {{ $newData['rt'] ?? $family->rt }}/RW {{ $newData['rw'] ?? $family->rw }}
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-semibold text-slate-500 uppercase">Wilayah</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $newData['desa_kelurahan'] ?? $family->desa_kelurahan }}, 
                                    {{ $newData['kecamatan'] ?? $family->kecamatan }}, 
                                    {{ $newData['kabupaten_kota'] ?? $family->kabupaten_kota }}, 
                                    {{ $newData['provinsi'] ?? $family->provinsi }} 
                                    {{ $newData['kode_pos'] ?? $family->kode_pos }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Daftar Anggota -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Anggota Keluarga (Berdasarkan KK Baru)</h3>
                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg">{{ count($newData['anggota'] ?? []) }} Orang</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4">NIK</th>
                                <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                                <th scope="col" class="px-6 py-4">Status Hub.</th>
                                <th scope="col" class="px-6 py-4">TTL</th>
                                <th scope="col" class="px-6 py-4">Pekerjaan</th>
                                <th scope="col" class="px-6 py-4">Status Baru?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($newData['anggota']) && is_array($newData['anggota']))
                                @foreach($newData['anggota'] as $anggota)
                                    @php
                                        // Cek apakah NIK ini sudah ada di database (anggota lama)
                                        $isExisting = $family->members->where('nik', $anggota['nik'])->first();
                                    @endphp
                                    <tr class="bg-white border-b hover:bg-slate-50">
                                        <td class="px-6 py-4 font-mono text-slate-900">{{ $anggota['nik'] }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $anggota['nama'] }}</td>
                                        <td class="px-6 py-4">{{ $anggota['hubungan_keluarga'] ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $anggota['tempat_lahir'] ?? '-' }}, {{ $anggota['tanggal_lahir'] ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $anggota['pekerjaan'] ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if($isExisting)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Terdaftar</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Warga Baru</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                        Tidak ada data anggota yang terekstrak.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            @if ($update->status === 'pending')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Keputusan</h3>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <form action="{{ route('kk.updates.approve', $update) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pembaruan data KK ini? Data keluarga di sistem akan ditimpa dengan data terbaru ini.')">
                            @csrf
                            <button type="submit" class="w-full flex justify-center items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-sm hover:shadow-md hover:shadow-emerald-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui & Sinkronisasi Data
                            </button>
                        </form>
                        
                        <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="flex-1 flex justify-center items-center gap-2 px-6 py-3 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-bold rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak Permohonan
                        </button>
                    </div>
                </div>

                <!-- Modal Tolak -->
                <div id="reject-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('reject-modal').classList.add('hidden')"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            <form action="{{ route('kk.updates.reject', $update) }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">Tolak Pembaruan KK</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-slate-500 mb-4">Berikan alasan penolakan agar warga dapat memperbaikinya.</p>
                                                <textarea name="alasan_penolakan" rows="3" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: Foto KK buram/tidak terbaca dengan jelas..." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                        Tolak Permohonan
                                    </button>
                                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
