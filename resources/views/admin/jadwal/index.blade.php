@extends('layouts.main')

@section('title', 'Kelola Jadwal - Admin LTI 2026')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-scout-primary flex items-center gap-3">
                    <i data-lucide="calendar" class="w-8 h-8"></i>
                    Kelola Jadwal Kegiatan
                </h1>
                <p class="text-scout-primary/70 mt-1 font-medium italic">Atur rundown acara LT-I Spencerone Camp 2026</p>
            </div>
            <a href="{{ route('admin.jadwal.create') }}"
                class="btn-scout-primary px-6 py-3 rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-scout-primary/20">
                <i data-lucide="plus" class="w-5 h-5"></i> Tambah Rundown
            </a>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r-lg shadow-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Global Event Settings -->
        <div
            class="card-scout rounded-2xl p-6 sm:p-8 mb-10 border-2 border-scout-accent/30 shadow-lg shadow-scout-accent/5">
            <h2 class="text-xl font-bold text-scout-primary mb-6 flex items-center gap-2">
                <i data-lucide="settings-2" class="w-6 h-6 text-scout-accent"></i>
                Pengaturan Utama Kegiatan
            </h2>
            <form action="{{ route('admin.jadwal.settings') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="event_start_date" class="block text-sm font-bold text-scout-primary mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_start_date" id="event_start_date"
                            value="{{ old('event_start_date', $settings['event_start_date'] ?? '2026-04-24') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('event_start_date') border-red-500 @enderror">
                        @error('event_start_date')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event_end_date" class="block text-sm font-bold text-scout-primary mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_end_date" id="event_end_date"
                            value="{{ old('event_end_date', $settings['event_end_date'] ?? '2026-04-25') }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('event_end_date') border-red-500 @enderror">
                        @error('event_end_date')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event_location" class="block text-sm font-bold text-scout-primary mb-2">
                            Lokasi Utama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="event_location" id="event_location"
                            value="{{ old('event_location', $settings['event_location'] ?? 'SMP Negeri 1 Cerbon') }}"
                            required
                            class="w-full px-4 py-3 rounded-xl border-2 border-scout-secondary/50 focus:border-scout-primary focus:ring-0 bg-white text-scout-primary transition-all @error('event_location') border-red-500 @enderror"
                            placeholder="Contoh: SMP Negeri 1 Cerbon">
                        @error('event_location')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="btn-scout-accent px-8 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-scout-accent/20 hover:scale-[1.02] transition-transform">
                        <i data-lucide="save" class="w-5 h-5"></i> Simpan Pengaturan Utama
                    </button>
                </div>
            </form>
        </div>

        <!-- Jadwal Table/Cards -->
        <div class="card-scout rounded-2xl overflow-hidden border-2 border-scout-secondary/20">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-scout-primary text-white">
                            <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Hari Ke</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Kegiatan</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 font-bold text-sm uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-scout-secondary/20">
                        @forelse($jadwals as $j)
                            <tr class="hover:bg-scout-surface/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-scout-accent text-scout-primary font-bold rounded-lg">{{ $j->hari_ke }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-scout-primary">
                                    {{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                    {{ substr($j->waktu_mulai, 0, 5) }}
                                    @if($j->waktu_selesai) - {{ substr($j->waktu_selesai, 0, 5) }} @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-scout-primary">{{ $j->kegiatan }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 italic">
                                    {{ $j->lokasi ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.jadwal.edit', $j->id) }}"
                                            class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                            title="Edit">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $j->id }}', '{{ $j->kegiatan }}')"
                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                            title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                        <form id="delete-form-{{ $j->id }}" action="{{ route('admin.jadwal.destroy', $j->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-scout-primary/50">
                                        <i data-lucide="calendar-x" class="w-12 h-12 mb-3 opacity-20"></i>
                                        <p class="font-bold italic">Belum ada jadwal yang diinput.</p>
                                        <a href="{{ route('admin.jadwal.create') }}"
                                            class="mt-4 text-scout-primary font-bold hover:underline">Tambah Sekarang</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile view for Schedule (cards) -->
    <div class="md:hidden px-4 pb-12 space-y-4">
        @foreach($jadwals as $j)
            <div class="card-scout rounded-xl p-4 border border-scout-secondary/30 relative">
                <div class="flex justify-between items-start mb-2">
                    <span class="bg-scout-accent text-scout-primary text-[10px] font-bold px-2 py-1 rounded">Hari
                        {{ $j->hari_ke }}</span>
                    <span
                        class="text-xs font-semibold text-gray-500 italic">{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}</span>
                </div>
                <div class="text-xs text-scout-primary font-bold mb-1 flex items-center gap-1">
                    <i data-lucide="clock" class="w-3 h-3 text-scout-accent"></i>
                    {{ substr($j->waktu_mulai, 0, 5) }} @if($j->waktu_selesai) - {{ substr($j->waktu_selesai, 0, 5) }} @endif
                </div>
                <h4 class="font-bold text-scout-primary text-lg leading-tight mb-1">{{ $j->kegiatan }}</h4>
                <div class="text-[10px] text-gray-500 mb-4 flex items-center gap-1 italic">
                    <i data-lucide="map-pin" class="w-3 h-3 text-scout-primary/40"></i>
                    {{ $j->lokasi ?? 'Lokasi belum ditentukan' }}
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.jadwal.edit', $j->id) }}"
                        class="flex-1 py-2 rounded-lg bg-blue-50 text-blue-600 text-center font-bold text-xs flex items-center justify-center gap-2 border border-blue-100">
                        <i data-lucide="edit-2" class="w-3 h-3"></i> Edit
                    </a>
                    <button type="button" onclick="confirmDelete('{{ $j->id }}', '{{ $j->kegiatan }}')"
                        class="flex-1 py-2 rounded-lg bg-red-50 text-red-600 text-center font-bold text-xs flex items-center justify-center gap-2 border border-red-100">
                        <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(id, kegiatan) {
                Swal.fire({
                    title: 'Hapus Agenda?',
                    text: "Anda akan menghapus " + kegiatan + " dari jadwal.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#002C4D',
                    cancelButtonColor: '#E6E6E6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-6 py-3',
                        cancelButton: 'rounded-xl font-bold px-6 py-3 text-scout-primary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
@endsection