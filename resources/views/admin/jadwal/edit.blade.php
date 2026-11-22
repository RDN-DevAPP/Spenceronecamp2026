@extends('layouts.main')

@section('title', 'Edit Agenda Jadwal - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500">
                        <a href="{{ route('admin.jadwal.index') }}" class="hover:text-brand-forest transition font-medium">Jadwal</a>
                        <span>/</span>
                        <span class="text-brand-forest font-bold">Edit Agenda</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-brand-forest uppercase tracking-tight">
                        Edit Agenda Kegiatan
                    </h1>
                </div>
                <a href="{{ route('admin.jadwal.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">
                <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Hari Ke -->
                        <div>
                            <label for="hari_ke" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Hari Ke- <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="hari_ke" id="hari_ke" value="{{ old('hari_ke', $jadwal->hari_ke) }}" min="1" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold text-center transition">
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Tanggal Pelaksanaan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}" required
                                min="{{ $settings['event_start_date'] ?? '' }}"
                                max="{{ $settings['event_end_date'] ?? '' }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Waktu Mulai -->
                        <div>
                            <label for="waktu_mulai" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai"
                                value="{{ old('waktu_mulai', substr($jadwal->waktu_mulai, 0, 5)) }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                        </div>

                        <!-- Waktu Selesai -->
                        <div>
                            <label for="waktu_selesai" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Waktu Selesai (Opsional)
                            </label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai"
                                value="{{ old('waktu_selesai', $jadwal->waktu_selesai ? substr($jadwal->waktu_selesai, 0, 5) : '') }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                        </div>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div>
                        <label for="kegiatan" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kegiatan" id="kegiatan" value="{{ old('kegiatan', $jadwal->kegiatan) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                    </div>

                    <!-- Lokasi Kegiatan -->
                    <div>
                        <label for="lokasi" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Lokasi Kegiatan
                        </label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $jadwal->lokasi) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-medium transition">
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2.5">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-bold text-xs bg-brand-forest hover:bg-emerald-900 text-white shadow-sm flex items-center justify-center gap-1.5 transition">
                            <i data-lucide="save" class="w-4 h-4 text-amber-400"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.jadwal.index') }}"
                            class="px-5 py-2.5 rounded-xl font-semibold text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 text-center transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection