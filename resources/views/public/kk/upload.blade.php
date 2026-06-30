<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Pendaftaran Kartu Keluarga mandiri secara online untuk RT.">
    <title>Daftar KK Mandiri · {{ $tenant->name ?? 'SmartRT Vision' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { font-family: 'Outfit', sans-serif; box-sizing: border-box; }
        body { background: #f8f9fc; color: #0f172a; }
        .glass-dark { background: rgba(15,15,25,0.85); backdrop-filter: blur(14px) saturate(160%); -webkit-backdrop-filter: blur(14px) saturate(160%); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-12 relative overflow-x-hidden">
    <!-- Ambient glowing backgrounds -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-violet-500/5 rounded-full blur-3xl pointer-events-none"></div>

    @include('partials.public-nav')

    @include('partials.public-page-header', [
        'title' => 'Pendaftaran KK Mandiri',
        'subtitle' => 'Scan Kartu Keluarga Anda menggunakan AI untuk pendaftaran otomatis ke RT.',
    ])

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-12 relative z-10">
        
        <!-- Premium Stepper Progress UI -->
        <div class="max-w-md mx-auto mb-12">
            <div class="flex items-center justify-between relative">
                <!-- Line background -->
                <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-200/80 z-0"></div>
                <!-- Line active progress -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-indigo-600 z-0" style="width: 15%;"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-indigo-100 transition-all duration-300">1</div>
                    <span class="text-[11px] font-bold text-slate-800">Unggah KK</span>
                </div>
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-bold text-sm shadow-sm transition-all duration-300">2</div>
                    <span class="text-[11px] font-semibold text-slate-400">Verifikasi</span>
                </div>
                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-400 flex items-center justify-center font-bold text-sm shadow-sm transition-all duration-300">3</div>
                    <span class="text-[11px] font-semibold text-slate-400">Selesai</span>
                </div>
            </div>
        </div>
        
        @if(session('error'))
            <div class="flex items-start gap-3.5 p-4.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium shadow-sm mb-6 animate-pulse">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div>
                    <h4 class="font-bold mb-0.5 text-rose-900">Gagal Mengunggah</h4>
                    <p class="text-rose-700 text-xs">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] border border-slate-200/80 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] overflow-hidden transition-all duration-300">
            <!-- Info tip with radial gradient & dark pattern -->
            <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 p-6 sm:p-8 text-white flex flex-col sm:flex-row items-center sm:items-start gap-5 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-xl"></div>
                <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 backdrop-blur-md border border-white/10 shadow-inner">
                    <svg class="w-6 h-6 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="text-center sm:text-left relative z-10">
                    <h4 class="text-lg font-extrabold mb-1 tracking-tight">Cepat, Aman & Praktis!</h4>
                    <p class="text-indigo-100/90 text-xs sm:text-[13px] leading-relaxed max-w-xl font-medium">
                        Cukup foto Kartu Keluarga Anda (atau upload berkas PDF resmi dari Dukcapil). Teknologi AI cerdas kami akan mengekstrak nama dan seluruh data anggota keluarga secara otomatis dalam hitungan detik.
                    </p>
                </div>
            </div>
            
            <div class="bg-amber-50 border-b border-amber-100 px-6 py-3.5 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-xs font-bold text-amber-800">
                    Sistem otomatis menolak foto yang BUKAN Kartu Keluarga, terpotong, atau terlalu buram!
                </p>
            </div>
            
            <form action="{{ route('warga.kk.extract', ['tenant' => $tenant->slug]) }}" method="POST" enctype="multipart/form-data" id="upload-form" class="p-6 sm:p-10" data-no-global-loader>
                @csrf

                <!-- Drop zone -->
                <div id="drop-zone"
                     class="group relative rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50/50 hover:bg-indigo-50/20 hover:border-indigo-400 p-8 sm:p-14 text-center cursor-pointer transition-all duration-300 mb-8"
                     onclick="document.getElementById('foto_kk').click()">
                     
                    <!-- Decorative ambient elements -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>

                    <div id="placeholder" class="space-y-5 relative z-10">
                        <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center mx-auto shadow-sm border border-slate-100 group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-extrabold text-slate-800">Seret & Lepas atau Klik di sini</p>
                            <p class="text-[12px] font-semibold text-slate-400 mt-1.5 max-w-xs mx-auto">Pilih berkas Foto (JPG/PNG) atau berkas PDF Kartu Keluarga (Maksimal 8MB)</p>
                        </div>
                        <div class="pt-2">
                            <button type="button" onclick="event.stopPropagation(); startCamera();" class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl transition-all shadow-lg shadow-indigo-100 hover:shadow-indigo-200/50 hover:scale-105 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ambil Foto via Kamera
                            </button>
                        </div>
                    </div>

                    <div id="preview-wrap" class="hidden relative z-10 w-full">
                        <div class="relative inline-block max-w-full">
                            <img id="preview" src="#" alt="Preview" class="max-h-80 mx-auto rounded-2xl object-contain border-4 border-white shadow-xl mb-5">
                            
                            <!-- PDF Preview Placeholder -->
                            <div id="pdf-preview" class="hidden py-8 text-center">
                                <div class="w-20 h-20 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-center mx-auto shadow-sm text-rose-500 mb-3">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h1a2 2 0 012 2v2a2 2 0 01-2 2H9m3 0h3m-3-4h3"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-500 mt-2">Dokumen PDF Terdeteksi</p>
                            </div>

                            <!-- Scanning Overlay Animation -->
                            <div id="scan-overlay" class="absolute inset-0 bg-indigo-900/20 backdrop-blur-[1px] rounded-2xl hidden items-center justify-center overflow-hidden">
                                <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-400 shadow-[0_0_15px_3px_rgba(99,102,241,0.8)] animate-scan"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-center gap-2.5">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-600 shadow-sm truncate max-w-[255px]">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span id="file-name" class="truncate font-semibold"></span>
                            </span>
                            <button type="button" id="clear-btn" class="p-2 rounded-xl bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-600 shadow-sm transition-colors" title="Batal / Hapus Foto" onclick="event.stopPropagation(); clearImage();">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <input type="file" name="foto_kk" id="foto_kk" accept="image/*,application/pdf" class="hidden" required>

                <button type="submit" id="submit-btn" class="w-full relative overflow-hidden group flex items-center justify-center gap-2.5 px-6 py-4.5 bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-sm font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:shadow-indigo-200/50 transition-all duration-300 hover:-translate-y-0.5 opacity-75 cursor-not-allowed pointer-events-none" disabled>
                    <span class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="relative z-10" id="btn-text">Kirim & Mulai Ekstraksi AI</span>
                </button>
            </form>
        </div>
    </main>

    @include('partials.public-footer')

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('foto_kk');

        // Setup Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('bg-indigo-50/50', 'border-indigo-400', 'scale-[1.01]'));
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('bg-indigo-50/50', 'border-indigo-400', 'scale-[1.01]'));
        });

        dropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            if (dt.files && dt.files.length > 0) {
                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });

        function clearImage() {
            fileInput.value = '';
            document.getElementById('preview').src = '#';
            document.getElementById('file-name').textContent = '';
            
            // Show placeholder, hide preview
            document.getElementById('placeholder').classList.remove('hidden');
            document.getElementById('preview-wrap').classList.add('hidden');
            document.getElementById('pdf-preview').classList.add('hidden');
            document.getElementById('preview').classList.remove('hidden');
            
            // Revert border styling
            dropZone.classList.add('border-slate-200', 'border-dashed', 'bg-slate-50/50');
            dropZone.classList.remove('border-indigo-400', 'border-solid', 'bg-indigo-50/50');
            
            // Disable button
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
        }

        fileInput.addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                const isImage = file.type.startsWith('image/');
                const isPdf = file.type === 'application/pdf';
                if (!isImage && !isPdf) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Format Salah!', 'Format file tidak didukung. Silakan unggah Foto KK atau file PDF KK.', 'error');
                    } else {
                        alert('Format Salah! File harus berupa gambar atau PDF.');
                    }
                    this.value = '';
                    return;
                }

                if (file.size > 8 * 1024 * 1024) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Ukuran Terlalu Besar!', 'Ukuran berkas maksimal adalah 8MB.', 'error');
                    } else {
                        alert('Ukuran berkas maksimal adalah 8MB.');
                    }
                    this.value = '';
                    return;
                }

                document.getElementById('file-name').textContent = file.name;
                
                if (isPdf) {
                    document.getElementById('preview').classList.add('hidden');
                    document.getElementById('pdf-preview').classList.remove('hidden');
                } else {
                    document.getElementById('preview').classList.remove('hidden');
                    document.getElementById('pdf-preview').classList.add('hidden');
                    document.getElementById('preview').src = URL.createObjectURL(file);
                }
                
                document.getElementById('placeholder').classList.add('hidden');
                document.getElementById('preview-wrap').classList.remove('hidden');
                
                dropZone.classList.remove('border-slate-200', 'border-dashed', 'bg-slate-50/50');
                dropZone.classList.add('border-indigo-400', 'border-solid', 'bg-indigo-50/50');
                
                const btn = document.getElementById('submit-btn');
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            }
        });

        // Camera Logic
        let activeStream = null;

        function startCamera() {
            const modal = document.getElementById('camera-modal');
            const video = document.getElementById('webcam');
            
            modal.classList.remove('hidden');
            
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => {
                    activeStream = stream;
                    video.srcObject = stream;
                })
                .catch(err => {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(stream => {
                            activeStream = stream;
                            video.srcObject = stream;
                        })
                        .catch(e => {
                            Swal.fire('Kamera Gagal', 'Tidak dapat membuka kamera. Pastikan memberikan izin akses.', 'error');
                            modal.classList.add('hidden');
                        });
                });
        }

        function stopCamera() {
            const modal = document.getElementById('camera-modal');
            const video = document.getElementById('webcam');
            
            if (activeStream) {
                activeStream.getTracks().forEach(track => track.stop());
                activeStream = null;
            }
            video.srcObject = null;
            modal.classList.add('hidden');
        }

        function captureFrame() {
            const video = document.getElementById('webcam');
            const canvas = document.getElementById('capture-canvas');
            const ctx = canvas.getContext('2d');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            canvas.toBlob(blob => {
                const file = new File([blob], "capture_kk.jpg", { type: "image/jpeg" });
                const container = new DataTransfer();
                container.items.add(file);
                fileInput.files = container.files;
                
                fileInput.dispatchEvent(new Event('change'));
                stopCamera();
            }, 'image/jpeg', 0.95);
        }

        // Stepper Progress Logger
        document.getElementById('upload-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const scanOverlay = document.getElementById('scan-overlay');
            const aiLoading = document.getElementById('ai-loading-overlay');
            
            scanOverlay.classList.remove('hidden');
            scanOverlay.classList.add('flex');
            aiLoading.classList.remove('hidden');

            updateStep('step-upload', 'done', 'Berkas berhasil diunggah.');

            setTimeout(() => {
                updateStep('step-upload', 'completed');
                updateStep('step-connect', 'done', 'Koneksi terjalin ke Gemini AI.');
            }, 1200);

            setTimeout(() => {
                updateStep('step-connect', 'completed');
                updateStep('step-ocr', 'done', 'Mengekstrak data Kepala Keluarga & Anggota...');
            }, 3200);

            setTimeout(() => {
                updateStep('step-ocr', 'completed');
                updateStep('step-validate', 'done', 'Memvalidasi data kependudukan...');
            }, 7000);
            
            btn.disabled = true;
            btn.classList.add('opacity-90', 'cursor-not-allowed', 'pointer-events-none');
        });

        function updateStep(elementId, status, customText = null) {
            const container = document.getElementById(elementId);
            if (!container) return;
            
            const icon = container.querySelector('.step-icon');
            const text = container.querySelector('.step-text');
            
            if (customText) text.textContent = customText;

            if (status === 'done') {
                container.className = "flex items-center gap-3 text-indigo-700 animate-pulse";
                icon.className = "step-icon w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold";
                icon.innerHTML = '<svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
            } else if (status === 'completed') {
                container.className = "flex items-center gap-3 text-emerald-600";
                icon.className = "step-icon w-6 h-6 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold";
                icon.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
            }
        }
    </script>
    <!-- Webcam Capture Overlay -->
    <div id="camera-modal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 flex flex-col">
            <div class="p-5 border-b border-white/10 flex justify-between items-center bg-slate-950 text-white">
                <h3 class="text-xs font-bold tracking-wider uppercase">Kamera Pemindai KK</h3>
                <button type="button" onclick="stopCamera();" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="relative flex-1 bg-black flex items-center justify-center overflow-hidden min-h-[300px] sm:min-h-[400px]">
                <video id="webcam" autoplay playsinline class="w-full h-full object-contain"></video>
                <div class="absolute inset-5 border-2 border-dashed border-indigo-400/50 rounded-3xl pointer-events-none flex items-center justify-center">
                    <div class="text-white/60 text-[10px] font-bold uppercase tracking-widest bg-black/50 px-4 py-2 rounded-full backdrop-blur-md border border-white/5">Posisikan Dokumen KK di dalam kotak</div>
                </div>
            </div>
            
            <div class="p-5 bg-slate-950 flex items-center justify-between gap-4">
                <button type="button" onclick="stopCamera();" class="px-5 py-2.5 text-xs font-bold text-slate-400 hover:text-white uppercase tracking-wider transition-colors">Batal</button>
                <button type="button" onclick="captureFrame();" class="w-16 h-16 rounded-full bg-white hover:bg-slate-100 flex items-center justify-center shadow-lg border-4 border-slate-900 transition-all hover:scale-105 active:scale-95">
                    <div class="w-10 h-10 rounded-full bg-indigo-600"></div>
                </button>
                <div class="w-12"></div>
            </div>
        </div>
    </div>
    
    <canvas id="capture-canvas" class="hidden"></canvas>

    <!-- AI Stepper Loading Overlay -->
    <div id="ai-loading-overlay" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-sm p-8 shadow-2xl border border-slate-100 flex flex-col items-center">
            <div class="relative w-16 h-16 mb-6 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-50"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                <svg class="w-6 h-6 text-indigo-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            
            <h3 class="text-base font-black text-slate-900 mb-1 text-center">Pemindaian AI Berlangsung</h3>
            <p class="text-[11px] text-slate-400 text-center mb-6 max-w-xs font-bold">Mohon tunggu, AI sedang mengekstrak data KK Anda.</p>
            
            <div class="w-full space-y-4 text-left border-t border-slate-100 pt-5">
                <div id="step-upload" class="flex items-center gap-3 text-slate-400 transition-all duration-300">
                    <span class="step-icon w-6 h-6 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">1</span>
                    <span class="step-text text-xs font-semibold">Mengunggah file dokumen...</span>
                </div>
                <div id="step-connect" class="flex items-center gap-3 text-slate-400 transition-all duration-300">
                    <span class="step-icon w-6 h-6 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">2</span>
                    <span class="step-text text-xs font-semibold">Menghubungkan ke Gemini AI...</span>
                </div>
                <div id="step-ocr" class="flex items-center gap-3 text-slate-400 transition-all duration-300">
                    <span class="step-icon w-6 h-6 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">3</span>
                    <span class="step-text text-xs font-semibold">Mengekstrak NIK & Anggota Keluarga...</span>
                </div>
                <div id="step-validate" class="flex items-center gap-3 text-slate-400 transition-all duration-300">
                    <span class="step-icon w-6 h-6 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold">4</span>
                    <span class="step-text text-xs font-semibold">Menyiapkan halaman verifikasi...</span>
                </div>
            </div>
        </div>
    </div>

    <style>
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
</body>
</html>
