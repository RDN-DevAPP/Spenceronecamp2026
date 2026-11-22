@extends('layouts.main')

@section('title', 'Manajemen Pengguna - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Sistem Kontrol Akses</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Manajemen Pengguna</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola akun dan hak akses Administrator, Juri perlombaan, dan Regu peserta.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-sm shadow-sm transition-all duration-200 hover:shadow-md">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Minimalist Tab Pills --}}
        <div class="bg-white p-1.5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-wrap gap-1.5 max-w-2xl mb-6">
            <button onclick="showUserTab('super_admin')" id="tab-super_admin"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all bg-brand-forest text-white shadow-sm whitespace-nowrap">
                <i data-lucide="shield-check" class="w-4 h-4 text-amber-300"></i>
                <span>Admin Utama</span>
                <span id="badge-super_admin" class="ml-1 bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $superAdmins->count() }}</span>
            </button>
            <button onclick="showUserTab('sub_admin')" id="tab-sub_admin"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50 whitespace-nowrap">
                <i data-lucide="shield" class="w-4 h-4 text-emerald-600"></i>
                <span>Admin Terbatas</span>
                <span id="badge-sub_admin" class="ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $subAdmins->count() }}</span>
            </button>
            <button onclick="showUserTab('juri')" id="tab-juri"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50 whitespace-nowrap">
                <i data-lucide="gavel" class="w-4 h-4 text-amber-500"></i>
                <span>Juri</span>
                <span id="badge-juri" class="ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $juris->count() }}</span>
            </button>
            <button onclick="showUserTab('regu')" id="tab-regu"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50 whitespace-nowrap">
                <i data-lucide="users" class="w-4 h-4 text-teal-600"></i>
                <span>Regu</span>
                <span id="badge-regu" class="ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $regus->count() }}</span>
            </button>
        </div>

        {{-- TAB 1: ADMIN UTAMA (SUPER ADMIN) --}}
        <div id="content-super_admin" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/40 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-brand-forest"></div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Daftar Admin Utama (Super Admin)</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Memiliki hak akses penuh tak terbatas ke semua modul, termasuk wewenang eksklusif Manajemen Pengguna.</p>
                </div>
                <span class="text-xs text-brand-forest font-bold bg-white px-3 py-1 rounded-full border border-emerald-200/60 shadow-xs">{{ $superAdmins->count() }} Akun</span>
            </div>
            @include('admin.users._user_table', ['users' => $superAdmins, 'role' => 'admin_utama'])
        </div>

        {{-- TAB 2: ADMIN TERBATAS (SUB ADMIN) --}}
        <div id="content-sub_admin" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-amber-50/30 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Daftar Admin Terbatas (Sub Admin)</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Hanya dapat mengakses fitur-fitur yang didelegasikan oleh Admin Utama. Fitur Pengaturan Pengguna otomatis tertutup.</p>
                </div>
                <span class="text-xs text-amber-900 font-bold bg-white px-3 py-1 rounded-full border border-amber-200/60 shadow-xs">{{ $subAdmins->count() }} Akun</span>
            </div>

            {{-- Mobile List Sub Admin --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($subAdmins as $user)
                    <div class="p-4 hover:bg-gray-50/60 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $user->username }}</div>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-amber-100 text-amber-900 border border-amber-200">
                                {{ count($user->permissions ?? []) }} Fitur Diizinkan
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mb-3 flex items-center gap-1.5">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-[11px] font-semibold text-gray-400 block mb-1">Akses Fitur:</span>
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->permissions ?? [] as $permKey)
                                    @php $permInfo = $availablePermissions[$permKey] ?? null; @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <i data-lucide="{{ $permInfo['icon'] ?? 'check' }}" class="w-3 h-3 text-emerald-600"></i>
                                        {{ $permInfo['label'] ?? $permKey }}
                                    </span>
                                @empty
                                    <span class="text-xs text-rose-500 italic">Belum ada fitur yang diizinkan</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 rounded-xl text-xs font-bold transition">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                <span>Atur Hak Akses</span>
                            </a>
                            <form id="del-subadmin-m-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete('del-subadmin-m-{{ $user->id }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-bold transition">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">Belum ada akun Admin Terbatas yang terdaftar.</div>
                @endforelse
            </div>

            {{-- Desktop Table Sub Admin --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/60">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Admin</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Modul Fitur yang Diizinkan</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($subAdmins as $user)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $user->username }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-lg">
                                        @forelse($user->permissions ?? [] as $permKey)
                                            @php $permInfo = $availablePermissions[$permKey] ?? null; @endphp
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                                                <i data-lucide="{{ $permInfo['icon'] ?? 'check' }}" class="w-3 h-3 text-emerald-600"></i>
                                                <span>{{ $permInfo['label'] ?? $permKey }}</span>
                                            </span>
                                        @empty
                                            <span class="inline-flex items-center gap-1 text-xs text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                                                <i data-lucide="alert-circle" class="w-3 h-3"></i> Belum ada izin fitur
                                            </span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-emerald-800 hover:text-emerald-950 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg border border-emerald-200 transition">
                                            <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                                            <span>Atur Akses</span>
                                        </a>
                                        <form id="del-subadmin-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete('del-subadmin-{{ $user->id }}')"
                                                class="text-xs font-bold text-rose-700 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">Belum ada akun Admin Terbatas yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: JURI --}}
        <div id="content-juri" class="space-y-6 hidden">
            {{-- Card 1: Daftar Mata Lomba & Kode Akses --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-amber-50/40 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <h2 class="text-sm font-bold text-amber-950 uppercase tracking-wider">Kode Akses Penilaian Lomba</h2>
                    </div>
                    <span class="text-xs text-amber-800/80 font-medium">Digunakan Juri untuk sinkronisasi penilaian</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/60">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mata Lomba</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-36">Kode Verifikasi</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($allMataLomba as $ml)
                                <tr class="hover:bg-gray-50/60 transition-colors" id="kode-row-{{ $ml->id }}">
                                    {{-- Display Mode --}}
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 display-kode-{{ $ml->id }}">
                                        {{ $ml->nama }}
                                    </td>
                                    <td class="px-6 py-4 text-center display-kode-{{ $ml->id }}">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-mono font-bold rounded-lg bg-amber-100 text-amber-900 tracking-wider border border-amber-200/80 shadow-xs">
                                            {{ $ml->kode ?? '------' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap display-kode-{{ $ml->id }}">
                                        <button type="button" onclick="toggleEditKode({{ $ml->id }})"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-amber-800 hover:text-amber-950 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg border border-amber-200/60 transition">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            <span>Ubah Kode</span>
                                        </button>
                                    </td>

                                    {{-- Edit Mode --}}
                                    <td class="px-6 py-3 edit-kode-{{ $ml->id }}" style="display:none">
                                        <form id="edit-form-{{ $ml->id }}" action="{{ route('admin.mata-lomba.update', $ml) }}" method="POST" class="hidden">
                                            @csrf @method('PUT')
                                        </form>
                                        <input type="text" name="nama" form="edit-form-{{ $ml->id }}" value="{{ $ml->nama }}" required
                                            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                    </td>
                                    <td class="px-6 py-3 text-center edit-kode-{{ $ml->id }}" style="display:none">
                                        <input type="text" name="kode" form="edit-form-{{ $ml->id }}" value="{{ $ml->kode }}" maxlength="6" minlength="6"
                                            pattern="[A-Za-z0-9]{6}" required
                                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                            class="w-28 mx-auto block px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-mono font-bold uppercase tracking-widest text-center focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                    </td>
                                    <td class="px-6 py-3 text-right edit-kode-{{ $ml->id }}" style="display:none">
                                        <button type="submit" form="edit-form-{{ $ml->id }}"
                                            class="text-xs font-bold text-emerald-950 bg-amber-400 hover:bg-amber-500 px-3 py-1.5 rounded-lg transition shadow-xs mr-1">
                                            Simpan
                                        </button>
                                        <button type="button" onclick="toggleEditKode({{ $ml->id }})"
                                            class="text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">
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
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Daftar Akun Juri</h2>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">{{ $juris->count() }} Akun Terdaftar</span>
                </div>

                {{-- Mobile List --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse($juris as $user)
                        <div class="p-4 hover:bg-gray-50/60 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $user->username }}</div>
                                </div>
                                <div class="flex flex-wrap gap-1 justify-end max-w-[50%]">
                                    @forelse($user->mataLombas as $ml)
                                        <span class="px-2 py-0.5 text-[11px] font-bold font-mono rounded-md bg-amber-50 text-amber-900 border border-amber-200">
                                            {{ $ml->kode }}
                                        </span>
                                    @empty
                                        <span class="px-2 py-0.5 text-[10px] font-medium rounded-md bg-gray-100 text-gray-500">
                                            Belum ada tugas
                                        </span>
                                    @endforelse
                                </div>
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
                                <form id="del-juri-m-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('del-juri-m-{{ $user->id }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-bold transition">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 text-sm">Belum ada akun juri yang terdaftar.</div>
                    @endforelse
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/60">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Juri</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penugasan Lomba</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($juris as $user)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $user->username }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($user->mataLombas as $ml)
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold font-mono rounded-md bg-amber-50 text-amber-900 border border-amber-200">
                                                    {{ $ml->kode }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-xs font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition">
                                                Edit
                                            </a>
                                            <form id="del-juri-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete('del-juri-{{ $user->id }}')"
                                                    class="text-xs font-bold text-rose-700 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">Belum ada akun juri yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB: REGU --}}
        <div id="content-regu" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-600"></div>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Daftar Akun Regu Peserta</h2>
                </div>
                <span class="text-xs text-gray-500 font-medium">{{ $regus->count() }} Akun Terdaftar</span>
            </div>
            @include('admin.users._user_table', ['users' => $regus, 'role' => 'regu'])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showUserTab(tab) {
        ['super_admin', 'sub_admin', 'juri', 'regu'].forEach(t => {
            const content = document.getElementById('content-' + t);
            const btn = document.getElementById('tab-' + t);
            const badge = document.getElementById('badge-' + t);

            if (content) content.classList.toggle('hidden', t !== tab);

            if (btn) {
                if (t === tab) {
                    btn.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all bg-brand-forest text-white shadow-sm whitespace-nowrap';
                    if (badge) badge.className = 'ml-1 bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-bold';
                } else {
                    btn.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50 whitespace-nowrap';
                    if (badge) badge.className = 'ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold';
                }
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

    function confirmDelete(formId) {
        if (confirm('Apakah Anda yakin ingin menghapus akun pengguna ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush
