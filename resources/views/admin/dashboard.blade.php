@extends('layouts.main')

@section('title', 'Admin Dashboard - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

            <!-- Cerdas Cermat Section -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i data-lucide="brain-circuit" class="w-6 h-6 mr-2 text-scout-primary"></i>
                    Manajemen Cerdas Cermat
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bank Soal Card -->
                    <div
                        class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300 group">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-indigo-50 p-3 rounded-lg group-hover:bg-indigo-100 transition-colors">
                                    <i data-lucide="book-open" class="w-8 h-8 text-indigo-600"></i>
                                </div>
                                <span
                                    class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full font-semibold">Bank
                                    Soal</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Bank Soal</h3>
                            <p class="text-gray-500 text-sm mb-6">Kelola database pertanyaan, kategori, dan jawaban untuk
                                lomba.</p>

                            <a href="{{ route('admin.cerdas-cermat.index') }}"
                                class="inline-flex items-center justify-center w-full px-4 py-3 bg-white border-2 border-indigo-600 rounded-lg font-semibold text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-200">
                                <span>Atur Soal</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Sesi & Peserta Card -->
                    <div
                        class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 hover:shadow-lg transition-shadow duration-300 group">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-rose-50 p-3 rounded-lg group-hover:bg-rose-100 transition-colors">
                                    <i data-lucide="users" class="w-8 h-8 text-rose-600"></i>
                                </div>
                                <span class="bg-rose-100 text-rose-800 text-xs px-2 py-1 rounded-full font-semibold">Sesi
                                    Lomba</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Sesi & Peserta</h3>
                            <p class="text-gray-500 text-sm mb-6">Kontrol sesi aktif, reset status peserta, dan pantau
                                progress.</p>

                            <a href="{{ route('admin.cerdas-cermat.sessions') }}"
                                class="inline-flex items-center justify-center w-full px-4 py-3 bg-white border-2 border-rose-600 rounded-lg font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition-all duration-200">
                                <span>Kelola Sesi</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h2 class="text-xl font-semibold mb-4">Filter Nilai</h2>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="mata_lomba_id" class="block text-sm font-medium text-gray-700">Mata Lomba</label>
                        <select name="mata_lomba_id" id="mata_lomba_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-accent focus:ring focus:ring-scout-accent focus:ring-opacity-50">
                            <option value="">Semua Mata Lomba</option>
                            @foreach($mataLomba as $ml)
                                <option value="{{ $ml->id }}" {{ request('mata_lomba_id') == $ml->id ? 'selected' : '' }}>
                                    {{ $ml->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="juri_id" class="block text-sm font-medium text-gray-700">Juri</label>
                        <select name="juri_id" id="juri_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-accent focus:ring focus:ring-scout-accent focus:ring-opacity-50">
                            <option value="">Semua Juri</option>
                            @foreach($juri as $j)
                                <option value="{{ $j->id }}" {{ request('juri_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="bg-scout-primary text-white px-4 py-2 rounded-md hover:bg-scout-primary/90 transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="ml-2 text-gray-600 hover:text-gray-900">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Scores Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Data Penilaian</h3>
                </div>
                <div class="border-t border-gray-200">
                    <!-- Mobile Card View -->
                    <div class="md:hidden">
                        @forelse($scores as $score)
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $score->reguProfile->nama_regu }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($score->reguProfile->jenis) }} - Regu
                                            {{ $score->reguProfile->nomor_regu }}
                                        </div>
                                    </div>
                                    <div class="text-lg font-bold text-scout-primary">{{ $score->nilai }}</div>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <div class="text-gray-600">
                                        <div class="font-medium">{{ $score->mataLomba->nama }}</div>

                                        <div class="text-xs text-gray-500">Juri: {{ $score->juri->name }}</div>
                                    </div>
                                    <div class="text-gray-400 text-xs">
                                        {{ $score->created_at->format('d M H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">Belum ada data penilaian.</div>
                        @endforelse
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Regu</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mata Lomba</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Juri</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($scores as $score)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $score->reguProfile->nama_regu }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ ucfirst($score->reguProfile->jenis) }} - Regu
                                                {{ $score->reguProfile->nomor_regu }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $score->mataLomba->nama }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $score->juri->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $score->nilai }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $score->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.scores.destroy', $score->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data penilaian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection