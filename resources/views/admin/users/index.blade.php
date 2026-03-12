@extends('layouts.main')

@section('title', 'Manajemen User - Admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-scout-primary">Manajemen User</h1>
            <a href="{{ route('admin.users.create') }}"
                class="px-4 py-2 bg-scout-primary text-white rounded-lg hover:bg-scout-primary/90 transition text-sm font-bold">
                <i data-lucide="user-plus" class="w-4 h-4 inline mr-1"></i> Tambah User
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tabs -->
        <div class="flex space-x-2 mb-6 bg-white rounded-xl p-1.5 shadow-sm border border-gray-200 max-w-lg">
            <button onclick="showUserTab('admin')" id="tab-admin"
                class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all bg-scout-primary text-white shadow">
                <i data-lucide="shield-check" class="w-4 h-4 inline mr-1"></i>
                Admin <span class="ml-1 bg-white/20 px-1.5 py-0.5 rounded text-xs">{{ $admins->count() }}</span>
            </button>
            <button onclick="showUserTab('juri')" id="tab-juri"
                class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all text-gray-600 hover:bg-gray-100">
                <i data-lucide="gavel" class="w-4 h-4 inline mr-1"></i>
                Juri <span class="ml-1 bg-gray-200 px-1.5 py-0.5 rounded text-xs">{{ $juris->count() }}</span>
            </button>
            <button onclick="showUserTab('regu')" id="tab-regu"
                class="flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all text-gray-600 hover:bg-gray-100">
                <i data-lucide="users" class="w-4 h-4 inline mr-1"></i>
                Regu <span class="ml-1 bg-gray-200 px-1.5 py-0.5 rounded text-xs">{{ $regus->count() }}</span>
            </button>
        </div>

        {{-- ========== TAB: ADMIN ========== --}}
        <div id="content-admin" class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-scout-primary to-scout-primary/80 px-6 py-3">
                <h2 class="text-white font-bold text-sm uppercase tracking-wider">User Admin</h2>
            </div>
            @include('admin.users._user_table', ['users' => $admins, 'role' => 'admin'])
        </div>

        {{-- ========== TAB: JURI ========== --}}
        <div id="content-juri" class="space-y-6 hidden">
            {{-- Card 1: Daftar Mata Lomba & Kode --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-scout-accent to-amber-600 px-6 py-3 flex justify-between items-center">
                    <h2 class="text-white font-bold text-sm uppercase tracking-wider">Daftar Mata Lomba & Kode</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-bold text-gray-500 uppercase">Nama Lomba</th>
                                <th
                                    class="px-4 py-2.5 text-left text-xs font-bold text-gray-500 uppercase w-32 text-center">
                                    Kode</th>
                                <th class="px-4 py-2.5 text-right text-xs font-bold text-gray-500 uppercase w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($allMataLomba as $ml)
                                <tr class="hover:bg-gray-50/50 transition-colors group" id="kode-row-{{ $ml->id }}">
                                    {{-- Display Mode --}}
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 display-kode-{{ $ml->id }}">
                                        {{ $ml->nama }}
                                    </td>
                                    <td class="px-4 py-3 text-center display-kode-{{ $ml->id }}">
                                        <span
                                            class="px-2.5 py-1 text-xs font-bold font-mono rounded bg-amber-100 text-amber-800 tracking-wider border border-amber-200 shadow-sm">
                                            {{ $ml->kode ?? '------' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap display-kode-{{ $ml->id }}">
                                        <button type="button" onclick="toggleEditKode({{ $ml->id }})"
                                            class="text-scout-accent hover:text-amber-700 text-xs font-bold mr-3 inline-flex items-center gap-1">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                        </button>
                                    </td>

                                    {{-- Edit Mode (Hidden) --}}
                                    <td class="px-4 py-2 edit-kode-{{ $ml->id }}" style="display:none">
                                        <form id="edit-form-{{ $ml->id }}" action="{{ route('admin.mata-lomba.update', $ml) }}" method="POST" class="hidden">
                                            @csrf @method('PUT')
                                        </form>
                                        <input type="text" name="nama" form="edit-form-{{ $ml->id }}" value="{{ $ml->nama }}" required
                                            class="w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-scout-accent/30 focus:border-scout-accent">
                                    </td>
                                    <td class="px-4 py-2 edit-kode-{{ $ml->id }}" style="display:none">
                                        <input type="text" name="kode" form="edit-form-{{ $ml->id }}" value="{{ $ml->kode }}" maxlength="6" minlength="6"
                                            pattern="[A-Za-z0-9]{6}" required
                                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                            class="w-24 mx-auto block px-2 py-1.5 border border-gray-300 rounded-md text-xs font-mono font-bold uppercase tracking-widest text-center focus:ring-2 focus:ring-scout-accent/30 focus:border-scout-accent">
                                    </td>
                                    <td class="px-4 py-2 text-right edit-kode-{{ $ml->id }}" style="display:none">
                                        <button type="submit" form="edit-form-{{ $ml->id }}"
                                            class="text-xs font-bold text-white bg-scout-accent px-2.5 py-1.5 rounded-md hover:bg-amber-700 transition mr-1">
                                            Simpan
                                        </button>
                                        <button type="button" onclick="toggleEditKode({{ $ml->id }})"
                                            class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1.5 rounded-md hover:bg-gray-200 transition">
                                            Batal
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card 2: Daftar Juri --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-scout-accent to-amber-600 px-6 py-3">
                    <h2 class="text-white font-bold text-sm uppercase tracking-wider">User Juri</h2>
                </div>

                <div class="overflow-x-auto">
                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-gray-200">
                        @forelse($juris as $user)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->username }}</div>
                                    </div>
                                    <div class="flex flex-wrap gap-1 justify-end max-w-[50%]">
                                        @forelse($user->mataLombas as $ml)
                                            <span
                                                class="px-1.5 py-0.5 text-[10px] font-bold font-mono rounded bg-amber-100 text-amber-800">
                                                {{ $ml->kode }}
                                            </span>
                                        @empty
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-100 text-gray-500">
                                                Belum ada
                                            </span>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mb-3">
                                    <i data-lucide="mail" class="w-3 h-3 inline mr-1"></i>{{ $user->email }}
                                </div>
                                <div class="flex space-x-2 pt-2 border-t border-gray-100">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-scout-accent/10 text-scout-accent hover:bg-scout-accent/20 rounded-md text-xs font-bold">
                                        <i data-lucide="edit-3" class="w-3 h-3 mr-1"></i> Edit
                                    </a>
                                    <form id="del-juri-m-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                                        method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('del-juri-m-{{ $user->id }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-md text-xs font-bold">
                                            <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500 text-sm">Belum ada user juri.</div>
                        @endforelse
                    </div>

                    {{-- Desktop --}}
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mata Lomba</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($juris as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->username }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @forelse($user->mataLombas as $ml)
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 text-xs font-bold font-mono rounded bg-amber-100 text-amber-800 mr-1 mb-1">
                                                {{ $ml->kode }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">—</span>
                                        @endforelse
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-scout-accent hover:text-amber-700 mr-3">Edit</a>
                                        <form id="del-juri-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete('del-juri-{{ $user->id }}')"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada user juri.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ========== TAB: REGU ========== --}}
        <div id="content-regu" class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hidden">
            <div class="bg-gradient-to-r from-scout-secondary to-scout-secondary/80 px-6 py-3">
                <h2 class="text-white font-bold text-sm uppercase tracking-wider">User Regu</h2>
            </div>
            @include('admin.users._user_table', ['users' => $regus, 'role' => 'regu'])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showUserTab(tab) {
            ['admin', 'juri', 'regu'].forEach(t => {
                document.getElementById('content-' + t).classList.toggle('hidden', t !== tab);
                const btn = document.getElementById('tab-' + t);
                if (t === tab) {
                    const colors = { admin: 'bg-scout-primary', juri: 'bg-scout-accent', regu: 'bg-scout-secondary' };
                    btn.className = 'flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all ' + colors[t] + ' text-white shadow';
                } else {
                    btn.className = 'flex-1 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all text-gray-600 hover:bg-gray-100';
                }
            });
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function toggleEditKode(id) {
            const displayEls = document.querySelectorAll('.display-kode-' + id);
            const editEls = document.querySelectorAll('.edit-kode-' + id);
            const isEditing = displayEls[0].style.display === 'none';

            displayEls.forEach(el => el.style.display = isEditing ? '' : 'none');
            editEls.forEach(el => el.style.display = isEditing ? 'none' : '');

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    </script>
@endpush