<x-super-admin-layout title="Edit RT - {{ $tenant->name }}">
    <div class="max-w-3xl mx-auto space-y-6" x-data="editTenantForm()">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('super-admin.show', $tenant) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors bg-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-gray-900 uppercase tracking-wide flex items-center gap-2">
                    EDIT RT
                </h1>
                <p class="text-[11px] text-gray-400 mt-0.5 uppercase tracking-wide font-semibold">{{ $tenant->name }} &bull; #{{ $tenant->id }}</p>
            </div>
        </div>

        <form action="{{ route('super-admin.update', $tenant) }}" method="POST" id="edit-tenant-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-6 mb-24">
                <!-- INFORMASI DASAR -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-5">INFORMASI DASAR</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">NAMA RT <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required class="w-full rounded-xl border-gray-100 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 font-semibold px-4 py-3">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">EMAIL (OPSIONAL)</label>
                            <input type="email" name="email" value="{{ old('email', $tenant->email) }}" class="w-full rounded-xl border-gray-100 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 px-4 py-3">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">TELEPON (OPSIONAL)</label>
                            <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="w-full rounded-xl border-gray-100 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 px-4 py-3">
                        </div>
                    </div>
                </div>

                <!-- MANAJEMEN PAKET & LISENSI -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-5">MANAJEMEN PAKET & LISENSI</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">PAKET BERLANGGANAN</label>
                            <select name="plan_id" class="w-full rounded-xl border-gray-100 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 font-semibold px-4 py-3">
                                <option value="">— Tanpa Paket —</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ ($activeSub && $activeSub->plan_id == $plan->id) ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">TANGGAL BERAKHIR LANGGANAN <span class="text-gray-300 normal-case">(Kosongkan = selamanya)</span></label>
                            <div class="relative">
                                <input type="date" name="current_period_end" x-model="endDate" class="w-full rounded-xl border-gray-100 bg-gray-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 font-bold px-4 py-3">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">PERPANJANG CEPAT:</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="addDays(7)" class="px-4 py-2 rounded-lg border border-gray-100 bg-white text-[10px] font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-200 transition-colors shadow-sm">
                                    +7 HARI (TRIAL) <span class="font-normal text-gray-400 ml-1" x-text="previewDate(7)"></span>
                                </button>
                                <button type="button" @click="addMonths(1)" class="px-4 py-2 rounded-lg border border-gray-100 bg-white text-[10px] font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-200 transition-colors shadow-sm">
                                    +1 BULAN <span class="font-normal text-gray-400 ml-1" x-text="previewDate(30)"></span>
                                </button>
                                <button type="button" @click="addMonths(3)" class="px-4 py-2 rounded-lg border border-gray-100 bg-white text-[10px] font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-200 transition-colors shadow-sm">
                                    +3 BULAN <span class="font-normal text-gray-400 ml-1" x-text="previewDate(90)"></span>
                                </button>
                                <button type="button" @click="addMonths(12)" class="px-4 py-2 rounded-lg border border-gray-100 bg-white text-[10px] font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-200 transition-colors shadow-sm">
                                    +1 TAHUN <span class="font-normal text-gray-400 ml-1" x-text="previewDate(365)"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATUS AKSES -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-5">STATUS AKSES</h2>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-gray-900" x-text="isActive ? 'Mitra Aktif' : 'Mitra Diblokir'"></p>
                            <p class="text-[11px] text-gray-400 mt-1">Nonaktifkan untuk memblokir semua akses user mitra ini</p>
                        </div>
                        <input type="hidden" name="status" :value="isActive ? 'active' : 'suspended'">
                        <button type="button" @click="isActive = !isActive" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2" :class="isActive ? 'bg-emerald-500' : 'bg-gray-200'" role="switch" aria-checked="true">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="isActive ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Floating Bottom Action Bar -->
            <div class="fixed bottom-0 left-0 lg:left-64 right-0 bg-white border-t border-gray-100 px-6 py-4 flex justify-end gap-3 z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <a href="{{ route('super-admin.show', $tenant) }}" class="px-6 py-2.5 rounded-xl border border-gray-200 bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors uppercase tracking-wide">
                    BATAL
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-700 transition-colors flex items-center gap-2 uppercase tracking-wide shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editTenantForm', () => ({
                endDate: '{{ $activeSub && $activeSub->current_period_end ? $activeSub->current_period_end->format("Y-m-d") : "" }}',
                isActive: {{ in_array($tenant->status, ['active', 'trial']) ? 'true' : 'false' }},
                
                addDays(days) {
                    let d = this.endDate ? new Date(this.endDate) : new Date();
                    d.setDate(d.getDate() + days);
                    this.endDate = d.toISOString().split('T')[0];
                },
                addMonths(months) {
                    let d = this.endDate ? new Date(this.endDate) : new Date();
                    d.setMonth(d.getMonth() + months);
                    this.endDate = d.toISOString().split('T')[0];
                },
                previewDate(daysOffset) {
                    let d = this.endDate ? new Date(this.endDate) : new Date();
                    if(daysOffset === 7) {
                        d.setDate(d.getDate() + 7);
                    } else if(daysOffset === 30) {
                        d.setMonth(d.getMonth() + 1);
                    } else if(daysOffset === 90) {
                        d.setMonth(d.getMonth() + 3);
                    } else if(daysOffset === 365) {
                        d.setFullYear(d.getFullYear() + 1);
                    }
                    return '(' + d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ')';
                }
            }))
        })
    </script>
    @endpush
</x-super-admin-layout>
