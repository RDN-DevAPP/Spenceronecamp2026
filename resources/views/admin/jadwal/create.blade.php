@extends('layouts.main')

@section('title', 'Tambah Jadwal - Admin LTI 2026')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.jadwal.index') }}"
                    class="text-scout-primary/70 hover:text-scout-accent transition-colors font-semibold text-sm flex items-center gap-1">
                    Kelola Jadwal
                </a>
                <span class="text-scout-primary/50 text-sm">/</span>
                <span class="text-scout-primary text-sm font-bold">Tambah Baru</span>
            </div>
            <h1 class="text-3xl font-bold text-scout-primary flex items-center gap-3">
                <i data-lucide="calendar-plus" class="w-8 h-8 text-scout-accent"></i>
                Tambah Agenda Kegiatan
            </h1>
        </div>

        <div class="card-scout rounded-2xl p-6 sm:p-8">
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hari Ke -->
                        <div>
                            <label for="hari_ke" class="block text-sm font-bold text-scout-primary mb-2">
                                Hari Ke- <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="hari_ke" id="hari_ke" value="{{ old('hari_ke', 1) }}" min="1"
                                required
                                class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('hari_ke') border-red-500 @enderror">
                            @error('hari_ke')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-bold text-scout-primary mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required
                                min="{{ $settings['event_start_date'] ?? '' }}"
                                max="{{ $settings['event_end_date'] ?? '' }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('tanggal') border-red-500 @enderror">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Waktu Mulai -->
                        <div>
                            <label for="waktu_mulai" class="block text-sm font-bold text-scout-primary mb-2">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                                class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('waktu_mulai') border-red-500 @enderror">
                            @error('waktu_mulai')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu Selesai -->
                        <div>
                            <label for="waktu_selesai" class="block text-sm font-bold text-scout-primary mb-2">
                                Waktu Selesai
                            </label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai') }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('waktu_selesai') border-red-500 @enderror">
                            @error('waktu_selesai')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-scout-primary/40 italic">Kosongkan jika acara tentatif / sampai
                                selesai.</p>
                        </div>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div>
                        <label for="kegiatan" class="block text-sm font-bold text-scout-primary mb-2">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kegiatan" id="kegiatan" value="{{ old('kegiatan') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('kegiatan') border-red-500 @enderror"
                            placeholder="Contoh: Upacara Pembukaan, Lomba Masak, dsb.">
                        @error('kegiatan')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi Kegiatan -->
                    <div>
                        <label for="lokasi" class="block text-sm font-bold text-scout-primary mb-2">
                            Lokasi Kegiatan
                        </label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('lokasi') border-red-500 @enderror"
                            placeholder="Contoh: Lapangan Utama, Ruang Kelas A, dsb.">
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-10 flex flex-col md:flex-row gap-4 pt-8 border-t border-scout-primary/10">
                    <button type="submit"
                        class="w-full md:w-auto btn-scout-accent px-10 py-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-scout-accent/20 hover:scale-[1.02] transition-transform">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i> Tambah ke Jadwal
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}"
                        class="w-full md:w-auto px-10 py-4 rounded-xl font-bold bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary text-center transition flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection