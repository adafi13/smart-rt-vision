<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SmartRT Vision - Sistem Pendataan Warga RT berbasis AI">
    <title>{{ isset($title) ? $title . ' · ' : '' }}{{ config('app.name', 'SmartRT Vision') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background-color: #f5f5f5; color: #1a1a2e; -webkit-tap-highlight-color: transparent; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }

        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; font-size: 14px; font-weight: 500; color: #6b7280; transition: all 0.15s ease; text-decoration: none; white-space: nowrap; }
        .sidebar-link:hover { background: #f3f4f6; color: #111827; }
        .sidebar-link.active { background: #eef2ff; color: #4f46e5; font-weight: 600; }
        .sidebar-link.active svg { color: #4f46e5; }

        .btn-primary { background: #4f46e5; color: white; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
        .btn-primary:hover { background: #4338ca; box-shadow: 0 4px 12px rgba(79,70,229,0.25); }

        .input-field { width: 100%; padding: 9px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 14px; outline: none; background: white; color: #111827; transition: all 0.15s; }
        .input-field:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .input-field::placeholder { color: #9ca3af; }
        .label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }

        /* Mobile safe area */
        @supports (padding: max(0px)) {
            .mobile-safe-bottom { padding-bottom: max(16px, env(safe-area-inset-bottom)); }
        }
    </style>
</head>
<body x-data="{
    sidebarExpanded: true,
    mobileOpen: false,
    pwaPrompt: null,
    pwaInstallable: false,
    installPwa() {
        if(this.pwaPrompt) {
            this.pwaPrompt.prompt();
            this.pwaPrompt.userChoice.then((choice) => {
                if(choice.outcome === 'accepted') this.pwaInstallable = false;
            });
        }
    }
}" @beforeinstallprompt.window="$event.preventDefault(); pwaPrompt = $event; pwaInstallable = true">

    <!-- Global Announcements (Root Level, guarantees top z-index) -->
    <x-global-announcement />

    <!-- Mobile Backdrop -->
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 md:hidden"
         style="background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">
    </div>



    <div class="flex min-h-screen">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main content -->
        <div class="flex flex-col flex-1 min-w-0 transition-all duration-300 w-full md:w-auto"
             :class="sidebarExpanded ? 'md:ml-[260px]' : 'md:ml-[70px]'">

            <!-- Topbar -->
            <header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 sm:px-6 min-h-[56px] py-3 flex items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <!-- Mobile hamburger -->
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    @isset($header)
                        <div class="flex-1 min-w-0">{{ $header }}</div>
                    @endisset
                </div>

                <!-- PWA Install Button -->
                <button x-show="pwaInstallable" style="display: none;" @click="installPwa()" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-2 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span class="hidden sm:inline">Install Aplikasi</span>
                </button>
            </header>

            <!-- Impersonation Banner (Full Width) -->
            @if(session()->has('impersonated_by'))
                <div class="w-full bg-amber-500 px-4 sm:px-6 lg:px-8 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm border-b border-black/5 z-10 relative">
                    <div class="flex items-center gap-3">
                        <div class="text-white flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div class="flex flex-wrap items-center gap-1.5 text-xs sm:text-sm text-white">
                            <span class="font-bold tracking-wide">MODE IMPERSONASI &mdash; </span>
                            <span class="font-medium">Anda login sebagai <span class="font-bold">{{ session('impersonating_tenant') }}</span></span>
                        </div>
                    </div>
                    
                    <form action="{{ route('super-admin.leave-impersonation') }}" method="POST" class="inline flex-shrink-0">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-amber-700 bg-white hover:bg-amber-50 rounded-md transition-colors shadow-sm whitespace-nowrap">
                            Kembali ke Superadmin &rarr;
                        </button>
                    </form>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6" style="background:#f5f5f5;">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('invalid', function(e) {
                    if (this.validity.valueMissing) {
                        this.setCustomValidity('Mohon isi kolom ini.');
                    } else if (this.validity.typeMismatch && this.type === 'email') {
                        this.setCustomValidity('Format email tidak valid (harus mengandung @).');
                    } else {
                        this.setCustomValidity('Isian tidak valid.');
                    }
                });
                input.addEventListener('input', function(e) {
                    this.setCustomValidity('');
                });
            });
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>

    <!-- Customer Service Button -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end group" style="animation: float 3s ease-in-out infinite;">
        <style>
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
                100% { transform: translateY(0px); }
            }
        </style>
        <div class="mb-3 px-3 py-1.5 bg-gray-900/90 backdrop-blur-sm text-white text-xs font-bold rounded-xl shadow-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none whitespace-nowrap border border-gray-700">
            Hubungi CS 🎧
        </div>
        <a href="https://wa.me/6285156412702?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20penggunaan%20sistem%20SmartRT%20Vision." target="_blank" class="w-16 h-16 rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:shadow-[0_0_30px_rgba(79,70,229,0.6)] hover:scale-110 transition-all duration-300 border-2 border-indigo-500 overflow-hidden bg-white">
            <img src="{{ asset('images/cs_profile.jpg') }}" alt="Customer Service" class="w-full h-full object-cover">
        </a>
    </div>
</body>
</html>
