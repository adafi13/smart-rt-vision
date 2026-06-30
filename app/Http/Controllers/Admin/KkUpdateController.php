<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KkUpdateRequest;
use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KkUpdateController extends Controller
{
    public function index()
    {
        $tenantId = app('currentTenant')->id;
        
        $updates = KkUpdateRequest::with('family')
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.kk_updates.index', compact('updates'));
    }

    public function show(KkUpdateRequest $update)
    {
        if ($update->tenant_id !== app('currentTenant')->id) {
            abort(403);
        }

        $family = $update->family()->with('members')->first();
        $newData = $update->data;

        return view('admin.kk_updates.show', compact('update', 'family', 'newData'));
    }

    public function approve(Request $request, KkUpdateRequest $update)
    {
        if ($update->tenant_id !== app('currentTenant')->id) {
            abort(403);
        }

        if ($update->status !== 'pending') {
            return back()->with('error', 'Permohonan sudah tidak pending.');
        }

        $family = $update->family;
        $newData = $update->data;

        try {
            DB::transaction(function () use ($update, $family, $newData) {
                // Update Family Data
                $family->update([
                    'nama_kepala_keluarga' => $newData['nama_kepala_keluarga'] ?? $family->nama_kepala_keluarga,
                    'alamat' => $newData['alamat'] ?? $family->alamat,
                    'rt' => $newData['rt'] ?? $family->rt,
                    'rw' => $newData['rw'] ?? $family->rw,
                    'desa_kelurahan' => $newData['desa_kelurahan'] ?? $family->desa_kelurahan,
                    'kecamatan' => $newData['kecamatan'] ?? $family->kecamatan,
                    'kabupaten_kota' => $newData['kabupaten_kota'] ?? $family->kabupaten_kota,
                    'provinsi' => $newData['provinsi'] ?? $family->provinsi,
                    'kode_pos' => $newData['kode_pos'] ?? $family->kode_pos,
                ]);

                // Update Photo if exists
                if (!empty($update->foto_path)) {
                    $family->update(['foto_path' => $update->foto_path]);
                }

                // Update Members
                if (isset($newData['anggota']) && is_array($newData['anggota'])) {
                    $incomingNiks = collect($newData['anggota'])->pluck('nik')->filter()->toArray();
                    
                    // Archive members not in the new KK?
                    // Actually, normally we don't delete them, maybe just set them to "Pindah" or "Tidak Aktif"
                    // But to be simple, let's just update existing and add new. 
                    // Let's mark those not in the new KK as "Pindah" (or similar) or leave them to be manually managed.
                    // For safety, let's just update the ones that match NIK, and create new ones.
                    
                    foreach ($newData['anggota'] as $anggotaData) {
                        if (empty($anggotaData['nik']) || empty($anggotaData['nama'])) continue;

                        $member = Member::where('family_id', $family->id)
                            ->where('nik', $anggotaData['nik'])
                            ->first();

                        if ($member) {
                            $member->update([
                                'nama' => $anggotaData['nama'],
                                'jenis_kelamin' => $anggotaData['jenis_kelamin'] ?? $member->jenis_kelamin,
                                'tempat_lahir' => $anggotaData['tempat_lahir'] ?? $member->tempat_lahir,
                                'tanggal_lahir' => $anggotaData['tanggal_lahir'] ?? $member->tanggal_lahir,
                                'agama' => $anggotaData['agama'] ?? $member->agama,
                                'pendidikan' => $anggotaData['pendidikan'] ?? $member->pendidikan,
                                'pekerjaan' => $anggotaData['pekerjaan'] ?? $member->pekerjaan,
                                'status_perkawinan' => $anggotaData['status_perkawinan'] ?? $member->status_perkawinan,
                                'hubungan_keluarga' => $anggotaData['hubungan_keluarga'] ?? $member->hubungan_keluarga,
                                'kewarganegaraan' => $anggotaData['kewarganegaraan'] ?? $member->kewarganegaraan,
                                'nama_ayah' => $anggotaData['nama_ayah'] ?? $member->nama_ayah,
                                'nama_ibu' => $anggotaData['nama_ibu'] ?? $member->nama_ibu,
                            ]);
                        } else {
                            // Check if NIK already exists in another family
                            $existsNik = Member::whereHas('family', function ($q) use ($update) {
                                $q->where('tenant_id', $update->tenant_id);
                            })->where('nik', $anggotaData['nik'])->exists();
                            
                            if (!$existsNik) {
                                $family->members()->create([
                                    'nik' => $anggotaData['nik'],
                                    'nama' => $anggotaData['nama'],
                                    'jenis_kelamin' => $anggotaData['jenis_kelamin'] ?? 'Laki-laki',
                                    'tempat_lahir' => $anggotaData['tempat_lahir'] ?? null,
                                    'tanggal_lahir' => $anggotaData['tanggal_lahir'] ?? null,
                                    'agama' => $anggotaData['agama'] ?? null,
                                    'pendidikan' => $anggotaData['pendidikan'] ?? null,
                                    'pekerjaan' => $anggotaData['pekerjaan'] ?? null,
                                    'status_perkawinan' => $anggotaData['status_perkawinan'] ?? null,
                                    'hubungan_keluarga' => $anggotaData['hubungan_keluarga'] ?? 'Anggota Keluarga',
                                    'kewarganegaraan' => $anggotaData['kewarganegaraan'] ?? 'WNI',
                                    'nama_ayah' => $anggotaData['nama_ayah'] ?? null,
                                    'nama_ibu' => $anggotaData['nama_ibu'] ?? null,
                                ]);
                            }
                        }
                    }
                }

                $update->update(['status' => 'approved']);

                \App\Models\AuditLog::create([
                    'tenant_id' => $update->tenant_id,
                    'user_id' => auth()->id(),
                    'action' => 'admin_approve_family_update',
                    'model_type' => \App\Models\Family::class,
                    'model_id' => $family->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('kk.updates.index')->with('success', 'Pembaruan KK berhasil disetujui dan data warga telah disinkronisasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembaruan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, KkUpdateRequest $update)
    {
        if ($update->tenant_id !== app('currentTenant')->id) {
            abort(403);
        }

        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $update->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('kk.updates.index')->with('success', 'Permohonan pembaruan KK ditolak.');
    }
}
