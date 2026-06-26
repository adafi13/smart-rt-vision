<x-super-admin-layout title="Daftar Tenant (RT)">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Tenant (RT)</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola semua pendaftar dan mitra aktif platform.</p>
            </div>
            <a href="#" class="btn-primary text-sm shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Manual
            </a>
        </div>

        {{-- Tenant List --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- List Header + Filter --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Daftar Tenant (RT)</h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tenants->total() }} total tenant terdaftar</p>
                    </div>
                    <form method="GET" class="flex flex-col sm:flex-row gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau slug RT..." class="input-field text-sm" style="min-width:200px;">
                        <select name="status" class="input-field text-sm max-w-xs" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            @foreach(['trial', 'active', 'expired', 'suspended'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary text-sm">Cari</button>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama RT</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Owner / PIC</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Langganan</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Jml KK</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Terdaftar</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($tenants as $tenant)
                        @php
                            $owner = $tenant->users->first();
                            $sub = $tenant->subscriptions->first();
                            $badge = [
                                'trial'     => 'bg-amber-100 text-amber-700',
                                'active'    => 'bg-emerald-100 text-emerald-700',
                                'expired'   => 'bg-rose-100 text-rose-700',
                                'suspended' => 'bg-gray-100 text-gray-600',
                            ][$tenant->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-4 py-3 text-xs font-mono text-gray-400">#{{ $tenant->id }}</td>
                            <td class="px-4 py-3">
                                <p class="font-bold text-gray-900">{{ $tenant->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $tenant->slug }}</p>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                @if($owner)
                                <p class="font-semibold text-gray-800">{{ $owner->name }}</p>
                                <p class="text-xs text-gray-400">{{ $owner->email }}</p>
                                @else
                                <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                @if($sub)
                                <p class="font-semibold text-gray-800 text-xs">{{ $sub->plan?->name ?? 'No Package' }}</p>
                                <p class="text-xs text-gray-400">s/d {{ $sub->current_period_end?->translatedFormat('d M Y') ?? '-' }}</p>
                                @else
                                <span class="text-xs text-amber-600 font-semibold">Free Trial</span>
                                @if($tenant->trial_ends_at)
                                <p class="text-xs text-gray-400">s/d {{ $tenant->trial_ends_at->translatedFormat('d M Y') }}</p>
                                @endif
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700 font-semibold hidden sm:table-cell">{{ $tenant->families_count }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ strtoupper($tenant->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell">{{ $tenant->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center gap-1.5 justify-end">
                                    <a href="{{ route('super-admin.show', $tenant) }}" class="px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">Detail</a>
                                    <form action="{{ route('super-admin.impersonate', $tenant) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-lg transition-colors" title="Login sebagai RT">Login</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-sm text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                Belum ada tenant yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tenants->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $tenants->links() }}
            </div>
            @endif
        </div>

    </div>
</x-super-admin-layout>
