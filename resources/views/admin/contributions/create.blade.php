<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.iuran.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Catat Pembayaran Kas (Iuran)</h2>
                <p class="text-sm text-slate-500 mt-1">Isi detail iuran yang diterima dari warga.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.iuran.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 mb-1">Informasi Transaksi Kas</h3>
                <p class="text-sm text-slate-500">Silakan isi detail pembayaran di bawah ini. Kolom bertanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
            </div>

            <div class="p-8 space-y-8">
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Kartu Keluarga (Pembayar) <span class="text-red-500">*</span></label>
                    <select name="family_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('family_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kepala Keluarga --</option>
                        @foreach($families as $f)
                            <option value="{{ $f->id }}" {{ old('family_id') == $f->id ? 'selected' : '' }}>{{ $f->nomor_kk }} — {{ $f->nama_kepala_keluarga }}</option>
                        @endforeach
                    </select>
                    @error('family_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Jenis Iuran <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_iuran" value="{{ old('jenis_iuran') }}" required placeholder="Contoh: Kebersihan"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('jenis_iuran') border-red-500 @enderror">
                        @error('jenis_iuran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div x-data="{ 
                            raw: '{{ old('jumlah') }}',
                            formatted: '',
                            init() {
                                if(this.raw) this.formatValue(this.raw);
                            },
                            formatValue(val) {
                                let num = val.toString().replace(/[^0-9]/g, '');
                                this.raw = num;
                                this.formatted = num ? parseInt(num, 10).toLocaleString('id-ID') : '';
                            }
                        }">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Jumlah (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm font-bold">Rp</span>
                            </div>
                            <!-- Hidden input for actual submission -->
                            <input type="hidden" name="jumlah" :value="raw">
                            <!-- Visible formatted input -->
                            <input type="text" x-model="formatted" @input="formatValue($event.target.value)" required placeholder="0"
                                class="w-full pl-10 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-3 text-sm font-bold text-emerald-700 @error('jumlah') border-red-500 @enderror">
                        </div>
                        @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Periode Bulan <span class="text-red-500">*</span></label>
                        <input type="date" name="periode" value="{{ old('periode') }}" required
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('periode') border-red-500 @enderror">
                        @error('periode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Bayar <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('tanggal_bayar') border-red-500 @enderror">
                        @error('tanggal_bayar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Catatan tambahan bila ada..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('admin.iuran.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Simpan Iuran
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
