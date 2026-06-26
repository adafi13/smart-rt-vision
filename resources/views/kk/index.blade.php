<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Data Kartu Keluarga</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola semua KK yang terdaftar</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('kk.import.form') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Import Excel
                </a>
                <a href="{{ route('kk.upload') }}" class="flex-1 sm:flex-none btn-primary justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah KK
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">

        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <form method="GET" action="{{ route('kk.index') }}" class="flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nomor KK atau nama..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm bg-white border border-gray-200 text-gray-900 placeholder-gray-400 outline-none transition-all focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
            </div>
            <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition-colors w-full sm:w-auto">Cari</button>
        </form>

        @if($families->isNotEmpty())
        <!-- Desktop: table -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto w-full">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100" style="background: #fafafa;">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">No KK</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Kepala Keluarga</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Alamat</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-5 py-3.5 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($families as $family)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-sm font-mono font-semibold text-indigo-600">{{ $family->nomor_kk }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-sm font-medium text-gray-900 whitespace-normal">{{ $family->nama_kepala_keluarga }}</td>
                        <td class="px-5 py-3.5 text-sm text-gray-500 whitespace-normal">
                            {{ Str::limit($family->alamat, 30) }} RT {{ $family->rt }}/{{ $family->rw }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ ucfirst($family->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                            <a href="{{ route('kk.show', $family) }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-all inline-block">Lihat</a>
                            <a href="{{ route('kk.edit', $family) }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-all inline-block">Edit</a>
                            <form action="{{ route('kk.destroy', $family) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus data KK ini beserta semua anggotanya?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-500 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile: cards -->
        <div class="md:hidden space-y-3">
            @foreach($families as $family)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-mono font-semibold text-indigo-600">{{ $family->nomor_kk }}</p>
                        <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $family->nama_kepala_keluarga }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ ucfirst($family->status_verifikasi) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ Str::limit($family->alamat, 40) }} RT {{ $family->rt }}/{{ $family->rw }}</p>
                <div class="flex items-center gap-1 mt-3 pt-3 border-t border-gray-50">
                    <a href="{{ route('kk.show', $family) }}" class="flex-1 text-center text-xs font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 px-2 py-2 rounded-lg transition-all">Lihat</a>
                    <a href="{{ route('kk.edit', $family) }}" class="flex-1 text-center text-xs font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 px-2 py-2 rounded-lg transition-all">Edit</a>
                    <form action="{{ route('kk.destroy', $family) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin hapus data KK ini beserta semua anggotanya?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-center text-xs font-medium text-gray-400 hover:text-red-500 hover:bg-red-50 px-2 py-2 rounded-lg transition-all">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 sm:py-20 text-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center bg-gray-50 border border-gray-100">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-500">Belum ada data KK</p>
                <a href="{{ route('kk.upload') }}" class="text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-500">Upload KK pertama &rarr;</a>
            </div>
        </div>
        @endif

        @if($families->hasPages())
            <div class="px-4 py-3 sm:px-5 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $families->links() }}</div>
        @endif
    </div>
</x-app-layout>
