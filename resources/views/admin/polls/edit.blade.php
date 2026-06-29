<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.polls.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Edit Polling / Musyawarah</h2>
                <p class="text-sm text-slate-500 mt-1">Ubah judul, rentang waktu, atau status polling.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.polls.update', $poll) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-black text-slate-900 mb-1">Informasi Musyawarah / Polling</h3>
                    <p class="text-sm text-slate-500">Kolom bertanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
                </div>
                <div class="bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider">
                    Edit Mode
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Judul Musyawarah -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Judul Musyawarah <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $poll->title) }}" required placeholder="Contoh: Pemilihan Ketua RT 05 Periode 2026-2030"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('title') border-red-500 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Keterangan / Aturan -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Keterangan / Aturan (Opsional)</label>
                    <textarea name="description" rows="3" placeholder="Tuliskan deksripsi pemilihan di sini..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('description') border-red-500 @enderror">{{ old('description', $poll->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tgl Mulai -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Tgl Mulai (Opsional)</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $poll->start_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('start_date') border-red-500 @enderror">
                        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tgl Selesai -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Tgl Selesai (Opsional)</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $poll->end_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('end_date') border-red-500 @enderror">
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $poll->status) === 'active' ? 'selected' : '' }}>🟢 Aktif (Bisa divote jika masuk tanggal)</option>
                        <option value="closed" {{ old('status', $poll->status) === 'closed' ? 'selected' : '' }}>🔴 Ditutup (Tidak bisa divote)</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 mt-4">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-900">Catatan</p>
                        <p class="text-xs text-blue-700 mt-1">Opsi pilihan suara tidak dapat diubah setelah polling dibuat demi menjaga integritas data perhitungan suara (Voting).</p>
                    </div>
                </div>

            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('admin.polls.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
