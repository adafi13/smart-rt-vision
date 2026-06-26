<x-app-layout title="Pengaturan Profil">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header / Hero Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-blue-600 to-sky-500 p-8 sm:p-10 text-white shadow-lg">
            <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-4xl font-bold shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-3xl font-bold tracking-tight mb-1">{{ auth()->user()->name }}</h1>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-md border border-white/20">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ auth()->user()->email }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 text-emerald-50">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ auth()->user()->role == 'admin_rt' ? 'Pengurus RT' : (auth()->user()->role == 'warga' ? 'Warga' : 'Super Admin') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100/50">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Informasi Akun
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status Akun</p>
                            <p class="font-medium text-gray-900 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Bergabung Sejak</p>
                            <p class="font-medium text-gray-900">{{ auth()->user()->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Update Profile Info -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 overflow-hidden relative group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                    <div class="p-6 sm:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 overflow-hidden relative group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                    <div class="p-6 sm:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 overflow-hidden relative group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
                    <div class="p-6 sm:p-8">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
