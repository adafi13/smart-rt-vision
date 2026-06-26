<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar - {{ $member->nama }}</title>
    <style>
        @page { size: A4; margin: 2cm 2.5cm 2cm 3cm; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.3;
            color: #000;
        }

        .header { text-align: center; }
        .header h4 { font-size: 12pt; margin: 0; font-weight: normal; }
        .header h2 { font-size: 14pt; margin: 2px 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header p { font-size: 10pt; margin: 0; font-style: italic; }

        .line-thick { border-bottom: 3px solid #000; margin-top: 5px; }
        .line-thin { border-bottom: 1px solid #000; margin-top: 2px; margin-bottom: 25px; }

        .judul-container { text-align: center; margin-bottom: 30px; }
        .judul-text {
            font-size: 14pt; font-weight: bold; text-transform: uppercase;
            text-decoration: underline; letter-spacing: 1.5px;
        }
        .nomor-surat { font-size: 12pt; margin-top: 5px; }

        .text-justify { text-align: justify; text-indent: 40px; margin-bottom: 10px; }

        .data-table { width: 100%; margin-left: 20px; margin-bottom: 15px; border-collapse: collapse; }
        .data-table td { vertical-align: top; padding: 3px 0; }
        .col-label { width: 170px; }
        .col-sep { width: 20px; text-align: center; }
        .col-val { font-weight: bold; }

        .keperluan-box {
            text-align: center; border: 2px solid #000; padding: 10px;
            margin: 15px 40px; font-weight: bold; text-transform: uppercase;
            background-color: #f9f9f9;
        }

        .signature-wrapper {
            width: 100%;
            margin-top: 40px;
            display: table;
            border-collapse: collapse;
        }
        .sign-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-left { padding-top: 25px; }
        .sign-col p { margin: 0; }
        .ttd-title { margin-bottom: 5px; }
        .ttd-space { height: 110px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h4>RUKUN TETANGGA (RT) {{ $member->family->rt }} RUKUN WARGA (RW) {{ $member->family->rw }}</h4>
        <h2>{{ $member->family->desa_kelurahan ?: $member->family->kelurahan ?? 'KELURAHAN' }}</h2>
        <h4>{{ $member->family->kecamatan }} - {{ $member->family->kabupaten_kota }}</h4>
        <p>{{ $member->family->alamat }}, {{ $member->family->provinsi }} {{ $member->family->kode_pos }}</p>
    </div>
    <div class="line-thick"></div>
    <div class="line-thin"></div>

    <div class="judul-container">
        <div class="judul-text">{{ $jenis_surat ?? 'SURAT PENGANTAR' }}</div>
        <div class="nomor-surat">
            Nomor: {{ str_pad($nomor_surat, 3, '0', STR_PAD_LEFT) }} / RT.{{ $member->family->rt }} - RW.{{ $member->family->rw }} / {{ date('m') }} / {{ date('Y') }}
        </div>
    </div>

    <p class="text-justify">
        Yang bertanda tangan di bawah ini, Ketua RT {{ $member->family->rt }} RW {{ $member->family->rw }}, menerangkan dengan sebenarnya bahwa:
    </p>

    <table class="data-table">
        <tr><td class="col-label">Nama Lengkap</td><td class="col-sep">:</td><td class="col-val">{{ strtoupper($member->nama) }}</td></tr>
        <tr><td class="col-label">NIK / No. KTP</td><td class="col-sep">:</td><td>{{ $member->nik }}</td></tr>
        <tr><td class="col-label">Tempat, Tgl Lahir</td><td class="col-sep">:</td><td>{{ $member->tempat_lahir }}@if($member->tanggal_lahir), {{ \Carbon\Carbon::parse($member->tanggal_lahir)->translatedFormat('d F Y') }}@endif</td></tr>
        <tr><td class="col-label">Jenis Kelamin</td><td class="col-sep">:</td><td>{{ $member->jenis_kelamin }}</td></tr>
        <tr><td class="col-label">Agama</td><td class="col-sep">:</td><td>{{ $member->agama ?? '-' }}</td></tr>
        <tr><td class="col-label">Pekerjaan</td><td class="col-sep">:</td><td>{{ $member->pekerjaan ?? '-' }}</td></tr>
        <tr><td class="col-label">Alamat Domisili</td><td class="col-sep">:</td><td>{{ $member->family->alamat }}</td></tr>
    </table>

    <p class="text-justify">
        Orang tersebut di atas adalah benar-benar warga kami yang berdomisili di lingkungan RT {{ $member->family->rt }} RW {{ $member->family->rw }}. Surat ini diberikan untuk keperluan:
    </p>

    <div class="keperluan-box">
        {{ $keperluan ?? 'PENGURUSAN ADMINISTRASI KEPENDUDUKAN' }}
    </div>

    <p class="text-justify">
        Surat ini berlaku selama <strong>30 (tiga puluh) hari</strong> sejak tanggal dikeluarkan. Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <div class="signature-wrapper">
        <div class="sign-col sign-left">
            <div class="ttd-title">
                <p>Pemohon / Warga,</p>
            </div>
            <div class="ttd-space"></div>
            <p class="ttd-name">{{ strtoupper($member->nama) }}</p>
        </div>

        <div class="sign-col">
            <div class="ttd-title">
                <p>{{ now()->translatedFormat('d F Y') }}</p>
                <p>Ketua RT {{ $member->family->rt }} RW {{ $member->family->rw }}</p>
            </div>
            <div class="ttd-space"></div>
            <p class="ttd-name">{{ auth()->user()->name ?? 'Pengurus RT' }}</p>
        </div>
    </div>

</body>
</html>
