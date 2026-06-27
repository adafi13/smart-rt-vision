<x-guest-layout>
    <div style="max-width:800px;margin:0 auto;padding:40px 20px;background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.05);">
        
        <a href="javascript:history.back()" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:32px;transition:color 0.2s;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div style="text-align:center;margin-bottom:40px;">
            <h1 style="font-size:32px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin:0 0 12px;line-height:1.2;">
                Syarat & Ketentuan Layanan
            </h1>
            <p style="font-size:14px;color:#64748b;font-weight:500;margin:0;">
                Terakhir diperbarui: {{ date('d F Y') }}
            </p>
        </div>

        <div style="font-size:15px;color:#334155;line-height:1.8;">
            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">1. Penerimaan Syarat</h3>
            <p style="margin-bottom:16px;">Dengan mengakses dan menggunakan sistem SmartRT Vision, Anda menyetujui untuk terikat dengan Syarat dan Ketentuan ini. Jika Anda tidak setuju dengan bagian apa pun dari syarat ini, Anda dilarang menggunakan sistem ini.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">2. Penggunaan Sistem</h3>
            <p style="margin-bottom:16px;">Anda setuju untuk menggunakan sistem ini hanya untuk tujuan pengelolaan data Rukun Tetangga (RT) yang sah, dan dengan cara yang tidak melanggar hak-hak, atau membatasi maupun menghalangi penggunaan serta penikmatan sistem ini oleh pihak ketiga mana pun.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">3. Privasi & Data Warga</h3>
            <p style="margin-bottom:16px;">Sebagai pengurus RT, Anda bertanggung jawab penuh atas kerahasiaan dan keamanan data warga yang Anda masukkan ke dalam sistem. SmartRT Vision menyediakan infrastruktur dengan enkripsi setara perbankan, namun tanggung jawab pengelolaan akses admin berada di tangan Anda.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">4. Masa Percobaan & Berlangganan</h3>
            <p style="margin-bottom:16px;">Akun baru akan mendapatkan masa percobaan gratis selama 14 hari. Setelah masa percobaan berakhir, Anda diwajibkan untuk meningkatkan paket ke layanan berlangganan premium untuk terus menggunakan fitur lengkap SmartRT Vision.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">5. Batasan Tanggung Jawab</h3>
            <p style="margin-bottom:16px;">Sistem disediakan "sebagaimana adanya". SmartRT Vision tidak bertanggung jawab atas kerugian langsung maupun tidak langsung yang timbul akibat kesalahan penggunaan sistem oleh pengguna, maupun kendala teknis yang berada di luar kendali kami.</p>
        </div>

    </div>

    <x-slot name="sidebar">
        <div style="display:flex;flex-direction:column;gap:28px;">
            <div class="fade-in-up">
                <h2 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.15;margin:0 0 12px;">
                    Komitmen<br><span style="color:#60a5fa;">Kepercayaan.</span>
                </h2>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;line-height:1.7;margin:0;">
                    Kami membangun lingkungan perangkat lunak yang aman, transparan, dan saling menguntungkan.
                </p>
            </div>
        </div>
    </x-slot>
</x-guest-layout>
