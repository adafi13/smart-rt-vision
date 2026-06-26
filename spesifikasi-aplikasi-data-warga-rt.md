# Spesifikasi Aplikasi: Pendataan Warga RT Berbasis Foto KK (Laravel)

> Dokumen ini adalah brief untuk AI coding agent. Bangun aplikasi web sesuai spesifikasi di bawah. Tulis kode bersih, beri komentar pada bagian penting, dan ikuti semua batasan teknis (terutama: harus jalan di **shared hosting cPanel**).

---

## 1. Tujuan

Membantu pengurus RT mendata warga tanpa mengetik manual. Pengurus **memotret/upload Kartu Keluarga (KK)**, lalu **AI (Google Gemini)** membaca foto dan mengisi data secara otomatis dan terstruktur. Pengurus tinggal memverifikasi, mengoreksi bila perlu, lalu menyimpan.

## 2. Pengguna

- Pengurus RT (admin). Login wajib. Untuk awal cukup single-user/multi-admin sederhana, tanpa peran rumit.

## 3. Tech Stack & Batasan Lingkungan (WAJIB DIPATUHI)

- **Framework:** Laravel (versi LTS terbaru yang kompatibel PHP 8.1+).
- **Lingkungan target:** **Shared hosting cPanel.** Artinya:
  - **TIDAK boleh** mengandalkan queue worker yang berjalan terus-menerus (`queue:work` daemon), supervisor, websocket, atau cron yang rumit.
  - Pemrosesan AI dilakukan **synchronous** (langsung dalam request): upload → panggil Gemini → tunggu hasil → tampilkan form verifikasi.
  - Asumsikan hanya ada PHP 8.1+ dengan ekstensi **cURL** dan **GD/Imagick** aktif, akses ke `composer` saat deploy, MySQL/MariaDB.
- **Database:** MySQL/MariaDB.
- **Frontend:** Blade + Tailwind CSS (atau Bootstrap bila lebih ringan untuk shared hosting). Harus **mobile-friendly/responsive** karena pengurus akan memotret KK langsung dari HP.
- **Autentikasi:** Laravel Breeze (paling ringan) cukup.
- **AI Vision:** **Google Gemini API** (model multimodal terbaru yang mendukung input gambar, mis. kelas `gemini-flash`). API key disimpan di `.env` (`GEMINI_API_KEY`), JANGAN hardcode.

## 4. Alur Aplikasi

1. Admin login.
2. Halaman **Upload KK**: ambil foto dari kamera HP atau pilih file (jpg/png, maks ~8MB). Tampilkan preview.
3. Backend mengompres/resize gambar bila perlu (hemat kuota & waktu), lalu mengirim ke Gemini dengan prompt yang meminta output **JSON terstruktur** (lihat §6).
4. Hasil JSON di-parse, lalu ditampilkan di **Form Verifikasi** yang seluruh field-nya bisa diedit.
5. Jalankan **validasi otomatis** (lihat §7) dan tandai field yang mencurigakan secara visual (mis. border kuning + catatan).
6. Admin mengoreksi bila perlu → klik **Simpan**.
7. Data tersimpan: 1 KK → tabel `families`, anggota → tabel `members`.
8. Admin bisa melihat **Daftar KK & Warga**, mencari/memfilter, mengedit, dan **mengekspor ke Excel/PDF**.
9. **Dashboard** menampilkan rekap statistik.

## 5. Skema Database

### Tabel `users` (bawaan Breeze)
Standar Laravel.

### Tabel `families` (1 baris = 1 KK)
| Kolom | Tipe | Catatan |
|---|---|---|
| id | bigint PK | |
| nomor_kk | string(16) | unique, validasi 16 digit |
| nama_kepala_keluarga | string | |
| alamat | string | |
| rt | string(5) | |
| rw | string(5) | |
| desa_kelurahan | string | |
| kecamatan | string | |
| kabupaten_kota | string | |
| provinsi | string | |
| kode_pos | string(10) | nullable |
| foto_path | string | path file KK tersimpan |
| status_verifikasi | enum('draft','terverifikasi') | default 'draft' |
| created_at / updated_at | timestamp | |

### Tabel `members` (1 baris = 1 anggota keluarga)
| Kolom | Tipe | Catatan |
|---|---|---|
| id | bigint PK | |
| family_id | bigint FK → families.id | onDelete cascade |
| nik | string(16) | index, validasi 16 digit |
| nama | string | |
| jenis_kelamin | enum('Laki-laki','Perempuan') | |
| tempat_lahir | string | nullable |
| tanggal_lahir | date | nullable |
| agama | string | nullable |
| pendidikan | string | nullable |
| pekerjaan | string | nullable |
| status_perkawinan | string | nullable |
| hubungan_keluarga | string | mis. Kepala Keluarga, Istri, Anak |
| kewarganegaraan | string | default 'WNI' |
| nama_ayah | string | nullable |
| nama_ibu | string | nullable |
| created_at / updated_at | timestamp | |

Buat migration, model (dengan relasi `Family hasMany Member`, `Member belongsTo Family`), dan factory/seeder sederhana untuk testing.

## 6. Integrasi Gemini (langkah inti)

Buat sebuah Service class, mis. `App\Services\KkExtractor`, dengan method `extract(string $imagePath): array`.

- Kirim gambar (base64) + prompt ke endpoint Gemini `generateContent` via HTTP (pakai `Illuminate\Support\Facades\Http`).
- Minta Gemini mengembalikan **HANYA JSON valid**, tanpa teks tambahan, tanpa markdown fence. Gunakan instruksi sistem yang tegas, dan jika tersedia, fitur *structured output / response schema* Gemini.
- Skema JSON yang diminta:

```json
{
  "nomor_kk": "string 16 digit",
  "alamat": "string",
  "rt": "string",
  "rw": "string",
  "desa_kelurahan": "string",
  "kecamatan": "string",
  "kabupaten_kota": "string",
  "provinsi": "string",
  "kode_pos": "string",
  "anggota": [
    {
      "nik": "string 16 digit",
      "nama": "string",
      "jenis_kelamin": "Laki-laki | Perempuan",
      "tempat_lahir": "string",
      "tanggal_lahir": "YYYY-MM-DD",
      "agama": "string",
      "pendidikan": "string",
      "pekerjaan": "string",
      "status_perkawinan": "string",
      "hubungan_keluarga": "string",
      "kewarganegaraan": "string",
      "nama_ayah": "string",
      "nama_ibu": "string"
    }
  ]
}
```

- Tangani error dengan baik: timeout, respons bukan JSON, kuota habis, gambar buram. Jika gagal parse, kembalikan pesan ramah ke admin dan tetap tampilkan form kosong agar bisa diisi manual.
- Set timeout HTTP yang masuk akal (mis. 60 detik) karena prosesnya synchronous.
- Catatan biaya/kuota: anggap pakai tier gratis Gemini; tambahkan penanganan rate limit (jeda + pesan jelas bila kena limit).

## 7. Validasi Otomatis (fitur cerdas — WAJIB ada)

Sebelum simpan, jalankan pemeriksaan berikut dan tandai (bukan blokir) field yang janggal:

1. **Format:** `nomor_kk` dan `nik` harus 16 digit angka.
2. **Konsistensi NIK ↔ data:** NIK Indonesia memuat info tanggal lahir & jenis kelamin pada digit ke-7 s/d 12.
   - Digit 7–8 = tanggal lahir. **Untuk perempuan, tanggal ini ditambah 40.** (mis. tanggal 15 → "55").
   - Digit 9–10 = bulan lahir. Digit 11–12 = 2 digit terakhir tahun lahir.
   - Bandingkan hasil decode NIK dengan `tanggal_lahir` dan `jenis_kelamin` hasil baca AI. Jika **tidak cocok**, tandai field tersebut "perlu dicek" (ini menangkap salah baca angka oleh AI dengan sangat efektif).
