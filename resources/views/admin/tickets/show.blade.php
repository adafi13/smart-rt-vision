<x-app-layout title="Detail Tiket - {{ $ticket->ticket_number }}" header="Detail Tiket Support">
    <div class="max-w-5xl mx-auto py-8 space-y-8 animate-fade-in-up min-h-screen transition-colors duration-300">

        {{-- Back --}}
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Tiket
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Main Thread --}}
            <div class="lg:col-span-8 space-y-6">
                {{-- First message (description) --}}
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-2xl shadow-slate-200/40 dark:shadow-none overflow-hidden transition-colors">
                    <div class="p-8 border-b border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/50">
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $ticket->subject }}</h2>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>{{ $ticket->ticket_number }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    {{-- Original description bubble --}}
                    <div class="p-8">
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-black text-lg shrink-0 border border-emerald-200 dark:border-emerald-500/20 shadow-lg shadow-emerald-500/10 dark:shadow-none">
                                {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $ticket->user->name }}</span>
                                    <span class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest border border-emerald-200 dark:border-emerald-500/20">Pelapor</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-5 text-sm text-slate-700 dark:text-slate-300 font-bold leading-relaxed whitespace-pre-wrap border border-slate-100 dark:border-slate-700 shadow-inner">{{ $ticket->description }}</div>
                                
                                <div class="flex items-center gap-2 mt-4 text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $ticket->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Replies Divider --}}
                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t-2 border-slate-100 dark:border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-6 bg-slate-50 dark:bg-slate-950 text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.3em] transition-colors">Percakapan</span>
                    </div>
                </div>

                {{-- Replies Thread --}}
                <div class="space-y-8">
                    @foreach($ticket->replies as $reply)
                        <div class="flex items-start gap-5 {{ $reply->is_staff_reply ? 'flex-row-reverse' : '' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-black shrink-0 border transition-all duration-300 {{ $reply->is_staff_reply ? 'bg-slate-900 dark:bg-emerald-600 text-white border-slate-800 dark:border-emerald-500 shadow-xl shadow-slate-900/20 dark:shadow-none' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20 shadow-lg shadow-emerald-500/10 dark:shadow-none' }}">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                            <div class="{{ $reply->is_staff_reply ? 'items-end' : 'items-start' }} flex flex-col max-w-[calc(100%-70px)]">
                                <div class="flex items-center gap-2 mb-3 {{ $reply->is_staff_reply ? 'flex-row-reverse' : '' }}">
                                    <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $reply->user->name }}</span>
                                    @if($reply->is_staff_reply)
                                        <span class="text-[9px] font-black text-emerald-500 dark:text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest border border-emerald-500/20">ApoApps Team</span>
                                    @endif
                                </div>
                                <div class="{{ $reply->is_staff_reply ? 'bg-slate-900 dark:bg-emerald-600 text-white shadow-xl shadow-slate-900/20 dark:shadow-none' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 shadow-lg shadow-slate-200/40 dark:shadow-none' }} rounded-[2rem] p-6 text-sm font-bold leading-relaxed whitespace-pre-wrap transition-colors">
                                    {{ $reply->message }}
                                </div>
                                
                                <div class="flex items-center gap-2 mt-3 text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $reply->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Reply Form (jika tiket masih aktif) --}}
                @if(!in_array($ticket->status, ['resolved', 'closed']))
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-2xl shadow-slate-200/40 dark:shadow-none p-10 transition-colors">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Tambahkan Balasan</h4>
                        </div>
                        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}"  class="space-y-6">
                            @csrf
                            <textarea name="message" rows="5" required placeholder="Tulis balasan atau informasi tambahan Anda di sini..."
                                      class="w-full px-6 py-5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-[2rem] text-sm font-bold text-slate-700 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-500 focus:border-transparent resize-none transition-all leading-relaxed">{{ old('message') }}</textarea>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <input type="file" name="attachment" id="replyFile" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip" class="hidden"
                                           onchange="document.getElementById('replyFileLabel').textContent = this.files[0]?.name || 'Lampirkan File'">
                                    <label for="replyFile" class="inline-flex items-center gap-3 px-5 py-3 bg-slate-50 dark:bg-slate-900 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-xl text-[10px] font-black cursor-pointer transition-all border border-slate-200 dark:border-slate-700 uppercase tracking-widest group">
                                        <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <span id="replyFileLabel">Lampirkan File</span>
                                    </label>
                                </div>
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    <button type="button" onclick="document.getElementById('closeTicketForm').submit()"
                                        class="w-full sm:w-auto px-6 py-4 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 dark:hover:text-red-400 transition-all border border-transparent hover:border-red-100 dark:hover:border-red-500/20">
                                        Selesaikan
                                    </button>
                                    <button type="submit" class="flex-1 sm:flex-none px-10 py-4 bg-slate-900 dark:bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-xl shadow-slate-900/20 dark:shadow-none dark:shadow-emerald-500/20">
                                        Kirim Balasan
                                    </button>
                                </div>
                            </div>
                        </form>
                        {{-- Form close ticket di luar form reply agar tidak nested --}}
                        <form id="closeTicketForm" method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="hidden">
                            @csrf
                        </form>
                    </div>
                @else
                    <div class="bg-slate-100 dark:bg-slate-900/50 rounded-[2rem] border border-slate-200 dark:border-slate-700 p-8 text-center transition-colors">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <p class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.25em]">Tiket ini sudah {{ $ticket->status === 'closed' ? 'ditutup' : 'diselesaikan' }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar Info --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Status Card --}}
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-2xl shadow-slate-200/40 dark:shadow-none p-8 transition-colors">
                    <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.25em] mb-8">Informasi Tiket</h4>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em]">Status Saat Ini</p>
                            @php $sl = $ticket->status_label; @endphp
                            <div class="inline-flex items-center gap-2 px-4 py-2 {{ $sl['bg'] }} {{ $sl['border'] }} dark:bg-opacity-20 border rounded-xl w-full transition-colors">
                                <span class="w-2 h-2 rounded-full {{ $sl['dot'] }} animate-pulse"></span>
                                <span class="text-[10px] font-black {{ $sl['text'] }} uppercase tracking-widest">{{ $sl['label'] }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em]">Prioritas</p>
                            @php $pl = $ticket->priority_label; @endphp
                            <div class="inline-flex items-center gap-2 px-4 py-2 {{ $pl['bg'] }} dark:bg-opacity-20 border border-transparent dark:border-current dark:border-opacity-10 rounded-xl w-full transition-colors">
                                <span class="w-2 h-2 rounded-full {{ $pl['dot'] }}"></span>
                                <span class="text-[10px] font-black {{ $pl['text'] }} uppercase tracking-widest">{{ $pl['label'] }}</span>
                            </div>
                        </div>
                        <div class="space-y-2 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em]">Kategori Masalah</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $ticket->category_label }}</p>
                        </div>
                        @if($ticket->assignedTo)
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em]">Ditangani Oleh</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-black text-slate-500 dark:text-slate-300 uppercase">
                                        {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                                    </div>
                                    <p class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $ticket->assignedTo->name }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="space-y-2">
                            <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em]">Waktu Dibuat</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        @if($ticket->resolved_at)
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em]">Waktu Penyelesaian</p>
                                <p class="text-sm font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-tight">{{ $ticket->resolved_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Tips --}}
                <div class="bg-amber-50 dark:bg-amber-500/10 rounded-[2rem] border border-amber-200 dark:border-amber-500/20 p-8 transition-colors">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-lg">💡</span>
                        <p class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-[0.2em]">Pro Tips</p>
                    </div>
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-500/80 leading-relaxed uppercase tracking-tight">Berikan detail kronologi atau screenshot tambahan agar tim kami dapat mengidentifikasi masalah lebih cepat.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
