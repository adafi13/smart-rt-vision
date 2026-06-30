<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Direktori Warga Global</h1>
                <p class="text-sm text-gray-500 mt-0.5">Pantau data kependudukan seluruh RT di bawah {{ $rw->name }}</p>
            </div>
            <a href="{{ route('rw.members.export-excel', ['q' => request('q'), 'rt_id' => request('rt_id')]) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Filter & Search --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('rw.members.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berdasarkan Nama atau NIK warga..." class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50">
                </div>
                
                <div class="sm:w-64">
                    <select name="rt_id" class="block w-full py-2.5 border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50/50" onchange="this.form.submit()">
                        <option value="all">Semua RT</option>
                        @foreach($rts as $rt)
                            <option value="{{ $rt->id }}" {{ request('rt_id') == $rt->id ? 'selected' : '' }}>
                                {{ $rt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Cari
                    </button>
                    @if(request('q') || (request('rt_id') && request('rt_id') !== 'all'))
                        <a href="{{ route('rw.members.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold rounded-xl transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Warga --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50/70">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Asal RT</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">L/P</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usia</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Agama</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($members as $member)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($member->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $member->nama }}</p>
                                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $member->nik }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $member->tenant->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($member->jenis_kelamin === 'Laki-laki')
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m0-8h-8m8 0L8 16"/></svg>
                                        L
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-pink-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 0v10m-3-3h6"/></svg>
                                        P
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($member->tanggal_lahir)
                                    <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($member->tanggal_lahir)->age }} Thn</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($member->tanggal_lahir)->format('d M Y') }}</p>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                {{ $member->agama }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Tidak ada data warga</h3>
                                <p class="text-xs text-gray-500 mt-1">Coba sesuaikan filter pencarian atau pastikan RT sudah menginput data warga.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($members->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $members->links() }}
                </div>
            @endif
        </div>

    </div>
</x-rw-app-layout>
