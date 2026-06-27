<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'nullable|numeric|exists:members,nik',
            'reporter_name' => 'required_without:nik|nullable|string|max:255',
            'reporter_phone' => 'required_without:nik|nullable|string|max:50',
            'kategori' => 'required|string',
            'laporan' => 'required|string|max:1000',
            'foto' => 'nullable|image|max:2048',
        ], [
            'nik.exists' => 'NIK tidak terdaftar sebagai warga.',
            'reporter_name.required_without' => 'Nama pelapor wajib diisi jika Anda bukan warga terdaftar.',
            'reporter_phone.required_without' => 'Nomor WhatsApp wajib diisi jika Anda bukan warga terdaftar.',
            'foto.max' => 'Ukuran foto terlalu besar (Maks 2MB).',
        ]);

        $warga = null;
        if ($request->nik) {
            $warga = Member::where('nik', $request->nik)->first();
        }

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('laporan-warga', 'public');
        }

        $ticket_number = 'TKT-'.date('ymd').'-'.strtoupper(Str::random(5));

        Report::create([
            'tenant_id' => app('currentTenant')->id,
            'ticket_number' => $ticket_number,
            'member_id' => $warga ? $warga->id : null,
            'reporter_name' => $warga ? null : $request->reporter_name,
            'reporter_phone' => $warga ? null : $request->reporter_phone,
            'kategori' => $request->kategori,
            'laporan' => $request->laporan,
            'foto_bukti' => $path,
            'status' => 'Menunggu',
        ]);

        return response()->json([
            'success' => true,
            'ticket_number' => $ticket_number,
            'message' => "Laporan berhasil dikirim! Nomor Tiket Anda: $ticket_number. Simpan nomor ini untuk melacak laporan.",
        ]);
    }

    public function cekLaporan(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
        ]);

        $laporan = Report::with('replies.user')
            ->where('tenant_id', app('currentTenant')->id)
            ->where('ticket_number', $request->ticket_number)
            ->first();

        if (! $laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan. Periksa kembali nomor tiket Anda.',
            ]);
        }

        // Format timeline
        $timeline = [];
        
        // Initial entry
        $timeline[] = [
            'waktu' => $laporan->created_at->translatedFormat('d M Y, H:i'),
            'pesan' => 'Laporan Diterima oleh Sistem.',
            'is_system' => true,
        ];

        // Format old tanggapan
        if ($laporan->tanggapan_rt && $laporan->replies->isEmpty()) {
            $timeline[] = [
                'waktu' => $laporan->updated_at->translatedFormat('d M Y, H:i'),
                'pesan' => 'Tanggapan RT: ' . $laporan->tanggapan_rt,
                'is_system' => false,
                'sender' => 'Pengurus RT',
            ];
        }

        // Format replies
        foreach ($laporan->replies as $reply) {
            $timeline[] = [
                'waktu' => $reply->created_at->translatedFormat('d M Y, H:i'),
                'pesan' => $reply->message,
                'is_system' => $reply->is_system,
                'sender' => $reply->is_system ? 'Sistem' : ($reply->user->name ?? 'Pengurus RT'),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ticket_number' => $laporan->ticket_number,
                'pelapor' => $laporan->member ? $laporan->member->nama : $laporan->reporter_name,
                'kategori' => $laporan->kategori,
                'isi' => $laporan->laporan,
                'status' => $laporan->status,
                'timeline' => array_reverse($timeline), // Newest first
            ]
        ]);
    }
}
