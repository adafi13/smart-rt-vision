<x-app-layout title="Pengaturan RT">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pengaturan Profil RT</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi dasar dan integrasi RT Anda.</p>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Integrasi dengan RW</h2>
            
            @if($tenant->rw_id)
                <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">RT Anda sudah terhubung dengan {{ $tenant->rw->name ?? 'RW' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Semua data kependudukan telah terintegrasi.</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">RT Anda belum terhubung dengan organisasi RW manapun. Untuk bergabung, berikan <strong>Token Gabung</strong> ini kepada Ketua RW Anda.</p>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        @if($tenant->adoption_token)
                            <div class="px-6 py-3 bg-gray-50 border border-gray-200 rounded-xl font-mono text-xl font-black text-indigo-600 tracking-widest shadow-inner select-all">
                                {{ $tenant->adoption_token }}
                            </div>
                        @else
                            <div class="px-6 py-3 bg-gray-50 border border-gray-200 border-dashed rounded-xl font-mono text-sm font-bold text-gray-400">
                                BELUM ADA TOKEN
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.settings.generate-token') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Generate Token Baru
                            </button>
                        </form>
                    </div>
                    
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 flex gap-3 text-amber-800 text-xs">
                        <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p><strong>Perhatian:</strong> Berikan token ini hanya kepada Ketua RW yang sah. Dengan memberikan token ini, RW dapat menambahkan struktur organisasi RT Anda ke dalam organisasi mereka.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
