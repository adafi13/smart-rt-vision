<x-rw-app-layout title="Tiket Support Saya" header="Pusat Bantuan & Support">
    <div class="space-y-6 py-4 animate-fade-in-up min-h-screen transition-colors duration-300">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-1">Pantau & kelola semua permintaan bantuan Anda</p>
            </div>
            <a href="{{ route('rw.tickets.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 dark:bg-emerald-600 text-white rounded-[1.25rem] text-[10px] font-black uppercase tracking-[0.15em] hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-xl shadow-slate-900/20 dark:shadow-none dark:shadow-emerald-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Buat Tiket Baru
            </a>
        </div>

        {{-- Stats Cards --}}
        @php
            $myOpen     = \App\Models\Ticket::where('tenant_id', auth()->user()->tenant_id)->where('status', 'open')->count();
            $myProgress = \App\Models\Ticket::where('tenant_id', auth()->user()->tenant_id)->whereIn('status', ['answered'])->count();
            $myResolved = \App\Models\Ticket::where('tenant_id', auth()->user()->tenant_id)->whereIn('status', ['resolved','closed'])->count();
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border {{ $myOpen > 0 ? 'border-amber-200 dark:border-amber-700' : 'border-slate-200 dark:border-slate-700' }} p-4 text-center">
                <p class="text-2xl font-black {{ $myOpen > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-slate-100' }}">{{ $myOpen }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Menunggu</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl border {{ $myProgress > 0 ? 'border-sky-200 dark:border-sky-700' : 'border-slate-200 dark:border-slate-700' }} p-4 text-center">
                <p class="text-2xl font-black {{ $myProgress > 0 ? 'text-sky-600 dark:text-sky-400' : 'text-slate-800 dark:text-slate-100' }}">{{ $myProgress }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Diproses</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 text-center">
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $myResolved }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Selesai</p>
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-xl shadow-slate-200/40 dark:shadow-none overflow-hidden transition-colors">
            <div class="p-8 border-b border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/50">
                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.25em]">Semua Tiket ({{ $tickets->total() }})</h3>
            </div>

            <div class="divide-y divide-slate-50 dark:divide-slate-700">
                @forelse($tickets as $ticket)
                    @php $status = $ticket->status_label; $priority = $ticket->priority_label; @endphp
                    <a href="{{ route('rw.tickets.show', $ticket) }}"
                       class="flex flex-col sm:flex-row sm:items-center gap-4 p-6 hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-all group">

                        {{-- Priority Dot --}}
                        <div class="shrink-0 w-3 h-3 rounded-full {{ $priority['dot'] }} ring-4 ring-current ring-opacity-20 mt-1 sm:mt-0 self-start sm:self-center transition-transform group-hover:scale-125"></div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $ticket->ticket_number }}</span>
                                <span class="text-[9px] font-black {{ $status['text'] }} {{ $status['bg'] }} dark:bg-opacity-20 px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $status['label'] }}</span>
                                <span class="text-[9px] font-black {{ $priority['text'] }} {{ $priority['bg'] }} dark:bg-opacity-20 px-2.5 py-0.5 rounded-full uppercase tracking-wider">{{ $priority['label'] }}</span>
                            </div>
                            <p class="text-sm font-black text-slate-900 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors uppercase tracking-tight">{{ $ticket->subject }}</p>
                            <div class="flex items-center gap-4 mt-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    {{ $ticket->category_label }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    {{ $ticket->replies_count }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $ticket->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Arrow --}}
                        <div class="shrink-0 text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 group-hover:translate-x-1 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @empty
                    <div class="py-32 text-center">
                        <div class="flex flex-col items-center gap-6">
                            <div class="w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-[2.5rem] flex items-center justify-center ring-1 ring-emerald-100 dark:ring-emerald-500/20 shadow-xl shadow-emerald-500/10 dark:shadow-none">
                                <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-base font-black text-slate-900 dark:text-white mb-1 uppercase tracking-tight">Belum Ada Tiket</p>
                                <p class="text-xs font-medium text-slate-400 dark:text-slate-500 uppercase tracking-widest">Butuh bantuan? Buat tiket pertama Anda sekarang.</p>
                            </div>
                            <a href="{{ route('rw.tickets.create') }}"
                               class="px-8 py-3.5 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-700 dark:hover:bg-emerald-500 transition-all shadow-xl shadow-emerald-500/20 dark:shadow-none">
                                + Buat Tiket Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($tickets->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 transition-colors">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-rw-app-layout>
