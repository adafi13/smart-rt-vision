<x-app-layout title="Upload Kartu Keluarga">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-4 border-b border-gray-100 pb-4">
            <a href="{{ route('kk.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors shadow-sm" title="Kembali">
                <svg class="w-5 h-5 pr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">Ekstraksi Otomatis KK (AI)</h1>
                <p class="text-[11px] font-semibold text-gray-500 mt-0.5">Sistem cerdas akan memindai dan membaca isi Kartu Keluarga Anda</p>
            </div>
        </div>

        @if(session('error'))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium shadow-sm animate-shake">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div>
                    <h4 class="font-bold mb-0.5 text-rose-900">Gagal Mengunggah</h4>
                    <p class="text-rose-700 text-xs">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <!-- Info tip -->
            <div class="bg-indigo-600 p-6 text-white flex flex-col sm:flex-row items-center sm:items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="text-center sm:text-left">
                    <h4 class="text-base font-bold mb-1">Cerdas & Instan!</h4>
                    <p class="text-indigo-100 text-[11px] leading-relaxed max-w-lg">
                        Gunakan kamera Anda atau pilih foto Kartu Keluarga. Pastikan teks NIK dan Nama terlihat jelas, tidak blur, dan berada di tempat terang agar AI kami dapat mengekstrak data 100% akurat.
                    </p>
                </div>
            </div>
            
            <div class="bg-amber-50 border-b border-amber-100 px-6 py-3 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-xs font-bold text-amber-800">
                    Sistem otomatis menolak foto yang BUKAN Kartu Keluarga, terpotong, atau terlalu buram!
                </p>
            </div>

            <form action="{{ route('kk.extract') }}" method="POST" enctype="multipart/form-data" id="upload-form" class="p-6 sm:p-8">
                @csrf

                <!-- Drop zone -->
                <div id="drop-zone"
                     class="group relative rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50/50 hover:bg-indigo-50 hover:border-indigo-400 p-8 sm:p-12 text-center cursor-pointer transition-all duration-300 mb-6"
                     onclick="document.getElementById('foto_kk').click()">
                     
                    <!-- Decorative corner glow -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors"></div>

                    <div id="placeholder" class="space-y-4 relative z-10">
                        <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center mx-auto shadow-sm border border-gray-100 group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Klik atau Sentuh di sini</p>
                            <p class="text-[11px] font-medium text-gray-500 mt-1">Pilih dari Galeri atau jepret langsung dengan Kamera (Max. 8MB)</p>
                        </div>
                    </div>

                    <div id="preview-wrap" class="hidden relative z-10 w-full">
                        <div class="relative inline-block max-w-full">
                            <img id="preview" src="#" alt="Preview" class="max-h-72 mx-auto rounded-xl object-contain border-4 border-white shadow-lg mb-4">
                            <!-- Scanning Overlay Animation (Hidden by default, shown on submit) -->
                            <div id="scan-overlay" class="absolute inset-0 bg-indigo-900/20 backdrop-blur-[1px] rounded-xl hidden items-center justify-center overflow-hidden">
                                <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-400 shadow-[0_0_15px_3px_rgba(99,102,241,0.8)] animate-scan"></div>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-[11px] font-bold text-gray-700 shadow-sm truncate max-w-[250px]">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span id="file-name" class="truncate"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <input type="file" name="foto_kk" id="foto_kk" accept="image/*" class="hidden" required>

                <button type="submit" id="submit-btn" class="w-full relative overflow-hidden group flex items-center justify-center gap-2 px-6 py-4 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-xl shadow-lg transition-all hover:shadow-xl hover:-translate-y-0.5">
                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="relative z-10" id="btn-text">Kirim & Mulai Ekstraksi AI</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('foto_kk').addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                // Validation: Must be image
                if (!file.type.startsWith('image/')) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Format Salah!', 'File yang Anda pilih bukan gambar. Silakan unggah foto.', 'error');
                    } else {
                        alert('Format Salah! File harus berupa gambar.');
                    }
                    this.value = '';
                    return;
                }

                // Validation: Max 8MB
                if (file.size > 8 * 1024 * 1024) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Ukuran Terlalu Besar!', 'Ukuran foto maksimal adalah 8MB. Silakan kompres atau pilih foto lain.', 'error');
                    } else {
                        alert('Ukuran Terlalu Besar! Ukuran foto maksimal adalah 8MB.');
                    }
                    this.value = '';
                    return;
                }

                document.getElementById('preview').src = URL.createObjectURL(file);
                document.getElementById('file-name').textContent = file.name;
                
                // Hide placeholder, show preview
                document.getElementById('placeholder').classList.add('hidden');
                document.getElementById('preview-wrap').classList.remove('hidden');
                
                // Change border styling
                const dropZone = document.getElementById('drop-zone');
                dropZone.classList.remove('border-gray-300', 'border-dashed', 'bg-gray-50/50');
                dropZone.classList.add('border-indigo-400', 'border-solid', 'bg-indigo-50/50');
                
                // Reset button if previously disabled
                const btn = document.getElementById('submit-btn');
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            }
        });

        document.getElementById('upload-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const span = document.getElementById('btn-text');
            const scanOverlay = document.getElementById('scan-overlay');
            
            // Show scanning animation
            scanOverlay.classList.remove('hidden');
            scanOverlay.classList.add('flex');

            // Button loading state
            btn.querySelector('svg').outerHTML = '<svg class="w-5 h-5 relative z-10 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
            span.textContent = 'AI sedang menganalisis gambar...';
            btn.disabled = true; 
            btn.classList.add('opacity-90', 'cursor-not-allowed', 'pointer-events-none');
        });
    </script>

    <style>
        .animate-shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        
        .animate-scan {
            animation: scan 2s linear infinite alternate;
        }
        @keyframes scan {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>
</x-app-layout>
