<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kalender Agenda & Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Daftar Kegiatan RT</h3>
                            <p class="text-sm text-gray-500">Kelola jadwal rapat, kerja bakti, dan posyandu.</p>
                        </div>
                        <button @click="$dispatch('open-modal', 'add-agenda')" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Agenda
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-xl">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="p-4 font-semibold rounded-tl-xl">Tanggal & Waktu</th>
                                    <th class="p-4 font-semibold">Kegiatan</th>
                                    <th class="p-4 font-semibold">Jenis</th>
                                    <th class="p-4 font-semibold">Lokasi</th>
                                    <th class="p-4 font-semibold text-right rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($agendas as $agenda)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            <div class="font-semibold text-gray-900">{{ $agenda->start_time->format('d M Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $agenda->start_time->format('H:i') }} {{ $agenda->end_time ? '- ' . $agenda->end_time->format('H:i') : '' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-medium text-gray-900">{{ $agenda->title }}</div>
                                            @if($agenda->description)
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($agenda->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($agenda->type === 'rapat')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700">Rapat</span>
                                            @elseif($agenda->type === 'kerjabakti')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700">Kerja Bakti</span>
                                            @elseif($agenda->type === 'posyandu')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-pink-100 text-pink-700">Posyandu</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700">Umum</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-sm text-gray-600">{{ $agenda->location ?? '-' }}</td>
                                        <td class="p-4 text-right flex items-center justify-end gap-2">
                                            <button @click="$dispatch('open-modal', 'edit-agenda-{{ $agenda->id }}')" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">Edit</button>
                                            <button @click="$dispatch('open-modal', 'delete-agenda-{{ $agenda->id }}')" class="text-rose-600 hover:text-rose-900 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-agenda-{{ $agenda->id }}" :show="false" maxWidth="md">
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-gray-900 mb-4">Edit Agenda</h2>
                                            <form action="{{ route('admin.agendas.update', $agenda) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label class="label">Judul Kegiatan</label>
                                                    <input type="text" name="title" value="{{ $agenda->title }}" class="input-field" required>
                                                </div>
                                                <div>
                                                    <label class="label">Jenis Kegiatan</label>
                                                    <select name="type" class="input-field" required>
                                                        <option value="umum" {{ $agenda->type == 'umum' ? 'selected' : '' }}>Umum</option>
                                                        <option value="rapat" {{ $agenda->type == 'rapat' ? 'selected' : '' }}>Rapat Warga/Pengurus</option>
                                                        <option value="kerjabakti" {{ $agenda->type == 'kerjabakti' ? 'selected' : '' }}>Kerja Bakti</option>
                                                        <option value="posyandu" {{ $agenda->type == 'posyandu' ? 'selected' : '' }}>Posyandu</option>
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="label">Waktu Mulai</label>
                                                        <input type="datetime-local" name="start_time" value="{{ $agenda->start_time->format('Y-m-d\TH:i') }}" class="input-field" required>
                                                    </div>
                                                    <div>
                                                        <label class="label">Waktu Selesai (Opsional)</label>
                                                        <input type="datetime-local" name="end_time" value="{{ $agenda->end_time ? $agenda->end_time->format('Y-m-d\TH:i') : '' }}" class="input-field">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="label">Lokasi</label>
                                                    <input type="text" name="location" value="{{ $agenda->location }}" class="input-field">
                                                </div>
                                                <div>
                                                    <label class="label">Deskripsi Tambahan</label>
                                                    <textarea name="description" class="input-field" rows="3">{{ $agenda->description }}</textarea>
                                                </div>
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                                    <button type="submit" class="btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    <!-- Delete Modal -->
                                    <x-modal name="delete-agenda-{{ $agenda->id }}" :show="false" maxWidth="sm">
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-gray-900 mb-4">Hapus Agenda</h2>
                                            <p class="text-gray-600 mb-6">Yakin ingin menghapus agenda <strong>{{ $agenda->title }}</strong>?</p>
                                            <form action="{{ route('admin.agendas.destroy', $agenda) }}" method="POST" class="flex justify-end gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-sm">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </x-modal>

                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-500">
                                            Belum ada agenda kegiatan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $agendas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <x-modal name="add-agenda" :show="false" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Agenda Kegiatan</h2>
            <form action="{{ route('admin.agendas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="label">Judul Kegiatan</label>
                    <input type="text" name="title" class="input-field" required placeholder="Contoh: Rapat Rutin Bulanan">
                </div>
                <div>
                    <label class="label">Jenis Kegiatan</label>
                    <select name="type" class="input-field" required>
                        <option value="umum">Umum</option>
                        <option value="rapat">Rapat Warga/Pengurus</option>
                        <option value="kerjabakti">Kerja Bakti</option>
                        <option value="posyandu">Posyandu</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" class="input-field" required>
                    </div>
                    <div>
                        <label class="label">Waktu Selesai (Opsional)</label>
                        <input type="datetime-local" name="end_time" class="input-field">
                    </div>
                </div>
                <div>
                    <label class="label">Lokasi</label>
                    <input type="text" name="location" class="input-field" placeholder="Contoh: Balai RT 01">
                </div>
                <div>
                    <label class="label">Deskripsi Tambahan</label>
                    <textarea name="description" class="input-field" rows="3" placeholder="Informasi tambahan terkait kegiatan..."></textarea>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
