<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.staff.index') }}" class="p-2 -ml-2 text-gray-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight">Edit Data Pengurus: {{ $staff->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi dan hak akses pengurus ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Personal Info Section -->
                <div class="p-6 sm:p-8 border-b border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Informasi Pribadi</h3>
                            <p class="text-sm text-gray-500">Data diri pengurus saat ini.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label class="label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $staff->name) }}" class="input-field bg-gray-50 focus:bg-white" required>
                            @error('name') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <label class="label">Email / Username</label>
                            <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="input-field bg-gray-50 focus:bg-white" required>
                            <p class="text-[11px] text-gray-500 mt-1.5">Gunakan email aktif atau format bebas (cth: ketua@rt). Ini digunakan untuk login.</p>
                            @error('email') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Role Section -->
                <div class="p-6 sm:p-8 bg-gray-50/50">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Hak Akses & Jabatan</h3>
                            <p class="text-sm text-gray-500">Ubah menu apa saja yang bisa diakses oleh pengurus ini.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <div class="flex items-center h-5">
                                <input name="tenant_role" type="radio" value="sekretaris" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ $staff->tenant_role === 'sekretaris' ? 'checked' : '' }}>
                            </div>
                            <div class="ms-4">
                                <span class="block text-sm font-bold text-gray-900">Sekretaris</span>
                                <span class="block text-xs text-gray-500 mt-0.5">Akses ke Data Warga, Surat Pengantar, Pengumuman, dan Administrasi Organisasi.</span>
                            </div>
                        </label>
                        
                        <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <div class="flex items-center h-5">
                                <input name="tenant_role" type="radio" value="bendahara" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ $staff->tenant_role === 'bendahara' ? 'checked' : '' }}>
                            </div>
                            <div class="ms-4">
                                <span class="block text-sm font-bold text-gray-900">Bendahara</span>
                                <span class="block text-xs text-gray-500 mt-0.5">Akses ke Buku Kas, Tagihan Iuran Bulanan, dan Laporan Keuangan RT.</span>
                            </div>
                        </label>
                        
                        <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <div class="flex items-center h-5">
                                <input name="tenant_role" type="radio" value="wakil_ketua" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ $staff->tenant_role === 'wakil_ketua' ? 'checked' : '' }}>
                            </div>
                            <div class="ms-4">
                                <span class="block text-sm font-bold text-gray-900">Wakil Ketua RT</span>
                                <span class="block text-xs text-gray-500 mt-0.5">Akses penuh hampir sama seperti Ketua RT, untuk membantu kepemimpinan wilayah.</span>
                            </div>
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                                <div class="flex items-center h-5">
                                    <input name="tenant_role" type="radio" value="keamanan" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ $staff->tenant_role === 'keamanan' ? 'checked' : '' }}>
                                </div>
                                <div class="ms-4">
                                    <span class="block text-sm font-bold text-gray-900">Keamanan</span>
                                    <span class="block text-[11px] text-gray-500 mt-0.5">Jadwal Ronda, Panic Alert, dan Laporan Darurat.</span>
                                </div>
                            </label>
                            
                            <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                                <div class="flex items-center h-5">
                                    <input name="tenant_role" type="radio" value="humas" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ $staff->tenant_role === 'humas' ? 'checked' : '' }}>
                                </div>
                                <div class="ms-4">
                                    <span class="block text-sm font-bold text-gray-900">Humas</span>
                                    <span class="block text-[11px] text-gray-500 mt-0.5">Berita, Polling, dan Buku Tamu RT.</span>
                                </div>
                            </label>

                            <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                                <div class="flex items-center h-5">
                                    <input name="tenant_role" type="radio" value="pembangunan" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ $staff->tenant_role === 'pembangunan' ? 'checked' : '' }}>
                                </div>
                                <div class="ms-4">
                                    <span class="block text-sm font-bold text-gray-900">Pembangunan</span>
                                    <span class="block text-[11px] text-gray-500 mt-0.5">Manajemen Inventaris, Fasilitas, dan Proyek Warga.</span>
                                </div>
                            </label>
                            
                            <label class="relative flex p-4 cursor-pointer bg-white border border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50/30 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                                <div class="flex items-center h-5">
                                    <input name="tenant_role" type="radio" value="owner" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-indigo-600" required {{ (empty($staff->tenant_role) || $staff->tenant_role === 'owner') ? 'checked' : '' }}>
                                </div>
                                <div class="ms-4">
                                    <span class="block text-sm font-bold text-gray-900">Ketua RT</span>
                                    <span class="block text-[11px] text-gray-500 mt-0.5">Akses Mutlak (Owner) ke seluruh menu dan pengaturan.</span>
                                </div>
                            </label>
                        </div>
                        @error('tenant_role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-6 sm:p-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3 bg-white">
                    <a href="{{ route('admin.staff.index') }}" class="w-full sm:w-auto px-6 py-3 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors text-center">Batal</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
