@extends('layouts.main')

@section('title', 'Verifikasi Nilai - Admin')

@section('content')
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i data-lucide="clipboard-list" class="w-8 h-8 mr-3 text-scout-primary"></i>
                    Verifikasi Penilaian
                </h1>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-gray-500 hover:text-scout-primary transition-colors flex items-center font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8 border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                    <i data-lucide="filter" class="w-5 h-5 mr-2 text-scout-accent"></i> Filter Data Nilai
                </h2>
                <form method="GET" action="{{ route('admin.scores.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="mata_lomba_id" class="block text-sm font-bold text-gray-700 mb-1">Mata Lomba</label>
                        <select name="mata_lomba_id" id="mata_lomba_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50 transition-all bg-gray-50">
                            <option value="">-- Semua Mata Lomba --</option>
                            @foreach($allMataLomba as $ml)
                                <option value="{{ $ml->id }}" {{ request('mata_lomba_id') == $ml->id ? 'selected' : '' }}>
                                    {{ $ml->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="juri_id" class="block text-sm font-bold text-gray-700 mb-1">Pilih Juri</label>
                        <select name="juri_id" id="juri_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50 transition-all bg-gray-50">
                            <option value="">-- Semua Juri --</option>
                            @foreach($juri as $j)
                                <option value="{{ $j->id }}" {{ request('juri_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end space-x-3">
                        <button type="submit"
                            class="bg-scout-primary text-white px-6 py-2 rounded-md hover:bg-scout-primary/90 transition shadow-md font-bold flex items-center">
                            <i data-lucide="search" class="w-4 h-4 mr-2"></i> Terapkan
                        </button>
                        <a href="{{ route('admin.scores.index') }}"
                            class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition font-medium">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Scores Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">
                        Riwayat Penilaian yang Masuk
                    </h3>
                    <span
                        class="bg-scout-light text-scout-primary px-3 py-1 rounded-full text-xs font-bold border border-scout-secondary">
                        Total: {{ $scores->total() }} Data
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <!-- Mobile View -->
                    <div class="md:hidden divide-y divide-gray-200">
                        @forelse($scores as $score)
                            <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="flex-shrink-0 h-6 w-6 bg-indigo-100 rounded-full flex items-center justify-center border border-indigo-200">
                                            <span
                                                class="text-indigo-700 font-bold text-[10px]">{{ substr($score->juri->name, 0, 2) }}</span>
                                        </div>
                                        <div class="text-sm font-bold text-gray-900">{{ $score->juri->name }}</div>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $score->created_at->format('d M Y, H:i') }}</div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 my-3">
                                    <div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">Mata
                                            Lomba</div>
                                        <div class="text-xs font-semibold text-gray-900">{{ $score->mataLomba->nama }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">Skor
                                            Akhir</div>
                                        <span
                                            class="px-2 py-0.5 inline-flex text-sm font-bold rounded bg-green-100 text-green-800 border border-green-200">
                                            {{ number_format($score->nilai, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 mb-3">
                                    <div class="text-sm font-bold text-gray-900">{{ $score->reguProfile->nama_regu }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($score->reguProfile->jenis) }} - Regu
                                        {{ $score->reguProfile->nomor_regu }}</div>
                                </div>

                                <div class="flex justify-end">
                                    @if($score->delete_requested)
                                        <span
                                            class="inline-flex justify-center w-full px-3 py-2 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            <i data-lucide="clock" class="w-4 h-4 mr-1"></i> Menunggu Verifikasi
                                        </span>
                                    @else
                                        <form action="{{ route('admin.scores.destroy', $score->id) }}" method="POST" class="w-full"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mengajukan penghapusan nilai ini pada Juri?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full justify-center inline-flex items-center px-4 py-2 text-sm font-bold rounded shadow-sm text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-colors">
                                                <i data-lucide="alert-circle" class="w-4 h-4 mr-2"></i> Ajukan Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500 bg-white">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 p-4 rounded-full mb-3">
                                        <i data-lucide="search-x" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <p class="text-base font-medium text-gray-900">Tidak ada data penilaian</p>
                                    <p class="text-sm mt-1">Belum ada nilai yang masuk sesuai kriteria filter Anda.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop View -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Mata Lomba
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Regu Peserta
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Pemberi Nilai (Juri)
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-bold text-scout-primary uppercase tracking-wider">
                                    Skor Akhir
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($scores as $score)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $score->mataLomba->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $score->created_at->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $score->reguProfile->nama_regu }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($score->reguProfile->jenis) }} - Regu
                                            {{ $score->reguProfile->nomor_regu }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center border border-indigo-200">
                                                <span
                                                    class="text-indigo-700 font-bold text-xs">{{ substr($score->juri->name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $score->juri->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            {{ number_format($score->nilai, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($score->delete_requested)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i> Menunggu Verifikasi
                                            </span>
                                        @else
                                            <form action="{{ route('admin.scores.destroy', $score->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin mengajukan penghapusan nilai ini pada Juri?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded transition-colors flex items-center font-semibold ml-auto border border-amber-200"
                                                    title="Ajukan Hapus">
                                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i> Ajukan Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-50 p-4 rounded-full mb-3">
                                                <i data-lucide="search-x" class="w-8 h-8 text-gray-400"></i>
                                            </div>
                                            <p class="text-base font-medium text-gray-900">Tidak ada data penilaian</p>
                                            <p class="text-sm mt-1">Belum ada nilai yang masuk sesuai kriteria filter Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($scores->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $scores->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection