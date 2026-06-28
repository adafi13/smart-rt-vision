<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Penjagaan Rumah Kosong</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau dan kelola data rumah warga yang ditinggal bepergian (mudik)</p>
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
            <form method="GET" action="{{ route('admin.vacant-homes.index') }}" class="flex gap-2 w-full sm:max-w-xs">
                <select name="status" class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Aktif" @selected(request('status') === 'Aktif')>Sedang Ditinggal (Aktif)</option>
                    <option value="Selesai" @selected(request('status') === 'Selesai')>Sudah Kembali (Selesai)</option>
                </select>
            </form>
        </div>

        @if($vacantHomes->isNotEmpty())
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($vacantHomes as $home)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="p-4 flex-1">
                    <div class="flex justify-between items-start gap-4 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 leading-tight">{{ $home->pelapor_nama }}</h3>
                                <p class="text-[11px] text-gray-500 font-mono mt-0.5 tracking-wide">{{ $home->nomor_wa }}</p>
                            </div>
                        </div>
                        @if($home->status === 'Aktif')
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
                    
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 mb-3">
                        <span class="text-gray-400 block text-[10px] uppercase tracking-wider font-bold mb-1">Alamat Rumah:</span>
                        <span class="font-medium text-gray-800 text-sm">{{ $home->alamat_rumah }}</span>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-600 mb-2">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase">Pergi</span>
                            <span class="font-medium">{{ $home->tanggal_pergi->format('d M Y') }}</span>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-[10px] text-gray-400 font-bold uppercase">Rencana Pulang</span>
                            <span class="font-medium">{{ $home->tanggal_pulang->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-50 p-3 flex gap-2 justify-between bg-gray-50/50">
                    <a href="{{ route('admin.vacant-homes.show', $home) }}" class="flex-1 text-center px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-xs rounded-xl shadow-sm transition-all">
                        Lihat Detail & Log
                    </a>
                    
                    @if($home->status === 'Aktif')
                    <form action="{{ route('admin.vacant-homes.status.update', $home) }}" method="POST" onsubmit="return confirm('Tandai bahwa warga sudah kembali?')">
                        @csrf @method('PUT')
                        <button type="submit" class="px-3 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-bold text-xs rounded-xl shadow-sm transition-all" title="Tandai Selesai">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('admin.vacant-homes.destroy', $home) }}" method="POST" onsubmit="return confirm('Hapus data titip rumah ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-white text-rose-600 hover:bg-rose-50 border border-gray-200 hover:border-rose-200 font-bold text-xs rounded-xl shadow-sm transition-all" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $vacantHomes->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Data Rumah Kosong</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Saat ini belum ada warga yang melaporkan penitipan rumah (sedang bepergian).</p>
        </div>
        @endif
    </div>
</x-app-layout>
