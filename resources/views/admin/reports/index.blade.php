<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Tiket Laporan Warga</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola laporan, tanggapi, dan berikan pembaruan status (*Helpdesk*)</p>
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

        <!-- Filter & Search Section -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex gap-2 w-full sm:max-w-xs">
                <select name="status" class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Status Laporan</option>
                    @foreach(['Menunggu', 'Proses', 'Selesai', 'Ditolak'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>Status: {{ $s }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($reports->isNotEmpty())
        <div class="space-y-4">
            @foreach($reports as $r)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col md:flex-row transition-all hover:shadow-md">
                
                <!-- Kiri: Info Tiket Laporan -->
                <div class="p-5 md:w-1/3 border-b md:border-b-0 md:border-r border-gray-100 bg-gray-50/30 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-2.5 py-1 bg-white shadow-sm text-gray-700 text-[10px] font-bold rounded border border-gray-200 font-mono tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            {{ $r->ticket_number }}
                        </span>
                        
                        @php $badge = match($r->status) {
                            'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                        }; @endphp
                        <span class="px-2.5 py-1 border rounded-md text-[10px] font-bold uppercase tracking-wide {{ $badge }}">{{ $r->status }}</span>
                    </div>

                    <div class="space-y-3.5 flex-1">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mb-0.5">Pelapor</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ substr($r->member ? $r->member->nama : ($r->reporter_name ?? 'W'), 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $r->member ? $r->member->nama : ($r->reporter_name ?? 'Warga (Guest)') }}
                                    </p>
                                    @if($r->reporter_phone)
                                        <p class="text-[11px] text-gray-500 font-mono">{{ $r->reporter_phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mb-0.5">Kategori</p>
                            <span class="inline-flex px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-semibold">{{ $r->kategori }}</span>
                        </div>
                        
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mb-0.5">Waktu Masuk</p>
                            <p class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $r->created_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <form action="{{ route('admin.laporan.destroy', $r) }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Hapus laporan beserta riwayatnya?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex items-center gap-1.5 text-[11px] font-bold text-rose-500 hover:text-rose-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Laporan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Kanan: Chat Thread & Balasan -->
                <div class="p-5 md:w-2/3 flex flex-col bg-white">
                    <div class="flex-1 space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        
                        <!-- Laporan Utama (Bubble Kiri) -->
                        <div class="flex gap-3 max-w-[90%]">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex-shrink-0 flex items-center justify-center text-gray-500 font-bold text-xs border border-gray-200">
                                {{ substr($r->member ? $r->member->nama : ($r->reporter_name ?? 'W'), 0, 1) }}
                            </div>
                            <div>
                                <div class="bg-gray-100 border border-gray-200 rounded-2xl rounded-tl-none p-3.5 text-sm text-gray-700 shadow-sm">
                                    <p class="whitespace-pre-wrap">{{ $r->laporan }}</p>
                                    @if($r->foto_bukti)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/'.$r->foto_bukti) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 shadow-sm rounded-lg text-xs font-semibold text-indigo-600 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Lihat Lampiran Foto
                                        </a>
                                    </div>
                                    @endif
                                    
                                    {{-- Backward compatibility for old replies without the replies table --}}
                                    @if($r->tanggapan_rt && $r->replies->isEmpty())
                                        <div class="mt-3 pt-3 border-t border-gray-200/60">
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide mb-1">Tanggapan Lawas:</p>
                                            <p class="text-gray-600 text-xs italic">{{ $r->tanggapan_rt }}</p>
                                        </div>
                                    @endif
                                </div>
                                <span class="text-[10px] text-gray-400 font-semibold mt-1.5 block ml-1">{{ $r->created_at->format('H:i') }}</span>
                            </div>
                        </div>

                        <!-- Balasan / Timeline -->
                        @foreach($r->replies as $reply)
                            @if($reply->is_system)
                                <!-- System Notice -->
                                <div class="flex items-center gap-3 justify-center my-4">
                                    <div class="h-px bg-gray-100 flex-1"></div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wide flex items-center gap-1 bg-gray-50 px-2 py-0.5 rounded-full border border-gray-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $reply->message }} ({{ $reply->created_at->format('H:i') }})
                                    </span>
                                    <div class="h-px bg-gray-100 flex-1"></div>
                                </div>
                            @else
                                <!-- Admin Reply (Bubble Kanan) -->
                                <div class="flex gap-3 justify-end ml-auto max-w-[90%]">
                                    <div class="flex flex-col items-end">
                                        <div class="bg-indigo-600 shadow-sm rounded-2xl rounded-tr-none p-3.5 text-sm text-white">
                                            <p class="text-[10px] font-bold text-indigo-200 mb-1 opacity-80">{{ $reply->user->name ?? 'Admin / Pengurus' }}</p>
                                            <p class="whitespace-pre-wrap">{{ $reply->message }}</p>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-semibold mt-1.5 block mr-1">{{ $reply->created_at->format('d/m, H:i') }}</span>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 border border-indigo-200 flex-shrink-0 flex items-center justify-center text-indigo-700 font-bold text-xs shadow-sm">
                                        RT
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Area Ketik Balasan -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <form action="{{ route('admin.laporan.update', $r) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                            @csrf @method('PUT')
                            <select name="status" class="w-full sm:w-36 text-xs font-semibold text-gray-700 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm">
                                <option value="Menunggu" @selected($r->status === 'Menunggu')>Status: Menunggu</option>
                                <option value="Proses" @selected($r->status === 'Proses')>Status: Proses</option>
                                <option value="Selesai" @selected($r->status === 'Selesai')>Status: Selesai</option>
                                <option value="Ditolak" @selected($r->status === 'Ditolak')>Status: Ditolak</option>
                            </select>
                            
                            <div class="flex-1 flex gap-2 relative">
                                <input type="text" name="message" required placeholder="Tuliskan balasan/tanggapan untuk tiket ini..." class="w-full text-sm rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-gray-400 shadow-sm pr-12">
                                <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center justify-center transition-colors shadow-sm" title="Kirim Balasan">
                                    <svg class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $reports->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Tiket Masuk</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Semua keluhan atau laporan dari warga akan masuk dan diproses melalui Helpdesk ini.</p>
        </div>
        @endif

    </div>
</x-app-layout>

<style>
/* Custom Scrollbar for Chat Thread */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #E5E7EB;
    border-radius: 20px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background-color: #D1D5DB;
}
</style>
