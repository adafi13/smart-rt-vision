@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')
@section('message', 'Aktivitas Terlalu Padat')

@section('icon')
<svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
@endsection

@section('description')
Sistem mendeteksi terlalu banyak permintaan dari Anda dalam waktu singkat. Untuk menjaga stabilitas *server* dan mencegah penyalahgunaan, silakan jeda aktivitas Anda dan coba lagi dalam beberapa menit.
@endsection
