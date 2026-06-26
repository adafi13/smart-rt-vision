<x-super-admin-layout title="Manajemen Paket">
    <div class="max-w-6xl space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Manajemen Paket (Plans)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Atur harga berlangganan dan limit kuota aplikasi</p>
            </div>
            <a href="{{ route('super-admin.plans.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Paket Baru
            </a>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($plans as $plan)
            <div class="bg-white rounded-2xl border p-5 relative {{ $plan->is_popular ? 'border-indigo-400 shadow-lg shadow-indigo-100' : 'border-gray-100 shadow-sm' }} {{ !$plan->is_active ? 'opacity-75' : '' }}">
                @if($plan->is_popular)
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full text-[10px] font-bold text-white bg-gradient-to-r from-indigo-500 to-purple-500">POPULER</span>
                @endif
                
                @if(!$plan->is_active)
                <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500">NONAKTIF</span>
                @endif

                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h2>
                    <span class="text-xs font-mono text-gray-400">#{{ $plan->sort_order }}</span>
                </div>
                
                <p class="text-xs text-gray-500 mb-4">{{ $plan->description ?? 'Tidak ada deskripsi.' }}</p>
                
                <div class="mb-4">
                    <p class="text-2xl font-black text-gray-900">Rp{{ number_format($plan->price_monthly, 0, ',', '.') }}<span class="text-sm font-normal text-gray-400">/bln</span></p>
                    <p class="text-xs font-semibold text-emerald-600 mt-1">Rp{{ number_format($plan->price_yearly, 0, ',', '.') }} /tahun</p>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between items-center text-sm border-t border-gray-50 pt-2">
                        <span class="text-gray-500">Maks. KK</span>
                        <span class="font-semibold text-gray-900">{{ $plan->isUnlimitedKk() ? 'Tanpa Batas' : number_format($plan->max_kk) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-gray-50 pt-2">
                        <span class="text-gray-500">Ekstraksi AI/bln</span>
                        <span class="font-semibold text-gray-900">{{ $plan->isUnlimitedAi() ? 'Tanpa Batas' : number_format($plan->max_ai_extractions_per_month) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-100 mt-auto">
                    <a href="{{ route('super-admin.plans.edit', $plan) }}" class="flex-1 text-center py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-sm font-semibold rounded-xl transition-colors">Edit</a>
                    <form action="{{ route('super-admin.plans.destroy', $plan) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus paket ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold rounded-xl transition-colors">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm">Belum ada paket langganan yang dibuat.</p>
                <a href="{{ route('super-admin.plans.create') }}" class="text-indigo-600 text-sm font-semibold hover:underline mt-2 inline-block">Buat Paket Pertama</a>
            </div>
            @endforelse
        </div>
    </div>
</x-super-admin-layout>
