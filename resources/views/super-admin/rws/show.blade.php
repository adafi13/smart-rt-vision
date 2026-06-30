<x-super-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('super-admin.rws.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">Manajemen RW</a>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-sm font-medium text-gray-500">Detail RW</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3">
                    {{ $rw->name }}
                </h1>
            </div>
            
            <div class="flex gap-3">
                <form action="{{ route('super-admin.impersonate.rw', $rw) }}" method="POST">
                    @csrf
                    <button type="submit" style="background-color: #ef4444; color: #ffffff;" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl shadow-sm transition-all hover:opacity-90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Login sebagai RW
                    </button>
                </form>
            </div>
        </div>

    <div class="space-y-6">
        
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total RT Terdaftar</p>
                        <p class="text-2xl font-black text-gray-900">{{ $rw->tenants_count }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">RT Aktif</p>
                        <p class="text-2xl font-black text-emerald-700">{{ $activeRtsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">RT Expired / Suspend</p>
                        <p class="text-2xl font-black text-red-700">{{ $expiredRtsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estimasi MRR</p>
                        <p class="text-2xl font-black text-gray-900">Rp {{ number_format($estimatedMrr, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Data RW & Admin -->
            <div class="space-y-6">
                <!-- Info RW -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Profil RW</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama RW</span>
                            <span class="text-sm font-medium text-gray-900">{{ $rw->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Invite Code</span>
                            <span class="inline-flex font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">{{ $rw->invite_code }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Lokasi</span>
                            <span class="text-sm font-medium text-gray-900">{{ $rw->city ?? '-' }}, {{ $rw->province ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal Bergabung</span>
                            <span class="text-sm font-medium text-gray-900">{{ $rw->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Admin RW List -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Daftar Admin RW</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($rw->users as $admin)
                            <div class="p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $admin->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-sm text-gray-500">
                                Belum ada admin untuk RW ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Daftar RT (Tenants) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">Daftar RT di bawah RW ini</h3>
                        <span class="bg-indigo-100 text-indigo-700 py-1 px-3 rounded-full text-xs font-bold">{{ $rw->tenants->count() }} RT</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama RT</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paket Aktif</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($rw->tenants as $rt)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $rt->name }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $rt->slug }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($rt->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                            @elseif($rt->status === 'trial')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Trial</span>
                                            @elseif($rt->status === 'expired')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Expired</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($rt->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($sub = $rt->activeSubscription())
                                                <div class="text-sm font-medium text-gray-900">{{ $sub->plan->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500">Exp: {{ \Carbon\Carbon::parse($sub->current_period_end)->format('d M Y') }}</div>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('super-admin.show', $rt) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Cek Detail RT</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                            RW ini belum memiliki RT terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-super-admin-layout>
