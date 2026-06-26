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
