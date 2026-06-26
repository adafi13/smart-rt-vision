<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base font-semibold text-gray-900">Detail Kartu Keluarga</h1>
                <p class="text-sm text-gray-500 mt-0.5 font-mono">{{ $family->nomor_kk }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('kk.edit', $family) }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50">Edit KK</a>
                <a href="{{ route('kk.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-100">&larr; Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl space-y-4">
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif

        <!-- Info KK -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-900">Informasi Keluarga</h2>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $family->status_verifikasi === 'terverifikasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $family->status_verifikasi === 'terverifikasi' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ ucfirst($family->status_verifikasi) }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Kepala Keluarga</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->nama_kepala_keluarga }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Nomor KK</p>
                    <p class="font-medium text-gray-900 mt-0.5 font-mono">{{ $family->nomor_kk }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-400">Alamat</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->alamat }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">RT / RW</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->rt }} / {{ $family->rw }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Kode Pos</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->kode_pos ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Desa/Kelurahan</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->desa_kelurahan ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Kecamatan</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->kecamatan ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Kabupaten/Kota</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->kabupaten_kota ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Provinsi</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $family->provinsi ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Anggota Keluarga -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between p-5 sm:p-6 pb-0">
                <h2 class="text-sm font-semibold text-gray-900">Anggota Keluarga ({{ $family->members->count() }})</h2>
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'tambah-anggota')" class="btn-primary !text-xs !py-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Anggota
                </button>
            </div>
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100" style="background:#fafafa;">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">NIK</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Nama</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap hidden sm:table-cell">Hubungan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap hidden sm:table-cell">Gender</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($family->members as $m)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm font-mono font-semibold text-indigo-600 whitespace-nowrap">{{ $m->nik }}</td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $m->nama }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $m->hubungan_keluarga }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500 whitespace-nowrap hidden sm:table-cell">{{ $m->jenis_kelamin }}</td>
                            <td class="px-5 py-3 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('cetak_surat', $m->id) }}" target="_blank" class="text-xs font-medium text-gray-500 hover:text-indigo-600 px-2 py-1.5 rounded-lg">Cetak Surat</a>
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-anggota-{{ $m->id }}')" class="text-xs font-medium text-gray-500 hover:text-indigo-600 px-2 py-1.5 rounded-lg">Edit</button>
                                <form action="{{ route('kk.anggota.destroy', [$family, $m]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus anggota ini dari KK?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-medium text-gray-400 hover:text-red-500 px-2 py-1.5 rounded-lg">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        <x-modal name="edit-anggota-{{ $m->id }}" focusable>
                            <form action="{{ route('kk.anggota.update', [$family, $m]) }}" method="POST" class="p-6 space-y-3 max-h-[80vh] overflow-y-auto">
                                @csrf @method('PUT')
                                <h2 class="text-base font-bold text-gray-900 mb-2">Edit Anggota — {{ $m->nama }}</h2>
                                @include('kk.partials.member-fields', ['member' => $m])
                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                                    <button type="submit" class="btn-primary">Simpan</button>
                                </div>
                            </form>
                        </x-modal>
                        @empty
                        <tr><td colspan="5" class="py-14 text-center text-sm text-gray-400">Belum ada anggota keluarga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal name="tambah-anggota" focusable>
        <form action="{{ route('kk.anggota.store', $family) }}" method="POST" class="p-6 space-y-3 max-h-[80vh] overflow-y-auto">
            @csrf
            <h2 class="text-base font-bold text-gray-900 mb-2">Tambah Anggota Keluarga</h2>
            @include('kk.partials.member-fields')
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
