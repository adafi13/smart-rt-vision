<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WargaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            [
                '3216212409120014', 'BUDI SANTOSO', 'Jl. Mawar No. 1', '001', '002',
                'Sukaragam', 'Serang Baru', 'Bekasi', 'Jawa Barat', '17330',
                '3216212409120014', 'BUDI SANTOSO', 'Laki-laki', 'Bekasi', '1985-04-12',
                'Islam', 'S1', 'Karyawan Swasta', 'Menikah', 'Kepala Keluarga', 'WNI', '', '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nomor_kk', 'nama_kepala_keluarga', 'alamat', 'rt', 'rw',
            'desa_kelurahan', 'kecamatan', 'kabupaten_kota', 'provinsi', 'kode_pos',
            'nik', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'agama', 'pendidikan', 'pekerjaan', 'status_perkawinan', 'hubungan_keluarga',
            'kewarganegaraan', 'nama_ayah', 'nama_ibu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:W1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ]);

        return [];
    }
}
