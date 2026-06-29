<x-super-admin-layout title="Tiket {{ $ticket->ticket_number }}" header="Detail Tiket Support">
<div class="max-w-5xl mx-auto py-4 space-y-5">

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 text-sm font-semibold">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Back --}}
    <a href="{{ route('super-admin.tickets.index') }}"
        class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Tiket
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ============================================================ --}}
        {{-- MAIN THREAD                                                  --}}
        {{-- ============================================================ --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Ticket Header --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                    @php $sl = $ticket->status_label; $pl = $ticket->priority_label; @endphp
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-[10px] font-mono font-black text-slate-400 dark:text-slate-500 uppercase">{{ $ticket->ticket_number }}</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $sl['bg'] }} dark:bg-opacity-10 border {{ $sl['border'] }} dark:border-opacity-30 rounded-lg">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sl['dot'] }}"></span>
                            <span class="text-[9px] font-black {{ $sl['text'] }} uppercase tracking-widest">{{ $sl['label'] }}</span>
                        </span>
                        <span class="px-2.5 py-1 {{ $pl['bg'] }} dark:bg-opacity-10 rounded-lg text-[9px] font-black {{ $pl['text'] }} uppercase tracking-widest">{{ $pl['label'] }}</span>
                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $ticket->category_label }}</span>
                    </div>
                    <h2 class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $ticket->subject }}</h2>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">
                        {{ $ticket->user->name ?? '—' }} · {{ $ticket->tenant->name ?? 'N/A' }} · {{ $ticket->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Replies Thread --}}
            @foreach($ticket->replies as $reply)
                <div class="flex items-start gap-3 {{ $reply->is_staff_reply ? 'flex-row-reverse' : '' }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black shrink-0
                        {{ $reply->is_staff_reply
                            ? 'bg-slate-900 dark:bg-emerald-600 text-white'
                            : 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' }}">
                        {{ strtoupper(substr($reply->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="{{ $reply->is_staff_reply ? 'items-end' : 'items-start' }} flex flex-col max-w-[calc(100%-52px)]">
                        <div class="flex items-center gap-2 mb-1.5 {{ $reply->is_staff_reply ? 'flex-row-reverse' : '' }}">
                            <span class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $reply->user->name ?? '—' }}</span>
                            @if($reply->is_staff_reply)
                                <span class="text-[9px] font-black text-white bg-slate-900 dark:bg-emerald-600 px-2 py-0.5 rounded-full uppercase tracking-widest">{{ config('app.name') }} Team</span>
                            @else
                                <span class="text-[9px] font-black text-white bg-emerald-600 px-2 py-0.5 rounded-full uppercase tracking-widest">Tenant</span>
                            @endif
                        </div>
                        <div class="{{ $reply->is_staff_reply
                            ? 'bg-slate-900 dark:bg-slate-700 text-white'
                            : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}
                            rounded-2xl p-4 text-sm font-medium leading-relaxed whitespace-pre-wrap shadow-sm">
                            {{ $reply->message }}
                        </div>
                        
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1.5">{{ $reply->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                </div>
            @endforeach

            {{-- Admin Reply Form --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest mb-4">Balas & Perbarui Status</h4>

                @if(session('error'))
                    <div class="mb-4 flex items-center gap-2 px-4 py-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-700 dark:text-rose-400 text-sm font-semibold">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('super-admin.tickets.reply', $ticket) }}"  class="space-y-4">
                    @csrf

                    <textarea name="message" rows="5" required
                        placeholder="Tulis balasan kepada tenant..."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none transition-all">{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror

                    <div class="flex flex-col sm:flex-row gap-3">
                        <select name="new_status"
                            class="flex-1 px-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                            <option value="in_progress"   @selected($ticket->status === 'in_progress')>🔵 Sedang Diproses</option>
                            <option value="waiting_reply" @selected($ticket->status === 'waiting_reply')>🟣 Menunggu Info dari Tenant</option>
                            <option value="open"          @selected($ticket->status === 'open')>🔴 Buka Kembali</option>
                            <option value="resolved"      @selected($ticket->status === 'resolved')>🟢 Tandai Selesai</option>
                            <option value="closed"        @selected($ticket->status === 'closed')>⚫ Tutup Tiket</option>
                        </select>
                        <button type="submit"
                            class="px-6 py-2.5 bg-slate-900 dark:bg-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-sm">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SIDEBAR                                                      --}}
        {{-- ============================================================ --}}
        <div class="space-y-4">

            {{-- Ticket Info --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Informasi Tiket</h4>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Tenant</p>
                        <p class="font-bold text-slate-900 dark:text-slate-100">{{ $ticket->tenant->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Dilaporkan Oleh</p>
                        <p class="font-bold text-slate-900 dark:text-slate-100">{{ $ticket->user->name ?? '—' }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ $ticket->user->email ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Kategori</p>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $ticket->category_label }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Dibuat</p>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    @if($ticket->resolved_at)
                    <div>
                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Diselesaikan</p>
                        <p class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $ticket->resolved_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Status Update --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Update Status</h4>
                <form method="POST" action="{{ route('super-admin.tickets.status', $ticket) }}">
                    @csrf @method('PATCH')
                    <select name="status"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all mb-3">
                        <option value="open"          @selected($ticket->status === 'open')>🔴 Open</option>
                        <option value="in_progress"   @selected($ticket->status === 'in_progress')>🔵 Diproses</option>
                        <option value="waiting_reply" @selected($ticket->status === 'waiting_reply')>🟣 Tunggu Info</option>
                        <option value="resolved"      @selected($ticket->status === 'resolved')>🟢 Selesai</option>
                        <option value="closed"        @selected($ticket->status === 'closed')>⚫ Ditutup</option>
                    </select>
                    <button type="submit"
                        class="w-full py-2.5 bg-slate-900 dark:bg-slate-700 hover:bg-emerald-600 dark:hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Simpan Status
                    </button>
                </form>
            </div>

            {{-- Assign --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Assign ke Admin</h4>
                <form method="POST" action="{{ route('super-admin.tickets.assign', $ticket) }}">
                    @csrf @method('PATCH')
                    <select name="assigned_to"
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all mb-3">
                        <option value="">Belum Di-assign</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" @selected($ticket->assigned_to === $admin->id)>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="w-full py-2.5 bg-slate-900 dark:bg-slate-700 hover:bg-emerald-600 dark:hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Simpan Assignment
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
</x-super-admin-layout>
