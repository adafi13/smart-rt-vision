<x-super-admin-layout title="Detail Tiket #{{ $ticket->id }}">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('super-admin.tickets.index') }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors bg-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    {{ $ticket->subject }}
                </h1>
                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                    <span class="font-mono text-gray-400">#{{ $ticket->id }}</span>
                    <span>&bull;</span>
                    <span class="font-bold text-gray-700">{{ $ticket->tenant->name }}</span>
                    <span>&bull;</span>
                    <span>{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left: Conversation -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-6">
                    
                    @foreach($ticket->replies as $reply)
                        @php
                            // Cek jika reply.user_id adalah staff tenant atau superadmin. 
                            // Untuk simplifikasi, jika reply.user_id == ticket.user_id, asumsikan itu tenant.
                            $isTenant = $reply->user_id === $ticket->user_id;
                        @endphp
                        <div class="flex gap-4 {{ $isTenant ? '' : 'flex-row-reverse text-right' }}">
                            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-white shadow-sm {{ $isTenant ? 'bg-slate-600' : 'bg-indigo-600' }}">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                            <div class="{{ $isTenant ? 'bg-gray-50 border border-gray-100' : 'bg-indigo-50 border border-indigo-100' }} p-4 rounded-2xl max-w-[85%] relative">
                                <div class="flex items-center gap-2 mb-1 {{ $isTenant ? '' : 'justify-end' }}">
                                    <span class="text-xs font-bold {{ $isTenant ? 'text-gray-900' : 'text-indigo-900' }}">{{ $reply->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $reply->created_at->format('H:i') }}</span>
                                </div>
                                <div class="text-sm {{ $isTenant ? 'text-gray-700' : 'text-indigo-800' }} whitespace-pre-wrap">{{ $reply->message }}</div>
                            </div>
                        </div>
                    @endforeach

                    @if($ticket->status !== 'closed')
                    <div class="pt-6 border-t border-gray-100">
                        <form action="{{ route('super-admin.tickets.reply', $ticket) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Balas Tiket</label>
                                <textarea name="message" rows="4" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 px-4 py-3 transition-colors" placeholder="Ketik balasan Anda di sini..."></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="status" value="resolved" id="mark_resolved" class="rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="mark_resolved" class="text-xs text-gray-600 font-medium cursor-pointer">Tandai Selesai (Resolved)</label>
                                </div>
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                                    Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="pt-6 border-t border-gray-100 text-center">
                        <div class="inline-flex items-center gap-2 text-rose-600 bg-rose-50 px-4 py-2 rounded-lg text-sm font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Tiket ini telah ditutup.
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right: Details -->
            <div class="space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Tiket</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Status</p>
                            @php
                                $sClass = [
                                    'open' => 'bg-rose-50 text-rose-600 border-rose-200',
                                    'answered' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    'closed' => 'bg-gray-100 text-gray-500 border-gray-200',
                                ][$ticket->status] ?? 'bg-gray-100 text-gray-500';
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-lg border text-[11px] font-bold uppercase {{ $sClass }}">{{ $ticket->status }}</span>
                        </div>
                        
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Prioritas</p>
                            @php
                                $pClass = [
                                    'low' => 'bg-gray-100 text-gray-600',
                                    'normal' => 'bg-indigo-50 text-indigo-600',
                                    'high' => 'bg-rose-50 text-rose-600'
                                ][$ticket->priority] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pClass }}">{{ $ticket->priority }}</span>
                        </div>

                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Dibuat Oleh</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">
                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">{{ $ticket->user->name }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $ticket->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Tenant (RT)</p>
                            <a href="{{ route('super-admin.show', $ticket->tenant) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                                {{ $ticket->tenant->name }}
                            </a>
                        </div>
                    </div>
                </div>

                @if($ticket->status !== 'closed')
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-4">Tindakan Cepat</h2>
                    
                    <form action="{{ route('super-admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <input type="hidden" name="message" value="Tiket ditutup oleh Super Admin.">
                        <input type="hidden" name="status" value="closed">
                        <button type="submit" onclick="return confirm('Tutup tiket ini? Tidak akan bisa dibalas lagi.')" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition-colors border border-rose-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Tutup Tiket (Close)
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-super-admin-layout>
