<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        // Get all staff for current tenant, except the owner if they have empty role
        // actually owner has empty role or 'owner'. Let's just list all admin_rt for this tenant.
        $tenantId = auth()->user()->tenant_id;
        $staffs = User::where('tenant_id', $tenantId)
            ->where('role', 'admin_rt')
            ->latest()
            ->paginate(15);
            
        return view('admin.staff.index', compact('staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'tenant_role' => 'required|in:sekretaris,bendahara,owner',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_rt',
            'tenant_role' => $request->tenant_role,
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        return back()->with('success', 'Akun pengurus berhasil ditambahkan.');
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Prevent owner from changing their own role to non-owner if they are the only owner
        if ($staff->id === auth()->id() && $request->tenant_role !== 'owner' && empty($staff->tenant_role)) {
            // Check if there's another owner
            $otherOwners = User::where('tenant_id', auth()->user()->tenant_id)
                ->where('role', 'admin_rt')
                ->where(function($q) {
                    $q->where('tenant_role', 'owner')->orWhereNull('tenant_role');
                })
                ->where('id', '!=', $staff->id)
                ->count();
                
            if ($otherOwners === 0) {
                return back()->with('error', 'Tidak bisa mengubah jabatan Anda karena Anda adalah satu-satunya Ketua RT (Owner).');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($staff->id)],
            'password' => 'nullable|string|min:8',
            'tenant_role' => 'required|in:sekretaris,bendahara,owner',
        ]);

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->tenant_role = $request->tenant_role;
        
        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }
        
        $staff->save();

        return back()->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        if ($staff->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Check if deleting the last owner
        if ($staff->isRtOwner()) {
            $otherOwners = User::where('tenant_id', auth()->user()->tenant_id)
                ->where('role', 'admin_rt')
                ->where(function($q) {
                    $q->where('tenant_role', 'owner')->orWhereNull('tenant_role');
                })
                ->where('id', '!=', $staff->id)
                ->count();
                
            if ($otherOwners === 0) {
                return back()->with('error', 'Tidak bisa menghapus Ketua RT (Owner) terakhir.');
            }
        }

        $staff->delete();

        return back()->with('success', 'Akun pengurus berhasil dihapus.');
    }
}
