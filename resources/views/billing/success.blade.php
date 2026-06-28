<x-app-layout title="Pembayaran Berhasil">
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Invoice Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-slate-100 relative">
                
                {{-- Decorative background elements --}}
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-emerald-50 opacity-50 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-indigo-50 opacity-50 blur-3xl"></div>

                <div class="p-8 sm:p-12 relative z-10">
                    
                    {{-- Success Animation & Header --}}
                    <div class="text-center mb-10 transition-all duration-700 ease-out transform" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                        <div class="w-24 h-24 mx-auto bg-emerald-100 rounded-full flex items-center justify-center mb-6 relative">
                            <div class="absolute inset-0 rounded-full bg-emerald-400 animate-ping opacity-20"></div>
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Pembayaran Berhasil!</h1>
                        <p class="text-slate-500 font-medium">Terima kasih, pembayaran untuk langganan SmartRT Vision Anda telah kami terima.</p>
                    </div>

                    {{-- Invoice Card --}}
                    <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 relative transition-all duration-700 delay-300 ease-out transform" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                        
                        {{-- Watermark --}}
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-[120px] font-black text-slate-200/40 rotate-[-15deg] select-none pointer-events-none z-0">
                            LUNAS
                        </div>

                        <div class="relative z-10">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-200/60 pb-6 mb-6">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kode Invoice</p>
                                    <p class="text-lg font-bold text-slate-900 font-mono">{{ $subscription->payment_external_id }}</p>
                                </div>
                                <div class="mt-4 sm:mt-0 text-left sm:text-right">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Bayar</p>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $subscription->paid_at ? $subscription->paid_at->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">Paket {{ $subscription->plan->name }}</p>
                                            <p class="text-xs text-slate-500">Berlaku selama 30 hari ke depan</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-black text-slate-900">Rp{{ number_format($subscription->amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-200/60 pt-6">
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Pembayaran</p>
                                    <p class="text-3xl font-black text-indigo-600">Rp{{ number_format($subscription->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4 transition-all duration-700 delay-500 ease-out transform" :class="show ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
                        <button onclick="window.print()" class="inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak / Simpan PDF
                        </button>
                        <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center gap-2 px-8 py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                            Masuk ke Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                </div>
            </div>
            
            <p class="text-center text-xs text-slate-400 mt-6 font-medium">
                Sistem sedang memperbarui status layanan Anda. Proses ini biasanya memakan waktu kurang dari 1 menit.
            </p>
        </div>
    </div>
</x-app-layout>
