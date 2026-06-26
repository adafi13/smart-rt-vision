<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Data Warga</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            color: #111827;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 11px;
        }
        .date {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10px;
            color: #9ca3af;
        }
        .family-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .family-header {
            background-color: #f3f4f6;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .family-header h3 {
            margin: 0 0 4px 0;
            font-size: 13px;
            color: #1f2937;
        }
        .family-header p {
            margin: 0;
            font-size: 10px;
            color: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 7px 10px;
            text-align: left;
        }
        th {
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        
        /* Page number trick for dompdf */
        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    
    <div class="footer">
        Dicetak dari SmartRT Vision pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }} | Halaman <span class="page-number"></span>
    </div>

    <div class="header">
        <h1>Rekapitulasi Data Warga RT</h1>
        <p>Laporan Data Kartu Keluarga dan Anggota Warga</p>
        <div class="date">
            Tgl Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </div>
    </div>

    @forelse($families as $family)
        <div class="family-section">
            <div class="family-header">
                <h3>Keluarga: {{ $family->nama_kepala_keluarga }} (No. KK: {{ $family->nomor_kk }})</h3>
                <p>Alamat: {{ $family->alamat }}, RT {{ $family->rt }}/RW {{ $family->rw }}, {{ $family->desa_kelurahan }}, {{ $family->kecamatan }}</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width="15%">NIK</th>
                        <th width="15%">Nama Lengkap</th>
                        <th width="5%" class="text-center">L/P</th>
                        <th width="10%">T. Lahir</th>
                        <th width="10%">Tgl Lahir</th>
                        <th width="10%">Agama</th>
                        <th width="10%">Pendidikan</th>
                        <th width="10%">Pekerjaan</th>
                        <th width="8%">Sts.Kawin</th>
                        <th width="7%">Hub.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($family->members as $member)
                    <tr>
                        <td style="font-family: monospace; font-size: 9px;">{{ $member->nik }}</td>
                        <td style="font-size: 9px;"><strong>{{ $member->nama }}</strong></td>
                        <td class="text-center" style="font-size: 9px;">{{ $member->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                        <td style="font-size: 9px;">{{ $member->tempat_lahir ?? '-' }}</td>
                        <td style="font-size: 9px;">{{ $member->tanggal_lahir ? \Carbon\Carbon::parse($member->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                        <td style="font-size: 9px;">{{ $member->agama ?? '-' }}</td>
                        <td style="font-size: 9px;">{{ $member->pendidikan ?? '-' }}</td>
                        <td style="font-size: 9px;">{{ $member->pekerjaan ?? '-' }}</td>
                        <td style="font-size: 9px;">{{ $member->status_perkawinan ?? '-' }}</td>
                        <td style="font-size: 9px;">{{ $member->hubungan_keluarga ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center" style="color: #6b7280; font-style: italic;">Belum ada anggota terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 50px; color: #6b7280;">
            <p>Belum ada data warga terdaftar dalam sistem.</p>
        </div>
    @endforelse

</body>
</html>
