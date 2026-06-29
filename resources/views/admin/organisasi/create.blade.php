<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.organisasi.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Tambah Jajaran Pengurus</h2>
                <p class="text-sm text-slate-500 mt-1">Daftarkan pengurus RT untuk ditampilkan pada Portal Warga.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.organisasi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 mb-1">Informasi Pengurus</h3>
                <p class="text-sm text-slate-500">Silakan isi formulir di bawah ini. Kolom bertanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
            </div>

            <div class="p-8 space-y-8">
                <!-- Nama -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jabatan & Order -->
                <div x-data="jabatanPicker()">
                    <label class="block text-sm font-bold text-slate-900 mb-2">Jabatan <span class="text-red-500">*</span></label>
                    <select name="position" required x-model="jabatan" @change="setOrder" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('position') border-red-500 @enderror">
                        <option value="">-- Pilih Jabatan --</option>
                        @php $jabatanList = ['Ketua RT','Wakil Ketua RT','Sekretaris','Bendahara','Wakil Bendahara','Seksi Keamanan & Ketertiban','Seksi Kebersihan & Lingkungan','Seksi Kesehatan','Seksi Pendidikan & Kebudayaan','Seksi Sosial & Kemasyarakatan','Seksi Pemuda & Olahraga','Seksi Pemberdayaan Perempuan','Seksi Agama','Anggota']; @endphp
                        @foreach($jabatanList as $j)
                            <option value="{{ $j }}" {{ old('position') === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Nomor Urut (Hierarki) <span class="text-red-500">*</span></label>
                            <input type="number" name="order_level" min="1" x-ref="orderInput" value="{{ old('order_level', 99) }}" required 
                                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('order_level') border-red-500 @enderror">
                            <p class="text-[11px] text-slate-500 mt-1.5">Terisi otomatis sesuai jabatan (1 = Paling Atas).</p>
                            @error('order_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Nomor HP/WA</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" 
                                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('phone') border-red-500 @enderror">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Foto Profil -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Foto Profil (Opsional)</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                    <p class="text-[11px] text-slate-400 mt-2">Maksimal 2MB. Format: JPG, PNG, GIF.</p>
                    @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status Aktif -->
                <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_active" value="1" id="is_active" checked class="w-4 h-4 text-emerald-600 bg-white border-emerald-300 rounded focus:ring-emerald-500">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="is_active" class="font-bold text-emerald-900 cursor-pointer">Status: Pengurus Aktif</label>
                        <p class="text-emerald-700 mt-0.5">Tampilkan pengurus ini di struktur organisasi portal warga.</p>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-900">Perhatian</p>
                        <p class="text-xs text-blue-700 mt-1">Struktur organisasi yang Anda tambahkan di sini akan langsung tampil dan dapat dilihat oleh seluruh warga di Portal Warga.</p>
                    </div>
                </div>

            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('admin.organisasi.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambahkan
                </button>
            </div>
        </form>
    </div>

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
        jabatan: '{{ old("position") }}',
        setOrder() {
            if (this.$refs.orderInput && orders[this.jabatan]) {
                this.$refs.orderInput.value = orders[this.jabatan];
            }
        }
    };
}
</script>
</x-app-layout>
