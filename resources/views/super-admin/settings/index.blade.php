<x-super-admin-layout title="Pengaturan Global">
    <div class="max-w-4xl space-y-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Pengaturan Global</h1>
            <p class="text-sm text-gray-500 mt-0.5">Konfigurasi utama aplikasi dan kebijakan *trial* tenant</p>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('super-admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Finance Settings -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Pengaturan Keuangan & AI</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label">Estimasi Biaya Dasar AI / Scan (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">Rp</span>
                            <input type="number" name="settings[finance][ai_cost_per_scan]" value="{{ $settings['finance']['ai_cost_per_scan'] ?? 150 }}" class="input-field" style="padding-left: 2.75rem;" required min="0" step="1">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                            Nilai ini digunakan di halaman <b>Finance</b> untuk menghitung estimasi tagihan API OpenAI/Gemini berdasarkan total <code>ai_extractions_used</code> bulan ini.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button type="submit" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-xl">Simpan Pengaturan Utama</button>
            </div>
        </form>

        <hr class="border-gray-200">

        <!-- Global Announcement — Pindah ke halaman sendiri -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 mt-1 sm:mt-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Pengumuman Global (Broadcast)</p>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola, buat, dan jadwalkan pengumuman untuk seluruh Mitra RT aktif.</p>
                </div>
            </div>
            <a href="{{ route('super-admin.announcements.index') }}" class="btn-primary w-full sm:w-auto justify-center flex-shrink-0 px-4 py-2.5 rounded-xl text-sm inline-flex items-center gap-2">
                Kelola Pengumuman
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- Maintenance Mode -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-5 sm:p-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-rose-50 rounded-bl-full pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="text-base font-bold text-rose-900 mb-2">Mode Perbaikan (Maintenance)</h2>
                <p class="text-sm text-gray-600 mb-4 sm:mb-6">Gunakan fitur ini jika Anda sedang melakukan pembaruan sistem besar-besaran. Semua pengguna (selain Super Admin) tidak akan bisa mengakses SmartRT Vision.</p>
                
                <form action="{{ route('super-admin.settings.maintenance') }}" method="POST">
                    @csrf
                    @if($isMaintenance)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-rose-50 p-4 rounded-xl border border-rose-100">
                        <div class="flex items-center gap-3 flex-1">
                            <span class="flex h-3 w-3 relative flex-shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                            </span>
                            <p class="text-sm font-bold text-rose-700">Sistem sedang dalam Mode Perbaikan!</p>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-bold bg-white text-emerald-600 hover:bg-emerald-50 border border-emerald-200 shadow-sm transition-colors text-center">Normalkan Sistem</button>
                    </div>
                    @else
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-sm font-medium text-emerald-600 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Sistem Berjalan Normal
                        </p>
                        <button type="submit" onclick="return confirm('Yakin ingin mengaktifkan mode perbaikan? Semua RT akan terputus aksesnya.')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-bold bg-rose-100 text-rose-700 hover:bg-rose-200 transition-colors text-center">Aktifkan Maintenance</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-super-admin-layout>
