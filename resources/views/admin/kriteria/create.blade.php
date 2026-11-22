@extends('layouts.main')

@section('title', 'Tambah Kriteria: ' . $mataLomba->nama . ' - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500">
                        <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}" class="hover:text-brand-forest transition font-medium">{{ $mataLomba->nama }}</a>
                        <span>/</span>
                        <span class="text-brand-forest font-bold">Tambah Kriteria</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-brand-forest uppercase tracking-tight">
                        Tambah Kriteria Baru
                    </h1>
                </div>
                <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">
                <form action="{{ route('admin.kriteria.store', $mataLomba->id) }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Nama Kriteria -->
                    <div>
                        <label for="nama_kriteria" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nama Kriteria <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kriteria" id="nama_kriteria" value="{{ old('nama_kriteria') }}"
                            required autofocus
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 text-sm font-medium transition @error('nama_kriteria') border-red-500 @enderror"
                            placeholder="Contoh: Kesesuaian Simpul & Ikatan, Kerapian">
                        @error('nama_kriteria')
                            <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nilai Min -->
                        <div>
                            <label for="nilai_min" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nilai Minimal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="nilai_min" id="nilai_min"
                                value="{{ old('nilai_min', 0) }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold transition @error('nilai_min') border-red-500 @enderror"
                                placeholder="0">
                            @error('nilai_min')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nilai Max -->
                        <div>
                            <label for="nilai_max" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Nilai Maksimal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="nilai_max" id="nilai_max"
                                value="{{ old('nilai_max', 100) }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold transition @error('nilai_max') border-red-500 @enderror"
                                placeholder="100">
                            @error('nilai_max')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label for="urutan" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nomor Urut Kriteria
                        </label>
                        <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" min="1"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold transition @error('urutan') border-red-500 @enderror">
                        <p class="text-[11px] text-gray-400 mt-1">Digunakan untuk menyortir urutan kolom lembar nilai juri.</p>
                        @error('urutan')
                            <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2.5">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold text-xs bg-brand-forest hover:bg-emerald-900 text-white shadow-sm flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="save" class="w-4 h-4 text-amber-400"></i> Simpan Kriteria
                        </button>
                        <a href="{{ route('admin.kriteria.show', $mataLomba->id) }}"
                            class="px-5 py-2.5 rounded-xl font-semibold text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 text-center transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection