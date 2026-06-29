<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Super Admin' }} · {{ config('app.name', 'SmartRT Vision Enterprise') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif, ui-sans-serif; box-sizing: border-box; }
        body { background: #f4f7f9; }
        .input-field { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; background: white; color: #0f172a; transition: all 0.2s; }
        .input-field:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .btn-primary { background: #4f46e5; color: white; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: all 0.2s; }
        .btn-primary:hover { background: #4338ca; box-shadow: 0 4px 12px rgba(79,70,229,0.25); }

        /* ApoApps Dark Sidebar */
        .sa-nav-group { padding: 4px 16px 8px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #475569; margin-top: 16px; }
        .sa-nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 16px; border-radius: 8px; font-size: 13.5px; font-weight: 500; transition: all 0.15s; color: #94a3b8; text-decoration: none; margin: 1px 0; }
        .sa-nav-item:hover { background: rgba(255,255,255,0.06); color: #f1f5f9; }
        .sa-nav-item.active { background: rgba(99,102,241,0.25); color: #c7d2fe; font-weight: 600; }
        .sa-nav-item svg { flex-shrink: 0; }
    </style>
</head>
<body class="text-gray-900 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Overlay -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/80 z-40 md:hidden"
             @click="sidebarOpen = false"></div>

        <!-- ApoApps Premium Dark Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-60 bg-slate-900 text-slate-300 shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex md:flex-col flex-shrink-0">

            <!-- Logo -->
            <div class="h-16 flex items-center gap-3 px-5 border-b border-white/5 flex-shrink-0 bg-slate-950">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden bg-white shadow-lg">
                    <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="block text-sm font-black text-white">SmartRT Vision</span>
                    <span class="block text-[10px] text-slate-400 -mt-0.5">Super Admin Panel</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-3 overflow-y-auto">

                <!-- SAAS ADMIN PANEL -->
                <p class="sa-nav-group">SaaS Admin Panel</p>
                <a href="{{ route('super-admin.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.index') ? 'active' : '' }}">
                    <svg class="w-4.5 h-4.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Platform Overview
                </a>
                
                <a href="{{ route('super-admin.tenants.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.tenants.index') || request()->routeIs('super-admin.show') || request()->routeIs('super-admin.edit') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Tenant (RT)
                </a>

                @if(!Auth::user()->isSuperAdminSupport())
                <a href="{{ route('super-admin.plans.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.plans.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Paket (Tiering)
                </a>
                <a href="{{ route('super-admin.transactions.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.transactions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Billing / Tagihan
                </a>
                @endif

                <!-- FINANCE -->
                @if(!Auth::user()->isSuperAdminSupport())
                <p class="sa-nav-group">Finance</p>
                <a href="{{ route('super-admin.finance.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.finance.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Laporan Keuangan
                </a>
                @endif

                <!-- SYSTEM -->
                <p class="sa-nav-group">System</p>
                <a href="{{ route('super-admin.announcements.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.announcements.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Pengumuman
                </a>
                @if(!Auth::user()->isSuperAdminFinance())
                <a href="{{ route('super-admin.audit-logs.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.audit-logs.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Audit Log
                </a>
                @endif
                @if(Auth::user()->isSuperAdminOwner())
                <a href="{{ route('super-admin.announcements.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.announcements.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Pengumuman Global
                </a>
                <a href="{{ route('super-admin.staff.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.staff.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    User Management
                </a>
                @endif

                <!-- YOUR PROFILE -->
                <p class="sa-nav-group">Your Profile</p>
                <a href="{{ route('super-admin.security.index') }}" class="sa-nav-item {{ request()->routeIs('super-admin.security.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Keamanan Akun
                </a>

            </nav>

            <!-- User Footer -->
            <div class="p-4 border-t border-white/5 bg-slate-950">
                <div class="flex items-center gap-3 px-2 py-2 mb-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-sm border border-white/10">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 truncate capitalize">{{ Auth::user()->su_role ?? 'owner' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 hover:text-rose-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden bg-slate-50/50">
            <!-- Mobile Topbar -->
            <header class="md:hidden sticky top-0 z-30 bg-slate-900 border-b border-white/10 shadow-md h-16 flex items-center justify-between px-4">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="text-slate-300 hover:text-white p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="text-sm font-bold text-white">SmartRT Vision Super</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-1.5 rounded-md">Logout</button>
                </form>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

