<x-super-admin-layout title="Tambah Staff">
    <div class="max-w-xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('super-admin.staff.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Staff Super Admin</h1>
            </div>
        </div>

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">
            <ul class="text-sm font-medium text-rose-700 list-disc list-inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('super-admin.staff.store') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password</label>
                <input type="text" name="password" class="input-field" required placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Role / Hak Akses</label>
                <select name="su_role" class="input-field" required>
                    <option value="owner">Owner (Akses Penuh)</option>
                    <option value="finance">Finance (Keuangan)</option>
                    <option value="support">Support (Customer Service)</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('super-admin.staff.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</a>
                <button type="submit" class="btn-primary">Tambahkan</button>
            </div>
        </form>
    </div>
</x-super-admin-layout>
