<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CCTV Lingkungan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Integrasi CCTV</h3>
                            <p class="text-sm text-gray-500">Kelola daftar kamera CCTV yang bisa dipantau oleh warga.</p>
                        </div>
                        <button @click="$dispatch('open-modal', 'add-cctv')" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Tambah Kamera
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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                        @forelse($cctvs as $cctv)
                            <div class="bg-gray-900 rounded-2xl overflow-hidden shadow-lg border border-gray-800 relative group">
                                <div class="p-4 bg-black/50 absolute top-0 inset-x-0 z-10 flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="flex gap-2">
                                        <button @click="$dispatch('open-modal', 'edit-cctv-{{ $cctv->id }}')" class="p-1.5 text-white hover:bg-white/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button @click="$dispatch('open-modal', 'delete-cctv-{{ $cctv->id }}')" class="p-1.5 text-rose-400 hover:bg-rose-500/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                    @if($cctv->status === 'active')
                                        <div class="flex items-center gap-1.5 px-2 py-1 bg-rose-500/20 border border-rose-500/50 rounded-md text-xs font-bold text-rose-500 uppercase tracking-wider">
                                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                                            LIVE
                                        </div>
                                    @elseif($cctv->status === 'maintenance')
                                        <div class="flex items-center gap-1.5 px-2 py-1 bg-yellow-500/20 border border-yellow-500/50 rounded-md text-xs font-bold text-yellow-500 uppercase tracking-wider">
                                            MAINTENANCE
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 px-2 py-1 bg-gray-500/20 border border-gray-500/50 rounded-md text-xs font-bold text-gray-400 uppercase tracking-wider">
                                            OFFLINE
                                        </div>
                                    @endif
                                </div>

                                <div class="aspect-video w-full bg-black relative flex items-center justify-center">
                                    @if($cctv->status === 'active')
                                        @if(str_contains($cctv->stream_url, '<iframe'))
                                            <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full border-none">
                                                {!! $cctv->stream_url !!}
                                            </div>
                                        @else
                                            <div class="text-gray-400 flex flex-col items-center gap-2">
                                                <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span class="text-xs">Klik link HLS/RTSP untuk memutar</span>
                                                <a href="{{ $cctv->stream_url }}" target="_blank" class="text-indigo-400 text-xs hover:underline">Buka di Tab Baru</a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-gray-500 flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            <span class="text-sm">Kamera tidak aktif</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 bg-gray-900 border-t border-gray-800 flex justify-between items-center">
                                    <div>
                                        <h4 class="font-bold text-white mb-0.5">{{ $cctv->name }}</h4>
                                        <p class="text-xs text-gray-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $cctv->location ?? 'Lokasi tidak diset' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <x-modal name="edit-cctv-{{ $cctv->id }}" :show="false" maxWidth="md">
                                <div class="p-6">
                                    <h2 class="text-lg font-bold text-gray-900 mb-4">Edit Kamera CCTV</h2>
                                    <form action="{{ route('admin.cctvs.update', $cctv) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="label">Nama Kamera</label>
                                            <input type="text" name="name" value="{{ $cctv->name }}" class="input-field" required>
                                        </div>
                                        <div>
                                            <label class="label">Lokasi</label>
                                            <input type="text" name="location" value="{{ $cctv->location }}" class="input-field">
                                        </div>
                                        <div>
                                            <label class="label">Status</label>
                                            <select name="status" class="input-field" required>
                                                <option value="active" {{ $cctv->status == 'active' ? 'selected' : '' }}>Aktif (LIVE)</option>
                                                <option value="inactive" {{ $cctv->status == 'inactive' ? 'selected' : '' }}>Nonaktif / Offline</option>
                                                <option value="maintenance" {{ $cctv->status == 'maintenance' ? 'selected' : '' }}>Sedang Perbaikan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="label">Kode Embed / URL Streaming</label>
                                            <textarea name="stream_url" class="input-field font-mono text-xs" rows="4" required>{{ $cctv->stream_url }}</textarea>
                                        </div>
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                            <button type="submit" class="btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </x-modal>

                            <!-- Delete Modal -->
                            <x-modal name="delete-cctv-{{ $cctv->id }}" :show="false" maxWidth="sm">
                                <div class="p-6">
                                    <h2 class="text-lg font-bold text-gray-900 mb-4">Hapus CCTV</h2>
                                    <p class="text-gray-600 mb-6">Yakin ingin menghapus <strong>{{ $cctv->name }}</strong> dari sistem?</p>
                                    <form action="{{ route('admin.cctvs.destroy', $cctv) }}" method="POST" class="flex justify-end gap-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-sm">Ya, Hapus</button>
                                    </form>
                                </div>
                            </x-modal>
                        @empty
                            <div class="col-span-full py-12 text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-sm mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <h4 class="text-gray-900 font-bold mb-1">Belum ada CCTV</h4>
                                <p class="text-gray-500 text-sm">Integrasikan kamera keamanan RT ke dalam sistem.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <x-modal name="add-cctv" :show="false" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Integrasi CCTV</h2>
            <form action="{{ route('admin.cctvs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="label">Nama Kamera</label>
                    <input type="text" name="name" class="input-field" required placeholder="Contoh: CCTV Gerbang Utama">
                </div>
                <div>
                    <label class="label">Lokasi</label>
                    <input type="text" name="location" class="input-field" placeholder="Contoh: Blok A Perempatan">
                </div>
                <div>
                    <label class="label">Status Awal</label>
                    <select name="status" class="input-field" required>
                        <option value="active">Aktif (LIVE)</option>
                        <option value="inactive">Nonaktif / Offline</option>
                        <option value="maintenance">Sedang Perbaikan</option>
                    </select>
                </div>
                <div>
                    <label class="label">Kode Embed / URL Streaming</label>
                    <textarea name="stream_url" class="input-field font-mono text-xs" rows="4" required placeholder="Paste tag <iframe...> dari Youtube Live atau URL RTSP/HLS di sini"></textarea>
                    <p class="text-[11px] text-gray-500 mt-1">Sangat disarankan menggunakan iframe YouTube Live untuk kompatibilitas HP tertinggi.</p>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
