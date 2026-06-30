<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-pub-key" content="{{ config('webpush.vapid.public_key') }}">
    <meta name="description" content="SmartRT Vision — Dashboard internal warga dan pengurus RT. Sistem Pendataan Warga RT berbasis AI.">
    <meta name="robots" content="noindex, nofollow"> <!-- Prevent indexing of private dashboard pages -->

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ isset($title) ? $title . ' · ' : '' }}{{ config('app.name', 'SmartRT Vision') }}">
    <meta property="og:description" content="SmartRT Vision — Dashboard internal warga dan pengurus RT. Sistem Pendataan Warga RT berbasis AI.">
    <meta property="og:image" content="{{ asset('logo.png') }}">

    <title>{{ isset($title) ? $title . ' · ' : '' }}{{ config('app.name', 'SmartRT Vision') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    pwaInstalled: false,
    installPwa() {
        if (this.pwaInstalled) return;
        if(this.pwaPrompt) {
            this.pwaPrompt.prompt();
            this.pwaPrompt.userChoice.then((choice) => {
                if(choice.outcome === 'accepted') {
                    this.pwaInstallable = false;
                    this.pwaInstalled = true;
                }
            });
        }
    }
}" @beforeinstallprompt.window="$event.preventDefault(); pwaPrompt = $event; pwaInstallable = true" @appinstalled.window="pwaInstallable = false; pwaInstalled = true">

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
                <button x-show="pwaInstallable || pwaInstalled" style="display: none;" @click="installPwa()" :class="pwaInstalled ? 'bg-emerald-50 text-emerald-600 border border-emerald-200 cursor-default opacity-80' : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 cursor-pointer'" class="text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-2 transition-colors flex-shrink-0">
                    <svg x-show="!pwaInstalled" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <svg x-show="pwaInstalled" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="hidden sm:inline" x-text="pwaInstalled ? '✓ Sudah Terinstall' : 'Install Aplikasi'"></span>
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
        document.addEventListener('DOMContentLoaded', () => {
            // Global SweetAlert Confirm Override
            document.querySelectorAll('form').forEach(form => {
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('return confirm')) {
                    const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                    const message = match ? match[1] : 'Anda yakin ingin melanjutkan?';
                    
                    form.removeAttribute('onsubmit');
                    
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Konfirmasi',
                                html: message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, Lanjutkan!',
                                cancelButtonText: 'Batal',
                                reverseButtons: true,
                                customClass: {
                                    confirmButton: 'rounded-xl',
                                    cancelButton: 'rounded-xl',
                                    popup: 'rounded-2xl'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        } else {
                            if(confirm(message)) {
                                form.submit();
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        window.subscribeToWebPush = function() {
            if (Notification.permission !== 'granted') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        executeSubscription();
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Izin Ditolak', 'Anda memblokir notifikasi di browser ini.', 'warning');
                        }
                    }
                });
            } else {
                executeSubscription();
            }
        };

        function executeSubscription() {
            navigator.serviceWorker.ready.then((registration) => {
                const subscribeOptions = {
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(
                        document.querySelector('meta[name="vapid-pub-key"]').getAttribute('content')
                    )
                };
                return registration.pushManager.subscribe(subscribeOptions);
            })
            .then((pushSubscription) => {
                storePushSubscription(pushSubscription);
            }).catch(err => console.error('Subscription error:', err));
        }

        function storePushSubscription(pushSubscription) {
            fetch('/push-subscriptions', {
                method: 'POST',
                body: JSON.stringify(pushSubscription),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(res => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Berhasil!', 'Notifikasi diaktifkan untuk perangkat ini.', 'success');
                }
            })
            .catch(err => console.log('Store subscription failed:', err));
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    // Cek jika sudah subscribe, maka update token ke server secara background
                    registration.pushManager.getSubscription().then(subscription => {
                        if (subscription) {
                            fetch('/push-subscriptions', {
                                method: 'POST',
                                body: JSON.stringify(subscription),
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                        }
                    });
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
