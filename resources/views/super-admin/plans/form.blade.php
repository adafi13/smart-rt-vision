<x-super-admin-layout :title="$plan->exists ? 'Edit Paket' : 'Tambah Paket Baru'">
    <div class="max-w-4xl space-y-5">
        <div>
            <a href="{{ route('super-admin.plans.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 mb-2 inline-block">&larr; Kembali ke Daftar Paket</a>
            <h1 class="text-xl font-bold text-gray-900">{{ $plan->exists ? 'Edit Paket: ' . $plan->name : 'Tambah Paket Baru' }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Atur harga, limit KK, kuota AI, dan fitur yang ditawarkan</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
                <ul class="list-disc list-inside text-sm text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $plan->exists ? route('super-admin.plans.update', $plan) : route('super-admin.plans.store') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6 space-y-6">
            @csrf
            @if($plan->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="label">Nama Paket <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="input-field" placeholder="Cth: Basic, Pro, Enterprise" required>
                </div>
                <div>
                    <label class="label">Slug (URL Aman) <span class="text-rose-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" class="input-field" placeholder="Cth: basic, pro, enterprise" required>
                    <p class="text-xs text-gray-400 mt-1">Harus unik, huruf kecil, tanpa spasi.</p>
                </div>
            </div>

            <div>
                <label class="label">Deskripsi Singkat</label>
                <input type="text" name="description" value="{{ old('description', $plan->description) }}" class="input-field" placeholder="Cth: Cocok untuk RT kecil dengan jumlah KK sedikit">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="label">Harga Bulanan (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price_monthly" value="{{ old('price_monthly', $plan->price_monthly ?? 0) }}" class="input-field" min="0" required>
                    <p class="text-xs text-gray-400 mt-1">Isi 0 untuk paket gratis.</p>
                </div>
                <div>
                    <label class="label">Harga Tahunan (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price_yearly" value="{{ old('price_yearly', $plan->price_yearly ?? 0) }}" class="input-field" min="0" required>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="label">Batas Maksimal (Cut) KK</label>
                    <input type="number" name="max_kk" value="{{ old('max_kk', $plan->max_kk) }}" class="input-field" min="1">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tanpa batas (unlimited).</p>
                </div>
                <div>
                    <label class="label">Batas Maksimal (Cut) Ekstraksi AI/Bulan</label>
                    <input type="number" name="max_ai_extractions_per_month" value="{{ old('max_ai_extractions_per_month', $plan->max_ai_extractions_per_month) }}" class="input-field" min="1">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tanpa batas (unlimited).</p>
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <label class="label">Fitur yang Ditampilkan (Satu baris satu fitur)</label>
                <textarea name="features" rows="5" class="input-field" placeholder="Maks. 50 KK&#10;Scan AI Terbatas&#10;Support Standar">{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Fitur ini akan muncul dengan ikon centang di halaman pembelian.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="label">Urutan Tampil (Sort Order) <span class="text-rose-500">*</span></label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}" class="input-field" required>
                    <p class="text-xs text-gray-400 mt-1">Makin kecil makin kiri.</p>
                </div>
                <div>
                    <label class="label">Paling Populer?</label>
                    <select name="is_popular" class="input-field">
                        <option value="0" {{ old('is_popular', $plan->is_popular) == false ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('is_popular', $plan->is_popular) == true ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div>
                    <label class="label">Status Aktif?</label>
                    <select name="is_active" class="input-field">
                        <option value="1" {{ old('is_active', $plan->is_active ?? true) == true ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
                        <option value="0" {{ old('is_active', $plan->is_active ?? true) == false ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('super-admin.plans.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>
</x-super-admin-layout>
