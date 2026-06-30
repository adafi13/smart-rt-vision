

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (document.getElementById('hero3d-canvas')) {
    import('./hero3d.js').then((m) => m.initHero3D());
}

function runOnReady(fn) {
    if (document.readyState !== 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

// ================================================================
// SISTEM VALIDASI CUSTOM - Tidak ada popup jelek browser
// ================================================================
runOnReady(() => {
    // 1. Matikan semua popup validasi bawaan browser pada semua form
    document.querySelectorAll('form').forEach(form => {
        form.setAttribute('novalidate', 'true');
    });

    // 2. Custom inline validation on submit
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName !== 'FORM') return;

        let hasError = false;

        // Bersihkan semua pesan error sebelumnya
        form.querySelectorAll('.custom-field-error').forEach(el => el.remove());
        form.querySelectorAll('.custom-input-error').forEach(el => {
            el.classList.remove('custom-input-error');
        });

        const fields = form.querySelectorAll('input[required], select[required], textarea[required]');

        fields.forEach(field => {
            // Skip hidden fields
            if (field.type === 'hidden') return;
            // Skip file input that has no value (let server handle)
            if (field.type === 'file') {
                if (!field.files || field.files.length === 0) {
                    showFieldError(field, 'Harap pilih file terlebih dahulu.');
                    hasError = true;
                }
                return;
            }

            const val = field.value.trim();

            if (!val) {
                showFieldError(field, getErrorMessage(field));
                hasError = true;
            } else if (field.type === 'email' && !isValidEmail(val)) {
                showFieldError(field, 'Format email tidak valid.');
                hasError = true;
            } else if (field.hasAttribute('minlength') && val.length < parseInt(field.getAttribute('minlength'))) {
                showFieldError(field, `Minimal ${field.getAttribute('minlength')} karakter.`);
                hasError = true;
            } else if (field.hasAttribute('pattern') && !(new RegExp('^' + field.getAttribute('pattern') + '$').test(val))) {
                showFieldError(field, field.getAttribute('title') || 'Format tidak sesuai.');
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault();
            // Scroll ke error pertama
            const firstError = form.querySelector('.custom-field-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }, true);

    // Hapus error saat user mulai mengisi field
    document.addEventListener('input', function(e) {
        const field = e.target;
        if (!field.tagName || !['INPUT','SELECT','TEXTAREA'].includes(field.tagName)) return;
        const errEl = field.parentElement && field.parentElement.querySelector('.custom-field-error');
        if (errEl) {
            errEl.remove();
            field.classList.remove('custom-input-error');
        }
    }, true);

    document.addEventListener('change', function(e) {
        const field = e.target;
        if (!field.tagName || !['INPUT','SELECT','TEXTAREA'].includes(field.tagName)) return;
        const errEl = field.parentElement && field.parentElement.querySelector('.custom-field-error');
        if (errEl) {
            errEl.remove();
            field.classList.remove('custom-input-error');
        }
    }, true);
});

function showFieldError(field, message) {
    field.classList.add('custom-input-error');
    const err = document.createElement('p');
    err.className = 'custom-field-error';
    err.style.cssText = 'color:#dc2626;font-size:0.72rem;margin-top:4px;font-weight:600;display:flex;align-items:center;gap:4px;animation:fadeInDown 0.2s ease;';
    err.innerHTML = `<svg style="width:12px;height:12px;flex-shrink:0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>${message}`;
    field.parentElement.appendChild(err);
}

function getErrorMessage(field) {
    const label = field.closest('div, td, li')?.querySelector('label');
    const labelText = label?.textContent?.replace('*', '').trim();
    if (labelText) return `${labelText} wajib diisi.`;
    const placeholder = field.getAttribute('placeholder');
    if (placeholder) return `Kolom ini wajib diisi.`;
    return 'Kolom ini wajib diisi.';
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// ================================================================
// GLOBAL SWEETALERT2 OVERRIDE UNTUK ONSUBMIT/ONCLICK CONFIRM
// ================================================================
runOnReady(() => {
    // Intercept all forms with onsubmit containing 'confirm'
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        const onsubmitStr = form.getAttribute('onsubmit');
        const match = onsubmitStr.match(/confirm\(['"](.*?)['"]\)/);
        if (match) {
            const message = match[1];
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#e11d48',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-sm border border-gray-100',
                            title: 'font-bold text-gray-900',
                            confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-sm',
                            cancelButton: 'rounded-xl font-bold px-5 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (window.showGlobalLoading) window.showGlobalLoading('Memproses...', 'Mohon tunggu, sedang memproses permintaan...');
                            form.submit();
                        }
                    });
                } else {
                    if (confirm(message)) {
                        if (window.showGlobalLoading) window.showGlobalLoading('Memproses...', 'Mohon tunggu, sedang memproses permintaan...');
                        form.submit();
                    }
                }
            });
        }
    });

    // Intercept all buttons/links with onclick containing 'confirm'
    document.querySelectorAll('[onclick*="confirm"]').forEach(el => {
        const onclickStr = el.getAttribute('onclick');
        const match = onclickStr.match(/confirm\(['"](.*?)['"]\)/);
        if (match) {
            const message = match[1];
            el.removeAttribute('onclick');
            el.addEventListener('click', function(e) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#e11d48',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-sm border border-gray-100',
                            title: 'font-bold text-gray-900',
                            confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-sm',
                            cancelButton: 'rounded-xl font-bold px-5 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (window.showGlobalLoading) window.showGlobalLoading('Memproses...', 'Mohon tunggu, sedang memproses permintaan...');
                            if (el.tagName === 'A') {
                                window.location.href = el.href;
                            } else if (el.tagName === 'BUTTON' && el.type === 'submit') {
                                el.closest('form').submit();
                            } else if (el.tagName === 'BUTTON') {
                                if(el.closest('form')) el.closest('form').submit();
                            }
                        }
                    });
                } else {
                    if (confirm(message)) {
                        if (window.showGlobalLoading) window.showGlobalLoading('Memproses...', 'Mohon tunggu, sedang memproses permintaan...');
                        if(el.tagName === 'A') window.location.href = el.href;
                        if(el.closest('form')) el.closest('form').submit();
                    }
                }
            });
        }
    });
});

