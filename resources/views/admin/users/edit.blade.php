@extends('layouts.main')

@section('title', 'Edit User - Admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-scout-primary">Edit User</h1>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Section: Informasi Akun --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-scout-surface px-5 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-scout-primary flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i> Informasi Akun
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-scout-primary focus:ring-2 focus:ring-scout-primary/20 transition text-sm"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-scout-primary focus:ring-2 focus:ring-scout-primary/20 transition text-sm"
                                required>
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-scout-primary focus:ring-2 focus:ring-scout-primary/20 transition text-sm"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section: Keamanan --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-scout-surface px-5 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-scout-primary flex items-center gap-2">
                            <i data-lucide="lock" class="w-4 h-4"></i> Keamanan
                        </h3>
                    </div>
                    <div class="p-5">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-scout-primary focus:ring-2 focus:ring-scout-primary/20 transition text-sm">
                            <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah.</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section: Pengaturan Role --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-scout-surface px-5 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-scout-primary flex items-center gap-2">
                            <i data-lucide="shield" class="w-4 h-4"></i> Pengaturan Role
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role" onchange="toggleKodeLomba()"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-scout-primary focus:ring-2 focus:ring-scout-primary/20 transition text-sm"
                                required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="juri" {{ old('role', $user->role) == 'juri' ? 'selected' : '' }}>Juri</option>
                                <option value="regu" {{ old('role', $user->role) == 'regu' ? 'selected' : '' }}>Regu</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="kode-lomba-group" style="{{ old('role', $user->role) == 'juri' ? '' : 'display:none' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Lomba yang Ditugaskan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                @foreach($allMataLomba ?? [] as $ml)
                                    <label class="flex items-center gap-2.5 p-2.5 rounded-lg hover:bg-white cursor-pointer transition border border-transparent hover:border-gray-200">
                                        <input type="checkbox" name="mata_lomba_ids[]" value="{{ $ml->id }}"
                                            {{ $user->mataLombas->contains($ml->id) ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300 text-scout-accent focus:ring-scout-accent/30">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="px-1.5 py-0.5 text-[11px] font-bold font-mono rounded bg-amber-100 text-amber-800 shrink-0">{{ $ml->kode }}</span>
                                                <span class="text-sm font-medium text-gray-800 truncate">{{ $ml->nama }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-400 mt-1.5">Pilih satu atau lebih mata lomba untuk juri ini.</p>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-scout-primary text-white rounded-lg font-semibold text-sm hover:bg-scout-primary/90 transition shadow-sm">
                        <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleKodeLomba() {
            const role = document.getElementById('role').value;
            const group = document.getElementById('kode-lomba-group');
            group.style.display = role === 'juri' ? '' : 'none';
        }
    </script>
@endpush