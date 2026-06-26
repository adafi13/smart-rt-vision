<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kk.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-gray-900">Import Data Warga (Excel)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Cara cepat memasukkan banyak data warga sekaligus</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-lg space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-blue-50 border border-blue-100 mb-5">
                <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-700">Punya data warga lama di Excel? Unduh template, isi sesuai kolom, lalu unggah di sini. KK akan otomatis dikelompokkan berdasarkan Nomor KK yang sama.</p>
            </div>

            <a href="{{ route('kk.import.template') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors mb-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Format Excel
            </a>

            <form action="{{ route('kk.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="label">Unggah file yang sudah diisi</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="input-field mb-4">
                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Import Sekarang
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('kk.upload') }}" class="text-xs text-gray-400 hover:text-indigo-600">atau upload foto KK satu-per-satu dengan AI &rarr;</a>
        </div>
    </div>
</x-app-layout>
