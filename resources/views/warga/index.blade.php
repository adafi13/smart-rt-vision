<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Data Warga</h1>
            <p class="text-sm text-gray-500 mt-0.5">Seluruh warga yang terdaftar dalam sistem</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('warga.index') }}" class="flex flex-col gap-4">
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari NIK atau Nama..."
                               class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm text-gray-900 placeholder-gray-400">
                    </div>

                    <!-- Gender Filter -->
                    <div class="w-full sm:w-40 relative">
                        <select name="jenis_kelamin" class="w-full py-2 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 appearance-none cursor-pointer">
                            <option value="">Semua Gender</option>
                            <option value="Laki-laki" {{ request('jenis_kelamin')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                            <option value="Perempuan" {{ request('jenis_kelamin')=='Perempuan'?'selected':'' }}>Perempuan</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="w-full sm:w-48 relative">
                        <select name="sort" class="w-full py-2 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 appearance-none cursor-pointer">
                            <option value="keluarga" {{ request('sort')=='keluarga'?'selected':'' }}>Urut per Keluarga</option>
                            <option value="nama_asc" {{ request('sort')=='nama_asc'?'selected':'' }}>Nama (A-Z)</option>
                            <option value="nama_desc" {{ request('sort')=='nama_desc'?'selected':'' }}>Nama (Z-A)</option>
                            <option value="umur_tua" {{ request('sort')=='umur_tua'?'selected':'' }}>Paling Tua</option>
                            <option value="umur_muda" {{ request('sort')=='umur_muda'?'selected':'' }}>Paling Muda</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        </div>
                    </div>

                    <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold transition-colors">Filter</button>
                </div>

                <!-- Footer area of the toolbar -->
                <div class="border-t border-gray-100 pt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                        Total: <span class="font-bold text-gray-900">{{ $members->total() }}</span> Warga
                    </div>
                    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                        <a href="{{ route('export.excel') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Excel
                        </a>
                        <a href="{{ route('export.pdf') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if($members->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama & NIK</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Demografi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Pekerjaan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Keluarga</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($members as $member)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900 block">{{ $member->nama }}</span>
                            <span class="text-xs text-gray-500 font-mono">{{ $member->nik }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                @if($member->jenis_kelamin == 'Laki-laki')
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Laki-laki
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-pink-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Perempuan
                                    </span>
                                @endif
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($member->tanggal_lahir)->age }} thn ({{ $member->agama ?? '-' }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-normal">
                            <p class="text-sm text-gray-800">{{ $member->pekerjaan ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $member->pendidikan ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wide border border-indigo-100 mb-1">
                                {{ $member->hubungan_keluarga }}
                            </span>
                            <div class="text-xs text-gray-500 font-mono">{{ $member->family->nomor_kk ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="triggerPdfPreview('{{ route('cetak_surat', ['id' => $member->id, 'preview' => 1]) }}', '{{ route('cetak_surat', $member->id) }}')" class="p-1.5 rounded-md text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors flex items-center gap-1 text-xs font-semibold" title="Cetak Surat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Surat
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
            @foreach($members as $member)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full {{ $member->jenis_kelamin == 'Laki-laki' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }} flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ substr($member->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ $member->nama }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $member->nik }}</p>
                    </div>
                    <div>
                        <span class="inline-flex px-2 py-1 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-100 whitespace-nowrap">{{ $member->hubungan_keluarga }}</span>
                    </div>
                </div>

                <div class="p-4 bg-gray-50/50 border-t border-gray-50">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide">Usia & Kelamin</span>
                            <span class="text-xs font-semibold text-gray-800">{{ \Carbon\Carbon::parse($member->tanggal_lahir)->age }} thn, {{ $member->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide">Pekerjaan</span>
                            <span class="text-xs font-semibold text-gray-800">{{ $member->pekerjaan ?? '-' }}</span>
                        </div>
                    </div>

                    <button type="button" onclick="triggerPdfPreview('{{ route('cetak_surat', ['id' => $member->id, 'preview' => 1]) }}', '{{ route('cetak_surat', $member->id) }}')" class="w-full flex items-center justify-center gap-2 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Buat Surat Pengantar
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $members->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Pencarian Tidak Ditemukan</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Tidak ada data warga yang cocok dengan kriteria pencarian atau filter Anda.</p>
        </div>
        @endif

    </div>

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
</x-app-layout>
