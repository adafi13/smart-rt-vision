<x-super-admin-layout title="Audit Logs">
    <div class="max-w-6xl space-y-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900">System Audit Logs</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau semua riwayat aktivitas dari pengguna di seluruh tenant</p>
        </div>

        <!-- Filter -->
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna atau aksi..." class="input-field flex-1">
            <select name="tenant_id" class="input-field max-w-xs" onchange="this.form.submit()">
                <option value="">Semua Tenant</option>
                @foreach($tenants as $t)
                    <option value="{{ $t->id }}" @selected(request('tenant_id') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Filter</button>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Waktu</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi & Modul</th>
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perubahan Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $log->created_at->translatedFormat('d M Y H:i:s') }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-gray-900">{{ $log->user->name ?? 'System' }}</p>
                            <p class="text-xs text-gray-500">{{ $log->tenant->name ?? 'Superadmin' }}</p>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ strtoupper($log->action) }}
                            </span>
                            <p class="text-[11px] text-gray-400 font-mono mt-1" title="{{ $log->model_type }}">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($log->old_values || $log->new_values)
                                <div class="text-[11px] font-mono text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100 max-h-24 overflow-y-auto">
                                    @if($log->old_values)
                                        <div class="text-rose-600 truncate">- {{ json_encode($log->old_values) }}</div>
                                    @endif
                                    @if($log->new_values)
                                        <div class="text-emerald-600 truncate">+ {{ json_encode($log->new_values) }}</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Tidak ada detail</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-12 text-center text-sm text-gray-400">Belum ada catatan log aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-4 py-3 bg-white border border-gray-100 shadow-sm rounded-2xl">{{ $logs->links() }}</div>
        @endif
    </div>
</x-super-admin-layout>
