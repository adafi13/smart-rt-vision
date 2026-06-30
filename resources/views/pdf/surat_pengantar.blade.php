<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar RT</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
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
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h4 {
            margin: 0;
            text-decoration: underline;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .title p {
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
            text-align: justify;
        }
        .table-data {
            width: 100%;
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .table-data td {
            vertical-align: top;
            padding: 2px 0;
        }
        .table-data td:nth-child(1) {
            width: 30%;
        }
        .table-data td:nth-child(2) {
            width: 2%;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            width: 40%;
            text-align: center;
            float: right;
        }
        .signature-box-left {
            width: 40%;
            text-align: center;
            float: left;
        }
        .signature-img {
            max-width: 150px;
            max-height: 80px;
            margin: 5px auto;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>RUKUN TETANGGA (RT) {{ substr(app('currentTenant')->name ?? '001', 0, 3) }}</h3>
        <h4>RUKUN WARGA (RW) {{ substr(app('currentTenant')->name ?? '020', -3) }}</h4>
        <p>PERUM. MEGA REGENCY BLOK G-3 NO. 38, JAWA BARAT 17334</p>
    </div>

    <div class="title">
        <h4>SURAT PENGANTAR RT</h4>
        <p>Nomor: {{ sprintf("%03d", $nomor_surat) }} / RT-{{ substr(app('currentTenant')->name ?? '001', 0, 3) }} / {{ date('m') }} / {{ date('Y') }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Ketua RT {{ substr(app('currentTenant')->name ?? '001', 0, 3) }} / RW {{ substr(app('currentTenant')->name ?? '020', -3) }}, dengan ini menerangkan bahwa:</p>
        
        <table class="table-data">
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ $member->nama }}</strong></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $member->tempat_lahir }}, {{ $member->tanggal_lahir ? \Carbon\Carbon::parse($member->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $member->jenis_kelamin }}</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $member->agama }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $member->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nomor KTP (NIK)</td>
                <td>:</td>
                <td>{{ $member->nik }}</td>
            </tr>
            <tr>
                <td>Alamat Tinggal</td>
                <td>:</td>
                <td>{{ $member->family->alamat ?? '-' }}</td>
            </tr>
        </table>

        <p>Orang tersebut di atas adalah benar-benar warga/penduduk kami yang berdomisili di alamat tersebut. Surat pengantar ini dibuat sebagai syarat untuk keperluan:</p>
        <div style="margin-left: 20px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">
            "{{ $keperluan }}"
        </div>

        <p>Demikian surat pengantar ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya oleh instansi/pihak yang berwenang.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box-left">
            <p><br>Pemohon,</p>
            <br><br><br>
            <p><strong>( {{ $member->nama }} )</strong></p>
        </div>

        <div class="signature-box">
            <p>Dikeluarkan pada tanggal: {{ now()->translatedFormat('d F Y') }}<br><strong>Ketua RT {{ substr(app('currentTenant')->name ?? '001', 0, 3) }}</strong></p>
            
            @if($rtSignature)
                <img src="{{ $rtSignature }}" alt="Tanda Tangan RT" class="signature-img">
            @else
                <br><br><br>
            @endif
            
            <p><strong>( {{ $rtName ?? '...........................' }} )</strong></p>
        </div>
        <div class="clear"></div>
        
        @if(isset($rwName) && $rwName)
        <div class="signature-box" style="float: none; margin: 40px auto 0;">
            <p>Mengetahui,<br><strong>Ketua {{ $rwName }}</strong></p>
            
            @if(isset($rwSignature) && $rwSignature)
                <img src="{{ $rwSignature }}" alt="Tanda Tangan RW" class="signature-img">
            @else
                <br><br><br>
            @endif
            
            <p><strong>( {{ $rwHeadName ?? '...........................' }} )</strong></p>
        </div>
        @endif
    </div>

</body>
</html>
