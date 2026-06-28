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

        $ketua = \App\Models\RtStaff::where('tenant_id', $member->tenant_id)
            ->where(function($q) {
                $q->where('position', 'like', '%ketua%')
                  ->orWhere('order_level', 1);
            })
            ->first();

        $tenantId = $member->tenant_id;
        $rtName = \App\Models\Setting::get("tenant_{$tenantId}_rt_name");
        $rtSignaturePath = \App\Models\Setting::get("tenant_{$tenantId}_rt_signature_path");
        
        $rtSignature = null;
        if ($rtSignaturePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($rtSignaturePath)) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($rtSignaturePath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $rtSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $data = [
            'member' => $member,
            'keperluan' => 'Pengurusan Administrasi Kependudukan',
            'nomor_surat' => $member->nomor_surat,
            'jenis_surat' => 'SURAT PENGANTAR',
            'rtName' => $rtName,
            'rtSignature' => $rtSignature,
        ];

        $pdf = Pdf::loadView('pdf.surat_pengantar', $data);

        if (request()->has('preview')) {
            return $pdf->stream('Surat_Pengantar_'.$member->nik.'.pdf');
        }
        return $pdf->download('Surat_Pengantar_'.$member->nik.'.pdf');
    }

    public function cetakSuratPermohonan($id)
    {
        $letterRequest = LetterRequest::with('member.family')->findOrFail($id);
        $member = $letterRequest->member;

        $ketua = \App\Models\RtStaff::where('tenant_id', $member->tenant_id)
            ->where(function($q) {
                $q->where('position', 'like', '%ketua%')
                  ->orWhere('order_level', 1);
            })
            ->first();

        $tenantId = $member->tenant_id;
        $rtName = \App\Models\Setting::get("tenant_{$tenantId}_rt_name");
        $rtSignaturePath = \App\Models\Setting::get("tenant_{$tenantId}_rt_signature_path");
        
        $rtSignature = null;
        if ($rtSignaturePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($rtSignaturePath)) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($rtSignaturePath);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $rtSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $data = [
            'member' => $member,
            'keperluan' => $letterRequest->keperluan,
            'jenis_surat' => $letterRequest->jenis_surat,
            'nomor_surat' => $letterRequest->id,
            'rtName' => $rtName,
            'rtSignature' => $rtSignature,
        ];

        $pdf = Pdf::loadView('pdf.surat_pengantar', $data);

        return $pdf->stream('Surat_Permohonan_'.$member->nama.'.pdf');
    }
}
