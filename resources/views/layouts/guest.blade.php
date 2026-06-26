<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartRT Vision') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        .input-auth { width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 14px; outline: none; background: #fff; color: #111827; transition: all 0.15s; }
        .input-auth:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .input-auth::placeholder { color: #9ca3af; }
    </style>
</head>
<body class="font-sans" style="background: #f8fafc; min-height: 100vh;">

    <div class="min-h-screen flex">

        <!-- LEFT: Branding Panel -->
        <div class="hidden lg:flex flex-col justify-between w-[460px] xl:w-[520px] flex-shrink-0 p-12 relative overflow-hidden"
             style="background: linear-gradient(145deg, #4338ca 0%, #6d28d9 50%, #7c3aed 100%);">

            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-10 translate-x-1/3 -translate-y-1/3"
                 style="background: white;"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full opacity-10 -translate-x-1/3 translate-y-1/3"
                 style="background: white;"></div>
            <div class="absolute top-1/2 left-1/2 w-96 h-96 rounded-full opacity-5 -translate-x-1/2 -translate-y-1/2"
                 style="background: white;"></div>

            <!-- Logo -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/30 overflow-hidden">
                    <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                </div>
                <span class="text-white font-extrabold text-lg tracking-tight">SmartRT Vision</span>
            </div>

            <!-- Center content -->
            <div class="relative z-10 flex flex-col gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white leading-tight">
                        Kelola Data<br>Warga RT<br>
                        <span class="text-indigo-200">dengan AI</span>
                    </h1>
                    <p class="text-indigo-200 mt-4 text-sm leading-relaxed">
                        Upload foto Kartu Keluarga dan biarkan AI membaca, mengekstrak, serta menyimpan data warga secara otomatis dan akurat.
                    </p>
                </div>

                <!-- Features -->
                <div class="space-y-3">
                    @foreach([
                        ['Ekstraksi AI Otomatis', 'Foto KK langsung dibaca oleh Google Gemini'],
                        ['Validasi NIK Cerdas', 'Deteksi otomatis ketidaksesuaian data'],
                        ['Ekspor Excel & PDF', 'Laporan rekap siap cetak kapan saja'],
                    ] as [$title, $desc])
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-semibold">{{ $title }}</p>
                            <p class="text-indigo-300 text-xs">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/10">
                    @foreach([['AI', 'Powered by Gemini'], ['Cepat', 'Otomasi Ekstraksi'], ['Aman', 'Privasi Data']] as $s)
                    <div>
                        <p class="text-xl font-black text-white truncate">{{ $s[0] }}</p>
                        <p class="text-[11px] text-indigo-300 mt-0.5 truncate">{{ $s[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10">
                <p class="text-indigo-200 text-[11px] font-medium">&copy; {{ date('Y') }} <strong>PT. Sekawan Putra Pratama</strong>.</p>
                <p class="text-indigo-300/80 text-[10px] mt-0.5">Hak Cipta Dilindungi Undang-Undang.</p>
            </div>
        </div>

        <!-- RIGHT: Form Panel -->
        <div class="flex-1 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-[380px]">

                <!-- Mobile logo -->
                <div class="flex items-center gap-2.5 mb-8 lg:hidden">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="font-extrabold text-gray-900">SmartRT Vision</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('invalid', function(e) {
                    if (this.validity.valueMissing) {
                        this.setCustomValidity('Mohon isi kolom ini.');
                    } else if (this.validity.typeMismatch && this.type === 'email') {
                        this.setCustomValidity('Format email tidak valid (harus mengandung @).');
                    } else {
                        this.setCustomValidity('Isian tidak valid.');
                    }
                });
                input.addEventListener('input', function(e) {
                    this.setCustomValidity('');
                });
            });
        });
    </script>
</body>
</html>
