<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WargaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithCustomStartCell
{
    private $rowNumber = 0;
    private $tenantName;

    public function __construct()
    {
        $this->tenantName = auth()->user()->tenant->name ?? 'Lingkungan RT/RW';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        // Pastikan hanya mengambil member milik tenant saat ini
        return Member::with('family')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('family_id')
            ->get();
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

        // Header Table Style
        $sheet->getStyle('A4:' . $lastCol . '4')->applyFromArray([
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

        // Table Body Border & Alignment
        if ($lastRow > 4) {
            $sheet->getStyle('A5:' . $lastCol . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '9CA3AF'], // Gray-400
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);
            
            // Center align numbering column
            $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        
        $sheet->getRowDimension(4)->setRowHeight(25);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cells for Title
                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');

                // Set Title Text
                $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI DATA WARGA');
                $sheet->setCellValue('A2', strtoupper($this->tenantName) . ' - DICETAK TANGGAL: ' . date('d M Y H:i'));

                // Style Title
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '111827'], // Gray-900
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Freeze Pane at row 5 so headers are always visible when scrolling
                $sheet->freezePane('A5');
            },
        ];
    }
}
