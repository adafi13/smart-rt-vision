<x-super-admin-layout>
    <x-slot name="title">Tulis Artikel Baru</x-slot>

    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#content',
        plugins: 'lists link image table code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
        height: 500,
        content_style: 'body { font-family:Inter,sans-serif; font-size:16px }'
      });
    </script>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tulis Artikel Baru</h1>
        </div>
        <a href="{{ route('super-admin.articles.index') }}" class="btn-ghost !text-slate-600 !border-slate-300 hover:!bg-slate-50">
            Batal
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super-admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:p-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kiri: Form Utama -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Artikel *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Manfaat Aplikasi Kas RT Digital" class="input-field text-lg font-medium">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Konten *</label>
                    <textarea name="content" id="content">{{ old('content') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ringkasan (Excerpt)</label>
                    <textarea name="excerpt" rows="3" class="input-field" placeholder="Ringkasan singkat untuk SEO dan cuplikan artikel...">{{ old('excerpt') }}</textarea>
                </div>
            </div>

            <!-- Kanan: Sidebar Form -->
            <div class="space-y-6">
                <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Publikasi</label>
                    <select name="status" class="input-field mb-4">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Terbitkan)</option>
                    </select>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Sampul</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                
                <button type="submit" class="w-full btn-primary justify-center py-3 text-base">
                    Simpan Artikel
                </button>
            </div>
        </div>
    </form>
</x-super-admin-layout>
