<x-super-admin-layout title="Manajemen Kupon">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Manajemen Kupon / Promo</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola kode diskon untuk pengguna yang berlangganan</p>
            </div>
            <button onclick="document.getElementById('addCouponModal').style.display='flex'" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Kupon Baru
            </button>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wider font-bold text-gray-500">
                            <th class="px-5 py-4">Kode Kupon</th>
                            <th class="px-5 py-4">Tipe Diskon</th>
                            <th class="px-5 py-4">Nilai</th>
                            <th class="px-5 py-4">Pemakaian</th>
                            <th class="px-5 py-4">Kadaluarsa</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-mono font-bold text-slate-700">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 font-medium text-gray-700">
                                    {{ $coupon->discount_type === 'percent' ? 'Persentase (%)' : 'Nominal Pasti (Rp)' }}
                                    <div class="mt-1">
                                        @if($coupon->applicable_cycle === 'monthly')
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200">Khusus Bulanan</span>
                                        @elseif($coupon->applicable_cycle === 'yearly')
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-600 border border-purple-200">Khusus Tahunan</span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-gray-50 text-gray-500 border border-gray-200">Semua Siklus</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-bold text-gray-900">
                                    {{ $coupon->discount_type === 'percent' ? floatval($coupon->discount_value) . '%' : 'Rp ' . number_format($coupon->discount_value, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    <span class="font-bold text-gray-900">{{ $coupon->used_count }}</span>
                                    / {{ $coupon->max_uses ?? 'Tak Terbatas' }}
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y, H:i') : 'Selamanya' }}
                                </td>
                                <td class="px-5 py-3">
                                    <form action="{{ route('super-admin.coupons.toggle', $coupon) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition-colors {{ $coupon->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <button onclick="openEditModal({{ $coupon->id }}, '{{ $coupon->code }}', '{{ $coupon->discount_type }}', '{{ $coupon->discount_type === 'fixed' ? intval($coupon->discount_value) : floatval($coupon->discount_value) }}', '{{ $coupon->applicable_cycle }}', '{{ $coupon->max_uses }}', '{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '' }}')" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded transition-colors mr-1" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>
                                    <form action="{{ route('super-admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Hapus kupon ini permanen?')" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500 text-sm">
                                    Belum ada kupon yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Buat Kupon -->
    <div id="addCouponModal" class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm items-center justify-center" style="display: none;">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden m-4 relative animate-[fade-in-up_0.2s_ease-out]">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Buat Kupon Baru</h3>
                <button type="button" onclick="document.getElementById('addCouponModal').style.display='none'" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('super-admin.coupons.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Kode Kupon</label>
                        <input type="text" name="code" class="input-field" placeholder="Contoh: PROMO100, MERDEKA" required style="text-transform: uppercase;">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Target Siklus</label>
                        <select name="applicable_cycle" class="input-field" required>
                            <option value="all">Berlaku untuk Semua</option>
                            <option value="monthly">Khusus Bulanan</option>
                            <option value="yearly">Khusus Tahunan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4" x-data="{
                    type: 'percent',
                    displayValue: '',
                    get realValue() {
                        if (this.type === 'fixed') {
                            return this.displayValue.replace(/\./g, '');
                        }
                        return this.displayValue.replace(/,/g, '.');
                    },
                    formatInput() {
                        if (this.type === 'fixed') {
                            let val = this.displayValue.replace(/[^0-9]/g, '');
                            if(val !== '') {
                                this.displayValue = parseInt(val, 10).toLocaleString('id-ID');
                            }
                        } else {
                            // Allow numbers and one dot or comma
                            let val = this.displayValue.replace(/[^0-9.,]/g, '');
                            this.displayValue = val;
                        }
                    }
                }">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Tipe Diskon</label>
                        <select name="discount_type" x-model="type" @change="displayValue = ''; formatInput()" class="input-field" required>
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Nilai Diskon</label>
                        <input type="text" x-model="displayValue" @input="formatInput()" class="input-field" placeholder="10 atau 50.000" required>
                        <input type="hidden" name="discount_value" :value="realValue">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Batas Pakai <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="max_uses" class="input-field" placeholder="Tak terbatas" min="1">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Berlaku Sampai <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="datetime-local" name="expires_at" class="input-field">
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full btn-primary justify-center">Simpan Kupon</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kupon -->
    <div id="editCouponModal" class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm items-center justify-center" style="display: none;">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden m-4 relative animate-[fade-in-up_0.2s_ease-out]">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Edit Kupon</h3>
                <button type="button" onclick="document.getElementById('editCouponModal').style.display='none'" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="editCouponForm" method="POST" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Kode Kupon</label>
                        <input type="text" id="edit_code" name="code" class="input-field" required style="text-transform: uppercase;">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Target Siklus</label>
                        <select id="edit_applicable_cycle" name="applicable_cycle" class="input-field" required>
                            <option value="all">Berlaku untuk Semua</option>
                            <option value="monthly">Khusus Bulanan</option>
                            <option value="yearly">Khusus Tahunan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4" id="editDiscountData" x-data="{
                    type: 'percent',
                    displayValue: '',
                    get realValue() {
                        if (this.type === 'fixed') {
                            return this.displayValue.replace(/\./g, '');
                        }
                        return this.displayValue.replace(/,/g, '.');
                    },
                    formatInput() {
                        if (this.type === 'fixed') {
                            let val = this.displayValue.replace(/[^0-9]/g, '');
                            if(val !== '') {
                                this.displayValue = parseInt(val, 10).toLocaleString('id-ID');
                            }
                        } else {
                            let val = this.displayValue.replace(/[^0-9.,]/g, '');
                            this.displayValue = val;
                        }
                    }
                }">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Tipe Diskon</label>
                        <select id="edit_discount_type" name="discount_type" x-model="type" @change="displayValue = ''; formatInput()" class="input-field" required>
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Nilai Diskon</label>
                        <input type="text" id="edit_discount_display" x-model="displayValue" @input="formatInput()" class="input-field" required>
                        <input type="hidden" id="edit_discount_value" name="discount_value" :value="realValue">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Batas Pakai <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="number" id="edit_max_uses" name="max_uses" class="input-field" min="1">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Berlaku Sampai <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="datetime-local" id="edit_expires_at" name="expires_at" class="input-field">
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full btn-primary justify-center">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(id, code, type, value, applicableCycle, maxUses, expiresAt) {
            const form = document.getElementById('editCouponForm');
            form.action = `/super-admin/coupons/${id}`;
            
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_applicable_cycle').value = applicableCycle || 'all';
            
            // Set values and trigger alpine reactivity
            const alpineData = Alpine.$data(document.getElementById('editDiscountData'));
            alpineData.type = type;
            
            if (type === 'fixed') {
                alpineData.displayValue = parseInt(value, 10).toLocaleString('id-ID');
            } else {
                alpineData.displayValue = parseFloat(value).toString();
            }
            alpineData.formatInput();

            document.getElementById('edit_max_uses').value = maxUses || '';
            document.getElementById('edit_expires_at').value = expiresAt || '';
            
            document.getElementById('editCouponModal').style.display = 'flex';
        }
    </script>
    @endpush
</x-super-admin-layout>
