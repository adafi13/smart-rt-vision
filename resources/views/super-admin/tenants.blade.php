<x-super-admin-layout title="Daftar Tenant (RT)">
<div class="space-y-5">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Tenant (RT)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua RT yang terdaftar di platform SmartRT Vision.</p>
        </div>
    </div>

    {{-- ===== STATUS QUICK FILTER TABS ===== --}}
    <div class="flex flex-wrap gap-2">
        @php
            $tabs = [
                ['key' => '',          'label' => 'Semua',    'count' => $counts['all'],       'color' => 'gray'],
                ['key' => 'active',    'label' => 'Aktif',    'count' => $counts['active'],    'color' => 'emerald'],
                ['key' => 'trial',     'label' => 'Trial',    'count' => $counts['trial'],     'color' => 'amber'],
                ['key' => 'expired',   'label' => 'Expired',  'count' => $counts['expired'],   'color' => 'rose'],
                ['key' => 'suspended', 'label' => 'Suspended','count' => $counts['suspended'], 'color' => 'slate'],
            ];
            $colorMap = [
                'gray'    => ['active' => 'bg-gray-900 text-white border-gray-900',    'inactive' => 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'],
                'emerald' => ['active' => 'bg-emerald-600 text-white border-emerald-600', 'inactive' => 'bg-white text-emerald-700 border-emerald-200 hover:border-emerald-400'],
                'amber'   => ['active' => 'bg-amber-500 text-white border-amber-500',   'inactive' => 'bg-white text-amber-700 border-amber-200 hover:border-amber-400'],
                'rose'    => ['active' => 'bg-rose-600 text-white border-rose-600',     'inactive' => 'bg-white text-rose-700 border-rose-200 hover:border-rose-400'],
                'slate'   => ['active' => 'bg-slate-600 text-white border-slate-600',   'inactive' => 'bg-white text-slate-600 border-slate-200 hover:border-slate-400'],
            ];
            $currentStatus = request('status', '');
        @endphp
        @foreach($tabs as $tab)
        @php $isActive = $currentStatus === $tab['key']; $cls = $colorMap[$tab['color']][$isActive ? 'active' : 'inactive']; @endphp
        <a href="{{ route('super-admin.tenants.index', array_merge(request()->except('status','page'), $tab['key'] ? ['status' => $tab['key']] : [])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-xs font-bold transition-all {{ $cls }}">
            {{ $tab['label'] }}
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $isActive ? 'bg-white/20' : 'bg-gray-100' }}">{{ $tab['count'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- ===== SEARCH BAR ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('super-admin.tenants.index') }}" class="flex flex-col sm:flex-row gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama RT, slug, atau email owner..." class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none">
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('super-admin.tenants.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
            @endif
        </form>
        @if(request('search'))
        <p class="text-xs text-gray-500 mt-2 ml-1">
            Menampilkan <span class="font-bold text-gray-800">{{ $tenants->total() }}</span> hasil untuk "<span class="font-bold text-indigo-600">{{ request('search') }}</span>"
        </p>
        @endif
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Table header info --}}
        <div class="px-5 py-3.5 border-b border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-500">
                Menampilkan <span class="font-bold text-gray-800">{{ $tenants->firstItem() ?? 0 }}</span>–<span class="font-bold text-gray-800">{{ $tenants->lastItem() ?? 0 }}</span>
                dari <span class="font-bold text-gray-800">{{ $tenants->total() }}</span> tenant
            </p>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/60 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider w-12">ID</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Nama RT</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Owner / PIC</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Paket & Masa Aktif</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">KK</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($tenants as $tenant)
                    @php
                        $owner = $tenant->users->first();
                        $sub   = $tenant->subscriptions->first();
                        $statusConfig = [
                            'trial'     => ['label' => 'Trial',     'dot' => 'bg-amber-400',   'badge' => 'bg-amber-50 text-amber-700 border border-amber-200'],
                            'active'    => ['label' => 'Aktif',     'dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
                            'expired'   => ['label' => 'Expired',   'dot' => 'bg-rose-400',    'badge' => 'bg-rose-50 text-rose-700 border border-rose-200'],
                            'suspended' => ['label' => 'Suspended', 'dot' => 'bg-gray-400',    'badge' => 'bg-gray-100 text-gray-600 border border-gray-200'],
                        ];
                        $sc = $statusConfig[$tenant->status] ?? $statusConfig['expired'];

                        // Days remaining
                        $expDate = $sub?->current_period_end ?? $tenant->trial_ends_at;
                        $daysLeft = $expDate ? now()->diffInDays($expDate, false) : null;
                        $isExpiring = $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;
                    @endphp
                    <tr class="hover:bg-gray-50/40 transition-colors group">
                        <td class="px-5 py-4 text-[11px] font-mono text-gray-400">#{{ $tenant->id }}</td>
                        <td class="px-5 py-4">
                            <a href="{{ route('super-admin.show', $tenant) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition-colors">{{ $tenant->name }}</a>
                            <p class="text-[11px] text-gray-400 font-mono mt-0.5">{{ $tenant->slug }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($owner)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 text-[11px] font-black flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($owner->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-800">{{ $owner->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $owner->email }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-[11px] text-gray-300 italic">Belum ada owner</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($sub)
                            <p class="text-xs font-bold text-gray-800">{{ $sub->plan?->name ?? 'No Package' }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <p class="text-[10px] text-gray-400">s/d {{ $sub->current_period_end?->translatedFormat('d M Y') ?? '—' }}</p>
                                @if($isExpiring)
                                <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-md">{{ $daysLeft }}h lagi!</span>
                                @endif
                            </div>
                            @else
                            <p class="text-xs font-bold text-amber-600">Free Trial</p>
                            @if($tenant->trial_ends_at)
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <p class="text-[10px] text-gray-400">s/d {{ $tenant->trial_ends_at->translatedFormat('d M Y') }}</p>
                                @if($isExpiring)
                                <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-md">{{ $daysLeft }}h lagi!</span>
                                @endif
                            </div>
                            @endif
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-black min-w-[28px]">{{ $tenant->families_count }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $sc['badge'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} {{ $tenant->status === 'active' ? 'animate-pulse' : '' }}"></span>
                                {{ $sc['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs text-gray-600">{{ $tenant->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $tenant->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center gap-1.5 justify-end opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('super-admin.show', $tenant) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors"
                                   title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                                <form action="{{ route('super-admin.impersonate', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-[11px] font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-lg transition-colors"
                                            title="Masuk sebagai RT ini"
                                            onclick="return confirm('Masuk sebagai admin {{ $tenant->name }}?')">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                        Login
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-400">Tidak ada tenant ditemukan</p>
                                @if(request()->hasAny(['search','status']))
                                <a href="{{ route('super-admin.tenants.index') }}" class="text-xs text-indigo-600 hover:underline">Hapus semua filter</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y divide-gray-50">
            @forelse($tenants as $tenant)
            @php
                $owner = $tenant->users->first();
                $sub   = $tenant->subscriptions->first();
                $statusConfig = [
                    'trial'     => ['label' => 'Trial',     'dot' => 'bg-amber-400',   'badge' => 'bg-amber-50 text-amber-700'],
                    'active'    => ['label' => 'Aktif',     'dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 text-emerald-700'],
                    'expired'   => ['label' => 'Expired',   'dot' => 'bg-rose-400',    'badge' => 'bg-rose-50 text-rose-700'],
                    'suspended' => ['label' => 'Suspended', 'dot' => 'bg-gray-400',    'badge' => 'bg-gray-100 text-gray-600'],
                ];
                $sc = $statusConfig[$tenant->status] ?? $statusConfig['expired'];
            @endphp
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <a href="{{ route('super-admin.show', $tenant) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors">{{ $tenant->name }}</a>
                        <p class="text-[10px] text-gray-400 font-mono">{{ $tenant->slug }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-bold {{ $sc['badge'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                        {{ $sc['label'] }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-3 text-[11px] text-gray-500 mb-3">
                    <span>👤 {{ $owner?->name ?? 'No owner' }}</span>
                    <span>🏠 {{ $tenant->families_count }} KK</span>
                    @if($sub)
                        <span>📦 {{ $sub->plan?->name ?? '—' }}</span>
                    @else
                        <span class="text-amber-600 font-semibold">📦 Trial</span>
                    @endif
                    <span>📅 {{ $tenant->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('super-admin.show', $tenant) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2 text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Detail
                    </a>
                    <form action="{{ route('super-admin.impersonate', $tenant) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" onclick="return confirm('Masuk sebagai {{ $tenant->name }}?')" class="w-full flex items-center justify-center gap-1.5 py-2 text-[11px] font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                            Login
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="py-12 text-center text-sm text-gray-400">Tidak ada tenant ditemukan.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($tenants->hasPages())
        <div class="px-5 py-4 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">
                Halaman {{ $tenants->currentPage() }} dari {{ $tenants->lastPage() }}
            </p>
            {{ $tenants->links() }}
        </div>
        @endif
    </div>

</div>
</x-super-admin-layout>
