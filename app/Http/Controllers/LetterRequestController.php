<?php

namespace App\Http\Controllers;

use App\Models\LetterRequest;
use App\Models\Member;
use Illuminate\Http\Request;

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
            } elseif ($surat->status === 'Selesai') {
                $timeline[] = [
                    'waktu' => $surat->updated_at->translatedFormat('d M Y, H:i'),
                    'pesan' => 'Surat selesai ditandatangani Ketua RT & siap diambil.',
                    'is_system' => true,
                ];
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
            ];
        });

        return response()->json([
            'success' => true,
            'nama' => $warga->nama,
            'data' => $formattedSurat,
        ]);
    }
}
