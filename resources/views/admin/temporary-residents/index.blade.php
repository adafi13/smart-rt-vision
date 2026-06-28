<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Laporan Warga Kos/Kontrakan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Verifikasi laporan penghuni baru dari pemilik kos</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('warga-kos.index') }}" class="flex gap-2 w-full sm:max-w-xs">
                <select name="status" class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Status Laporan</option>
                    @foreach(['Pending', 'Terverifikasi', 'Ditolak'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>Status: {{ $s }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($residents->isNotEmpty())
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Nama Penghuni Baru</th>
                        <th class="px-6 py-4">Pemilik & Alamat Kos</th>
                        <th class="px-6 py-4">Foto KTP</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($residents as $res)
                    <tr class="hover:bg-gray-50/50 transition-colors group align-top">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900 block">{{ $res->nama_penghuni }}</span>
                            <span class="text-[10px] text-gray-500 font-mono mt-0.5 block">NIK: {{ $res->nik_penghuni }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-700 block">{{ $res->nama_pemilik }}</span>
                            <span class="text-xs text-gray-500 block mt-0.5">{{ $res->alamat_kos }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ asset('storage/' . $res->foto_ktp) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat KTP
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($res->status === 'Terverifikasi')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Terverifikasi
                                </span>
                            @elseif($res->status === 'Ditolak')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 transition-opacity">
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'verifikasi-{{ $res->id }}')" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Verifikasi Laporan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <form action="{{ route('warga-kos.destroy', $res) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen laporan warga ini?')">
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
        
        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @foreach($residents as $res)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="font-bold text-gray-900 block">{{ $res->nama_penghuni }}</span>
                        <span class="text-xs text-gray-500 font-mono">NIK: {{ $res->nik_penghuni }}</span>
                    </div>
                    @if($res->status === 'Terverifikasi')
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase tracking-wide">Terverifikasi</span>
                    @elseif($res->status === 'Ditolak')
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold uppercase tracking-wide">Ditolak</span>
                    @else
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wide">Pending</span>
                    @endif
                </div>
                
                <div class="bg-gray-50 p-3 rounded-xl mb-4 border border-gray-100 text-xs">
                    <p class="font-semibold text-gray-800">{{ $res->nama_pemilik }}</p>
                    <p class="text-gray-500 mt-0.5">{{ $res->alamat_kos }}</p>
                    <p class="text-gray-400 mt-2 text-[10px]">{{ $res->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
                
                <div class="flex gap-2">
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'verifikasi-{{ $res->id }}')" class="flex-1 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-bold transition-colors">
                        Verifikasi
                    </button>
                    <a href="{{ asset('storage/' . $res->foto_ktp) }}" target="_blank" class="px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <form action="{{ route('warga-kos.destroy', $res) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen laporan warga ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        @if($residents->hasPages())
        <div class="mt-6">
            {{ $residents->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Laporan Kos</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">Saat ini belum ada data warga kos atau penghuni sementara yang dilaporkan.</p>
        </div>
        @endif
    </div>

    <!-- Modals Verifikasi -->
    @foreach($residents as $res)
    <x-modal name="verifikasi-{{ $res->id }}" focusable>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Verifikasi Penghuni Baru</h2>
                    <p class="text-xs text-gray-500">Pemohon: <span class="font-semibold text-gray-700">{{ $res->nama_pemilik }}</span></p>
                </div>
            </div>

            <!-- Preview KTP -->
            <div class="mb-5">
                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wide mb-2">Foto KTP / Identitas</p>
                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50 h-48 flex items-center justify-center relative">
                    <img src="{{ asset('storage/' . $res->foto_ktp) }}" alt="KTP" class="max-h-full object-contain">
                </div>
            </div>

            <form action="{{ route('warga-kos.update', $res) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tindakan Verifikasi <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-medium">
                        <option value="Pending" @selected($res->status === 'Pending')>⌛ Biarkan Pending (Menunggu)</option>
                        <option value="Terverifikasi" @selected($res->status === 'Terverifikasi')>✅ Setujui & Verifikasi</option>
                        <option value="Ditolak" @selected($res->status === 'Ditolak')>❌ Tolak Laporan</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Admin (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm placeholder-gray-400" placeholder="Berikan alasan jika ditolak...">{{ $res->catatan }}</textarea>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </x-modal>
    @endforeach
</x-app-layout>
