<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syarat & Ketentuan Layanan - SmartRT Vision</title>
    <meta name="description" content="Pelajari syarat dan ketentuan penggunaan platform SmartRT Vision.">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/icon.png') }}">

    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-slate-200 py-5 sticky top-0 z-50 backdrop-blur">
        <div class="max-w-4xl mx-auto px-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-black text-xl text-slate-900">
                SmartRT Vision
            </a>
            <a href="{{ url('/') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-16">
        <header class="mb-12">
            <span class="inline-block bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-100 mb-4">Legal</span>
            <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Syarat & Ketentuan Layanan</h1>
            <p class="text-slate-500 font-medium text-lg">Harap baca dengan seksama sebelum menggunakan platform SmartRT Vision.</p>
        </header>

        <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 text-sm leading-relaxed text-slate-600 space-y-8">

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                <p class="text-[12px] font-black text-blue-700 uppercase tracking-widest mb-1">Versi 1.0.0 | Berlaku: 1 Januari 2026</p>
                <p class="text-blue-800 font-medium">
                    Dengan mengakses dan menggunakan platform <strong>SmartRT Vision</strong> yang dikelola oleh <strong>PT. Sekawan Putra Pratama</strong>, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku dalam dokumen ini.
                </p>
            </div>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">1. DEFINISI</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>"Layanan"</strong> merujuk pada platform SmartRT Vision yang mencakup sistem administrasi rukun tetangga, manajemen data warga, iuran, laporan keuangan, dan modul terkait.</li>
                    <li><strong>"Pengguna"</strong> merujuk pada pengurus RT beserta seluruh warga yang terdaftar dalam sistem.</li>
                    <li><strong>"Pengelola"</strong> merujuk pada PT. Sekawan Putra Pratama sebagai pengembang dan operator SmartRT Vision.</li>
                    <li><strong>"Data Warga"</strong> merujuk pada seluruh data yang dimasukkan ke dalam sistem, termasuk data KK, transaksi, tagihan, dan laporan warga.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">2. PENDAFTARAN & AKUN</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Pendaftaran wajib menggunakan data RT dan pengurus yang valid dan dapat diverifikasi.</li>
                    <li>Setiap lingkungan RT hanya diperbolehkan memiliki satu (1) akun utama untuk kepengurusan yang aktif.</li>
                    <li>Anda bertanggung jawab penuh atas kerahasiaan kredensial login dan seluruh aktivitas yang terjadi di bawah akun Anda.</li>
                    <li>Pengelola berhak menonaktifkan akun yang terbukti menyalahgunakan layanan tanpa pemberitahuan sebelumnya.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">3. PAKET LANGGANAN & PEMBAYARAN</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Layanan mungkin tersedia dalam beberapa skema berlangganan dengan fitur yang berbeda-beda.</li>
                    <li>Masa uji coba gratis dapat diberikan atas kebijakan Pengelola.</li>
                    <li>Pembayaran bersifat non-refundable kecuali terjadi gangguan layanan berat dari pihak Pengelola.</li>
                    <li>Kegagalan pembayaran dapat mengakibatkan pembatasan akses fitur.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">4. PENGGUNAAN YANG DILARANG</h3>
                <p>Pengguna dilarang keras melakukan hal-hal berikut:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Menggunakan SmartRT Vision untuk kegiatan ilegal, penipuan, atau pencurian data pribadi.</li>
                    <li>Mencoba meretas, merusak, atau mengakses sistem di luar hak akses yang diberikan.</li>
                    <li>Menjual kembali atau mendistribusikan akses layanan kepada pihak yang tidak berkepentingan.</li>
                    <li>Melakukan scraping atau mengekstrak data identitas warga secara masif tanpa izin.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">5. KEPEMILIKAN DATA</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Data Warga yang Anda masukkan adalah milik Anda/RT Anda sepenuhnya.</li>
                    <li>Pengelola tidak akan menjual, menyewakan, atau membagikan Data Warga kepada pihak ketiga untuk kepentingan komersial.</li>
                    <li>Pengelola berhak menggunakan data secara anonim dan teragregat untuk pengembangan fitur dan analisis sistem.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">6. BATASAN TANGGUNG JAWAB</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Pengelola tidak bertanggung jawab atas konflik internal warga atau masalah administratif yang timbul di lapangan.</li>
                    <li>Pengelola menjamin uptime layanan minimal 99.0% per bulan. Jika tidak tercapai, kompensasi akan diberikan dalam bentuk perpanjangan masa langganan.</li>
                    <li>Pengguna bertanggung jawab penuh atas kebenaran data warga yang dimasukkan.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">7. PENGHENTIAN LAYANAN</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Pengguna dapat mengakhiri penggunaan layanan kapan saja dengan menghubungi tim support.</li>
                    <li>Pengelola berhak mengakhiri layanan kepada Pengguna yang terbukti melanggar Syarat & Ketentuan ini.</li>
                    <li>Setelah penghentian, data akan dihapus permanen setelah melewati masa retensi sesuai Kebijakan Privasi.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">8. HUKUM YANG BERLAKU</h3>
                <p>Syarat & Ketentuan ini diatur berdasarkan hukum <strong>Negara Kesatuan Republik Indonesia</strong>. Setiap perselisihan yang timbul akan diselesaikan melalui musyawarah mufakat.</p>
            </section>

            <section class="space-y-3 pt-6 border-t border-slate-100">
                <h3 class="text-lg font-black text-slate-900">Hubungi Kami</h3>
                <p>Untuk pertanyaan terkait Syarat & Ketentuan ini:</p>
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mt-4">
                    <p class="font-bold text-slate-900">PT. Sekawan Putra Pratama</p>
                    <p class="text-slate-500">Senin – Jumat, 08.00–17.00 WIB</p>
                    <p class="mt-2 text-blue-600 font-bold">WhatsApp: +62 851-5641-2702</p>
                    <p class="text-blue-600 font-bold">Email: support@smartrtvision.sekawanputrapratama.com</p>
                    <p class="text-blue-600 font-bold">Web: <a href="https://www.sekawanputrapratama.com" target="_blank" class="underline">www.sekawanputrapratama.com</a></p>
                </div>
            </section>
        </div>
    </main>

    <footer class="py-12 text-center text-slate-400 text-xs font-medium">
        <p>&copy; {{ date('Y') }} <strong>PT. Sekawan Putra Pratama</strong>. Seluruh Hak Cipta Dilindungi.</p>
        <p class="mt-1 opacity-75">SmartRT Vision adalah platform sistem informasi administrasi rukun tetangga yang dikembangkan oleh PT. Sekawan Putra Pratama.</p>
    </footer>
</body>
</html>
