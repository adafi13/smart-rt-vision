<!-- ===== SIDEBAR ===== -->
<aside class="fixed inset-y-0 left-0 z-40 flex flex-col bg-white border-r border-gray-200 transition-all duration-300 w-[280px] -translate-x-full md:translate-x-0"
       :class="{
           '!translate-x-0': mobileOpen,
           'md:w-[260px]': sidebarExpanded,
           'md:w-[70px]': !sidebarExpanded
       }">

    <!-- Logo + Toggle -->
    <div class="flex items-center h-16 px-4 border-b border-gray-100 flex-shrink-0 justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0 flex-1 overflow-hidden">
            <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center overflow-hidden bg-white shadow-sm border border-gray-100">
                <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
            </div>
            <!-- Show label: always on mobile, conditional on desktop -->
            <span class="font-bold text-sm text-gray-900 whitespace-nowrap md:hidden">SmartRT Vision</span>
            <span x-show="sidebarExpanded"
                  x-transition:enter="transition-opacity duration-200 delay-100"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="font-bold text-sm text-gray-900 whitespace-nowrap hidden md:block">SmartRT Vision</span>
        </a>

        <!-- Desktop collapse button -->
        <button @click="sidebarExpanded = !sidebarExpanded"
                class="hidden md:flex p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors flex-shrink-0 ml-1">
            <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarExpanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Mobile close button -->
        <button @click="mobileOpen = false"
                class="md:hidden flex p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden p-3 space-y-1 relative" style="scrollbar-width: thin;">
        @php
            $user = auth()->user();
            $role = $user->tenant_role;
            $isOwner = empty($role) || $role === 'owner' || $role === 'wakil_ketua';
            $isSekretaris = $role === 'sekretaris';
            $isBendahara = $role === 'bendahara';
            $isKeamanan = $role === 'keamanan';
            $isHumas = $role === 'humas';
            $isPembangunan = $role === 'pembangunan';

            $menuGroups = [
                [
                    'label' => 'Utama',
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    'items' => [
                        ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard'],
                    ],
                ],
                [
                    'label' => 'Kependudukan',
                    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    'items' => array_filter([
                        ($isOwner || $isSekretaris) ? ['route' => 'kk.index', 'match' => 'kk.*', 'label' => 'Data KK'] : null,
                        ($isOwner || $isSekretaris) ? ['route' => 'warga.index', 'match' => 'warga.*', 'label' => 'Data Warga'] : null,
                        ($isOwner || $isSekretaris) ? ['route' => 'warga-kos.index', 'match' => 'warga-kos.*', 'label' => 'Warga Kos/Kontrakan'] : null,
                        ($isOwner || $isSekretaris) ? ['route' => 'admin.riwayat-scan.index', 'match' => 'admin.riwayat-scan.*', 'label' => 'Riwayat Scan AI'] : null,
                    ]),
                ],
                [
                    'label' => 'Keuangan Kas',
                    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 8v2m9-4a9 9 0 11-18 0 9 9 0 0118 0z',
                    'items' => array_filter([
                        ($isOwner || $isBendahara) ? ['route' => 'admin.iuran.index', 'match' => 'admin.iuran.*', 'label' => 'Iuran Warga'] : null,
                        ($isOwner || $isBendahara) ? ['route' => 'admin.tunggakan.index', 'match' => 'admin.tunggakan.*', 'label' => 'Rekap Tunggakan'] : null,
                        ($isOwner || $isBendahara) ? ['route' => 'admin.pengeluaran.index', 'match' => 'admin.pengeluaran.*', 'label' => 'Pengeluaran Kas'] : null,
                    ]),
                ],
                [
                    'label' => 'Layanan & Surat',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'items' => array_filter([
                        ($isOwner || $isSekretaris) ? ['route' => 'admin.pengajuan.index', 'match' => 'admin.pengajuan.*', 'label' => 'Pengajuan Surat'] : null,
                        ($isOwner || $isSekretaris) ? ['route' => 'admin.documents.index', 'match' => 'admin.documents.*', 'label' => 'Brankas Digital'] : null,
                        ($isOwner || $isSekretaris || $isKeamanan || $isPembangunan) ? ['route' => 'admin.laporan.index', 'match' => 'admin.laporan.*', 'label' => 'Laporan Warga'] : null,
                        ($isOwner || $isSekretaris || $isKeamanan) ? ['route' => 'admin.panic.index', 'match' => 'admin.panic.*', 'label' => 'Riwayat Darurat (Panic)'] : null,
                        // ($isOwner || $isKeamanan) ? ['route' => 'admin.cctvs.index', 'match' => 'admin.cctvs.*', 'label' => 'CCTV Lingkungan'] : null,
                        ($isOwner || $isSekretaris || $isKeamanan) ? ['route' => 'admin.ronda.index', 'match' => 'admin.ronda.*', 'label' => 'Jadwal Ronda'] : null,
                        ($isOwner || $isSekretaris || $isBendahara || $isPembangunan) ? ['route' => 'admin.inventaris.index', 'match' => 'admin.inventaris.*', 'label' => 'Inventaris & Aset'] : null,
                        ($isOwner || $isSekretaris || $isHumas) ? ['route' => 'admin.polls.index', 'match' => 'admin.polls.*', 'label' => 'Musyawarah (E-Voting)'] : null,
                        ($isOwner || $isSekretaris || $isHumas) ? ['route' => 'admin.guestbooks.index', 'match' => 'admin.guestbooks.*', 'label' => 'Buku Tamu (Security)'] : null,
                        ($isOwner || $isSekretaris || $isKeamanan) ? ['route' => 'admin.vacant-homes.index', 'match' => 'admin.vacant-homes.*', 'label' => 'Penjagaan Rumah Kosong'] : null,
                    ]),
                ],
                [
                    'label' => 'Informasi & UMKM',
                    'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 11h6v-3H7v3z',
                    'items' => array_filter([
                        ($isOwner || $isSekretaris || $isHumas) ? ['route' => 'admin.agendas.index', 'match' => 'admin.agendas.*', 'label' => 'Kalender Agenda'] : null,
                        ($isOwner || $isSekretaris || $isHumas) ? ['route' => 'admin.berita.index', 'match' => 'admin.berita.*', 'label' => 'Berita & Pengumuman'] : null,
                        ($isOwner || $isSekretaris || $isHumas) ? ['route' => 'admin.peristiwa.index', 'match' => 'admin.peristiwa.*', 'label' => 'Lapor Peristiwa'] : null,
                        ($isOwner || $isSekretaris || $isHumas) ? ['route' => 'admin.umkm.index', 'match' => 'admin.umkm.*', 'label' => 'Pasar Warga (UMKM)'] : null,
                    ]),
                ],
                [
                    'label' => 'Laporan & Ekspor',
                    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'items' => [
                        ['route' => 'export.excel', 'match' => 'export.excel', 'label' => 'Export Excel'],
                        ['route' => 'export.pdf', 'match' => 'export.pdf', 'label' => 'Export PDF'],
                    ],
                ],
                [
                    'label' => 'Pengaturan',
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                    'items' => array_filter([
                        $isOwner ? ['route' => 'admin.settings.index', 'match' => 'admin.settings.*', 'label' => 'Pengaturan Profil RT'] : null,
                        ($isOwner || $isSekretaris) ? ['route' => 'admin.organisasi.index', 'match' => 'admin.organisasi.*', 'label' => 'Struktur Organisasi'] : null,
                        $isOwner ? ['route' => 'admin.staff.index', 'match' => 'admin.staff.*', 'label' => 'Manajemen Staff (Akun)'] : null,
                        $isOwner ? ['route' => 'admin.logs.index', 'match' => 'admin.logs.*', 'label' => 'Log Aktivitas'] : null,
                        ($isOwner || $isBendahara) ? ['route' => 'billing.index', 'match' => 'billing.*', 'label' => 'Paket & Tagihan'] : null,
                        $isOwner ? ['route' => 'admin.tickets.index', 'match' => 'admin.tickets.*', 'label' => 'Helpdesk / Bantuan'] : null,
                    ]),
                ],
            ];
        @endphp

        <!-- Group Accordions -->
        @foreach($menuGroups as $group)
            @if(empty($group['items']))
                @continue
            @endif

            @php 
                $hasActiveChild = false;
                foreach($group['items'] as $item) {
                    if(request()->routeIs($item['match'])) {
                        $hasActiveChild = true;
                        break;
                    }
                }
                // If the group only has one item, don't use accordion, just render it directly
                $isSingleItem = count($group['items']) === 1;
            @endphp

            @if($isSingleItem)
                @php 
                    $item = $group['items'][array_key_first($group['items'])];
                    $active = request()->routeIs($item['match']);
                @endphp
                <a href="{{ route($item['route']) }}" @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group relative mb-1 {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                   title="{{ $item['label'] }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $active ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $group['icon'] }}"/>
                    </svg>
                    <span class="md:hidden whitespace-nowrap">{{ $item['label'] }}</span>
                    <span x-show="sidebarExpanded"
                          x-transition:enter="transition-opacity duration-200 delay-100"
                          x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                          x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                          class="hidden md:block whitespace-nowrap">{{ $item['label'] }}</span>
                </a>
            @else
                <div x-data="{ groupOpen: {{ $hasActiveChild ? 'true' : 'false' }} }" class="mb-1">
                    <button @click="groupOpen = !groupOpen; if(!sidebarExpanded) sidebarExpanded = true;"
                            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ $hasActiveChild ? 'bg-gray-50 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                            title="{{ $group['label'] }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 {{ $hasActiveChild ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $group['icon'] }}"/>
                            </svg>
                            <span class="md:hidden whitespace-nowrap">{{ $group['label'] }}</span>
                            <span x-show="sidebarExpanded"
                                  x-transition:enter="transition-opacity duration-200 delay-100"
                                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                                  class="hidden md:block whitespace-nowrap">{{ $group['label'] }}</span>
                        </div>
                        <svg x-show="sidebarExpanded || mobileOpen"
                             class="w-4 h-4 text-gray-400 transition-transform duration-300"
                             :class="groupOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Items -->
                    <div x-show="groupOpen && (sidebarExpanded || mobileOpen)"
                         x-transition:enter="transition-all ease-out duration-200"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-screen"
                         x-transition:leave="transition-all ease-in duration-150"
                         x-transition:leave-start="opacity-100 max-h-screen"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="overflow-hidden ml-[22px] mt-1 space-y-1 border-l-2 border-gray-100 pl-3">
                        @foreach($group['items'] as $item)
                            @php $active = request()->routeIs($item['match']); @endphp
                            <a href="{{ route($item['route']) }}" @click="mobileOpen = false"
                               class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div class="my-4 border-t border-gray-100"></div>

        <!-- Call to Actions -->
        @if(auth()->user()->tenant_id)
            @if(!auth()->user()->isRtBendahara())
            <div class="px-2 mb-2">
                <a href="{{ route('kk.upload') }}" @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm"
                   style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;"
                   title="Upload KK Baru">
                    <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="md:hidden whitespace-nowrap">Upload KK Baru</span>
                    <span x-show="sidebarExpanded"
                          x-transition:enter="transition-opacity duration-200 delay-100"
                          x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                          x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                          class="hidden md:block whitespace-nowrap">Upload KK Baru</span>
                </a>
            </div>
            @endif

            <div class="px-2 pb-4">
                <a href="{{ route('home', ['tenant' => auth()->user()->tenant->slug ?? '']) }}" target="_blank" @click="mobileOpen = false"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100"
                   title="Portal Warga (Web)">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span class="md:hidden whitespace-nowrap">Portal Warga (Web)</span>
                    <span x-show="sidebarExpanded"
                          x-transition:enter="transition-opacity duration-200 delay-100"
                          x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                          x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                          class="hidden md:block whitespace-nowrap">Portal Warga (Web)</span>
                </a>
            </div>
        @endif
    </nav>

    <!-- User footer -->
    <div class="flex-shrink-0 p-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex items-center gap-3 relative group">
            <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center text-white font-black shadow-sm"
                 style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <!-- Always visible on mobile, conditional on desktop -->
            <div class="flex-1 min-w-0 md:hidden">
                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->tenant->name ?? 'SmartRT Vision' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->name }}</p>
            </div>
            <div x-show="sidebarExpanded"
                 x-transition:enter="transition-opacity duration-200 delay-100"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                 class="flex-1 min-w-0 hidden md:block">
                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->tenant->name ?? 'SmartRT Vision' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->name }}</p>
            </div>
            <!-- Logout button - visible on mobile and desktop expanded -->
            <form method="POST" action="{{ route('logout') }}" class="md:hidden flex-shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}" x-show="sidebarExpanded" class="hidden md:block flex-shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
        <!-- Logout when desktop collapsed -->
        <div x-show="!sidebarExpanded" class="hidden md:flex justify-center mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="p-2 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
