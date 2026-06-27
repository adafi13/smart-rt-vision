<x-app-layout title="Manajemen UMKM Warga">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Etalase UMKM Warga</h1>
                <p class="text-sm text-gray-500 mt-0.5">Dukung usaha lokal warga. Produk akan tampil di halaman utama.</p>
            </div>
            
            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-produk')" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk Baru
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
            <form method="GET" action="{{ route('admin.umkm.index') }}" class="flex gap-2 w-full sm:max-w-md relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau nama penjual..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm transition-shadow">
                <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors">
                    Cari
                </button>
            </form>
        </div>

        @if($products->isNotEmpty())
        <!-- ENTERPRISE PRODUCT GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6">
            @foreach($products as $p)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-shadow relative">
                
                <!-- Status Badge Absolute -->
                <div class="absolute top-3 right-3 z-10">
                    @if($p->is_ready)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50/90 backdrop-blur-sm text-emerald-700 border border-emerald-200/50 rounded-lg text-[10px] font-bold uppercase tracking-wide shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50/90 backdrop-blur-sm text-rose-700 border border-rose-200/50 rounded-lg text-[10px] font-bold uppercase tracking-wide shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Kosong
                        </span>
                    @endif
                </div>

                <!-- Product Image -->
                @if($p->foto)
                    <div class="h-48 w-full bg-cover bg-center border-b border-gray-100 group-hover:scale-105 transition-transform duration-500" style="background-image: url('{{ asset('storage/'.$p->foto) }}')"></div>
                @else
                    <div class="h-48 w-full bg-indigo-50 border-b border-gray-100 flex items-center justify-center text-indigo-300">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif

                <!-- Product Info -->
                <div class="p-4 flex flex-col flex-1 bg-white relative z-20">
                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 w-fit mb-2">
                        {{ $p->kategori }}
                    </span>
                    
                    <h3 class="text-sm font-bold text-gray-900 leading-tight mb-1">{{ $p->nama_produk }}</h3>
                    
                    @if($p->harga)
                        <p class="text-indigo-600 font-black mb-3">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    @else
                        <p class="text-gray-400 text-xs italic mb-3">Harga tidak dicantumkan</p>
                    @endif

                    <!-- Seller Info Box -->
                    <div class="mt-auto bg-gray-50 rounded-xl p-3 border border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs flex-shrink-0 shadow-sm">
                            {{ substr($p->penjual, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $p->penjual }}</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->whatsapp) }}" target="_blank" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 mt-0.5 truncate transition-colors" title="Chat via WhatsApp">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825.001 6.938 3.113 6.939 6.937 0 3.825-3.112 6.938-6.939 6.942z"/></svg>
                                {{ $p->whatsapp }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Admin Action Buttons (Hover) -->
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px] z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-produk-{{ $p->id }}')" class="w-12 h-12 bg-white text-indigo-600 rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform" title="Edit Produk">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <form action="{{ route('admin.umkm.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk ini permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-12 h-12 bg-white text-rose-600 rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform" title="Hapus Produk">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $products->links() }}
        </div>
        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Produk UMKM</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Klik tombol "Tambah Produk Baru" untuk mendaftarkan usaha warga.</p>
        </div>
        @endif
    </div>

    <!-- MODAL TAMBAH PRODUK -->
    <x-modal name="tambah-produk" focusable>
        <form action="{{ route('admin.umkm.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">Daftarkan Produk/Jasa</h2>
                    <p class="text-[11px] font-semibold text-gray-500 mt-0.5">Bantu promosikan UMKM milik warga</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama Produk / Jasa</label>
                    <input type="text" name="nama_produk" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-semibold" placeholder="Contoh: Nasi Bakar Bu Tejo">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                        <input type="text" name="kategori" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-medium" placeholder="Makanan, Jasa, Pakaian...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Harga (Rp)</label>
                        <input type="number" name="harga" min="0" step="500" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-bold text-indigo-600" placeholder="Kosongkan jika tidak pasti">
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <h4 class="text-[11px] font-bold text-gray-900 uppercase tracking-wider mb-3">Informasi Penjual</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-wide mb-1.5">Nama Penjual</label>
                            <input type="text" name="penjual" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm" placeholder="Nama Warga">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-wide mb-1.5">No. WhatsApp</label>
                            <input type="text" name="whatsapp" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm" placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Deskripsi Produk (Opsional)</label>
                    <textarea name="deskripsi" rows="2" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm custom-scrollbar" placeholder="Jelaskan keunggulan produk/jasa ini..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Foto Produk (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 bg-gray-50/50 cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_ready" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Status: Siap Jual / Tersedia</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5">Hilangkan centang jika stok produk sedang kosong.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Tambahkan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL EDIT PRODUK -->
    @foreach($products as $p)
    <x-modal name="edit-produk-{{ $p->id }}" focusable>
        <form action="{{ route('admin.umkm.update', $p) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 leading-tight">Edit Info UMKM</h2>
                    <p class="text-[11px] font-semibold text-gray-500 mt-0.5">Perbarui detail produk atau stok</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nama Produk / Jasa</label>
                    <input type="text" name="nama_produk" required value="{{ $p->nama_produk }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-semibold">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Kategori</label>
                        <input type="text" name="kategori" required value="{{ $p->kategori }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Harga (Rp)</label>
                        <input type="number" name="harga" min="0" step="500" value="{{ $p->harga }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm font-bold text-indigo-600">
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <h4 class="text-[11px] font-bold text-gray-900 uppercase tracking-wider mb-3">Informasi Penjual</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-wide mb-1.5">Nama Penjual</label>
                            <input type="text" name="penjual" required value="{{ $p->penjual }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 tracking-wide mb-1.5">No. WhatsApp</label>
                            <input type="text" name="whatsapp" required value="{{ $p->whatsapp }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="2" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm shadow-sm custom-scrollbar">{{ $p->deskripsi }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Ganti Foto (Opsional)</label>
                    @if($p->foto)
                        <div class="mb-3 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-cover bg-center border border-gray-200" style="background-image: url('{{ asset('storage/'.$p->foto) }}')"></div>
                            <span class="text-[10px] font-semibold text-gray-400">Foto saat ini terpasang.</span>
                        </div>
                    @endif
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 bg-gray-50/50 cursor-pointer hover:bg-gray-50 transition-colors">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_ready" value="1" @checked($p->is_ready) class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900">Status: Siap Jual / Tersedia</span>
                            <span class="block text-[11px] text-gray-500 mt-0.5">Hilangkan centang jika stok produk sedang kosong.</span>
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
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 20px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #D1D5DB; }
</style>
