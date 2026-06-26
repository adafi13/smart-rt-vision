<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WargaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $rowNumber = 0;

    public function collection()
    {
        return Member::with('family')->orderBy('family_id')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No KK',
            'Kepala Keluarga',
            'Nama Lengkap',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Pendidikan',
            'Jenis Pekerjaan',
            'Status Perkawinan',
            'Hubungan Keluarga',
            'Kewarganegaraan'
        ];
    }

    public function map($member): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            "'" . ($member->family->nomor_kk ?? '-'),
            strtoupper($member->family->nama_kepala_keluarga ?? '-'),
            strtoupper($member->nama),
            "'" . $member->nik,
            strtoupper($member->jenis_kelamin),
            strtoupper($member->tempat_lahir),
            $member->tanggal_lahir ? date('d-m-Y', strtotime($member->tanggal_lahir)) : '',
            strtoupper($member->agama),
            strtoupper($member->pendidikan),
            strtoupper($member->pekerjaan),
            strtoupper($member->status_perkawinan),
            strtoupper($member->hubungan_keluarga),
            strtoupper($member->kewarganegaraan)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // Header style (Beautiful Indigo)
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Standard body style with soft borders
        $sheet->getStyle('A2:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'], // Gray-300
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }
}
