<!-- ===== SIDEBAR ===== -->
<aside class="fixed inset-y-0 left-0 z-40 flex flex-col bg-slate-900 text-slate-300 border-r border-slate-800 transition-all duration-300 w-[280px] -translate-x-full md:translate-x-0 shadow-2xl"
       :class="{
           '!translate-x-0': mobileOpen,
           'md:w-[260px]': sidebarExpanded,
           'md:w-[70px]': !sidebarExpanded
       }">

    <!-- Logo + Toggle -->
    <div class="flex items-center h-16 px-4 border-b border-slate-800 flex-shrink-0 justify-between bg-slate-950">
        <a href="{{ route('rw.dashboard') }}" class="flex items-center gap-3 min-w-0 flex-1 overflow-hidden">
            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden bg-white shadow-sm">
                <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col md:hidden">
                <span class="font-bold text-sm text-white whitespace-nowrap">SmartRT Vision</span>
                <span class="text-[10px] text-slate-400 font-medium">Super RW Panel</span>
            </div>
            <div x-show="sidebarExpanded"
                 x-transition:enter="transition-opacity duration-200 delay-100"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                 class="hidden md:flex flex-col">
                <span class="font-bold text-sm text-white whitespace-nowrap">SmartRT Vision</span>
                <span class="text-[10px] text-slate-400 font-medium -mt-0.5">Super RW Panel</span>
            </div>
        </a>

        <!-- Desktop collapse button -->
        <button @click="sidebarExpanded = !sidebarExpanded"
                class="hidden md:flex p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors flex-shrink-0 ml-1">
            <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarExpanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Mobile close button -->
        <button @click="mobileOpen = false"
                class="md:hidden flex p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto overflow-x-hidden p-3 space-y-1 relative" style="scrollbar-width: thin; scrollbar-color: #334155 transparent;">
        <style>
            .rw-nav-group { padding: 4px 16px 8px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #475569; margin-top: 16px; }
            .rw-nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 8px; font-size: 13.5px; font-weight: 500; transition: all 0.15s; color: #94a3b8; text-decoration: none; margin: 1px 0; }
            .rw-nav-item:hover { background: rgba(255,255,255,0.05); color: #f8fafc; }
            .rw-nav-item.active { background: rgba(99,102,241,0.25); color: #c7d2fe; font-weight: 600; }
        </style>

        <p class="rw-nav-group" :class="!sidebarExpanded ? 'md:hidden' : ''" x-show="sidebarExpanded || mobileOpen">Menu RW</p>

        <a href="{{ route('rw.dashboard') }}" class="rw-nav-item {{ request()->routeIs('rw.dashboard') ? 'active' : '' }}" title="Dashboard" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="md:hidden whitespace-nowrap">Dashboard</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('rw.tenants.index') }}" class="rw-nav-item {{ request()->routeIs('rw.tenants.index') || request()->routeIs('rw.tenants.show') || request()->routeIs('rw.tenants.create') ? 'active' : '' }}" title="Manajemen RT" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span class="md:hidden whitespace-nowrap">Manajemen RT</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Manajemen RT</span>
        </a>

        <a href="{{ route('rw.members.index') }}" class="rw-nav-item {{ request()->routeIs('rw.members.*') ? 'active' : '' }}" title="Direktori Warga" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="md:hidden whitespace-nowrap">Direktori Warga</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Direktori Warga</span>
        </a>

        <a href="{{ route('rw.letters.index') }}" class="rw-nav-item {{ request()->routeIs('rw.letters.*') ? 'active' : '' }}" title="Pengajuan Surat" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="md:hidden whitespace-nowrap">Pengajuan Surat</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Pengajuan Surat</span>
        </a>

        <a href="{{ route('rw.finance.index') }}" class="rw-nav-item {{ request()->routeIs('rw.finance.*') ? 'active' : '' }}" title="Keuangan RW" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="md:hidden whitespace-nowrap">Keuangan RW</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Keuangan RW</span>
        </a>

        <a href="{{ route('rw.agendas.index') }}" class="rw-nav-item {{ request()->routeIs('rw.agendas.*') ? 'active' : '' }}" title="Agenda RW" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="md:hidden whitespace-nowrap">Agenda RW</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Agenda RW</span>
        </a>

        <a href="{{ route('rw.news.index') }}" class="rw-nav-item {{ request()->routeIs('rw.news.*') ? 'active' : '' }}" title="Warta RW" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span class="md:hidden whitespace-nowrap">Warta RW</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Warta RW</span>
        </a>

        <a href="{{ route('rw.inventories.index') }}" class="rw-nav-item {{ request()->routeIs('rw.inventories.*') ? 'active' : '' }}" title="Inventaris RW" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span class="md:hidden whitespace-nowrap">Inventaris RW</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Inventaris RW</span>
        </a>

        <a href="{{ route('rw.tenants.adopt') }}" class="rw-nav-item {{ request()->routeIs('rw.tenants.adopt') ? 'active' : '' }}" title="Klaim RT Lama" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <span class="md:hidden whitespace-nowrap">Klaim RT Lama</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Klaim RT Lama</span>
        </a>

        <a href="{{ route('rw.broadcasts.index') }}" class="rw-nav-item {{ request()->routeIs('rw.broadcasts.*') ? 'active' : '' }}" title="Broadcast" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <span class="md:hidden whitespace-nowrap">Broadcast</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Broadcast</span>
        </a>

        <a href="{{ route('rw.tickets.index') }}" class="rw-nav-item {{ request()->routeIs('rw.tickets.*') ? 'active' : '' }}" title="Tiket Bantuan" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span class="md:hidden whitespace-nowrap">Tiket Bantuan</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Tiket Bantuan</span>
        </a>

        <p class="rw-nav-group" :class="!sidebarExpanded ? 'md:hidden' : ''" x-show="sidebarExpanded || mobileOpen">Pengaturan</p>

        <a href="{{ route('rw.settings') }}" class="rw-nav-item {{ request()->routeIs('rw.settings*') ? 'active' : '' }}" title="Pengaturan RW" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="1.75"/></svg>
            <span class="md:hidden whitespace-nowrap">Pengaturan RW</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Pengaturan RW</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="rw-nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" title="Profil Akun" @click="mobileOpen = false">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="md:hidden whitespace-nowrap">Profil Akun</span>
            <span x-show="sidebarExpanded" class="hidden md:block whitespace-nowrap">Profil Akun</span>
        </a>
    </nav>

    <!-- User footer -->
    <div class="flex-shrink-0 p-4 border-t border-slate-800 bg-slate-950">
        <div class="flex items-center gap-3 relative group">
            <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center text-white font-black shadow-sm"
                 style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <!-- Always visible on mobile, conditional on desktop -->
            <div class="flex-1 min-w-0 md:hidden">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-medium truncate uppercase tracking-wider">Ketua RW</p>
            </div>
            <div x-show="sidebarExpanded"
                 x-transition:enter="transition-opacity duration-200 delay-100"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                 class="flex-1 min-w-0 hidden md:block">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-medium truncate uppercase tracking-wider">Ketua RW</p>
            </div>
            <!-- Logout button - visible on mobile and desktop expanded -->
            <form method="POST" action="{{ route('logout') }}" class="md:hidden flex-shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-all border border-transparent hover:border-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}" x-show="sidebarExpanded" class="hidden md:block flex-shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-all border border-transparent hover:border-slate-700" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
        <!-- Logout when desktop collapsed -->
        <div x-show="!sidebarExpanded" class="hidden md:flex justify-center mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="p-2 rounded-xl text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-all border border-transparent hover:border-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
