{{--
    Global Announcement Popup (Modal) — SmartRT Vision
    Identik dengan ApoApps: backdrop glassmorphism, spring animation,
    multi-pengumuman dengan dot navigator, dismiss ke database.
--}}
@if(isset($globalAnnouncements) && $globalAnnouncements->isNotEmpty())
    <div id="announcement-root"
         x-cloak
         x-show="show"
         x-data='{
            announcements: @json($globalAnnouncements),
            currentIndex: 0,
            show: false,
            isClosing: false,
            init() {
                this.show = this.announcements.length > 0;
            },
            dismiss(id) {
                if (this.isClosing) return;
                this.isClosing = true;
                this.show = false;

                setTimeout(() => {
                    if (this.currentIndex < this.announcements.length - 1) {
                        this.currentIndex++;
                        this.show = true;
                        this.isClosing = false;
                    } else {
                        const el = document.getElementById("announcement-root");
                        if (el) el.remove();
                    }
                }, 400);

                // Simpan ke database (Async)
                fetch("{{ route('announcements.dismiss') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content
                    },
                    body: JSON.stringify({ announcement_id: id })
                });
            }
         }'
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6">

        {{-- Backdrop glassmorphism --}}
        <div x-show="show"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-slate-900/40 backdrop-blur-md"></div>

        {{-- Modal Container --}}
        <div x-show="show"
             x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-600"
             x-transition:enter-start="opacity-0 scale-90 translate-y-12"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl overflow-hidden ring-1 ring-slate-900/5">

            <template x-if="announcements[currentIndex]">
                <div class="relative group">
                    {{-- Close Button (X) — hanya jika is_dismissible --}}
                    <template x-if="announcements[currentIndex].is_dismissible">
                        <button @click="dismiss(announcements[currentIndex].id)"
                                class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-white/40 hover:bg-white/80 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all backdrop-blur-md shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </template>

                    {{-- Visual Header --}}
                    <div :class="announcements[currentIndex].type_label.bg"
                         class="h-40 flex items-center justify-center relative overflow-hidden transition-colors duration-500">

                        {{-- Elegant Background Orbs --}}
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/40 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-black/5 rounded-full blur-2xl"></div>
                        
                        {{-- Subtle Pattern --}}
                        <div class="absolute inset-0 opacity-10" :class="announcements[currentIndex].type_label.text" style="background-image: radial-gradient(circle at 2px 2px, currentColor 1px, transparent 0); background-size: 24px 24px;"></div>

                        {{-- Icon Circle --}}
                        <div class="w-20 h-20 rounded-2xl bg-white shadow-xl flex items-center justify-center relative z-10 transform group-hover:scale-105 transition-transform duration-500 ring-4 ring-white/50">

                            <template x-if="announcements[currentIndex].type === 'info'">
                                <svg class="w-10 h-10" :class="announcements[currentIndex].type_label.icon_color" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </template>
                            <template x-if="announcements[currentIndex].type === 'warning'">
                                <svg class="w-10 h-10" :class="announcements[currentIndex].type_label.icon_color" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </template>
                            <template x-if="announcements[currentIndex].type === 'success'">
                                <svg class="w-10 h-10" :class="announcements[currentIndex].type_label.icon_color" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </template>
                            <template x-if="announcements[currentIndex].type === 'danger'">
                                <svg class="w-10 h-10" :class="announcements[currentIndex].type_label.icon_color" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </template>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-10 pt-10 pb-6 text-center">
                        {{-- Label badge --}}
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 mb-4">
                            <span class="w-1.5 h-1.5 rounded-full animate-pulse"
                                  :class="announcements[currentIndex].type_label.icon_color.replace('text-', 'bg-')"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500"
                                  x-text="announcements[currentIndex].type_label.label + ' — SmartRT Vision Official'"></span>
                        </div>

                        <h3 class="text-2xl font-black text-slate-900 leading-tight mb-4"
                            x-text="announcements[currentIndex].title"></h3>

                        <div class="max-h-[15rem] overflow-y-auto pr-2 custom-scrollbar">
                            <p class="text-sm text-slate-600 font-medium leading-relaxed whitespace-pre-wrap text-center md:text-justify"
                               x-text="announcements[currentIndex].message"></p>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="px-10 pb-10 pt-4">
                        <button @click="dismiss(announcements[currentIndex].id)"
                                class="w-full relative group/btn overflow-hidden bg-slate-900 text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] shadow-2xl shadow-slate-200">
                            <span class="relative z-10">SAYA MENGERTI, LANJUTKAN</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-violet-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </button>

                        {{-- Dot navigator untuk multiple announcements --}}
                        <div class="flex items-center justify-center gap-3 mt-6">
                            <template x-for="(ann, index) in announcements" :key="index">
                                <div class="h-1.5 rounded-full transition-all duration-500"
                                     :class="index === currentIndex
                                        ? 'w-8 ' + announcements[currentIndex].type_label.icon_color.replace('text-', 'bg-')
                                        : 'w-1.5 bg-slate-200'"></div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
@endif
