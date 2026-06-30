<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Tambah Rukun Tetangga (RT)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Daftarkan RT baru di bawah naungan organisasi RW Anda.</p>
            </div>
            <a href="{{ route('rw.tenants.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form method="POST" action="{{ route('rw.tenants.store') }}" class="p-6 sm:p-8 space-y-8">
                @csrf

                <!-- Data RT -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide border-b border-gray-100 pb-2">Informasi Rukun Tetangga (RT)</h3>
                    
                    <div class="mb-4">
                        <x-input-label for="rt_name" value="Nama RT (Contoh: RT 01)" />
                        <x-text-input id="rt_name" class="block mt-1 w-full" type="text" name="rt_name" :value="old('rt_name')" required autofocus placeholder="Masukkan nama RT..." />
                        <p class="text-xs text-gray-500 mt-1">Ini akan digunakan sebagai tautan web khusus untuk RT tersebut (misal: /rt-01).</p>
                        <x-input-error :messages="$errors->get('rt_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Data Admin / Ketua RT -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide border-b border-gray-100 pb-2">Data Ketua / Admin RT</h3>
                    <p class="text-xs text-gray-500 mb-4">Akun ini akan digunakan oleh pengurus RT untuk login dan mengelola warganya.</p>

                    <div class="mb-4">
                        <x-input-label for="admin_name" value="Nama Lengkap Ketua/Admin RT" />
                        <x-text-input id="admin_name" class="block mt-1 w-full" type="text" name="admin_name" :value="old('admin_name')" required placeholder="Nama lengkap..." />
                        <x-input-error :messages="$errors->get('admin_name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" value="Alamat Email (Untuk Login)" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="email@contoh.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="password" value="Password" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('rw.tenants.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm transition-colors">
                        Simpan & Daftarkan RT
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-rw-app-layout>
