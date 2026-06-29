<x-app-layout title="Buat Tiket Support" header="Buat Tiket Bantuan Baru">
    <div class="max-w-3xl mx-auto py-8 animate-fade-in-up min-h-screen transition-colors duration-300">
        <div class="mb-8">
            <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Tiket
            </a>
        </div>

        {{-- Info Banner --}}
        <div class="flex items-start gap-5 p-6 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-[2rem] mb-8 transition-colors">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-2xl flex items-center justify-center shrink-0 border border-blue-200 dark:border-blue-500/30 shadow-lg shadow-blue-500/10 dark:shadow-none">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-black text-blue-800 dark:text-blue-300 uppercase tracking-tight">Tim Support ApoApps Siap Membantu</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 font-bold mt-1 leading-relaxed">Waktu respons rata-rata kami adalah 1–4 jam kerja. Jelaskan masalah Anda sedetail mungkin agar kami bisa membantu dengan cepat.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-2xl shadow-slate-200/40 dark:shadow-none overflow-hidden transition-colors">
            <div class="p-10 border-b border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/50">
                <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">Buat Tiket Bantuan Baru</h3>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-1.5">Isi formulir dengan lengkap agar kami dapat menganalisis masalah Anda dengan tepat</p>
            </div>

            <form method="POST" action="{{ route('admin.tickets.store') }}" class="p-10 space-y-8" enctype="multipart/form-data">
                @csrf

                {{-- Category & Priority --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div class="space-y-2.5">
                        <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-[0.2em] ml-1">Kategori Masalah <span class="text-red-500">*</span></label>
                        <select name="category" required
                                class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-black text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-500 focus:border-transparent transition-all @error('category') border-red-300 dark:border-red-500/50 bg-red-50 dark:bg-red-500/10 @enderror appearance-none">
                            <option value="">Pilih kategori...</option>
                            <option value="technical"       {{ old('category') === 'technical'       ? 'selected' : '' }}>🔧 Teknis (Bug, Error, Gangguan)</option>
                            <option value="billing"         {{ old('category') === 'billing'         ? 'selected' : '' }}>💰 Billing (Tagihan, Langganan)</option>
                            <option value="general"         {{ old('category') === 'general'         ? 'selected' : '' }}>💬 Umum (Pertanyaan, Panduan)</option>
                            <option value="feature_request" {{ old('category') === 'feature_request' ? 'selected' : '' }}>💡 Permintaan Fitur Baru</option>
                        </select>
                        @error('category') <p class="text-[10px] text-red-500 mt-1 font-black uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2.5">
                        <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-[0.2em] ml-1">Prioritas <span class="text-red-500">*</span></label>
                        <select name="priority" required
                                class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-black text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-500 focus:border-transparent transition-all @error('priority') border-red-300 dark:border-red-500/50 bg-red-50 dark:bg-red-500/10 @enderror appearance-none">
                            <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>⚪ Rendah — Tidak mendesak</option>
                            <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>🔵 Normal — Standar</option>
                            <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>🟠 Tinggi — Mengganggu operasional</option>
                        </select>
                        @error('priority') <p class="text-[10px] text-red-500 mt-1 font-black uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Subject --}}
                <div class="space-y-2.5">
                    <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-[0.2em] ml-1">Judul / Subjek Masalah <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                           placeholder="Contoh: Laporan stok tidak bisa diexport..."
                           class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-black text-slate-700 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-500 focus:border-transparent transition-all @error('subject') border-red-300 dark:border-red-500/50 bg-red-50 dark:bg-red-500/10 @enderror">
                    @error('subject') <p class="text-[10px] text-red-500 mt-1 font-black uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="space-y-2.5">
                    <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-[0.2em] ml-1">Deskripsi Masalah <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="8" required
                              placeholder="Jelaskan masalah Anda secara detail:&#10;- Apa yang terjadi?&#10;- Kapan pertama kali terjadi?&#10;- Langkah-langkah untuk mereproduksi masalah?&#10;- Pesan error yang muncul (jika ada)?"
                              class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-[2rem] text-sm font-bold text-slate-700 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-500 focus:border-transparent resize-none transition-all @error('message') border-red-300 dark:border-red-500/50 bg-red-50 dark:bg-red-500/10 @enderror leading-relaxed">{{ old('message') }}</textarea>
                    @error('message') <p class="text-[10px] text-red-500 mt-1 font-black uppercase tracking-widest">{{ $message }}</p> @enderror
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 dark:text-slate-500 mt-2 uppercase tracking-widest ml-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Minimal 20 karakter. Semakin detail, semakin cepat kami membantu.
                    </div>
                </div>

                {{-- Attachment --}}
                <div class="space-y-2.5">
                    <label class="block text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-[0.2em] ml-1">Lampiran / Screenshot (Opsional)</label>
                    <div class="relative">
                        <input type="file" name="attachment" id="attachmentInput" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                               class="hidden"
                               onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name || 'Klik untuk pilih file...'">
                        <label for="attachmentInput"
                               class="flex flex-col items-center justify-center gap-3 w-full p-10 bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-[2rem] cursor-pointer hover:border-emerald-400 dark:hover:border-emerald-500/50 hover:bg-emerald-50 dark:hover:bg-emerald-500/5 transition-all group shadow-inner">
                            <div class="w-14 h-14 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-xl rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <svg class="w-7 h-7 text-slate-400 dark:text-slate-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </div>
                            <div class="text-center">
                                <span id="fileLabel" class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight block">Klik untuk pilih file</span>
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.15em] mt-1 block">JPG, PNG, PDF, ZIP — Maks. 10MB</span>
                            </div>
                        </label>
                    </div>
                    @error('attachment') <p class="text-[10px] text-red-500 mt-1 font-black uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-10 border-t border-slate-100 dark:border-slate-700">
                    <button type="submit"
                            class="flex-1 bg-slate-900 dark:bg-emerald-600 text-white py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.25em] hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-xl shadow-slate-900/20 dark:shadow-none dark:shadow-emerald-500/20 flex items-center justify-center gap-3 group">
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Permintaan
                    </button>
                    <a href="{{ route('admin.tickets.index') }}"
                       class="px-10 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 dark:hover:bg-slate-600 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
