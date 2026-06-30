<x-super-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen RW</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau kinerja dan kelola semua Rukun Warga (RW) yang terdaftar di sistem KakaAi.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-bold border border-indigo-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Total RW: {{ $counts['total'] }}
                </div>
            </div>
        </div>
        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <form action="{{ route('super-admin.rws.index') }}" method="GET" class="w-full sm:w-96 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama RW, email, atau kota..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            @if(request('search'))
                <a href="{{ route('super-admin.rws.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">Reset Pencarian</a>
            @endif
        </div>

        <!-- RW List Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rws as $rw)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all duration-300 flex flex-col">
                    <div class="p-6 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($rw->name, 0, 1)) }}
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $rw->rts_count }} RT Terdaftar
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 line-clamp-1" title="{{ $rw->name }}">
                            {{ $rw->name }}
                        </h3>
                        
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ $rw->city ?? 'Belum diset' }}, {{ $rw->province ?? 'Belum diset' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $rw->invite_code }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50 flex justify-between items-center rounded-b-2xl">
                        <div class="text-xs text-gray-500 font-medium">
                            Terdaftar: {{ $rw->created_at->translatedFormat('d M Y') }}
                        </div>
                        <a href="{{ route('super-admin.rws.show', $rw) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors group">
                            Detail RW
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Tidak Ada Data RW</h3>
                        <p class="text-gray-500 mt-2 max-w-sm mx-auto">Belum ada Rukun Warga yang terdaftar di sistem, atau pencarian Anda tidak menemukan hasil.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $rws->links() }}
        </div>
    </div>
</x-super-admin-layout>
