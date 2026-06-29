<x-app-layout title="Tiket {{ $ticket->ticket_number }}" header="Detail Tiket">
@php
    $sl = $ticket->status_label;
    $pl = $ticket->priority_label;
    $firstReply = $ticket->replies->first();
    $restReplies = $ticket->replies->skip(1);
    $isActive = !in_array($ticket->status, ['resolved', 'closed']);
@endphp

{{-- ─── MOBILE: Sticky Header ─────────────────────────────────── --}}
<div class="lg:hidden sticky top-0 z-30 bg-white/90 dark:bg-slate-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-700 px-4 py-3 flex items-center gap-3">
    <a href="{{ route('admin.tickets.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
        <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div class="flex-1 min-w-0">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $ticket->ticket_number }}</p>
        <p class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $ticket->subject }}</p>
    </div>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $sl['bg'] }} border {{ $sl['border'] }} rounded-lg shrink-0">
        <span class="w-1.5 h-1.5 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
        <span class="text-[9px] font-black {{ $sl['text'] }} uppercase tracking-wider">{{ $sl['label'] }}</span>
    </span>
</div>

<div class="max-w-6xl mx-auto py-0 lg:py-6 animate-fade-in-up">

    {{-- ─── DESKTOP: Top Bar ───────────────────────────────────────── --}}
    <div class="hidden lg:flex items-center justify-between mb-6">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-emerald-600 uppercase tracking-widest transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Tiket
        </a>
        <div class="flex items-center gap-2">
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

    {{-- ─── Flash Messages ─────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="mx-4 lg:mx-0 mb-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-2xl text-emerald-700 dark:text-emerald-400 text-sm font-bold">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ─── MAIN LAYOUT ────────────────────────────────────────────── --}}
    <div class="lg:grid lg:grid-cols-12 lg:gap-6">

        {{-- ════════════════════════════════════════════════════════════
             CHAT THREAD COLUMN
        ════════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-8 flex flex-col">

            {{-- Desktop: Ticket Subject Card --}}
            <div class="hidden lg:block bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 mb-4">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-lg shrink-0 shadow-lg shadow-emerald-500/30">
                        {{ strtoupper(substr($ticket->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg font-black text-slate-900 dark:text-white leading-tight mb-1">{{ $ticket->subject }}</h1>
                        <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span>{{ $ticket->user?->name ?? '—' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>{{ $ticket->category_label }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chat Thread --}}
            <div class="flex-1 px-4 lg:px-0 py-4 lg:py-0 space-y-3 lg:space-y-4 mb-4"
                 id="chatThread">

                {{-- First message (original report) --}}
                @if($firstReply)
                <div class="flex items-end gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md shadow-emerald-500/30">
                        {{ strtoupper(substr($ticket->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="max-w-[75%] lg:max-w-[70%]">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-300">{{ $ticket->user?->name ?? '—' }}</span>
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 px-1.5 py-0.5 rounded-full">Pelapor</span>
                        </div>
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl rounded-bl-sm px-4 py-3 text-sm text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm">
                            {{ $firstReply->message }}
                        </div>
                        <p class="text-[9px] font-bold text-slate-400 mt-1 ml-1">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endif

                {{-- Rest of replies --}}
                @foreach($restReplies as $reply)
                    @if($reply->is_staff_reply)
                    {{-- Staff reply — right aligned --}}
                    <div class="flex items-end gap-2.5 flex-row-reverse">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 dark:from-emerald-500 dark:to-teal-600 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md">
                            {{ strtoupper(substr($reply->user?->name ?? 'S', 0, 1)) }}
                        </div>
                        <div class="max-w-[75%] lg:max-w-[70%]">
                            <div class="flex items-center gap-2 mb-1 flex-row-reverse">
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-300">Tim Support</span>
                                <span class="text-[9px] font-bold text-white bg-slate-800 dark:bg-emerald-600 px-1.5 py-0.5 rounded-full">{{ config('app.name') }}</span>
                            </div>
                            <div class="bg-slate-900 dark:bg-emerald-600 rounded-2xl rounded-br-sm px-4 py-3 text-sm text-white leading-relaxed shadow-md shadow-slate-900/20 dark:shadow-emerald-500/20">
                                {{ $reply->message }}
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 text-right mr-1">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @else
                    {{-- Tenant reply — left aligned --}}
                    <div class="flex items-end gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md shadow-emerald-500/20">
                            {{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="max-w-[75%] lg:max-w-[70%]">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-300">{{ $reply->user?->name ?? 'Pengguna' }}</span>
                            </div>
                            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl rounded-bl-sm px-4 py-3 text-sm text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm">
                                {{ $reply->message }}
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 ml-1">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Attachment --}}
                    @if($reply->attachment)
                    <div class="{{ $reply->is_staff_reply ? 'flex-row-reverse ml-10' : 'ml-10' }} flex items-center gap-2">
                        <a href="{{ Storage::url($reply->attachment) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] font-bold transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Lihat Lampiran
                        </a>
                    </div>
                    @endif
                @endforeach

                {{-- Empty state --}}
                @if($restReplies->isEmpty() && !$firstReply)
                <div class="text-center py-12 text-slate-400 dark:text-slate-600">
                    <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <p class="text-xs font-bold uppercase tracking-widest">Belum ada percakapan</p>
                </div>
                @endif
            </div>

            {{-- ─── Reply Input ────────────────────────────────────── --}}
            @if($isActive)
            <div class="sticky bottom-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur border-t border-slate-200 dark:border-slate-700 px-4 py-4 lg:px-0 lg:static lg:bg-transparent lg:dark:bg-transparent lg:backdrop-blur-none lg:border-0 lg:rounded-2xl lg:border lg:border-slate-200 lg:dark:border-slate-700 lg:shadow-sm lg:p-5">

                {{-- Resolved banner for mobile --}}
                <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data" id="replyForm">
                    @csrf
                    <div class="flex items-end gap-3">
                        {{-- Avatar --}}
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-md shadow-emerald-500/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        {{-- Input area --}}
                        <div class="flex-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-transparent transition-all">
                            <textarea name="message" id="replyMessage" rows="2"
                                      placeholder="Tulis balasan..."
                                      class="w-full px-4 pt-3 pb-1 bg-transparent text-sm text-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 resize-none focus:outline-none leading-relaxed">{{ old('message') }}</textarea>
                            <div class="flex items-center justify-between px-3 pb-3 pt-1">
                                <div class="flex items-center gap-2">
                                    {{-- File Attach --}}
                                    <input type="file" name="attachment" id="replyFile" class="hidden" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip" onchange="document.getElementById('replyFileLabel').textContent = this.files[0]?.name || ''">
                                    <label for="replyFile" class="cursor-pointer p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors" title="Lampirkan file">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </label>
                                    <span id="replyFileLabel" class="text-[10px] text-slate-400 truncate max-w-[100px]"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="document.getElementById('closeTicketForm').submit()"
                                            class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Selesaikan
                                    </button>
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-500/30 active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        <span class="hidden sm:inline">Kirim</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile: Selesaikan button --}}
                    <div class="lg:hidden mt-3 pl-11">
                        <button type="button" onclick="document.getElementById('closeTicketForm').submit()"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-500 dark:text-slate-400 hover:text-red-500 rounded-xl text-[10px] font-black uppercase tracking-wider transition-colors border border-slate-200 dark:border-slate-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tandai Selesai
                        </button>
                    </div>
                </form>
                <form id="closeTicketForm" method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="hidden">@csrf</form>
            </div>
            @else
            {{-- Ticket closed state --}}
            <div class="mx-4 lg:mx-0 flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl">
                <div class="w-7 h-7 rounded-full bg-{{ $ticket->status === 'closed' ? 'slate' : 'emerald' }}-100 dark:bg-{{ $ticket->status === 'closed' ? 'slate' : 'emerald' }}-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 text-{{ $ticket->status === 'closed' ? 'slate' : 'emerald' }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $ticket->status === 'closed' ? 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' : 'M5 13l4 4L19 7' }}"/></svg>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">Tiket {{ $ticket->status === 'closed' ? 'Ditutup' : 'Diselesaikan' }}</p>
                    @if($ticket->resolved_at)
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $ticket->resolved_at->translatedFormat('d M Y, H:i') }}</p>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- end main thread --}}

        {{-- ════════════════════════════════════════════════════════════
             SIDEBAR — Desktop only
        ════════════════════════════════════════════════════════════ --}}
        <div class="hidden lg:block lg:col-span-4 space-y-4">

            {{-- Ticket Info --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Informasi Tiket</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor Tiket</p>
                        <p class="text-sm font-black text-slate-800 dark:text-white font-mono">{{ $ticket->ticket_number }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $sl['bg'] }} border {{ $sl['border'] }} rounded-xl">
                            <span class="w-2 h-2 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
                            <span class="text-[10px] font-black {{ $sl['text'] }} uppercase tracking-widest">{{ $sl['label'] }}</span>
                        </span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Prioritas</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $pl['bg'] }} rounded-xl">
                            <span class="w-2 h-2 rounded-full {{ $pl['dot'] }}"></span>
                            <span class="text-[10px] font-black {{ $pl['text'] }} uppercase tracking-widest">{{ $pl['label'] }}</span>
                        </span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ticket->category_label }}</p>
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
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Ditangani Oleh</p>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-black text-slate-500">
                                {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                            </div>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $ticket->assignedTo->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tips Card --}}
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/10 rounded-2xl border border-amber-200 dark:border-amber-700/30 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-base">💡</span>
                    <p class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-widest">Tips</p>
                </div>
                <p class="text-xs font-medium text-amber-700 dark:text-amber-500 leading-relaxed">Sertakan detail kronologi kejadian dan screenshot agar tim support dapat merespon lebih cepat.</p>
            </div>
        </div>

    </div>{{-- end grid --}}
</div>
</x-app-layout>
