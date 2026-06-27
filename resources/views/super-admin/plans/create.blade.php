<x-super-admin-layout title="Tambah Paket" header="Manajemen Paket Berlangganan">
    <div class="max-w-5xl mx-auto pb-20">
        {{-- Header Section --}}
        <div class="relative mb-10 text-center">
            <div class="absolute top-0 left-0">
                <a href="{{ route('super-admin.plans.index') }}" 
                   class="flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-emerald-500 transition-all uppercase tracking-widest group">
                    <div class="p-2 rounded-full bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    </div>
                    Kembali
                </a>
            </div>
            
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight mb-2">
                Konfigurasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-400">Paket Baru</span>
            </h1>
            <p class="text-slate-400 dark:text-slate-500 font-medium">Rancang paket berlangganan eksklusif untuk mitra RT/RW Anda.</p>
        </div>

        <form action="{{ route('super-admin.plans.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                {{-- Left Content: Main Configuration --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- 1. Identity Card --}}
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-32 h-32"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.182 0l4.318-4.318a2.25 2.25 0 0 0 0-3.182L10.582 3.659A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-400 text-white flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800 dark:text-white">Identitas Paket</h2>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Informasi Dasar & Penamaan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="group">
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-emerald-500 transition-colors">Nama Paket</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                       placeholder="Misal: Enterprise Pro"
                                       class="w-full bg-slate-50 dark:bg-slate-800/50 border-0 rounded-2xl px-6 py-4 text-slate-800 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-300">
                                @error('name') <p class="text-xs text-rose-500 mt-2 font-bold px-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-emerald-500 transition-colors">Slug (URL)</label>
                                <input type="text" name="slug" value="{{ old('slug') }}" required 
                                       placeholder="Misal: enterprise-pro"
                                       class="w-full bg-slate-50 dark:bg-slate-800/50 border-0 rounded-2xl px-6 py-4 text-slate-800 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-300">
                                @error('slug') <p class="text-xs text-rose-500 mt-2 font-bold px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div class="group">
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-emerald-500 transition-colors">Deskripsi Marketing</label>
                                <textarea name="description" rows="3" 
                                          placeholder="Tuliskan value proposition dari paket ini..."
                                          class="w-full bg-slate-50 dark:bg-slate-800/50 border-0 rounded-2xl px-6 py-4 text-slate-800 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-300">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="group">
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-emerald-500 transition-colors">Urutan Tampil (Sort Order)</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required 
                                       placeholder="0"
                                       class="w-full bg-slate-50 dark:bg-slate-800/50 border-0 rounded-2xl px-6 py-4 text-slate-800 dark:text-white font-bold focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-300">
                                @error('sort_order') <p class="text-xs text-rose-500 mt-2 font-bold px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Quota & Limits --}}
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-400 text-white flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-9.75 0h9.75" /></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800 dark:text-white">Batasan Operasional</h2>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Kapasitas Penggunaan Resource</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach([
                                ['max_kk', 'Kartu Keluarga', 'Maksimal KK Terdaftar', 'Unlimited'],
                                ['max_ai_extractions_per_month', 'Kuota AI', 'Scan KTP/KK per Bulan', 'Unlimited']
                            ] as [$key, $label, $sub, $def])
                            <div class="group">
                                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 transition-all group-focus-within:ring-2 group-focus-within:ring-indigo-500">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ $label }}</label>
                                    <input type="number" name="{{ $key }}" value="{{ old($key) }}" min="1" placeholder="{{ $def }}"
                                           class="w-full bg-transparent border-0 p-0 text-xl font-black text-indigo-600 dark:text-indigo-400 focus:ring-0 placeholder:text-slate-300">
                                    <p class="text-[9px] text-slate-400 mt-2 font-bold">{{ $sub }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <div class="p-1 rounded-full bg-amber-100 text-amber-600 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.25v2.75a.75.75 0 001.5 0v-3.5A.75.75 0 0010 9H9z" clip-rule="evenodd" /></svg>
                            </div>
                            <p class="text-[10px] text-slate-500 font-medium leading-relaxed italic">Biarkan kosong untuk memberikan akses tak terbatas (*Unlimited*) pada RT/RW.</p>
                        </div>
                    </div>

                    {{-- 3. Advanced Features Grid --}}
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-400 text-white flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-slate-800 dark:text-white">Modul Eksklusif</h2>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Aktifkan Fitur Berdasarkan Tier</p>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-full">10 Modul Tersedia</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $featuresConfig = [
                                    'data_kk' => ['label' => 'Manajemen Data KK', 'icon' => '👥', 'color' => 'blue'],
                                    'data_warga' => ['label' => 'Manajemen Data Warga', 'icon' => '📝', 'color' => 'indigo'],
                                    'iuran_warga' => ['label' => 'Pencatatan Iuran Warga', 'icon' => '💰', 'color' => 'emerald'],
                                    'pengeluaran_kas' => ['label' => 'Pencatatan Pengeluaran Kas', 'icon' => '📉', 'color' => 'rose'],
                                    'pengajuan_surat' => ['label' => 'Layanan Pengajuan Surat', 'icon' => '📄', 'color' => 'amber'],
                                    'laporan_warga' => ['label' => 'Sistem Pelaporan Warga', 'icon' => '📢', 'color' => 'orange'],
                                    'lapor_peristiwa' => ['label' => 'Pencatatan Peristiwa Warga', 'icon' => '🚨', 'color' => 'red'],
                                    'berita_pengumuman' => ['label' => 'Portal Berita & Pengumuman', 'icon' => '📰', 'color' => 'cyan'],
                                    'pasar_umkm' => ['label' => 'Pasar Warga (UMKM)', 'icon' => '🏪', 'color' => 'violet'],
                                    'export_laporan' => ['label' => 'Ekspor Laporan (Excel & PDF)', 'icon' => '📊', 'color' => 'teal'],
                                ];
                            @endphp

                            @foreach($featuresConfig as $key => $conf)
                            <div x-data="{ active: {{ old('features.' . $key) ? 'true' : 'false' }} }">
                                <label class="relative block h-full">
                                    <input type="checkbox" name="features[{{ $key }}]" value="1" x-model="active" class="sr-only">
                                    <div class="h-full p-4 rounded-3xl border-2 transition-all cursor-pointer group flex items-center gap-4"
                                         :class="active ? 'bg-emerald-600 border-emerald-600 shadow-lg shadow-emerald-200 dark:shadow-none' : 'bg-white dark:bg-slate-800/50 border-slate-100 dark:border-slate-800 hover:border-emerald-300'">
                                        
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl transition-transform group-hover:scale-110"
                                             :class="active ? 'bg-white shadow-inner' : 'bg-slate-50 dark:bg-slate-700'">
                                            {{ $conf['icon'] }}
                                        </div>
                                        
                                        <div class="flex-1">
                                            <h3 class="text-xs font-black transition-colors uppercase tracking-wider"
                                                :class="active ? 'text-white' : 'text-slate-800 dark:text-slate-200'">
                                                {{ $conf['label'] }}
                                            </h3>
                                            <p class="text-[10px] font-bold mt-0.5"
                                               :class="active ? 'text-emerald-100' : 'text-slate-400'"
                                               x-text="active ? 'Fitur Aktif' : 'Non-aktif'">
                                            </p>
                                        </div>

                                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                                             :class="active ? 'bg-white border-white scale-110' : 'bg-transparent border-slate-200 dark:border-slate-700'">
                                            <svg x-show="active" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Column: Unified Sidebar --}}
                <div class="lg:col-span-4 lg:sticky lg:top-8">
                    <div class="bg-slate-900 rounded-[3rem] shadow-2xl relative overflow-hidden text-white border border-slate-800 flex flex-col">
                        {{-- Decorative background --}}
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/20 blur-[100px] rounded-full"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-indigo-500/5 blur-[120px] rounded-full pointer-events-none"></div>
                        
                        {{-- 1. Pricing Section --}}
                        <div class="p-8 pb-6 relative z-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-black tracking-tight">Skema Harga</h2>
                                    <p class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em]">Monetisasi Paket</p>
                                </div>
                            </div>

                            <div class="space-y-5" x-data="{ 
                                monthly: '{{ old('price_monthly', 0) }}',
                                yearly: '{{ old('price_yearly', 0) }}',
                                formatRaw(val) { return val.toString().replace(/\D/g, ''); },
                                formatDisplay(val) {
                                    if (!val || val == 0) return '';
                                    return val.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                },
                                applyRecommendation() {
                                    if (this.monthly > 0) {
                                        // Annual = 11 months (1 month discount)
                                        this.yearly = Math.round(this.monthly * 11);
                                    }
                                },
                                get monthsSaved() {
                                    if (!this.monthly || !this.yearly || this.monthly == 0) return 0;
                                    let fullPrice = this.monthly * 12;
                                    if (this.yearly >= fullPrice) return 0;
                                    return Math.floor((fullPrice - this.yearly) / this.monthly);
                                }
                            }">
                                <div class="space-y-2 group">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 transition-colors group-focus-within:text-emerald-400">Bulanan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-5 flex items-center text-emerald-500/50 font-black text-xs">Rp</div>
                                        <input type="text" x-bind:value="formatDisplay(monthly)" x-on:input="monthly = formatRaw($event.target.value)"
                                               placeholder="0"
                                               class="w-full bg-white/[0.03] border-2 border-white/5 rounded-[1.5rem] py-4 pl-12 pr-6 text-xl font-black focus:border-emerald-500/50 focus:bg-white/[0.07] transition-all placeholder:text-slate-800 text-white">
                                        <input type="hidden" name="price_monthly" x-bind:value="monthly">
                                    </div>
                                    @error('price_monthly') <p class="text-xs text-rose-500 mt-2 font-bold px-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2 group">
                                    <div class="flex items-center justify-between ml-1">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest transition-colors group-focus-within:text-indigo-400">Tahunan</label>
                                        <button type="button" 
                                                x-show="monthly > 0"
                                                x-on:click="applyRecommendation()"
                                                class="flex items-center gap-1.5 px-2 py-1 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 rounded-lg text-[8px] font-black uppercase tracking-tighter border border-indigo-500/20 transition-all active:scale-95">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311L4.542 14.83a.75.75 0 01-1.28-.53V9.583A.75.75 0 014.012 8.83l4.718.01a.75.75 0 01.53 1.281L8.003 11.38l.312.311a4 4 0 006.691-1.794.75.75 0 011.306.527zm-1.124-2.848a.75.75 0 01-1.306-.527 4 4 0 00-6.691 1.794.75.75 0 01-1.306-.527 5.5 5.5 0 019.201-2.466l.312.311 1.257-1.257a.75.75 0 011.28.53v4.718a.75.75 0 01-.75.75l-4.718-.01a.75.75 0 01-.53-1.281l1.257-1.257-.312-.311z" clip-rule="evenodd" /></svg>
                                            Magic Fill (Potongan 1 Bln)
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-5 flex items-center text-indigo-500/50 font-black text-xs">Rp</div>
                                        <input type="text" x-bind:value="formatDisplay(yearly)" x-on:input="yearly = formatRaw($event.target.value)"
                                               placeholder="0"
                                               class="w-full bg-white/[0.03] border-2 border-white/5 rounded-[1.5rem] py-4 pl-12 pr-6 text-xl font-black focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all placeholder:text-slate-800 text-white">
                                        <input type="hidden" name="price_yearly" x-bind:value="yearly">
                                    </div>
                                    @error('price_yearly') <p class="text-xs text-rose-500 mt-2 font-bold px-1">{{ $message }}</p> @enderror
                                    <div class="flex items-center gap-2 px-1 mt-2" x-show="monthsSaved > 0">
                                        <div class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 rounded-full text-[9px] font-black uppercase tracking-tighter" x-text="'Hemat ' + monthsSaved + ' Bulan'"></div>
                                        <span class="text-[9px] text-slate-500 font-bold italic">Promo bayar tahunan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mx-8"></div>

                        {{-- 2. Visibility & Final Action --}}
                        <div class="p-8 pt-6 relative z-10 flex flex-col gap-6">
                            
                            <label class="flex items-center justify-between p-4 bg-white/[0.02] border border-white/5 rounded-2xl cursor-pointer group hover:bg-white/[0.05] transition-all">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white transition-colors">Populer</span>
                                    <span class="text-[9px] text-slate-500 font-bold mt-0.5">Tandai sebagai paket rekomendasi</span>
                                </div>
                                <div class="relative inline-flex items-center">
                                    <input type="hidden" name="is_popular" value="0">
                                    <input type="checkbox" name="is_popular" value="1" class="sr-only peer">
                                    <div class="w-12 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-slate-900 after:content-[''] after:absolute after:top-[3px] after:start-[3px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500 peer-checked:after:bg-white shadow-inner"></div>
                                </div>
                            </label>

                            <label class="flex items-center justify-between p-4 bg-white/[0.02] border border-white/5 rounded-2xl cursor-pointer group hover:bg-white/[0.05] transition-all">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-white transition-colors">Visibilitas</span>
                                    <span class="text-[9px] text-slate-500 font-bold mt-0.5">Publish ke halaman depan</span>
                                </div>
                                <div class="relative inline-flex items-center">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                    <div class="w-12 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-slate-900 after:content-[''] after:absolute after:top-[3px] after:start-[3px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white shadow-inner"></div>
                                </div>
                            </label>

                            <div class="space-y-3">
                                <button type="submit" class="group relative w-full flex items-center justify-center gap-3 py-5 bg-gradient-to-r from-emerald-600 to-teal-500 text-white rounded-[1.8rem] font-black text-xs shadow-2xl shadow-emerald-900/20 dark:shadow-none hover:scale-[1.02] hover:brightness-110 transition-all active:scale-[0.98] overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <span class="uppercase tracking-[0.1em]">Launch Paket Baru</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1"><path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" /></svg>
                                </button>
                                
                                <p class="text-[8px] text-center text-slate-600 font-black uppercase tracking-[0.3em]">SmartRT Vision • RT/RW Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-super-admin-layout>
