<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('rw.tenants.index') }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">Klaim RT yang Sudah Ada</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Cari dan hubungkan RT lama ke organisasi <span class="font-semibold text-indigo-600">{{ $rw->name }}</span></p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Penjelasan --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm font-bold text-amber-800">Untuk RT yang sudah terdaftar sebelumnya</p>
                <p class="text-xs text-amber-700 mt-1">RT yang didaftarkan mandiri sebelum ada sistem RW tidak otomatis terhubung ke RW manapun. Gunakan fitur ini untuk mengklaim mereka ke dalam organisasi RW Anda.</p>
            </div>
        </div>

        {{-- Form Pencarian --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('rw.tenants.adopt') }}" class="flex gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ $query ?? '' }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Cari nama RT atau slug (contoh: rt-01-desa-mekar)..." autofocus>
                </div>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors">
                    Cari
                </button>
            </form>
        </div>

        {{-- Hasil Pencarian --}}
        @if(isset($query))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <p class="text-sm font-bold text-gray-700">
                        {{ $results->count() > 0 ? "Ditemukan {$results->count()} RT tidak bertuan" : 'Tidak ada hasil' }}
                        @if($query)
                            untuk pencarian <span class="text-indigo-600">"{{ $query }}"</span>
                        @endif
                    </p>
                </div>

                @if($results->count())
                    <ul class="divide-y divide-gray-50">
                        @foreach($results as $rt)
                            <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $rt->name }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs text-gray-400 font-mono">/{{ $rt->slug }}</span>
                                        <span class="text-xs text-gray-400">{{ $rt->families_count }} KK</span>
                                        <span class="text-xs {{ $rt->status === 'active' ? 'text-emerald-600 font-semibold' : 'text-gray-400' }}">{{ $rt->status === 'active' ? '● Aktif' : '○ Nonaktif' }}</span>
                                    </div>
                                </div>
                                @if($rt->rw_id !== null)
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-500 bg-gray-100 rounded-xl cursor-not-allowed border border-gray-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Sudah Terdaftar di RW Lain
                                    </div>
                                @else
                                    <div x-data="{ showModal: false }">
                                        <form method="POST" action="{{ route('rw.tenants.adopt.store', $rt) }}" class="flex items-center gap-2" id="form-{{ $rt->id }}" @submit.prevent="showModal = true">
                                            @csrf
                                            <input type="text" name="token" placeholder="Token Gabung (6 Karakter)..." maxlength="6" required class="w-52 text-xs font-mono uppercase border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-indigo-500 focus:border-indigo-500">
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                Klaim RT Ini
                                            </button>
                                        </form>

                                        <!-- Custom Alpine Modal -->
                                        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                                <div x-show="showModal" 
                                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                                     class="fixed inset-0 transition-opacity bg-gray-900/75 backdrop-blur-sm" @click="showModal = false"></div>
                                                
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                                
                                                <div x-show="showModal" 
                                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                     class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 border border-gray-100">
                                                    
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-indigo-50 rounded-2xl sm:mx-0 sm:h-12 sm:w-12">
                                                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        </div>
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                            <h3 class="text-lg font-black leading-6 text-gray-900">Konfirmasi Klaim RT</h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500">Anda yakin ingin mengklaim struktur organisasi <strong class="text-gray-900">{{ $rt->name }}</strong> ke dalam naungan RW Anda?</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-6 sm:flex sm:flex-row-reverse gap-3">
                                                        <button type="button" onclick="document.getElementById('form-{{ $rt->id }}').submit()" class="inline-flex justify-center w-full px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 border border-transparent rounded-xl shadow-sm hover:bg-indigo-700 focus:outline-none sm:w-auto">
                                                            Ya, Klaim Sekarang
                                                        </button>
                                                        <button type="button" @click="showModal = false" class="inline-flex justify-center w-full px-5 py-2.5 mt-3 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <p class="text-sm text-gray-400">Tidak ada RT bebas yang ditemukan dengan kata kunci tersebut.</p>
                        <p class="text-xs text-gray-400 mt-1">RT mungkin sudah terhubung ke RW lain, atau belum terdaftar sama sekali.</p>
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-rw-app-layout>
