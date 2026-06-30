<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('rw.agendas.index') }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 bg-transparent hover:bg-indigo-50 rounded-xl transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 leading-tight">Edit Agenda Kegiatan</h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui detail acara yang sudah ada.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('rw.agendas.update', $agenda->id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-black text-slate-900 mb-1">Informasi Kegiatan</h3>
                <p class="text-sm text-slate-500">Perbarui detail acara. Kolom dengan tanda bintang (<span class="text-red-500 font-bold">*</span>) wajib diisi.</p>
            </div>

            <div class="p-8 space-y-8">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $agenda->title) }}" required 
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('title') border-red-500 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Jenis Kegiatan -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Jenis Kegiatan <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('type') border-red-500 @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="umum" {{ old('type', $agenda->type) == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="rapat" {{ old('type', $agenda->type) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                            <option value="kerjabakti" {{ old('type', $agenda->type) == 'kerjabakti' ? 'selected' : '' }}>Kerja Bakti</option>
                            <option value="posyandu" {{ old('type', $agenda->type) == 'posyandu' ? 'selected' : '' }}>Posyandu</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Lokasi (Opsional)</label>
                        <input type="text" name="location" value="{{ old('location', $agenda->location) }}" 
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Waktu Mulai -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time', $agenda->start_time ? $agenda->start_time->format('Y-m-d\TH:i') : '') }}" required 
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('start_time') border-red-500 @enderror">
                        @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Waktu Selesai -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Waktu Selesai (Opsional)</label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time', $agenda->end_time ? $agenda->end_time->format('Y-m-d\TH:i') : '') }}" 
                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm @error('end_time') border-red-500 @enderror">
                        <p class="text-xs text-slate-400 mt-1">Kosongkan jika acara berlangsung sampai selesai tanpa batas waktu pasti.</p>
                        @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi / Catatan Tambahan (Opsional)</label>
                    <textarea name="description" rows="4" 
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors px-4 py-3 text-sm">{{ old('description', $agenda->description) }}</textarea>
                </div>
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-900">Perhatian</p>
                        <p class="text-xs text-blue-700 mt-1">Perubahan agenda yang Anda lakukan di sini akan langsung diperbarui di **Portal Warga** (di bagian Kalender Kegiatan).</p>
                    </div>
                </div>

            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                <a href="{{ route('rw.agendas.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-rw-app-layout>
