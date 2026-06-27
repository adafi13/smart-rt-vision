<x-app-layout title="Struktur Organisasi RT">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Jajaran Pengurus RT</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola daftar pengurus yang akan ditampilkan pada Portal Warga.</p>
            </div>
            <button x-data @click="$dispatch('open-modal', 'add-staff-modal')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengurus
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Data Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($staffs as $staff)
            <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group">
                <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNykiLz48L3N2Zz4=')]"></div>
                    <div class="absolute -bottom-10 left-6">
                        <div class="w-20 h-20 rounded-2xl bg-white p-1 shadow-lg">
                            @if($staff->photo)
                                <img src="{{ asset('storage/'.$staff->photo) }}" class="w-full h-full object-cover rounded-xl" alt="{{ $staff->name }}">
                            @else
                                <div class="w-full h-full bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md text-white text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-white/20">
                        Level {{ $staff->order_level }}
                    </div>
                </div>
                
                <div class="pt-12 p-6">
                    <h3 class="text-lg font-black text-gray-900 truncate">{{ $staff->name }}</h3>
                    <p class="text-sm font-bold text-indigo-600 truncate mt-0.5">{{ $staff->position }}</p>
                    
                    @if($staff->phone)
                    <div class="flex items-center gap-2 mt-3 text-xs text-gray-500 font-medium">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $staff->phone }}
                    </div>
                    @endif

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold {{ $staff->is_active ? 'text-emerald-600 bg-emerald-50' : 'text-gray-500 bg-gray-100' }} px-2 py-1 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full {{ $staff->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $staff->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>

                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <!-- Tombol Edit -->
                            <button type="button" 
                                    x-data 
                                    @click="$dispatch('open-modal', 'edit-staff-{{ $staff->id }}')" 
                                    class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"
                                    title="Edit Pengurus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.organisasi.destroy', $staff) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus data pengurus ini?')" class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors" title="Hapus Pengurus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit -->
            <x-modal name="edit-staff-{{ $staff->id }}" focusable>
                <form method="post" action="{{ route('admin.organisasi.update', $staff) }}" enctype="multipart/form-data" class="p-6">
                    @csrf @method('PUT')
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <h2 class="text-xl font-black text-gray-900">Edit Data Pengurus</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <x-input-label value="Nama Lengkap" class="font-bold" />
                            <x-text-input name="name" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" value="{{ $staff->name }}" required />
                        </div>

                        <div>
                            <x-input-label value="Jabatan" class="font-bold" />
                            <select name="position" required class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                @php $jabatanList = ['Ketua RT','Wakil Ketua RT','Sekretaris','Bendahara','Wakil Bendahara','Seksi Keamanan & Ketertiban','Seksi Kebersihan & Lingkungan','Seksi Kesehatan','Seksi Pendidikan & Kebudayaan','Seksi Sosial & Kemasyarakatan','Seksi Pemuda & Olahraga','Seksi Pemberdayaan Perempuan','Seksi Agama','Anggota']; @endphp
                                @foreach($jabatanList as $j)
                                    <option value="{{ $j }}" {{ $staff->position === $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Nomor Urut (Hierarki)" class="font-bold" />
                                <x-text-input name="order_level" type="number" min="1" class="mt-1 block w-full bg-gray-50 border-gray-200" value="{{ $staff->order_level }}" required />
                                <p class="text-[10px] text-gray-500 mt-1">1=Paling atas (Ketua)</p>
                            </div>
                            <div>
                                <x-input-label value="Nomor HP/WA" class="font-bold" />
                                <x-text-input name="phone" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" value="{{ $staff->phone }}" />
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <x-input-label value="Foto Profil (Baru)" class="font-bold mb-2" />
                            <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-[10px] text-gray-500 mt-1.5">Kosongkan jika tidak ingin mengubah foto. Maks 2MB.</p>
                        </div>

                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 w-5 h-5" {{ $staff->is_active ? 'checked' : '' }}>
                            <span class="text-sm font-bold text-gray-700">Pengurus Aktif</span>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </x-modal>
            @empty
            <div class="col-span-full">
                <div class="bg-white border border-gray-200 border-dashed rounded-3xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pengurus</h3>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto">Tambahkan daftar ketua, sekretaris, atau seksi-seksi lainnya untuk ditampilkan di Portal Publik.</p>
                    <button x-data @click="$dispatch('open-modal', 'add-staff-modal')" class="mt-5 text-indigo-600 text-sm font-bold hover:text-indigo-800">
                        + Tambah Data Pertama
                    </button>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Tambah -->
    <x-modal name="add-staff-modal" focusable>
        <form method="post" action="{{ route('admin.organisasi.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h2 class="text-xl font-black text-gray-900">Tambah Jajaran Pengurus</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label value="Nama Lengkap" class="font-bold" />
                    <x-text-input name="name" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" required />
                </div>

                <div x-data="jabatanPicker()">
                    <x-input-label value="Jabatan" class="font-bold" />
                    <select name="position" required x-model="jabatan" @change="setOrder" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Ketua RT">Ketua RT</option>
                        <option value="Wakil Ketua RT">Wakil Ketua RT</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Wakil Bendahara">Wakil Bendahara</option>
                        <option value="Seksi Keamanan & Ketertiban">Seksi Keamanan & Ketertiban</option>
                        <option value="Seksi Kebersihan & Lingkungan">Seksi Kebersihan & Lingkungan</option>
                        <option value="Seksi Kesehatan">Seksi Kesehatan</option>
                        <option value="Seksi Pendidikan & Kebudayaan">Seksi Pendidikan & Kebudayaan</option>
                        <option value="Seksi Sosial & Kemasyarakatan">Seksi Sosial & Kemasyarakatan</option>
                        <option value="Seksi Pemuda & Olahraga">Seksi Pemuda & Olahraga</option>
                        <option value="Seksi Pemberdayaan Perempuan">Seksi Pemberdayaan Perempuan</option>
                        <option value="Seksi Agama">Seksi Agama</option>
                        <option value="Anggota">Anggota</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Nomor urut hierarki akan terisi otomatis.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label value="Nomor Urut (Hierarki)" class="font-bold" />
                        <x-text-input name="order_level" type="number" min="1" x-ref="orderInput" value="99" class="mt-1 block w-full bg-gray-50 border-gray-200" required />
                        <p class="text-[10px] text-gray-500 mt-1">Terisi otomatis sesuai jabatan</p>
                    </div>
                    <div>
                        <x-input-label value="Nomor HP/WA" class="font-bold" />
                        <x-text-input name="phone" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" />
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <x-input-label value="Foto Profil" class="font-bold mb-2" />
                    <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-[10px] text-gray-500 mt-1.5">Opsional. Maks 2MB.</p>
                </div>

                <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 w-5 h-5">
                    <span class="text-sm font-bold text-gray-700">Tampilkan sebagai Aktif</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>Simpan Pengurus</x-primary-button>
            </div>
        </form>
    </x-modal>

<script>
function jabatanPicker() {
    const orders = {
        'Ketua RT': 1, 'Wakil Ketua RT': 2, 'Sekretaris': 3, 'Bendahara': 4,
        'Wakil Bendahara': 5, 'Seksi Keamanan & Ketertiban': 6,
        'Seksi Kebersihan & Lingkungan': 7, 'Seksi Kesehatan': 8,
        'Seksi Pendidikan & Kebudayaan': 9, 'Seksi Sosial & Kemasyarakatan': 10,
        'Seksi Pemuda & Olahraga': 11, 'Seksi Pemberdayaan Perempuan': 12,
        'Seksi Agama': 13, 'Anggota': 99
    };
    return {
        jabatan: '',
        setOrder() {
            if (this.$refs.orderInput && orders[this.jabatan]) {
                this.$refs.orderInput.value = orders[this.jabatan];
            }
        }
    };
}
</script>
</x-app-layout>
