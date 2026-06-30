<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartRT Vision') }} - Super RW</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif, ui-sans-serif; }
        [x-cloak] { display: none !important; }
        body { background: #f8fafc; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-[#f4f7f9]" x-data="{ mobileOpen: false, sidebarExpanded: window.innerWidth >= 768 }" @resize.window="if(window.innerWidth < 768) sidebarExpanded = false; else sidebarExpanded = true;">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        @include('layouts.rw-navigation')

        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-100 z-30 flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <button @click="mobileOpen = !mobileOpen" class="p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden bg-white shadow-sm border border-gray-100">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-sm text-gray-900 truncate">SmartRT Vision</span>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs border border-indigo-200">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 md:hidden"
             @click="mobileOpen = false" x-cloak>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 relative pt-16 md:pt-0"
             :class="{
                 'md:ml-[260px]': sidebarExpanded,
                 'md:ml-[70px]': !sidebarExpanded
             }">
            
            <main class="flex-1 overflow-y-auto">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Impersonation Banner (Full Width) -->
                @if(session()->has('impersonated_by') && session()->has('impersonating_rw'))
                    <div class="w-full bg-amber-500 px-4 sm:px-6 lg:px-8 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm border-b border-black/5 z-10 relative">
                        <div class="flex items-center gap-3">
                            <div class="text-white flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5 text-xs sm:text-sm text-white">
                                <span class="font-bold tracking-wide">MODE IMPERSONASI &mdash; </span>
                                <span class="font-medium">Anda login sebagai RW: <span class="font-bold">{{ session('impersonating_rw') }}</span></span>
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

                <!-- Main Section -->
                <div class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
