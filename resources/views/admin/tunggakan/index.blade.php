<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Rekap Tunggakan Iuran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Sistem pelacakan tunggakan (piutang) otomatis</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6" x-data="{ tab: 'bulanan' }">
        
        <!-- TOP SECTION: Info, Settings, and Filter -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            
            <!-- Banner Info & Settings -->
            <div class="lg:col-span-2 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-lg shadow-indigo-200/50">
                <!-- Decorative Circle -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 right-20 w-32 h-32 bg-indigo-400/20 rounded-full blur-xl"></div>
                
                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start gap-5 h-full">
                    <div class="flex-1 flex flex-col justify-between h-full">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-500/30 border border-indigo-400/30 text-indigo-100 text-[10px] font-bold uppercase tracking-wider mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Info Sistem
                            </div>
                            <h3 class="text-xl sm:text-2xl font-black mb-2 leading-tight">Sistem Tunggakan Otomatis</h3>
                            <p class="text-indigo-100 text-xs sm:text-sm max-w-xl leading-relaxed">
                                Mendeteksi piutang secara <strong>otomatis (Time-based)</strong>. Warga yang belum mencatat iuran bulanan pada menu Kas Masuk akan langsung dicatat menunggak sesuai dengan tarif dasar RT.
                            </p>
                        </div>
                        
                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <div class="px-4 py-2 bg-indigo-900/40 rounded-xl text-sm font-bold border border-indigo-500/30 backdrop-blur-sm">
                                Tarif Dasar: <span class="text-white ml-1">Rp {{ number_format($nominalIuran, 0, ',', '.') }} <span class="text-indigo-200 text-xs font-medium font-normal">/ bln</span></span>
                            </div>
                            <button x-on:click="$dispatch('open-modal', 'setting-iuran')" class="px-5 py-2 bg-white text-indigo-700 rounded-xl text-sm font-bold hover:bg-indigo-50 transition-all shadow-sm active:scale-95 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ubah Tarif
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col justify-center">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Pilih Periode Laporan</h3>
                <form action="{{ route('admin.tunggakan.index') }}" method="GET" class="flex flex-col gap-3">
                    <div class="flex gap-2 w-full">
                        <select name="month" class="w-full border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm rounded-xl text-gray-700 bg-gray-50 py-2.5 font-semibold">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                        <select name="year" class="w-2/3 border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm rounded-xl text-gray-700 bg-gray-50 py-2.5 font-semibold">
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors shadow-sm font-bold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Tampilkan Data
                    </button>
                </form>
            </div>
            
        </div>

        <!-- TABS (Centered) -->
        <div class="flex justify-center sm:justify-start">
            <div class="flex gap-1.5 p-1.5 bg-gray-100 border border-gray-200 rounded-2xl w-full sm:w-fit">
                <button @click="tab = 'bulanan'" :class="tab === 'bulanan' ? 'bg-white shadow-sm text-indigo-700 font-bold border border-gray-200/50' : 'text-gray-500 hover:text-gray-700 font-semibold'" class="flex-1 sm:flex-none px-6 py-2.5 text-sm rounded-xl transition-all text-center">Bulan Ini</button>
                <button @click="tab = 'tahunan'" :class="tab === 'tahunan' ? 'bg-white shadow-sm text-indigo-700 font-bold border border-gray-200/50' : 'text-gray-500 hover:text-gray-700 font-semibold'" class="flex-1 sm:flex-none px-6 py-2.5 text-sm rounded-xl transition-all text-center">Akumulasi {{ $year }}</button>
            </div>
        </div>

        <!-- TAB: BULANAN -->
        <div x-show="tab === 'bulanan'" class="space-y-4" x-cloak>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Status Pembayaran Iuran</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Periode: <span class="font-semibold text-gray-700">{{ $selectedDate->translatedFormat('F Y') }}</span></p>
                    </div>
                    <div class="sm:text-right p-3 sm:p-0 bg-white sm:bg-transparent rounded-xl sm:rounded-none border sm:border-0 border-rose-100 flex justify-between items-center sm:block">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Belum Bayar</p>
                        <p class="text-lg font-black text-rose-600">{{ $bulanan->where('status', 'MENUNGGAK')->count() }} <span class="text-sm font-semibold text-gray-400">KK</span></p>
                    </div>
                </div>
                
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">Kepala Keluarga</th>
                                <th class="px-6 py-4">Status Bulan Ini</th>
                                <th class="px-6 py-4 text-right">Nominal Kas</th>
                                <th class="px-6 py-4 text-center">Aksi / Tagih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-gray-700">
                            @foreach($bulanan as $row)
                            <tr class="hover:bg-gray-50/50 transition-colors {{ $row->status === 'MENUNGGAK' ? 'bg-rose-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full {{ $row->status === 'LUNAS' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} border flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ substr($row->family->nama_kepala_keluarga, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 block">{{ $row->family->nama_kepala_keluarga }}</span>
                                            <span class="text-xs text-gray-500">Blok/No: {{ $row->family->alamat ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($row->status === 'LUNAS')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold uppercase tracking-wide">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                            Menunggak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($row->status === 'LUNAS')
                                        <span class="text-emerald-700 font-bold text-sm bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">Rp {{ number_format($row->amount_paid, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-rose-600 font-bold text-sm bg-rose-50 px-2 py-1 rounded-md border border-rose-100">- Rp {{ number_format($row->arrears_amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($row->status === 'MENUNGGAK')
                                        @php
                                            $waText = rawurlencode(
                                                "Halo Bapak/Ibu *{$row->family->nama_kepala_keluarga}*,\n\n" .
                                                "Kami dari Pengurus RT ingin menginformasikan bahwa data tagihan Iuran Wajib RT untuk keluarga Bapak/Ibu pada bulan ini belum tercatat di sistem kami.\n\n" .
                                                "*Rincian Tagihan:*\n" .
                                                "• Periode: *{$selectedDate->translatedFormat('F Y')}*\n" .
                                                "• Nominal: *Rp " . number_format($row->arrears_amount, 0, ',', '.') . "*\n\n" .
                                                "Agar operasional lingkungan dan kas RT kita dapat berjalan dengan lancar, mohon kesediaannya untuk dapat melakukan pelunasan tagihan tersebut.\n" .
                                                "_(Bapak/Ibu dapat mengabaikan pesan ini apabila telah melakukan pembayaran sebelumnya)_\n\n" .
                                                "Terima kasih banyak atas perhatian dan kerjasamanya!"
                                            );
                                        @endphp
                                        <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm" title="Kirim Tagihan WA">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.585 2.113.945 3.151.945 3.18 0 5.768-2.587 5.768-5.766 0-3.181-2.587-5.767-5.768-5.767zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.393.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825 0 6.938 3.112 6.938 6.937 0 3.824-3.113 6.938-6.938 6.938z"/></svg>
                                            Tagih Via WA
                                        </a>
                                    @else
                                        <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards for Bulanan -->
            <div class="md:hidden space-y-3">
                @foreach($bulanan as $row)
                <div class="bg-white rounded-2xl shadow-sm border {{ $row->status === 'MENUNGGAK' ? 'border-rose-200' : 'border-gray-200' }} overflow-hidden relative">
                    <!-- Status Indicator Bar -->
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $row->status === 'LUNAS' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                    
                    <div class="p-4 pl-5 flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $row->family->nama_kepala_keluarga }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Blok: {{ $row->family->alamat ?? '-' }}</p>
                            <div class="mt-2">
                                @if($row->status === 'LUNAS')
                                    <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold uppercase">Lunas</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded text-[10px] font-bold uppercase">Menunggak</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if($row->status === 'LUNAS')
                                <span class="block text-sm font-bold text-emerald-600">Rp {{ number_format($row->amount_paid, 0, ',', '.') }}</span>
                            @else
                                <span class="block text-sm font-bold text-rose-600">-Rp {{ number_format($row->arrears_amount, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($row->status === 'MENUNGGAK')
                    <div class="px-4 py-3 bg-rose-50/50 border-t border-rose-100 pl-5">
                        @php
                            $waText = rawurlencode(
                                "Halo Bapak/Ibu *{$row->family->nama_kepala_keluarga}*,\n\n" .
                                "Kami dari Pengurus RT ingin menginformasikan bahwa data tagihan Iuran Wajib RT untuk keluarga Bapak/Ibu pada bulan ini belum tercatat di sistem kami.\n\n" .
                                "*Rincian Tagihan:*\n" .
                                "• Periode: *{$selectedDate->translatedFormat('F Y')}*\n" .
                                "• Nominal: *Rp " . number_format($row->arrears_amount, 0, ',', '.') . "*\n\n" .
                                "Agar operasional lingkungan dan kas RT kita dapat berjalan dengan lancar, mohon kesediaannya untuk dapat melakukan pelunasan tagihan tersebut.\n" .
                                "_(Bapak/Ibu dapat mengabaikan pesan ini apabila telah melakukan pembayaran sebelumnya)_\n\n" .
                                "Terima kasih banyak atas perhatian dan kerjasamanya!"
                            );
                        @endphp
                        <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.585 2.113.945 3.151.945 3.18 0 5.768-2.587 5.768-5.766 0-3.181-2.587-5.767-5.768-5.767zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.393.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825 0 6.938 3.112 6.938 6.937 0 3.824-3.113 6.938-6.938 6.938z"/></svg>
                            Kirim Tagihan WA
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- TAB: TAHUNAN -->
        <div x-show="tab === 'tahunan'" class="space-y-4" x-cloak>
            <div class="bg-white rounded-2xl border border-rose-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-rose-100 bg-rose-50/50 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                    <div>
                        <h2 class="text-sm font-bold text-rose-900">Akumulasi Tunggakan {{ $year }}</h2>
                        <p class="text-[10px] sm:text-xs text-rose-600/70 mt-0.5">Menampilkan warga yang memiliki hutang > 0 bulan tahun ini.</p>
                    </div>
                    <div class="sm:text-right p-3 sm:p-0 bg-white sm:bg-transparent rounded-xl sm:rounded-none border sm:border-0 border-rose-100 flex justify-between items-center sm:block">
                        <p class="text-[10px] text-rose-500 font-bold uppercase tracking-wider">Total Piutang Kas</p>
                        <p class="text-lg font-black text-rose-600">Rp {{ number_format($tahunan->sum('total_arrears'), 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <!-- Desktop Table for Tahunan -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white border-b border-rose-100 text-xs text-rose-700/70 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">Keluarga Penunggak</th>
                                <th class="px-6 py-4 text-center">Bulan Tertunggak</th>
                                <th class="px-6 py-4 text-right">Total Akumulasi</th>
                                <th class="px-6 py-4 text-center">Aksi / Tagih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-50 text-gray-700 bg-white">
                            @forelse($tahunan as $row)
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ substr($row->family->nama_kepala_keluarga, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 block">{{ $row->family->nama_kepala_keluarga }}</span>
                                            <span class="text-[10px] text-gray-500 block mt-0.5 max-w-xs truncate" title="{{ implode(', ', $row->unpaid_months_list) }}">
                                                Belum bayar: <span class="font-semibold text-rose-600">{{ implode(', ', $row->unpaid_months_list) }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase">
                                        {{ $row->months_owed }} Bulan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-rose-600 font-bold text-sm bg-rose-50 px-2 py-1 rounded-md border border-rose-100">- Rp {{ number_format($row->total_arrears, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $waText = rawurlencode(
                                            "Halo Bapak/Ibu *{$row->family->nama_kepala_keluarga}*,\n\n" .
                                            "Kami dari Pengurus RT ingin menginformasikan bahwa terdapat akumulasi tagihan Iuran Wajib RT untuk keluarga Bapak/Ibu di tahun *{$year}* yang belum tercatat di sistem kami.\n\n" .
                                            "*Rincian Tagihan:*\n" .
                                            "• Jumlah Tertunggak: *{$row->months_owed} Bulan*\n" .
                                            "• Detail Bulan: _" . implode(', ', $row->unpaid_months_list) . "_\n" .
                                            "• Total Nominal: *Rp " . number_format($row->total_arrears, 0, ',', '.') . "*\n\n" .
                                            "Agar operasional lingkungan dan kas RT kita dapat berjalan dengan lancar, mohon kesediaannya untuk dapat melakukan pelunasan tagihan tersebut.\n" .
                                            "_(Bapak/Ibu dapat mengabaikan pesan ini apabila telah melakukan pembayaran sebelumnya)_\n\n" .
                                            "Terima kasih banyak atas perhatian dan kerjasamanya!"
                                        );
                                    @endphp
                                    <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.585 2.113.945 3.151.945 3.18 0 5.768-2.587 5.768-5.766 0-3.181-2.587-5.767-5.768-5.767zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.393.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825 0 6.938 3.112 6.938 6.937 0 3.824-3.113 6.938-6.938 6.938z"/></svg>
                                        Kirim Surat Tagihan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center bg-white">
                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-50 text-emerald-500 border border-emerald-100 mb-4">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 mb-1">Luar Biasa!</p>
                                    <p class="text-xs text-gray-500">Tidak ada keluarga yang menunggak iuran di tahun ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards for Tahunan -->
            <div class="md:hidden space-y-3">
                @forelse($tahunan as $row)
                <div class="bg-white rounded-2xl shadow-sm border border-rose-200 overflow-hidden relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500"></div>
                    
                    <div class="p-4 pl-5 flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $row->family->nama_kepala_keluarga }}</h3>
                            <span class="inline-flex px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[10px] font-bold mt-1">Nunggak {{ $row->months_owed }} Bulan</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-sm font-bold text-rose-600">-Rp {{ number_format($row->total_arrears, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50/50 border-t border-gray-100 pl-5">
                        <p class="text-[10px] text-gray-500 mb-3">
                            <span class="font-bold text-gray-700 block mb-0.5">Bulan tertunggak:</span>
                            <span class="text-rose-600 font-medium">{{ implode(', ', $row->unpaid_months_list) }}</span>
                        </p>
                        @php
                            $waText = rawurlencode(
                                "Halo Bapak/Ibu *{$row->family->nama_kepala_keluarga}*,\n\n" .
                                "Kami dari Pengurus RT ingin menginformasikan bahwa terdapat akumulasi tagihan Iuran Wajib RT untuk keluarga Bapak/Ibu di tahun *{$year}* yang belum tercatat di sistem kami.\n\n" .
                                "*Rincian Tagihan:*\n" .
                                "• Jumlah Tertunggak: *{$row->months_owed} Bulan*\n" .
                                "• Detail Bulan: _" . implode(', ', $row->unpaid_months_list) . "_\n" .
                                "• Total Nominal: *Rp " . number_format($row->total_arrears, 0, ',', '.') . "*\n\n" .
                                "Agar operasional lingkungan dan kas RT kita dapat berjalan dengan lancar, mohon kesediaannya untuk dapat melakukan pelunasan tagihan tersebut.\n" .
                                "_(Bapak/Ibu dapat mengabaikan pesan ini apabila telah melakukan pembayaran sebelumnya)_\n\n" .
                                "Terima kasih banyak atas perhatian dan kerjasamanya!"
                            );
                        @endphp
                        <a href="https://wa.me/?text={{ $waText }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.585 2.113.945 3.151.945 3.18 0 5.768-2.587 5.768-5.766 0-3.181-2.587-5.767-5.768-5.767zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.393.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825 0 6.938 3.112 6.938 6.937 0 3.824-3.113 6.938-6.938 6.938z"/></svg>
                            Kirim Tagihan via WA
                        </a>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-900 mb-1">Luar Biasa!</p>
                    <p class="text-[10px] text-gray-500">Tidak ada keluarga yang menunggak iuran.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- MODAL SETTING IURAN -->
    <x-modal name="setting-iuran" focusable maxWidth="md">
        <form action="{{ route('admin.tunggakan.update-setting') }}" method="POST" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Pengaturan Tunggakan</h2>
                    <p class="text-xs text-gray-500">Sesuaikan nominal iuran wajib bulanan</p>
                </div>
            </div>

            <div class="space-y-4">
                <div x-data="{ 
                        raw: '{{ $nominalIuran }}',
                        formatted: '{{ number_format($nominalIuran, 0, ',', '.') }}',
                        formatValue(val) {
                            let num = val.toString().replace(/[^0-9]/g, '');
                            this.raw = num;
                            this.formatted = num ? parseInt(num, 10).toLocaleString('id-ID') : '';
                        }
                    }">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nominal Iuran Wajib per Bulan (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-medium">Rp</span>
                        </div>
                        <input type="hidden" name="nominal_iuran_bulanan" :value="raw">
                        <input type="text" x-model="formatted" @input="formatValue($event.target.value)" required class="w-full pl-9 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-semibold text-gray-900">
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2 leading-relaxed">
                        Perubahan ini akan otomatis mengubah seluruh perhitungan piutang warga di sistem tunggakan berdasarkan tarif yang baru.
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">Simpan</button>
            </div>
        </form>
    </x-modal>

</x-app-layout>
