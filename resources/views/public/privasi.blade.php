<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Kebijakan privasi pengelolaan data warga di {{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }}.">
    <title>Kebijakan Privasi · {{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: #f8f9fc; color: #0f172a; }
        .glass-dark { background: rgba(15,15,25,0.55); backdrop-filter: blur(14px) saturate(160%); -webkit-backdrop-filter: blur(14px) saturate(160%); }
        .btn-ghost { background: rgba(255,255,255,0.08); color: white; padding: 12px 22px; border-radius: 12px; font-size: 14px; font-weight: 600; border: 1px solid rgba(255,255,255,0.25); cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-ghost:hover { background: rgba(255,255,255,0.16); }
        article h2 { font-size: 1.125rem; font-weight: 800; color: #111827; margin-top: 2rem; margin-bottom: 0.75rem; }
        article p { color: #4b5563; font-size: 0.9rem; line-height: 1.75; margin-bottom: 0.75rem; }
        article ul { list-style: disc; padding-left: 1.25rem; color: #4b5563; font-size: 0.9rem; line-height: 1.75; margin-bottom: 0.75rem; }
        article li { margin-bottom: 0.35rem; }
        article strong { color: #1f2937; }
    </style>
</head>
<body>

    @include('partials.public-nav')

    @include('partials.public-page-header', [
        'title' => 'Kebijakan Privasi',
        'subtitle' => 'Bagaimana data kependudukan warga dikumpulkan, digunakan, dan dilindungi.',
    ])

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-14">
        <article>
            <p class="text-xs text-gray-400">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

            <h2>1. Tentang Layanan Ini</h2>
            <p>
                Halaman ini berlaku untuk portal warga <strong>{{ $tenant->name ?? 'RT ini' }}</strong>, yang dibangun di atas
                {{ config('app.name', 'SmartRT Vision') }} — aplikasi yang digunakan oleh pengurus RT untuk mendata
                warga (Kartu Keluarga dan anggotanya) serta menyediakan layanan mandiri bagi warga (cek NIK, pengajuan surat,
                pengecekan iuran, pelaporan keluhan, dan pelaporan peristiwa kependudukan). Kebijakan ini menjelaskan data apa
                saja yang kami kumpulkan dan bagaimana data tersebut diperlakukan.
            </p>

            <h2>2. Data yang Dikumpulkan</h2>
            <ul>
                <li>Data identitas: NIK, nama lengkap, jenis kelamin, tempat &amp; tanggal lahir, agama, status perkawinan, pendidikan, pekerjaan, dan hubungan keluarga.</li>
                <li>Data Kartu Keluarga: nomor KK, alamat, RT/RW, dan foto/scan Kartu Keluarga yang diunggah pengurus.</li>
                <li>Data layanan mandiri: isi pengajuan surat, isi laporan keluhan beserta foto bukti, dan data peristiwa (kelahiran/kematian/dll) yang dilaporkan warga.</li>
                <li>Data transaksi kas: riwayat iuran per Kartu Keluarga dan pengeluaran kas RT.</li>
            </ul>

            <h2>3. Tujuan Penggunaan Data</h2>
            <p>
                Data digunakan semata-mata untuk keperluan administrasi kependudukan RT: pembuatan surat pengantar,
                rekapitulasi statistik warga, pencatatan transparansi kas, serta penanganan keluhan dan peristiwa
                kependudukan. Data tidak digunakan untuk kepentingan komersial dan tidak dijual atau dibagikan ke pihak
                ketiga di luar yang disebutkan pada bagian 4.
            </p>

            <h2>4. Pemrosesan oleh Pihak Ketiga (AI Google Gemini)</h2>
            <p>
                Saat pengurus mengunggah foto Kartu Keluarga, gambar tersebut dikirimkan ke layanan Google Gemini API
                untuk diekstrak secara otomatis menjadi data terstruktur (nomor KK, data anggota keluarga, dst). Ini
                berarti foto KK akan diproses oleh server Google sesuai Kebijakan Privasi Google, sebelum hasilnya
                dikembalikan ke aplikasi ini untuk diverifikasi oleh pengurus. Foto KK <strong>tidak disimpan secara publik</strong>
                — file disimpan di penyimpanan privat aplikasi dan hanya dapat diakses oleh pengurus yang sudah login.
            </p>

            <h2>5. Keamanan Data</h2>
            <ul>
                <li>Seluruh data warga hanya dapat dikelola oleh pengurus RT yang memiliki akun dan telah login.</li>
                <li>Foto Kartu Keluarga dan dokumen pendukung disimpan di direktori privat, bukan folder publik yang dapat diakses langsung lewat internet.</li>
                <li>Kata sandi akun pengurus disimpan dalam bentuk terenkripsi (hashed), tidak dapat dibaca dalam bentuk teks biasa.</li>
            </ul>

            <h2>6. Hak Warga atas Data Pribadi</h2>
            <p>
                Warga berhak meminta koreksi data yang tidak akurat, atau meminta penghapusan data pribadinya, dengan
                menghubungi pengurus RT secara langsung. Permintaan akan ditindaklanjuti selama tidak bertentangan dengan
                kewajiban administrasi kependudukan yang berlaku.
            </p>

            <h2>7. Retensi Data</h2>
            <p>
                Data disimpan selama warga terdaftar sebagai bagian dari lingkungan RT. Data dapat dihapus dari sistem
                apabila warga pindah domisili secara permanen dan pengurus RT memverifikasi laporan peristiwa pindah
                tersebut.
            </p>

            <h2>8. Perubahan Kebijakan</h2>
            <p>
                Kebijakan ini dapat diperbarui sewaktu-waktu apabila terdapat perubahan cara pengelolaan data. Tanggal
                pembaruan terakhir selalu tercantum di bagian atas halaman ini.
            </p>

            <h2>9. Kontak</h2>
            <p>
                Pertanyaan atau permintaan terkait data pribadi dapat disampaikan langsung kepada Pengurus RT melalui
                jalur komunikasi warga yang berlaku di lingkungan Anda.
            </p>
        </article>
    </main>

    @include('partials.public-footer')
</body>
</html>
