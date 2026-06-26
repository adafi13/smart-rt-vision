<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminStaffController extends Controller
{
    public function index()
    {
        $staffs = User::where('is_super_admin', true)->latest()->paginate(20);
        return view('super-admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('super-admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'su_role'  => 'required|in:owner,finance,support',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'is_super_admin' => true,
            'su_role'        => $request->su_role,
        ]);

        return redirect()->route('super-admin.staff.index')
            ->with('success', 'Staff Super Admin berhasil ditambahkan.');
    }

    public function edit(User $staff)
    {
        abort_unless($staff->is_super_admin, 404);
        return view('super-admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        abort_unless($staff->is_super_admin, 404);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($staff->id)],
            'password' => 'nullable|string|min:8',
            'su_role'  => 'required|in:owner,finance,support',
        ]);

        // Prevent changing own role
        if ($staff->id === auth()->id() && $request->su_role !== ($staff->su_role ?? 'owner')) {
            return back()->with('error', 'Anda tidak bisa mengubah role Anda sendiri.');
        }

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'su_role' => $request->su_role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('super-admin.staff.index')
            ->with('success', 'Data staff berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        abort_unless($staff->is_super_admin, 404);

        if ($staff->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $staff->delete();

        return redirect()->route('super-admin.staff.index')
            ->with('success', 'Staff berhasil dihapus.');
    }
}
