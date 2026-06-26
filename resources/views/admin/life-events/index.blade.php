<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Lapor Peristiwa</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelahiran, kematian, pernikahan, pindah datang/keluar</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <select name="status" class="input-field max-w-xs" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['Pending', 'Terverifikasi', 'Ditolak'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </form>

        <div class="space-y-3">
            @forelse($lifeEvents as $le)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2 py-0.5 rounded-full text-[11px] bg-violet-50 text-violet-700">{{ $le->jenis_laporan }}</span>
                        @php $badge = [
                            'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Terverifikasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ][$le->status_verifikasi]; @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $badge }}">{{ $le->status_verifikasi }}</span>
                    </div>
                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $le->nama_subjek }}</p>
                    <p class="text-xs text-gray-500">{{ $le->tanggal_kejadian->format('d/m/Y') }} @if($le->member) &middot; terhubung ke {{ $le->member->nama }} @endif</p>
                    @if($le->keterangan)
                    <p class="text-xs text-gray-500 mt-1">{{ $le->keterangan }}</p>
                    @endif
                    @if($le->bukti_dokumen)
                    <a href="{{ asset('storage/'.$le->bukti_dokumen) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 font-medium mt-2">Lihat dokumen</a>
                    @endif
                </div>
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'verifikasi-{{ $le->id }}')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap">Verifikasi</button>
            </div>

            <x-modal name="verifikasi-{{ $le->id }}" focusable>
                <form action="{{ route('admin.peristiwa.update', $le) }}" method="POST" class="p-6 space-y-3">
                    @csrf @method('PUT')
                    <h2 class="text-base font-bold text-gray-900">Verifikasi Peristiwa — {{ $le->nama_subjek }}</h2>
                    <div>
                        <label class="label">Status Verifikasi</label>
                        <select name="status_verifikasi" class="input-field">
                            @foreach(['Pending', 'Terverifikasi', 'Ditolak'] as $s)
                                <option value="{{ $s }}" @selected($le->status_verifikasi === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <form action="{{ route('admin.peristiwa.destroy', $le) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
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
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-14 text-center text-sm text-gray-400">Belum ada laporan peristiwa.</div>
            @endforelse
        </div>
        @if($lifeEvents->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $lifeEvents->links() }}</div>
        @endif
    </div>
</x-app-layout>
