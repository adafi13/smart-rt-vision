<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight">Manajemen Staff RT</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akses pengurus RT (Sekretaris, Bendahara, dll).</p>
            </div>
            <button @click="$dispatch('open-modal', 'add-staff')" class="btn-primary w-full sm:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengurus
            </button>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Error & Success Messages -->
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4">Nama Pengurus</th>
                            <th class="p-4">Email / Username</th>
                            <th class="p-4">Jabatan (Role)</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($staffs as $staff)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $staff->name }}
                                        @if($staff->id === auth()->id())
                                            <span class="ml-2 text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold">Anda</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 text-gray-600">{{ $staff->email }}</td>
                            <td class="p-4">
                                @if($staff->tenant_role === 'sekretaris')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-sky-100 text-sky-700">Sekretaris</span>
                                @elseif($staff->tenant_role === 'bendahara')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-700">Bendahara</span>
                                @elseif($staff->tenant_role === 'wakil_ketua')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-700">Wakil Ketua RT</span>
                                @elseif($staff->tenant_role === 'keamanan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">Seksi Keamanan</span>
                                @elseif($staff->tenant_role === 'humas')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-700">Seksi Humas</span>
                                @elseif($staff->tenant_role === 'pembangunan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-orange-100 text-orange-700">Seksi Pembangunan</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-100 text-indigo-700">Ketua RT (Owner)</span>
                                @endif
                            </td>
                            <td class="p-4 text-right flex items-center justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-staff-{{ $staff->id }}')" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                </button>
                                
                                <button @click="$dispatch('open-modal', 'reset-password-{{ $staff->id }}')" class="text-amber-600 hover:text-amber-900 font-semibold text-xs bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    Ubah Sandi
                                </button>
                                
                                @if($staff->id !== auth()->id())
                                <button @click="$dispatch('open-modal', 'delete-staff-{{ $staff->id }}')" class="text-rose-600 hover:text-rose-900 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <x-modal name="edit-staff-{{ $staff->id }}" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-bold text-gray-900 mb-4">Edit Pengurus: {{ $staff->name }}</h2>
                                <form action="{{ route('admin.staff.update', $staff) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="label">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ $staff->name }}" class="input-field" required>
                                    </div>
                                    <div>
                                        <label class="label">Email / Username</label>
                                        <input type="email" name="email" value="{{ $staff->email }}" class="input-field" required>
                                    </div>
                                    <div>
                                        <label class="label">Jabatan (Role)</label>
                                        <select name="tenant_role" class="input-field" required>
                                            <option value="owner" {{ (empty($staff->tenant_role) || $staff->tenant_role === 'owner') ? 'selected' : '' }}>Ketua RT (Owner)</option>
                                            <option value="wakil_ketua" {{ $staff->tenant_role === 'wakil_ketua' ? 'selected' : '' }}>Wakil Ketua RT</option>
                                            <option value="sekretaris" {{ $staff->tenant_role === 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                            <option value="bendahara" {{ $staff->tenant_role === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                            <option value="keamanan" {{ $staff->tenant_role === 'keamanan' ? 'selected' : '' }}>Seksi Keamanan</option>
                                            <option value="humas" {{ $staff->tenant_role === 'humas' ? 'selected' : '' }}>Seksi Humas</option>
                                            <option value="pembangunan" {{ $staff->tenant_role === 'pembangunan' ? 'selected' : '' }}>Seksi Pembangunan</option>
                                        </select>
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>

                        <!-- Reset Password Modal -->
                        <x-modal name="reset-password-{{ $staff->id }}" :show="false" maxWidth="sm">
                            <div class="p-6">
                                <h2 class="text-lg font-bold text-gray-900 mb-1">Ubah Sandi</h2>
                                <p class="text-xs text-gray-500 mb-5">Atur password baru untuk <strong>{{ $staff->name }}</strong>.</p>
                                <form action="{{ route('admin.staff.reset_password', $staff) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="label">Password Baru</label>
                                        <input type="password" name="new_password" class="input-field" required placeholder="Minimal 8 karakter">
                                    </div>
                                    <div>
                                        <label class="label">Konfirmasi Password</label>
                                        <input type="password" name="new_password_confirmation" class="input-field" required placeholder="Ketik ulang password baru">
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl transition-colors">Ubah Sandi</button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>

                        <!-- Delete Modal -->
                        @if($staff->id !== auth()->id())
                        <x-modal name="delete-staff-{{ $staff->id }}" :show="false" maxWidth="sm">
                            <div class="p-6 text-center">
                                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900 mb-2">Hapus Pengurus?</h2>
                                <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus <b>{{ $staff->name }}</b> dari sistem? Tindakan ini tidak dapat dibatalkan.</p>
                                
                                <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="flex justify-center gap-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors">Ya, Hapus</button>
                                </form>
                            </div>
                        </x-modal>
                        @endif

                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <p class="font-medium">Belum ada pengurus lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-100">
                {{ $staffs->links() }}
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <x-modal name="add-staff" :show="false" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Tambah Pengurus RT</h2>
            <p class="text-sm text-gray-500 mb-5">Berikan akses kepada sekretaris atau bendahara untuk membantu Anda.</p>
            
            <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input type="text" name="name" class="input-field" required placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="label">Email / Username</label>
                    <input type="email" name="email" class="input-field" required placeholder="Contoh: sekretaris@rt01.com">
                </div>
                <div>
                    <label class="label">Jabatan (Hak Akses)</label>
                    <select name="tenant_role" class="input-field" required>
                        <option value="owner">Ketua RT (Akses Penuh)</option>
                        <option value="wakil_ketua">Wakil Ketua RT (Akses Penuh)</option>
                        <option value="sekretaris">Sekretaris (Warga, Surat, Berita, Organisasi)</option>
                        <option value="bendahara">Bendahara (Kas, Iuran, Tunggakan)</option>
                        <option value="keamanan">Seksi Keamanan (Ronda, Panic Alert, Laporan)</option>
                        <option value="humas">Seksi Humas (Berita, Polling, Buku Tamu)</option>
                        <option value="pembangunan">Seksi Pembangunan (Inventaris, Laporan)</option>
                    </select>
                </div>
                <div>
                    <label class="label">Password Login</label>
                    <input type="text" name="password" class="input-field" required placeholder="Minimal 8 karakter">
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
