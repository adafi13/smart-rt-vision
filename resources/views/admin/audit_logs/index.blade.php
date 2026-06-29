<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900 leading-tight">Log Aktivitas (Audit Trail)</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau riwayat aktivitas semua pengurus RT untuk menjaga keamanan data.</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-12">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Waktu</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Pengurus (Aktor)</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Aktivitas</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Tipe Data</th>
                            <th class="px-6 py-4 font-bold tracking-wider">IP / Perangkat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-900 font-medium">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->user)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $log->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $log->user->tenant_role ?? 'Warga')) }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500 italic">Sistem / Dihapus</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $actionColors = [
                                            'created' => 'bg-green-100 text-green-700',
                                            'updated' => 'bg-blue-100 text-blue-700',
                                            'deleted' => 'bg-red-100 text-red-700',
                                            'login' => 'bg-indigo-100 text-indigo-700',
                                        ];
                                        $actionKey = explode('_', $log->action)[0];
                                        $color = $actionColors[$actionKey] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $color }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded inline-block">
                                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-600">{{ $log->ip_address ?? '-' }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5 truncate max-w-[150px]" title="{{ $log->user_agent }}">
                                        {{ $log->user_agent ?? '-' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada aktivitas yang terekam.</p>
                                    <p class="text-xs text-gray-400 mt-1">Log aktivitas pengurus akan muncul di sini secara otomatis.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
