<x-app-layout title="Paket & Tagihan">
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-slate-900">Paket &amp; Tagihan</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola langganan workspace SmartRT Vision Anda</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-8 py-4">
        @if(session('success'))
            <div class="flex items-center gap-3 px-6 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 px-6 py-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- 1. Dashboard Status Langganan -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden relative group">
            <!-- Decorative background -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-50/50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between gap-8 z-10">
                <div class="w-full">
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Status Saat Ini</h2>
                    @if($tenant->onTrial())
                        <div class="flex items-center gap-3 mb-3">
                            <span class="relative flex h-5 w-5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-5 w-5 bg-amber-500"></span>
                            </span>
                            <p class="text-3xl font-black text-slate-900 tracking-tight">Masa Percobaan (Trial)</p>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mb-6">Anda sedang dalam masa trial gratis. Berakhir pada <span class="font-bold text-slate-900">{{ $tenant->trial_ends_at->translatedFormat('d F Y') }}</span>.</p>
                        
                        @php
                            $totalTrial = config('kakaai.trial_days', 14);
                            $daysLeft = max(0, now()->diffInDays($tenant->trial_ends_at, false));
                            $percent = max(0, min(100, ($daysLeft / $totalTrial) * 100));
                        @endphp
                        <div class="w-full max-w-lg">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-[11px] font-black text-amber-600 uppercase tracking-widest">{{ floor($daysLeft) }} Hari Tersisa</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total {{ $totalTrial }} Hari</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                    @elseif($subscription && $subscription->isActive())
                        <div class="flex items-center gap-3 mb-3">
                            <span class="relative flex h-5 w-5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500"></span>
                            </span>
                            <p class="text-3xl font-black text-slate-900 tracking-tight">Aktif — Paket {{ $subscription->plan->name }}</p>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mb-6">Layanan Anda aktif dan berlaku hingga <span class="font-bold text-slate-900">{{ $subscription->current_period_end->translatedFormat('d F Y') }}</span>.</p>
                        
                        @php
                            $totalDays = $subscription->current_period_start->diffInDays($subscription->current_period_end);
                            $daysLeft = max(0, now()->diffInDays($subscription->current_period_end, false));
                            $percent = max(0, min(100, ($daysLeft / max(1, $totalDays)) * 100));
                        @endphp
                        <div class="w-full max-w-lg">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest">{{ floor($daysLeft) }} Hari Tersisa</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siklus {{ $totalDays }} Hari</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 mb-3">
                            <span class="relative flex h-5 w-5">
                              <span class="relative inline-flex rounded-full h-5 w-5 bg-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.5)]"></span>
                            </span>
                            <p class="text-3xl font-black text-slate-900 tracking-tight">Belum Berlangganan</p>
                        </div>
                        <p class="text-sm font-medium text-slate-500">Pilih paket di bawah untuk mulai menggunakan semua fitur SmartRT Vision.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Pilihan Paket -->
        <div class="pt-4">
            <h2 class="text-sm font-black text-slate-900 uppercase tracking-[0.2em] mb-6 pl-2">Pilih Paket Langganan</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($plans as $plan)
                <div class="rounded-[2.5rem] border relative transition-all duration-300 flex flex-col {{ $plan->is_popular ? 'border-indigo-500 shadow-2xl shadow-indigo-200/50 scale-[1.03] bg-white z-10' : 'border-slate-200 shadow-lg shadow-slate-200/30 bg-white hover:-translate-y-1 hover:shadow-xl' }}">
                    @if($plan->is_popular)
                    <div class="absolute -top-4 inset-x-0 flex justify-center">
                        <span class="px-5 py-1.5 rounded-full text-[10px] font-black text-white shadow-lg tracking-[0.2em] uppercase" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            PALING POPULER
                        </span>
                    </div>
                    @endif
                    
                    <div class="p-8 md:p-10 flex-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">{{ $plan->name }}</p>
                        <div class="flex items-baseline gap-1 mb-8">
                            <span class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">Rp{{ number_format($plan->price_monthly, 0, ',', '.') }}</span>
                            <span class="text-sm font-bold text-slate-400">/bln</span>
                        </div>
                        
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 leading-relaxed">
                                    Maks. <b>{{ $plan->max_kk ?: 'Unlimited' }}</b> Kartu Keluarga
                                </span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 leading-relaxed">
                                    Maks. <b>{{ $plan->max_users ?: 'Unlimited' }}</b> Akun Pengurus
                                </span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 leading-relaxed">
                                    <b>{{ $plan->max_ai_extractions_per_month ?: 'Unlimited' }}x</b> Scan KTP/KK per Bulan
                                </span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700 leading-relaxed">
                                    <b>Akses Penuh</b> ke Seluruh Modul Aplikasi
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="p-8 pt-0 mt-auto">
                        @if($subscription && $subscription->plan_id === $plan->id && $subscription->isActive())
                            <button disabled class="w-full py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] bg-emerald-50 text-emerald-700 border border-emerald-200 cursor-not-allowed">
                                Paket Saat Ini
                            </button>
                        @else
                            <form action="{{ route('billing.checkout', $plan) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full relative group overflow-hidden py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all shadow-sm {{ $plan->is_popular ? 'text-white' : 'text-slate-700 bg-slate-50 border border-slate-200 hover:bg-slate-100' }}" @if($plan->is_popular) style="background: linear-gradient(135deg, #4f46e5, #7c3aed);" @endif>
                                    <span class="relative z-10">
                                        @if($subscription && $subscription->isActive())
                                            Ganti ke {{ $plan->name }}
                                        @else
                                            Pilih {{ $plan->name }}
                                        @endif
                                    </span>
                                    @if($plan->is_popular)
                                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">
                <p class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50/50 rounded-full border border-emerald-100 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Pembayaran diproses aman melalui Xendit Payment Gateway
                </p>
            </div>
        </div>

        <!-- 3. Riwayat Transaksi -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden mt-8">
            <div class="p-8 border-b border-slate-100 bg-slate-50/30">
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-[0.25em]">Riwayat Transaksi</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="px-8 py-4 whitespace-nowrap">Tanggal</th>
                            <th class="px-8 py-4 whitespace-nowrap">Paket</th>
                            <th class="px-8 py-4 whitespace-nowrap">Nominal</th>
                            <th class="px-8 py-4 whitespace-nowrap">Status</th>
                            <th class="px-8 py-4 whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($histories as $history)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-8 py-5 text-slate-600 font-medium whitespace-nowrap">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-8 py-5 font-bold text-slate-900 whitespace-nowrap">{{ $history->plan?->name ?? '-' }}</td>
                            <td class="px-8 py-5 font-mono text-slate-700 whitespace-nowrap">Rp{{ number_format($history->amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                @if($history->status === 'active')
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">Aktif (Lunas)</span>
                                @elseif($history->status === 'pending_payment')
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-amber-50 text-amber-600 border border-amber-100">Menunggu Pembayaran</span>
                                @elseif($history->status === 'cancelled')
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-rose-50 text-rose-600 border border-rose-100">Dibatalkan</span>
                                @else
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-slate-100 text-slate-500">{{ ucfirst($history->status) }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right whitespace-nowrap">
                                @if($history->status === 'pending_payment')
                                    <div class="flex items-center justify-end gap-3">
                                        <form action="{{ route('billing.cancel', $history) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tagihan ini?');">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-rose-500 transition-colors underline underline-offset-4">
                                                Batalkan
                                            </button>
                                        </form>
                                        @if($history->payment_url)
                                            <a href="{{ $history->payment_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-[10px] font-black uppercase tracking-widest bg-slate-900 text-white hover:bg-indigo-600 rounded-xl transition-all shadow-md">
                                                Bayar
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                @elseif($history->status === 'active')
                                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500 flex items-center justify-end gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Selesai
                                    </span>
                                @else
                                    <span class="text-xs text-slate-300 font-bold">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-[1.5rem] flex items-center justify-center mb-4 shadow-inner">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Belum ada riwayat transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
