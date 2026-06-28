<x-super-admin-layout title="Detail RT - {{ $tenant->name }}">
<div class="space-y-6" x-data="{ openResetModal: false }">

    {{-- ===== BREADCRUMB & HEADER ===== --}}
    <div>
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <a href="{{ route('super-admin.index') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('super-admin.tenants.index') }}" class="hover:text-indigo-600 transition-colors">Tenant</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 font-semibold truncate">{{ $tenant->name }}</span>
        </div>

        {{-- Header Row --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Avatar Big --}}
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xl font-black shadow-lg flex-shrink-0">
                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl font-black text-gray-900 tracking-tight">{{ $tenant->name }}</h1>
                        @php
                            $statusBadge = [
                                'active'    => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                'trial'     => 'bg-amber-100 text-amber-700 border border-amber-200',
                                'expired'   => 'bg-rose-100 text-rose-700 border border-rose-200',
                                'suspended' => 'bg-gray-100 text-gray-600 border border-gray-200',
                            ][$tenant->status] ?? 'bg-gray-100 text-gray-600 border border-gray-200';
                            $statusDot = [
                                'active'    => 'bg-emerald-500 animate-pulse',
                                'trial'     => 'bg-amber-500',
                                'expired'   => 'bg-rose-500',
                                'suspended' => 'bg-gray-400',
                            ][$tenant->status] ?? 'bg-gray-400';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $statusBadge }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                            {{ strtoupper($tenant->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5 font-mono">ID #{{ $tenant->id }} · {{ $tenant->slug }} · Daftar {{ $tenant->created_at->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('super-admin.impersonate', $tenant) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Masuk sebagai admin {{ $tenant->name }}?')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Login sebagai RT
                    </button>
                </form>

                <form action="{{ route('super-admin.status', $tenant) }}" method="POST">
                    @csrf @method('PATCH')
                    @if($tenant->status === 'suspended')
                        <input type="hidden" name="status" value="active">
                        <button class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Unsuspend
                        </button>
                    @else
                        <input type="hidden" name="status" value="suspended">
                        <button onclick="return confirm('Suspend tenant {{ $tenant->name }}?')" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Suspend
                        </button>
                    @endif
                </form>

                <a href="{{ route('super-admin.edit', $tenant) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-sm shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit & Perpanjang
                </a>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if($isExpired)
    <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700 font-semibold">
        <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        Langganan tenant ini sudah <strong>KEDALUWARSA</strong>. Perpanjang segera agar RT dapat mengakses fitur lengkap.
    </div>
    @endif

    {{-- ===== MAIN GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

        {{-- ===== LEFT / MAIN COLUMN ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @php
                $statCards = [
                    ['label' => 'Total KK',   'value' => $usage['kk'],      'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'bg' => 'bg-indigo-50',  'ico' => 'text-indigo-600'],
                    ['label' => 'Total Warga', 'value' => $usage['warga'],   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'bg' => 'bg-emerald-50', 'ico' => 'text-emerald-600'],
                    ['label' => 'Pengurus',    'value' => $usage['staff'],   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'bg' => 'bg-amber-50',  'ico' => 'text-amber-600'],
                    ['label' => 'AI Ekstraksi','value' => $usage['ai_used'], 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'bg' => 'bg-blue-50',   'ico' => 'text-blue-600'],
                ];
                @endphp
                @foreach($statCards as $card)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
                    <div class="w-10 h-10 {{ $card['bg'] }} rounded-xl flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 {{ $card['ico'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($card['value']) }}</p>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Revenue Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <div class="absolute -right-3 -top-3 w-20 h-20 bg-white/10 rounded-full"></div>
                    <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-2">Total Revenue</p>
                    <p class="text-2xl font-black">Rp{{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-[11px] text-white/60 mt-1">{{ $revenueStats['trx_all_time'] }} transaksi</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Trx Bulan Ini</p>
                    <p class="text-2xl font-black text-gray-900">{{ number_format($revenueStats['trx_this_month']) }}</p>
                    <p class="text-[11px] text-gray-400 mt-1">{{ now()->translatedFormat('M Y') }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Revenue Bulan Ini</p>
                    <p class="text-2xl font-black text-gray-900">Rp{{ number_format($revenueStats['revenue_this_month'], 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-400 mt-1">{{ now()->translatedFormat('M Y') }}</p>
                </div>
            </div>

            {{-- Daftar Pengurus --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <h2 class="text-sm font-black text-gray-900">Daftar Pengurus RT <span class="text-gray-400 font-normal">({{ $staffs->count() }})</span></h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($staffs as $staff)
                    @php
                        $roleBadge = [
                            'owner'       => 'bg-indigo-100 text-indigo-700',
                            'sekretaris'  => 'bg-blue-100 text-blue-700',
                            'bendahara'   => 'bg-emerald-100 text-emerald-700',
                            'keamanan'    => 'bg-rose-100 text-rose-700',
                            'humas'       => 'bg-amber-100 text-amber-700',
                        ][$staff->tenant_role] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <div class="px-6 py-3.5 flex items-center justify-between gap-3 hover:bg-gray-50/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 text-xs font-black flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $staff->name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $staff->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase {{ $roleBadge }}">
                                {{ $staff->tenant_role ?? 'admin' }}
                            </span>
                            <span class="px-2 py-1 rounded-lg text-[10px] font-black bg-emerald-50 text-emerald-600">Aktif</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-400">Belum ada pengurus terdaftar.</div>
                    @endforelse
                </div>
            </div>

            {{-- 5 Transaksi Terakhir --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50">
                    <h2 class="text-sm font-black text-gray-900">5 Transaksi Terakhir</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Kode</th>
                                <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Paket</th>
                                <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Nominal</th>
                                <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentTransactions as $rtx)
                            @php
                                $trxBadge = [
                                    'active'          => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'expired'         => 'bg-gray-100 text-gray-500 border-gray-200',
                                ][$rtx->status] ?? 'bg-gray-100 text-gray-500 border-gray-200';
                                $trxLabel = [
                                    'active'          => 'Aktif',
                                    'pending_payment' => 'Pending',
                                    'expired'         => 'Expired',
                                ][$rtx->status] ?? $rtx->status;
                            @endphp
                            <tr class="hover:bg-gray-50/40 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-[11px] text-gray-500">{{ Str::limit($rtx->payment_external_id ?? 'MNL-'.$rtx->id, 18) }}</td>
                                <td class="px-5 py-3.5 text-xs font-bold text-gray-800">{{ $rtx->plan?->name ?? 'Custom' }}</td>
                                <td class="px-5 py-3.5 text-xs font-semibold text-gray-700">Rp{{ number_format($rtx->amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg border text-[10px] font-bold {{ $trxBadge }}">{{ $trxLabel }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-[11px] text-gray-400">{{ $rtx->created_at->translatedFormat('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ===== RIGHT COLUMN SIDEBAR ===== --}}
        <div class="space-y-4">

            {{-- Paket & Masa Aktif --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="text-sm font-black text-gray-900">Paket Berlangganan</h2>
                </div>
                <div class="p-5 space-y-4">
                    {{-- Plan Name --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Paket Aktif</p>
                            <p class="text-lg font-black text-gray-900">{{ $activeSub ? $activeSub->plan->name : 'Free Trial' }}</p>
                        </div>
                        @if($activeSub)
                        <span class="text-xs font-black text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                            Rp{{ number_format($activeSub->amount, 0, ',', '.') }}
                        </span>
                        @endif
                    </div>

                    {{-- Expiry Info --}}
                    @php
                        $expDate = $activeSub?->current_period_end ?? $tenant->trial_ends_at;
                        $startDate = $activeSub?->current_period_start ?? $tenant->created_at;
                        $daysLeft = $expDate ? now()->diffInDays($expDate, false) : null;
                        $totalDays = ($expDate && $startDate) ? max(1, $startDate->diffInDays($expDate)) : 1;
                        $progress = $daysLeft !== null ? max(0, min(100, round(($daysLeft / $totalDays) * 100))) : 0;
                    @endphp

                    @if($expDate)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Masa Aktif</p>
                            <p class="text-[10px] font-black {{ $daysLeft <= 7 ? 'text-rose-600' : 'text-gray-600' }}">
                                @if($daysLeft >= 0)
                                    {{ $daysLeft }} hari lagi
                                @else
                                    Sudah berakhir
                                @endif
                            </p>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-1">
                            <div class="h-2 rounded-full transition-all {{ $daysLeft <= 7 ? 'bg-rose-400' : ($daysLeft <= 30 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-gray-400">
                            <span>{{ $startDate->translatedFormat('d M Y') }}</span>
                            <span>{{ $expDate->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    @endif

                    <a href="{{ route('super-admin.edit', $tenant) }}"
                       class="w-full flex items-center justify-center gap-2 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Perpanjang / Ubah Paket
                    </a>
                </div>
            </div>

            {{-- Owner / PIC --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="text-sm font-black text-gray-900">Owner / PIC</h2>
                </div>
                <div class="p-5">
                    @if($ownerUser)
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white text-lg font-black flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($ownerUser->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-gray-900 text-sm truncate">{{ $ownerUser->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $ownerUser->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2.5 mb-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs text-gray-500">Role</span>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">Owner</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs text-gray-500">Status Akun</span>
                            <span class="text-xs font-bold text-emerald-600">● Aktif</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs text-gray-500">Daftar</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $ownerUser->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs text-gray-500">Email Verified</span>
                            @if($ownerUser->email_verified_at)
                                <span class="text-xs font-bold text-emerald-600">✓ Terverifikasi</span>
                            @else
                                <span class="text-xs font-bold text-amber-600">Belum</span>
                            @endif
                        </div>
                    </div>
                    <button @click="openResetModal = true"
                        class="w-full flex items-center justify-center gap-2 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-100 rounded-xl transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Reset Password Owner
                    </button>
                    @else
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <p class="text-xs text-gray-400">Belum ada owner terdaftar</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Berlangganan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="text-sm font-black text-gray-900">Riwayat Langganan</h2>
                </div>
                <div class="p-5">
                    @if($histories->count() > 0)
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-3.5 top-2 bottom-2 w-px bg-gray-100"></div>
                        <div class="space-y-4">
                            @foreach($histories as $i => $hist)
                            @php
                                $hBadge = [
                                    'active'          => 'bg-emerald-100 text-emerald-700',
                                    'pending_payment' => 'bg-amber-100 text-amber-700',
                                    'expired'         => 'bg-gray-100 text-gray-500',
                                ][$hist->status] ?? 'bg-gray-100 text-gray-500';
                                $hDot = [
                                    'active'          => 'bg-emerald-500',
                                    'pending_payment' => 'bg-amber-500',
                                    'expired'         => 'bg-gray-300',
                                ][$hist->status] ?? 'bg-gray-300';
                            @endphp
                            <div class="flex items-start gap-3 relative">
                                <div class="w-7 h-7 rounded-full {{ $hDot }} flex items-center justify-center flex-shrink-0 mt-0.5 z-10 border-2 border-white shadow-sm">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-gray-900">{{ $hist->plan?->name ?? 'Custom' }}</p>
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-md {{ $hBadge }}">
                                            {{ strtoupper($hist->status) }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $hist->created_at->translatedFormat('d M Y') }}</p>
                                    @if($hist->amount > 0)
                                    <p class="text-[10px] font-semibold text-gray-600 mt-0.5">Rp{{ number_format($hist->amount, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada riwayat langganan.</p>
                    @endif
                </div>
            </div>

            {{-- Total Revenue Dark Card --}}
            <div class="rounded-2xl p-5 relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a, #1e1b4b);">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full"></div>
                <div class="absolute -right-1 top-4 w-12 h-12 bg-white/5 rounded-full"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Revenue (All Time)</p>
                <p class="text-3xl font-black text-emerald-400">Rp{{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}</p>
                <p class="text-[11px] text-gray-500 mt-2">Dari {{ $revenueStats['trx_all_time'] }} transaksi terbayar</p>
            </div>

        </div>
    </div>

    {{-- ===== MODAL RESET PASSWORD ===== --}}
    <div x-show="openResetModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openResetModal = false"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900">Reset Password Owner</h3>
                    <p class="text-xs text-gray-500">{{ $ownerUser?->email }}</p>
                </div>
                <button @click="openResetModal = false" class="ml-auto text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-reset" action="{{ route('super-admin.reset-password', $tenant) }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Baru</label>
                        <input type="password" name="password" required
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent outline-none transition">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end gap-2">
                    <button type="button" @click="openResetModal = false"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-sm">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-super-admin-layout>
