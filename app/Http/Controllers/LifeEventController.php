<?php

namespace App\Http\Controllers;

use App\Models\LifeEvent;
use App\Models\Member;
use Illuminate\Http\Request;

class LifeEventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|string',
            'nama_subjek' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'foto' => 'nullable|image|max:4096',
        ]);

        $member = null;
        if ($request->nik_subjek) {
            $member = Member::where('nik', $request->nik_subjek)->first();
        }

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('life-events', 'public');
        }

        LifeEvent::create([
            'member_id' => $member?->id,
            'jenis_laporan' => $request->jenis_laporan,
            'nama_subjek' => $request->nama_subjek,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'keterangan' => $request->keterangan,
            'bukti_dokumen' => $path,
            'status_verifikasi' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan peristiwa berhasil dikirim ke Pak RT!',
        ]);
    }
}