// ================================================================
// SISTEM LOADING BAR GLOBAL & PENJAGA KONEKSI (ANTI DATA HILANG)
// ================================================================
runOnReady(() => {
    // 1. Buat elemen loading overlay dinamis
    const overlay = document.createElement('div');
    overlay.id = 'global-loading-overlay';
    overlay.className = 'hidden fixed inset-0 z-[9999] bg-slate-950/65 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300 opacity-0';
    overlay.innerHTML = `
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 sm:p-8 shadow-2xl border border-slate-100/80 flex flex-col items-center transition-all duration-300 transform scale-95">
            <div class="relative w-16 h-16 mb-5 flex items-center justify-center">
                <!-- Glowing ring -->
                <div class="absolute inset-0 rounded-full border-4 border-indigo-50/50"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                <svg class="w-6 h-6 text-indigo-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            
            <h3 id="global-loading-title" class="text-base font-black text-slate-900 mb-1 text-center">Memproses Permintaan</h3>
            <p id="global-loading-subtitle" class="text-[11px] text-slate-500 text-center font-medium">Mohon tunggu, sedang mengirimkan data aman ke server...</p>
            
            <!-- Progress Line Indicator -->
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-5 relative">
                <div class="absolute top-0 bottom-0 left-0 bg-gradient-to-r from-indigo-500 to-purple-600 w-1/3 rounded-full animate-progress-slide"></div>
            </div>
        </div>
    `;
    
    // Tambahkan CSS Keyframe untuk progress slide ke document
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes progress-slide {
            0% { left: -35%; width: 35%; }
            100% { left: 100%; width: 35%; }
        }
        .animate-progress-slide {
            animation: progress-slide 1.5s infinite linear;
        }
    `;
    document.head.appendChild(style);
    console.log("SmartRT Vision Global Loading System initialized.");

    let activeTimeout = null;

    // 2. Interseptor submit global
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName !== 'FORM') return;

        // Cek apakah event dicegah oleh validasi custom
        if (e.defaultPrevented) return;
        if (form.hasAttribute('data-no-global-loader')) return;

        // Periksa koneksi internet (Offline protection)
        if (!navigator.onLine) {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Koneksi Terputus!',
                    text: 'Gagal mengirim data karena Anda sedang offline. Silakan periksa koneksi internet Anda dan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#4f46e5'
                });
            } else {
                alert('Koneksi Terputus! Silakan periksa koneksi internet Anda.');
            }
            return;
        }

        // Tampilkan loading screen
        showGlobalLoading('Mengirim Data...', 'Sedang menyimpan data aman ke server...');

        // Disable submit button untuk mencegah double submit
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
        });

        // Set timeout pengaman jika koneksi los saat pengiriman (18 detik)
        if (activeTimeout) clearTimeout(activeTimeout);
        activeTimeout = setTimeout(() => {
            hideGlobalLoading();
            submitButtons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            });
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Respons Lambat!',
                    text: 'Server membutuhkan waktu terlalu lama untuk merespons. Silakan periksa koneksi internet Anda dan coba kirim ulang.',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
            }
        }, 18000);
    });

    // 3. Detektor perubahan koneksi (los signal warning)
    window.addEventListener('offline', () => {
        if (typeof Swal !== 'undefined') {
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            toast.fire({
                icon: 'error',
                title: 'Koneksi internet Anda terputus!'
            });
        }
    });

    window.addEventListener('online', () => {
        if (typeof Swal !== 'undefined') {
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            toast.fire({
                icon: 'success',
                title: 'Koneksi internet kembali terhubung.'
            });
        }
    });

    function showGlobalLoading(title, subtitle) {
        if (document.body && !document.body.contains(overlay)) {
            document.body.appendChild(overlay);
        }
        if (title) document.getElementById('global-loading-title').textContent = title;
        if (subtitle) document.getElementById('global-loading-subtitle').textContent = subtitle;
        
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.add('opacity-100');
            overlay.querySelector('.transform').classList.remove('scale-95');
            overlay.querySelector('.transform').classList.add('scale-100');
        }, 10);
    }

    function hideGlobalLoading() {
        overlay.classList.remove('opacity-100');
        overlay.querySelector('.transform').classList.add('scale-95');
        overlay.querySelector('.transform').classList.remove('scale-100');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }
    
    // Export globally
    window.showGlobalLoading = showGlobalLoading;
    window.hideGlobalLoading = hideGlobalLoading;
});
