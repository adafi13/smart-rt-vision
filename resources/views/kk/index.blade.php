<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Data Kartu Keluarga</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola arsip KK dan informasi dasar keluarga</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Status Filter Tabs -->
        <div class="border-b border-gray-200 bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                <a href="{{ route('kk.index') }}" 
                   class="border-b-2 pb-2 px-1 text-sm font-semibold whitespace-nowrap {{ empty($status) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua KK
                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ empty($status) ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900' }}">
                        {{ $totalAll }}
                    </span>
                </a>
                <a href="{{ route('kk.index', ['status' => 'pending']) }}" 
                   class="border-b-2 pb-2 px-1 text-sm font-semibold whitespace-nowrap {{ $status === 'pending' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pendaftaran Mandiri (Pending)
                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ $status === 'pending' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900' }}">
                        {{ $totalPending }}
                    </span>
                </a>
                <a href="{{ route('kk.index', ['status' => 'terverifikasi']) }}" 
                   class="border-b-2 pb-2 px-1 text-sm font-semibold whitespace-nowrap {{ $status === 'terverifikasi' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Terverifikasi
                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ $status === 'terverifikasi' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900' }}">
                        {{ $totalVerified }}
                    </span>
                </a>
                <a href="{{ route('kk.index', ['status' => 'ditolak']) }}" 
                   class="border-b-2 pb-2 px-1 text-sm font-semibold whitespace-nowrap {{ $status === 'ditolak' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Ditolak
                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ $status === 'ditolak' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900' }}">
                        {{ $totalDitolak }}
                    </span>
                </a>
                <a href="{{ route('kk.index', ['status' => 'draft']) }}" 
                   class="border-b-2 pb-2 px-1 text-sm font-semibold whitespace-nowrap {{ $status === 'draft' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Draft
                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ $status === 'draft' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900' }}">
                        {{ $totalDraft }}
                    </span>
                </a>
            </nav>
        </div>

        <!-- Actions & Search -->
        <div class="flex flex-col-reverse sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('kk.index') }}" class="flex-1 flex gap-2 w-full sm:max-w-md">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari NIK atau Nama..."
                           class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-900 placeholder-gray-400">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">Cari</button>
            </form>

            <div class="flex gap-2">
                <a href="{{ route('kk.import.form') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Import
                </a>
                <a href="{{ route('kk.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Input Manual
                </a>
                <a href="{{ route('kk.upload') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Scan KK (AI)
                </a>
            </div>
        </div>

        @if($families->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Keluarga</th>
                        <th class="px-6 py-4">Alamat Lengkap</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($families as $family)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                    {{ substr($family->nama_kepala_keluarga, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 block">{{ $family->nama_kepala_keluarga }}</span>
                                    <span class="text-xs text-gray-500 font-mono">{{ $family->nomor_kk }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                            <p class="text-sm text-gray-800">{{ Str::limit($family->alamat, 45) }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">RT {{ str_pad($family->rt, 3, '0', STR_PAD_LEFT) }}/RW {{ str_pad($family->rw, 3, '0', STR_PAD_LEFT) }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($family->status_verifikasi === 'terverifikasi')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified
                                </span>
                            @elseif($family->status_verifikasi === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 uppercase tracking-wide animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Pending
                                </span>
                            @elseif($family->status_verifikasi === 'ditolak')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('kk.show', $family) }}" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('kk.edit', $family) }}" class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('kk.destroy', $family) }}" method="POST" class="inline" id="delete-form-{{ $family->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $family->id }}')" class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
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
            @foreach($families as $family)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-50 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ substr($family->nama_kepala_keluarga, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ $family->nama_kepala_keluarga }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $family->nomor_kk }}</p>
                    </div>
                    <div>
                        @if($family->status_verifikasi === 'terverifikasi')
                            <span class="inline-flex px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">Verified</span>
                        @elseif($family->status_verifikasi === 'pending')
                            <span class="inline-flex px-2 py-1 rounded bg-indigo-100 text-indigo-700 text-[10px] font-bold animate-pulse">Pending</span>
                        @elseif($family->status_verifikasi === 'ditolak')
                            <span class="inline-flex px-2 py-1 rounded bg-rose-100 text-rose-700 text-[10px] font-bold">Ditolak</span>
                        @else
                            <span class="inline-flex px-2 py-1 rounded bg-amber-100 text-amber-700 text-[10px] font-bold">Draft</span>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-gray-50/50">
                    <p class="text-xs text-gray-600 mb-2 leading-relaxed">{{ Str::limit($family->alamat, 60) }}</p>
                    <div class="flex items-center gap-1.5 mb-4">
                        <span class="px-2 py-1 rounded bg-white border border-gray-200 text-gray-600 text-[10px] font-bold">RT {{ str_pad($family->rt, 3, '0', STR_PAD_LEFT) }}</span>
                        <span class="px-2 py-1 rounded bg-white border border-gray-200 text-gray-600 text-[10px] font-bold">RW {{ str_pad($family->rw, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('kk.show', $family) }}" class="flex-1 flex items-center justify-center py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl transition-colors">Detail</a>
                        <a href="{{ route('kk.edit', $family) }}" class="flex-1 flex items-center justify-center py-2 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-xl transition-colors">Edit</a>
                        <button type="button" onclick="confirmDelete('{{ $family->id }}')" class="flex-none px-4 flex items-center justify-center py-2 bg-rose-50 border border-rose-100 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $families->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Data KK</h3>
            <p class="text-xs text-gray-500 mb-5 max-w-sm mx-auto">Sistem belum mendeteksi adanya data Kartu Keluarga. Silakan klik "Tambah KK Baru".</p>
            <a href="{{ route('kk.upload') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah KK Baru
            </a>
        </div>
        @endif

    </div>

    @push('scripts')
    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Kartu Keluarga?',
            html: "Data KK dan <b>seluruh anggotanya</b> akan ikut terhapus permanen.<br>Aksi ini tidak bisa dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-xl',
                cancelButton: 'rounded-xl',
                popup: 'rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
    </script>
    @endpush
</x-app-layout>
