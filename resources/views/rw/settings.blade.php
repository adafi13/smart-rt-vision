<x-rw-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Pengaturan Organisasi RW</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola informasi resmi <span class="font-semibold text-indigo-600">{{ $rw->name }}</span></p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 text-sm text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-100">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form method="POST" action="{{ route('rw.settings.update') }}" class="p-6 sm:p-8 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Organisasi RW</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $rw->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-rose-300 @enderror"
                           placeholder="Contoh: RW 05 Kelurahan Sukamaju">
                    @error('name') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                    <input id="address" name="address" type="text" value="{{ old('address', $rw->address) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('address') border-rose-300 @enderror"
                           placeholder="Jalan, gedung, atau kawasan...">
                    @error('address') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kota / Kabupaten</label>
                        <input id="city" name="city" type="text" value="{{ old('city', $rw->city) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('city') border-rose-300 @enderror"
                               placeholder="Nama kota">
                        @error('city') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="province" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Provinsi</label>
                        <input id="province" name="province" type="text" value="{{ old('province', $rw->province) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('province') border-rose-300 @enderror"
                               placeholder="Nama provinsi">
                        @error('province') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900">Informasi Akun</h2>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Email</span>
                    <span class="font-semibold text-gray-800">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Role</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">Super RW</span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-900">Edit profil akun & password →</a>
                </div>
            </div>
        </div>
    </div>
</x-rw-app-layout>
