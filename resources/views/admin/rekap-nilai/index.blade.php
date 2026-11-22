@extends('layouts.main')

@section('title', 'Rekap Nilai Cerdas Cermat - Admin Spencerone Camp 2026')

@section('content')
<div class="py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                    <i data-lucide="brain" class="w-3.5 h-3.5 text-emerald-600"></i>
                    Rekapitulasi Cerdas Cermat
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                    Rekap Nilai Cerdas Cermat
                </h1>
                <p class="text-sm text-gray-500 mt-1">Lihat peringkat dan evaluasi skor setiap babak (Babak 1, Babak 2, Babak 3 Final).</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.export.berita-acara') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-forest hover:bg-emerald-900 border border-emerald-800 shadow-sm transition">
                    <i class="fas fa-file-pdf text-amber-400"></i>
                    <span>Cetak Berita Acara</span>
                </a>
                <a href="{{ route('juri.cerdas-cermat.index') }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-900 shadow-2xs transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200" id="rekapTabs">
            <nav class="flex space-x-2" aria-label="Tabs">
                <button onclick="showTab('babak1')" id="tab-babak1"
                    class="tab-btn text-brand-forest border-brand-forest px-4 py-2.5 font-bold text-sm border-b-2 transition flex items-center gap-2">
                    <i data-lucide="check-square" class="w-4 h-4"></i>
                    <span>Babak 1 (PG)</span>
                </button>
                <button onclick="showTab('babak2')" id="tab-babak2"
                    class="tab-btn text-gray-500 hover:text-gray-700 px-4 py-2.5 font-bold text-sm border-b-2 border-transparent transition flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Babak 2 (Isian)</span>
                </button>
                <button onclick="showTab('babak3')" id="tab-babak3"
                    class="tab-btn text-gray-500 hover:text-gray-700 px-4 py-2.5 font-bold text-sm border-b-2 border-transparent transition flex items-center gap-2">
                    <i data-lucide="crown" class="w-4 h-4"></i>
                    <span>Babak 3 (Final Uraian)</span>
                </button>
            </nav>
        </div>

        <!-- Babak 1 Tab -->
        <div id="content-babak1" class="tab-content">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="award" class="w-5 h-5 text-amber-500"></i>
                        <span>Babak 1 - Pilihan Ganda (Skor Otomatis)</span>
                    </h2>
                    <span class="text-xs text-gray-400 font-semibold">{{ count($rekapBabak1) }} Peserta</span>
                </div>
                @if($rekapBabak1->isEmpty())
                    <div class="p-10 text-center text-gray-400">
                        <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm">Belum ada data nilai Babak 1.</p>
                    </div>
                @else
                    <!-- Mobile -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($rekapBabak1 as $index => $item)
                            <div class="p-4 {{ $index < 3 ? 'bg-amber-50/15' : 'bg-white' }} flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $index == 0 ? 'bg-amber-500 text-white' : ($index == 1 ? 'bg-slate-400 text-white' : ($index == 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }} font-black text-xs shadow-xs">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-base font-black text-brand-forest">{{ $item->score_round_1 }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium mt-0.5">
                                        <i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($item->time_used_seconds / 60) }}m {{ $item->time_used_seconds % 60 }}s
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Desktop -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider w-24">Ranking</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Waktu & Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($rekapBabak1 as $index => $item)
                                <tr class="hover:bg-gray-50/60 transition-colors {{ $index < 3 ? 'bg-amber-50/15' : '' }}">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $index == 0 ? 'bg-amber-500 text-white' : ($index == 1 ? 'bg-slate-400 text-white' : ($index == 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }} text-xs font-black">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Regu {{ $item->reguProfile->nomor_regu ?? '-' }} &bull; {{ ucfirst($item->reguProfile->jenis ?? 'Lainnya') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-base font-black text-brand-forest">{{ $item->score_round_1 }}</div>
                                        <div class="text-xs text-gray-400 font-medium mt-0.5"><i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($item->time_used_seconds / 60) }}m {{ $item->time_used_seconds % 60 }}s</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Babak 2 Tab -->
        <div id="content-babak2" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="edit-3" class="w-5 h-5 text-emerald-700"></i>
                        <span>Babak 2 - Isian Singkat (Penilaian Juri)</span>
                    </h2>
                    <form action="{{ route('juri.cerdas-cermat.finalize-round', 2) }}" method="POST" 
                        onsubmit="return confirm('Kunci semua nilai Babak 2 secara permanen? Seluruh nilai yang sudah dimasukkan tidak dapat diubah lagi.')">
                        @csrf
                        <button type="submit" @if(!$allGradedRound2 || $isFinalizedRound2) disabled @endif
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-xs
                            {{ $isFinalizedRound2 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 cursor-not-allowed' : 
                               (!$allGradedRound2 ? 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed' : 'bg-brand-forest text-white hover:bg-emerald-900') }}">
                            @if($isFinalizedRound2)
                                <i data-lucide="lock" class="w-4 h-4 mr-1.5"></i> Nilai Sudah Dikunci
                            @else
                                <i data-lucide="shield-check" class="w-4 h-4 mr-1.5"></i> Kunci Semua Nilai Babak 2
                            @endif
                        </button>
                    </form>
                </div>
                @if($rekapBabak2->isEmpty())
                    <div class="p-10 text-center text-gray-400">
                        <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm">Belum ada data nilai Babak 2.</p>
                    </div>
                @else
                    <!-- Mobile -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($rekapBabak2 as $index => $item)
                            <div class="p-4 {{ $index < 3 ? 'bg-amber-50/15' : 'bg-white' }}">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $index == 0 ? 'bg-amber-500 text-white' : ($index == 1 ? 'bg-slate-400 text-white' : ($index == 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }} font-black text-xs shadow-xs">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                            <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-base font-black text-brand-forest">{{ $item->score_round_2 }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium mt-0.5"><i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($item->time_used_seconds / 60) }}m {{ $item->time_used_seconds % 60 }}s</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-2 mt-3 pt-3 border-t border-gray-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $item->is_finalized_round_2 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($item->is_graded_round_2 ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                        {{ $item->is_finalized_round_2 ? 'Final' : ($item->is_graded_round_2 ? 'Dinilai' : 'Belum Dinilai') }}
                                    </span>
                                    <a href="{{ route('juri.cerdas-cermat.show', [$item->id, 2]) }}" 
                                        class="inline-flex items-center px-3 py-1 bg-brand-forest text-white rounded-lg text-xs font-bold shadow-2xs hover:bg-emerald-900 transition">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 mr-1"></i> Penilaian
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Desktop -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider w-24">Ranking</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Waktu & Nilai</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($rekapBabak2 as $index => $item)
                                <tr class="hover:bg-gray-50/60 transition-colors {{ $index < 3 ? 'bg-amber-50/15' : '' }}">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $index == 0 ? 'bg-amber-500 text-white' : ($index == 1 ? 'bg-slate-400 text-white' : ($index == 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }} text-xs font-black">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Regu {{ $item->reguProfile->nomor_regu ?? '-' }} &bull; {{ ucfirst($item->reguProfile->jenis ?? 'Lainnya') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $item->is_finalized_round_2 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($item->is_graded_round_2 ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                            @if($item->is_finalized_round_2) <i data-lucide="lock" class="w-3 h-3 mr-1"></i> @endif
                                            {{ $item->is_finalized_round_2 ? 'Final' : ($item->is_graded_round_2 ? 'Terpilih' : 'Menunggu') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-base font-black text-brand-forest">{{ $item->score_round_2 }}</div>
                                        <div class="text-xs text-gray-400 font-medium mt-0.5"><i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($item->time_used_seconds / 60) }}m {{ $item->time_used_seconds % 60 }}s</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('juri.cerdas-cermat.show', [$item->id, 2]) }}"
                                            class="inline-flex items-center px-3.5 py-1.5 bg-brand-forest text-white rounded-xl text-xs font-bold hover:bg-emerald-900 transition shadow-2xs">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5 mr-1.5"></i> PENILAIAN
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Babak 3 Tab -->
        <div id="content-babak3" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="crown" class="w-5 h-5 text-amber-500"></i>
                        <span>Babak 3 - Uraian Final (Penetapan Juara)</span>
                    </h2>
                    <form action="{{ route('juri.cerdas-cermat.finalize-round', 3) }}" method="POST" 
                        onsubmit="return confirm('Kunci semua nilai Babak 3 secara permanen? Nilai akhir pemenang akan segera ditetapkan.')">
                        @csrf
                        <button type="submit" @if(!$allGradedRound3 || $isFinalizedRound3) disabled @endif
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-xs
                            {{ $isFinalizedRound3 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 cursor-not-allowed' : 
                               (!$allGradedRound3 ? 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed' : 'bg-brand-forest text-white hover:bg-emerald-900') }}">
                            @if($isFinalizedRound3)
                                <i data-lucide="lock" class="w-4 h-4 mr-1.5"></i> Nilai Sudah Dikunci
                            @else
                                <i data-lucide="shield-check" class="w-4 h-4 mr-1.5"></i> Kunci Semua Nilai Babak 3
                            @endif
                        </button>
                    </form>
                </div>
                @if($rekapBabak3->isEmpty())
                    <div class="p-10 text-center text-gray-400">
                        <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm">Belum ada data nilai Babak 3.</p>
                    </div>
                @else
                    <!-- Mobile -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($rekapBabak3 as $index => $item)
                            <div class="p-4 {{ $index < 3 ? 'bg-amber-50/15' : 'bg-white' }}">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $index == 0 ? 'bg-amber-500 text-white' : ($index == 1 ? 'bg-slate-400 text-white' : ($index == 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }} font-black text-xs shadow-xs">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                            <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-base font-black text-brand-forest">{{ $item->score_round_3 ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium mt-0.5"><i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($item->time_used_seconds / 60) }}m {{ $item->time_used_seconds % 60 }}s</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-2 mt-3 pt-3 border-t border-gray-100">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $item->is_finalized_round_3 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : (is_null($item->score_round_3) ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200') }}">
                                        {{ $item->is_finalized_round_3 ? 'Final' : (is_null($item->score_round_3) ? 'Belum Dinilai' : 'Dinilai') }}
                                    </span>
                                    <a href="{{ route('juri.cerdas-cermat.show', [$item->id, 3]) }}" 
                                        class="inline-flex items-center px-3 py-1 bg-brand-forest text-white rounded-lg text-xs font-bold shadow-2xs hover:bg-emerald-900 transition">
                                        <i data-lucide="crown" class="w-3.5 h-3.5 mr-1"></i> Penilaian
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Desktop -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider w-24">Ranking</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Waktu & Nilai</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($rekapBabak3 as $index => $item)
                                <tr class="hover:bg-gray-50/60 transition-colors {{ $index < 3 ? 'bg-amber-50/15' : '' }}">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg {{ $index == 0 ? 'bg-amber-500 text-white' : ($index == 1 ? 'bg-slate-400 text-white' : ($index == 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }} text-xs font-black">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">Regu {{ $item->reguProfile->nomor_regu ?? '-' }} &bull; {{ ucfirst($item->reguProfile->jenis ?? 'Lainnya') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $item->is_finalized_round_3 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : (is_null($item->score_round_3) ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200') }}">
                                            @if($item->is_finalized_round_3) <i data-lucide="lock" class="w-3 h-3 mr-1"></i> @endif
                                            {{ $item->is_finalized_round_3 ? 'Final' : (is_null($item->score_round_3) ? 'Menunggu' : 'Dinilai') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-base font-black text-brand-forest">{{ $item->score_round_3 ?? '-' }}</div>
                                        <div class="text-xs text-gray-400 font-medium mt-0.5"><i data-lucide="timer" class="w-3 h-3 inline mb-0.5"></i> {{ floor($item->time_used_seconds / 60) }}m {{ $item->time_used_seconds % 60 }}s</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('juri.cerdas-cermat.show', [$item->id, 3]) }}"
                                            class="inline-flex items-center px-3.5 py-1.5 bg-brand-forest text-white rounded-xl text-xs font-bold hover:bg-emerald-900 transition shadow-2xs">
                                            <i data-lucide="crown" class="w-3.5 h-3.5 mr-1.5"></i> PENILAIAN
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('text-brand-forest', 'border-brand-forest');
            el.classList.add('text-gray-500', 'border-transparent');
        });
        document.getElementById('content-' + tabName).classList.remove('hidden');
        const tabBtn = document.getElementById('tab-' + tabName);
        tabBtn.classList.remove('text-gray-500', 'border-transparent');
        tabBtn.classList.add('text-brand-forest', 'border-brand-forest');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endpush
@endsection