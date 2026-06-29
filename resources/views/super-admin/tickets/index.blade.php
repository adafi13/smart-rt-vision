<x-super-admin-layout title="Helpdesk / Tiket Bantuan">
    <div class="space-y-6 py-4 animate-fade-in-up">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $statCards = [
                    ['label' => 'Perlu Respons',  'value' => $stats['open'],        'icon' => '🔴', 'bg' => 'bg-red-50 dark:bg-red-900/10',     'text' => 'text-red-700 dark:text-red-400',     'border' => 'border-red-200 dark:border-red-800/30'],
                    ['label' => 'Diproses',        'value' => $stats['in_progress'], 'icon' => '🔵', 'bg' => 'bg-blue-50 dark:bg-blue-900/10',    'text' => 'text-blue-700 dark:text-blue-400',    'border' => 'border-blue-200 dark:border-blue-800/30'],
                    ['label' => 'Tunggu Info',     'value' => $stats['waiting'],     'icon' => '🟣', 'bg' => 'bg-purple-50 dark:bg-purple-900/10',  'text' => 'text-purple-700 dark:text-purple-400',  'border' => 'border-purple-200 dark:border-purple-800/30'],
                    ['label' => 'Diselesaikan',    'value' => $stats['resolved'],    'icon' => '🟢', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/10', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800/30'],
                ];
            @endphp
            @foreach($statCards as $card)
                <div class="bg-white dark:bg-slate-800 rounded-3xl border {{ $card['border'] }} p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 transition-all hover:shadow-md">
                    <div class="text-3xl bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-slate-100 dark:border-slate-700/50">{{ $card['icon'] }}</div>
                    <div>
                        <p class="text-3xl font-black {{ $card['text'] }} leading-none mb-1">{{ $card['value'] }}</p>
                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200/60 dark:border-slate-700/60 shadow-sm p-6 sm:p-8">
            <form method="GET" action="{{ route('super-admin.tickets.index') }}" class="flex flex-col md:flex-row flex-wrap gap-4 items-end">
                <div class="flex-1 w-full md:w-auto min-w-[200px]">
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Cari</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nomor, subjek, nama mitra..."
                           class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Status</label>
                    <select name="status" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all appearance-none">
                        <option value="all"          {{ $status === 'all'          ? 'selected' : '' }}>Semua Status</option>
                        <option value="open"         {{ $status === 'open'         ? 'selected' : '' }}>🔴 Open</option>
                        <option value="in_progress"  {{ $status === 'in_progress'  ? 'selected' : '' }}>🔵 Diproses</option>
                        <option value="waiting_reply"{{ $status === 'waiting_reply'? 'selected' : '' }}>🟣 Tunggu Info</option>
                        <option value="resolved"     {{ $status === 'resolved'     ? 'selected' : '' }}>🟢 Selesai</option>
                        <option value="closed"       {{ $status === 'closed'       ? 'selected' : '' }}>⚫ Ditutup</option>
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Prioritas</label>
                    <select name="priority" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all appearance-none">
                        <option value="all"    {{ $priority === 'all'    ? 'selected' : '' }}>Semua Prioritas</option>
                        <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                        <option value="high"   {{ $priority === 'high'   ? 'selected' : '' }}>🟠 Tinggi</option>
                        <option value="normal" {{ $priority === 'normal' ? 'selected' : '' }}>🔵 Normal</option>
                        <option value="low"    {{ $priority === 'low'    ? 'selected' : '' }}>⚪ Rendah</option>
                    </select>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none px-8 py-3.5 bg-slate-900 dark:bg-slate-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-md shadow-slate-200 dark:shadow-none text-center">
                        Filter
                    </button>
                    @if($search || $status !== 'all' || $priority !== 'all')
                        <a href="{{ route('super-admin.tickets.index') }}" class="flex-none px-6 py-3.5 bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-800 transition-all border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tickets List --}}
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200/60 dark:border-slate-700/60 shadow-sm overflow-hidden mb-6">
            <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/30 dark:bg-slate-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-xs font-black text-slate-900 dark:text-slate-100 uppercase tracking-[0.25em]">Semua Tiket ({{ $tickets->total() }})</h3>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700/60">
                @forelse($tickets as $ticket)
                    @php $sl = $ticket->status_label; $pl = $ticket->priority_label; @endphp
                    <a href="{{ route('super-admin.tickets.show', $ticket) }}"
                       class="flex flex-col sm:flex-row sm:items-center gap-5 p-6 sm:p-8 hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition-colors group">

                        {{-- Priority Indicator --}}
                        <div class="shrink-0 w-3 h-3 rounded-full {{ $pl['dot'] }} ring-4 ring-current ring-opacity-20 self-start sm:self-center mt-1 sm:mt-0 shadow-sm"></div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2.5 mb-2">
                                <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest font-mono bg-slate-50 dark:bg-slate-900/50 px-2.5 py-1 rounded-lg border border-slate-100 dark:border-slate-700">{{ $ticket->ticket_number }}</span>
                                <span class="text-[9px] font-black {{ $sl['text'] }} {{ $sl['bg'] }} dark:bg-opacity-20 border border-current border-opacity-20 px-2.5 py-1 rounded-md tracking-widest uppercase shadow-sm">{{ $sl['label'] }}</span>
                                <span class="text-[9px] font-black {{ $pl['text'] }} {{ $pl['bg'] }} dark:bg-opacity-20 border border-current border-opacity-20 px-2.5 py-1 rounded-md tracking-widest uppercase shadow-sm">{{ $pl['label'] }}</span>
                                <span class="text-[9px] font-black text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 px-2.5 py-1 rounded-md tracking-widest uppercase shadow-sm">{{ $ticket->category_label }}</span>
                            </div>
                            <p class="text-base font-extrabold text-slate-900 dark:text-slate-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors leading-snug">{{ $ticket->subject }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-2.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                <span class="text-slate-600 dark:text-slate-400">{{ $ticket->tenant->name ?? '-' }}</span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span>{{ $ticket->user->name }}</span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $ticket->replies_count }} balasan
                                </span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                @if($ticket->assignedTo)
                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                    <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Ditangani: {{ $ticket->assignedTo->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Arrow --}}
                        <div class="shrink-0 text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 group-hover:translate-x-1 transition-all bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl group-hover:bg-emerald-50 dark:group-hover:bg-emerald-900/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @empty
                    <div class="py-32 text-center">
                        <div class="mx-auto w-20 h-20 bg-slate-50 dark:bg-slate-800/80 rounded-full flex items-center justify-center mb-4 ring-1 ring-slate-100 dark:ring-slate-700">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <p class="text-sm font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Tidak ada tiket dengan filter ini</p>
                    </div>
                @endforelse
            </div>
            @if($tickets->hasPages())
                <div class="px-6 sm:px-8 py-6 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-super-admin-layout>
