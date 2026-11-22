@extends('layouts.main')

@section('title', 'Verifikasi Penilaian - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <i data-lucide="clipboard-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Penjurian & Verifikasi
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                        Verifikasi Penilaian
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Validasi skor masuk dari juri, kelola permintaan perbaikan nilai, dan cetak dokumen resmi.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('admin.export.berita-acara') }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-sm transition">
                        <i class="fas fa-file-pdf text-amber-400"></i>
                        <span>Cetak Berita Acara</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-900 shadow-2xs transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm mb-8 border border-gray-200/80">
                <div class="flex items-center gap-2.5 mb-4 text-brand-forest">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center border border-emerald-200/50">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Filter Data Penilaian</h2>
                        <p class="text-xs text-gray-400">Saring riwayat skor berdasarkan mata lomba dan juri penilai</p>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.scores.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="mata_lomba_id" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Mata Lomba</label>
                        <select name="mata_lomba_id" id="mata_lomba_id"
                            class="block w-full px-3 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50/80 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition">
                            <option value="">-- Semua Mata Lomba --</option>
                            @foreach($allMataLomba as $ml)
                                <option value="{{ $ml->id }}" {{ request('mata_lomba_id') == $ml->id ? 'selected' : '' }}>
                                    {{ $ml->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="juri_id" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Juri Penilai</label>
                        <select name="juri_id" id="juri_id"
                            class="block w-full px-3 py-2 text-sm rounded-xl border border-gray-300 bg-gray-50/80 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition">
                            <option value="">-- Semua Juri --</option>
                            @foreach($juri as $j)
                                <option value="{{ $j->id }}" {{ request('juri_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-brand-forest hover:bg-emerald-900 text-white px-5 py-2.5 rounded-xl font-bold text-xs tracking-wide shadow-sm transition">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            <span>Terapkan</span>
                        </button>
                        <a href="{{ route('admin.scores.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl font-bold text-xs text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Scores Table Card -->
            <div class="bg-white shadow-sm rounded-2xl border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50/70 gap-2">
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="list" class="w-4 h-4 text-emerald-700"></i>
                        <span>Riwayat Penilaian yang Masuk</span>
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-brand-forest border border-emerald-200/60 w-fit">
                        Total: {{ $scores->total() }} Data
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <!-- Mobile View -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @forelse($scores as $score)
                            <div class="p-4 bg-white hover:bg-gray-50/70 transition-colors {{ $score->unlock_requested ? 'bg-amber-50/20' : '' }}">
                                <div class="flex items-center justify-between mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-100/80 text-brand-forest flex items-center justify-center text-xs font-black">
                                            {{ substr($score->juri->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-900">{{ $score->juri->name }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $score->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-sm font-black font-mono bg-emerald-50 text-brand-forest border border-emerald-200/60">
                                            {{ number_format($score->nilai, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 mb-3">
                                    <div class="text-xs font-bold text-gray-900">{{ $score->reguProfile->nama_regu }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">{{ ucfirst($score->reguProfile->jenis) }} &bull; Regu {{ $score->reguProfile->nomor_regu }} &bull; <span class="font-semibold text-emerald-800">{{ $score->mataLomba->nama }}</span></div>
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-gray-100">
                                    <div>
                                        @if($score->status === 'verified')
                                            <span class="px-2 py-0.5 inline-flex items-center gap-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <i data-lucide="check-check" class="w-3 h-3"></i> VERIFIED
                                            </span>
                                        @elseif($score->status === 'submitted')
                                            <span class="px-2 py-0.5 inline-flex items-center gap-1 text-[10px] font-bold rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                                <i data-lucide="lock" class="w-3 h-3"></i> SUBMITTED
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 inline-flex items-center gap-1 text-[10px] font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                                <i data-lucide="edit-3" class="w-3 h-3"></i> DRAFT
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1.5">
                                        @if($score->unlock_requested)
                                            <form action="{{ route('admin.scores.approve-unlock', $score->id) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('Buka kunci nilai ini agar juri dapat menyunting kembali?');">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-lg text-xs font-bold shadow-xs">
                                                    Izinkan Edit
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.scores.reject-unlock', $score->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded-lg text-xs font-bold">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        @if($score->status === 'submitted')
                                            <form action="{{ route('admin.scores.verify', $score->id) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('Sahkan dan verifikasi nilai ini secara resmi?');">
                                                @csrf
                                                <button type="submit" class="bg-brand-forest hover:bg-emerald-900 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-xs flex items-center gap-1">
                                                    <i data-lucide="check-check" class="w-3 h-3 text-amber-400"></i> Verifikasi
                                                </button>
                                            </form>
                                        @endif

                                        @if(!$score->delete_requested)
                                            <form action="{{ route('admin.scores.destroy', $score->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin mengajukan penghapusan nilai ini pada Juri?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg text-xs font-semibold border border-red-200 transition"
                                                    title="Ajukan Hapus">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <i data-lucide="search-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm font-semibold text-gray-700">Tidak ada data penilaian</p>
                                <p class="text-xs text-gray-400 mt-0.5">Belum ada nilai yang masuk sesuai kriteria filter Anda.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop View -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Mata Lomba</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Regu Peserta</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Pemberi Nilai (Juri)</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Skor Akhir</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi & Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($scores as $score)
                                <tr class="hover:bg-gray-50/60 transition-colors {{ $score->unlock_requested ? 'bg-amber-50/20' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $score->mataLomba->nama }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $score->created_at->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $score->reguProfile->nama_regu }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($score->reguProfile->jenis) }} &bull; Regu {{ $score->reguProfile->nomor_regu }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100/80 text-brand-forest flex items-center justify-center font-black text-xs">
                                                {{ substr($score->juri->name, 0, 2) }}
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $score->juri->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-black font-mono bg-emerald-50 text-brand-forest border border-emerald-200/60">
                                            {{ number_format($score->nilai, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($score->status === 'verified')
                                            <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> VERIFIED
                                            </span>
                                        @elseif($score->status === 'submitted')
                                            <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                                <i data-lucide="lock" class="w-3.5 h-3.5"></i> SUBMITTED
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-bold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> DRAFT
                                            </span>
                                        @endif

                                        @if($score->unlock_requested)
                                            <div class="mt-1">
                                                <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-red-100 text-red-700 border border-red-200 inline-block animate-pulse">
                                                    Minta Buka Kunci
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                        <div class="flex items-center justify-end gap-2 flex-wrap">
                                            @if($score->unlock_requested)
                                                <form action="{{ route('admin.scores.approve-unlock', $score->id) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Buka kunci nilai ini agar juri dapat menyunting kembali?');">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-lg font-bold shadow-xs transition">
                                                        Izinkan Edit
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.scores.reject-unlock', $score->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2.5 py-1 rounded-lg font-bold transition">
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif

                                            @if($score->status === 'submitted')
                                                <form action="{{ route('admin.scores.verify', $score->id) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Sahkan dan verifikasi nilai ini secara resmi?');">
                                                    @csrf
                                                    <button type="submit" class="bg-brand-forest hover:bg-emerald-900 text-white px-3 py-1.5 rounded-xl font-bold shadow-xs transition flex items-center gap-1">
                                                        <i data-lucide="check-check" class="w-3.5 h-3.5 text-amber-400"></i> Verifikasi
                                                    </button>
                                                </form>
                                            @endif

                                            @if($score->delete_requested)
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                                    Menunggu Juri
                                                </span>
                                            @else
                                                <form action="{{ route('admin.scores.destroy', $score->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin mengajukan penghapusan nilai ini pada Juri?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-xl font-bold border border-red-200/60 transition"
                                                        title="Ajukan Hapus">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="search-x" class="w-8 h-8 text-gray-300 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-700">Tidak ada data penilaian</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Belum ada nilai yang masuk sesuai kriteria filter Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($scores->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $scores->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection