<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900 leading-tight">Log Aktivitas (Audit Trail)</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau riwayat aktivitas semua pengurus RT untuk menjaga keamanan data.</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-12">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <!-- Desktop View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Waktu</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Pengurus (Aktor)</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Aktivitas</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Modul Data</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Perangkat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-slate-900 font-medium">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $log->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->user)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs border border-indigo-100">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $log->user->name }}</div>
                                                <div class="text-[11px] text-slate-500 font-medium uppercase tracking-wider">{{ str_replace('_', ' ', $log->user->rt_role ?? 'Warga') }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic font-medium">Sistem / Terhapus</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $actionColors = [
                                            'created' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'updated' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'deleted' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'login' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        ];
                                        $actionKey = explode('_', $log->action)[0];
                                        $color = $actionColors[$actionKey] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                        
                                        // Format action text beautifully
                                        $actionText = str_replace('_', ' ', $log->action);
                                        $actionText = str_replace(['created', 'updated', 'deleted'], ['Menambahkan', 'Memperbarui', 'Menghapus'], $actionText);
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold tracking-wide border {{ $color }}">
                                        {{ $actionText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-[11px] font-medium text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-md inline-block">
                                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium text-slate-700">{{ $log->ip_address ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 mt-1 truncate max-w-[150px]" title="{{ $log->user_agent }}">
                                        {{ $log->user_agent ?? '-' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-slate-500 font-bold">Belum ada aktivitas yang terekam.</p>
                                    <p class="text-xs text-slate-400 mt-1">Setiap penambahan atau perubahan data (Warga, UMKM, Agenda, dll) akan otomatis muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($logs as $log)
                    <div class="p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                {{ $log->created_at->format('d M Y - H:i') }}
                            </div>
                            @php
                                $actionColors = [
                                    'created' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'updated' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'deleted' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'login' => 'bg-purple-50 text-purple-700 border-purple-200',
                                ];
                                $actionKey = explode('_', $log->action)[0];
                                $color = $actionColors[$actionKey] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                
                                $actionText = str_replace('_', ' ', $log->action);
                                $actionText = str_replace(['created', 'updated', 'deleted'], ['Menambahkan', 'Memperbarui', 'Menghapus'], $actionText);
                            @endphp
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wide border {{ $color }}">
                                {{ $actionText }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                            @if($log->user)
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm border border-indigo-100 shrink-0">
                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">{{ $log->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ str_replace('_', ' ', $log->user->rt_role ?? 'Warga') }}</div>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <span class="text-slate-400 italic font-medium text-sm">Sistem / Terhapus</span>
                            @endif
                        </div>
                        
                        <div class="mt-3 flex items-center justify-between">
                            <div class="font-mono text-[10px] font-medium text-slate-600 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-md inline-block">
                                Modul: {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            </div>
                            <div class="text-[10px] text-slate-400" title="{{ $log->user_agent }}">
                                IP: {{ $log->ip_address ?? '-' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-slate-500 font-bold">Belum ada aktivitas.</p>
                        <p class="text-xs text-slate-400 mt-1">Setiap aktivitas warga dan pengurus akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
            
            @if($logs->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
