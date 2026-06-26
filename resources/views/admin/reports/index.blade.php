<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Laporan Keluhan Warga</h1>
            <p class="text-sm text-gray-500 mt-0.5">Keluhan kebersihan, keamanan, dan fasilitas dari warga</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <select name="status" class="input-field max-w-xs" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['Menunggu', 'Proses', 'Selesai'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </form>

        <div class="space-y-3">
            @forelse($reports as $r)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-gray-900">{{ $r->member->nama }}</p>
                            <span class="px-2 py-0.5 rounded-full text-[11px] bg-gray-100 text-gray-600">{{ $r->kategori }}</span>
                            @php $badge = [
                                'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Proses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            ][$r->status]; @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $badge }}">{{ $r->status }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1.5">{{ $r->laporan }}</p>
                        @if($r->foto_bukti)
                        <a href="{{ asset('storage/'.$r->foto_bukti) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 font-medium mt-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Lihat foto bukti
                        </a>
                        @endif
                        @if($r->tanggapan_rt)
                        <p class="text-xs text-gray-500 mt-2 bg-gray-50 rounded-lg p-2"><strong>Tanggapan RT:</strong> {{ $r->tanggapan_rt }}</p>
                        @endif
                    </div>
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tanggapi-{{ $r->id }}')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap">Tanggapi</button>
                </div>
            </div>

            <x-modal name="tanggapi-{{ $r->id }}" focusable>
                <form action="{{ route('admin.laporan.update', $r) }}" method="POST" class="p-6 space-y-3">
                    @csrf @method('PUT')
                    <h2 class="text-base font-bold text-gray-900">Tanggapi Laporan — {{ $r->member->nama }}</h2>
                    <p class="text-sm text-gray-500">{{ $r->laporan }}</p>
                    <div>
                        <label class="label">Status</label>
                        <select name="status" class="input-field">
                            @foreach(['Menunggu', 'Proses', 'Selesai'] as $s)
                                <option value="{{ $s }}" @selected($r->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Tanggapan RT</label>
                        <textarea name="tanggapan_rt" rows="3" class="input-field">{{ $r->tanggapan_rt }}</textarea>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <form action="{{ route('admin.laporan.destroy', $r) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-600">Hapus</button>
                        </form>
                        <div class="flex gap-2">
                            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                            <button type="submit" class="btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </x-modal>
            @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-14 text-center text-sm text-gray-400">Belum ada laporan warga.</div>
            @endforelse
        </div>
        @if($reports->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $reports->links() }}</div>
        @endif
    </div>
</x-app-layout>
