<x-super-admin-layout title="Audit Logs">
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">System Audit Logs</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau semua riwayat aktivitas dari seluruh RT & pengguna sistem.</p>
            </div>
            <a href="{{ route('super-admin.audit-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset Filter
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($totalAll) }}</p>
                    <p class="text-xs text-gray-500 font-medium">Total Semua Log</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($totalMonth) }}</p>
                    <p class="text-xs text-gray-500 font-medium">Bulan Ini</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($totalToday) }}</p>
                    <p class="text-xs text-gray-500 font-medium">Hari Ini</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <form method="GET" action="{{ route('super-admin.audit-logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Search --}}
                    <div class="lg:col-span-1">
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Cari</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, aksi, atau IP..." class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none">
                        </div>
                    </div>

                    {{-- Tenant --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tenant / RT</label>
                        <select name="tenant_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none bg-white">
                            <option value="">Semua Tenant</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}" @selected(request('tenant_id') == $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tipe Aksi</label>
                        <select name="action" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none bg-white">
                            <option value="">Semua Aksi</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" @selected(request('action') == $act)>{{ str_replace('_', ' ', strtoupper($act)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Range --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Rentang Tanggal</label>
                        <div class="flex gap-1.5">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="flex-1 px-2 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none" title="Dari tanggal">
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="flex-1 px-2 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none" title="Sampai tanggal">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Active Filters Indicator --}}
        @if(request()->hasAny(['search', 'tenant_id', 'action', 'date_from', 'date_to']))
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-xs text-gray-500 font-semibold">Filter aktif:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-full text-xs font-semibold">
                    Cari: "{{ request('search') }}"
                </span>
            @endif
            @if(request('tenant_id') && ($tn = $tenants->firstWhere('id', request('tenant_id'))))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-semibold">
                    RT: {{ $tn->name }}
                </span>
            @endif
            @if(request('action'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-full text-xs font-semibold">
                    Aksi: {{ str_replace('_', ' ', strtoupper(request('action'))) }}
                </span>
            @endif
            @if(request('date_from') || request('date_to'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-full text-xs font-semibold">
                    Tanggal: {{ request('date_from') ?? '...' }} → {{ request('date_to') ?? '...' }}
                </span>
            @endif
            <span class="text-xs text-gray-400">— {{ $logs->total() }} hasil ditemukan</span>
        </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/60">
                            <th class="px-5 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider w-44">Waktu</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pengguna / RT</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Target Data</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Perubahan</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">IP / Device</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                        @php
                            $actionColors = [
                                'create'   => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'update'   => 'bg-amber-50 text-amber-700 border-amber-100',
                                'delete'   => 'bg-rose-50 text-rose-700 border-rose-100',
                                'export'   => 'bg-blue-50 text-blue-700 border-blue-100',
                                'import'   => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                'login'    => 'bg-violet-50 text-violet-700 border-violet-100',
                                'logout'   => 'bg-slate-50 text-slate-600 border-slate-100',
                            ];
                            $actionKey = collect(array_keys($actionColors))->first(fn($k) => str_contains($log->action, $k));
                            $badgeClass = $actionColors[$actionKey] ?? 'bg-indigo-50 text-indigo-700 border-indigo-100';
                        @endphp
                        <tr class="hover:bg-gray-50/40 transition-colors group">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="text-xs font-semibold text-gray-700">{{ $log->created_at->translatedFormat('d M Y') }}</p>
                                <p class="text-[11px] text-gray-400 font-mono">{{ $log->created_at->format('H:i:s') }}</p>
                                <p class="text-[10px] text-gray-300 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-black flex-shrink-0">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $log->user->name ?? 'System' }}</p>
                                        @if($log->tenant)
                                            <p class="text-xs text-gray-500">{{ $log->tenant->name }}</p>
                                        @elseif($log->rw)
                                            <p class="text-xs text-emerald-500 font-semibold">RW {{ str_pad($log->rw->rw, 3, '0', STR_PAD_LEFT) }} - {{ $log->rw->name }}</p>
                                        @else
                                            <p class="text-xs text-indigo-500 font-bold">Super Admin</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $badgeClass }}">
                                    {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="text-xs font-mono font-semibold text-gray-700">{{ class_basename($log->model_type ?? '') }}</p>
                                <p class="text-[11px] text-gray-400 font-mono">#{{ $log->model_id }}</p>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                @if($log->old_values || $log->new_values)
                                    @php
                                        $formatValues = function($values) {
                                            if (!is_array($values)) return '';
                                            $items = [];
                                            foreach($values as $k => $v) {
                                                if (is_array($v) || is_object($v)) {
                                                    $val = '{...}';
                                                } else {
                                                    $val = Str::limit((string)$v, 30);
                                                }
                                                $items[] = "<span class='text-gray-400'>$k:</span> $val";
                                            }
                                            return implode(', ', $items);
                                        };
                                    @endphp
                                    <div class="text-[10px] font-mono rounded-lg overflow-hidden border border-gray-100">
                                        @if($log->old_values)
                                            <div class="bg-rose-50 text-rose-700 px-2 py-1.5 truncate" title="{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}">
                                                <span class="font-black mr-1">−</span> {!! $formatValues($log->old_values) !!}
                                            </div>
                                        @endif
                                        @if($log->new_values)
                                            <div class="bg-emerald-50 text-emerald-700 px-2 py-1.5 truncate" title="{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}">
                                                <span class="font-black mr-1">+</span> {!! $formatValues($log->new_values) !!}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[11px] text-gray-300 italic">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="text-[11px] font-mono text-gray-600">{{ $log->ip_address ?? '—' }}</p>
                                @if($log->user_agent)
                                    <p class="text-[10px] text-gray-400 truncate max-w-[140px]" title="{{ $log->user_agent }}">
                                        @if(str_contains($log->user_agent, 'Mobile'))
                                            📱 Mobile
                                        @elseif(str_contains($log->user_agent, 'Chrome'))
                                            🌐 Chrome
                                        @elseif(str_contains($log->user_agent, 'Firefox'))
                                            🦊 Firefox
                                        @elseif(str_contains($log->user_agent, 'Safari'))
                                            🧭 Safari
                                        @else
                                            🖥️ {{ Str::limit($log->user_agent, 20) }}
                                        @endif
                                    </p>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-400">Belum ada catatan log aktivitas</p>
                                    <p class="text-xs text-gray-300">Log akan muncul saat admin melakukan perubahan data</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-50">
                @forelse($logs as $log)
                @php
                    $actionColors = [
                        'create' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'update' => 'bg-amber-50 text-amber-700 border-amber-100',
                        'delete' => 'bg-rose-50 text-rose-700 border-rose-100',
                        'export' => 'bg-blue-50 text-blue-700 border-blue-100',
                    ];
                    $actionKey = collect(array_keys($actionColors))->first(fn($k) => str_contains($log->action, $k));
                    $badgeClass = $actionColors[$actionKey] ?? 'bg-indigo-50 text-indigo-700 border-indigo-100';
                @endphp
                <div class="p-4 hover:bg-gray-50/40 transition-colors">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-black flex-shrink-0">
                                {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $log->user->name ?? 'System' }}</p>
                                <p class="text-xs text-gray-500">{{ $log->tenant->name ?? 'Super Admin' }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[11px] font-semibold text-gray-600">{{ $log->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $log->created_at->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 items-center mb-3">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $badgeClass }}">
                            {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                        </span>
                        <span class="text-[11px] text-gray-500 font-mono">{{ class_basename($log->model_type ?? '') }} #{{ $log->model_id }}</span>
                    </div>

                    @if($log->old_values || $log->new_values)
                    <div class="text-[10px] font-mono rounded-lg overflow-hidden border border-gray-100 mb-2">
                        @if($log->old_values)
                            <div class="bg-rose-50 text-rose-700 px-2 py-1">
                                <span class="font-black">−</span> {{ Str::limit(json_encode($log->old_values), 100) }}
                            </div>
                        @endif
                        @if($log->new_values)
                            <div class="bg-emerald-50 text-emerald-700 px-2 py-1">
                                <span class="font-black">+</span> {{ Str::limit(json_encode($log->new_values), 100) }}
                            </div>
                        @endif
                    </div>
                    @endif

                    @if($log->ip_address)
                    <p class="text-[10px] text-gray-400 font-mono">📍 {{ $log->ip_address }}</p>
                    @endif
                </div>
                @empty
                <div class="py-16 text-center text-sm text-gray-400">Belum ada catatan log.</div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-semibold text-gray-800">{{ $logs->firstItem() }}</span>–<span class="font-semibold text-gray-800">{{ $logs->lastItem() }}</span>
                dari <span class="font-semibold text-gray-800">{{ $logs->total() }}</span> log
            </p>
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl px-4 py-2">
                {{ $logs->links() }}
            </div>
        </div>
        @else
        <p class="text-sm text-gray-400 text-center">Menampilkan {{ $logs->count() }} dari {{ $logs->total() }} log</p>
        @endif

    </div>
</x-super-admin-layout>
