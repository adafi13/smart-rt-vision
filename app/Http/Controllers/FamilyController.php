<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\Member;
use App\Services\KkExtractor;
use App\Services\NikValidator;
use Illuminate\Support\Facades\Storage;
use App\Imports\ResidentsImport;
use App\Exports\WargaTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        
        $totalAll = Family::count();
        $totalPending = Family::where('status_verifikasi', 'pending')->count();
        $totalVerified = Family::where('status_verifikasi', 'terverifikasi')->count();
        $totalDitolak = Family::where('status_verifikasi', 'ditolak')->count();
        $totalDraft = Family::where('status_verifikasi', 'draft')->count();

        $query = Family::query();
        
        if ($status) {
            $query->where('status_verifikasi', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_kk', 'like', "%{$search}%")
                  ->orWhere('nama_kepala_keluarga', 'like', "%{$search}%");
            });
        }
        
        $families = $query->latest()->paginate(10)->withQueryString();
        
        return view('kk.index', compact(
            'families',
            'status',
            'totalAll',
            'totalPending',
            'totalVerified',
            'totalDitolak',
            'totalDraft'
        ));
    }

    public function create()
    {
        return view('kk.create');
    }

    public function upload()
    {
        return view('kk.upload');
    }

    public function extract(Request $request, KkExtractor $extractor)
    {
        $tenant = app('currentTenant');

        if (! $tenant->canRunAiExtraction()) {
            return back()->with('error', 'Kuota ekstraksi AI bulan ini sudah habis untuk paket Anda. Silakan upgrade paket di menu Paket & Tagihan.');
        }

        if (! $tenant->canAddMoreKk()) {
            return back()->with('error', 'Jumlah Kartu Keluarga sudah mencapai batas paket Anda. Silakan upgrade paket di menu Paket & Tagihan.');
        }

        $request->validate([
            'foto_kk' => 'required|mimes:jpeg,png,jpg,pdf|max:8192',
        ]);

        $path = $request->file('foto_kk')->store('kk_images', 'public');
        $fullPath = storage_path('app/public/' . $path);

        try {
            $result = $extractor->extract($fullPath);
            $tenant->incrementAiUsage();

            if (!$result) {
                \App\Models\KkScanLog::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => auth()->id(),
                    'uploader_type' => 'admin',
                    'file_path' => $path,
                    'status' => 'failed',
                    'error_message' => 'Gagal mengekstrak data dari gambar (Layanan AI sibuk/timeout).',
                ]);
                return back()->with('error', 'Gagal mengekstrak data dari gambar. Silakan isi manual dengan melewati AI.')->withInput();
            }

            if (isset($result['error'])) {
                // Restore usage count if it failed due to invalid image
                $tenant->decrement('ai_extractions_used');
                \App\Models\KkScanLog::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => auth()->id(),
                    'uploader_type' => 'admin',
                    'file_path' => $path,
                    'status' => 'failed',
                    'error_message' => $result['error'],
                ]);
                return back()->with('error', $result['error'])->withInput();
            }

            // Fallback: Cari nama kepala keluarga dari anggota jika kosong di root
            if (empty($result['nama_kepala_keluarga']) && isset($result['anggota']) && is_array($result['anggota'])) {
                foreach ($result['anggota'] as $anggota) {
                    $hub = strtolower($anggota['hubungan_keluarga'] ?? '');
                    if ($hub === 'kepala keluarga' || $hub === 'suami') {
                        $result['nama_kepala_keluarga'] = $anggota['nama'] ?? '';
                        break;
                    }
                }
            }

            \App\Models\KkScanLog::create([
                'tenant_id' => $tenant->id,
                'user_id' => auth()->id(),
                'uploader_type' => 'admin',
                'file_path' => $path,
                'status' => 'success',
                'nomor_kk' => $result['nomor_kk'] ?? null,
                'nama_kepala_keluarga' => $result['nama_kepala_keluarga'] ?? null,
            ]);

            // Simpan data dan path gambar sementara di session
            session(['kk_extracted' => $result, 'kk_foto_path' => $path]);

            return redirect()->route('kk.verify');
        } catch (\Exception $e) {
            $tenant->decrement('ai_extractions_used');
            \App\Models\KkScanLog::create([
                'tenant_id' => $tenant->id,
                'user_id' => auth()->id(),
                'uploader_type' => 'admin',
                'file_path' => $path,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function verify()
    {
        if (!session()->has('kk_extracted')) {
            return redirect()->route('kk.upload');
        }

        $data = session('kk_extracted');
        $fotoPath = session('kk_foto_path');

        $warningsKk = NikValidator::validateKk($data['nomor_kk'] ?? '');
        $warningsAnggota = [];

        // 1. Dapatkan Tahun Lahir Kepala Keluarga (KK)
        $parentBirthYear = null;
        if (isset($data['anggota']) && is_array($data['anggota'])) {
            foreach ($data['anggota'] as $anggota) {
                $hubungan = strtolower($anggota['hubungan_keluarga'] ?? '');
                if (($hubungan === 'kepala keluarga' || $hubungan === 'suami') && !empty($anggota['tanggal_lahir'])) {
                    $parentBirthYear = (int)date('Y', strtotime($anggota['tanggal_lahir']));
                    break;
                }
            }
        }

        // 2. Jalankan validasi dasar & validasi relasi umur orang tua-anak
        if (isset($data['anggota']) && is_array($data['anggota'])) {
            foreach ($data['anggota'] as $i => $anggota) {
                $warningsAnggota[$i] = NikValidator::validate($anggota);

                $hubungan = strtolower($anggota['hubungan_keluarga'] ?? '');
                if ($hubungan === 'anak' && !empty($anggota['tanggal_lahir']) && $parentBirthYear !== null) {
                    $childBirthYear = (int)date('Y', strtotime($anggota['tanggal_lahir']));
                    // Jika anak lahir sebelum orang tua atau selisih umur kurang dari 14 tahun
                    if ($childBirthYear <= $parentBirthYear || ($childBirthYear - $parentBirthYear) < 14) {
                        $warningsAnggota[$i]['tanggal_lahir'][] = 'Peringatan: Tahun lahir anak (' . $childBirthYear . ') tidak selaras dengan Kepala Keluarga (' . $parentBirthYear . ').';
                    }
                }
            }
        }

        return view('kk.verify', compact('data', 'fotoPath', 'warningsKk', 'warningsAnggota'));
    }

    public function store(Request $request)
    {
        if (! app('currentTenant')->canAddMoreKk()) {
            return redirect()->route('kk.upload')->with('error', 'Jumlah Kartu Keluarga sudah mencapai batas paket Anda. Silakan upgrade paket di menu Paket & Tagihan.');
        }

        $request->validate([
            'nomor_kk' => 'required|string|size:16',
            'nama_kepala_keluarga' => 'required|string',
        ]);

        $tenantId = app('currentTenant')->id;

        // 1. Validasi Keunikan Nomor KK pada RT ini
        $existsKk = Family::where('tenant_id', $tenantId)
            ->where('nomor_kk', $request->nomor_kk)
            ->exists();
        if ($existsKk) {
            return back()->with('error', 'Gagal: Nomor KK ' . $request->nomor_kk . ' sudah terdaftar di database RT Anda.')->withInput();
        }

        // 2. Validasi Keunikan NIK Anggota pada RT ini
        if ($request->has('anggota') && is_array($request->anggota)) {
            foreach ($request->anggota as $anggotaData) {
                if (empty($anggotaData['nik'])) continue;
                
                $existsNik = Member::whereHas('family', function ($q) use ($tenantId) {
                    $q->where('tenant_id', $tenantId);
                })->where('nik', $anggotaData['nik'])->exists();
                
                if ($existsNik) {
                    return back()->with('error', 'Gagal: Warga dengan NIK ' . $anggotaData['nik'] . ' (' . ($anggotaData['nama'] ?? 'Tanpa Nama') . ') sudah terdaftar di database RT Anda.')->withInput();
                }
            }
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $family = Family::create([
                    'nomor_kk' => $request->nomor_kk,
                    'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
                    'alamat' => $request->alamat ?? '',
                    'rt' => $request->rt ?? '',
                    'rw' => $request->rw ?? '',
                    'desa_kelurahan' => $request->desa_kelurahan ?? '',
                    'kecamatan' => $request->kecamatan ?? '',
                    'kabupaten_kota' => $request->kabupaten_kota ?? '',
                    'provinsi' => $request->provinsi ?? '',
                    'kode_pos' => $request->kode_pos ?? '',
                    'foto_path' => $request->foto_path ?? null,
                    'status_verifikasi' => 'terverifikasi',
                ]);

                if ($request->has('anggota') && is_array($request->anggota)) {
                    foreach ($request->anggota as $anggotaData) {
                        if (empty($anggotaData['nik']) || empty($anggotaData['nama'])) continue;

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

                \App\Models\AuditLog::create([
                    'tenant_id' => app('currentTenant')->id,
                    'user_id' => auth()->id(),
                    'action' => 'create_family',
                    'model_type' => Family::class,
                    'model_id' => $family->id,
                    'new_values' => ['nomor_kk' => $family->nomor_kk, 'nama' => $family->nama_kepala_keluarga],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            session()->forget(['kk_extracted', 'kk_foto_path']);
            return redirect()->route('kk.index')->with('success', 'Data Kartu Keluarga berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan KK: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Family $family)
    {
        $family->load('members');
        return view('kk.show', compact('family'));
    }

    public function edit(Family $family)
    {
        return view('kk.edit', compact('family'));
    }

    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'nomor_kk' => 'required|string|size:16',
            'nama_kepala_keluarga' => 'required|string',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
            'desa_kelurahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kabupaten_kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kode_pos' => 'nullable|string',
        ]);

        $family->update($validated);
        return redirect()->route('kk.index')->with('success', 'Data KK diupdate.');
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return redirect()->route('kk.index')->with('success', 'Data KK dihapus.');
    }

    public function importForm()
    {
        return view('kk.import');
    }

    public function import(Request $request)
    {
        if (! app('currentTenant')->canAddMoreKk()) {
            return redirect()->route('kk.import.form')->with('error', 'Jumlah Kartu Keluarga sudah mencapai batas paket Anda. Silakan upgrade paket di menu Paket & Tagihan sebelum mengimpor data baru.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new ResidentsImport;
        Excel::import($import, $request->file('file'));

        return redirect()->route('kk.index')->with(
            'success',
            "Import selesai: {$import->imported} warga berhasil ditambahkan/diperbarui".
            ($import->skipped ? ", {$import->skipped} baris dilewati karena data tidak lengkap." : '.')
        );
    }

    public function downloadTemplate()
    {
        return Excel::download(new WargaTemplateExport, 'Format_Import_Warga.xlsx');
    }

    public function approve(Family $family)
    {
        $family->update([
            'status_verifikasi' => 'terverifikasi',
            'alasan_penolakan' => null,
        ]);

        \App\Models\AuditLog::create([
            'tenant_id' => $family->tenant_id,
            'user_id' => auth()->id(),
            'action' => 'approve_citizen_family',
            'model_type' => Family::class,
            'model_id' => $family->id,
            'new_values' => ['nomor_kk' => $family->nomor_kk, 'status_verifikasi' => 'terverifikasi'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('kk.show', $family)->with('success', 'Pendaftaran Kartu Keluarga berhasil disetujui.');
    }

    public function reject(Request $request, Family $family)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $family->update([
            'status_verifikasi' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        \App\Models\AuditLog::create([
            'tenant_id' => $family->tenant_id,
            'user_id' => auth()->id(),
            'action' => 'reject_citizen_family',
            'model_type' => Family::class,
            'model_id' => $family->id,
            'new_values' => ['nomor_kk' => $family->nomor_kk, 'status_verifikasi' => 'ditolak', 'alasan' => $request->alasan_penolakan],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('kk.show', $family)->with('success', 'Pendaftaran Kartu Keluarga telah ditolak.');
    }
}
