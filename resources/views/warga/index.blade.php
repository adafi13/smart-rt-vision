<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Data Warga</h1>
            <p class="text-sm text-gray-500 mt-0.5">Seluruh warga yang terdaftar dalam sistem</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">

        <!-- Premium Toolbar -->
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm mb-6">
            <form method="GET" action="{{ route('warga.index') }}" class="flex flex-col gap-5">
                
                <div class="flex flex-col md:flex-row gap-4 md:items-end">
                    <!-- Search Input -->
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Pencarian Data</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Ketik NIK atau nama warga..."
                                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 text-gray-900 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="w-full md:w-48">
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Urutkan Berdasarkan</label>
                        <div class="relative">
                            <select name="sort" class="w-full py-2.5 pl-4 pr-10 bg-gray-50/50 border border-gray-200 text-gray-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer shadow-sm">
                                <option value="keluarga" {{ request('sort')=='keluarga'?'selected':'' }}>Per Keluarga</option>
                                <option value="nama_asc" {{ request('sort')=='nama_asc'?'selected':'' }}>Nama (A-Z)</option>
                                <option value="nama_desc" {{ request('sort')=='nama_desc'?'selected':'' }}>Nama (Z-A)</option>
                                <option value="umur_tua" {{ request('sort')=='umur_tua'?'selected':'' }}>Paling Tua</option>
                                <option value="umur_muda" {{ request('sort')=='umur_muda'?'selected':'' }}>Paling Muda</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Dropdown -->
                    <div class="w-full md:w-40">
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Filter Gender</label>
                        <div class="relative">
                            <select name="jenis_kelamin" class="w-full py-2.5 pl-4 pr-10 bg-gray-50/50 border border-gray-200 text-gray-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer shadow-sm">
                                <option value="">Semua</option>
                                <option value="Laki-laki" {{ request('jenis_kelamin')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                                <option value="Perempuan" {{ request('jenis_kelamin')=='Perempuan'?'selected':'' }}>Perempuan</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl text-sm shadow-md shadow-indigo-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Terapkan
                        </button>
                    </div>
                </div>

                <!-- Footer area of the toolbar -->
                <div class="border-t border-gray-100 pt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        Menampilkan <span class="font-bold text-gray-900">{{ $members->total() }}</span> hasil pencarian
                    </div>
                    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                        <a href="{{ route('export.excel') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 hover:border-emerald-300 rounded-xl text-xs font-bold transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Excel
                        </a>
                        <a href="{{ route('export.pdf') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 rounded-xl text-xs font-bold transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto w-full">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100" style="background: #fafafa;">
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">NIK</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Nama Lengkap</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell whitespace-nowrap">Gender</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell whitespace-nowrap">Tgl. Lahir</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell whitespace-nowrap">Agama</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell whitespace-nowrap">Pendidikan</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell whitespace-nowrap">Pekerjaan</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell whitespace-nowrap">Status Kawin</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden xl:table-cell whitespace-nowrap">No KK</th>
                        <th class="px-4 sm:px-5 py-3 sm:py-3.5 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 whitespace-nowrap">
                            <span class="text-xs sm:text-sm font-mono font-semibold text-indigo-600">{{ $member->nik }}</span>
                        </td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap">{{ $member->nama }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 hidden sm:table-cell whitespace-nowrap">
                            @if($member->jenis_kelamin == 'Laki-laki')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Laki-laki</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold bg-pink-50 text-pink-700 border border-pink-200">Perempuan</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm text-gray-500 hidden md:table-cell whitespace-nowrap">{{ $member->tanggal_lahir }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm text-gray-500 hidden lg:table-cell whitespace-nowrap">{{ $member->agama ?? '-' }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm text-gray-500 hidden lg:table-cell whitespace-nowrap">{{ $member->pendidikan ?? '-' }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm text-gray-500 hidden lg:table-cell whitespace-nowrap">{{ $member->pekerjaan ?? '-' }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm text-gray-500 hidden lg:table-cell whitespace-nowrap">{{ $member->status_perkawinan ?? '-' }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-xs sm:text-sm font-mono text-gray-400 hidden xl:table-cell whitespace-nowrap">{{ $member->family->nomor_kk ?? '-' }}</td>
                        <td class="px-4 sm:px-5 py-3 sm:py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('cetak_surat', $member->id) }}" target="_blank" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 px-2 py-1.5 rounded-lg">Cetak Surat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 sm:py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center bg-gray-50 border border-gray-100">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500">Belum ada data warga</p>
                                <a href="{{ route('kk.upload') }}" class="text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-500">Upload KK untuk menambahkan warga &rarr;</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="px-4 py-3 sm:px-5 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $members->links() }}</div>
        @endif
    </div>
</x-app-layout>
