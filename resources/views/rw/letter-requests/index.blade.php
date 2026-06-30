<x-rw-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Pengajuan Surat</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola permohonan surat masuk dari warga</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <form method="GET" action="{{ route('rw.letter-requests.index') }}" class="flex gap-2 w-full sm:max-w-xs">
                <select name="rw_status" class="w-full pl-3 pr-8 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-700 bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors" onchange="this.form.submit()">
                    <option value="">Semua Status Permohonan</option>
                    @foreach(['Pending', 'Disetujui', 'Ditolak'] as $s)
                        <option value="{{ $s }}" @selected(request('rw_status') === $s)>Status: {{ $s }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($letterRequests->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Keperluan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($letterRequests as $lr)
                    <tr class="hover:bg-gray-50/50 transition-colors group align-top">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ substr($lr->member->nama, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 block">{{ $lr->member->nama }}</span>
                                    <span class="text-[10px] text-gray-500 font-mono mt-0.5 block">NIK: {{ $lr->member->nik }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600 border border-gray-200">
                                {{ $lr->jenis_surat }}
                            </span>
                            <span class="text-[10px] text-gray-400 block mt-1.5">{{ $lr->created_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px] max-w-xs">
                            <p class="text-xs text-gray-600 line-clamp-2" title="{{ $lr->keperluan }}">{{ $lr->keperluan }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($lr->rw_status === 'Disetujui')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Disetujui
                                </span>
                            @elseif($lr->rw_status === 'Ditolak')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 transition-opacity">
                                @if($lr->rw_status === 'Disetujui')
                                <button type="button" onclick="triggerPdfPreview('{{ route('rw.letter-requests.pdf', ['letterRequest' => $lr->id, 'preview' => 1]) }}', '{{ route('rw.letter-requests.pdf', $lr->id) }}')" class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors" title="Cetak Surat PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </button>
                                @endif
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'kelola-surat-{{ $lr->id }}')" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Kelola Pengajuan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE COMPACT CARDS -->
        <div class="md:hidden space-y-3">
            @foreach($letterRequests as $lr)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <!-- Status Indicator Bar -->
                <div class="absolute left-0 top-0 bottom-0 w-1 
                    {{ $lr->rw_status === 'Disetujui' ? 'bg-emerald-500' : ($lr->rw_status === 'Ditolak' ? 'bg-rose-500' : 'bg-amber-500') }}">
                </div>
                
                <div class="p-4 pl-5">
                    <div class="flex justify-between items-start gap-3 mb-2.5">
                        <div>
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600 border border-gray-200 mb-1.5">{{ $lr->jenis_surat }}</span>
                            <h3 class="font-bold text-gray-900 leading-tight">{{ $lr->member->nama }}</h3>
                            <p class="text-[10px] text-gray-500 font-mono mt-0.5">NIK: {{ $lr->member->nik }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @if($lr->rw_status === 'Disetujui')
                                <span class="inline-flex px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold uppercase">Disetujui</span>
                            @elseif($lr->rw_status === 'Ditolak')
                                <span class="inline-flex px-2 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded text-[10px] font-bold uppercase">Ditolak</span>
                            @else
                                <span class="inline-flex px-2 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-bold uppercase">Pending</span>
                            @endif
                            <span class="text-[9px] text-gray-400 mt-1 block">{{ $lr->created_at->format('d/m/y H:i') }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                        <p class="text-[11px] text-gray-600"><span class="font-semibold text-gray-800">Keperluan:</span> {{ $lr->keperluan }}</p>
                        @if($lr->catatan_rw)
                            <p class="text-[11px] text-gray-600 mt-1.5"><span class="font-semibold text-gray-800">Catatan RW:</span> {{ $lr->catatan_rw }}</p>
                        @endif
                    </div>
                </div>

                <div class="p-3 bg-gray-50/50 border-t border-gray-50 flex gap-2 pl-5">
                    @if($lr->rw_status === 'Disetujui')
                        <button type="button" onclick="triggerPdfPreview('{{ route('rw.letter-requests.pdf', ['letterRequest' => $lr->id, 'preview' => 1]) }}', '{{ route('rw.letter-requests.pdf', $lr->id) }}')" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 text-emerald-700 text-[11px] font-bold rounded-lg transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak
                        </button>
                    @endif

                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'kelola-surat-{{ $lr->id }}')" class="flex-1 flex items-center justify-center gap-1.5 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[11px] font-bold rounded-lg transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Kelola
                    </button>

                    
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $letterRequests->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Pengajuan</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Saat ini belum ada warga yang mengajukan permohonan surat pengantar melalui portal warga.</p>
        </div>
        @endif

    </div>

    <!-- MODAL KELOLA SURAT -->
    @foreach($letterRequests as $lr)
    <x-modal name="kelola-surat-{{ $lr->id }}" focusable>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Tinjau Pengajuan Surat</h2>
                    <p class="text-xs text-gray-500">Pemohon: <span class="font-semibold text-gray-700">{{ $lr->member->nama }}</span> ({{ $lr->member->nik }})</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 mb-5 border border-gray-100">
                <div class="grid grid-cols-1 gap-3 text-sm">
                    <div>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wide">Jenis Surat</p>
                        <p class="font-semibold text-gray-900 mt-0.5">{{ $lr->jenis_surat }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wide">Keperluan Warga</p>
                        <p class="text-gray-800 mt-0.5">{{ $lr->keperluan }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wide">Waktu Pengajuan</p>
                        <p class="text-gray-800 mt-0.5">{{ $lr->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('rw.letter-requests.update', $lr) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tindakan Keputusan <span class="text-red-500">*</span></label>
                    <select name="rw_status" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-medium">
                        <option value="Pending" @selected($lr->rw_status === 'Pending')>⌛ Biarkan Pending (Menunggu)</option>
                        <option value="Disetujui" @selected($lr->rw_status === 'Disetujui')>✅ Setujui (Siap Dicetak)</option>
                        <option value="Ditolak" @selected($lr->rw_status === 'Ditolak')>❌ Tolak Pengajuan</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan RW (Opsional)</label>
                    <textarea name="catatan_rw" rows="3" class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm placeholder-gray-400" placeholder="Berikan alasan jika ditolak, atau pesan tambahan untuk warga...">{{ $lr->catatan_rw }}</textarea>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </x-modal>
    @endforeach

    <!-- PDF Preview & Printing Modal -->
    <div x-data="{ openPdfModal: false, pdfUrl: '', downloadUrl: '', iframeLoading: true }" 
         @open-pdf-modal.window="openPdfModal = true; iframeLoading = true; pdfUrl = $event.detail.url; downloadUrl = $event.detail.downloadUrl" 
         class="relative z-50" 
         x-cloak>
        
        <!-- Backdrop blur overlay -->
        <div x-show="openPdfModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
             @click="openPdfModal = false; iframeLoading = true"></div>

        <!-- Center modal body -->
        <div x-show="openPdfModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-10">
            
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden">
                <!-- Modal Header -->
                <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Pratinjau Cetak Surat Pengantar</h3>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5 tracking-wider">Silakan tinjau surat sebelum mencetak atau mengunduh.</p>
                    </div>
                    <button type="button" @click="openPdfModal = false; iframeLoading = true" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Content (Iframe with Loading State) -->
                <div class="flex-1 bg-gray-100 relative">
                    <!-- Loading Spinner -->
                    <div x-show="iframeLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 z-10 transition-all duration-300">
                        <div class="w-12 h-12 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-4 animate-pulse">Menyiapkan Dokumen PDF...</p>
                    </div>
                    
                    <iframe id="pdfPreviewIframe" :src="pdfUrl" @load="iframeLoading = false" class="w-full h-full border-none bg-white"></iframe>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <!-- Print Button -->
                        <button type="button" onclick="printPdfIframe()" class="flex-1 sm:flex-none bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2zm5-11V3m-4 0h8"></path></svg>
                            <span>Cetak Surat</span>
                        </button>

                        <!-- Download Button -->
                        <a :href="downloadUrl" class="flex-1 sm:flex-none bg-white hover:bg-gray-100 text-gray-800 px-6 py-3 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Unduh PDF</span>
                        </a>
                    </div>

                    <button type="button" @click="openPdfModal = false; iframeLoading = true" class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function triggerPdfPreview(previewUrl, downloadUrl) {
            window.dispatchEvent(new CustomEvent('open-pdf-modal', {
                detail: { url: previewUrl, downloadUrl: downloadUrl }
            }));
        }

        function printPdfIframe() {
            const iframe = document.getElementById('pdfPreviewIframe');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        }
    </script>

    

    
</x-rw-app-layout>
