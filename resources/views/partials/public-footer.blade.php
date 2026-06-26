    <footer class="bg-[#0a0915] text-slate-400 pt-16 pb-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center overflow-hidden bg-[#0a0915] border border-white/20">
                            <img src="{{ asset('logo.png') }}" alt="SmartRT Vision" class="w-full h-full object-cover">
                        </div>
                        <span class="text-sm font-bold text-white">{{ ($tenant->name ?? config('app.name', 'SmartRT Vision')) }}</span>
                    </a>
                    <p class="text-xs text-slate-500 mt-4 leading-relaxed">
                        Portal digital pendataan &amp; pelayanan warga RT — mempermudah pengurusan administrasi, transparansi kas, dan komunikasi antar warga.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Layanan Mandiri</p>
                    <ul class="space-y-3 text-xs">
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Cek Status NIK</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Ajukan Surat</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Cek Riwayat Iuran</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Lapor Keluhan Warga</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Cek Status Laporan</a></li>
                        <li><a href="{{ route('home') }}#layanan" class="hover:text-white transition-colors">Lapor Peristiwa</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Informasi</p>
                    <ul class="space-y-3 text-xs">
                        <li><a href="{{ route('home') }}#kas" class="hover:text-white transition-colors">Transparansi Kas RT</a></li>
                        <li><a href="{{ route('home') }}#warta" class="hover:text-white transition-colors">Warta &amp; Pengumuman</a></li>
                        <li><a href="{{ route('home') }}#umkm" class="hover:text-white transition-colors">Pasar Warga (UMKM)</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login Pengurus</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-bold text-white uppercase tracking-wider mb-4">Legal &amp; Privasi</p>
                    <ul class="space-y-3 text-xs">
                        <li><a href="{{ route('privasi') }}" class="hover:text-white transition-colors">Kebijakan Privasi Data Warga</a></li>
                        <li><a href="{{ route('syarat') }}" class="hover:text-white transition-colors">Syarat &amp; Ketentuan Layanan</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-12 pt-6">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} <strong class="text-slate-200 font-semibold">PT. Sekawan Putra Pratama</strong>. Seluruh Hak Cipta Dilindungi.
                </p>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                    Portal ini ditenagai oleh <strong class="text-slate-300 font-semibold">{{ config('app.name', 'SmartRT Vision') }}</strong> — platform sistem informasi terintegrasi yang dikembangkan oleh PT. Sekawan Putra Pratama untuk mendukung digitalisasi pelayanan dan administrasi warga RT.
                </p>
            </div>
        </div>
    </footer>
