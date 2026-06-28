

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (document.getElementById('hero3d-canvas')) {
    import('./hero3d.js').then((m) => m.initHero3D());
}

// ================================================================
// SISTEM VALIDASI CUSTOM - Tidak ada popup jelek browser
// ================================================================
document.addEventListener('DOMContentLoaded', () => {
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
