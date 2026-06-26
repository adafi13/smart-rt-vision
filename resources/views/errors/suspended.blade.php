<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditangguhkan - SmartRT Vision</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-2 bg-rose-500"></div>
        <div class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-black text-gray-900 mb-2">Akses Ditangguhkan</h1>
        <p class="text-gray-500 text-sm mb-6 leading-relaxed">
            Mohon maaf, akses sistem SmartRT Vision untuk RT Anda saat ini ditangguhkan (Suspended) oleh Super Admin. Ini mungkin terjadi karena masalah penagihan atau pelanggaran kebijakan.
        </p>
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left border border-gray-100">
            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Status Tenant</p>
            <p class="text-sm font-medium text-gray-900">{{ optional(auth()->user()->tenant)->name ?? 'Unknown RT' }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-slate-200">
                Logout & Kembali
            </button>
        </form>
    </div>
</body>
</html>
