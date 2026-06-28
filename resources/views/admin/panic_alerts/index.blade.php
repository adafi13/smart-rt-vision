<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <div class="p-2 bg-rose-100 rounded-xl">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                Panic Alerts
            </h2>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openModal: false, selectedAlert: null, selectedName: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-4 rounded-2xl flex items-center shadow-sm">
                    <svg class="w-6 h-6 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Mobile View: Cards -->
            <div class="grid grid-cols-1 gap-4 md:hidden px-4 sm:px-0">
                @forelse($alerts as $alert)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden">
                        @if($alert->status == 'active')
                            <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
                        @else
                            <div class="absolute top-0 left-0 w-1 h-full bg-emerald-400"></div>
                        @endif

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $alert->reporter_name }}</h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $alert->reporter_contact }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full {{ $alert->status == 'active' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $alert->status == 'active' ? 'Darurat Aktif' : 'Selesai' }}
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3 mb-4 text-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">Kategori:</span>
                                <span class="font-semibold text-gray-900">{{ $alert->type }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">Waktu:</span>
                                <span class="font-medium text-gray-900">{{ $alert->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Lokasi:</span>
                                <span class="font-medium text-gray-900 text-right">{{ $alert->location }}</span>
                            </div>
                        </div>

                        @if($alert->status == 'active')
                            <button @click="openModal = true; selectedAlert = '{{ $alert->id }}'; selectedName = '{{ addslashes($alert->reporter_name) }}'" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Tangani Sekarang
                            </button>
                        @else
                            <div class="text-sm bg-emerald-50 border border-emerald-100 rounded-xl p-3 text-emerald-800">
                                <span class="font-bold block mb-1">Catatan Penanganan:</span>
                                {{ $alert->resolution_note }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Aman Terkendali</h3>
                        <p class="text-gray-500 mt-1">Belum ada riwayat laporan darurat di lingkungan RT.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Table -->
            <div class="hidden md:block bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Pelapor</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kejadian</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($alerts as $alert)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-50 flex items-center justify-center flex-shrink-0 border border-indigo-100">
                                                <span class="font-bold text-indigo-700">{{ substr($alert->reporter_name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $alert->reporter_name }}</p>
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $alert->reporter_contact }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="mb-1">
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $alert->type }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-900 font-medium mt-2">{{ $alert->location }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $alert->created_at->format('d M Y, H:i') }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($alert->status == 'active')
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                <span class="text-xs font-bold uppercase tracking-wide">Aktif</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-xs font-bold uppercase tracking-wide">Selesai</span>
                                            </div>
                                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mt-2">
                                                Ditangani pada {{ $alert->resolved_at->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        @if($alert->status == 'active')
                                            <button @click="openModal = true; selectedAlert = '{{ $alert->id }}'; selectedName = '{{ addslashes($alert->reporter_name) }}'" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                                                Tandai Selesai
                                            </button>
                                        @else
                                            <div class="text-left text-sm text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100 inline-block max-w-xs">
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Catatan Admin</span>
                                                {{ $alert->resolution_note }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Aman Terkendali</h3>
                                        <p class="text-gray-500 mt-1">Belum ada riwayat laporan darurat di lingkungan RT.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $alerts->links() }}
            </div>
            
        </div>

        <!-- Modal Tangani Darurat -->
        <div x-show="openModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="openModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" 
                 @click="openModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal Panel -->
                <div x-show="openModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                    
                    <form :action="'{{ url('admin/panic-alerts') }}/' + selectedAlert + '/resolve'" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Selesaikan Status Darurat</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Anda akan menandai laporan dari <span class="font-bold text-gray-900" x-text="selectedName"></span> sebagai selesai. Silakan isi catatan penanganan di bawah ini.
                                        </p>
                                        
                                        <div>
                                            <label for="resolution_note" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Penanganan <span class="text-rose-500">*</span></label>
                                            <textarea name="resolution_note" id="resolution_note" rows="3" required
                                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                placeholder="Contoh: Tim keamanan sudah mendatangi lokasi dan situasi sudah kondusif..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-rose-700 sm:ml-3 sm:w-auto transition-colors">
                                Selesaikan Kasus
                            </button>
                            <button type="button" @click="openModal = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
