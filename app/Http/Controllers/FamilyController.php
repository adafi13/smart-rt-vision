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
        $query = Family::query();
        if ($search = $request->input('search')) {
            $query->where('nomor_kk', 'like', "%{$search}%")
                  ->orWhere('nama_kepala_keluarga', 'like', "%{$search}%");
        }
        $families = $query->latest()->paginate(10);
        return view('kk.index', compact('families'));
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
            'foto_kk' => 'required|image|max:8192',
        ]);

        $path = $request->file('foto_kk')->store('kk_images');
        $fullPath = storage_path('app/private/' . $path);

        // Laravel 11 stores private files in storage/app/private by default
        if(!file_exists($fullPath)) {
            $fullPath = storage_path('app/' . $path);
        }

        $result = $extractor->extract($fullPath);
        $tenant->incrementAiUsage();

        if (!$result) {
            return back()->with('error', 'Gagal mengekstrak data dari gambar. Silakan isi manual dengan melewati AI.')->withInput();
        }

        // Simpan data dan path gambar sementara di session
        session(['kk_extracted' => $result, 'kk_foto_path' => $path]);

        return redirect()->route('kk.verify');
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
        if (isset($data['anggota']) && is_array($data['anggota'])) {
            foreach ($data['anggota'] as $i => $anggota) {
                $warningsAnggota[$i] = NikValidator::validate($anggota);
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
            'nomor_kk' => 'required|string',
            'nama_kepala_keluarga' => 'required|string',
        ]);

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
        $family->update($request->except(['_token', '_method']));
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
}
