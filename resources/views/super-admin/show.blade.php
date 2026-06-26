<x-super-admin-layout title="Detail RT - {{ $tenant->name }}">
    <div class="space-y-6" x-data="{ openEditModal: false, openResetModal: false }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('super-admin.index') }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors bg-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-xl font-black text-gray-900 uppercase tracking-wide flex items-center gap-2">
                        {{ $tenant->name }}
                    </h1>
                    <p class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-semibold">ID #{{ $tenant->id }} &bull; {{ $tenant->slug }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('super-admin.impersonate', $tenant) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-[11px] font-bold text-emerald-500 bg-transparent border border-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors flex items-center gap-2 uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        LOGIN SEBAGAI RT
                    </button>
                </form>
                <form action="{{ route('super-admin.status', $tenant) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    @if($tenant->status === 'suspended')
                        <input type="hidden" name="status" value="active">
                        <button class="px-4 py-2 text-[11px] font-bold text-rose-500 bg-transparent border border-rose-500 hover:bg-rose-50 rounded-lg transition-colors flex items-center gap-2 uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            UNSUSPEND
                        </button>
                    @else
                        <input type="hidden" name="status" value="suspended">
                        <button class="px-4 py-2 text-[11px] font-bold text-rose-500 bg-transparent border border-rose-500 hover:bg-rose-50 rounded-lg transition-colors flex items-center gap-2 uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            SUSPEND
                        </button>
                    @endif
                </form>
                <a href="{{ route('super-admin.edit', $tenant) }}" class="px-5 py-2 text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-sm flex items-center gap-2 uppercase tracking-wide">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    EDIT RT
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 py-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <!-- LEFT COLUMN (Main Content) -->
            <div class="md:col-span-2 space-y-5">
                <!-- Alert Warning -->
                @if($isExpired)
                <div class="flex items-center gap-3 px-5 py-4 rounded-xl bg-amber-50 border border-amber-100 text-amber-700 text-[13px] font-semibold">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Langganan tenant ini sudah KEDALUWARSA.
                </div>
                @endif

                <!-- 4 Top Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-gray-900 leading-none mb-1">{{ number_format($usage['kk']) }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL KK</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-gray-900 leading-none mb-1">{{ number_format($usage['warga']) }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TOTAL WARGA</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-gray-900 leading-none mb-1">{{ number_format($usage['staff']) }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">PENGURUS</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-gray-900 leading-none mb-1">{{ number_format($usage['ai_used']) }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">AI EKSTRAKSI</p>
                        </div>
                    </div>
                </div>

                <!-- 3 Revenue Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="rounded-2xl p-5 text-white shadow-sm flex flex-col justify-between h-32 bg-emerald-500">
                        <p class="text-[10px] font-bold text-white/90 uppercase tracking-wider">TOTAL TRANSAKSI</p>
                        <div>
                            <p class="text-4xl font-black text-white leading-none mb-1">{{ number_format($revenueStats['trx_all_time']) }}</p>
                            <p class="text-[11px] text-white/80">Sepanjang waktu</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">TRX BULAN INI</p>
                        <div>
                            <p class="text-4xl font-black text-gray-900 leading-none mb-1">{{ number_format($revenueStats['trx_this_month']) }}</p>
                            <p class="text-[11px] text-gray-400">{{ now()->translatedFormat('M Y') }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-32">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">REVENUE BULAN INI</p>
                        <div>
                            <p class="text-3xl font-black text-gray-900 truncate leading-none mb-1">Rp{{ number_format($revenueStats['revenue_this_month'], 0, ',', '.') }}</p>
                            <p class="text-[11px] text-gray-400">{{ now()->translatedFormat('M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Pengurus -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5">
                        <h2 class="text-[12px] font-bold text-gray-500 uppercase tracking-wider">DAFTAR PENGURUS ({{ $staffs->count() }})</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-t border-gray-100">
                            <thead>
                                <tr class="bg-white text-gray-400 font-semibold text-[10px] uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b border-gray-50">NAMA</th>
                                    <th class="px-6 py-4 border-b border-gray-50">TELEPON</th>
                                    <th class="px-6 py-4 border-b border-gray-50">STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @forelse($staffs as $staff)
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-50/50">
                                        <p class="font-bold text-gray-900 text-xs">{{ $staff->name }}</p>
                                        @if($staff->tenant_role === 'owner')
                                        <p class="text-[10px] text-gray-400 uppercase mt-0.5">OWNER</p>
                                        @else
                                        <p class="text-[10px] text-gray-400 uppercase mt-0.5">{{ $staff->tenant_role ?? 'ADMIN' }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-50/50 text-xs">{{ $staff->phone ?? '-' }}</td>
                                    <td class="px-6 py-4 border-b border-gray-50/50">
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-500 uppercase">AKTIF</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400 text-xs">Belum ada pengurus</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 5 Transaksi Terakhir -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5">
                        <h2 class="text-[12px] font-bold text-gray-500 uppercase tracking-wider">5 TRANSAKSI TERAKHIR</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-t border-gray-100">
                            <thead>
                                <tr class="bg-white text-gray-400 font-semibold text-[10px] uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b border-gray-50">KODE</th>
                                    <th class="px-6 py-4 border-b border-gray-50">PAKET</th>
                                    <th class="px-6 py-4 border-b border-gray-50">TOTAL</th>
                                    <th class="px-6 py-4 border-b border-gray-50">STATUS</th>
                                    <th class="px-6 py-4 border-b border-gray-50">WAKTU</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @forelse($recentTransactions as $rtx)
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-50/50 font-mono text-xs text-gray-500">{{ $rtx->payment_external_id ?? 'MANUAL-'.$rtx->id }}</td>
                                    <td class="px-6 py-4 border-b border-gray-50/50 font-bold text-gray-900 text-xs">{{ $rtx->plan?->name ?? 'Custom' }}</td>
                                    <td class="px-6 py-4 border-b border-gray-50/50 text-gray-900 text-xs">Rp{{ number_format($rtx->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 border-b border-gray-50/50">
                                        @if($rtx->status === 'active')
                                            <span class="text-[9px] font-bold text-emerald-500 uppercase">AKTIF</span>
                                        @elseif($rtx->status === 'pending_payment')
                                            <span class="text-[9px] font-bold text-amber-500 uppercase">PENDING</span>
                                        @else
                                            <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $rtx->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-50/50 text-xs text-gray-500">{{ $rtx->created_at->translatedFormat('d M Y, H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-xs">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN (Sidebar Panel) -->
            <div class="md:col-span-1 space-y-5">
                <!-- Paket Berlangganan -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-5">PAKET BERLANGGANAN</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">PAKET AKTIF</p>
                            <p class="text-base font-black text-gray-900 uppercase">{{ $activeSub ? $activeSub->plan->name : 'FREE Trial' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1.5">STATUS</p>
                            @if($isExpired)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase">EXPIRED</span>
                            @elseif($tenant->status === 'trial')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase">TRIAL</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-500 border border-emerald-200 uppercase">AKTIF</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">BERAKHIR</p>
                            @if($activeSub)
                                <p class="text-sm font-bold text-rose-500">{{ $activeSub->current_period_end->translatedFormat('d M Y') }}</p>
                            @elseif($tenant->status === 'trial')
                                <p class="text-sm font-bold text-rose-500">{{ $tenant->trial_ends_at->translatedFormat('d M Y') }}</p>
                            @else
                                <p class="text-sm font-bold text-gray-900">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">SUBSCRIPTION PLAN</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $activeSub ? $activeSub->plan->name : 'Trial' }}</p>
                        </div>
                        
                        <a href="{{ route('super-admin.edit', $tenant) }}" class="w-full mt-2 py-2.5 rounded-lg text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            EDIT / PERPANJANG
                        </a>
                    </div>
                </div>

                <!-- Owner / PIC -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-5">OWNER / PIC</h2>
                    @if($ownerUser)
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center text-lg font-black flex-shrink-0">
                                {{ strtoupper(substr($ownerUser->name, 0, 1)) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-bold text-gray-900 text-sm truncate">{{ $ownerUser->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $ownerUser->email }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center pb-1">
                                <span class="text-xs text-gray-400">Role</span>
                                <span class="text-xs font-semibold text-gray-900">Owner</span>
                            </div>
                            <div class="flex justify-between items-center pb-1">
                                <span class="text-xs text-gray-400">Status</span>
                                <span class="text-xs font-bold text-emerald-500">Aktif</span>
                            </div>
                            <div class="flex justify-between items-center pb-1">
                                <span class="text-xs text-gray-400">Daftar</span>
                                <span class="text-xs font-semibold text-gray-900">{{ $ownerUser->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400">Email Verified</span>
                                <span class="text-xs font-bold text-emerald-500">Ya</span>
                            </div>
                        </div>

                        <button @click="openResetModal = true" class="w-full py-2 rounded-lg text-[11px] font-bold text-rose-500 bg-transparent border border-rose-100 hover:bg-rose-50 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            RESET PASSWORD OWNER
                        </button>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada akun Owner terdaftar.</p>
                    @endif
                </div>

                <!-- Riwayat Berlangganan -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-5">RIWAYAT BERLANGGANAN</h2>
                    @if($histories->count() > 0)
                        <div class="space-y-4">
                            @foreach($histories as $hist)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $hist->plan?->name ?? 'Custom' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $hist->created_at->translatedFormat('d M Y') }}</p>
                                </div>
                                @if($hist->status === 'active')
                                    <span class="text-[9px] font-bold text-emerald-500 uppercase">AKTIF</span>
                                @elseif($hist->status === 'pending_payment')
                                    <span class="text-[9px] font-bold text-amber-500 uppercase">PENDING</span>
                                @else
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $hist->status }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-400 text-center py-6">Belum ada riwayat</p>
                    @endif
                </div>

                <!-- Total Revenue Dark Card -->
                <div class="rounded-2xl p-6 bg-slate-900 text-white shadow-md relative overflow-hidden h-32 flex flex-col justify-between">
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">TOTAL REVENUE (ALL TIME)</p>
                        <p class="text-3xl font-black text-emerald-400 leading-none">Rp{{ number_format($revenueStats['total_revenue'], 0, ',', '.') }}</p>
                        <p class="text-[11px] text-gray-500 mt-2">Dari {{ $revenueStats['trx_all_time'] }} transaksi</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL: RESET PASSWORD -->
        <div x-show="openResetModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openResetModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true" @click="openResetModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="openResetModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-bold text-gray-900" id="modal-title">Reset Password Owner</h3>
                                <p class="text-sm text-gray-500 mt-1">Masukkan password baru untuk akun <strong>{{ $ownerUser?->email }}</strong>.</p>
                                <div class="mt-5 space-y-4">
                                    <form id="form-reset" action="{{ route('super-admin.reset-password', $tenant) }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                                                <input type="password" name="password" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-rose-500 focus:ring-rose-500 sm:text-sm text-gray-700">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                                                <input type="password" name="password_confirmation" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-rose-500 focus:ring-rose-500 sm:text-sm text-gray-700">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" @click="openResetModal = false" class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" form="form-reset" class="px-5 py-2.5 rounded-xl bg-rose-600 text-sm font-bold text-white hover:bg-rose-700 transition-colors">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-super-admin-layout>
