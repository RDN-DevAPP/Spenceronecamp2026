@extends('layouts.main')

@section('title', 'Daftar Regu - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-scout-primary/10 mb-4">
                    <i data-lucide="users" class="w-8 h-8 text-scout-primary"></i>
                </div>
                <h1 class="text-3xl font-bold text-scout-primary mb-2">Daftar Regu</h1>
                <p class="text-gray-600">LT-I Spencerone Camp 2026</p>
            </div>

            <!-- Tabs Putra / Putri -->
            <div class="flex justify-center mb-8 space-x-4">
                <button onclick="showTab('putra')" id="tab-putra"
                    class="px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 bg-scout-primary text-white shadow-lg">
                    <i data-lucide="shield" class="w-4 h-4 inline mr-1"></i>
                    Regu Putra ({{ count($reguPutra) }})
                </button>
                <button onclick="showTab('putri')" id="tab-putri"
                    class="px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary">
                    <i data-lucide="flower" class="w-4 h-4 inline mr-1"></i>
                    Regu Putri ({{ count($reguPutri) }})
                </button>
            </div>

            <!-- Regu Putra -->
            <div id="content-putra" class="space-y-6">
                @forelse($reguPutra as $regu)
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-scout-secondary/30 hover:border-scout-primary/30 transition-all duration-300">
                        <div class="bg-gradient-to-r from-scout-primary to-scout-primary/80 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur">
                                        <span class="text-white font-bold text-lg">{{ $regu->nomor_regu }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-bold text-lg">{{ $regu->nama_regu }}</h3>
                                        <p class="text-white/80 text-xs">Regu Putra {{ $regu->nomor_regu }} •
                                            {{ $regu->anggotaRegu->count() }} anggota
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase tracking-wider">
                                        <th class="px-3 py-2 text-left font-medium">No</th>
                                        <th class="px-3 py-2 text-left font-medium">Nama</th>
                                        <th class="px-3 py-2 text-center font-medium">Kelas</th>
                                        <th class="px-3 py-2 text-left font-medium">Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($regu->anggotaRegu->sortBy('urutan') as $anggota)
                                        <tr class="hover:bg-scout-surface transition-colors">
                                            <td class="px-3 py-2.5 text-sm text-gray-600">{{ $anggota->urutan }}</td>
                                            <td class="px-3 py-2.5 text-sm font-medium text-gray-900">{{ $anggota->nama }}</td>
                                            <td class="px-3 py-2.5 text-sm text-center font-bold">{{ $anggota->kelas ?? '-' }}</td>
                                            <td class="px-3 py-2.5">
                                                @if($anggota->jabatan === 'pinru')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-scout-primary/10 text-scout-primary">
                                                        <i data-lucide="crown" class="w-3 h-3 mr-1"></i> PINRU
                                                    </span>
                                                @elseif($anggota->jabatan === 'wapinru')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-scout-secondary/20 text-scout-primary">
                                                        <i data-lucide="award" class="w-3 h-3 mr-1"></i> WAPINRU
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500">Anggota</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-md p-12 text-center border border-dashed border-gray-300">
                        <i data-lucide="users-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada regu putra</h3>
                        <p class="text-sm text-gray-500 mt-1">Regu putra belum didaftarkan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Regu Putri -->
            <div id="content-putri" class="space-y-6 hidden">
                @forelse($reguPutri as $regu)
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-scout-secondary/30 hover:border-scout-accent/50 transition-all duration-300">
                        <div class="bg-gradient-to-r from-scout-accent to-amber-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur">
                                        <span class="text-white font-bold text-lg">{{ $regu->nomor_regu }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-bold text-lg">{{ $regu->nama_regu }}</h3>
                                        <p class="text-white/80 text-xs">Regu Putri {{ $regu->nomor_regu }} •
                                            {{ $regu->anggotaRegu->count() }} anggota
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase tracking-wider">
                                        <th class="px-3 py-2 text-left font-medium">No</th>
                                        <th class="px-3 py-2 text-left font-medium">Nama</th>
                                        <th class="px-3 py-2 text-center font-medium">Kelas</th>
                                        <th class="px-3 py-2 text-left font-medium">Jabatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($regu->anggotaRegu->sortBy('urutan') as $anggota)
                                        <tr class="hover:bg-amber-50/30 transition-colors">
                                            <td class="px-3 py-2.5 text-sm text-gray-600">{{ $anggota->urutan }}</td>
                                            <td class="px-3 py-2.5 text-sm font-medium text-gray-900">{{ $anggota->nama }}</td>
                                            <td class="px-3 py-2.5 text-sm text-center font-bold">{{ $anggota->kelas ?? '-' }}</td>
                                            <td class="px-3 py-2.5">
                                                @if($anggota->jabatan === 'pinru')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                        <i data-lucide="crown" class="w-3 h-3 mr-1"></i> PINRU
                                                    </span>
                                                @elseif($anggota->jabatan === 'wapinru')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                                        <i data-lucide="award" class="w-3 h-3 mr-1"></i> WAPINRU
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500">Anggota</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-md p-12 text-center border border-dashed border-gray-300">
                        <i data-lucide="users-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada regu putri</h3>
                        <p class="text-sm text-gray-500 mt-1">Regu putri belum didaftarkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showTab(tab) {
            document.getElementById('content-putra').classList.toggle('hidden', tab !== 'putra');
            document.getElementById('content-putri').classList.toggle('hidden', tab !== 'putri');

            const tabPutra = document.getElementById('tab-putra');
            const tabPutri = document.getElementById('tab-putri');

            if (tab === 'putra') {
                tabPutra.className = 'px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 bg-scout-primary text-white shadow-lg';
                tabPutri.className = 'px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary';
            } else {
                tabPutri.className = 'px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 bg-scout-accent text-white shadow-lg';
                tabPutra.className = 'px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 bg-white text-scout-primary border-2 border-scout-secondary hover:border-scout-primary';
            }

            nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
        }
    </script>
@endpush