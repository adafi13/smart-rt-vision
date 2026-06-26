<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Syarat dan ketentuan penggunaan layanan mandiri warga di {{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }}.">
    <title>Syarat &amp; Ketentuan · {{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }}</title>

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
        'title' => 'Syarat & Ketentuan',
        'subtitle' => 'Ketentuan penggunaan portal dan layanan mandiri warga.',
    ])

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-14">
        <article>
            <p class="text-xs text-gray-400">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

            <h2>1. Penerimaan Ketentuan</h2>
            <p>
                Dengan menggunakan layanan mandiri di portal ini (cek NIK, pengajuan surat, cek iuran, pelaporan
                keluhan, dan pelaporan peristiwa), Anda dianggap menyetujui syarat &amp; ketentuan berikut.
            </p>

            <h2>2. Ruang Lingkup Layanan</h2>
            <p>
                Portal ini adalah alat bantu administrasi internal lingkungan RT, <strong>bukan</strong> kanal resmi
                Dinas Kependudukan dan Pencatatan Sipil (Dukcapil) atau instansi pemerintah lainnya. Surat pengantar
                yang dihasilkan dari portal ini adalah surat pengantar tingkat RT, dan kemungkinan masih perlu diproses
                lebih lanjut di tingkat kelurahan/kecamatan sesuai jenis keperluan Anda.
            </p>

            <h2>3. Kewajiban Pengguna</h2>
            <ul>
                <li>Mengisi NIK dan data lain dengan benar dan sesuai data kependudukan yang sebenarnya.</li>
                <li>Tidak mengirimkan laporan, pengajuan, atau keterangan palsu dalam bentuk apa pun.</li>
                <li>Menjaga kerahasiaan NIK milik sendiri maupun anggota keluarga lain saat menggunakan perangkat bersama.</li>
            </ul>

            <h2>4. Larangan Penyalahgunaan</h2>
            <p>
                Pengguna dilarang menggunakan formulir pelaporan untuk mengirim konten yang menyinggung, mencemarkan
                nama baik, atau tidak relevan dengan keperluan administrasi RT. Pengurus RT berhak menolak atau
                menghapus pengajuan/laporan yang dianggap tidak sesuai.
            </p>

            <h2>5. Tanggung Jawab Pengurus RT</h2>
            <p>
                Pengurus RT akan menindaklanjuti pengajuan surat dan laporan warga secara wajar, namun waktu proses
                dapat bervariasi tergantung kompleksitas dan ketersediaan pengurus. Status setiap pengajuan dapat
                dipantau mandiri melalui fitur "Cek Status" pada portal.
            </p>

            <h2>6. Batasan Tanggung Jawab</h2>
            <p>
                Layanan disediakan "sebagaimana adanya". Pengurus RT tidak bertanggung jawab atas keterlambatan atau
                gangguan yang disebabkan oleh hal-hal di luar kendali pengurus, termasuk namun tidak terbatas pada
                gangguan jaringan internet atau gangguan pada layanan pihak ketiga yang digunakan aplikasi ini.
            </p>

            <h2>7. Perubahan Layanan</h2>
            <p>
                Pengurus RT dapat menambah, mengubah, atau menghentikan sebagian fitur layanan mandiri sewaktu-waktu
                untuk peningkatan kualitas pelayanan, dengan tetap mengutamakan kemudahan akses bagi warga.
            </p>

            <h2>8. Hukum yang Berlaku</h2>
            <p>
                Ketentuan ini tunduk pada peraturan dan perundang-undangan yang berlaku di Republik Indonesia,
                termasuk ketentuan mengenai pelindungan data pribadi.
            </p>

            <h2>9. Kontak</h2>
            <p>
                Pertanyaan terkait ketentuan layanan ini dapat disampaikan langsung kepada Pengurus RT di lingkungan
                Anda.
            </p>
        </article>
    </main>

    @include('partials.public-footer')
</body>
</html>
