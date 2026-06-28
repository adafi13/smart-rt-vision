<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Data Warga - {{ $tenantName }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px; /* Diperkecil agar muat 14 kolom lanskap */
            color: #374151;
            line-height: 1.4;
            margin: 0;
            padding: 10px 0;
        }
        /* KOP SURAT (LETTERHEAD) */
        .header {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h3, .header h4, .header p {
            margin: 0;
            padding: 0;
        }
        .header h3 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h4 {
            font-size: 14pt;
            font-weight: bold;
        }
        .header p {
            font-size: 11pt;
            color: #111827;
        }

        .document-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .document-title h3 {
            font-size: 14px;
            margin: 0;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .document-title p {
            font-size: 10px;
            margin: 5px 0 0 0;
            color: #6b7280;
        }

        /* DATA KELUARGA */
        .family-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .family-header {
            background-color: #f3f4f6;
            padding: 8px 12px;
            border-left: 4px solid #4f46e5;
            margin-bottom: 8px;
        }
        .family-header table {
            width: 100%;
            border: none;
            margin: 0;
        }
        .family-header table td {
            border: none;
            padding: 2px 0;
            font-size: 11px;
        }

        /* TABEL ANGGOTA */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table-data th, .table-data td {
            border: 1px solid #d1d5db;
            padding: 6px 5px;
            text-align: left;
        }
        .table-data th {
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }
        .table-data tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* TANDA TANGAN */
        .signature-box {
            width: 250px;
            float: right;
            text-align: center;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box p { margin: 0 0 5px 0; font-size: 11pt; }
        .signature-img {
            max-width: 150px;
            max-height: 80px;
            margin: 10px auto;
            display: block;
        }
        .signature-box .name { font-weight: bold; text-decoration: underline; font-size: 12pt; }
        .clear { clear: both; }
        
        /* FOOTER */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        .page-number:before { content: counter(page); }
    </style>
</head>
<body>
    
    <div class="footer">
        Dicetak otomatis oleh Sistem Manajemen RT/RW (SmartRT Vision) pada {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} WIB | Halaman <span class="page-number"></span>
    </div>

    <div class="header">
        <h3>RUKUN TETANGGA (RT) {{ substr($tenantName ?? '001', 0, 3) }}</h3>
        <h4>RUKUN WARGA (RW) {{ substr($tenantName ?? '020', -3) }}</h4>
        <p>PERUM. MEGA REGENCY BLOK G-3 NO. 38, JAWA BARAT 17334</p>
    </div>

    <div class="document-title">
        <h3>Laporan Rekapitulasi Warga</h3>
        <p>Bulan: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    @forelse($families as $family)
        <div class="family-section">
            <div class="family-header">
                <table>
                    <tr>
                        <td width="15%"><strong>Nama Kepala Keluarga</strong></td>
                        <td width="35%">: {{ strtoupper($family->nama_kepala_keluarga) }}</td>
                        <td width="15%"><strong>Nomor KK</strong></td>
                        <td width="35%">: <span style="font-family: monospace; font-size: 12px; font-weight: bold;">{{ $family->nomor_kk }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat Domisili</strong></td>
                        <td>: {{ $family->alamat }}</td>
                        <td><strong>Wilayah / RT / RW</strong></td>
                        <td>: RT {{ $family->rt }} / RW {{ $family->rw }} ({{ $family->desa_kelurahan }})</td>
                    </tr>
                </table>
            </div>
            
            <table class="table-data">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="15%">NIK</th>
                        <th width="18%">Nama Lengkap</th>
                        <th width="4%">L/P</th>
                        <th width="10%">T. Lahir</th>
                        <th width="9%">Tgl Lahir</th>
                        <th width="9%">Agama</th>
                        <th width="9%">Pendidikan</th>
                        <th width="10%">Pekerjaan</th>
                        <th width="7%">Sts Kawin</th>
                        <th width="6%">Hub. KK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($family->members as $index => $member)
                    <tr>
                        <td class="text-center" style="font-size: 8px;">{{ $index + 1 }}</td>
                        <td style="font-family: monospace; font-size: 9px; font-weight: bold;">{{ $member->nik }}</td>
                        <td style="font-size: 9px; font-weight: bold;">{{ strtoupper($member->nama) }}</td>
                        <td class="text-center" style="font-size: 8px; font-weight: bold;">{{ $member->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                        <td style="font-size: 8px;">{{ strtoupper($member->tempat_lahir) ?? '-' }}</td>
                        <td class="text-center" style="font-size: 8px;">{{ $member->tanggal_lahir ? \Carbon\Carbon::parse($member->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                        <td class="text-center" style="font-size: 8px;">{{ strtoupper($member->agama) ?? '-' }}</td>
                        <td style="font-size: 8px;">{{ strtoupper($member->pendidikan) ?? '-' }}</td>
                        <td style="font-size: 8px;">{{ strtoupper($member->pekerjaan) ?? '-' }}</td>
                        <td class="text-center" style="font-size: 8px;">{{ strtoupper($member->status_perkawinan) ?? '-' }}</td>
                        <td class="text-center" style="font-size: 8px; font-weight: bold;">{{ strtoupper($member->hubungan_keluarga) ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center" style="color: #9ca3af; font-style: italic; padding: 15px;">Data anggota keluarga kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 50px; color: #6b7280; border: 1px dashed #d1d5db;">
            <p>Belum ada data keluarga (KK) maupun warga yang terdaftar dalam sistem {{ $tenantName }}.</p>
        </div>
    @endforelse

    <div class="signature-box">
        <p>Dikeluarkan pada tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br><strong>Ketua RT {{ substr($tenantName ?? '001', 0, 3) }}</strong></p>
        
        @if(isset($rtSignature) && $rtSignature)
            <img src="{{ $rtSignature }}" alt="Tanda Tangan RT" class="signature-img">
        @else
            <br><br><br>
        @endif
        
        <p><strong>( {{ $rtName ?? '...........................' }} )</strong></p>
    </div>
    <div class="clear"></div>

</body>
</html>
