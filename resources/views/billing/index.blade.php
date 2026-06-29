<x-app-layout title="Paket & Tagihan">
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-slate-900">Paket &amp; Tagihan</h1>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola langganan SmartRT Vision Anda</p>
        </div>
    </x-slot>

    <style>
        /* ── Premium Billing Page ─────────────────────────── */
        .plan-card {
            position: relative;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease, opacity .25s ease;
        }
        .plan-card.popular {
            border-color: #6366f1;
            background: linear-gradient(160deg, #f5f3ff 0%, #ffffff 60%);
            box-shadow: 0 0 0 4px rgba(99,102,241,.12), 0 12px 40px rgba(99,102,241,.12);
        }
        /* cycle toggle */
        .cycle-toggle { display:inline-flex; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:14px; padding:4px; gap:4px; }
        .cycle-btn { padding:7px 20px; border-radius:10px; font-size:12px; font-weight:700; cursor:pointer; border:none; background:transparent; color:#64748b; letter-spacing:.04em; transition:all .2s; }
        .cycle-btn.active { background:#fff; color:#4f46e5; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1px solid #e2e8f0; }
        /* progress bar */
        .progress-bar { height:6px; border-radius:99px; background:#f1f5f9; overflow:hidden; }
        .progress-fill { height:100%; border-radius:99px; transition:width 1s ease; }

        /* Mobile stacked cards */
        @media (max-width: 767px) {
            .plan-card { margin-bottom: 0; }
            .plans-grid { display:flex; flex-direction:column; gap:16px; }
        }
        @media (min-width: 768px) {
            .plans-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; align-items:start; }
            .plan-card.popular { transform: scale(1.03); }
        }

        /* History mobile cards */
        .history-mobile-card { padding:16px; border-bottom:1px solid #f1f5f9; }
        .history-mobile-card:last-child { border-bottom:none; }

        /* Shine animation for popular badge */
        @keyframes shine { 0%{background-position:-200%} 100%{background-position:200%} }
    </style>

    <div class="max-w-5xl space-y-6 py-4 pb-12">

        {{-- ─── Flash ──────────────────────────────────────── --}}
        @if(session('success'))
        <div class="flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 text-sm font-semibold">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- ─── 1. STATUS CARD ─────────────────────────────── --}}
        <div style="background:#fff; border:1.5px solid #e2e8f0; border-radius:20px; overflow:hidden;">

            {{-- Header bar --}}
            <div style="padding:14px 24px; border-bottom:1px solid #f1f5f9; background:#fafafa;">
                <p style="font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.12em; text-transform:uppercase;">Status Langganan</p>
            </div>

            <div style="padding:24px;">
                @if($tenant->onTrial())
                    @php
                        $totalTrial = config('kakaai.trial_days', 14);
                        $daysLeft   = max(0, (int) now()->diffInDays($tenant->trial_ends_at, false));
                        $percent    = max(0, min(100, ($daysLeft / $totalTrial) * 100));
                    @endphp
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:#fef3c7; border:1px solid #fde68a; border-radius:99px; font-size:11px; font-weight:800; color:#d97706; letter-spacing:.05em; text-transform:uppercase;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block;animation:ping 1.5s ease infinite;"></span>
                            Trial Aktif
                        </span>
                    </div>
                    <p style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:4px;">Masa Percobaan Gratis</p>
                    <p style="font-size:13px; color:#64748b; margin-bottom:18px;">Berakhir pada <strong style="color:#0f172a;">{{ $tenant->trial_ends_at->translatedFormat('d F Y') }}</strong></p>
                    <div style="max-width:440px;">
                        <div style="display:flex; justify-content:space-between; font-size:11px; font-weight:700; margin-bottom:8px;">
                            <span style="color:#d97706;">{{ $daysLeft }} hari tersisa</span>
                            <span style="color:#94a3b8;">{{ $totalTrial }} hari total</span>
                        </div>
                        <div class="progress-bar"><div class="progress-fill" style="width:{{ $percent }}%; background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div></div>
                    </div>

                @elseif($subscription && $subscription->isActive())
                    @php
                        $totalDays = (int) $subscription->current_period_start->diffInDays($subscription->current_period_end);
                        $daysLeft  = max(0, (int) now()->diffInDays($subscription->current_period_end, false));
                        $percent   = max(0, min(100, ($daysLeft / max(1, $totalDays)) * 100));
                    @endphp
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:#d1fae5; border:1px solid #6ee7b7; border-radius:99px; font-size:11px; font-weight:800; color:#059669; letter-spacing:.05em; text-transform:uppercase;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                            Aktif
                        </span>
                        <span style="font-size:11px; color:#94a3b8; font-weight:600;">Paket {{ $subscription->plan->name }}</span>
                    </div>
                    <p style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:4px;">Langganan Berjalan</p>
                    <p style="font-size:13px; color:#64748b; margin-bottom:18px;">Berlaku hingga <strong style="color:#0f172a;">{{ $subscription->current_period_end->translatedFormat('d F Y') }}</strong></p>
                    <div style="max-width:440px;">
                        <div style="display:flex; justify-content:space-between; font-size:11px; font-weight:700; margin-bottom:8px;">
                            <span style="color:#059669;">{{ $daysLeft }} hari tersisa</span>
                            <span style="color:#94a3b8;">Siklus {{ $totalDays }} hari</span>
                        </div>
                        <div class="progress-bar"><div class="progress-fill" style="width:{{ $percent }}%; background:linear-gradient(90deg,#10b981,#34d399);"></div></div>
                    </div>

                @else
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:#fee2e2; border:1px solid #fca5a5; border-radius:99px; font-size:11px; font-weight:800; color:#dc2626; letter-spacing:.05em; text-transform:uppercase;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;"></span>
                            Tidak Aktif
                        </span>
                    </div>
                    <p style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:4px;">Belum Berlangganan</p>
                    <p style="font-size:13px; color:#64748b;">Pilih paket di bawah untuk mengaktifkan semua fitur SmartRT Vision.</p>
                @endif
            </div>
        </div>

        {{-- ─── 2. PROMO BANNER ──────────────────────────────── --}}
        @if(isset($activeCoupons) && $activeCoupons->count() > 0)
        <div class="mb-6 relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-[2px] shadow-lg">
            <div class="relative bg-white/95 backdrop-blur-xl rounded-[14px] p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-pink-500/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-lg font-bold text-slate-900 leading-tight">Promo Spesial Untuk Anda!</h3>
                        <p class="text-[11px] sm:text-sm text-slate-500 mt-1">Gunakan kode di bawah ini saat memilih paket untuk mendapatkan diskon khusus.</p>
                    </div>
                </div>

                <div class="relative z-10 flex flex-wrap md:flex-nowrap gap-2 w-full md:w-auto">
                    @foreach($activeCoupons as $coupon)
                        <div x-data="{ copied: false }" class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-lg p-2 sm:p-2.5 w-full sm:w-auto flex-shrink-0">
                            <div class="flex-1">
                                <p class="text-[9px] sm:text-[10px] font-bold text-indigo-600 uppercase tracking-wider mb-0.5">
                                    Diskon {{ $coupon->discount_type === 'percent' ? floatval($coupon->discount_value).'%' : 'Rp '.number_format($coupon->discount_value, 0, ',', '.') }}
                                    @if($coupon->applicable_cycle === 'monthly')
                                        <span class="inline-block ml-1 px-1.5 py-0.5 rounded text-[8px] bg-indigo-100 text-indigo-700">BULANAN</span>
                                    @elseif($coupon->applicable_cycle === 'yearly')
                                        <span class="inline-block ml-1 px-1.5 py-0.5 rounded text-[8px] bg-purple-100 text-purple-700">TAHUNAN</span>
                                    @endif
                                </p>
                                <p class="text-xs sm:text-sm font-mono font-bold text-slate-900 select-all">{{ $coupon->code }}</p>
                            </div>
                            <button @click="navigator.clipboard.writeText('{{ $coupon->code }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                    class="p-1.5 sm:p-2 bg-white border border-slate-200 rounded-md transition-colors" 
                                    :class="copied ? 'text-emerald-500 border-emerald-200 bg-emerald-50' : 'text-slate-400 hover:text-indigo-600 hover:border-indigo-200'"
                                    title="Salin Kode">
                                <svg x-show="!copied" class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg x-show="copied" style="display: none;" class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ─── 3. PAKET ───────────────────────────────────── --}}
        <div x-data="{ isYearly: false }">

            {{-- Section header + Toggle --}}
            <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-bottom:20px;">
                <div>
                    <p style="font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.12em; text-transform:uppercase; margin-bottom:4px;">Pilihan Paket</p>
                    <h2 style="font-size:17px; font-weight:800; color:#0f172a;">Pilih Paket Langganan</h2>
                </div>

                {{-- Toggle --}}
                <div class="cycle-toggle">
                    <button class="cycle-btn" :class="!isYearly ? 'active' : ''" @click="isYearly = false" type="button">Bulanan</button>
                    <button class="cycle-btn" :class="isYearly ? 'active' : ''" @click="isYearly = true" type="button">
                        Tahunan &nbsp;<span style="background:#dcfce7; color:#16a34a; border-radius:5px; padding:1px 6px; font-size:9px; font-weight:900; letter-spacing:.04em;">HEMAT 1 BLN</span>
                    </button>
                </div>
            </div>

            {{-- Cards --}}
            <div class="plans-grid">
                @foreach($plans as $plan)
                <div class="plan-card {{ $plan->is_popular ? 'popular' : '' }}">

                    {{-- Popular badge --}}
                    @if($plan->is_popular)
                    <div style="position:absolute; top:-14px; left:50%; transform:translateX(-50%); z-index:10;">
                        <span style="display:inline-flex; align-items:center; gap:5px; padding:5px 16px; border-radius:99px; font-size:10px; font-weight:900; color:#fff; letter-spacing:.08em; text-transform:uppercase; white-space:nowrap; background:linear-gradient(135deg,#4f46e5,#7c3aed); box-shadow:0 4px 12px rgba(99,102,241,.4);">
                            ✦ PALING POPULER
                        </span>
                    </div>
                    @endif

                    {{-- Card inner --}}
                    <div style="padding:28px 24px 20px; flex:1; {{ $plan->is_popular ? 'padding-top:36px;' : '' }}">

                        {{-- Plan label --}}
                        <p style="font-size:10px; font-weight:800; color:{{ $plan->is_popular ? '#6366f1' : '#94a3b8' }}; letter-spacing:.12em; text-transform:uppercase; margin-bottom:12px;">
                            {{ $plan->name }}
                        </p>

                        {{-- Price monthly --}}
                        <div x-show="!isYearly">
                            <div style="display:flex; align-items:baseline; gap:4px; margin-bottom:2px; flex-wrap:wrap;">
                                <span style="font-size:28px; font-weight:900; color:#0f172a; letter-spacing:-.02em; line-height:1;">
                                    Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}
                                </span>
                                <span style="font-size:12px; font-weight:600; color:#94a3b8;">/bulan</span>
                            </div>
                            <p style="font-size:11px; color:#cbd5e1; font-weight:500; margin-bottom:20px;">Tagihan bulanan</p>
                        </div>

                        {{-- Price yearly --}}
                        <div x-show="isYearly" style="display:none;">
                            <div style="display:flex; align-items:baseline; gap:4px; margin-bottom:2px; flex-wrap:wrap;">
                                <span style="font-size:28px; font-weight:900; color:#0f172a; letter-spacing:-.02em; line-height:1;">
                                    Rp {{ number_format($plan->price_yearly, 0, ',', '.') }}
                                </span>
                                <span style="font-size:12px; font-weight:600; color:#94a3b8;">/tahun</span>
                            </div>
                            <p style="font-size:11px; color:#16a34a; font-weight:700; margin-bottom:20px;">
                                ≈ Rp {{ number_format(intdiv($plan->price_yearly, 12), 0, ',', '.') }}/bln · 1 bulan gratis
                            </p>
                        </div>

                        {{-- Divider --}}
                        <div style="height:1px; background:#f1f5f9; margin-bottom:18px;"></div>

                        {{-- Features --}}
                        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                            <li style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155;">
                                <span style="width:18px; height:18px; border-radius:50%; background:{{ $plan->is_popular ? '#ede9fe' : '#f0fdf4' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $plan->is_popular ? '#6366f1' : '#22c55e' }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                @if($plan->max_kk)
                                    Maks. <strong style="margin-left:3px;">{{ number_format($plan->max_kk) }}</strong>&nbsp;Kartu Keluarga
                                @else
                                    <strong>Kartu Keluarga</strong>&nbsp;tanpa batas
                                @endif
                            </li>
                            <li style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155;">
                                <span style="width:18px; height:18px; border-radius:50%; background:{{ $plan->is_popular ? '#ede9fe' : '#f0fdf4' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $plan->is_popular ? '#6366f1' : '#22c55e' }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                @if($plan->max_users)
                                    Maks. <strong style="margin-left:3px;">{{ $plan->max_users }}</strong>&nbsp;Akun Pengurus
                                @else
                                    <strong>Akun Pengurus</strong>&nbsp;tanpa batas
                                @endif
                            </li>
                            <li style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155;">
                                <span style="width:18px; height:18px; border-radius:50%; background:{{ $plan->is_popular ? '#ede9fe' : '#f0fdf4' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $plan->is_popular ? '#6366f1' : '#22c55e' }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                @if($plan->max_ai_extractions_per_month)
                                    <strong>{{ number_format($plan->max_ai_extractions_per_month) }}x</strong>&nbsp;Scan KTP/KK per Bulan
                                @else
                                    <strong>Scan KTP/KK</strong>&nbsp;tanpa batas
                                @endif
                            </li>
                            <li style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155;">
                                <span style="width:18px; height:18px; border-radius:50%; background:#f0fdf4; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <strong>Akses Penuh</strong>&nbsp;Seluruh Modul
                            </li>
                        </ul>
                    </div>

                    {{-- CTA --}}
                    <div style="padding:0 24px 24px;">
                        @if($subscription && $subscription->plan_id === $plan->id && $subscription->isActive())
                            <div style="width:100%; padding:12px; border-radius:12px; text-align:center; background:#f0fdf4; border:1.5px solid #bbf7d0; color:#16a34a; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase;">
                                ✓ &nbsp;Paket Aktif Saat Ini
                            </div>
                        @else
                            <form :action="isYearly ? '{{ route('billing.checkout', $plan) }}?cycle=yearly' : '{{ route('billing.checkout', $plan) }}'" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="coupon_code" placeholder="Punya Kode Promo?" class="w-full px-3 py-2.5 rounded-xl border border-dashed border-slate-300 bg-slate-50 text-xs font-bold text-slate-700 text-center uppercase tracking-wider focus:outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:normal-case placeholder:font-medium placeholder:text-slate-400">
                                </div>
                                @if($plan->is_popular)
                                <button type="submit" style="width:100%; padding:13px; border-radius:12px; border:none; cursor:pointer; font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:#fff; background:linear-gradient(135deg,#4f46e5,#7c3aed); box-shadow:0 4px 16px rgba(99,102,241,.35); transition:opacity .2s;" onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                                    @if($subscription && $subscription->isActive())
                                        Ganti ke {{ $plan->name }}
                                    @else
                                        Mulai Paket {{ $plan->name }}
                                    @endif
                                </button>
                                @else
                                <button type="submit" style="width:100%; padding:13px; border-radius:12px; border:1.5px solid #e2e8f0; cursor:pointer; font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:#475569; background:#f8fafc; transition:background .2s, border-color .2s;" onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                    @if($subscription && $subscription->isActive())
                                        Ganti ke {{ $plan->name }}
                                    @else
                                        Mulai Paket {{ $plan->name }}
                                    @endif
                                </button>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Xendit secure badge --}}
            <div style="text-align:center; margin-top:20px;">
                <span style="display:inline-flex; align-items:center; gap:6px; font-size:10px; font-weight:700; color:#94a3b8; letter-spacing:.06em; text-transform:uppercase;">
                    <svg width="12" height="12" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Pembayaran diproses aman melalui Xendit
                </span>
            </div>
        </div>

        {{-- ─── 3. RIWAYAT TRANSAKSI ───────────────────────── --}}
        <div style="background:#fff; border:1.5px solid #e2e8f0; border-radius:20px; overflow:hidden;">

            <div style="padding:14px 24px; border-bottom:1px solid #f1f5f9; background:#fafafa;">
                <p style="font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.12em; text-transform:uppercase;">Riwayat Transaksi</p>
            </div>

            {{-- ── Desktop table ── --}}
            <div class="hidden md:block" style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:1px solid #f1f5f9;">
                            <th style="padding:12px 20px; text-align:left; font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.1em; text-transform:uppercase; white-space:nowrap;">Tanggal</th>
                            <th style="padding:12px 20px; text-align:left; font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.1em; text-transform:uppercase;">Paket</th>
                            <th style="padding:12px 20px; text-align:left; font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.1em; text-transform:uppercase;">Nominal</th>
                            <th style="padding:12px 20px; text-align:left; font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.1em; text-transform:uppercase;">Siklus</th>
                            <th style="padding:12px 20px; text-align:left; font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.1em; text-transform:uppercase;">Status</th>
                            <th style="padding:12px 20px; text-align:right; font-size:10px; font-weight:800; color:#94a3b8; letter-spacing:.1em; text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                        <tr style="border-bottom:1px solid #f8fafc; transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                            <td style="padding:14px 20px; font-size:12px; color:#64748b; white-space:nowrap;">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td style="padding:14px 20px; font-size:13px; font-weight:700; color:#0f172a; white-space:nowrap;">{{ $history->plan?->name ?? '—' }}</td>
                            <td style="padding:14px 20px; font-size:13px; font-weight:700; color:#0f172a; font-variant-numeric:tabular-nums; white-space:nowrap;">Rp {{ number_format($history->amount, 0, ',', '.') }}</td>
                            <td style="padding:14px 20px; white-space:nowrap;">
                                <span style="font-size:11px; font-weight:700; color:#64748b;">
                                    {{ $history->billing_cycle === 'yearly' ? '📅 Tahunan' : '🗓️ Bulanan' }}
                                </span>
                            </td>
                            <td style="padding:14px 20px; white-space:nowrap;">
                                @if($history->status === 'active')
                                    <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; background:#d1fae5; color:#059669; border:1px solid #6ee7b7;">Lunas</span>
                                @elseif($history->status === 'pending_payment')
                                    <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; background:#fef3c7; color:#d97706; border:1px solid #fde68a;">Menunggu</span>
                                @elseif($history->status === 'cancelled')
                                    <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; background:#fee2e2; color:#dc2626; border:1px solid #fca5a5;">Dibatalkan</span>
                                @else
                                    <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; background:#f1f5f9; color:#64748b;">{{ ucfirst($history->status) }}</span>
                                @endif
                            </td>
                            <td style="padding:14px 20px; text-align:right; white-space:nowrap;">
                                @if($history->status === 'pending_payment')
                                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:12px;">
                                        <form action="{{ route('billing.cancel', $history) }}" method="POST" onsubmit="return confirm('Batalkan tagihan ini?');">
                                            @csrf
                                            <button type="submit" style="font-size:11px; font-weight:700; color:#94a3b8; background:none; border:none; cursor:pointer; text-decoration:underline; text-underline-offset:3px;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">Batalkan</button>
                                        </form>
                                        @if($history->payment_url)
                                        <a href="{{ $history->payment_url }}" target="_blank"
                                           style="display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:8px; font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:#fff; background:#4f46e5; text-decoration:none; box-shadow:0 2px 8px rgba(79,70,229,.3); transition:opacity .2s;"
                                           onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                                            Bayar
                                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                        @endif
                                    </div>
                                @elseif($history->status === 'active')
                                    <span style="font-size:11px; font-weight:800; color:#10b981;">✓ Selesai</span>
                                @else
                                    <span style="color:#e2e8f0;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding:60px 20px; text-align:center;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                                    <div style="width:48px; height:48px; border-radius:14px; background:#f8fafc; border:1.5px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                                        <svg width="22" height="22" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <p style="font-size:11px; font-weight:800; color:#cbd5e1; letter-spacing:.1em; text-transform:uppercase;">Belum ada riwayat transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── Mobile list ── --}}
            <div class="md:hidden">
                @forelse($histories as $history)
                <div class="history-mobile-card">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
                        <div>
                            <p style="font-size:14px; font-weight:800; color:#0f172a;">{{ $history->plan?->name ?? '—' }}</p>
                            <p style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        @if($history->status === 'active')
                            <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; text-transform:uppercase; background:#d1fae5; color:#059669; border:1px solid #6ee7b7; flex-shrink:0;">Lunas</span>
                        @elseif($history->status === 'pending_payment')
                            <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; text-transform:uppercase; background:#fef3c7; color:#d97706; border:1px solid #fde68a; flex-shrink:0;">Menunggu</span>
                        @elseif($history->status === 'cancelled')
                            <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; text-transform:uppercase; background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; flex-shrink:0;">Batal</span>
                        @else
                            <span style="padding:3px 10px; border-radius:99px; font-size:10px; font-weight:800; text-transform:uppercase; background:#f1f5f9; color:#64748b; flex-shrink:0;">{{ ucfirst($history->status) }}</span>
                        @endif
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <p style="font-size:15px; font-weight:800; color:#0f172a;">Rp {{ number_format($history->amount, 0, ',', '.') }}</p>
                            <p style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ $history->billing_cycle === 'yearly' ? '📅 Tahunan' : '🗓️ Bulanan' }}</p>
                        </div>
                        @if($history->status === 'pending_payment')
                        <div style="display:flex; gap:8px; align-items:center;">
                            <form action="{{ route('billing.cancel', $history) }}" method="POST" onsubmit="return confirm('Batalkan tagihan ini?');">
                                @csrf
                                <button type="submit" style="font-size:11px; font-weight:700; color:#94a3b8; background:none; border:none; cursor:pointer; text-decoration:underline;">Batalkan</button>
                            </form>
                            @if($history->payment_url)
                            <a href="{{ $history->payment_url }}" target="_blank"
                               style="padding:7px 14px; border-radius:8px; font-size:11px; font-weight:800; color:#fff; background:#4f46e5; text-decoration:none;">
                                Bayar →
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding:60px 20px; text-align:center;">
                    <p style="font-size:11px; font-weight:800; color:#cbd5e1; letter-spacing:.1em; text-transform:uppercase;">Belum ada riwayat transaksi</p>
                </div>
                @endforelse
            </div>

        </div>{{-- /riwayat --}}

    </div>{{-- /max-w-5xl --}}
</x-app-layout>
