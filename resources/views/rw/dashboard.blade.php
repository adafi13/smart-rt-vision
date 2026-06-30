<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Dashboard RW</h1>
                <p class="text-sm text-gray-500 mt-0.5">Selamat datang, <span class="font-semibold text-indigo-600">{{ $rw->name }}</span></p>
            </div>
            <a href="{{ route('rw.tenants.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah RT
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        @if(isset($expiredCount) && $expiredCount > 0)
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-rose-900">Perhatian: Ada RT yang Kedaluwarsa</h3>
                <p class="text-xs text-rose-700 mt-1">Terdapat <strong>{{ $expiredCount }} RT</strong> yang masa langganannya telah habis. Segera hubungi pengurus RT terkait untuk memperpanjang langganan agar sistem KakaAi tetap dapat digunakan oleh warga.</p>
            </div>
            <a href="{{ route('rw.tenants.index') }}" class="flex-shrink-0 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-colors">
                Lihat Detail RT
            </a>
        </div>
        @endif

        {{-- ── Overview Stats ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total RT --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-indigo-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total RT</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $rts->count() }}</p>
                </div>
            </div>
            {{-- Total KK --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-blue-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total KK</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ number_format($totalFamilies, 0, ',', '.') }}</p>
                </div>
            </div>
            {{-- Total Warga --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-emerald-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Warga</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ number_format($totalMembers, 0, ',', '.') }} <span class="text-sm font-medium text-gray-400">jiwa</span></p>
                </div>
            </div>
            {{-- Saldo Kas Gabungan --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-3 -top-3 w-20 h-20 bg-amber-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kas Gabungan</p>
                    <p class="text-2xl font-black mt-1 {{ $saldoKas < 0 ? 'text-rose-600' : 'text-gray-900' }}">Rp {{ number_format($saldoKas, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- ── Demografi Gabungan ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Jenis Kelamin</h3>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-sm font-bold text-blue-600">Laki-laki</span>
                            <span class="text-lg font-black text-gray-900">{{ number_format($totalMale) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $totalMembers > 0 ? ($totalMale / $totalMembers) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-sm font-bold text-pink-500">Perempuan</span>
                            <span class="text-lg font-black text-gray-900">{{ number_format($totalFemale) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-pink-500 h-2.5 rounded-full" style="width: {{ $totalMembers > 0 ? ($totalFemale / $totalMembers) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Kelompok Usia</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-black text-indigo-600">{{ number_format($anak) }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Anak (0-12)</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-emerald-600">{{ number_format($remaja) }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Remaja (13-25)</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-amber-600">{{ number_format($dewasa) }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Dewasa (26-59)</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-rose-600">{{ number_format($lansia) }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">Lansia (60+)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Breakdown per RT ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ringkasan per RT</h2>
                <a href="{{ route('rw.tenants.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/70">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama RT</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total KK</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Kas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Langganan</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($rts as $rt)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900">{{ $rt->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">/{{ $rt->slug }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $rt->families_count }} KK</td>
                            <td class="px-6 py-4 text-sm font-bold {{ $rt->saldo < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                Rp {{ number_format($rt->saldo, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $activeSub = $rt->activeSubscription();
                                    $isOnTrial = $rt->onTrial();
                                    $contactStaff = $rt->rtStaffs->whereNotNull('phone')->first();
                                    $waNumber = $contactStaff ? preg_replace('/[^0-9]/', '', $contactStaff->phone) : null;
                                    if ($waNumber && str_starts_with($waNumber, '0')) {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                    $waMessage = urlencode("Halo Pengurus {$rt->name}, kami dari RW ingin mengingatkan bahwa masa langganan sistem KakaAi Anda telah kedaluwarsa. Mohon segera diperpanjang agar layanan warga tetap berjalan lancar. Terima kasih.");
                                @endphp
                                
                                @if($activeSub)
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $activeSub->plan->name ?? 'Aktif' }}
                                        </span>
                                    </div>
                                @elseif($isOnTrial)
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Uji Coba (Trial)
                                        </span>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-[11px] font-bold border border-rose-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Kedaluwarsa
                                        </span>
                                        @if($waNumber)
                                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="text-[10px] font-medium text-rose-500 hover:text-emerald-600 hover:underline cursor-pointer flex items-center gap-1 transition-colors">
                                                Hubungi via WA &rarr;
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('rw.tenants.show', $rt) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-900">Detail →</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <p class="text-sm font-medium text-gray-500">Belum ada RT tergabung</p>
                                <div class="mt-3 flex items-center justify-center gap-3">
                                    <a href="{{ route('rw.tenants.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-900">+ Tambah RT Baru</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('rw.tenants.adopt') }}" class="text-xs font-bold text-amber-600 hover:text-amber-900">↩ Klaim RT yang Sudah Ada</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Info Organisasi RW ── --}}
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-wider mb-1">Organisasi</p>
                    <h3 class="text-xl font-black">{{ $rw->name }}</h3>
                    <p class="text-indigo-200 text-sm mt-1">{{ $rw->address }}, {{ $rw->city }}, {{ $rw->province }}</p>
                </div>
                <a href="{{ route('rw.settings') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-sm font-bold text-white transition-colors self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                    Edit Profil RW
                </a>
            </div>
        </div>

    </div>
</x-rw-app-layout>
