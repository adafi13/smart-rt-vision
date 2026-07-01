<x-rw-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kalender Agenda & Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Daftar Kegiatan RW</h3>
                            <p class="text-sm text-gray-500 mt-1">Kelola jadwal rapat, kerja bakti, posyandu tingkat RW.</p>
                        </div>
                        <a href="{{ route('rw.agendas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-sm shadow-indigo-200 inline-flex items-center gap-2 w-full sm:w-auto justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Tambah Agenda
                        </a>
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

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="p-4 font-semibold rounded-tl-xl whitespace-nowrap">Tanggal & Waktu</th>
                                    <th class="p-4 font-semibold whitespace-nowrap">Kegiatan</th>
                                    <th class="p-4 font-semibold whitespace-nowrap">Jenis</th>
                                    <th class="p-4 font-semibold whitespace-nowrap">Lokasi</th>
                                    <th class="p-4 font-semibold text-right rounded-tr-xl whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($agendas as $agenda)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            <div class="font-semibold text-gray-900 whitespace-nowrap">{{ $agenda->start_time->format('d M Y') }}</div>
                                            <div class="text-sm text-gray-500 whitespace-nowrap">{{ $agenda->start_time->format('H:i') }} {{ $agenda->end_time ? '- ' . $agenda->end_time->format('H:i') : '' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-bold text-gray-900">{{ $agenda->title }}</div>
                                            @if($agenda->description)
                                                <div class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $agenda->description }}</div>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($agenda->type === 'rapat')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700">Rapat</span>
                                            @elseif($agenda->type === 'kerjabakti')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-700">Kerja Bakti</span>
                                            @elseif($agenda->type === 'posyandu')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-pink-100 text-pink-700">Posyandu</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700">Umum</span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-sm text-gray-600">{{ $agenda->location ?? '-' }}</td>
                                        <td class="p-4 text-right flex items-center justify-end gap-2">
                                            <a href="{{ route('rw.agendas.edit', $agenda->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors inline-block whitespace-nowrap">Edit</a>
                                            <button @click="$dispatch('open-modal', 'delete-agenda-{{ $agenda->id }}')" class="text-rose-600 hover:text-rose-900 font-semibold text-xs bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <x-modal name="delete-agenda-{{ $agenda->id }}" :show="false" maxWidth="sm">
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-gray-900 mb-4">Hapus Agenda</h2>
                                            <p class="text-gray-600 mb-6">Yakin ingin menghapus agenda <strong>{{ $agenda->title }}</strong>?</p>
                                            <form action="{{ route('rw.agendas.destroy', $agenda) }}" method="POST" class="flex justify-end gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-sm">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </x-modal>

                                @empty
                                    <tr>
                                        <td colspan="5" class="p-12 text-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Agenda RW</h3>
                                            <p class="text-xs text-gray-500 max-w-sm mx-auto">Klik tombol "Tambah Agenda" di atas untuk membuat jadwal kegiatan RW yang baru.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards Layout (Visible only on small screens) -->
                    <div class="grid grid-cols-1 gap-4 md:hidden">
                        @foreach($agendas as $agenda)
                            <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm flex flex-col gap-3">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 leading-tight">{{ $agenda->title }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $agenda->start_time->format('d M Y') }} &bull; {{ $agenda->start_time->format('H:i') }}
                                        </p>
                                    </div>
                                    @if($agenda->type === 'rapat')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-blue-100 text-blue-700">Rapat</span>
                                    @elseif($agenda->type === 'kerjabakti')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700">Kerja</span>
                                    @elseif($agenda->type === 'posyandu')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-pink-100 text-pink-700">Posyandu</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-gray-100 text-gray-700">Umum</span>
                                    @endif
                                </div>
                                
                                @if($agenda->location)
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="truncate">{{ $agenda->location }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 mt-2 pt-3 border-t border-gray-100">
                                    <a href="{{ route('rw.agendas.edit', $agenda->id) }}" class="flex-1 text-center font-bold text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 py-2.5 rounded-xl transition-colors">Edit</a>
                                    <button @click="$dispatch('open-modal', 'delete-agenda-{{ $agenda->id }}')" class="flex-1 font-bold text-xs bg-rose-50 text-rose-700 hover:bg-rose-100 py-2.5 rounded-xl transition-colors">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($agendas->isEmpty())
                            <div class="p-10 text-center bg-gray-50 rounded-3xl border border-gray-100 border-dashed">
                                <div class="w-14 h-14 bg-white shadow-sm rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Agenda RW</h3>
                                <p class="text-xs text-gray-500 max-w-[200px] mx-auto leading-relaxed">Agenda yang Bapak tambahkan akan tampil di sini.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        {{ $agendas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-rw-app-layout>
