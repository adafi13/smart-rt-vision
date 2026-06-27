<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RtStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RtStaffController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $staffs = RtStaff::where('tenant_id', $tenantId)
            ->orderBy('order_level')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.organisasi.index', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_level' => 'required|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');
        $data['tenant_id'] = auth()->user()->tenant_id;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff-photos', 'public');
        }

        RtStaff::create($data);

        return back()->with('success', 'Anggota jajaran pengurus berhasil ditambahkan.');
    }

    public function update(Request $request, RtStaff $staff)
    {
        if ($staff->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_level' => 'required|integer|min:1',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }
            $data['photo'] = $request->file('photo')->store('staff-photos', 'public');
        }

        $staff->update($data);

        return back()->with('success', 'Data jajaran pengurus berhasil diperbarui.');
    }

    public function destroy(RtStaff $staff)
    {
        if ($staff->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($staff->photo) {
            Storage::disk('public')->delete($staff->photo);
        }

        $staff->delete();

        return back()->with('success', 'Anggota jajaran pengurus berhasil dihapus.');
    }
}
