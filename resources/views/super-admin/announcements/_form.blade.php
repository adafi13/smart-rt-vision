{{-- Partial: Reusable Announcement Form Fields --}}
<div class="space-y-6">
    {{-- Title --}}
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Judul Pengumuman <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required
               placeholder="Contoh: Update Fitur Baru v2.5!"
               class="mt-1.5 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-all py-3.5 px-5 @error('title') border-red-500 @enderror">
        @error('title') <p class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</p> @enderror
    </div>

    {{-- Message --}}
    <div>
        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Isi Pesan <span class="text-red-500">*</span></label>
        <textarea name="message" rows="4" required
                  placeholder="Tuliskan isi pengumuman yang akan tampil di dashboard mitra..."
                  class="mt-1.5 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 transition-all py-3.5 px-5 @error('message') border-red-500 @enderror">{{ old('message', $announcement->message ?? '') }}</textarea>
        @error('message') <p class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Type --}}
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tipe Pesan <span class="text-red-500">*</span></label>
            <select name="type" class="mt-1.5 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-all py-3.5 px-5">
                @foreach(['info' => '💬 Info', 'warning' => '⚠️ Peringatan', 'success' => '✅ Kabar Baik', 'danger' => '🚨 Penting / Urgent'] as $val => $label)
                    <option value="{{ $val }}" {{ old('type', $announcement->type ?? 'info') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Target --}}
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Target Penerima <span class="text-red-500">*</span></label>
            <select name="target" class="mt-1.5 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-all py-3.5 px-5">
                @foreach(['all' => '👥 Semua User', 'owner' => '👤 Ketua RT (Owner)', 'bendahara' => '💰 Bendahara', 'sekretaris' => '📋 Sekretaris'] as $val => $label)
                    <option value="{{ $val }}" {{ old('target', $announcement->target ?? 'all') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Starts At --}}
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Mulai Tayang (Opsional)</label>
            <input type="datetime-local" name="starts_at"
                   value="{{ old('starts_at', isset($announcement->starts_at) ? $announcement->starts_at->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1.5 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-all py-3.5 px-5">
            <p class="text-[9px] text-slate-400 font-bold mt-1 ml-1 uppercase tracking-widest">Kosongkan = langsung tayang</p>
        </div>

        {{-- Ends At --}}
        <div>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Berakhir Tayang (Opsional)</label>
            <input type="datetime-local" name="ends_at"
                   value="{{ old('ends_at', isset($announcement->ends_at) ? $announcement->ends_at->format('Y-m-d\TH:i') : '') }}"
                   class="mt-1.5 w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 transition-all py-3.5 px-5">
            <p class="text-[9px] text-slate-400 font-bold mt-1 ml-1 uppercase tracking-widest">Kosongkan = tidak ada batas waktu</p>
        </div>
    </div>

    {{-- Toggles --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
        <label class="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:border-indigo-300 transition-all">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}
                   class="w-5 h-5 rounded-lg text-indigo-600 border-slate-300 focus:ring-indigo-500">
            <div>
                <p class="text-sm font-black text-slate-900">Aktifkan Sekarang</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pengumuman langsung aktif</p>
            </div>
        </label>

        <label class="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:border-indigo-300 transition-all">
            <input type="checkbox" name="is_dismissible" value="1" {{ old('is_dismissible', $announcement->is_dismissible ?? true) ? 'checked' : '' }}
                   class="w-5 h-5 rounded-lg text-indigo-600 border-slate-300 focus:ring-indigo-500">
            <div>
                <p class="text-sm font-black text-slate-900">Bisa Ditutup User</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">User bisa menutup banner ini</p>
            </div>
        </label>
        
        @if(isset($announcement))
        <label class="md:col-span-2 flex items-center gap-4 p-5 bg-rose-50 rounded-2xl border border-rose-200 cursor-pointer hover:border-rose-300 transition-all">
            <input type="checkbox" name="reset_dismissals" value="1"
                   class="w-5 h-5 rounded-lg text-rose-600 border-rose-300 focus:ring-rose-500">
            <div>
                <p class="text-sm font-black text-rose-900">Reset Status "Sudah Dibaca"</p>
                <p class="text-[10px] text-rose-500 font-bold uppercase tracking-widest mt-0.5">Centang ini jika ingin pengumuman muncul lagi bagi user yang sudah menutupnya.</p>
            </div>
        </label>
        @endif
    </div>
</div>
