<x-super-admin-layout title="Tiket {{ $ticket->ticket_number }}" header="Detail Tiket">
@php
    $sl = $ticket->status_label;
    $pl = $ticket->priority_label;
@endphp

{{-- ─── MOBILE Sticky Header ─────────────────────────────────── --}}
<div class="lg:hidden sticky top-0 z-30 bg-white/95 dark:bg-slate-900/95 backdrop-blur border-b border-slate-200 dark:border-slate-700">
    <div class="flex items-center gap-3 px-4 py-3">
        <a href="{{ route('super-admin.tickets.index') }}"
           class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition-colors shrink-0">
            <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div class="flex-1 min-w-0">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $ticket->ticket_number }}</p>
            <p class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $ticket->subject }}</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $sl['bg'] }} border {{ $sl['border'] }} rounded-xl shrink-0">
            <span class="w-1.5 h-1.5 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
            <span class="text-[9px] font-black {{ $sl['text'] }} uppercase tracking-wider">{{ $sl['label'] }}</span>
        </span>
    </div>
    {{-- Mobile tabs --}}
    <div class="flex border-t border-slate-200 dark:border-slate-700">
        <button onclick="showTab('chat')" id="tab-chat"
                class="flex-1 py-2.5 text-[10px] font-black uppercase tracking-wider text-emerald-600 border-b-2 border-emerald-500 transition-colors">
            💬 Percakapan
        </button>
        <button onclick="showTab('info')" id="tab-info"
                class="flex-1 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b-2 border-transparent transition-colors">
            ℹ️ Detail & Aksi
        </button>
    </div>
</div>

