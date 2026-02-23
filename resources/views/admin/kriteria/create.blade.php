@extends('layouts.main')

@section('title', 'Tambah Kriteria: ' . $mataLomba->nama . ' - Admin LTI 2026')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.kriteria.index') }}"
                    class="text-scout-primary/70 hover:text-scout-accent transition-colors font-semibold text-sm flex items-center gap-1">
                    Kelola Kriteria
                </a>
                <span class="text-scout-primary/50 text-sm">/</span>
                <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}"
                    class="text-scout-primary/70 hover:text-scout-accent transition-colors font-semibold text-sm flex items-center gap-1">
                    {{ $mataLomba->nama }}
                </a>
                <span class="text-scout-primary/50 text-sm">/</span>
                <span class="text-scout-primary text-sm font-bold">Tambah Kriteria</span>
            </div>
            <h1 class="text-3xl font-bold text-scout-primary flex items-center gap-3">
                <i data-lucide="plus-circle" class="w-8 h-8"></i>
                Tambah Kriteria Baru
            </h1>
            <p class="text-scout-primary/80 mt-1 text-sm font-medium">Lomba: <strong>{{ $mataLomba->nama }}</strong></p>
        </div>

        <!-- Form -->
        <div class="card-scout rounded-xl p-6 sm:p-8">
            <form action="{{ route('admin.kriteria.store', $mataLomba->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Nama Kriteria -->
                    <div>
                        <label for="nama_kriteria" class="block text-sm font-bold text-scout-primary mb-2">
                            Nama Kriteria <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kriteria" id="nama_kriteria" value="{{ old('nama_kriteria') }}"
                            required autofocus
                            class="w-full px-4 py-3 rounded-lg border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-colors @error('nama_kriteria') border-red-500 @enderror"
                            placeholder="Contoh: Kesesuaian Tema, Kreativitas, dsb.">
                        @error('nama_kriteria')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nilai Min -->
                        <div>
                            <label for="nilai_min" class="block text-sm font-bold text-scout-primary mb-2">
                                Nilai Minimal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="nilai_min" id="nilai_min"
                                value="{{ old('nilai_min', 0) }}" required
                                class="w-full px-4 py-3 rounded-lg border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-colors @error('nilai_min') border-red-500 @enderror"
                                placeholder="0">
                            @error('nilai_min')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nilai Max -->
                        <div>
                            <label for="nilai_max" class="block text-sm font-bold text-scout-primary mb-2">
                                Nilai Maksimal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="nilai_max" id="nilai_max"
                                value="{{ old('nilai_max', 100) }}" required
                                class="w-full px-4 py-3 rounded-lg border-2 border-scout-secondary/50 focus:border-scout-accent focus:ring-0 bg-white text-scout-primary transition-colors @error('nilai_max') border-red-500 @enderror"
                                placeholder="100">
                            @error('nilai_max')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label for="urutan" class="block text-sm font-bold text-scout-primary mb-2">
                            Urutan Tampilan
                        </label>
                        <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" min="0"
                            class="w-full px-4 py-3 rounded-lg border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-colors @error('urutan') border-red-500 @enderror"
                            placeholder="Contoh: 1">
                        <p class="text-xs text-scout-primary/60 mt-1 font-medium">Bisa digunakan untuk mengurutkan kriteria
                            (Kecil ke Besar).</p>
                        @error('urutan')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex flex-col md:flex-row gap-3 pt-6 border-t border-scout-primary/10">
                    <button type="submit"
                        class="w-full md:w-auto btn-scout-accent px-8 py-3 rounded-lg font-bold flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i> Simpan Kriteria
                    </button>
                    <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}"
                        class="w-full md:w-auto px-8 py-3 rounded-lg font-bold bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary text-center transition flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection