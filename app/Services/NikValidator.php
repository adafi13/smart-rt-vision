<?php

namespace App\Services;

use App\Models\Family;
use App\Models\Member;

class NikValidator
{
    /**
     * Memvalidasi NIK berdasarkan format, konsistensi data, dan duplikasi.
     *
     * @param array $member Data anggota
     * @return array Kumpulan warning
     */
    public static function validate(array $member): array
    {
        $warnings = [];

        $nik = $member['nik'] ?? '';
        $tanggalLahir = $member['tanggal_lahir'] ?? ''; // YYYY-MM-DD
        $jenisKelamin = $member['jenis_kelamin'] ?? '';

        // 1. Format 16 digit
        if (!preg_match('/^\d{16}$/', $nik)) {
            $warnings['nik'][] = 'NIK harus 16 digit angka.';
        }

        // 2. Konsistensi NIK
        if (preg_match('/^\d{16}$/', $nik) && !empty($tanggalLahir) && !empty($jenisKelamin)) {
            $tglNik = substr($nik, 6, 2);
            $blnNik = substr($nik, 8, 2);
            $thnNik = substr($nik, 10, 2);

            $parts = explode('-', $tanggalLahir);
            if (count($parts) === 3) {
                $thnLahir = substr($parts[0], -2);
                $blnLahir = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                $tglLahir = str_pad($parts[2], 2, '0', STR_PAD_LEFT);

                $expectedTglLahir = (int)$tglLahir;
                if ($jenisKelamin === 'Perempuan') {
                    $expectedTglLahir += 40;
                }
                
                $expectedTglNikStr = str_pad($expectedTglLahir, 2, '0', STR_PAD_LEFT);

                if ($tglNik !== $expectedTglNikStr || $blnNik !== $blnLahir || $thnNik !== $thnLahir) {
                    $warnings['nik'][] = 'Peringatan: NIK tidak sinkron dengan Tanggal Lahir dan Jenis Kelamin.';
                }
            }
        }

        // 3. Duplikat
        if (!empty($nik) && Member::where('nik', $nik)->exists()) {
            $warnings['nik'][] = 'Peringatan: NIK ini sudah terdaftar di sistem.';
        }

        // 4. Validasi Masa Depan Tanggal Lahir
        if (!empty($tanggalLahir)) {
            if (strtotime($tanggalLahir) > time()) {
                $warnings['tanggal_lahir'][] = 'Peringatan: Tanggal lahir tidak boleh di masa depan.';
            }
        }

        // 5. Validasi Hubungan Keluarga dan Gender
        if (!empty($jenisKelamin)) {
            $hubungan = strtolower($member['hubungan_keluarga'] ?? '');
            if ($hubungan === 'istri' && $jenisKelamin !== 'Perempuan') {
                $warnings['jenis_kelamin'][] = 'Peringatan: Anggota dengan hubungan keluarga "Istri" harus berjenis kelamin Perempuan.';
            }
        }

        // 6. Validasi Status Perkawinan Anak di Bawah Umur
        if (!empty($tanggalLahir)) {
            $birthYear = (int)date('Y', strtotime($tanggalLahir));
            $currentYear = (int)date('Y');
            $age = $currentYear - $birthYear;
            $statusKawin = strtolower($member['status_perkawinan'] ?? '');
            if ($age < 15 && !empty($statusKawin) && $statusKawin !== 'belum kawin' && $statusKawin !== 'belum kawin') {
                $warnings['status_perkawinan'][] = 'Peringatan: Status perkawinan anak di bawah 15 tahun umumnya adalah "Belum Kawin".';
            }
        }

        // 7. Validasi Agama Standar (Deteksi Typos hasil OCR)
        if (!empty($member['agama'])) {
            $agamaList = ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu', 'protestan', 'penghayat'];
            $agamaInput = strtolower(trim($member['agama']));
            $matched = false;
            foreach ($agamaList as $a) {
                if (str_contains($agamaInput, $a)) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $warnings['agama'][] = 'Peringatan: Agama tidak standar. Pastikan tidak ada salah ketik.';
            }
        }

        return $warnings;
    }

    /**
     * Memvalidasi Nomor KK.
     */
    public static function validateKk(string $nomorKk): array
    {
        $warnings = [];
        if (!preg_match('/^\d{16}$/', $nomorKk)) {
            $warnings['nomor_kk'][] = 'Nomor KK harus 16 digit angka.';
        }

        if (!empty($nomorKk) && Family::where('nomor_kk', $nomorKk)->exists()) {
            $warnings['nomor_kk'][] = 'Peringatan: Nomor KK ini sudah terdaftar di sistem.';
        }

        return $warnings;
    }
}
