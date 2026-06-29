<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Helpdesk') }}
        </h2>
    </x-slot>
    <div class="max-w-6xl mx-auto space-y-6" x-data="{ openCreateModal: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Helpdesk</h1>
                <p class="text-sm text-gray-500 mt-0.5">Hubungi dukungan KakaAI jika Anda mengalami kendala atau butuh bantuan.</p>
            </div>
            <button @click="openCreateModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Tiket Baru
            </button>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wider font-bold text-gray-500">
                            <th class="px-5 py-4">ID</th>
                            <th class="px-5 py-4">Subjek</th>
                            <th class="px-5 py-4">Prioritas</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Terakhir Diupdate</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="text-xs font-mono font-bold text-gray-500">#{{ $ticket->id }}</span>
                                </td>
                                <td class="px-5 py-3 font-medium text-gray-700">
                                    {{ Str::limit($ticket->subject, 40) }}
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $pClass = [
                                            'low' => 'bg-gray-100 text-gray-600',
                                            'normal' => 'bg-indigo-50 text-indigo-600',
                                            'high' => 'bg-rose-50 text-rose-600'
                                        ][$ticket->priority] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pClass }}">{{ $ticket->priority }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $sClass = [
                                            'open' => 'bg-rose-50 text-rose-600 border-rose-200',
                                            'answered' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'closed' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        ][$ticket->status] ?? 'bg-gray-100 text-gray-500';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg border text-[10px] font-bold uppercase {{ $sClass }}">{{ $ticket->status }}</span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-xs font-bold text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-500 text-sm">
                                    Belum ada tiket bantuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $tickets->links() }}
            </div>
            @endif
        </div>

        {{-- ===== MODAL BUAT TIKET ===== --}}
        <div x-show="openCreateModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openCreateModal = false"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-gray-900">Buat Tiket Bantuan</h3>
                        <p class="text-xs text-gray-500">Jelaskan kendala Anda sejelas mungkin.</p>
                    </div>
                    <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('admin.tickets.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Subjek Kendala</label>
                            <input type="text" name="subject" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition font-semibold" placeholder="Contoh: Kesalahan sistem saat tambah KK">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Prioritas</label>
                            <select name="priority" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition font-semibold">
                                <option value="low">Rendah (Pertanyaan umum)</option>
                                <option value="normal" selected>Normal (Bug minor)</option>
                                <option value="high">Tinggi (Sistem tidak bisa digunakan / Kritis)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pesan Detail</label>
                            <textarea name="message" rows="5" required class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition" placeholder="Jelaskan detail kendala, langkah-langkah untuk menemukannya, dll..."></textarea>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end gap-2">
                        <button type="button" @click="openCreateModal = false"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-sm">
                            Kirim Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
