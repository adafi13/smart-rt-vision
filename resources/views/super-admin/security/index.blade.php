<x-super-admin-layout title="Keamanan Akun">
    <div class="max-w-2xl space-y-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Keamanan Akun</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi profil dan password akun Super Admin Anda.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-3 text-sm font-semibold text-emerald-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">
            <ul class="text-sm font-medium text-rose-700 list-disc list-inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('super-admin.security.update') }}" method="POST">
            @csrf

            {{-- Profil --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4 mb-5">
                <h2 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">Informasi Profil</h2>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="input-field" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="input-field" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Role</label>
                    <input type="text" value="{{ ucfirst(auth()->user()->su_role ?? 'owner') }}" class="input-field bg-gray-50" readonly>
                </div>
            </div>

            {{-- Password --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4 mb-5">
                <h2 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">Ubah Password</h2>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" class="input-field" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru</label>
                    <input type="password" name="password" class="input-field" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-super-admin-layout>
