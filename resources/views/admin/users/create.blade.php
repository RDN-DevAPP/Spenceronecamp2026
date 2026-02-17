@extends('layouts.main')

@section('title', 'Tambah User - Admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-scout-primary">Tambah User Baru</h1>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary/50"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary/50"
                            required>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary/50"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary/50"
                            required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" id="role"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary/50"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="juri" {{ old('role') == 'juri' ? 'selected' : '' }}>Juri</option>
                            <option value="regu" {{ old('role') == 'regu' ? 'selected' : '' }}>Regu</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-scout-primary text-white rounded-lg hover:bg-scout-primary/90 transition">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection