<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Buku Tamu (Security Gate)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola data tamu dan pantau keamanan lingkungan RT</p>
        </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
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
        
        <!-- DESKTOP TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Data Tamu</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Keperluan</th>
                        <th class="px-6 py-4">Waktu & Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($guestbooks as $guest)
                    <tr class="hover:bg-gray-50/50 transition-colors group align-top">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $guest->nama_tamu }}</div>
                            <div class="text-[11px] font-mono text-gray-500 mt-0.5">Plat: {{ $guest->plat_nomor ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                            {{ $guest->tujuan_rumah }}
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px] max-w-xs text-gray-600 text-xs line-clamp-2" title="{{ $guest->keperluan }}">
                            {{ $guest->keperluan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($guest->status === 'Masuk')
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200 mb-1">Di Dalam Area</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600 border border-gray-200 mb-1">Sudah Keluar</span>
                            @endif
                            <div class="text-[10px] text-gray-400 font-medium">
                                Masuk: {{ $guest->waktu_masuk->format('H:i - d M Y') }}
                                @if($guest->waktu_keluar)
                                    <br>Keluar: {{ $guest->waktu_keluar->format('H:i - d M Y') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($guest->status === 'Masuk')
                                <form action="{{ route('admin.guestbooks.mark-left', $guest) }}" method="POST" class="inline" onsubmit="return confirm('Tandai tamu ini sudah keluar area RT?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-100">Tandai Keluar</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.guestbooks.destroy', $guest) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tamu ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
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
            @foreach($guestbooks as $guest)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $guest->status === 'Masuk' ? 'bg-amber-500' : 'bg-gray-400' }}"></div>
                <div class="p-4 pl-5">
                    <div class="flex justify-between items-start gap-3 mb-2">
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">{{ $guest->nama_tamu }}</h3>
                            <p class="text-[11px] text-gray-500 font-mono mt-0.5">Plat: {{ $guest->plat_nomor ?: '-' }}</p>
                        </div>
                        @if($guest->status === 'Masuk')
                            <span class="inline-flex px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-bold uppercase shrink-0">Di Dalam Area</span>
                        @else
                            <span class="inline-flex px-2 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded text-[10px] font-bold uppercase shrink-0">Sudah Keluar</span>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100 mb-2">
                        <p class="text-[11px] text-gray-600"><span class="font-semibold text-gray-800">Tujuan:</span> {{ $guest->tujuan_rumah }}</p>
                        <p class="text-[11px] text-gray-600 mt-1"><span class="font-semibold text-gray-800">Keperluan:</span> {{ $guest->keperluan }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center mt-3">
                        <div class="text-[10px] text-gray-400">
                            Masuk: {{ $guest->waktu_masuk->format('H:i') }}
                            @if($guest->waktu_keluar) <br>Keluar: {{ $guest->waktu_keluar->format('H:i') }} @endif
                        </div>
                        <div class="flex gap-2">
                            @if($guest->status === 'Masuk')
                            <form action="{{ route('admin.guestbooks.mark-left', $guest) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] rounded-lg">Tandai Keluar</button>
                            </form>
                            @endif
                        </div>
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
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Buku Tamu Kosong</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Saat ini belum ada data tamu atau pengunjung yang masuk ke area RT.</p>
        </div>
        @endif
        </div>
    </div>
</x-app-layout>
