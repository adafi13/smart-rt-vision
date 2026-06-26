<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function cekIuran(Request $request)
    {
        $request->validate(['nik' => 'required|numeric']);

        $warga = Member::with('family')->where('nik', $request->nik)->first();

        if (! $warga) {
            return response()->json(['success' => false, 'message' => 'NIK tidak ditemukan.']);
        }

        $riwayat = Contribution::where('family_id', $warga->family_id)
            ->orderBy('periode', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => $item->jenis_iuran,
                    'periode' => Carbon::parse($item->periode)->translatedFormat('F Y'),
                    'jumlah' => 'Rp '.number_format($item->jumlah, 0, ',', '.'),
                    'tanggal' => Carbon::parse($item->tanggal_bayar)->format('d/m/Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'nama' => $warga->nama,
            'kk' => $warga->family->nomor_kk,
            'data' => $riwayat,
        ]);
    }
}
