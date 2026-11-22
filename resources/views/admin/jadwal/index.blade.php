@extends('layouts.main')

@section('title', 'Kelola Jadwal - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Rundown & Waktu Kegiatan
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Kelola Jadwal Kegiatan
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Atur rundown acara, tanggal pelaksanaan, dan hitungan mundur Spencerone Camp Season II.</p>
                </div>
                <a href="{{ route('admin.jadwal.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-sm transition self-start md:self-auto">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Rundown</span>
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold rounded-2xl shadow-2xs flex items-center gap-3 text-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Global Event Settings Card -->
            <div class="bg-white rounded-2xl p-5 sm:p-6 mb-8 border border-gray-200/80 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 text-brand-forest">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center border border-emerald-200/50">
                        <i data-lucide="settings-2" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Pengaturan Utama & Tanggal Kegiatan</h2>
                        <p class="text-xs text-gray-400">Sinkronisasi tanggal perkemahan dan banner hitungan mundur beranda</p>
                    </div>
                </div>

                <form action="{{ route('admin.jadwal.settings') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="event_start_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="event_start_date" id="event_start_date"
                                value="{{ old('event_start_date', $settings['event_start_date'] ?? '2026-04-24') }}" required
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition @error('event_start_date') border-red-500 @enderror">
                            @error('event_start_date')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="event_end_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="event_end_date" id="event_end_date"
                                value="{{ old('event_end_date', $settings['event_end_date'] ?? '2026-04-25') }}" required
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition @error('event_end_date') border-red-500 @enderror">
                            @error('event_end_date')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="event_location" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                Lokasi Utama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="event_location" id="event_location"
                                value="{{ old('event_location', $settings['event_location'] ?? 'Bumi Perkemahan Spencerone') }}" required
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition @error('event_location') border-red-500 @enderror"
                                placeholder="Contoh: Bumi Perkemahan Spencerone">
                            @error('event_location')
                                <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                            <i data-lucide="save" class="w-3.5 h-3.5 text-amber-400"></i> Simpan Tanggal & Lokasi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Schedule List -->
            <div class="space-y-3.5 mb-10">
                @forelse($jadwals as $j)
                    <div class="bg-white rounded-2xl p-4 sm:p-5 hover:border-emerald-500/40 transition border border-gray-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <!-- Left Block: Day & Time -->
                        <div class="flex items-center gap-3.5 shrink-0">
                            <div class="flex flex-col items-center justify-center w-12 h-12 bg-emerald-50 text-brand-forest rounded-xl border border-emerald-200/50 shrink-0">
                                <span class="text-[9px] font-black uppercase tracking-wider text-gray-400 leading-none">Hari</span>
                                <span class="text-lg font-black text-brand-forest leading-tight">{{ $j->hari_ke }}</span>
                            </div>
                            
                            <div>
                                <div class="text-xs font-bold text-gray-400">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="text-sm font-black text-gray-900 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>{{ substr($j->waktu_mulai, 0, 5) }} @if($j->waktu_selesai) - {{ substr($j->waktu_selesai, 0, 5) }} @endif</span>
                                </div>
                            </div>
                        </div>

                        <!-- Center: Activity Title & Location -->
                        <div class="flex-1 min-w-0 md:px-4 md:border-l md:border-gray-100">
                            <h4 class="font-bold text-gray-900 text-sm sm:text-base leading-snug">{{ $j->kegiatan }}</h4>
                            <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-emerald-600 shrink-0"></i>
                                <span class="truncate">{{ $j->lokasi ?? 'Lokasi belum ditentukan' }}</span>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center gap-2 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                            <a href="{{ route('admin.jadwal.edit', $j->id) }}"
                                class="px-3 py-1.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold text-xs flex items-center gap-1 border border-gray-200 transition">
                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> <span>Edit</span>
                            </a>
                            <button type="button" onclick="confirmDeleteJadwal('{{ $j->id }}', '{{ addslashes($j->kegiatan) }}')"
                                class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs flex items-center gap-1 border border-red-200/60 transition">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> <span>Hapus</span>
                            </button>
                            <form id="delete-form-{{ $j->id }}" action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                        <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                        <h3 class="text-sm font-bold text-gray-700 mb-1">Jadwal Masih Kosong</h3>
                        <p class="text-xs text-gray-400">Belum ada agenda rundown yang dimasukkan.</p>
                        <a href="{{ route('admin.jadwal.create') }}"
                            class="inline-block mt-4 px-4 py-2 bg-brand-forest text-white rounded-xl text-xs font-bold shadow-xs">
                            Tambah Rundown Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDeleteJadwal(id, kegiatan) {
                if (confirm(`Hapus agenda "${kegiatan}" dari rundown jadwal?`)) {
                    document.getElementById('delete-form-' + id).submit();
                }
            }
        </script>
    @endpush
@endsection