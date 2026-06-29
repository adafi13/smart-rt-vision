<x-app-layout title="Tiket {{ $ticket->ticket_number }}" header="Detail Tiket">
@php
    $sl = $ticket->status_label;
    $pl = $ticket->priority_label;
    $firstReply = $ticket->replies->first();
    $restReplies = $ticket->replies->skip(1);
    $isActive = !in_array($ticket->status, ['resolved', 'closed']);
@endphp

<div class="max-w-6xl mx-auto">

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('admin.tickets.index') }}"
           class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 uppercase tracking-widest transition-colors group">
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
    @if($errors->any())
    <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-700 rounded-xl text-rose-700 dark:text-rose-400 text-sm font-bold">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- CHAT PANEL --}}
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col" style="min-height:580px">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-sm shrink-0 shadow-md shadow-emerald-500/20">
                        {{ strtoupper(substr($ticket->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-sm font-black text-slate-900 dark:text-white truncate">{{ $ticket->subject }}</h2>
                        <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-0.5">
                            {{ $ticket->user?->name ?? '—' }} &middot; {{ $ticket->category_label }} &middot; {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                {{-- Thread --}}
                <div class="flex-1 px-6 py-5 space-y-6 overflow-y-auto" id="chatThread">

                    {{-- First message --}}
                    @if($firstReply)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-sm">
                            {{ strtoupper(substr($ticket->user?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-black text-slate-800 dark:text-slate-100">{{ $ticket->user?->name ?? '—' }}</span>
                                <span class="text-[9px] font-bold text-emerald-700 bg-emerald-100 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-800 px-2 py-0.5 rounded-full">Pelapor</span>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-2xl rounded-tl-sm px-4 py-3.5 text-sm text-slate-700 dark:text-slate-200 leading-relaxed whitespace-pre-wrap">{{ $firstReply->message }}</div>
                            <p class="text-[10px] text-slate-400 mt-1.5 ml-1">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif

                    @if($restReplies->isNotEmpty())
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700/60"></div>
                        <span class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-widest">Balasan</span>
                        <div class="flex-1 h-px bg-slate-100 dark:bg-slate-700/60"></div>
                    </div>
                    @endif

                    {{-- Replies --}}
                    @foreach($restReplies as $reply)
                        @if($reply->is_staff_reply)
                        <div class="flex items-start gap-3 flex-row-reverse">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 dark:from-emerald-600 dark:to-teal-700 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-sm">
                                {{ strtoupper(substr($reply->user?->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="flex-1 flex flex-col items-end">
                                <div class="flex items-center gap-2 mb-2 flex-row-reverse">
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100">{{ $reply->user?->name ?? 'Tim Support' }}</span>
                                    <span class="text-[9px] font-bold text-white bg-slate-800 dark:bg-emerald-600 px-2 py-0.5 rounded-full">{{ config('app.name') }}</span>
                                </div>
                                <div class="bg-slate-900 dark:bg-emerald-700 rounded-2xl rounded-tr-sm px-4 py-3.5 text-sm text-white leading-relaxed whitespace-pre-wrap w-full">{{ $reply->message }}</div>
                                <p class="text-[10px] text-slate-400 mt-1.5 mr-1">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-sm">
                                {{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100">{{ $reply->user?->name ?? 'Pengguna' }}</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700/80 rounded-2xl rounded-tl-sm px-4 py-3.5 text-sm text-slate-700 dark:text-slate-200 leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</div>
                                <p class="text-[10px] text-slate-400 mt-1.5 ml-1">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                        @if($reply->attachment)
                        <div class="{{ $reply->is_staff_reply ? 'justify-end' : 'pl-11' }} flex">
                            <a href="{{ Storage::url($reply->attachment) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg text-[10px] font-bold transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                Lihat Lampiran
                            </a>
                        </div>
                        @endif
                    @endforeach

                    @if($ticket->replies->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada percakapan</p>
                    </div>
                    @endif
                </div>

                {{-- Reply Form --}}
                @if($isActive)
                <div class="border-t border-slate-100 dark:border-slate-700 p-5">
                    <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data">
                        @csrf
                        <textarea name="message" rows="3"
                                  placeholder="Tulis balasan atau informasi tambahan..."
                                  class="w-full px-4 py-3 mb-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-white placeholder-slate-400 resize-none focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all leading-relaxed">{{ old('message') }}</textarea>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <input type="file" name="attachment" id="replyFile" class="hidden" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip"
                                       onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name ?? ''">
                                <label for="replyFile" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[10px] font-bold text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 cursor-pointer transition-colors border border-slate-200 dark:border-slate-600 hover:border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    Lampiran
                                </label>
                                <span id="fileLabel" class="text-[10px] text-slate-400 truncate max-w-[100px]"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="if(confirm('Tandai tiket ini sebagai selesai?')) document.getElementById('closeTicketForm').submit()"
                                        class="px-4 py-2 rounded-xl text-[10px] font-black text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors border border-slate-200 dark:border-slate-600 hover:border-red-200 uppercase tracking-wider">
                                    ✓ Selesaikan
                                </button>
                                <button type="submit"
                                        class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-500/25 active:scale-95">
                                    Kirim Balasan →
                                </button>
                            </div>
                        </div>
                    </form>
                    <form id="closeTicketForm" method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="hidden">@csrf</form>
                </div>
                @else
                <div class="border-t border-slate-100 dark:border-slate-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $ticket->status === 'resolved' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-slate-100 dark:bg-slate-700' }} flex items-center justify-center">
                            <svg class="w-4 h-4 {{ $ticket->status === 'resolved' ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $ticket->status === 'resolved' ? 'M5 13l4 4L19 7' : 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">Tiket {{ $ticket->status === 'resolved' ? 'Diselesaikan' : 'Ditutup' }}</p>
                            @if($ticket->resolved_at)
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $ticket->resolved_at->translatedFormat('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40">
                    <h3 class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Informasi Tiket</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nomor</span>
                        <span class="text-xs font-black font-mono text-slate-700 dark:text-slate-200">{{ $ticket->ticket_number }}</span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 {{ $sl['bg'] }} dark:bg-opacity-20 border {{ $sl['border'] }} rounded-lg">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
                            <span class="text-[9px] font-black {{ $sl['text'] }} uppercase tracking-wider">{{ $sl['label'] }}</span>
                        </span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Prioritas</span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 {{ $pl['bg'] }} dark:bg-opacity-20 rounded-lg">
                            <span class="w-1.5 h-1.5 rounded-full {{ $pl['dot'] }}"></span>
                            <span class="text-[9px] font-black {{ $pl['text'] }} uppercase tracking-wider">{{ $pl['label'] }}</span>
                        </span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kategori</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-right max-w-[60%]">{{ $ticket->category_label }}</span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pelapor</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-right max-w-[60%]">{{ $ticket->user?->name ?? '—' }}</span>
                    </div>
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Dibuat</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $ticket->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    @if($ticket->resolved_at)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Selesai</span>
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 text-right">{{ $ticket->resolved_at->translatedFormat('d M Y') }}</span>
                    </div>
                    @endif
                    @if($ticket->assignedTo)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ditangani</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $ticket->assignedTo->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-700/30 p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span>💡</span>
                    <p class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-widest">Tips</p>
                </div>
                <p class="text-xs text-amber-700/80 dark:text-amber-500/80 leading-relaxed">Sertakan detail kronologi dan screenshot agar tim kami dapat merespon lebih cepat.</p>
            </div>
        </div>

    </div>
</div>

<script>
    const t = document.getElementById('chatThread');
    if (t) t.scrollTop = t.scrollHeight;
</script>
</x-app-layout>