3. **Duplikat:** cek apakah `nomor_kk` atau `nik` sudah ada di database. Jika ya, beri peringatan (kemungkinan data ganda).
4. **Kepala keluarga:** pastikan ada tepat satu anggota dengan `hubungan_keluarga` = "Kepala Keluarga"; jika tidak, beri peringatan.

Tampilkan ringkasan peringatan di atas form sebelum admin menekan Simpan.

## 8. Halaman / Rute

- `/login` — autentikasi.
- `/dashboard` — rekap: total KK, total jiwa, jumlah per jenis kelamin, per agama, per kelompok umur (anak/dewasa/lansia). Tampilkan kartu angka sederhana.
- `/kk/upload` — form upload + preview.
- `/kk/extract` (POST) — proses ke Gemini, redirect ke form verifikasi.
- `/kk/verify` — form verifikasi yang bisa diedit + tampilan peringatan validasi.
- `/kk` — daftar KK (search by nomor KK / nama kepala keluarga, paginate).
- `/kk/{family}` — detail satu KK + daftar anggotanya.
- `/kk/{family}/edit` & update.
- `/warga` — daftar semua warga/anggota (search by NIK/nama, filter jenis kelamin/agama).
- `/export/excel` & `/export/pdf` — ekspor data.

## 9. Ekspor

- **Excel:** gunakan `maatwebsite/excel`. Ekspor daftar warga & daftar KK.
- **PDF:** gunakan `barryvdh/laravel-dompdf`. Sediakan cetak rekap data warga per RT.
- (Opsional, bila sempat) generate **surat pengantar** sederhana berisi data warga terpilih.

## 10. Keamanan & Privasi (penting — data KK = data pribadi sensitif)

- Semua rute (kecuali login) wajib `auth` middleware.
- Simpan foto KK di `storage/app` (private), **bukan** folder publik yang bisa diakses tanpa login. Sajikan via rute terproteksi.
- `GEMINI_API_KEY` hanya di `.env`. Jangan commit `.env`.
- Validasi & sanitasi semua input. Gunakan CSRF bawaan Laravel.
- Tambahkan catatan di README bahwa pada langkah ekstraksi, gambar dikirim ke Google; sarankan admin sadar akan hal ini (kepatuhan UU PDP).

## 11. Seed & Testing

- Buatkan seeder akun admin awal (email/password dari `.env`, mis. `[email protected]`).
- Buat beberapa data dummy keluarga+anggota agar dashboard & daftar bisa langsung dilihat.
- Sediakan feature test dasar untuk: login, simpan KK, validasi NIK.

## 12. Deliverable & Deploy

- Sertakan **README.md** berisi:
  - Cara instalasi lokal (`composer install`, `npm install && npm run build`, `php artisan migrate --seed`).
  - Cara isi `.env` (termasuk `GEMINI_API_KEY` dan cara mendapatkannya).
  - **Panduan deploy ke shared hosting cPanel** (upload file, set document root ke folder `public`, jalankan migrate, set permission `storage` & `bootstrap/cache`).
- Pastikan aplikasi berjalan tanpa perlu queue worker atau layanan latar belakang.

## 13. Prioritas Pengerjaan (jika waktu terbatas)

1. Auth + skema DB + CRUD KK manual (fondasi).
2. Integrasi Gemini + form verifikasi (inti nilai aplikasi).
3. Validasi otomatis NIK + deteksi duplikat.
4. Dashboard + daftar/pencarian.
5. Ekspor Excel/PDF.
6. Fitur opsional (surat pengantar).

---

**Catatan untuk agent:** Jika ada keputusan desain yang ambigu, pilih opsi paling sederhana yang tetap berjalan di shared hosting. Utamakan keandalan dan kemudahan deploy daripada fitur canggih.
