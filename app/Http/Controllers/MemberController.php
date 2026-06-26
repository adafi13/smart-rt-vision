<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\Member;

class MemberController extends Controller
{
    private function memberRules(): array
    {
        return [
            'nik' => 'required|string|size:16',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string',
            'pendidikan' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'hubungan_keluarga' => 'required|string',
            'kewarganegaraan' => 'nullable|string',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'status_warga' => 'nullable|string',
        ];
    }

    public function store(Request $request, Family $family)
    {
        $request->validate($this->memberRules());

        $family->members()->create($request->only(array_keys($this->memberRules())));

        return back()->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function updateMember(Request $request, Family $family, Member $member)
    {
        $request->validate($this->memberRules());

        $member->update($request->only(array_keys($this->memberRules())));

        return back()->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroyMember(Family $family, Member $member)
    {
        $member->delete();

        return back()->with('success', 'Anggota keluarga dihapus.');
    }

    public function index(Request $request)
    {
        $query = Member::with('family');
        if ($search = $request->input('search')) {
            $query->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }
        if ($gender = $request->input('jenis_kelamin')) {
            $query->where('jenis_kelamin', $gender);
        }

        $sort = $request->input('sort', 'keluarga');
        if ($sort == 'nama_asc') {
            $query->orderBy('nama', 'asc');
        } elseif ($sort == 'nama_desc') {
            $query->orderBy('nama', 'desc');
        } elseif ($sort == 'umur_tua') {
            $query->orderBy('tanggal_lahir', 'asc');
        } elseif ($sort == 'umur_muda') {
            $query->orderBy('tanggal_lahir', 'desc');
        } else {
            // Default: by family
            $query->orderByDesc('family_id')->orderBy('id');
        }

        $members = $query->paginate(10);
        return view('warga.index', compact('members'));
    }
}
