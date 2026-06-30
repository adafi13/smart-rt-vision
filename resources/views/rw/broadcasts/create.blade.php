<x-rw-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('rw.broadcasts.index') }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">Buat Pengumuman</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Tulis pesan yang akan disebarkan ke seluruh RT.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('rw.broadcasts.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Judul Pengumuman</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block px-4 py-3" placeholder="Contoh: Kerja Bakti Massal RW 08">
                    @error('title') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Isi Pesan</label>
                    <textarea name="content" rows="6" required class="w-full bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block px-4 py-3" placeholder="Tuliskan isi pengumuman secara detail...">{{ old('content') }}</textarea>
                    @error('content') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Status Penayangan</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-gray-300 has-[:checked]:border-indigo-600 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <input type="radio" name="status" value="active" class="sr-only" checked>
                            <div class="flex items-center gap-3">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-gray-300 bg-white peer-checked:border-indigo-600 peer-checked:bg-indigo-600">
                                    <span class="h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Aktif Tayang</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Langsung terlihat oleh warga</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-gray-300 has-[:checked]:border-indigo-600 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <input type="radio" name="status" value="inactive" class="sr-only">
                            <div class="flex items-center gap-3">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-gray-300 bg-white peer-checked:border-indigo-600 peer-checked:bg-indigo-600">
                                    <span class="h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">Simpan Draf</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Sembunyikan sementara</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('rw.broadcasts.index') }}" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm rounded-xl transition-colors">Terbitkan</button>
                </div>
            </form>
        </div>
    </div>
</x-rw-app-layout>
