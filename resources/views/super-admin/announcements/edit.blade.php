<x-super-admin-layout title="Edit Pengumuman">
    <div class="max-w-3xl mx-auto py-4">
        <div class="mb-6">
            <a href="{{ route('super-admin.announcements.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/30">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-[0.25em]">Edit Pengumuman</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Perubahan akan langsung berlaku jika pengumuman dalam status aktif.</p>
            </div>

            <form method="POST" action="{{ route('super-admin.announcements.update', $announcement) }}" class="p-8">
                @csrf
                @method('PUT')
                @include('super-admin.announcements._form', ['announcement' => $announcement])

                <div class="flex gap-3 pt-8 border-t border-slate-100 mt-8">
                    <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-lg">
                        💾 Simpan Perubahan
                    </button>
                    <a href="{{ route('super-admin.announcements.index') }}"
                       class="px-8 bg-slate-100 text-slate-500 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-super-admin-layout>
