@extends('layouts.main')

@section('title', 'Kriteria: ' . $mataLomba->nama . ' - Admin Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500">
                        <a href="{{ route('admin.informasi-lomba.index') }}" class="hover:text-brand-forest transition font-medium">Info Lomba</a>
                        <span>/</span>
                        <span class="text-brand-forest font-bold">{{ $mataLomba->nama }}</span>
                    </div>
                    @php
                        $icon = match ($mataLomba->slug) {
                            'cerdas-cermat' => 'brain',
                            'tapak-kemah' => 'tent',
                            'masak-konvensional' => 'chef-hat',
                            'upcycle-art' => 'recycle',
                            'desain-poster-digital' => 'image',
                            default => 'award',
                        };
                    @endphp
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight flex items-center gap-2.5">
                        <i data-lucide="{{ $icon }}" class="w-7 h-7 text-emerald-700"></i>
                        <span>Kriteria Penilaian: {{ $mataLomba->nama }}</span>
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    @if(in_array($mataLomba->slug, ['lkbb', 'lkbb-tongkat']) || str_contains(strtolower($mataLomba->nama), 'pbb'))
                        <a href="{{ route('admin.pbb-rubrik.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-emerald-950 bg-amber-400 hover:bg-amber-500 shadow-sm transition">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i> Lembar Penilaian PBB
                        </a>
                    @endif
                    @if($mataLomba->slug !== 'cerdas-cermat')
                        <a href="{{ route('admin.kriteria.create', $mataLomba->id) }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-forest hover:bg-emerald-900 shadow-sm transition">
                            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kriteria
                        </a>
                    @endif
                    <a href="{{ route('admin.informasi-lomba.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 shadow-2xs transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                    </a>
                </div>
            </div>

            @if($mataLomba->slug === 'cerdas-cermat')
                <!-- Cerdas Cermat Dash View -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Main CC Card -->
                    <div class="lg:col-span-3 bg-white border border-gray-200/80 rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
                        <div class="relative z-10">
                            <h2 class="text-xl font-bold text-brand-forest mb-2 flex items-center gap-2">
                                <i data-lucide="cpu" class="w-5 h-5 text-emerald-600"></i>
                                Penilaian Otomatis Engine Kuis
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium mb-6 max-w-2xl leading-relaxed">
                                Mata lomba Cerdas Cermat menggunakan akumulasi skor langsung dari butir soal per babak. Nilai akhir dihitung otomatis oleh sistem tanpa kriteria manual.
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Nilai Maksimal</p>
                                    <p class="text-2xl font-black text-brand-forest">{{ (int) $mataLomba->nilai_maksimal }} <span class="text-xs font-bold text-gray-400">PTS</span></p>
                                </div>
                                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Butir Soal</p>
                                    <p class="text-2xl font-black text-amber-700">{{ array_sum($cerdasCermatCounts) }} Soal</p>
                                </div>
                                <div class="flex items-end">
                                    <a href="{{ route('admin.cerdas-cermat.index') }}"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-amber-400 hover:bg-amber-500 text-emerald-950 rounded-xl font-bold text-xs shadow-2xs transition">
                                        <i data-lucide="settings-2" class="w-4 h-4"></i>
                                        <span>Atur Bank Soal & Skor</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Type Breakdown -->
                    <div class="bg-white border border-gray-200/80 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-wider">Rincian Butir Soal</h3>
                        <div class="space-y-2">
                            @foreach($cerdasCermatCounts as $type => $count)
                                <div class="flex items-center justify-between p-2.5 bg-gray-50/80 rounded-xl border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-700">{{ $type }}</span>
                                    <span class="bg-emerald-50 text-brand-forest text-xs font-black px-2 py-0.5 rounded-md border border-emerald-200/50">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <p class="text-[10px] text-gray-400 leading-tight">
                                *Setiap pembaruan bobot nilai soal otomatis menyelaraskan skor maksimal lomba.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Regular Competition Dash View -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
                    <!-- Summary Stats -->
                    <div class="bg-white border border-gray-200/80 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-center">
                        <div class="grid grid-cols-2 gap-4 text-center sm:text-left">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Kriteria</p>
                                <p class="text-2xl font-black text-brand-forest">{{ $criteria->count() }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Skor Kriteria</p>
                                <p class="text-2xl font-black text-amber-700">{{ $criteria->sum('nilai_max') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Set Target Score -->
                    <div class="lg:col-span-2 bg-white border border-gray-200/80 rounded-2xl p-5 sm:p-6 shadow-sm">
                        <form action="{{ route('admin.informasi-lomba.update', $mataLomba->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="from_kriteria" value="1">
                            <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                                <div class="flex-1">
                                    <label for="target_nilai_maks" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                                        Target Nilai Maksimal Lomba
                                    </label>
                                    <input type="number" name="nilai_maksimal" id="target_nilai_maks" 
                                        value="{{ old('nilai_maksimal', (int) $mataLomba->nilai_maksimal) }}"
                                        class="w-full px-3.5 py-2 rounded-xl border border-gray-300 bg-gray-50 focus:bg-white focus:border-emerald-600 text-sm font-bold text-gray-900 transition @error('nilai_maksimal') border-red-500 @enderror">
                                    @error('nilai_maksimal')
                                        <p class="mt-1 text-xs text-red-600 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" 
                                        onclick="document.getElementById('target_nilai_maks').value = '{{ $criteria->sum('nilai_max') }}'"
                                        class="px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition flex items-center gap-1.5"
                                        title="Samakan dengan total skor kriteria">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                        Auto Set
                                    </button>
                                    <button type="submit" 
                                        class="px-4 py-2 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                                        <i data-lucide="save" class="w-3.5 h-3.5 text-amber-400"></i>
                                        Perbarui
                                    </button>
                                </div>
                            </div>
                        </form>
                        <p class="mt-2 text-[11px] text-gray-400 flex items-center gap-1">
                            <i data-lucide="info" class="w-3 h-3 text-emerald-600"></i>
                            Nilai total ini menjadi batas acuan dewan juri. Disarankan sama dengan total nilai rubrik kriteria.
                        </p>
                    </div>
                </div>

                @if($criteria->sum('nilai_max') != $mataLomba->nilai_maksimal && $criteria->count() > 0)
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex items-center gap-3 text-xs sm:text-sm">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0"></i>
                        <div>
                            <span class="font-bold">Perhatian Selisih Nilai:</span> Total skor rubrik kriteria ({{ $criteria->sum('nilai_max') }}) belum sama dengan Target Nilai Maksimal Lomba ({{ (int) $mataLomba->nilai_maksimal }}). Silakan gunakan tombol "Auto Set" di atas untuk menyelaraskan.
                        </div>
                    </div>
                @endif

                <!-- Criteria Table -->
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="list" class="w-4 h-4 text-emerald-700"></i>
                            <span>Daftar Rubrik Kriteria Penilaian</span>
                        </h3>
                        <span class="text-xs font-bold text-brand-forest bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">
                            {{ $criteria->count() }} Kriteria
                        </span>
                    </div>

                    <!-- Mobile List -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @forelse($criteria as $c)
                            <div class="p-4 hover:bg-gray-50/60 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-gray-900 text-sm">{{ $c->nama_kriteria }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold bg-emerald-50 text-brand-forest border border-emerald-200/60">
                                        {{ $c->nilai_min }} - {{ $c->nilai_max }} Pts
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 mb-3">Urutan tampil: #{{ $c->urutan ?? 1 }}</div>
                                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                                    <a href="{{ route('admin.kriteria.edit', $c->id) }}"
                                        class="text-xs font-bold text-emerald-700 hover:text-emerald-900 p-1 flex items-center gap-1">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.kriteria.destroy', $c->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus kriteria ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 p-1 flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <i data-lucide="clipboard-x" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm">Belum ada rubrik kriteria untuk lomba ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop Table -->
                    <table class="hidden md:table min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80 text-gray-600">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider w-16">Urutan</th>
                                <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Kriteria</th>
                                <th class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider">Rentang Skor</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($criteria as $c)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-6 py-4 text-xs font-bold text-gray-400">{{ $c->urutan ?? 1 }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $c->nama_kriteria }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-mono font-bold bg-emerald-50 text-brand-forest border border-emerald-200/60">
                                            {{ $c->nilai_min }} &ndash; {{ $c->nilai_max }} PTS
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.kriteria.edit', $c->id) }}"
                                                class="text-emerald-700 hover:text-emerald-900 p-1.5 hover:bg-emerald-50 rounded-lg transition" title="Edit Kriteria">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('admin.kriteria.destroy', $c->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus kriteria ini?');" class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-lg transition" title="Hapus Kriteria">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        <i data-lucide="clipboard-x" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                                        <p class="text-sm font-semibold text-gray-700">Belum ada kriteria penilaian</p>
                                        <span class="text-xs text-gray-400 mt-0.5">Silakan tambahkan kriteria baru untuk mata lomba ini.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
@endsection