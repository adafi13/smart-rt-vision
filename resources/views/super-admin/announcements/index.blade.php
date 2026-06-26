<x-super-admin-layout title="Pengumuman Global">
    <div class="space-y-6 py-4">
        {{-- Header Actions --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola pesan yang tampil di seluruh dashboard mitra RT</p>
            </div>
            <a href="{{ route('super-admin.announcements.create') }}"
               class="flex items-center gap-2 px-5 py-3 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-slate-900/20 overflow-hidden group">
                <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Buat Pengumuman
            </a>
        </div>


        {{-- Announcements List --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/30">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.25em]">Semua Pengumuman ({{ $announcements->total() }})</h3>
            </div>

            <div class="divide-y divide-slate-50">
                @forelse($announcements as $ann)
                    @php $style = $ann->type_label; @endphp
                    <div class="p-8 flex flex-col md:flex-row md:items-start gap-6 hover:bg-slate-50/40 transition-colors group">
                        {{-- Type indicator --}}
                        <div class="shrink-0 w-10 h-10 rounded-xl {{ $style['bg'] }} {{ $style['border'] }} border flex items-center justify-center">
                            @if($ann->type === 'info')
                                <svg class="w-5 h-5 {{ $style['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($ann->type === 'warning')
                                <svg class="w-5 h-5 {{ $style['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @elseif($ann->type === 'success')
                                <svg class="w-5 h-5 {{ $style['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-5 h-5 {{ $style['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h4 class="text-sm font-extrabold text-slate-900">{{ $ann->title }}</h4>
                                
                                {{-- Status badge --}}
                                @if($ann->status_label === 'Aktif')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">Aktif</span>
                                @elseif($ann->status_label === 'Terjadwal')
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest">Terjadwal</span>
                                @elseif($ann->status_label === 'Kedaluwarsa')
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[9px] font-black uppercase tracking-widest">Kedaluwarsa</span>
                                @else
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-400 rounded-full text-[9px] font-black uppercase tracking-widest">Nonaktif</span>
                                @endif
                                
                                {{-- Type badge --}}
                                <span class="px-2 py-0.5 {{ $style['bg'] }} {{ $style['text'] }} rounded-full text-[9px] font-black uppercase tracking-widest">{{ $style['label'] }}</span>
                                
                                {{-- Target badge --}}
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[9px] font-black uppercase tracking-widest">
                                    {{ $ann->target === 'all' ? 'Semua User' : ($ann->target === 'owner' ? 'Ketua RT' : ucfirst($ann->target)) }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 font-medium leading-relaxed mb-3">{{ Str::limit($ann->message, 180) }}</p>

                            <div class="flex flex-wrap gap-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                <span>Dibuat: {{ $ann->created_at->translatedFormat('d M Y H:i') }}</span>
                                @if($ann->starts_at) <span>Mulai: {{ $ann->starts_at->translatedFormat('d M Y') }}</span> @endif
                                @if($ann->ends_at) <span>Berakhir: {{ $ann->ends_at->translatedFormat('d M Y') }}</span> @endif
                                <span>{{ $ann->is_dismissible ? 'Bisa Ditutup' : 'Permanen' }}</span>
                                <span class="text-slate-300">Dibuat oleh: {{ $ann->creator->name ?? '-' }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Toggle Active --}}
                            <form method="POST" action="{{ route('super-admin.announcements.toggle', $ann) }}">
                                @csrf
                                <button type="submit" title="{{ $ann->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    class="w-9 h-9 rounded-xl {{ $ann->is_active ? 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' }} flex items-center justify-center transition-all">
                                    @if($ann->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    @endif
                                </button>
                            </form>

                            <a href="{{ route('super-admin.announcements.edit', $ann) }}"
                               class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>

                            <form method="POST" action="{{ route('super-admin.announcements.destroy', $ann) }}"
                                  onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf @method('DELETE')
                                <button class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-32 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada pengumuman dibuat</p>
                            <a href="{{ route('super-admin.announcements.create') }}" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">+ Buat Pertama</a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($announcements->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-super-admin-layout>
