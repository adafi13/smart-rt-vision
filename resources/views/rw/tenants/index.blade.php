<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Manajemen RT</h1>
                <p class="text-sm text-gray-500 mt-0.5">Seluruh RT di bawah naungan <span class="font-semibold text-indigo-600">{{ $rw->name }}</span></p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('rw.tenants.adopt') }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-bold rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Klaim RT Lama
                </a>
                <a href="{{ route('rw.tenants.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah RT Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4">
        @if (session('success'))
            <div class="flex items-center gap-3 p-4 text-sm text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-100">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center gap-3 p-4 text-sm text-rose-700 bg-rose-50 rounded-xl border border-rose-100">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Invite Link Card --}}
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-emerald-900 mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Link Undangan RT
                </h3>
                <p class="text-xs text-emerald-700 font-medium">Bagikan link ini ke Ketua RT. Saat mereka mendaftar, RT-nya otomatis masuk ke naungan RW Anda.</p>
            </div>
            
            <div class="flex items-center gap-2" x-data="{ copied: false }">
                <input type="text" readonly value="{{ route('register', ['ref' => $rw->invite_code]) }}" class="text-xs font-mono bg-white border border-emerald-200 rounded-lg px-3 py-2 w-full md:w-64 text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                
                <button @click="navigator.clipboard.writeText('{{ route('register', ['ref' => $rw->invite_code]) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold transition-all" :class="copied ? 'bg-emerald-500 text-white' : 'bg-emerald-600 text-white hover:bg-emerald-700'">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <svg x-show="copied" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama RT</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin RT</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah KK</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Akun</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Langganan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bergabung</th>
                            <th class="px-6 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($rts as $rt)
                            @php $owner = $rt->users->first(); @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $rt->name }}</p>
                                    <a href="{{ route('home', ['tenant' => $rt->slug]) }}" target="_blank" class="text-xs text-gray-400 font-mono hover:text-indigo-500 transition-colors">/{{ $rt->slug }} ↗</a>
                                </td>
                                <td class="px-6 py-4">
                                    @if($owner)
                                        <p class="text-sm font-semibold text-gray-800">{{ $owner->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $owner->email }}</p>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum ada admin</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-800">{{ $rt->families_count }}</span>
                                    <span class="text-xs text-gray-400 ml-0.5">KK</span>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('rw.tenants.toggle-status', $rt) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold transition-all hover:opacity-80 {{ $rt->status === 'active' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $rt->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                            {{ $rt->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                    @php
                                        $activeSub = $rt->activeSubscription();
                                        $isOnTrial = $rt->onTrial();
                                        $contactStaff = $rt->rtStaffs->whereNotNull('phone')->first();
                                        $waNumber = $contactStaff ? preg_replace('/[^0-9]/', '', $contactStaff->phone) : null;
                                        // Ensure it starts with country code (e.g., 62 for Indonesia)
                                        if ($waNumber && str_starts_with($waNumber, '0')) {
                                            $waNumber = '62' . substr($waNumber, 1);
                                        }
                                        $waMessage = urlencode("Halo Pengurus {$rt->name}, kami dari RW ingin mengingatkan bahwa masa langganan sistem KakaAi Anda telah kedaluwarsa. Mohon segera diperpanjang agar layanan warga tetap berjalan lancar. Terima kasih.");
                                    @endphp
                                    
                                    @if($activeSub)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $activeSub->plan->name ?? 'Aktif' }}
                                            </span>
                                            <span class="text-[10px] font-medium text-gray-500">
                                                Exp: {{ \Carbon\Carbon::parse($activeSub->current_period_end)->format('d M Y') }}
                                            </span>
                                        </div>
                                    @elseif($isOnTrial)
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Uji Coba (Trial)
                                            </span>
                                            <span class="text-[10px] font-medium text-gray-500">
                                                Exp: {{ $rt->trial_ends_at ? $rt->trial_ends_at->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex w-fit items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Kedaluwarsa
                                            </span>
                                            @if($waNumber)
                                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="text-[10px] font-medium text-rose-500 hover:text-emerald-600 hover:underline cursor-pointer flex items-center gap-1 transition-colors">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    Hubungi via WA &rarr;
                                                </a>
                                            @else
                                                <span class="text-[10px] font-medium text-rose-500 hover:underline cursor-pointer" onclick="alert('Admin RT belum mengatur data pengurus/nomor WA di menunya.')">
                                                    Ingatkan RT &rarr;
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-400">{{ $rt->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('rw.tenants.show', $rt) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-900 transition-colors">
                                        Detail
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <svg class="mx-auto w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <p class="text-sm font-bold text-gray-600">Belum Ada RT</p>
                                    <p class="text-xs text-gray-400 mt-1 mb-4">Daftarkan RT pertama atau klaim RT yang sudah ada.</p>
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('rw.tenants.create') }}" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors">+ Tambah RT Baru</a>
                                        <a href="{{ route('rw.tenants.adopt') }}" class="px-4 py-2 text-xs font-bold text-amber-700 bg-amber-50 rounded-xl hover:bg-amber-100 border border-amber-200 transition-colors">↩ Klaim RT Lama</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($rts->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $rts->links() }}</div>
            @endif
        </div>
    </div>
</x-rw-app-layout>
