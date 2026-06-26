<?php

namespace App\Imports;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ResidentsImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;

    public int $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['nomor_kk']) || empty($row['nik']) || empty($row['nama'])) {
                $this->skipped++;

                continue;
            }

            $family = Family::firstOrCreate(
                ['nomor_kk' => (string) $row['nomor_kk']],
                [
                    'nama_kepala_keluarga' => $row['nama_kepala_keluarga'] ?? 'Belum Diisi',
                    'alamat' => $row['alamat'] ?? '-',
                    'rt' => $row['rt'] ?? '-',
                    'rw' => $row['rw'] ?? '-',
                    'desa_kelurahan' => $row['desa_kelurahan'] ?? '-',
                    'kecamatan' => $row['kecamatan'] ?? '-',
                    'kabupaten_kota' => $row['kabupaten_kota'] ?? '-',
                    'provinsi' => $row['provinsi'] ?? '-',
                    'kode_pos' => $row['kode_pos'] ?? null,
                    'status_verifikasi' => 'terverifikasi',
                ]
            );

            Member::updateOrCreate(
                ['nik' => (string) $row['nik']],
                [
                    'family_id' => $family->id,
                    'nama' => $row['nama'],
                    'jenis_kelamin' => in_array($row['jenis_kelamin'] ?? null, ['Laki-laki', 'Perempuan']) ? $row['jenis_kelamin'] : 'Laki-laki',
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? null),
                    'agama' => $row['agama'] ?? null,
                    'pendidikan' => $row['pendidikan'] ?? null,
                    'pekerjaan' => $row['pekerjaan'] ?? null,
                    'status_perkawinan' => $row['status_perkawinan'] ?? null,
                    'hubungan_keluarga' => $row['hubungan_keluarga'] ?? 'Anggota Keluarga',
                    'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                    'nama_ayah' => $row['nama_ayah'] ?? null,
                    'nama_ibu' => $row['nama_ibu'] ?? null,
                    'status_warga' => 'Aktif',
                ]
            );

            $this->imported++;
        }
    }

    private function parseDate($value): ?string
    {
        if (! $value) {
            return null;
        }

        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
