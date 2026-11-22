@extends('layouts.main')

@section('title', 'Data Induk Siswa - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                    <i data-lucide="database" class="w-3.5 h-3.5"></i>
                    <span>Master Database Siswa</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Data Induk Siswa</h1>
                <p class="text-sm text-gray-500 mt-1">Master data siswa per tingkatan kelas untuk validasi keanggotaan regu lomba.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold shadow-xs transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Minimalist Tab Pills --}}
        <div class="bg-white p-1.5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col sm:flex-row gap-1.5 max-w-md mb-6">
            <button onclick="showKelasTab(7)" id="tab-kelas-7"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all bg-brand-forest text-white shadow-sm">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                <span>Kelas 7</span>
                <span id="badge-kelas-7" class="ml-1 bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $siswaKelas7->count() }}</span>
            </button>
            <button onclick="showKelasTab(8)" id="tab-kelas-8"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                <span>Kelas 8</span>
                <span id="badge-kelas-8" class="ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $siswaKelas8->count() }}</span>
            </button>
            <button onclick="showKelasTab(9)" id="tab-kelas-9"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                <span>Kelas 9</span>
                <span id="badge-kelas-9" class="ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $siswaKelas9->count() }}</span>
            </button>
        </div>

        @foreach([7, 8, 9] as $kelas)
            @php
                $siswa = ${'siswaKelas' . $kelas};
            @endphp
            <div id="content-kelas-{{ $kelas }}" class="{{ $kelas !== 7 ? 'hidden' : '' }}">
                {{-- Actions Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <button onclick="toggleActionForm('add', {{ $kelas }})"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-xs shadow-xs transition">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span>Tambah Siswa</span>
                        </button>
                        <button onclick="toggleActionForm('import', {{ $kelas }})"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-400 hover:bg-amber-500 text-emerald-950 rounded-xl font-bold text-xs shadow-xs transition">
                            <i data-lucide="file-up" class="w-4 h-4"></i>
                            <span>Import CSV</span>
                        </button>
                    </div>

                    @if($siswa->count() > 0)
                        <form id="delete-all-{{ $kelas }}" action="{{ route('admin.siswa.delete-all', $kelas) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeleteAll('delete-all-{{ $kelas }}', {{ $kelas }})"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/60 rounded-xl font-bold text-xs transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                <span>Hapus Semua Siswa Kelas {{ $kelas }}</span>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Collapsible Forms Container --}}
                <div id="forms-container-{{ $kelas }}" class="space-y-4 mb-8 hidden">
                    {{-- Add Student Form --}}
                    <div id="form-add-{{ $kelas }}" class="hidden bg-white rounded-2xl shadow-sm p-6 border border-emerald-200">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-800">
                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">
                                    Tambah Siswa Baru - Kelas {{ $kelas }}
                                </h3>
                            </div>
                            <button onclick="toggleActionForm('add', {{ $kelas }})" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <form action="{{ route('admin.siswa.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            @csrf
                            <input type="hidden" name="kelas" value="{{ $kelas }}">
                            <div class="md:col-span-7">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap Siswa</label>
                                <input type="text" name="nama" required placeholder="Contoh: Ahmad Fauzi Pratama"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-medium transition">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 text-sm font-medium transition">
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <button type="submit"
                                    class="w-full py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Import CSV Form --}}
                    <div id="form-import-{{ $kelas }}" class="hidden bg-white rounded-2xl shadow-sm p-6 border border-amber-200">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                                    <i data-lucide="file-up" class="w-4 h-4"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide">
                                    Import Data Siswa dari File CSV - Kelas {{ $kelas }}
                                </h3>
                            </div>
                            <button onclick="toggleActionForm('import', {{ $kelas }})" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                            <div class="lg:col-span-2">
                                <form action="{{ route('admin.siswa.import-csv') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-end">
                                    @csrf
                                    <input type="hidden" name="kelas" value="{{ $kelas }}">
                                    <div class="flex-1 w-full">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pilih File CSV (.csv / .txt)</label>
                                        <input type="file" name="csv_file" accept=".csv,.txt" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 transition-all cursor-pointer">
                                    </div>
                                    <button type="submit"
                                        class="w-full sm:w-auto px-6 py-2.5 bg-amber-400 hover:bg-amber-500 text-emerald-950 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm shrink-0">
                                        Mulai Import
                                    </button>
                                </form>
                            </div>
                            <div class="bg-amber-50/60 p-4 rounded-xl border border-amber-200/80">
                                <p class="text-[11px] text-amber-950 font-bold uppercase tracking-wider mb-2">Format Kolom Header:</p>
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <code class="px-2 py-0.5 bg-white border border-amber-200 rounded-md text-[10px] font-mono font-bold text-amber-900">nama</code>
                                    <code class="px-2 py-0.5 bg-white border border-amber-200 rounded-md text-[10px] font-mono font-bold text-amber-900">jenis_kelamin</code>
                                </div>
                                <p class="text-[11px] text-gray-600 leading-relaxed">Kolom <b>nama</b> wajib diisi. Kolom jenis kelamin (L/P) bersifat opsional.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile View: Cards --}}
                <div class="md:hidden bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden divide-y divide-gray-100 mb-8">
                    @forelse($siswa as $index => $s)
                        <div class="p-4 hover:bg-gray-50/60 transition-colors" id="card-{{ $s->id }}">
                            {{-- Display Mode --}}
                            <div class="display-card-{{ $s->id }}">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex items-start gap-2.5">
                                        <span class="text-xs font-bold text-gray-400 w-6 mt-0.5">#{{ $index + 1 }}</span>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">{{ $s->nama }}</h4>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                @if($s->nisn)
                                                    <span class="text-[11px] font-mono text-gray-500">NTA: {{ $s->nta ?? $s->nisn }}</span>
                                                @endif
                                                @if($s->reguProfile)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $s->reguProfile->isPutra() ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-rose-100 text-rose-900 border border-rose-200' }}">
                                                        {{ $s->reguProfile->nama_regu }}
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-gray-400 italic">Belum ada regu</span>
                                                @endif
                                                @if($s->tingkatan_tku)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                        {{ $s->tingkatan_tku }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($s->jenis_kelamin === 'L')
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">L</span>
                                        @elseif($s->jenis_kelamin === 'P')
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">P</span>
                                        @else
                                            <span class="text-[10px] text-gray-400">-</span>
                                        @endif
                                        
                                        <div class="flex items-center gap-1">
                                            <button onclick="startEdit({{ $s->id }}, true)" class="p-1.5 text-gray-500 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition" title="Edit Siswa">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form id="delete-siswa-m-{{ $s->id }}" action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-siswa-m-{{ $s->id }}')" 
                                                    class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Siswa">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Edit Mode --}}
                            <div class="edit-card-{{ $s->id }} hidden py-2">
                                <form action="{{ route('admin.siswa.update', $s) }}" method="POST" class="space-y-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="kelas" value="{{ $kelas }}">
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Siswa</label>
                                            <input type="text" name="nama" value="{{ $s->nama }}" required
                                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-medium">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" 
                                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-medium">
                                                <option value="" {{ is_null($s->jenis_kelamin) ? 'selected' : '' }}>-- Belum Ditentukan --</option>
                                                <option value="L" {{ $s->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                                <option value="P" {{ $s->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 py-1.5 bg-brand-forest text-white rounded-xl text-xs font-bold transition shadow-xs">Simpan</button>
                                        <button type="button" onclick="cancelEdit({{ $s->id }}, true)" 
                                            class="flex-1 py-1.5 bg-white text-gray-600 rounded-xl text-xs font-semibold border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 px-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <i data-lucide="inbox" class="w-6 h-6"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum ada data siswa di kelas {{ $kelas }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop View: Table --}}
                <div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200/80 mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/60">
                                <tr>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-14">No</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">NTA / NISN</th>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Regu</th>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">TKU</th>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Gender</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($siswa as $index => $s)
                                    <tr class="hover:bg-gray-50/60 transition-colors group" id="row-{{ $s->id }}">
                                        {{-- Display Mode --}}
                                        <td class="display-cell px-4 py-3.5 whitespace-nowrap text-xs font-bold text-gray-400 text-center">{{ $index + 1 }}</td>
                                        <td class="display-cell px-6 py-3.5 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $s->nama }}</td>
                                        <td class="display-cell px-4 py-3.5 whitespace-nowrap text-center text-xs font-mono text-gray-500">
                                            {{ $s->nta ?? $s->nisn ?? '-' }}
                                        </td>
                                        <td class="display-cell px-4 py-3.5 whitespace-nowrap text-center">
                                            @if($s->reguProfile)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold {{ $s->reguProfile->isPutra() ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-rose-100 text-rose-900 border border-rose-200' }}">
                                                    {{ $s->reguProfile->nama_regu }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Belum ada regu</span>
                                            @endif
                                        </td>
                                        <td class="display-cell px-4 py-3.5 whitespace-nowrap text-center">
                                            @if($s->tingkatan_tku)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    {{ $s->tingkatan_tku }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="display-cell px-4 py-3.5 whitespace-nowrap text-center">
                                            @if($s->jenis_kelamin === 'L')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">L</span>
                                            @elseif($s->jenis_kelamin === 'P')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">P</span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="display-cell px-6 py-3.5 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="startEdit({{ $s->id }})" class="p-1.5 text-gray-500 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition" title="Edit Siswa">
                                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                </button>
                                                <form id="delete-siswa-{{ $s->id }}" action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete('delete-siswa-{{ $s->id }}')" 
                                                        class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Siswa">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        {{-- Edit Mode --}}
                                        <td class="edit-cell hidden px-4 py-3 text-center text-xs font-bold text-gray-400" colspan="1">{{ $index + 1 }}</td>
                                        <td class="edit-cell hidden px-6 py-3" colspan="6">
                                            <form action="{{ route('admin.siswa.update', $s) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="kelas" value="{{ $kelas }}">
                                                <input type="text" name="nama" value="{{ $s->nama }}" required
                                                    class="flex-1 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                                <select name="jenis_kelamin"
                                                    class="w-24 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                                    <option value="" {{ is_null($s->jenis_kelamin) ? 'selected' : '' }}>-</option>
                                                    <option value="L" {{ $s->jenis_kelamin === 'L' ? 'selected' : '' }}>L</option>
                                                    <option value="P" {{ $s->jenis_kelamin === 'P' ? 'selected' : '' }}>P</option>
                                                </select>
                                                <button type="submit" class="px-4 py-1.5 bg-brand-forest text-white rounded-xl text-xs font-bold transition shadow-xs">Simpan</button>
                                                <button type="button" onclick="cancelEdit({{ $s->id }})" class="px-4 py-1.5 bg-white text-gray-600 rounded-xl text-xs font-semibold border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center">
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 text-gray-400">
                                                <i data-lucide="inbox" class="w-5 h-5"></i>
                                            </div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum ada data siswa di kelas {{ $kelas }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showKelasTab(kelas) {
        [7, 8, 9].forEach(k => {
            document.getElementById('content-kelas-' + k).classList.toggle('hidden', k !== kelas);
            const tab = document.getElementById('tab-kelas-' + k);
            const badge = document.getElementById('badge-kelas-' + k);
            if (k === kelas) {
                tab.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all bg-brand-forest text-white shadow-sm';
                if (badge) badge.className = 'ml-1 bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-bold';
            } else {
                tab.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50';
                if (badge) badge.className = 'ml-1 bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold';
            }
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function startEdit(id, isMobile = false) {
        if (isMobile) {
            const card = document.getElementById('card-' + id);
            card.querySelector('.display-card-' + id).classList.add('hidden');
            card.querySelector('.edit-card-' + id).classList.remove('hidden');
        } else {
            const row = document.getElementById('row-' + id);
            row.querySelectorAll('.display-cell').forEach(c => c.classList.add('hidden'));
            row.querySelectorAll('.edit-cell').forEach(c => c.classList.remove('hidden'));
        }
    }

    function cancelEdit(id, isMobile = false) {
        if (isMobile) {
            const card = document.getElementById('card-' + id);
            card.querySelector('.display-card-' + id).classList.remove('hidden');
            card.querySelector('.edit-card-' + id).classList.add('hidden');
        } else {
            const row = document.getElementById('row-' + id);
            row.querySelectorAll('.display-cell').forEach(c => c.classList.remove('hidden'));
            row.querySelectorAll('.edit-cell').forEach(c => c.classList.add('hidden'));
        }
    }

    function toggleActionForm(type, kelas) {
        const container = document.getElementById('forms-container-' + kelas);
        const addForm = document.getElementById('form-add-' + kelas);
        const importForm = document.getElementById('form-import-' + kelas);
        
        const targetForm = type === 'add' ? addForm : importForm;
        const wasShowingTarget = !targetForm.classList.contains('hidden');
        
        addForm.classList.add('hidden');
        importForm.classList.add('hidden');
        container.classList.add('hidden');
        
        if (!wasShowingTarget) {
            container.classList.remove('hidden');
            targetForm.classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            if (type === 'add') {
                setTimeout(() => {
                    targetForm.querySelector('input[name="nama"]').focus();
                }, 100);
            }
        }
    }

    function confirmDeleteAll(formId, kelas) {
        if (confirm('Apakah Anda yakin ingin menghapus SEMUA data siswa kelas ' + kelas + '? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById(formId).submit();
        }
    }

    function confirmDelete(formId) {
        if (confirm('Apakah Anda yakin ingin menghapus data siswa ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush
