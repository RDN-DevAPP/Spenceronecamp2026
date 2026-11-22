@extends('layouts.main')

@section('title', 'Manajemen Sertifikat & Piagam - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 mb-2">
                    <i data-lucide="award" class="w-3.5 h-3.5 text-amber-700"></i>
                    <span>Penerbitan Dokumen Resmi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Manajemen Sertifikat & Piagam</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola dan terbitkan sertifikat kejuaraan dan partisipasi peserta dengan penomoran otomatis.</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tabs Pills --}}
        <div class="bg-white p-1.5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col sm:flex-row gap-1.5 max-w-md mb-8">
            <button onclick="showCertTab('penerbitan')" id="tab-btn-penerbitan"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all bg-brand-forest text-white shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Penerbitan Sertifikat</span>
            </button>
            <button onclick="showCertTab('pengaturan')" id="tab-btn-pengaturan"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50">
                <i data-lucide="sliders" class="w-4 h-4"></i>
                <span>Template & Penomoran</span>
            </button>
        </div>

        {{-- TAB 1: PENERBITAN SERTIFIKAT --}}
        <div id="content-penerbitan" class="space-y-8">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8">
                <h2 class="text-base font-black text-brand-forest uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4 text-amber-500"></i>
                    Pilih Kategori & Regu Penerima
                </h2>

                <form method="GET" action="{{ route('admin.sertifikat.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Regu Peserta</label>
                        <select name="regu_id" id="select-regu" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            <option value="">-- Pilih Regu Peserta --</option>
                            @foreach($regus as $r)
                                <option value="{{ $r->id }}" {{ request('regu_id') == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama_regu }} ({{ ucfirst($r->jenis) }}) - {{ $r->sekolah ?? 'SMPN 1 Cerbon' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jenis Sertifikat</label>
                        <div class="flex gap-2">
                            <label class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer font-bold text-xs transition has-[:checked]:bg-amber-400 has-[:checked]:text-emerald-950 has-[:checked]:border-amber-400">
                                <input type="radio" name="cert_type" value="juara" checked onchange="updateAwardPreset()" class="hidden">
                                <i data-lucide="trophy" class="w-4 h-4"></i>
                                <span>Sertifikat Juara</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer font-bold text-xs transition has-[:checked]:bg-brand-forest has-[:checked]:text-white has-[:checked]:border-brand-forest">
                                <input type="radio" name="cert_type" value="peserta" onchange="updateAwardPreset()" class="hidden">
                                <i data-lucide="award" class="w-4 h-4"></i>
                                <span>Sertifikat Peserta</span>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Gelar / Predikat Penghargaan</label>
                        <input type="text" id="cert_title_input" name="cert_title" value="JUARA UMUM 1"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold uppercase focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                    </div>
                </form>

                {{-- Preset Title Badges --}}
                <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2" id="preset-badges">
                    <span class="text-xs text-gray-400 font-semibold mr-1">Preset Cepat:</span>
                    <button type="button" onclick="setPreset('JUARA UMUM 1')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100 transition">Juara Umum 1</button>
                    <button type="button" onclick="setPreset('JUARA UMUM 2')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100 transition">Juara Umum 2</button>
                    <button type="button" onclick="setPreset('JUARA UMUM 3')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100 transition">Juara Umum 3</button>
                    <button type="button" onclick="setPreset('JUARA 1 LOMBA CERDAS CERMAT')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-900 border border-emerald-200 hover:bg-emerald-100 transition">Juara 1 Cerdas Cermat</button>
                    <button type="button" onclick="setPreset('JUARA 1 LOMBA HASTA KARYA')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-900 border border-emerald-200 hover:bg-emerald-100 transition">Juara 1 Hasta Karya</button>
                    <button type="button" onclick="setPreset('PESERTA AKTIF & BERPRESTASI')" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition">Peserta Teladan</button>
                </div>
            </div>

            {{-- Members Table for Selected Regu --}}
            @if($selectedRegu)
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $selectedRegu->isPutra() ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-rose-100 text-rose-900 border border-rose-200' }}">
                                    {{ ucfirst($selectedRegu->jenis) }}
                                </span>
                                <h3 class="text-base font-black text-brand-forest uppercase">{{ $selectedRegu->nama_regu }}</h3>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $selectedRegu->sekolah ?? 'SMPN 1 Cerbon' }} • {{ $selectedRegu->anggotaRegu->count() }} Anggota Terdaftar</p>
                        </div>
                        <div>
                            <button type="button" onclick="downloadAllForRegu({{ $selectedRegu->id }})"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-xs shadow-sm transition">
                                <i data-lucide="file-down" class="w-4 h-4 text-amber-400"></i>
                                <span>Download PDF Semua Anggota Regu (1 File)</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/60">
                                <tr>
                                    <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-14">No</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan</th>
                                    <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas & TKU</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Aksi Cetak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($selectedRegu->anggotaRegu as $idx => $m)
                                    <tr class="hover:bg-gray-50/60 transition-colors">
                                        <td class="px-6 py-4 text-center text-xs font-bold text-gray-400">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 overflow-hidden shrink-0 flex items-center justify-center font-bold text-xs text-emerald-800">
                                                    @if($m->foto_path)
                                                        <img src="{{ asset('storage/' . $m->foto_path) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <i data-lucide="user" class="w-4 h-4 text-emerald-700/50"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900">{{ $m->nama }}</h4>
                                                    @if($m->nisn)
                                                        <span class="text-[10px] font-mono text-gray-400">NISN: {{ $m->nisn }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                                {{ $m->jabatan === 'pinru' ? 'bg-rose-100 text-rose-800 border border-rose-200' : ($m->jabatan === 'wapinru' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') }}">
                                                {{ strtoupper($m->jabatan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs font-medium text-gray-600">
                                            Kelas {{ $m->kelas }} • TKU {{ strtoupper($m->tingkatan_tku ?? '-') }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <button type="button" onclick="downloadSingleCert({{ $m->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 rounded-xl text-xs font-bold transition">
                                                <i data-lucide="download" class="w-3.5 h-3.5 text-amber-700"></i>
                                                <span>Unduh PDF</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">Regu ini belum memiliki data anggota.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-amber-600">
                        <i data-lucide="award" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-1">Pilih Regu untuk Menerbitkan Sertifikat</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">Silakan pilih regu pada pilihan di atas untuk memuat daftar seluruh anggotanya dan menerbitkan sertifikat resmi.</p>
                </div>
            @endif
        </div>

        {{-- TAB 2: PENGATURAN TEMPLATE & PENOMORAN --}}
        <div id="content-pengaturan" class="hidden space-y-8">
            <form action="{{ route('admin.sertifikat.update-settings') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Template Background Upload --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Template Juara --}}
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-800">
                                        <i data-lucide="trophy" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Template Sertifikat Juara</h3>
                                </div>
                                @if(!empty($settings['cert_template_juara']))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Kustom Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">Bawaan Sistem</span>
                                @endif
                            </div>

                            <p class="text-xs text-gray-500 mb-4">Unggah file gambar latar belakang khusus (A4 Landscape, JPG/PNG, rekomendasi resolusi 3508 x 2480 piksel).</p>

                            @if(!empty($settings['cert_template_juara']))
                                <div class="aspect-[1.414/1] bg-gray-100 rounded-xl overflow-hidden mb-4 border border-gray-200 relative group">
                                    <img src="{{ asset('storage/' . $settings['cert_template_juara']) }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih File Template Baru</label>
                                <input type="file" name="template_juara" accept=".jpg,.jpeg,.png"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200 transition cursor-pointer">
                            </div>
                        </div>

                        @if(!empty($settings['cert_template_juara']))
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                                <label class="inline-flex items-center gap-2 text-xs font-bold text-rose-600 cursor-pointer">
                                    <input type="checkbox" name="delete_template_juara" value="1" class="rounded border-gray-300 text-rose-600">
                                    <span>Hapus template kustom & gunakan desain bawaan</span>
                                </label>
                            </div>
                        @endif
                    </div>

                    {{-- Template Peserta --}}
                    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-800">
                                        <i data-lucide="award" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Template Sertifikat Peserta</h3>
                                </div>
                                @if(!empty($settings['cert_template_peserta']))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Kustom Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">Bawaan Sistem</span>
                                @endif
                            </div>

                            <p class="text-xs text-gray-500 mb-4">Unggah file gambar latar belakang khusus untuk peserta (A4 Landscape, JPG/PNG, 3508 x 2480 piksel).</p>

                            @if(!empty($settings['cert_template_peserta']))
                                <div class="aspect-[1.414/1] bg-gray-100 rounded-xl overflow-hidden mb-4 border border-gray-200 relative group">
                                    <img src="{{ asset('storage/' . $settings['cert_template_peserta']) }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih File Template Baru</label>
                                <input type="file" name="template_peserta" accept=".jpg,.jpeg,.png"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-900 hover:file:bg-emerald-200 transition cursor-pointer">
                            </div>
                        </div>

                        @if(!empty($settings['cert_template_peserta']))
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                                <label class="inline-flex items-center gap-2 text-xs font-bold text-rose-600 cursor-pointer">
                                    <input type="checkbox" name="delete_template_peserta" value="1" class="rounded border-gray-300 text-rose-600">
                                    <span>Hapus template kustom & gunakan desain bawaan</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Penomoran & Identitas Penandatangan --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-6 mb-8">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-base font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="binary" class="w-4 h-4 text-emerald-600"></i>
                            Format Penomoran Otomatis
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Gunakan variabel placeholder: <code>{NOMOR}</code> (urutan nomor), <code>{BULAN}</code> (bulan romawi), <code>{TAHUN}</code> (tahun berjalan), <code>{REGU}</code> (nama regu).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Format Nomor: Sertifikat Juara</label>
                            <input type="text" name="cert_number_format_juara" value="{{ $settings['cert_number_format_juara'] }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs text-gray-500">Nomor Awal (Counter):</span>
                                <input type="number" name="cert_counter_juara" value="{{ $settings['cert_counter_juara'] }}" min="1"
                                    class="w-24 px-3 py-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Format Nomor: Sertifikat Peserta</label>
                            <input type="text" name="cert_number_format_peserta" value="{{ $settings['cert_number_format_peserta'] }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs text-gray-500">Nomor Awal (Counter):</span>
                                <input type="number" name="cert_counter_peserta" value="{{ $settings['cert_counter_peserta'] }}" min="1"
                                    class="w-24 px-3 py-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-base font-black text-brand-forest uppercase tracking-wide mb-4 flex items-center gap-2">
                            <i data-lucide="pen-tool" class="w-4 h-4 text-emerald-600"></i>
                            Identitas Pengesahan & Tanda Tangan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Penandatangan 1 (Mengesahkan)</label>
                                <input type="text" name="cert_signer_name" value="{{ $settings['cert_signer_name'] ?? 'Noormaidah, S. Pd., M.Pd.' }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold mb-2">
                                
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jabatan Penandatangan 1</label>
                                <input type="text" name="cert_signer_title" value="{{ $settings['cert_signer_title'] ?? 'Ka. Mabigus SMP Negeri 1 Cerbon' }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium mb-2">

                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">NIP Penandatangan 1</label>
                                <input type="text" name="cert_signer_nip" value="{{ $settings['cert_signer_nip'] ?? 'NIP. 19790705 200604 2 031' }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Penandatangan 2 (Mengetahui)</label>
                                <input type="text" name="cert_signer_sub_name" value="{{ $settings['cert_signer_sub_name'] ?? 'Rabianti, S.Pd.' }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold mb-2">
                                
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jabatan Penandatangan 2</label>
                                <input type="text" name="cert_signer_sub_title" value="{{ $settings['cert_signer_sub_title'] ?? 'Pembina Pramuka' }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium mb-2">

                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">NIP Penandatangan 2</label>
                                <input type="text" name="cert_signer_sub_nip" value="{{ $settings['cert_signer_sub_nip'] ?? 'NIP. 19940412 202221 2 003' }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium font-mono">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tempat & Tanggal Penerbitan</label>
                                <input type="text" name="cert_location_date" value="{{ $settings['cert_location_date'] }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-sm shadow-sm transition">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Simpan Pengaturan Template & Format</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showCertTab(tab) {
        ['penerbitan', 'pengaturan'].forEach(t => {
            document.getElementById('content-' + t).classList.toggle('hidden', t !== tab);
            const btn = document.getElementById('tab-btn-' + t);
            if (t === tab) {
                btn.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-all bg-brand-forest text-white shadow-sm';
            } else {
                btn.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-gray-600 hover:text-brand-forest hover:bg-gray-50';
            }
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function setPreset(val) {
        document.getElementById('cert_title_input').value = val;
    }

    function updateAwardPreset() {
        const type = document.querySelector('input[name="cert_type"]:checked').value;
        const input = document.getElementById('cert_title_input');
        if (type === 'peserta') {
            input.value = 'PESERTA AKTIF & BERPRESTASI';
        } else {
            input.value = 'JUARA UMUM 1';
        }
    }

    function downloadSingleCert(anggotaId) {
        const type = document.querySelector('input[name="cert_type"]:checked').value;
        const title = document.getElementById('cert_title_input').value;
        const url = `/admin/sertifikat/download-single/${anggotaId}?type=${encodeURIComponent(type)}&title=${encodeURIComponent(title)}`;
        window.open(url, '_blank');
    }

    function downloadAllForRegu(reguId) {
        const type = document.querySelector('input[name="cert_type"]:checked').value;
        const title = document.getElementById('cert_title_input').value;
        const url = `/admin/sertifikat/download-regu/${reguId}?type=${encodeURIComponent(type)}&title=${encodeURIComponent(title)}`;
        window.open(url, '_blank');
    }
</script>
@endpush
