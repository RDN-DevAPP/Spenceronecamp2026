@extends('layouts.main')

@section('title', 'Edit Pengguna - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-brand-forest transition mb-2">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Manajemen Pengguna</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Edit Pengguna</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui data akun {{ $user->name }} ({{ strtoupper($user->role) }}).</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Section: Informasi Akun --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-800">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Informasi Akun</h3>
                        <p class="text-xs text-gray-500">Data identitas utama pengguna</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-medium transition"
                            required>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-mono transition"
                            required>
                        @error('username')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-medium transition"
                            required>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section: Keamanan --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-800">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ubah Kata Sandi</h3>
                        <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin memperbarui password pengguna</p>
                    </div>
                </div>
                <div class="p-6">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Baru</label>
                        <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak diubah"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-medium transition">
                        <p class="text-xs text-gray-400 mt-1.5">Minimal 8 karakter jika ingin mengubah password.</p>
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section: Pengaturan Role & Hak Akses --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-brand-forest/10 flex items-center justify-center text-brand-forest">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Pengaturan Role & Hak Akses</h3>
                        <p class="text-xs text-gray-500">Kelola level otorisasi sistem dan hak akses fitur</p>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Role Otorisasi</label>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <div class="w-full px-4 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50/40 text-emerald-900 text-sm font-bold flex items-center justify-between">
                                <span>Admin Utama (Akun Anda Sedang Digunakan)</span>
                                <span class="text-xs bg-emerald-200/70 text-emerald-900 px-2 py-0.5 rounded font-mono">Terkunci</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1.5">Anda tidak dapat mengubah role akun Admin Utama Anda sendiri demi keamanan sistem.</p>
                        @else
                            <select name="role" id="role" onchange="handleRoleChange()"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-semibold transition"
                                required>
                                <option value="">-- Pilih Role Akses --</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin Utama (Akses Penuh &amp; Pengaturan Pengguna)</option>
                                <option value="sub_admin" {{ old('role', $user->role) == 'sub_admin' ? 'selected' : '' }}>Admin Terbatas / Khusus (Hanya Fitur Terpilih)</option>
                                <option value="juri" {{ old('role', $user->role) == 'juri' ? 'selected' : '' }}>Juri (Penilaian Lomba)</option>
                                <option value="regu" {{ old('role', $user->role) == 'regu' ? 'selected' : '' }}>Regu (Peserta Lomba)</option>
                            </select>
                        @endif
                        @error('role')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Delegasi Fitur Khusus untuk Sub Admin --}}
                    @php
                        $userPerms = is_array($user->permissions) ? $user->permissions : [];
                        $activePerms = old('permissions', $userPerms);
                    @endphp
                    <div id="sub-admin-permissions" style="{{ old('role', $user->role) == 'sub_admin' ? '' : 'display:none' }}" class="space-y-4 pt-4 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-emerald-50/50 border border-emerald-200/60 p-4 rounded-xl">
                            <div>
                                <h4 class="text-xs font-bold text-emerald-950 uppercase tracking-wider flex items-center gap-1.5">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-700"></i>
                                    Delegasi Fitur Admin Terbatas
                                </h4>
                                <p class="text-xs text-emerald-800/80 mt-0.5">Atur modul apa saja yang dapat diakses oleh admin ini.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="selectAllPerms(true)"
                                    class="px-2.5 py-1 text-xs font-bold bg-white text-emerald-800 border border-emerald-300 rounded-lg hover:bg-emerald-100 transition shadow-xs">
                                    Pilih Semua
                                </button>
                                <button type="button" onclick="selectAllPerms(false)"
                                    class="px-2.5 py-1 text-xs font-bold bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 transition shadow-xs">
                                    Hapus Semua
                                </button>
                            </div>
                        </div>

                        {{-- Notice Keamanan User Management --}}
                        <div class="bg-amber-50 border border-amber-200/80 p-3.5 rounded-xl flex items-start gap-2.5 text-xs text-amber-900">
                            <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                            <div>
                                <span class="font-bold">Keamanan Terpusat:</span> Modul <strong>Pengaturan Pengguna (Kelola Akun)</strong> tidak dapat didelegasikan dan secara mutlak hanya dapat diakses oleh <strong>Admin Utama</strong>.
                            </div>
                        </div>

                        {{-- Daftar Fitur Berdasarkan Kategori --}}
                        @php
                            $grouped = [];
                            foreach ($availablePermissions as $key => $perm) {
                                $cat = $perm['category'] ?? 'Lainnya';
                                $grouped[$cat][$key] = $perm;
                            }
                        @endphp

                        <div class="space-y-4">
                            @foreach($grouped as $category => $items)
                                <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
                                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                                        <span>Kategori: {{ $category }}</span>
                                    </div>
                                    <div class="p-3 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach($items as $key => $item)
                                            <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 hover:border-emerald-300 hover:bg-emerald-50/20 cursor-pointer transition">
                                                <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                                    {{ in_array($key, $activePerms) ? 'checked' : '' }}
                                                    class="perm-checkbox w-4 h-4 mt-0.5 rounded text-brand-forest focus:ring-emerald-500 border-gray-300">
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                                        <i data-lucide="{{ $item['icon'] ?? 'check' }}" class="w-3.5 h-3.5 text-brand-forest"></i>
                                                        <span>{{ $item['label'] }}</span>
                                                    </div>
                                                    <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">{{ $item['description'] }}</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mata Lomba untuk Juri --}}
                    <div id="kode-lomba-group" style="{{ old('role', $user->role) == 'juri' ? '' : 'display:none' }}" class="space-y-3 pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Mata Lomba yang Ditugaskan ke Juri</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-64 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50/50">
                            @foreach($allMataLomba ?? [] as $ml)
                                <label class="flex items-center gap-3 p-3 rounded-xl bg-white border border-gray-200 hover:border-emerald-400 cursor-pointer transition shadow-xs">
                                    <input type="checkbox" name="mata_lomba_ids[]" value="{{ $ml->id }}"
                                        {{ $user->mataLombas->contains($ml->id) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded text-brand-forest focus:ring-emerald-500 border-gray-300">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded bg-amber-100 text-amber-900 border border-amber-200/60 shrink-0">{{ $ml->kode }}</span>
                                            <span class="text-sm font-semibold text-gray-800 truncate">{{ $ml->nama }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400">Centang satu atau lebih lomba yang berhak dinilai oleh juri ini.</p>
                    </div>
                </div>
            </div>

            {{-- Form Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}"
                    class="px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-sm shadow-sm transition-all duration-200 hover:shadow-md">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleRoleChange() {
        const roleEl = document.getElementById('role');
        const role = roleEl ? roleEl.value : '{{ $user->role }}';
        const subAdminDiv = document.getElementById('sub-admin-permissions');
        const juriDiv = document.getElementById('kode-lomba-group');

        if (subAdminDiv) subAdminDiv.style.display = (role === 'sub_admin') ? '' : 'none';
        if (juriDiv) juriDiv.style.display = (role === 'juri') ? '' : 'none';

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function selectAllPerms(checked) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.checked = checked;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        handleRoleChange();
    });
</script>
@endpush
