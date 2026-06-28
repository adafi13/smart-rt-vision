@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('message', 'Akses Tidak Diizinkan')

@section('icon')
<svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
@endsection

@section('description')
Anda tidak memiliki izin (hak akses) untuk melihat direktori atau halaman ini menggunakan kredensial yang Anda berikan. Jika Anda merasa ini sebuah kesalahan, hubungi Administrator sistem Anda.
@endsection
