<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight">Manajemen RT</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh RT di wilayah <span class="font-bold text-indigo-600">{{ $rw->name }}</span></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('rw.tenants.adopt') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-all hover:scale-105 active:scale-95 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Klaim RT Lama
                </a>
                <a href="{{ route('rw.tenants.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl text-white shadow-md shadow-indigo-200 transition-all hover:scale-105 active:scale-95" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Tambah RT Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if (session('success'))
            <div class="flex items-center gap-3 p-4 text-sm text-emerald-800 bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm animate-[slideDown_0.3s_ease-out]">
                <div class="p-2 bg-emerald-100 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 p-4 text-sm text-rose-800 bg-rose-50 rounded-2xl border border-rose-100 shadow-sm animate-[slideDown_0.3s_ease-out]">
                <div class="p-2 bg-rose-100 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                </div>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Invite Link Card --}}
        <div class="relative overflow-hidden rounded-2xl p-6 md:p-8 border border-indigo-100 shadow-sm bg-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-60"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -ml-20 -mb-20 opacity-60"></div>
            
            <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold mb-3 border border-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Otomatisasi
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-1">
                        Link Undangan Cepat
                    </h3>
                    <p class="text-sm text-gray-500 font-medium max-w-xl">Bagikan link ini ke Ketua RT di lingkungan Anda. Saat mereka mendaftar, akun RT mereka otomatis tergabung ke dalam struktur RW Anda.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto" x-data="{ copied: false }">
                    <div class="relative flex-1 sm:w-72">
                        <input type="text" readonly value="{{ route('register', ['ref' => $rw->invite_code]) }}" class="w-full text-sm font-mono bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    
                    <button @click="navigator.clipboard.writeText('{{ route('register', ['ref' => $rw->invite_code]) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="flex-shrink-0 flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold shadow-sm transition-all hover:scale-105 active:scale-95" :class="copied ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-gray-900 text-white hover:bg-gray-800 shadow-gray-200'">
                        <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <svg x-show="copied" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="copied ? 'Berhasil Disalin!' : 'Salin Link'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile View: Cards (Visible only on small screens) --}}
        <div class="md:hidden space-y-4">
            @forelse($rts as $rt)
                @php 
                    $owner = $rt->users->first(); 
                    $activeSub = $rt->activeSubscription();
                    $isOnTrial = $rt->onTrial();
                    $contactStaff = $rt->rtStaffs->whereNotNull('phone')->first();
                    $waNumber = $contactStaff ? preg_replace('/[^0-9]/', '', $contactStaff->phone) : null;
                    if ($waNumber && str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    $waMessage = urlencode("Halo Pengurus {$rt->name}, kami dari RW ingin mengingatkan bahwa masa langganan sistem KakaAi Anda telah kedaluwarsa. Mohon segera diperpanjang agar layanan warga tetap berjalan lancar. Terima kasih.");
                @endphp
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4">
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $rt->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $rt->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $rt->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-white text-lg flex-shrink-0 shadow-inner" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            {{ strtoupper(substr($rt->name, 0, 1)) }}
                        </div>
                        <div class="pr-16">
                            <h3 class="text-base font-black text-gray-900">{{ $rt->name }}</h3>
                            <a href="{{ route('home', ['tenant' => $rt->slug]) }}" target="_blank" class="inline-block text-xs font-mono text-indigo-500 mt-0.5 mb-1 bg-indigo-50 px-2 py-0.5 rounded-md">/{{ $rt->slug }}</a>
                            <p class="text-xs text-gray-500 font-medium line-clamp-1 mt-1">Admin: <span class="text-gray-900 font-semibold">{{ $owner ? $owner->name : 'Belum Ada' }}</span></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 mb-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total KK</p>
                            <p class="text-sm font-black text-gray-900 mt-0.5">{{ $rt->families_count }} <span class="text-gray-500 text-xs font-semibold">KK</span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Bergabung</p>
                            <p class="text-sm font-black text-gray-900 mt-0.5">{{ $rt->created_at->format('d M y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2 pt-4 border-t border-gray-100">
                        <div>
                            @if($activeSub)
                                <div class="flex flex-col gap-0.5">
                                    <span class="inline-flex w-fit items-center gap-1 text-[11px] font-bold text-emerald-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $activeSub->plan->name ?? 'Aktif' }}
                                    </span>
                                    <span class="text-[9px] font-medium text-gray-500">Exp: {{ \Carbon\Carbon::parse($activeSub->current_period_end)->format('d/m/y') }}</span>
                                </div>
                            @elseif($isOnTrial)
                                <div class="flex flex-col gap-0.5">
                                    <span class="inline-flex w-fit items-center gap-1 text-[11px] font-bold text-blue-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Uji Coba
                                    </span>
                                    <span class="text-[9px] font-medium text-gray-500">Exp: {{ $rt->trial_ends_at ? $rt->trial_ends_at->format('d/m/y') : '-' }}</span>
                                </div>
                            @else
                                <div class="flex flex-col gap-0.5">
                                    <span class="inline-flex w-fit items-center gap-1 text-[11px] font-bold text-rose-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Kedaluwarsa
                                    </span>
                                    @if($waNumber)
                                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="text-[9px] font-bold text-rose-500 hover:text-emerald-600 underline">Ingatkan WA &rarr;</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('rw.tenants.show', $rt) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold rounded-xl text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 transition-colors">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="text-base font-bold text-gray-900">Belum Ada RT</p>
                    <p class="text-sm text-gray-500 mt-1 mb-5">Daftarkan RT pertama Anda.</p>
                    <a href="{{ route('rw.tenants.create') }}" class="w-full block px-4 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl">Tambah RT Baru</a>
                </div>
            @endforelse
            
            @if($rts->hasPages())
                <div class="pt-2">{{ $rts->links() }}</div>
            @endif
        </div>

        {{-- Desktop View: Premium Table (Hidden on small screens) --}}
        <div class="hidden md:block bg-white rounded-2xl shadow-[0_2px_12px_-4px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Informasi RT</th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Admin Utama</th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Statistik</th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Status / Akun</th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Berlangganan</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-black text-gray-500 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse($rts as $rt)
                            @php 
                                $owner = $rt->users->first(); 
                                $activeSub = $rt->activeSubscription();
                                $isOnTrial = $rt->onTrial();
                                $contactStaff = $rt->rtStaffs->whereNotNull('phone')->first();
                                $waNumber = $contactStaff ? preg_replace('/[^0-9]/', '', $contactStaff->phone) : null;
                                if ($waNumber && str_starts_with($waNumber, '0')) {
                                    $waNumber = '62' . substr($waNumber, 1);
                                }
                                $waMessage = urlencode("Halo Pengurus {$rt->name}, kami dari RW ingin mengingatkan bahwa masa langganan sistem KakaAi Anda telah kedaluwarsa. Mohon segera diperpanjang agar layanan warga tetap berjalan lancar. Terima kasih.");
                            @endphp
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-white shadow-sm flex-shrink-0" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                                            {{ strtoupper(substr($rt->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $rt->name }}</p>
                                            <a href="{{ route('home', ['tenant' => $rt->slug]) }}" target="_blank" class="inline-block text-xs font-mono text-gray-500 hover:text-indigo-500 bg-gray-50 px-1.5 py-0.5 rounded transition-colors mt-0.5">/{{ $rt->slug }} ↗</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($owner)
                                        <p class="text-sm font-bold text-gray-800">{{ $owner->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $owner->email }}</p>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-gray-50 text-gray-500 text-xs font-medium border border-gray-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Belum Diatur
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <p class="text-sm font-black text-gray-900">{{ $rt->families_count }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Keluarga</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider {{ $rt->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $rt->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                            {{ $rt->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-medium">Join: {{ $rt->created_at->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activeSub)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $activeSub->plan->name ?? 'Aktif' }}
                                            </span>
                                            <span class="text-[11px] font-medium text-gray-500">Exp: {{ \Carbon\Carbon::parse($activeSub->current_period_end)->format('d M Y') }}</span>
                                        </div>
                                    @elseif($isOnTrial)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Uji Coba
                                            </span>
                                            <span class="text-[11px] font-medium text-gray-500">Exp: {{ $rt->trial_ends_at ? $rt->trial_ends_at->format('d M Y') : '-' }}</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Kedaluwarsa
                                            </span>
                                            @if($waNumber)
                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="text-[11px] font-bold text-rose-500 hover:text-emerald-600 hover:underline cursor-pointer flex items-center gap-1 transition-colors">
                                                    Hubungi via WA &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('rw.tenants.show', $rt) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold rounded-xl text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all">
                                        Detail Info
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100 shadow-sm">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <p class="text-base font-black text-gray-900">Belum Ada Data RT</p>
                                    <p class="text-sm text-gray-500 mt-1 mb-6">Mulai dengan mendaftarkan RT pertama di lingkungan Anda.</p>
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('rw.tenants.create') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200">+ Tambah RT Baru</a>
                                        <a href="{{ route('rw.tenants.adopt') }}" class="px-5 py-2.5 text-sm font-bold text-amber-700 bg-amber-50 rounded-xl hover:bg-amber-100 border border-amber-200 transition-all">Klaim RT Lama</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($rts->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">{{ $rts->links() }}</div>
            @endif
        </div>
    </div>
</x-rw-app-layout>
