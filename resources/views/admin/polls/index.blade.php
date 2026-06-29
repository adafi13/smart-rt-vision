<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Musyawarah Warga (E-Voting)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola jejak pendapat atau pemilihan untuk warga</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        
        <!-- Action Toolbar -->
        <div class="flex justify-end">
            <a href="{{ route('admin.polls.create') }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Polling Baru
            </a>
        </div>
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($polls->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($polls as $poll)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-5 border-b border-gray-50 flex-grow">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ $poll->title }}</h2>
                        @if($poll->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600 border border-gray-200">Ditutup</span>
                        @endif
                    </div>
                    @if($poll->description)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $poll->description }}</p>
                    @endif
                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-500 font-medium mb-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Mulai: {{ $poll->start_date ? $poll->start_date->format('d M Y') : '-' }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Selesai: {{ $poll->end_date ? $poll->end_date->format('d M Y') : 'Tanpa batas' }}
                        </div>
                    </div>

                    <!-- Hasil Suara -->
                    @php
                        $totalVotes = $poll->options->sum('votes_count');
                    @endphp
                    <div class="space-y-3 mt-2">
                        <div class="text-xs font-bold text-gray-700 uppercase tracking-wide flex justify-between">
                            <span>Perolehan Suara</span>
                            <span class="text-indigo-600">{{ $totalVotes }} Suara Masuk</span>
                        </div>
                        @foreach($poll->options as $opt)
                        @php
                            $percentage = $totalVotes > 0 ? round(($opt->votes_count / $totalVotes) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-medium text-gray-700 mb-1">
                                <span>{{ $opt->option_text }}</span>
                                <span>{{ $opt->votes_count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-indigo-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="bg-gray-50/50 px-5 py-3 flex gap-2 justify-end border-t border-gray-100">
                    <a href="{{ route('admin.polls.edit', $poll) }}" class="px-3 py-1.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Edit / Status
                    </a>
                    <form action="{{ route('admin.polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Hapus polling ini permanen beserta semua data suaranya?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg hover:bg-rose-100 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pt-2">
            {{ $polls->links() }}
        </div>
        @else
        <!-- ZERO STATE -->
        <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-10 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Musyawarah/Polling</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Buat jejak pendapat pertama untuk warga. 1 Warga 1 Suara.</p>
        </div>
        @endif
    </div>
    </div>
</x-app-layout>
