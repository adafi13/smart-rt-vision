<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|exists:members,nik',
            'kategori' => 'required|string',
            'laporan' => 'required|string|max:1000',
            'foto' => 'required|image|max:2048',
        ], [
            'nik.exists' => 'NIK tidak terdaftar sebagai warga.',
            'foto.max' => 'Ukuran foto terlalu besar (Maks 2MB).',
        ]);

        $warga = Member::where('nik', $request->nik)->first();

        $path = $request->file('foto')->store('laporan-warga', 'public');

        Report::create([
            'member_id' => $warga->id,
            'kategori' => $request->kategori,
            'laporan' => $request->laporan,
            'foto_bukti' => $path,
            'status' => 'Menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim! Terima kasih atas kepedulian Anda.',
        ]);
    }

    public function cekLaporan(Request $request)
    {
        $request->validate(['nik' => 'required|numeric']);

        $warga = Member::where('nik', $request->nik)->first();

        if (! $warga) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak ditemukan dalam pendataan warga.',
            ]);
        }

        $laporan = Report::where('member_id', $warga->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'kategori' => $item->kategori,
                    'isi' => $item->laporan,
                    'status' => $item->status,
                    'tanggapan' => $item->tanggapan_rt ?? 'Menunggu tanggapan dari Pak RT.',
                    'tanggal' => $item->created_at->translatedFormat('d F Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'nama' => $warga->nama,
            'data' => $laporan,
        ]);
    }
}
