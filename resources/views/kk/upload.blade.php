<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kk.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-gray-900">Upload Kartu Keluarga</h1>
                <p class="text-sm text-gray-500 mt-0.5">AI akan membaca dan mengekstrak data otomatis</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-lg space-y-4">
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <!-- Info tip -->
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-blue-50 border border-blue-100 mb-5">
                <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-700">Pastikan foto KK jelas, terang, dan tidak buram agar AI dapat membaca data dengan akurat.</p>
            </div>

            <form action="{{ route('kk.extract') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf

                <!-- Drop zone -->
                <div id="drop-zone"
                     class="rounded-xl border-2 border-dashed border-gray-200 p-10 text-center cursor-pointer transition-all mb-5 hover:border-indigo-300 hover:bg-indigo-50/30"
                     onclick="document.getElementById('foto_kk').click()">

                    <div id="placeholder">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center mx-auto mb-4 border border-indigo-100">
                            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Klik untuk pilih foto KK</p>
                        <p class="text-xs text-gray-400 mt-1">Format JPG, PNG — Maks. 8MB</p>
                    </div>

                    <div id="preview-wrap" class="hidden">
                        <img id="preview" src="#" alt="Preview KK" class="max-h-56 mx-auto rounded-xl object-contain border border-gray-200 mb-3">
                        <p id="file-name" class="text-xs text-gray-500"></p>
                    </div>
                </div>

                <input type="file" name="foto_kk" id="foto_kk" accept="image/*" class="hidden" required>

                <button type="submit" id="submit-btn"
                        class="btn-primary w-full justify-center py-2.5"
                        style="width: 100%; justify-content: center;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                    </svg>
                    Upload & Ekstrak dengan AI
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('foto_kk').addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                document.getElementById('preview').src = URL.createObjectURL(file);
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('placeholder').classList.add('hidden');
                document.getElementById('preview-wrap').classList.remove('hidden');
                const dz = document.getElementById('drop-zone');
                dz.classList.add('border-indigo-300', 'bg-indigo-50/30');
            }
        });
        document.getElementById('upload-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Memproses dengan AI...';
            btn.disabled = true; btn.style.opacity = '0.7';
        });
    </script>
</x-app-layout>
