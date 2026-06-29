<x-super-admin-layout title="User Management">
    <div class="space-y-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">User Management</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola akun internal tim Super Admin SmartRT Vision.</p>
            </div>
            <a href="{{ route('super-admin.staff.create') }}" class="btn-primary w-full sm:w-auto justify-center">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Staff
            </a>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-xl px-4 py-3 text-sm font-semibold text-rose-700">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Bergabung</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($staffs as $staff)
                        @php $roleColor = match($staff->su_role ?? 'owner') {
                            'owner'   => 'bg-indigo-100 text-indigo-700',
                            'finance' => 'bg-emerald-100 text-emerald-700',
                            'support' => 'bg-sky-100 text-sky-700',
                            default   => 'bg-gray-100 text-gray-600',
                        }; @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $staff->name }}
                                        @if($staff->id === auth()->id())
                                        <span class="ml-1 text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold">Anda</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $staff->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $roleColor }}">{{ ucfirst($staff->su_role ?? 'owner') }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400 hidden sm:table-cell">{{ $staff->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('super-admin.staff.edit', $staff) }}" class="px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">Edit</a>
                                    @if($staff->id !== auth()->id())
                                    <form action="{{ route('super-admin.staff.destroy', $staff) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors" onclick="return confirm('Hapus staff {{ $staff->name }}?')">Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-10 text-center text-sm text-gray-400">Belum ada staff.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($staffs->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $staffs->links() }}</div>
            @endif
        </div>
    </div>
</x-super-admin-layout>
