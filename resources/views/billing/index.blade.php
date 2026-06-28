<x-app-layout title="Paket & Tagihan">
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-slate-900">Paket &amp; Tagihan</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola langganan workspace SmartRT Vision Anda</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-6 py-4 pb-10">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ══════════════════════════════════════ --}}
        {{-- 1. STATUS LANGGANAN                   --}}
        {{-- ══════════════════════════════════════ --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Langganan</p>
            </div>
            <div class="p-6 md:p-8">
                @if($tenant->onTrial())
                    <div class="flex items-center gap-3 mb-2">
                        <span class="relative flex h-4 w-4 flex-shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                        </span>
                        <p class="text-xl md:text-2xl font-black text-slate-900">Masa Percobaan (Trial)</p>
                    </div>
                    <p class="text-sm text-slate-500 mb-5 ml-7">Berakhir pada <span class="font-bold text-slate-900">{{ $tenant->trial_ends_at->translatedFormat('d F Y') }}</span></p>
                    @php
                        $totalTrial = config('kakaai.trial_days', 14);
                        $daysLeft   = max(0, (int) now()->diffInDays($tenant->trial_ends_at, false));
                        $percent    = max(0, min(100, ($daysLeft / $totalTrial) * 100));
                    @endphp
                    <div class="max-w-lg ml-7">
                        <div class="flex justify-between text-[11px] font-bold mb-1.5">
                            <span class="text-amber-600">{{ $daysLeft }} hari tersisa</span>
                            <span class="text-slate-400">{{ $totalTrial }} hari total</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                @elseif($subscription && $subscription->isActive())
                    <div class="flex items-center gap-3 mb-2">
                        <span class="relative flex h-4 w-4 flex-shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                        </span>
                        <p class="text-xl md:text-2xl font-black text-slate-900">Aktif — Paket {{ $subscription->plan->name }}</p>
                    </div>
                    <p class="text-sm text-slate-500 mb-5 ml-7">Berlaku hingga <span class="font-bold text-slate-900">{{ $subscription->current_period_end->translatedFormat('d F Y') }}</span></p>
                    @php
                        $totalDays = (int) $subscription->current_period_start->diffInDays($subscription->current_period_end);
                        $daysLeft  = max(0, (int) now()->diffInDays($subscription->current_period_end, false));
                        $percent   = max(0, min(100, ($daysLeft / max(1, $totalDays)) * 100));
                    @endphp
                    <div class="max-w-lg ml-7">
                        <div class="flex justify-between text-[11px] font-bold mb-1.5">
                            <span class="text-emerald-600">{{ $daysLeft }} hari tersisa</span>
                            <span class="text-slate-400">Siklus {{ $totalDays }} hari</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                @else
                    <div class="flex items-center gap-3 mb-2">
                        <span class="relative flex h-4 w-4 flex-shrink-0">
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500"></span>
                        </span>
                        <p class="text-xl md:text-2xl font-black text-slate-900">Belum Berlangganan</p>
                    </div>
                    <p class="text-sm text-slate-500 ml-7">Pilih paket di bawah untuk mulai menggunakan semua fitur.</p>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 2. PILIHAN PAKET                      --}}
        {{-- ══════════════════════════════════════ --}}
        <div x-data="{ isYearly: false }">

            {{-- Header + Toggle --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Pilih Paket Langganan</h2>

                {{-- Toggle Bulanan / Tahunan --}}
                <div class="inline-flex items-center self-start sm:self-auto bg-slate-100 border border-slate-200 rounded-xl p-1 gap-1 shadow-inner">
                    <button type="button" @click="isYearly = false"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200"
                        :class="!isYearly ? 'bg-white text-indigo-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-800'">
                        Bulanan
                    </button>
                    <button type="button" @click="isYearly = true"
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 flex items-center gap-1.5"
                        :class="isYearly ? 'bg-white text-indigo-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-800'">
                        Tahunan
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-emerald-100 text-emerald-700 tracking-wide">HEMAT</span>
                    </button>
                </div>
            </div>

            {{-- Plan Cards --}}
            {{-- Mobile: vertical stack | Desktop: 3-column grid --}}
            <div class="flex flex-col gap-4 md:grid md:grid-cols-3 md:gap-5 md:items-start">
                @foreach($plans as $plan)
                <div class="relative bg-white rounded-2xl border flex flex-col transition-all duration-300
                    {{ $plan->is_popular
                        ? 'border-indigo-400 ring-2 ring-indigo-400/30 shadow-lg shadow-indigo-100/60'
                        : 'border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5' }}">

                    {{-- Popular badge --}}
                    @if($plan->is_popular)
                    <div class="absolute -top-3.5 inset-x-0 flex justify-center">
                        <span class="px-4 py-1 rounded-full text-[10px] font-black text-white tracking-widest uppercase"
                              style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            PALING POPULER
                        </span>
                    </div>
                    @endif

                    {{-- Card body --}}
                    <div class="p-5 md:p-6 flex-1 {{ $plan->is_popular ? 'pt-7' : '' }}">

                        {{-- Plan name --}}
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            Paket {{ $plan->name }}
                        </p>

                        {{-- Price - monthly --}}
                        <div x-show="!isYearly" class="mb-1">
                            <div class="flex items-end gap-1 flex-wrap">
                                <span class="text-2xl md:text-3xl font-black text-slate-900 leading-none">
                                    Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 mb-0.5">/bulan</span>
                            </div>
                        </div>

                        {{-- Price - yearly --}}
                        <div x-show="isYearly" style="display:none;" class="mb-1">
                            <div class="flex items-end gap-1 flex-wrap">
                                <span class="text-2xl md:text-3xl font-black text-slate-900 leading-none">
                                    Rp {{ number_format($plan->price_yearly, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 mb-0.5">/tahun</span>
                            </div>
                            <p class="text-[10px] text-emerald-600 font-bold mt-1">
                                ≈ Rp {{ number_format(intdiv($plan->price_yearly, 12), 0, ',', '.') }}/bln · hemat 2 bulan
                            </p>
                        </div>

                        <hr class="my-4 border-slate-100">

                        {{-- Features --}}
                        <ul class="space-y-2.5">
                            <li class="flex items-start gap-2.5 text-sm text-slate-700">
                                <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Maks. <b class="ml-1">{{ $plan->max_kk ? number_format($plan->max_kk) : 'Unlimited' }}</b>&nbsp;KK
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-slate-700">
                                <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Maks. <b class="ml-1">{{ $plan->max_users ?: 'Unlimited' }}</b>&nbsp;Akun Pengurus
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-slate-700">
                                <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <b>{{ $plan->max_ai_extractions_per_month ?: 'Unlimited' }}x</b>&nbsp;Scan KTP/KK per Bln
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-slate-700">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <b>Akses Penuh</b>&nbsp;Seluruh Modul
                            </li>
                        </ul>
                    </div>

                    {{-- CTA button --}}
                    <div class="p-5 md:p-6 pt-0">
                        @if($subscription && $subscription->plan_id === $plan->id && $subscription->isActive())
                            <div class="w-full py-3 rounded-xl text-[11px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-200 text-center cursor-not-allowed select-none">
                                ✓ Paket Saat Ini
                            </div>
                        @else
                            <form :action="isYearly ? '{{ route('billing.checkout', $plan) }}?cycle=yearly' : '{{ route('billing.checkout', $plan) }}'" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-200 relative overflow-hidden group
                                    {{ $plan->is_popular ? 'text-white shadow-md hover:shadow-lg hover:opacity-90' : 'text-slate-700 bg-slate-50 border border-slate-200 hover:bg-slate-100 hover:border-slate-300' }}"
                                    @if($plan->is_popular) style="background: linear-gradient(135deg, #4f46e5, #7c3aed);" @endif>
                                    @if($subscription && $subscription->isActive())
                                        Ganti ke {{ $plan->name }}
                                    @else
                                        Pilih Paket {{ $plan->name }}
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Xendit badge --}}
            <div class="mt-6 flex justify-center">
                <p class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-full border border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Pembayaran aman via Xendit
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════ --}}
        {{-- 3. RIWAYAT TRANSAKSI                  --}}
        {{-- ══════════════════════════════════════ --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Riwayat Transaksi</h2>
            </div>

            {{-- Desktop table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/30">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4">Siklus</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($histories as $history)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">{{ $history->plan?->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">Rp{{ number_format($history->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-[10px] font-bold text-slate-500">
                                    {{ $history->billing_cycle === 'yearly' ? '📅 Tahunan' : '🗓 Bulanan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($history->status === 'active')
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">Lunas</span>
                                @elseif($history->status === 'pending_payment')
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-amber-50 text-amber-600 border border-amber-100">Menunggu</span>
                                @elseif($history->status === 'cancelled')
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-rose-50 text-rose-600 border border-rose-100">Dibatalkan</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-slate-100 text-slate-500">{{ ucfirst($history->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($history->status === 'pending_payment')
                                    <div class="flex items-center justify-end gap-3">
                                        <form action="{{ route('billing.cancel', $history) }}" method="POST" onsubmit="return confirm('Batalkan tagihan ini?');">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-black uppercase text-slate-400 hover:text-rose-500 transition-colors underline underline-offset-4">Batalkan</button>
                                        </form>
                                        @if($history->payment_url)
                                        <a href="{{ $history->payment_url }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg transition-all shadow-sm">
                                            Bayar
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                        @endif
                                    </div>
                                @elseif($history->status === 'active')
                                    <span class="text-[10px] font-black uppercase text-emerald-500">✓ Selesai</span>
                                @else
                                    <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Belum ada riwayat transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list --}}
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($histories as $history)
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $history->plan?->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        @if($history->status === 'active')
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex-shrink-0">Lunas</span>
                        @elseif($history->status === 'pending_payment')
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-amber-50 text-amber-600 border border-amber-100 flex-shrink-0">Menunggu</span>
                        @elseif($history->status === 'cancelled')
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-rose-50 text-rose-600 border border-rose-100 flex-shrink-0">Dibatalkan</span>
                        @else
                            <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-full bg-slate-100 text-slate-500 flex-shrink-0">{{ ucfirst($history->status) }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-mono font-bold text-slate-800 text-sm">Rp{{ number_format($history->amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $history->billing_cycle === 'yearly' ? '📅 Tahunan' : '🗓 Bulanan' }}</p>
                        </div>
                        @if($history->status === 'pending_payment')
                        <div class="flex items-center gap-2">
                            <form action="{{ route('billing.cancel', $history) }}" method="POST" onsubmit="return confirm('Batalkan tagihan ini?');">
                                @csrf
                                <button type="submit" class="text-[10px] font-black uppercase text-slate-400 hover:text-rose-500 transition-colors underline underline-offset-4">Batalkan</button>
                            </form>
                            @if($history->payment_url)
                            <a href="{{ $history->payment_url }}" target="_blank"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-black uppercase bg-indigo-600 text-white rounded-lg shadow-sm">
                                Bayar →
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-16 text-center px-6">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Belum ada riwayat transaksi</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
