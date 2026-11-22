{{-- Reusable user table partial for Admin and Regu tabs --}}
<div class="overflow-x-auto">
    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($users as $user)
            <div class="p-4 hover:bg-gray-50/60 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="text-sm font-bold text-gray-900 flex items-center gap-1.5">
                            <span>{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-800">Anda</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 font-mono">{{ $user->username }}</div>
                    </div>
                    @if($role === 'admin_utama')
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Akses Penuh
                        </span>
                    @endif
                </div>
                <div class="text-xs text-gray-500 mb-3 flex items-center gap-1.5">
                    <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 rounded-xl text-xs font-bold transition">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        <span>Edit</span>
                    </a>
                    @if($user->id !== auth()->id())
                        <form id="del-{{ $role }}-m-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                            method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete('del-{{ $role }}-m-{{ $user->id }}')"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-bold transition">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-400 text-sm">Belum ada akun yang terdaftar.</div>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <table class="hidden md:table min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50/60">
            <tr>
                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Username</th>
                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                @if($role === 'admin_utama')
                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hak Akses Modul</th>
                @endif
                <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                        <div class="flex items-center gap-1.5">
                            <span>{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">Anda</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    @if($role === 'admin_utama')
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                                Akses Penuh (Termasuk Manajemen User)
                            </span>
                        </td>
                    @endif
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="text-xs font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                                <form id="del-{{ $role }}-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                                    method="POST" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('del-{{ $role }}-{{ $user->id }}')"
                                        class="text-xs font-bold text-rose-700 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $role === 'admin_utama' ? 5 : 4 }}" class="px-6 py-8 text-center text-sm text-gray-400">Belum ada akun yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
