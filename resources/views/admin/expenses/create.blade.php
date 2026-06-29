<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pengeluaran.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-slate-900">Catat Pengeluaran Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Form untuk mencatat arus kas keluar RT</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-base font-bold text-slate-800">Form Pengeluaran Kas</h2>
                <p class="text-xs text-slate-500 mt-1">Lengkapi rincian pengeluaran di bawah ini.</p>
            </div>

            <form action="{{ route('admin.pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan / Rincian <span class="text-red-500">*</span></label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}" required 
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('keterangan') border-red-500 @enderror" 
                            placeholder="Contoh: Pembayaran Tukang Sampah Bulan Februari">
                        @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                                <input type="hidden" name="jumlah" :value="raw">
                                <input type="text" x-model="formatted" @input="formatValue($event.target.value)" required placeholder="0"
                                    class="w-full pl-11 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors py-3 text-sm font-bold text-rose-600 @error('jumlah') border-red-500 @enderror">
                            </div>
                            @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('kategori') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(['Kebersihan', 'Keamanan', 'Sosial & Warga', 'Infrastruktur', 'Operasional RT', 'Lainnya'] as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                            @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Keluar <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_keluar" required value="{{ old('tanggal_keluar', date('Y-m-d')) }}" 
                                class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('tanggal_keluar') border-red-500 @enderror">
                            @error('tanggal_keluar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Upload Bukti Nota/Struk</label>
                            <input type="file" name="bukti_nota" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl cursor-pointer bg-slate-50">
                            @error('bukti_nota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 mt-4">
                        <div class="text-blue-500 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Perhatian:</strong> Data pengeluaran kas yang Anda catat ini akan langsung memperbarui Total Saldo Kas secara otomatis. Harap pastikan jumlah dan nota yang dilampirkan sudah sesuai agar kas RT seimbang.
                        </p>
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.pengeluaran.index') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all">
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
