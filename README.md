# Aplikasi Pendataan Warga RT (Berbasis AI Vision)

Aplikasi web untuk mengelola data warga (Kartu Keluarga & Anggota Warga) yang ditujukan untuk pengurus RT. Dibangun menggunakan Laravel 11 dan dilengkapi dengan integrasi AI (Google Gemini) untuk membaca dan mengekstrak data dari foto Kartu Keluarga secara otomatis.

Aplikasi ini didesain khusus agar dapat dijalankan dengan baik di **Shared Hosting cPanel** (tanpa membutuhkan Queue Worker, Supervisor, atau layanan daemon lainnya).

## 🚀 Fitur Utama

- **Ekstraksi AI Otomatis**: Cukup unggah foto Kartu Keluarga, dan biarkan Google Gemini mengekstrak data JSON terstruktur.
- **Validasi Cerdas**: Secara otomatis mengecek konsistensi data NIK (Kecocokan tanggal lahir dan jenis kelamin pada NIK) dan mem-flag data yang janggal dengan warna kuning pada form verifikasi.
- **Manajemen Data KK & Warga**: Melihat, mencari, memfilter, dan menghapus data secara mudah.
- **Ekspor Data**: Tersedia fitur ekspor daftar warga ke format Excel (.xlsx) dan rekapitulasi data RT ke format PDF.
- **Dashboard Statistik**: Tampilan ringkasan demografi warga (Total KK, Warga, Laki-laki, Perempuan).
- **Keamanan Data**: Semua rute (kecuali halaman login) dilindungi sistem Autentikasi dan file foto KK disimpan di direktori privat (`storage/app/private` atau `storage/app/kk_images`), sehingga tidak terkespos ke web publik.

## 🛠️ Persyaratan Sistem (Local & Hosting)

- PHP 8.2 atau lebih baru (Karena menggunakan Laravel 11).
- Ekstensi PHP: cURL, GD/Imagick, DOM, mbstring, PDO MySQL.
- MySQL/MariaDB database.
- Composer.
- Node.js & NPM (hanya untuk pengembangan lokal & *build* aset frontend).

## ⚙️ Panduan Instalasi Lokal

1. Pindah ke direktori root proyek ini.
2. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
3. Sesuaikan konfigurasi `.env`, khususnya API Key Gemini:
   ```env
   DB_CONNECTION=sqlite # Atau mysql
   
   GEMINI_API_KEY=KODE_API_KEY_ANDA_DI_SINI
   ```
   *(Catatan: Dapatkan API Key Gemini gratis dari Google AI Studio).*
4. Instal dependensi PHP:
   ```bash
   composer install
   ```
5. Instal dan *build* dependensi Frontend:
   ```bash
   npm install
   npm run build
   ```
6. Jalankan Migrasi Database:
   ```bash
   php artisan migrate
   ```
7. Jalankan *local server*:
   ```bash
   php artisan serve
   ```

## 🌐 Panduan Deploy ke Shared Hosting (cPanel)

Aplikasi ini menggunakan metode pemrosesan sinkron ke Gemini API, sehingga sangat bersahabat dengan Shared Hosting yang tidak mengizinkan script jalan di *background* (*daemon*).

1. Lakukan *build* aset di lokal komputer Anda terlebih dahulu (`npm run build`).
2. Zip seluruh proyek (abaikan `node_modules`).
3. Upload file ZIP ke cPanel Anda. Sangat disarankan untuk meletakkan file sistem di **luar** folder publik, dan hanya memindahkan isi folder `public` dari Laravel ke dalam `public_html`.
4. Sesuaikan path pada file `index.php` jika Anda memisahkan folder core.
5. Buat Database MySQL melalui cPanel dan isi kredensialnya di file `.env`.
6. Pastikan `GEMINI_API_KEY` terisi di `.env`.
7. Jalankan perintah Migrasi Database (Bisa menggunakan fitur Terminal di cPanel).
8. Pastikan *permissions* untuk folder `storage/` dan `bootstrap/cache/` adalah `775`.

> **Peringatan Privasi (UU Perlindungan Data Pribadi):** Harap disadari bahwa fitur ekstraksi otomatis akan mengirimkan foto Kartu Keluarga (yang memuat data kependudukan) ke Google Gemini via API. Pastikan Anda mengelola API key dengan benar dan menyadari aliran pengolahan pihak ketiga tersebut.
