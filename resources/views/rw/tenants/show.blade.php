<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('rw.tenants.index') }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">{{ $tenant->name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5 font-mono">/{{ $tenant->slug }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('home', ['tenant' => $tenant->slug]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-bold rounded-xl text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Portal RT
                </a>
                <form method="POST" action="{{ route('rw.tenants.toggle-status', $tenant) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-bold rounded-xl transition-colors {{ $tenant->status === 'active' ? 'text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-100' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100' }}">
                        {{ $tenant->status === 'active' ? 'Nonaktifkan RT' : 'Aktifkan RT' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 text-sm text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-100">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</p>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $tenant->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $tenant->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $tenant->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total KK</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ number_format($totalKK, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Warga</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ number_format($totalWarga, 0, ',', '.') }} <span class="text-sm font-medium text-gray-400">jiwa</span></p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Saldo Kas</p>
                <p class="text-2xl font-black mt-1 {{ $saldo < 0 ? 'text-rose-600' : 'text-emerald-600' }}">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Masuk: Rp {{ number_format($pemasukan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Info Admin RT --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Admin / Ketua RT</h2>
                @if($owner)
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-black text-indigo-600 text-sm flex-shrink-0">
                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $owner->name }}</p>
                            <p class="text-xs text-gray-500">{{ $owner->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Role: <span class="font-semibold text-gray-700">{{ ucfirst($owner->role) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Bergabung: <span class="font-semibold text-gray-700">{{ $owner->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <svg class="mx-auto w-8 h-8 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <p class="text-xs text-gray-400">Belum ada admin RT terdaftar.</p>
                    </div>
                @endif

                <div class="mt-5 pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Info RT</h3>
                    <div class="space-y-1.5 text-xs text-gray-500">
                        <div class="flex justify-between">
                            <span>Terdaftar</span>
                            <span class="font-semibold text-gray-700">{{ $tenant->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Bergabung sejak</span>
                            <span class="font-semibold text-gray-700">{{ $tenant->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar KK Terbaru --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">KK Terbaru</h2>
                </div>
                @if($recentFamilies->count())
                    <ul class="divide-y divide-gray-50">
                        @foreach($recentFamilies as $family)
                            <li class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $family->kepala_keluarga }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">No. KK: {{ $family->nomor_kk }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $family->created_at->format('d M Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <p class="text-sm text-gray-400">Belum ada data KK di RT ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-rw-app-layout>
