<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Brankas Digital RT') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold">Arsip Dokumen</h3>
                            <p class="text-sm text-gray-500">Penyimpanan cloud untuk dokumen penting kepengurusan RT.</p>
                        </div>
                        <button @click="$dispatch('open-modal', 'upload-document')" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Upload Dokumen
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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($documents as $doc)
                            <div class="bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow rounded-2xl overflow-hidden flex flex-col">
                                <div class="p-5 flex-1">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        @if($doc->is_public)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wide">Publik</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-wide">Private (Internal)</span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-gray-900 line-clamp-1 mb-1">{{ $doc->title }}</h4>
                                    <div class="text-xs font-medium text-indigo-600 mb-2 uppercase tracking-wide">{{ str_replace('_', ' ', $doc->category) }}</div>
                                    @if($doc->description)
                                        <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $doc->description }}</p>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-auto">
                                        Diunggah: {{ $doc->created_at->format('d M Y') }} oleh {{ $doc->uploader->name ?? 'Anonim' }}
                                    </div>
                                </div>
                                <div class="bg-gray-50 border-t border-gray-100 p-3 flex justify-between items-center">
                                    <a href="{{ route('admin.documents.download', $doc) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center gap-1 px-2 py-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download
                                    </a>
                                    <div class="flex items-center gap-1">
                                        <button @click="$dispatch('open-modal', 'edit-doc-{{ $doc->id }}')" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button @click="$dispatch('open-modal', 'delete-doc-{{ $doc->id }}')" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <x-modal name="edit-doc-{{ $doc->id }}" :show="false" maxWidth="md">
                                <div class="p-6">
                                    <h2 class="text-lg font-bold text-gray-900 mb-4">Edit Info Dokumen</h2>
                                    <form action="{{ route('admin.documents.update', $doc) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="label">Nama Dokumen</label>
                                            <input type="text" name="title" value="{{ $doc->title }}" class="input-field" required>
                                        </div>
                                        <div>
                                            <label class="label">Kategori</label>
                                            <select name="category" class="input-field" required>
                                                <option value="sk" {{ $doc->category == 'sk' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                                                <option value="notulen" {{ $doc->category == 'notulen' ? 'selected' : '' }}>Notulen Rapat</option>
                                                <option value="laporan" {{ $doc->category == 'laporan' ? 'selected' : '' }}>Laporan (Keuangan/Kegiatan)</option>
                                                <option value="surat_masuk" {{ $doc->category == 'surat_masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                                <option value="surat_keluar" {{ $doc->category == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
                                                <option value="umum" {{ $doc->category == 'umum' ? 'selected' : '' }}>Umum / Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="label">Ganti File (Opsional)</label>
                                            <input type="file" name="file" class="input-field text-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti file lama.</p>
                                        </div>
                                        <div>
                                            <label class="label">Deskripsi Tambahan (Opsional)</label>
                                            <textarea name="description" class="input-field" rows="2">{{ $doc->description }}</textarea>
                                        </div>
                                        <div class="flex items-center gap-2 mt-2">
                                            <input type="checkbox" name="is_public" id="is_public_edit_{{ $doc->id }}" value="1" {{ $doc->is_public ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="is_public_edit_{{ $doc->id }}" class="text-sm text-gray-700 cursor-pointer">Bisa dilihat/diunduh oleh Warga (Publik)</label>
                                        </div>
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                            <button type="submit" class="btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </x-modal>

                            <!-- Delete Modal -->
                            <x-modal name="delete-doc-{{ $doc->id }}" :show="false" maxWidth="sm">
                                <div class="p-6">
                                    <h2 class="text-lg font-bold text-gray-900 mb-4">Hapus Dokumen</h2>
                                    <p class="text-gray-600 mb-6">Yakin ingin menghapus <strong>{{ $doc->title }}</strong> secara permanen?</p>
                                    <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" class="flex justify-end gap-3">
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
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4 class="text-gray-900 font-bold mb-1">Brankas Kosong</h4>
                                <p class="text-gray-500 text-sm">Belum ada dokumen yang diunggah.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <x-modal name="upload-document" :show="false" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Upload Dokumen Baru</h2>
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="label">Nama Dokumen</label>
                    <input type="text" name="title" class="input-field" required placeholder="Contoh: SK Kelurahan 2026">
                </div>
                <div>
                    <label class="label">Kategori</label>
                    <select name="category" class="input-field" required>
                        <option value="sk">Surat Keputusan (SK)</option>
                        <option value="notulen">Notulen Rapat</option>
                        <option value="laporan">Laporan (Keuangan/Kegiatan)</option>
                        <option value="surat_masuk">Surat Masuk</option>
                        <option value="surat_keluar">Surat Keluar</option>
                        <option value="umum">Umum / Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="label">File Dokumen</label>
                    <input type="file" name="file" class="input-field text-sm" required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                    <p class="text-[11px] text-gray-500 mt-1">Maksimal 10MB.</p>
                </div>
                <div>
                    <label class="label">Deskripsi Tambahan (Opsional)</label>
                    <textarea name="description" class="input-field" rows="2" placeholder="Keterangan singkat..."></textarea>
                </div>
                <div class="flex items-center gap-2 mt-2 bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                    <input type="checkbox" name="is_public" id="is_public_new" value="1" class="rounded border-yellow-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                    <label for="is_public_new" class="text-sm font-medium text-yellow-800 cursor-pointer">Bisa dilihat & diunduh oleh Warga (Publik)</label>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