<div class="max-w-7xl mx-auto px-0 lg:px-0 py-0 lg:py-6 animate-fade-in-up">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mx-4 lg:mx-0 mb-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-2xl text-emerald-700 dark:text-emerald-400 text-sm font-bold">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Desktop top bar --}}
    <div class="hidden lg:flex items-center justify-between mb-5">
        <a href="{{ route('super-admin.tickets.index') }}"
           class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-emerald-600 uppercase tracking-widest transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Tiket
        </a>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-mono font-black text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-xl">{{ $ticket->ticket_number }}</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $sl['bg'] }} border {{ $sl['border'] }} rounded-xl">
                <span class="w-2 h-2 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
                <span class="text-[10px] font-black {{ $sl['text'] }} uppercase tracking-widest">{{ $sl['label'] }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $pl['bg'] }} rounded-xl">
                <span class="w-2 h-2 rounded-full {{ $pl['dot'] }}"></span>
                <span class="text-[10px] font-black {{ $pl['text'] }} uppercase tracking-widest">{{ $pl['label'] }}</span>
            </span>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="lg:grid lg:grid-cols-12 lg:gap-5">

        {{-- ════════════════════════════════════════════════════
             CHAT COLUMN
        ════════════════════════════════════════════════════ --}}
        <div id="panel-chat" class="lg:col-span-8 flex flex-col">

            {{-- Desktop: Subject header --}}
            <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 mb-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-black text-base shrink-0 shadow-lg shadow-violet-500/30">
                        {{ strtoupper(substr($ticket->user?->name ?? 'T', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-base font-black text-slate-900 dark:text-white mb-1 leading-tight">{{ $ticket->subject }}</h1>
                        <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>{{ $ticket->user?->name ?? '—' }}</span>
                            <span>·</span>
                            <span class="text-violet-600 dark:text-violet-400">{{ $ticket->tenant?->name ?? 'N/A' }}</span>
                            <span>·</span>
                            <span>{{ $ticket->category_label }}</span>
                            <span>·</span>
                            <span>{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thread --}}
            <div class="flex-1 px-4 lg:px-0 py-4 space-y-3 lg:space-y-4">
                @foreach($ticket->replies as $reply)
                    @if($reply->is_staff_reply)
                    {{-- Staff — right --}}
                    <div class="flex items-end gap-2.5 flex-row-reverse">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md ring-2 ring-slate-200 dark:ring-slate-600">
                            {{ strtoupper(substr($reply->user?->name ?? 'S', 0, 1)) }}
                        </div>
                        <div class="max-w-[75%] lg:max-w-[68%]">
                            <div class="flex items-center gap-2 mb-1 flex-row-reverse">
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-300">{{ $reply->user?->name ?? 'Admin' }}</span>
                                <span class="text-[9px] font-bold text-white bg-slate-800 dark:bg-violet-600 px-2 py-0.5 rounded-full">{{ config('app.name') }}</span>
                            </div>
                            <div class="bg-slate-900 dark:bg-slate-700 rounded-2xl rounded-br-sm px-4 py-3 text-sm text-white leading-relaxed shadow-md">
                                {{ $reply->message }}
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 text-right mr-1">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @else
                    {{-- Tenant — left --}}
                    <div class="flex items-end gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md ring-2 ring-violet-100 dark:ring-violet-900/30">
                            {{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="max-w-[75%] lg:max-w-[68%]">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-300">{{ $reply->user?->name ?? 'Pengguna' }}</span>
                                <span class="text-[9px] font-bold text-white bg-violet-500 px-2 py-0.5 rounded-full">Tenant</span>
                            </div>
                            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl rounded-bl-sm px-4 py-3 text-sm text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm">
                                {{ $reply->message }}
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 ml-1">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                @endforeach

                @if($ticket->replies->isEmpty())
                <div class="text-center py-16 text-slate-400 dark:text-slate-600">
                    <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p class="text-xs font-bold uppercase tracking-widest">Belum ada pesan</p>
                </div>
                @endif
            </div>

            {{-- Reply Box — Desktop sticky-like, Mobile sticky bottom --}}
            <div class="sticky bottom-0 lg:static bg-white/95 dark:bg-slate-800/95 lg:bg-white lg:dark:bg-slate-800 backdrop-blur lg:backdrop-blur-none border-t border-slate-200 dark:border-slate-700 lg:border lg:rounded-2xl lg:shadow-sm px-4 lg:px-5 py-4 lg:py-5">
                <h4 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3 hidden lg:block">Balas Tiket</h4>

                <form method="POST" action="{{ route('super-admin.tickets.reply', $ticket) }}" class="space-y-3">
                    @csrf
                    <div class="flex items-end gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md hidden lg:flex">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-violet-500 focus-within:border-transparent transition-all">
                            <textarea name="message" rows="3" required
                                      placeholder="Tulis balasan kepada tenant..."
                                      class="w-full px-4 pt-3 pb-1 bg-transparent text-sm text-slate-700 dark:text-white placeholder-slate-400 resize-none focus:outline-none leading-relaxed">{{ old('message') }}</textarea>
                            <div class="flex items-center justify-between px-3 pb-3 gap-2">
                                {{-- Status select --}}
                                <select name="status"
                                        class="flex-1 sm:flex-none text-[10px] font-bold px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-1 focus:ring-violet-500 appearance-none">
                                    <option value="open"     @selected($ticket->status === 'open')>🔴 Open</option>
                                    <option value="answered" @selected($ticket->status === 'answered')>🟡 Answered</option>
                                    <option value="resolved" @selected($ticket->status === 'resolved')>🟢 Resolved</option>
                                    <option value="closed"   @selected($ticket->status === 'closed')>⚫ Closed</option>
                                </select>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-slate-900 dark:bg-violet-600 hover:bg-violet-600 dark:hover:bg-violet-500 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-md active:scale-95 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>{{-- end chat column --}}

        {{-- ════════════════════════════════════════════════════
             SIDEBAR — Desktop & Mobile Tab
        ════════════════════════════════════════════════════ --}}
        <div id="panel-info" class="hidden lg:block lg:col-span-4 space-y-4 mt-4 lg:mt-0 px-4 lg:px-0">

            {{-- Ticket Info --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Informasi Tiket</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-black text-sm shadow-md shadow-violet-500/20">
                            {{ strtoupper(substr($ticket->tenant?->name ?? 'T', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tenant</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white">{{ $ticket->tenant?->name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700 space-y-3">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Dilaporkan Oleh</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ticket->user?->name ?? '—' }}</p>
                            <p class="text-[10px] text-slate-400">{{ $ticket->user?->email ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ticket->category_label }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Prioritas</p>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $pl['bg'] }} rounded-xl">
                                <span class="w-1.5 h-1.5 rounded-full {{ $pl['dot'] }}"></span>
                                <span class="text-[9px] font-black {{ $pl['text'] }} uppercase tracking-widest">{{ $pl['label'] }}</span>
                            </span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Dibuat</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        @if($ticket->resolved_at)
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Diselesaikan</p>
                            <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $ticket->resolved_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        @endif
                        @if($ticket->assignedTo)
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Ditangani</p>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[9px] font-black text-slate-500">
                                    {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                                </div>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ticket->assignedTo->name }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Status Update --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">⚡ Ubah Status</h4>
                <form method="POST" action="{{ route('super-admin.tickets.status', $ticket) }}" class="space-y-2">
                    @csrf @method('PATCH')
                    <select name="status"
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all">
                        <option value="open"     @selected($ticket->status === 'open')>🔴 Open — Menunggu</option>
                        <option value="answered" @selected($ticket->status === 'answered')>🟡 Answered — Sudah Dibalas</option>
                        <option value="resolved" @selected($ticket->status === 'resolved')>🟢 Resolved — Selesai</option>
                        <option value="closed"   @selected($ticket->status === 'closed')>⚫ Closed — Ditutup</option>
                    </select>
                    <button type="submit"
                            class="w-full py-2 bg-slate-900 dark:bg-slate-700 hover:bg-violet-600 dark:hover:bg-violet-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Simpan Status
                    </button>
                </form>
            </div>

            {{-- Assign Admin --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">👤 Tugaskan Admin</h4>
                <form method="POST" action="{{ route('super-admin.tickets.assign', $ticket) }}" class="space-y-2">
                    @csrf @method('PATCH')
                    <select name="assigned_to"
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all">
                        <option value="">— Belum Di-assign —</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" @selected($ticket->assigned_to === $admin->id)>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full py-2 bg-slate-900 dark:bg-slate-700 hover:bg-violet-600 dark:hover:bg-violet-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Simpan Assignment
                    </button>
                </form>
            </div>

        </div>{{-- end sidebar --}}

    </div>{{-- end grid --}}
</div>

{{-- Mobile Tab Script --}}
<script>
function showTab(tab) {
    const chat = document.getElementById('panel-chat');
    const info = document.getElementById('panel-info');
    const tabChat = document.getElementById('tab-chat');
    const tabInfo = document.getElementById('tab-info');

    if (tab === 'chat') {
        chat.classList.remove('hidden');
        info.classList.add('hidden');
        tabChat.className = 'flex-1 py-2.5 text-[10px] font-black uppercase tracking-wider text-emerald-600 border-b-2 border-emerald-500 transition-colors';
        tabInfo.className = 'flex-1 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b-2 border-transparent transition-colors';
    } else {
        chat.classList.add('hidden');
        info.classList.remove('hidden');
        tabChat.className = 'flex-1 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b-2 border-transparent transition-colors';
        tabInfo.className = 'flex-1 py-2.5 text-[10px] font-black uppercase tracking-wider text-violet-600 border-b-2 border-violet-500 transition-colors';
    }
}
// Scroll to bottom of chat on load
document.addEventListener('DOMContentLoaded', function() {
    const thread = document.querySelector('#panel-chat .flex-1.px-4');
    if (thread) thread.scrollTop = thread.scrollHeight;
});
</script>
</x-super-admin-layout>
