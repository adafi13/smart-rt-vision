<x-mail::message>
# Pengumuman Baru dari RW

Halo Bapak/Ibu Pengurus RT,

Ada informasi dan pengumuman baru dari pengurus RW yang perlu Anda ketahui:

<x-mail::panel>
**{{ $broadcast->title }}**

{{ $broadcast->content }}
</x-mail::panel>

Silakan periksa kembali di Dashboard SmartRT Vision untuk melihat pengumuman ini secara langsung atau jika ada pembaruan lebih lanjut.

<x-mail::button :url="route('login')">
Buka Dashboard SmartRT Vision
</x-mail::button>

Terima kasih atas perhatian dan kerja samanya,<br>
**Pengurus {{ $broadcast->rw->name }}**
</x-mail::message>
