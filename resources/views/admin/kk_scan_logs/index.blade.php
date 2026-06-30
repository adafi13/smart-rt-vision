<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-900">Riwayat Scan AI</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar riwayat scan dokumen Kartu Keluarga oleh warga</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.riwayat-scan.index') }}" class="flex items-center gap-2">
                    <select name="status" class="text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary py-2" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-gray-700 text-xs uppercase font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">User</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Error Message</th>
                            <th class="px-5 py-3.5">Detected NIKs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4">
                                @if($log->user)
                                    {{ $log->user->name }}
                                @else
                                    <span class="text-gray-400 italic">User Terhapus</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Success
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="max-w-xs truncate" title="{{ $log->error_message }}">
                                    {{ $log->error_message ?: '-' }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if(!empty($log->extracted_niks))
                                    @foreach($log->extracted_niks as $nik)
                                        <span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs text-gray-700 mr-1 mb-1">{{ $nik }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 text-sm">
                                Belum ada data riwayat scan AI.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
                {{ $logs->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
