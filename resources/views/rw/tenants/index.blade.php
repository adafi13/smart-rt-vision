<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen RT</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh RT di wilayah <span class="font-semibold text-indigo-600">{{ $rw->name }}</span></p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('rw.tenants.adopt') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Klaim RT Lama
                </a>
                <a href="{{ route('rw.tenants.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah RT Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flex items-start gap-3 p-4 text-sm text-emerald-800 bg-emerald-50 rounded-lg border border-emerald-200 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-start gap-3 p-4 text-sm text-rose-800 bg-rose-50 rounded-lg border border-rose-200 shadow-sm">
                <svg class="w-5 h-5 text-rose-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif

        {{-- Invite Link Card --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col md:flex-row md:items-center">
            <div class="p-6 md:w-1/2 bg-gray-50 border-b md:border-b-0 md:border-r border-gray-200">
                <div class="flex items-center gap-2 mb-2">
                    <span class="p-1.5 bg-indigo-100 text-indigo-600 rounded-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </span>
                    <h3 class="text-base font-bold text-gray-900">Link Undangan Cepat</h3>
                </div>
                <p class="text-sm text-gray-600">Bagikan link ini kepada calon admin RT. Setelah mendaftar, RT tersebut akan otomatis bernaung di bawah RW Anda.</p>
            </div>
            <div class="p-6 md:w-1/2 flex items-center" x-data="{ copied: false }">
                <div class="flex w-full gap-2 relative">
                    <input type="text" readonly value="{{ route('register', ['ref' => $rw->invite_code]) }}" class="w-full text-sm font-mono bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <button @click="navigator.clipboard.writeText('{{ route('register', ['ref' => $rw->invite_code]) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="flex-shrink-0 inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors" :class="copied ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-gray-800 text-white hover:bg-gray-900'">
                        <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
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
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 relative">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-lg border border-indigo-200">
                                {{ strtoupper(substr($rt->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 leading-tight">{{ $rt->name }}</h3>
                                <a href="{{ route('home', ['tenant' => $rt->slug]) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">/{{ $rt->slug }}</a>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium {{ $rt->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $rt->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $rt->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-600"><span class="font-medium text-gray-900">Admin:</span> {{ $owner ? $owner->name : 'Belum Ada' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Total Warga</p>
                            <p class="font-semibold text-gray-900">{{ $rt->families_count }} <span class="text-xs font-normal text-gray-500">KK</span></p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Bergabung</p>
                            <p class="font-semibold text-gray-900">{{ $rt->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div>
                            @if($activeSub)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $activeSub->plan->name ?? 'Berlangganan' }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1">Exp: {{ \Carbon\Carbon::parse($activeSub->current_period_end)->format('d/m/Y') }}</div>
                            @elseif($isOnTrial)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                    Uji Coba
                                </span>
                                <div class="text-xs text-gray-500 mt-1">Exp: {{ $rt->trial_ends_at ? $rt->trial_ends_at->format('d/m/Y') : '-' }}</div>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-rose-100 text-rose-800">
                                    Kedaluwarsa
                                </span>
                                @if($waNumber)
                                    <div class="mt-1">
                                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="text-xs font-medium text-rose-600 hover:underline">Ingatkan WA &rarr;</a>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        <a href="{{ route('rw.tenants.show', $rt) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-semibold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="text-base font-semibold text-gray-900">Belum Ada RT</p>
                    <p class="text-sm text-gray-500 mt-1 mb-4">Daftarkan RT pertama di lingkungan Anda.</p>
                    <a href="{{ route('rw.tenants.create') }}" class="inline-block px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg">Tambah RT Baru</a>
                </div>
            @endforelse
            
            @if($rts->hasPages())
                <div class="pt-2">{{ $rts->links() }}</div>
            @endif
        </div>

        {{-- Desktop View: Table (Hidden on small screens) --}}
        <div class="hidden md:block bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi RT</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin Utama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Warga</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Akun</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Langganan</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
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
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-indigo-200">
                                            {{ strtoupper(substr($rt->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $rt->name }}</p>
                                            <a href="{{ route('home', ['tenant' => $rt->slug]) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">/{{ $rt->slug }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($owner)
                                        <p class="text-sm font-semibold text-gray-900">{{ $owner->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $owner->email }}</p>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                            Belum Diatur
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-semibold text-gray-900">{{ $rt->families_count }} <span class="text-xs font-normal text-gray-500">KK</span></p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium {{ $rt->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $rt->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $rt->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">Join: {{ $rt->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activeSub)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">
                                            {{ $activeSub->plan->name ?? 'Aktif' }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">Exp: {{ \Carbon\Carbon::parse($activeSub->current_period_end)->format('d/m/Y') }}</p>
                                    @elseif($isOnTrial)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            Uji Coba
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">Exp: {{ $rt->trial_ends_at ? $rt->trial_ends_at->format('d/m/Y') : '-' }}</p>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-rose-100 text-rose-800">
                                            Kedaluwarsa
                                        </span>
                                        @if($waNumber)
                                            <p class="mt-1">
                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="text-xs font-medium text-rose-600 hover:underline">
                                                    Hubungi via WA &rarr;
                                                </a>
                                            </p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('rw.tenants.show', $rt) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                        Detail Info
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <p class="text-base font-semibold text-gray-900">Belum Ada Data RT</p>
                                    <p class="text-sm text-gray-500 mt-1 mb-4">Mulai dengan mendaftarkan RT pertama di lingkungan Anda.</p>
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('rw.tenants.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Tambah RT Baru</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($rts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $rts->links() }}</div>
            @endif
        </div>
    </div>
</x-rw-app-layout>
