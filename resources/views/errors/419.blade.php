@extends('errors.layout')

@section('title', 'Halaman Kedaluwarsa')
@section('code', '419')
@section('message', 'Sesi Telah Berakhir')

@section('icon')
<svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('description')
Sesi login Anda telah berakhir atau halaman sudah terlalu lama didiamkan. Untuk alasan keamanan, silakan muat ulang (refresh) halaman ini atau login kembali untuk melanjutkan aktivitas Anda.
@endsection
