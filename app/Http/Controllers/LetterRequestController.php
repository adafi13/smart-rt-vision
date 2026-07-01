<?php

namespace App\Http\Controllers;

use App\Models\LetterRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

class LetterRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|exists:members,nik',
            'jenis_surat' => 'required|string',
            'keperluan' => 'required|string|max:500',
        ], [
            'nik.exists' => 'Maaf, NIK Anda belum terdaftar sebagai warga.',
            'nik.required' => 'Mohon isi NIK Anda.',
        ]);

        $warga = Member::where('nik', $request->nik)->first();

        LetterRequest::create([
            'member_id' => $warga->id,
            'jenis_surat' => $request->jenis_surat,
            'keperluan' => $request->keperluan,
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil dikirim! Silakan hubungi Pak RT untuk konfirmasi.',
        ]);
    }

    public function cekSurat(Request $request)
    {
        $request->validate(['nik' => 'required|numeric']);

        $warga = Member::where('nik', $request->nik)->first();

        if (! $warga) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak ditemukan. Silakan periksa kembali NIK Anda.'
            ]);
        }

        $suratList = LetterRequest::where('member_id', $warga->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $formattedSurat = $suratList->map(function ($surat) {
            $timeline = [];
            
            // Step 1: Diajukan
            $timeline[] = [
                'waktu' => $surat->created_at->translatedFormat('d M Y, H:i'),
                'pesan' => 'Permohonan diajukan oleh warga.',
                'is_system' => true,
            ];
            
            // Step 2: Status update
            if ($surat->status === 'Diproses') {
                $timeline[] = [
                    'waktu' => $surat->updated_at->translatedFormat('d M Y, H:i'),
                    'pesan' => 'Sedang diverifikasi / diproses oleh pengurus RT.',
                    'is_system' => true,
                ];
            } elseif ($surat->status === 'Selesai' || $surat->status === 'Disetujui') {
                if ($surat->rw_status === 'Pending') {
                    $timeline[] = [
                        'waktu' => $surat->updated_at->translatedFormat('d M Y, H:i'),
                        'pesan' => 'Telah disetujui RT. Saat ini sedang menunggu tanda tangan Ketua RW.',
                        'is_system' => true,
                    ];
                } else {
                    $pesan = $surat->rw_status === 'Disetujui' ? 'Surat selesai ditandatangani Ketua RT & Ketua RW, dan siap diunduh.' : 'Surat selesai ditandatangani Ketua RT & siap diunduh.';
                    $timeline[] = [
                        'waktu' => $surat->updated_at->translatedFormat('d M Y, H:i'),
                        'pesan' => $pesan,
                        'is_system' => true,
                    ];
                    // Add download url
                    $surat->download_url = route('unduh-surat', ['tenant' => app('currentTenant')->slug, 'id' => $surat->id]);
                }
            } elseif ($surat->status === 'Ditolak') {
                $timeline[] = [
                    'waktu' => $surat->updated_at->translatedFormat('d M Y, H:i'),
                    'pesan' => 'Permohonan ditolak oleh pengurus RT.',
                    'is_system' => true,
                ];
            }

            return [
                'jenis_surat' => $surat->jenis_surat,
                'keperluan' => $surat->keperluan,
                'status' => $surat->status,
                'tanggal_pengajuan' => $surat->created_at->translatedFormat('d F Y'),
                'timeline' => array_reverse($timeline),
                'download_url' => $surat->download_url ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'nama' => $warga->nama,
            'data' => $formattedSurat,
        ]);
    }

    public function downloadPdfPublic($tenant, $id)
    {
        $letterRequest = LetterRequest::where('id', $id)
            ->whereHas('member.family.tenant', function ($q) {
                $q->where('id', app('currentTenant')->id);
            })->firstOrFail();

        if ($letterRequest->status !== 'Disetujui' && $letterRequest->status !== 'Selesai') {
            abort(403, 'Surat belum disetujui.');
        }

        $tenantId = app('currentTenant')->id;
        $rtSignaturePath = Setting::get("tenant_{$tenantId}_rt_signature_path");
        $rtSignature = $rtSignaturePath ? public_path('storage/' . $rtSignaturePath) : null;
        
        if ($rtSignature && file_exists($rtSignature)) {
            $type = pathinfo($rtSignature, PATHINFO_EXTENSION);
            $data = file_get_contents($rtSignature);
            $rtSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $rtAdmin = app('currentTenant')->users()->where('role', 'admin_rt')->first();
        $fallbackRtName = $rtAdmin ? $rtAdmin->name : '...........................';
        $rtName = Setting::get("tenant_{$tenantId}_rt_name") ?: $fallbackRtName;
        
        $rw = app('currentTenant')->rw;
        $rwSignature = null;
        $rwName = null;
        $rwHeadName = null;

        if ($letterRequest->rw_status === 'Disetujui' && $rw) {
            $rwSignaturePath = Setting::get("rw_{$rw->id}_signature_path");
            $rwSignatureFile = $rwSignaturePath ? public_path('storage/' . $rwSignaturePath) : null;
            if ($rwSignatureFile && file_exists($rwSignatureFile)) {
                $type = pathinfo($rwSignatureFile, PATHINFO_EXTENSION);
                $data = file_get_contents($rwSignatureFile);
                $rwSignature = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            $rwName = $rw->name;
            
            $rwAdmin = $rw->users()->where('role', 'admin_rw')->first();
            $fallbackRwName = $rwAdmin ? $rwAdmin->name : '...........................';
            $rwHeadName = Setting::get("rw_{$rw->id}_head_name") ?: $fallbackRwName;
        }
        
        $member = $letterRequest->member;

        $pdf = Pdf::loadView('pdf.surat_pengantar', [
            'nomor_surat' => $letterRequest->id,
            'keperluan' => $letterRequest->keperluan,
            'jenis_surat' => $letterRequest->jenis_surat,
            'member' => $member,
            'tenant' => app('currentTenant'),
            'rtSignature' => $rtSignature,
            'rtName' => $rtName,
            'rwSignature' => $rwSignature,
            'rwName' => $rwName,
            'rwHeadName' => $rwHeadName,
        ]);

        return $pdf->download("Surat_Pengantar_{$member->nama}.pdf");
    }
}
