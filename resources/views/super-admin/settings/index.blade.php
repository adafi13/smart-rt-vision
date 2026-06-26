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

            <!-- General Settings -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Informasi Aplikasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label">Nama Aplikasi</label>
                        <input type="text" name="settings[general][app_name]" value="{{ $settings['general']['app_name'] ?? config('app.name') }}" class="input-field" required>
                    </div>
                    <div>
                        <label class="label">Email Support</label>
                        <input type="email" name="settings[general][support_email]" value="{{ $settings['general']['support_email'] ?? 'support@kakaai.id' }}" class="input-field" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">Alamat Kantor</label>
                        <textarea name="settings[general][address]" rows="2" class="input-field">{{ $settings['general']['address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Trial Policies -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Kebijakan Free Trial</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label">Masa Trial Default (Hari)</label>
                        <input type="number" name="settings[trial][duration_days]" value="{{ $settings['trial']['duration_days'] ?? 14 }}" class="input-field" required min="1">
                        <p class="text-xs text-gray-500 mt-1">Lama waktu tenant dapat mencoba gratis.</p>
                    </div>
                    <div>
                        <label class="label">Maksimal Upload KK (Trial)</label>
                        <input type="number" name="settings[trial][max_kk]" value="{{ $settings['trial']['max_kk'] ?? 20 }}" class="input-field" required min="1">
                        <p class="text-xs text-gray-500 mt-1">Batas kuota KK saat masa trial.</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">Pesan Expired Trial</label>
                        <textarea name="settings[trial][expired_message]" rows="2" class="input-field">{{ $settings['trial']['expired_message'] ?? 'Masa percobaan Anda telah berakhir. Silakan pilih paket langganan untuk melanjutkan.' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl">Simpan Pengaturan Utama</button>
            </div>
        </form>

        <hr class="border-gray-200">

        <!-- Global Announcement — Pindah ke halaman sendiri -->
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 shadow-sm p-6 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Pengumuman Global (Broadcast)</p>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola, buat, dan jadwalkan pengumuman untuk seluruh Mitra RT aktif.</p>
                </div>
            </div>
            <a href="{{ route('super-admin.announcements.index') }}" class="btn-primary flex-shrink-0 px-4 py-2 rounded-xl text-sm inline-flex items-center gap-2">
                Kelola Pengumuman
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- Maintenance Mode -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-rose-50 rounded-bl-full pointer-events-none"></div>
            <h2 class="text-base font-bold text-rose-900 mb-2">Mode Perbaikan (Maintenance)</h2>
            <p class="text-sm text-gray-600 mb-4">Gunakan fitur ini jika Anda sedang melakukan pembaruan sistem besar-besaran. Semua pengguna (selain Super Admin) tidak akan bisa mengakses SmartRT Vision.</p>
            
            <form action="{{ route('super-admin.settings.maintenance') }}" method="POST">
                @csrf
                @if($isMaintenance)
                <div class="flex items-center gap-4 bg-rose-50 p-4 rounded-xl">
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                    </span>
                    <p class="text-sm font-bold text-rose-700 flex-1">Sistem sedang dalam Mode Perbaikan!</p>
                    <button type="submit" class="px-5 py-2 rounded-xl text-sm font-bold bg-white text-emerald-600 hover:bg-emerald-50 border border-emerald-200 shadow-sm transition-colors">Normalkan Sistem</button>
                </div>
                @else
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-emerald-600 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sistem Berjalan Normal
                    </p>
                    <button type="submit" onclick="return confirm('Yakin ingin mengaktifkan mode perbaikan? Semua RT akan terputus aksesnya.')" class="px-5 py-2 rounded-xl text-sm font-bold bg-rose-100 text-rose-700 hover:bg-rose-200 transition-colors">Aktifkan Maintenance</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</x-super-admin-layout>
