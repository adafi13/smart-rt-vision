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
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-700">Ketua RT (Owner)</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <button @click="$dispatch('open-modal', 'edit-staff-{{ $staff->id }}')" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors inline-block mr-2">Edit</button>
                                
                                @if($staff->id !== auth()->id())
                                <button @click="$dispatch('open-modal', 'delete-staff-{{ $staff->id }}')" class="text-rose-600 hover:text-rose-900 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors inline-block">Hapus</button>
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
                                            <option value="sekretaris" {{ $staff->tenant_role === 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                            <option value="bendahara" {{ $staff->tenant_role === 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="label">Password Baru (Opsional)</label>
                                        <input type="text" name="password" class="input-field" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
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
                        <option value="sekretaris">Sekretaris (Akses: Warga, Surat, Berita)</option>
                        <option value="bendahara">Bendahara (Akses: Kas, Iuran)</option>
                        <option value="owner">Ketua RT (Akses Penuh)</option>
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
