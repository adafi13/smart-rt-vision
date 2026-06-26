<x-app-layout title="Akses Workspace Dikunci">
    <!-- FULL SCREEN LOCK OVERLAY -->
    <div class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-md flex flex-col pt-16 sm:pt-20 px-4 overflow-y-auto overflow-x-hidden">
        
        <!-- Floating Logout Button -->
        <div class="absolute top-4 right-4 sm:top-8 sm:right-8 z-[60]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-white/95 backdrop-blur-md rounded-full text-[10px] sm:text-[11px] font-black uppercase tracking-[0.2em] text-slate-600 hover:text-rose-600 hover:bg-white hover:scale-105 shadow-xl shadow-black/10 border border-white/50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="hidden sm:inline">Keluar /</span> Logout
                </button>
            </form>
        </div>

        <div class="max-w-6xl w-full mx-auto pb-24 relative mt-4 sm:mt-0">
            
            <!-- Lock Header Message -->
            <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] p-8 sm:p-10 md:p-14 shadow-2xl border border-rose-100 relative overflow-hidden mb-6 sm:mb-8 text-center max-w-3xl mx-auto transform translate-y-4">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-50 via-white to-white -z-10"></div>
                
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-rose-50 rounded-[1.5rem] sm:rounded-[2rem] flex items-center justify-center mx-auto mb-5 sm:mb-6 shadow-inner border border-rose-100">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                
                <h2 class="text-[10px] sm:text-[12px] font-black text-rose-500 uppercase tracking-[0.3em] mb-2 sm:mb-3">Akses Ditangguhkan</h2>
                <h1 class="text-2xl sm:text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-4 sm:mb-6 leading-tight">Masa Aktif Paket Berakhir</h1>
                
                <p class="text-slate-500 text-xs sm:text-sm md:text-base leading-relaxed max-w-xl mx-auto font-medium">
                    Workspace <span class="font-bold text-slate-900">SmartRT Vision</span> Anda dikunci sementara karena masa berlangganan atau trial telah habis. Perbarui paket sekarang untuk kembali menikmati seluruh fitur tanpa batas.
                </p>
                
                <div class="mt-6 sm:mt-8 pt-6 sm:pt-8 border-t border-slate-100">
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                        Pilih paket lanjutan di bawah ini untuk mengaktifkan kembali workspace Anda
                    </p>
                </div>
            </div>

            <!-- MAIN CONTENT: SUBSCRIPTION PLANS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 lg:gap-8 mt-12 sm:mt-0">
                @foreach($plans as $plan)
                <div class="rounded-[2rem] sm:rounded-[2.5rem] border relative transition-all duration-300 flex flex-col {{ $plan->is_popular ? 'border-indigo-500 shadow-2xl shadow-indigo-200/50 sm:scale-[1.03] bg-white z-10' : 'border-slate-200 shadow-lg shadow-slate-200/30 bg-white hover:-translate-y-1 hover:shadow-xl' }}">
                    @if($plan->is_popular)
                    <div class="absolute -top-4 inset-x-0 flex justify-center">
                        <span class="px-5 py-1.5 rounded-full text-[9px] sm:text-[10px] font-black text-white shadow-lg tracking-[0.2em] uppercase whitespace-nowrap" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            REKOMENDASI
                        </span>
                    </div>
                    @endif
                    
                    <div class="p-6 sm:p-8 md:p-10 flex-1">
                        <p class="text-[10px] sm:text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 sm:mb-4">{{ $plan->name }}</p>
                        <div class="flex items-baseline gap-1 mb-6 sm:mb-8">
                            <span class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Rp{{ number_format($plan->price_monthly, 0, ',', '.') }}</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-400">/bln</span>
                        </div>
                        
                        <p class="text-[9.5px] sm:text-[11px] font-bold text-slate-500 uppercase tracking-widest h-auto sm:h-10 mb-6 leading-relaxed">
                            @if($plan->slug === 'starter')
                                COCOK UNTUK RT SKALA KECIL DENGAN KEBUTUHAN PENDATAAN WARGA STANDAR
                            @elseif($plan->slug === 'growth')
                                SOLUSI OPTIMAL UNTUK RT BERKEMBANG, DILENGKAPI EKSTRAKSI AI CERDAS
                            @else
                                SOLUSI MENYELURUH UNTUK OPERASIONAL RT BESAR DENGAN AKSES TANPA BATAS
                            @endif
                        </p>

                        <ul class="space-y-3 sm:space-y-4 mb-6 sm:mb-8">
                            @foreach($plan->features ?? [] as $feature)
                            <li class="flex items-start gap-3 sm:gap-4">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-medium text-slate-700 leading-relaxed">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="p-6 sm:p-8 pt-0 mt-auto">
                        <form action="{{ route('billing.checkout', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full relative group overflow-hidden py-3.5 sm:py-4 rounded-xl sm:rounded-2xl text-[10px] sm:text-[11px] font-black uppercase tracking-[0.2em] transition-all shadow-sm {{ $plan->is_popular ? 'text-white' : 'text-slate-700 bg-slate-50 border border-slate-200 hover:bg-slate-100' }}" @if($plan->is_popular) style="background: linear-gradient(135deg, #4f46e5, #7c3aed);" @endif>
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    Buka Akses <span class="hidden sm:inline">dengan {{ $plan->name }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                @if($plan->is_popular)
                                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Footer Secure Badge -->
            <div class="mt-8 flex justify-center pb-8 px-2 text-center">
                <p class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 bg-white rounded-full shadow-lg border border-slate-100 text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Pembayaran diproses aman <span class="hidden sm:inline">melalui Xendit Payment Gateway</span>
                </p>
            </div>
            
        </div>
    </div>
</x-app-layout>
