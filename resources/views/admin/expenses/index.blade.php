<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Pengeluaran Kas RT</h1>
            <p class="text-sm text-gray-500 mt-0.5">Catat dan pantau arus kas keluar</p>
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
        @if($errors->any())
            <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter, Search & Actions Section -->
        <div class="flex flex-col-reverse sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="flex-1 flex gap-2 w-full sm:max-w-md">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari keterangan atau kategori..."
                           class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-900 placeholder-gray-400 shadow-sm">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors shadow-sm">Cari</button>
            </form>

            <div class="flex gap-2">
                <a href="{{ route('admin.pengeluaran.create') }}" class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Pengeluaran
                </a>
            </div>
        </div>

        @if($expenses->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4 text-center">Tgl Keluar</th>
                        <th class="px-6 py-4 text-center">Bukti Nota</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($expenses as $e)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-normal">
                            <span class="font-bold text-gray-900 block line-clamp-2" title="{{ $e->keterangan }}">{{ $e->keterangan }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600 border border-gray-200">
                                {{ $e->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                Rp {{ number_format($e->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            {{ $e->tanggal_keluar->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($e->bukti_nota)
                                <a href="{{ asset('storage/'.$e->bukti_nota) }}" target="_blank" class="inline-flex items-center justify-center p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors" title="Lihat Bukti Nota">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                </a>
                            @else
                                <span class="text-xs text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.pengeluaran.edit', $e) }}" class="inline-flex items-center p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.pengeluaran.destroy', $e) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengeluaran ini?')">
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
            @foreach($expenses as $e)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-50 flex justify-between items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600 border border-gray-200 mb-1.5">{{ $e->kategori }}</span>
                        <p class="font-bold text-gray-900 leading-snug">{{ $e->keterangan }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="block text-sm font-bold text-rose-600">Rp {{ number_format($e->jumlah, 0, ',', '.') }}</span>
                        <span class="text-[10px] text-gray-400 mt-1 block">{{ $e->tanggal_keluar->format('d/m/y') }}</span>
                    </div>
                </div>

                <div class="p-3 bg-gray-50/50 flex gap-2">
                    @if($e->bukti_nota)
                        <a href="{{ asset('storage/'.$e->bukti_nota) }}" target="_blank" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[11px] font-bold rounded-lg transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Lihat Nota
                        </a>
                    @else
                        <div class="flex-1 flex items-center justify-center py-1.5 border border-dashed border-gray-200 text-gray-400 text-[11px] font-medium rounded-lg">Tanpa Nota</div>
                    @endif

                    <a href="{{ route('admin.pengeluaran.edit', $e) }}" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 text-indigo-700 text-[11px] font-bold rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>

                    <form action="{{ route('admin.pengeluaran.destroy', $e) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data pengeluaran ini?')">
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
            {{ $expenses->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Pengeluaran</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-5">Sistem belum mendeteksi catatan pengeluaran kas RT. Klik "Catat Pengeluaran" untuk mulai.</p>
            <a href="{{ route('admin.pengeluaran.create') }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Catat Pengeluaran Pertama
            </a>
        </div>
        @endif

    </div>

    </div>
</x-app-layout>
