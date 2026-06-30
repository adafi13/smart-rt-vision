<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'SmartRT Vision') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Decoration Elements -->
        <div class="fixed top-0 inset-x-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        <div class="fixed top-1/2 left-0 -translate-y-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="fixed bottom-0 right-0 translate-y-1/3 translate-x-1/3 w-[30rem] h-[30rem] bg-pink-500/10 rounded-full blur-3xl -z-10"></div>

        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 md:p-16 border border-slate-100 text-center relative overflow-hidden">
            <!-- Icon/Code Area -->
            <div class="mb-8 relative inline-block">
                <span class="text-7xl md:text-9xl font-black text-slate-100 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 select-none">
                    @yield('code')
                </span>
                <div class="w-24 h-24 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto text-indigo-600 shadow-sm border border-indigo-100 rotate-3 transition-transform hover:rotate-6">
                    @yield('icon')
                </div>
            </div>

            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-4">
                @yield('message')
            </h1>
            
            <p class="text-slate-500 text-lg mb-10 max-w-md mx-auto">
                @yield('description')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button onclick="window.history.back()" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white border-2 border-slate-200 text-slate-700 font-bold hover:bg-slate-50 hover:border-slate-300 transition-colors focus:ring-4 focus:ring-slate-100">
                    Kembali
                </button>
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 border-2 border-indigo-600 text-white font-bold hover:bg-indigo-700 hover:border-indigo-700 shadow-lg shadow-indigo-200 transition-all focus:ring-4 focus:ring-indigo-100">
                    Ke Beranda Utama
                </a>
                
                @auth
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-rose-50 border-2 border-rose-100 text-rose-600 font-bold hover:bg-rose-100 hover:border-rose-200 transition-colors focus:ring-4 focus:ring-rose-50">
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>

        <div class="mt-12 text-center text-sm font-medium text-slate-400">
            &copy; {{ date('Y') }} SmartRT Vision Enterprise. Hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
