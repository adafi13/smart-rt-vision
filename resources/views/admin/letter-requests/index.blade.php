<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Pengajuan Surat</h1>
            <p class="text-sm text-gray-500 mt-0.5">Permohonan surat dari warga via portal publik</p>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <form method="GET" class="flex gap-2">
            <select name="status" class="input-field max-w-xs" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['Pending', 'Disetujui', 'Ditolak'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </form>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-x-auto w-full">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100" style="background:#fafafa;">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Pemohon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Jenis Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap hidden md:table-cell">Keperluan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letterRequests as $lr)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 align-top">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <p class="text-sm font-medium text-gray-900">{{ $lr->member->nama }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $lr->member->nik }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $lr->jenis_surat }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell max-w-xs">{{ Str::limit($lr->keperluan, 60) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php $badge = [
                                'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ][$lr->status]; @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badge }}">{{ $lr->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                            @if($lr->status === 'Disetujui')
                            <a href="{{ route('cetak_permohonan', $lr->id) }}" target="_blank" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 px-2 py-1.5 rounded-lg inline-block">Cetak</a>
                            @endif
                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'kelola-surat-{{ $lr->id }}')" class="text-xs font-medium text-gray-500 hover:text-indigo-600 px-2 py-1.5 rounded-lg">Kelola</button>
                        </td>
                    </tr>

                    <x-modal name="kelola-surat-{{ $lr->id }}" focusable>
                        <form action="{{ route('admin.pengajuan.update', $lr) }}" method="POST" class="p-6 space-y-3">
                            @csrf @method('PUT')
                            <h2 class="text-base font-bold text-gray-900">Kelola Permohonan — {{ $lr->member->nama }}</h2>
                            <p class="text-sm text-gray-500">{{ $lr->jenis_surat }}: {{ $lr->keperluan }}</p>
                            <div>
                                <label class="label">Status</label>
                                <select name="status" class="input-field">
                                    @foreach(['Pending', 'Disetujui', 'Ditolak'] as $s)
                                        <option value="{{ $s }}" @selected($lr->status === $s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label">Catatan Admin (opsional)</label>
                                <textarea name="catatan_admin" rows="2" class="input-field">{{ $lr->catatan_admin }}</textarea>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <form action="{{ route('admin.pengajuan.destroy', $lr) }}" method="POST" onsubmit="return confirm('Hapus permohonan ini?')">
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
                    <tr><td colspan="5" class="py-14 text-center text-sm text-gray-400">Belum ada pengajuan surat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($letterRequests->hasPages())
            <div class="px-4 py-3 border border-gray-100 rounded-2xl bg-white shadow-sm">{{ $letterRequests->links() }}</div>
        @endif
    </div>
</x-app-layout>
