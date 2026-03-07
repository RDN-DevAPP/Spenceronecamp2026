{{-- Reusable user table partial for Admin and Regu tabs --}}
<div class="overflow-x-auto">
    {{-- Mobile --}}
    <div class="md:hidden divide-y divide-gray-200">
        @forelse($users as $user)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->username }}</div>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mb-3">
                    <i data-lucide="mail" class="w-3 h-3 inline mr-1"></i>{{ $user->email }}
                </div>
                <div class="flex space-x-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-md text-xs font-bold">
                        <i data-lucide="edit-3" class="w-3 h-3 mr-1"></i> Edit
                    </a>
                    @if($user->id !== auth()->id())
                        <form id="del-{{ $role }}-m-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                            method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete('del-{{ $role }}-m-{{ $user->id }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-md text-xs font-bold">
                                <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500 text-sm">Belum ada user {{ $role }}.</div>
        @endforelse
    </div>

    {{-- Desktop --}}
    <table class="hidden md:table min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Username</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        @if($user->id !== auth()->id())
                            <form id="del-{{ $role }}-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                                method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete('del-{{ $role }}-{{ $user->id }}')"
                                    class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada user {{ $role }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>