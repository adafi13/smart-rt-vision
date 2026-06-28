<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Buku Tamu (Security Gate)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola data tamu dan pantau keamanan lingkungan RT</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Filter Section -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.guestbooks.index') }}" class="flex gap-2 w-full sm:max-w-xs">
                <select name="status" class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Masuk" @selected(request('status') === 'Masuk')>Masih Bertamu (Masuk)</option>
                    <option value="Keluar" @selected(request('status') === 'Keluar')>Sudah Keluar</option>
                </select>
            </form>
        </div>

        @if($guestbooks->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Informasi Tamu</th>
                        <th class="px-6 py-4">Tujuan Kunjungan</th>
                        <th class="px-6 py-4">Waktu Kunjungan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($guestbooks as $guest)
                    <tr class="hover:bg-gray-50/50 transition-colors align-top">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ substr($guest->nama_tamu, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 block">{{ $guest->nama_tamu }}</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[11px] font-mono text-gray-500">Plat: {{ $guest->plat_nomor ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px] max-w-xs">
                            <div class="flex flex-col gap-1">
                                <span class="font-medium text-gray-800">{{ $guest->tujuan_rumah }}</span>
                                <p class="text-xs text-gray-500 line-clamp-2" title="{{ $guest->keperluan }}">{{ $guest->keperluan }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5 text-xs">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <span class="w-14 text-gray-400 font-medium">Masuk:</span>
                                    <span class="font-semibold">{{ $guest->waktu_masuk->format('H:i') }}</span>
                                    <span class="text-gray-400">{{ $guest->waktu_masuk->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <span class="w-14 text-gray-400 font-medium">Keluar:</span>
                                    @if($guest->waktu_keluar)
                                        <span class="font-semibold">{{ $guest->waktu_keluar->format('H:i') }}</span>
                                        <span class="text-gray-400">{{ $guest->waktu_keluar->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($guest->status === 'Masuk')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    DI DALAM AREA
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-bold">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    SUDAH KELUAR
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                @if($guest->status === 'Masuk')
                                <form action="{{ route('admin.guestbooks.mark-left', $guest) }}" method="POST" class="inline" onsubmit="return confirm('Tandai tamu ini sudah keluar area RT?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold text-emerald-700 bg-white hover:bg-emerald-50 border border-emerald-200 hover:border-emerald-300 rounded-xl transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar Area
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.guestbooks.destroy', $guest) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tamu ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all shadow-sm" title="Hapus Data">
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

        <!-- MOBILE PREMIUM CARDS -->
        <div class="md:hidden grid grid-cols-1 gap-4">
            @foreach($guestbooks as $guest)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex justify-between items-start gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ substr($guest->nama_tamu, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">{{ $guest->nama_tamu }}</h3>
                            <p class="text-[11px] text-gray-500 font-mono mt-0.5 tracking-wide">Plat: {{ $guest->plat_nomor ?: '-' }}</p>
                        </div>
                    </div>
                    @if($guest->status === 'Masuk')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[10px] font-bold uppercase shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Di Area
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-[10px] font-bold uppercase shrink-0">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Keluar
                        </span>
                    @endif
                </div>
                
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 mb-4">
                    <div class="grid grid-cols-1 gap-2 text-xs">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase tracking-wider font-bold mb-0.5">Tujuan:</span>
                            <span class="font-medium text-gray-800">{{ $guest->tujuan_rumah }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase tracking-wider font-bold mb-0.5">Keperluan:</span>
                            <span class="text-gray-700">{{ $guest->keperluan }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                    <div class="flex flex-col gap-1">
                        <div class="text-[10px] font-medium text-gray-500">
                            Masuk: <span class="text-gray-900 font-bold">{{ $guest->waktu_masuk->format('H:i') }}</span>
                        </div>
                        @if($guest->waktu_keluar)
                        <div class="text-[10px] font-medium text-gray-500">
                            Keluar: <span class="text-gray-900 font-bold">{{ $guest->waktu_keluar->format('H:i') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex gap-2">
                        @if($guest->status === 'Masuk')
                        <form action="{{ route('admin.guestbooks.mark-left', $guest) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-white border border-emerald-200 hover:bg-emerald-50 text-emerald-700 font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Tandai Keluar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $guestbooks->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Buku Tamu Kosong</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Saat ini belum ada data tamu atau pengunjung yang masuk ke area RT.</p>
        </div>
        @endif
    </div>
</x-app-layout>
