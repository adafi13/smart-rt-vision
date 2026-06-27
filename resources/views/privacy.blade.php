<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kebijakan Privasi - SmartRT Vision</title>
    <meta name="description" content="Kebijakan privasi SmartRT Vision menjelaskan bagaimana kami menjaga keamanan data warga dan informasi pribadi Anda dengan standar enkripsi tinggi.">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/icon.png') }}">

    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
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
            <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Kebijakan Privasi</h1>
            <p class="text-slate-500 font-medium text-lg">Melindungi data warga Anda adalah prioritas utama kami di ekosistem SmartRT Vision.</p>
        </header>

        <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 text-sm leading-relaxed text-slate-600 space-y-8">
            
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                <p class="text-[12px] font-black text-blue-700 uppercase tracking-widest mb-1">Versi 1.0.0 | Diperbarui: 01 Januari 2026</p>
                <p class="text-blue-800 font-medium">
                    <strong>PT. Sekawan Putra Pratama</strong> ("Kami") berkomitmen untuk selalu menjunjung tinggi keamanan dan kerahasiaan data Anda dan seluruh warga lingkungan RT Anda sehubungan dengan penggunaan layanan Kami melalui Aplikasi SmartRT Vision.
                </p>
            </div>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">1. PENGUMPULAN DATA PRIBADI</h3>
                <p>Kami mengumpulkan Data Pribadi termasuk namun tidak terbatas pada:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Nama lengkap Pengurus RT (Ketua, Sekretaris, Bendahara) dan data Warga.</li>
                    <li>Alamat lingkungan RT, nomor telepon, serta alamat email.</li>
                    <li>Data Kartu Keluarga (KK), KTP, dan informasi kependudukan warga yang diunggah ke sistem.</li>
                    <li>Riwayat iuran bulanan, catatan kas RT, transaksi keuangan, serta surat menyurat administratif.</li>
                    <li>Log aktivitas pengguna (Pengurus & Warga) beserta cap waktu akses (timestamp).</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">2. PENGGUNAAN DATA PRIBADI</h3>
                <p>Kami menggunakan data Anda untuk:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Verifikasi identitas warga dan validasi pengurus RT.</li>
                    <li>Menyediakan layanan administrasi kependudukan seperti pembuatan surat pengantar secara otomatis.</li>
                    <li>Mencatat dan melaporkan transparansi iuran kas RT.</li>
                    <li>Mengirimkan notifikasi penting seperti tagihan iuran, undangan rapat warga, atau peringatan keamanan.</li>
                    <li>Meningkatkan layanan melalui penelitian dan pengembangan fitur baru secara anonim.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">3. MASA PENYIMPANAN DATA</h3>
                <p>Kami memiliki aturan retensi data yang transparan:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Akun Aktif:</strong> Data disimpan penuh selama masa penggunaan.</li>
                    <li><strong>Masa Tenggang (30 Hari):</strong> Jika Anda memutuskan untuk berhenti menggunakan layanan, data tetap disimpan selama 30 hari sebagai backup apabila Anda berubah pikiran.</li>
                    <li><strong>Penghapusan Permanen:</strong> Melewati 60 hari setelah akun dinonaktifkan, data kependudukan warga akan dihapus secara permanen demi keamanan privasi.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-2">4. KEAMANAN DATA KEPENDUDUKAN</h3>
                <p>Kami menyadari sensitivitas data KTP & KK, oleh karena itu kami menggunakan teknologi keamanan tingkat tinggi:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Enkripsi data pribadi dan finansial di tingkat database server (AES-256).</li>
                    <li>Koneksi aman melalui protokol HTTPS/TLS 1.3 untuk setiap pertukaran informasi.</li>
                    <li>Sistem Autentikasi yang ketat dan pencadangan (backup) harian secara terotomatisasi di cloud.</li>
                </ul>
            </section>

            <section class="space-y-3 pt-6 border-t border-slate-100">
                <h3 class="text-lg font-black text-slate-900">Hubungi Tim Keamanan Kami</h3>
                <p>Jika Anda memiliki pertanyaan atau kekhawatiran sehubungan dengan perlindungan data warga:</p>
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mt-4">
                    <p class="font-bold text-slate-900">SmartRT Vision Official Support</p>
                    <p class="text-slate-500">Senin – Jumat, 08.00–17.00 WIB</p>
                    <p class="mt-2 text-blue-600 font-bold">WhatsApp: +62 851-5641-2702</p>
                    <p class="text-blue-600 font-bold">Email: support@smartrtvision.sekawanputrapratama.com</p>
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
