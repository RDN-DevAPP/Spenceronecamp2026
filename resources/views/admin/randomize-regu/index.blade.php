@extends('layouts.main')

@section('title', 'Pengacakan Regu - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pengacakan Regu</h1>
                <a href="{{ route('admin.dashboard') }}" class="text-scout-primary hover:underline text-sm font-medium">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Putra Total</div>
                    <div class="text-2xl font-black text-scout-primary">{{ $totalPutra }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Putri Total</div>
                    <div class="text-2xl font-black text-scout-accent">{{ $totalPutri }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Putra per Kelas</div>
                    <div class="text-sm text-gray-700">
                        K7: <strong>{{ $countPutraPerKelas[7] }}</strong> •
                        K8: <strong>{{ $countPutraPerKelas[8] }}</strong> •
                        K9: <strong>{{ $countPutraPerKelas[9] }}</strong>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Putri per Kelas</div>
                    <div class="text-sm text-gray-700">
                        K7: <strong>{{ $countPutriPerKelas[7] }}</strong> •
                        K8: <strong>{{ $countPutriPerKelas[8] }}</strong> •
                        K9: <strong>{{ $countPutriPerKelas[9] }}</strong>
                    </div>
                </div>
            </div>

            @if(!$isRandomized)
                <!-- Randomize Form -->
                <div class="bg-white rounded-xl shadow-md p-6 border-2 border-scout-secondary/30 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="shuffle" class="w-5 h-5 mr-2 text-scout-primary"></i>
                        Acak Regu Baru
                    </h2>
                    <p class="text-sm text-gray-500 mb-6">
                        Tentukan jumlah regu putra dan putri yang ingin dibuat. Sistem akan mendistribusikan siswa secara acak
                        dengan ketentuan: putra & putri terpisah, setiap regu ada dari tiap kelas, pembagian rata, min 7 – maks
                        10 anggota.
                    </p>
                    <form action="{{ route('admin.randomize-regu.randomize') }}" method="POST" id="randomize-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i data-lucide="shield" class="w-4 h-4 inline mr-1 text-scout-primary"></i>
                                    Jumlah Regu Putra
                                </label>
                                <input type="number" name="jumlah_regu_putra" required min="1" max="20"
                                    value="{{ old('jumlah_regu_putra', 4) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-scout-primary/30 focus:border-scout-primary transition text-lg font-bold text-center">
                                <p class="text-xs text-gray-400 mt-1 text-center">{{ $totalPutra }} siswa tersedia</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <i data-lucide="flower" class="w-4 h-4 inline mr-1 text-scout-accent"></i>
                                    Jumlah Regu Putri
                                </label>
                                <input type="number" name="jumlah_regu_putri" required min="1" max="20"
                                    value="{{ old('jumlah_regu_putri', 4) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-scout-accent/30 focus:border-scout-accent transition text-lg font-bold text-center">
                                <p class="text-xs text-gray-400 mt-1 text-center">{{ $totalPutri }} siswa tersedia</p>
                            </div>
                        </div>
                        <button type="button" onclick="confirmRandomize()"
                            class="w-full py-4 bg-gradient-to-r from-scout-primary to-amber-600 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                            <i data-lucide="shuffle" class="w-5 h-5 inline mr-2"></i>
                            ACAK REGU SEKARANG
                        </button>
                    </form>
                </div>
            @else
                <!-- Reset Button -->
                <div class="bg-white rounded-xl shadow-md p-6 border-2 border-orange-200 mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-500"></i>
                                Regu Sudah Diacak
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">Hasil pengacakan ditampilkan di bawah dan di halaman publik.
                            </p>
                        </div>
                        <form action="{{ route('admin.randomize-regu.reset') }}" method="POST" id="reset-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmReset()"
                                class="px-6 py-2.5 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition shadow-sm">
                                <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-1"></i> Reset & Acak Ulang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Display Results -->
                <div class="space-y-8">
                    <!-- Regu Putra -->
                    @if($reguPutra->count() > 0)
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="shield" class="w-5 h-5 mr-2 text-scout-primary"></i>
                                Regu Putra ({{ $reguPutra->count() }} regu)
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($reguPutra as $regu)
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-scout-primary to-scout-primary/80 px-4 py-3">
                                            <h3 class="text-white font-bold">{{ $regu->nama_regu }}</h3>
                                            <p class="text-white/80 text-xs">{{ $regu->anggotaRegu->count() }} anggota</p>
                                        </div>
                                        <div class="p-3">
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="text-xs text-gray-500">
                                                        <th class="px-2 py-1 text-left">#</th>
                                                        <th class="px-2 py-1 text-left">Nama</th>
                                                        <th class="px-2 py-1 text-left text-center">Kelas</th>
                                                        <th class="px-2 py-1 text-left">Jabatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-50">
                                                    @foreach($regu->anggotaRegu->sortBy('urutan') as $a)
                                                        <tr class="hover:bg-scout-surface">
                                                            <td class="px-2 py-1.5 text-gray-500">{{ $a->urutan }}</td>
                                                            <td class="px-2 py-1.5 font-medium text-gray-900">{{ $a->nama }}</td>
                                                            <td class="px-2 py-1.5 text-center font-bold text-scout-primary">
                                                                {{ $a->kelas ?? '-' }}</td>
                                                            <td class="px-2 py-1.5">
                                                                @if($a->jabatan === 'pinru')
                                                                    <span class="text-xs font-bold text-amber-600">PINRU</span>
                                                                @elseif($a->jabatan === 'wapinru')
                                                                    <span class="text-xs font-bold text-amber-500">WAPINRU</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400">Anggota</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Regu Putri -->
                    @if($reguPutri->count() > 0)
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="flower" class="w-5 h-5 mr-2 text-scout-accent"></i>
                                Regu Putri ({{ $reguPutri->count() }} regu)
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($reguPutri as $regu)
                                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                                        <div class="bg-gradient-to-r from-scout-accent to-amber-600 px-4 py-3">
                                            <h3 class="text-white font-bold">{{ $regu->nama_regu }}</h3>
                                            <p class="text-white/80 text-xs">{{ $regu->anggotaRegu->count() }} anggota</p>
                                        </div>
                                        <div class="p-3">
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="text-xs text-gray-500">
                                                        <th class="px-2 py-1 text-left">#</th>
                                                        <th class="px-2 py-1 text-left">Nama</th>
                                                        <th class="px-2 py-1 text-left text-center">Kelas</th>
                                                        <th class="px-2 py-1 text-left">Jabatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-50">
                                                    @foreach($regu->anggotaRegu->sortBy('urutan') as $a)
                                                        <tr class="hover:bg-amber-50/30">
                                                            <td class="px-2 py-1.5 text-gray-500">{{ $a->urutan }}</td>
                                                            <td class="px-2 py-1.5 font-medium text-gray-900">{{ $a->nama }}</td>
                                                            <td class="px-2 py-1.5 text-center font-bold text-scout-accent">
                                                                {{ $a->kelas ?? '-' }}</td>
                                                            <td class="px-2 py-1.5">
                                                                @if($a->jabatan === 'pinru')
                                                                    <span class="text-xs font-bold text-amber-600">PINRU</span>
                                                                @elseif($a->jabatan === 'wapinru')
                                                                    <span class="text-xs font-bold text-amber-500">WAPINRU</span>
                                                                @else
                                                                    <span class="text-xs text-gray-400">Anggota</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmRandomize() {
            Swal.fire({
                title: 'Acak Regu?',
                text: 'Sistem akan mengacak anggota regu secara otomatis. Pastikan semua data siswa sudah lengkap.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D4037',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Acak!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('randomize-form').submit();
                }
            });
        }

        function confirmReset() {
            Swal.fire({
                title: 'Reset Pengacakan?',
                text: 'Semua data regu dan anggota regu akan dihapus. Tindakan ini tidak dapat dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-form').submit();
                }
            });
        }
    </script>
@endpush