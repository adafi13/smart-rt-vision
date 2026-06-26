<?php

namespace App\Http\Controllers;

use App\Models\LetterRequest;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function cetakSurat($id)
    {
        $member = Member::with('family')->findOrFail($id);

        if (! $member->nomor_surat) {
            $member->nomor_surat = (Member::max('nomor_surat') ?? 0) + 1;
            $member->save();
        }

        $data = [
            'member' => $member,
            'keperluan' => 'Pengurusan Administrasi Kependudukan',
            'nomor_surat' => $member->nomor_surat,
        ];

        $pdf = Pdf::loadView('pdf.surat_pengantar', $data);

        return $pdf->stream('Surat_Pengantar_'.$member->nik.'.pdf');
    }

    public function cetakSuratPermohonan($id)
    {
        $letterRequest = LetterRequest::with('member.family')->findOrFail($id);
        $member = $letterRequest->member;

        $data = [
            'member' => $member,
            'keperluan' => $letterRequest->keperluan,
            'jenis_surat' => $letterRequest->jenis_surat,
            'nomor_surat' => $letterRequest->id,
        ];

        $pdf = Pdf::loadView('pdf.surat_pengantar', $data);

        return $pdf->stream('Surat_Permohonan_'.$member->nama.'.pdf');
    }
}
