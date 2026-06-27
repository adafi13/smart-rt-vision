<x-guest-layout>
    <div style="max-width:800px;margin:0 auto;padding:40px 20px;background:#fff;border-radius:16px;box-shadow:0 10px 25px rgba(0,0,0,0.05);">
        
        <a href="javascript:history.back()" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:32px;transition:color 0.2s;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div style="text-align:center;margin-bottom:40px;">
            <h1 style="font-size:32px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin:0 0 12px;line-height:1.2;">
                Kebijakan Privasi
            </h1>
            <p style="font-size:14px;color:#64748b;font-weight:500;margin:0;">
                Terakhir diperbarui: {{ date('d F Y') }}
            </p>
        </div>

        <div style="font-size:15px;color:#334155;line-height:1.8;">
            <p style="margin-bottom:24px;font-size:16px;">Privasi Anda sangat penting bagi kami. Kebijakan Privasi ini menjelaskan bagaimana SmartRT Vision mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">1. Informasi yang Kami Kumpulkan</h3>
            <p style="margin-bottom:16px;">Kami mengumpulkan informasi yang Anda berikan langsung kepada kami, seperti saat Anda membuat akun, memasukkan data Kartu Keluarga (KK), memperbarui profil warga, atau menghubungi dukungan pelanggan kami. Ini termasuk nama, alamat email, nomor telepon, dan data demografi RT Anda.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">2. Penggunaan Informasi</h3>
            <p style="margin-bottom:16px;">Informasi yang dikumpulkan digunakan semata-mata untuk mengoperasikan, memelihara, dan menyediakan fitur-fitur dari layanan SmartRT Vision. Kami tidak pernah menjual, menyewakan, atau memperdagangkan informasi identitas pribadi pengguna kepada pihak ketiga.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">3. Keamanan Data</h3>
            <p style="margin-bottom:16px;">Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang ketat (termasuk enkripsi AES-256) untuk melindungi data Anda dari akses, pengungkapan, perubahan, atau penghancuran yang tidak sah.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">4. Penggunaan Cookie</h3>
            <p style="margin-bottom:16px;">Kami menggunakan "cookies" untuk menjaga sesi Anda tetap aman (seperti fitur "Ingat Saya") dan untuk memahami bagaimana Anda menggunakan layanan kami, sehingga kami dapat meningkatkan pengalaman Anda.</p>

            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin:32px 0 12px;">5. Perubahan Kebijakan</h3>
            <p style="margin-bottom:16px;">Kami berhak memperbarui Kebijakan Privasi ini kapan saja. Kami akan memberi tahu Anda tentang perubahan apa pun melalui email atau dengan memposting kebijakan privasi baru di halaman ini.</p>
        </div>

    </div>

    <x-slot name="sidebar">
        <div style="display:flex;flex-direction:column;gap:28px;">
            <div class="fade-in-up">
                <h2 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.15;margin:0 0 12px;">
                    Privasi Anda,<br><span style="color:#60a5fa;">Prioritas Kami.</span>
                </h2>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);font-weight:500;line-height:1.7;margin:0;">
                    Kami tidak akan pernah menjual atau menyalahgunakan data identitas warga RT Anda. Titik.
                </p>
            </div>
        </div>
    </x-slot>
</x-guest-layout>
