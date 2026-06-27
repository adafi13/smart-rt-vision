<x-app-layout title="Kelola Berita & Pengumuman">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Portal Berita & Pengumuman</h1>
                <p class="text-sm text-gray-500 mt-0.5">Berita yang Anda tulis akan tampil di halaman depan publik warga</p>
            </div>
            
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-berita')" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tulis Berita Baru
            </button>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.berita.index') }}" class="flex gap-2 w-full sm:max-w-md relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm transition-shadow">
                <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors">
                    Cari
                </button>
            </form>
        </div>

        @if($newsList->isNotEmpty())
        
        <!-- DESKTOP ENTERPRISE TABLE -->
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4 w-24">Cover</th>
                        <th class="px-6 py-4">Judul & Kategori</th>
                        <th class="px-6 py-4">Cuplikan Isi</th>
                        <th class="px-6 py-4">Tanggal Rilis</th>
                        <th class="px-6 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @foreach($newsList as $n)
                    <tr class="hover:bg-gray-50/50 transition-colors group align-top">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($n->gambar)
                                <div class="w-16 h-12 rounded-lg bg-gray-100 bg-cover bg-center border border-gray-200" style="background-image: url('{{ asset('storage/'.$n->gambar) }}')"></div>
                            @else
                                <div class="w-16 h-12 rounded-lg bg-indigo-50 text-indigo-300 border border-indigo-100 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[250px]">
                            <div class="flex flex-col gap-1.5">
                                <span class="font-bold text-gray-900 leading-tight">{{ $n->judul }}</span>
                                <div class="flex items-center gap-2">
                                    @php
                                        $catColor = match($n->kategori) {
                                            'Pengumuman' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Berita' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Undangan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border {{ $catColor }}">
                                        {{ $n->kategori }}
                                    </span>
                                    
                                    @if($n->is_penting)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        Penting
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px] max-w-xs">
                            <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-2">{{ Str::limit($n->isi, 90) }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            <span class="font-semibold text-gray-800 block">{{ $n->created_at->translatedFormat('d M Y') }}</span>
                            {{ $n->created_at->format('H:i') }} WIB
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-berita-{{ $n->id }}')" class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.berita.destroy', $n) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus berita ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE COMPACT CARDS -->
        <div class="md:hidden space-y-4">
            @foreach($newsList as $n)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                @if($n->is_penting)
                <!-- Red left border for important news -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                @endif
                
                @if($n->gambar)
                <!-- Cover Image -->
                <div class="h-32 w-full bg-cover bg-center" style="background-image: url('{{ asset('storage/'.$n->gambar) }}')"></div>
                @endif

                <div class="p-4 {{ $n->is_penting ? 'pl-5' : '' }}">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        @php
                            $catColor = match($n->kategori) {
                                'Pengumuman' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Berita' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'Undangan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200'
                            };
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide border {{ $catColor }}">
                            {{ $n->kategori }}
                        </span>
                        
                        @if($n->is_penting)
                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200">
                            Penting
                        </span>
                        @endif
                        <span class="text-[10px] text-gray-400 font-medium ml-auto">{{ $n->created_at->translatedFormat('d M y') }}</span>
                    </div>
                    
                    <h3 class="font-bold text-gray-900 leading-tight mb-2">{{ $n->judul }}</h3>
                    <p class="text-[11px] text-gray-500 line-clamp-3 leading-relaxed">{{ $n->isi }}</p>
                </div>

                <div class="p-3 bg-gray-50/50 border-t border-gray-50 flex gap-2 {{ $n->is_penting ? 'pl-5' : '' }}">
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-berita-{{ $n->id }}')" class="flex-1 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[11px] font-bold rounded-lg transition-colors shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </button>
                    <form action="{{ route('admin.berita.destroy', $n) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus berita ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-1.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 rounded-lg text-[11px] font-bold transition-colors shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $newsList->links() }}
        </div>

        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Portal Berita Kosong</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Klik tombol "Tulis Berita Baru" di atas untuk membagikan informasi pertama kepada warga Anda.</p>
        </div>
        @endif

    </div>

    <!-- MODAL TAMBAH BERITA -->
    <x-modal name="tambah-berita" focusable>
        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">Tulis Berita Baru</h2>
                    <p class="text-[11px] font-semibold text-gray-500 mt-0.5">Informasi akan tayang di portal publik warga</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Judul Postingan</label>
                    <input type="text" name="judul" required placeholder="Contoh: Kerja Bakti Massal Bulan Depan" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-semibold">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                    <select name="kategori" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-medium">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Pengumuman">Pengumuman Resmi</option>
                        <option value="Berita">Berita Ringan</option>
                        <option value="Undangan">Undangan Acara</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Isi / Deskripsi</label>
                    <textarea name="isi" required rows="5" placeholder="Tuliskan isi informasi secara lengkap di sini..." class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm custom-scrollbar"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Foto Sampul (Opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 bg-gray-50/50 cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_penting" value="1" class="w-4 h-4 text-rose-600 rounded border-gray-300 focus:ring-rose-500">
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Tandai Sangat Penting</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5">Postingan ini akan disorot dengan tanda merah di halaman warga.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Publikasikan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL EDIT BERITA -->
    @foreach($newsList as $n)
    <x-modal name="edit-berita-{{ $n->id }}" focusable>
        <form action="{{ route('admin.berita.update', $n) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">Edit Postingan</h2>
                    <p class="text-[11px] font-semibold text-gray-500 mt-0.5">Perbarui informasi yang telah lalu</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Judul Postingan</label>
                    <input type="text" name="judul" required value="{{ $n->judul }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-semibold">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                    <select name="kategori" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-medium">
                        @foreach(['Pengumuman', 'Berita', 'Undangan'] as $kat)
                            <option value="{{ $kat }}" @selected($n->kategori === $kat)>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Isi / Deskripsi</label>
                    <textarea name="isi" required rows="5" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm custom-scrollbar">{{ $n->isi }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Ganti Foto Sampul</label>
                    @if($n->gambar)
                        <div class="mb-3 flex items-center gap-3">
                            <div class="w-16 h-12 rounded-lg bg-cover bg-center border border-gray-200" style="background-image: url('{{ asset('storage/'.$n->gambar) }}')"></div>
                            <span class="text-[10px] font-semibold text-gray-400">Gambar saat ini. Unggah file baru untuk mengganti.</span>
                        </div>
                    @endif
                    <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 bg-gray-50/50 cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_penting" value="1" class="w-4 h-4 text-rose-600 rounded border-gray-300 focus:ring-rose-500" @checked($n->is_penting)>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Tandai Sangat Penting</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5">Postingan ini akan disorot dengan tanda merah di halaman warga.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-modal>
    @endforeach

</x-app-layout>

<style>
/* Custom Scrollbar for Textarea */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 20px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #D1D5DB; }
</style>
