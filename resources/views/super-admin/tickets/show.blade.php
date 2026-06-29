<x-super-admin-layout title="Tiket {{ $ticket->ticket_number }}" header="Detail Tiket">
@php
    $sl = $ticket->status_label;
    $pl = $ticket->priority_label;
@endphp

<div class="max-w-7xl mx-auto">

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('super-admin.tickets.index') }}"
           class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 uppercase tracking-widest transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <div class="flex items-center gap-2">
            <span class="text-[9px] font-mono font-black text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">{{ $ticket->ticket_number }}</span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $sl['bg'] }} dark:bg-opacity-20 border {{ $sl['border'] }} rounded-lg">
                <span class="w-1.5 h-1.5 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
                <span class="text-[9px] font-black {{ $sl['text'] }} uppercase tracking-wider">{{ $sl['label'] }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $pl['bg'] }} dark:bg-opacity-20 rounded-lg">
                <span class="w-1.5 h-1.5 rounded-full {{ $pl['dot'] }}"></span>
                <span class="text-[9px] font-black {{ $pl['text'] }} uppercase tracking-wider">{{ $pl['label'] }}</span>
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-emerald-700 dark:text-emerald-400 text-sm font-bold">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- CHAT PANEL --}}
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col" style="min-height:580px">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-black text-sm shrink-0 shadow-md shadow-violet-500/20">
                        {{ strtoupper(substr($ticket->user?->name ?? 'T', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $ticket->subject }}</h2>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-0.5">
                            {{ $ticket->user?->name ?? '—' }} &middot; <span class="text-violet-500">{{ $ticket->tenant?->name ?? 'N/A' }}</span> &middot; {{ $ticket->category_label }} &middot; {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                {{-- Thread --}}
                <div class="flex-1 px-6 py-5 space-y-6 overflow-y-auto" id="chatThread">

                    @foreach($ticket->replies as $reply)
                        @if($reply->is_staff_reply)
                        {{-- Staff right --}}
                        <div class="flex items-start gap-3 flex-row-reverse">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-sm">
                                {{ strtoupper(substr($reply->user?->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="flex-1 flex flex-col items-end">
                                <div class="flex items-center gap-2 mb-2 flex-row-reverse">
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100">{{ $reply->user?->name ?? 'Admin' }}</span>
                                    <span class="text-[9px] font-bold text-white bg-slate-800 dark:bg-violet-600 px-2 py-0.5 rounded-full">{{ config('app.name') }}</span>
                                </div>
                                <div class="bg-slate-900 dark:bg-slate-700 rounded-2xl rounded-tr-sm px-4 py-3.5 text-sm text-white leading-relaxed whitespace-pre-wrap w-full">{{ $reply->message }}</div>
                                <p class="text-[10px] text-slate-400 mt-1.5 mr-1">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @else
                        {{-- Tenant left --}}
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-sm">
                                {{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100">{{ $reply->user?->name ?? 'Pengguna' }}</span>
                                    <span class="text-[9px] font-bold text-white bg-violet-500 px-2 py-0.5 rounded-full">Tenant</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-2xl rounded-tl-sm px-4 py-3.5 text-sm text-slate-700 dark:text-slate-200 leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</div>
                                <p class="text-[10px] text-slate-400 mt-1.5 ml-1">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    @if($ticket->replies->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada pesan</p>
                    </div>
                    @endif
                </div>

                {{-- Reply Form --}}
                <div class="border-t border-slate-100 dark:border-slate-700 p-5">
                    <form method="POST" action="{{ route('super-admin.tickets.reply', $ticket) }}">
                        @csrf
                        <textarea name="message" rows="3" required
                                  placeholder="Tulis balasan kepada tenant..."
                                  class="w-full px-4 py-3 mb-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-white placeholder-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all leading-relaxed">{{ old('message') }}</textarea>
                        <div class="flex items-center justify-between gap-3">
                            <select name="status"
                                    class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-[10px] font-bold text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all">
                                <option value="open"     @selected($ticket->status === 'open')>🔴 Open</option>
                                <option value="answered" @selected($ticket->status === 'answered')>🟡 Answered</option>
                                <option value="resolved" @selected($ticket->status === 'resolved')>🟢 Resolved</option>
                                <option value="closed"   @selected($ticket->status === 'closed')>⚫ Closed</option>
                            </select>
                            <button type="submit"
                                    class="px-5 py-2 bg-slate-900 dark:bg-violet-600 hover:bg-violet-600 dark:hover:bg-violet-500 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-md active:scale-95">
                                Kirim Balasan →
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Info --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Informasi Tiket</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-black text-xs shadow-sm shrink-0">
                            {{ strtoupper(substr($ticket->tenant?->name ?? 'T', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tenant</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white truncate">{{ $ticket->tenant?->name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="px-5 py-3 flex items-start justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest shrink-0 mt-0.5">Pelapor</span>
                        <div class="text-right min-w-0">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $ticket->user?->name ?? '—' }}</p>
                            <p class="text-[9px] text-slate-400 truncate">{{ $ticket->user?->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 {{ $sl['bg'] }} dark:bg-opacity-20 border {{ $sl['border'] }} rounded-lg shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
                            <span class="text-[9px] font-black {{ $sl['text'] }} uppercase tracking-wider">{{ $sl['label'] }}</span>
                        </span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Prioritas</span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 {{ $pl['bg'] }} dark:bg-opacity-20 rounded-lg shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $pl['dot'] }}"></span>
                            <span class="text-[9px] font-black {{ $pl['text'] }} uppercase tracking-wider">{{ $pl['label'] }}</span>
                        </span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kategori</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $ticket->category_label }}</span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Dibuat</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $ticket->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    @if($ticket->resolved_at)
                    <div class="px-5 py-3 flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Selesai</span>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">{{ $ticket->resolved_at->translatedFormat('d M Y') }}</span>
                    </div>
                    @endif
                    @if($ticket->assignedTo)
                    <div class="px-5 py-3 flex items-center justify-between gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ditangani</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $ticket->assignedTo->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Status --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">⚡ Ubah Status</h4>
                <form method="POST" action="{{ route('super-admin.tickets.status', $ticket) }}" class="space-y-2">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all">
                        <option value="open"     @selected($ticket->status === 'open')>🔴 Open — Menunggu</option>
                        <option value="answered" @selected($ticket->status === 'answered')>🟡 Answered — Sudah Dibalas</option>
                        <option value="resolved" @selected($ticket->status === 'resolved')>🟢 Resolved — Selesai</option>
                        <option value="closed"   @selected($ticket->status === 'closed')>⚫ Closed — Ditutup</option>
                    </select>
                    <button type="submit" class="w-full py-2.5 bg-slate-900 dark:bg-violet-700 hover:bg-violet-600 dark:hover:bg-violet-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Simpan Status
                    </button>
                </form>
            </div>

            {{-- Assign --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">👤 Tugaskan Admin</h4>
                <form method="POST" action="{{ route('super-admin.tickets.assign', $ticket) }}" class="space-y-2">
                    @csrf @method('PATCH')
                    <select name="assigned_to" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all">
                        <option value="">— Belum Di-assign —</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" @selected($ticket->assigned_to === $admin->id)>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full py-2.5 bg-slate-900 dark:bg-violet-700 hover:bg-violet-600 dark:hover:bg-violet-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Simpan Assignment
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    const t = document.getElementById('chatThread');
    if (t) t.scrollTop = t.scrollHeight;
</script>
</x-super-admin-layout>
