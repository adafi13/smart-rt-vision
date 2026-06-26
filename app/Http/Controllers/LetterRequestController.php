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
}
