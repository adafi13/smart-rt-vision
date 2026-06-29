<x-super-admin-layout title="Helpdesk / Tiket Bantuan">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Helpdesk / Tiket Bantuan</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola keluhan dan pertanyaan dari Tenant (RT)</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('super-admin.tickets.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !request('status') ? 'bg-indigo-50 text-indigo-700' : 'bg-white text-gray-600 border border-gray-200' }}">Semua</a>
                <a href="{{ route('super-admin.tickets.index', ['status' => 'open']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') == 'open' ? 'bg-rose-50 text-rose-700' : 'bg-white text-gray-600 border border-gray-200' }}">Open</a>
                <a href="{{ route('super-admin.tickets.index', ['status' => 'answered']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') == 'answered' ? 'bg-amber-50 text-amber-700' : 'bg-white text-gray-600 border border-gray-200' }}">Answered</a>
                <a href="{{ route('super-admin.tickets.index', ['status' => 'resolved']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') == 'resolved' ? 'bg-emerald-50 text-emerald-700' : 'bg-white text-gray-600 border border-gray-200' }}">Resolved</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wider font-bold text-gray-500">
                            <th class="px-5 py-4">ID</th>
                            <th class="px-5 py-4">Tenant (RT)</th>
                            <th class="px-5 py-4">Subjek</th>
                            <th class="px-5 py-4">Prioritas</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Waktu</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="text-xs font-mono font-bold text-gray-500">#{{ $ticket->id }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-bold text-gray-900">{{ $ticket->tenant->name }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $ticket->user->name }}</p>
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
                                    {{ $ticket->created_at->diffForHumans() }}
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('super-admin.tickets.show', $ticket) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 text-xs font-bold text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500 text-sm">
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
    </div>
</x-super-admin-layout>
