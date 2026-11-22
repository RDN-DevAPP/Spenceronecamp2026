@extends('layouts.main')

@section('title', 'Manajemen Sertifikat Peserta Resmi - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Page Header --}}
        @php
            $activeCertTab = in_array(request('tab'), ['penerbitan', 'panitia', 'juara', 'pengaturan']) ? request('tab') : 'penerbitan';
        @endphp
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 mb-2">
                    <i data-lucide="award" class="w-3.5 h-3.5 text-emerald-700"></i>
                    <span>Manajemen Sertifikat Resmi Gugus Depan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                    Sertifikat Peserta, Panitia &amp; Juara
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Terbitkan sertifikat peserta, panitia (sesuai formulir ID Card), dan juara tiap lomba dengan pengaturan tata letak, TTD, serta QR yang sama.
                </p>
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
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Sub-Tabs (Peserta | Panitia | Juara Lomba | Pengaturan Format) --}}
        <div class="bg-white p-1.5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-wrap gap-1.5 mb-8">
            <button type="button" onclick="showCertTab('penerbitan')" id="tab-btn-penerbitan"
                class="flex-1 min-w-[150px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer {{ $activeCertTab === 'penerbitan' ? 'font-bold bg-brand-forest text-white shadow-sm' : 'font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>Sertifikat Peserta</span>
            </button>
            <button type="button" onclick="showCertTab('panitia')" id="tab-btn-panitia"
                class="flex-1 min-w-[150px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer {{ $activeCertTab === 'panitia' ? 'font-bold bg-brand-forest text-white shadow-sm' : 'font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50' }}">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Sertifikat Panitia ({{ isset($panitiaList) ? $panitiaList->count() : 0 }})</span>
            </button>
            <button type="button" onclick="showCertTab('juara')" id="tab-btn-juara"
                class="flex-1 min-w-[150px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer {{ $activeCertTab === 'juara' ? 'font-bold bg-brand-forest text-white shadow-sm' : 'font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50' }}">
                <i data-lucide="trophy" class="w-4 h-4"></i>
                <span>Sertifikat Juara Lomba</span>
            </button>
            <button type="button" onclick="showCertTab('pengaturan')" id="tab-btn-pengaturan"
                class="flex-1 min-w-[150px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer {{ $activeCertTab === 'pengaturan' ? 'font-bold bg-brand-forest text-white shadow-sm' : 'font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50' }}">
                <i data-lucide="sliders" class="w-4 h-4"></i>
                <span>Pengaturan Format</span>
            </button>
        </div>

        {{-- TAB 1: PENERBITAN SERTIFIKAT PESERTA --}}
        <div id="content-penerbitan" class="{{ $activeCertTab === 'penerbitan' ? '' : 'hidden' }} space-y-8">
            
            {{-- Official Template Banner Info --}}
            <div class="bg-gradient-to-r from-emerald-950 via-brand-forest to-emerald-900 rounded-3xl p-6 text-white shadow-md border-2 border-emerald-600/30 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2">
                    <span class="px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-300/30 text-[10px] font-black uppercase tracking-wider inline-block">
                        Format Standar Gugus Depan 03.059 – 03.060
                    </span>
                    <h3 class="text-lg sm:text-xl font-black text-white tracking-tight">
                        Template Resmi SMP Negeri 1 Cerbon
                    </h3>
                    <p class="text-xs text-emerald-100/90 max-w-2xl leading-relaxed">
                        Sertifikat peserta secara otomatis mencantumkan logo ganda (Tunas Kelapa & WOSM Pandu Dunia), narasi resmi Spencerone Camp II Tahun 2027, serta tanda tangan Ka. Mabigus <strong>Noormaidah, S. Pd., M.Pd.</strong> dan Pembina Pramuka <strong>Rabianti, S.Pd.</strong>
                    </p>
                </div>
                <div class="shrink-0 flex flex-wrap items-center gap-3">
                    <div class="text-center px-4 py-2 rounded-2xl bg-black/40 border border-white/10">
                        <span class="text-[10px] text-gray-400 uppercase block font-semibold">Deteksi Rumus Otomatis</span>
                        <span class="text-xs font-mono font-bold text-amber-300">[$nomor$] &bull; [$nama$]</span>
                    </div>
                    <a href="{{ route('admin.sertifikat.peserta', ['tab' => 'pengaturan', 'scope' => 'peserta']) }}"
                        class="px-4 py-2.5 rounded-xl bg-white text-emerald-950 hover:bg-emerald-50 text-xs font-black transition inline-flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="sliders" class="w-3.5 h-3.5 text-brand-forest"></i> Atur Format Peserta
                    </a>
                </div>
            </div>

            {{-- Filter Regu Peserta --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8">
                <h2 class="text-base font-black text-brand-forest uppercase tracking-wide mb-4 flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4 text-emerald-600"></i>
                    Pilih Regu Peserta
                </h2>

                <form method="GET" action="{{ route('admin.sertifikat.peserta') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-8">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Regu</label>
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
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Status Sertifikat</label>
                        <input type="text" id="cert_title_input" name="cert_title" value="PESERTA" readonly
                            class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-xs font-bold uppercase text-gray-600 cursor-not-allowed">
                    </div>
                </form>
            </div>

            {{-- Members Table for Selected Regu --}}
            @if($selectedRegu)
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-black text-brand-forest uppercase">{{ $selectedRegu->nama_regu }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ (method_exists($selectedRegu, 'isPutra') ? $selectedRegu->isPutra() : (strtolower($selectedRegu->jenis ?? '') === 'putra')) ? 'bg-amber-100 text-amber-900' : 'bg-rose-100 text-rose-900' }}">
                                    Regu {{ $selectedRegu->jenis }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Jumlah Anggota: <strong>{{ $selectedRegu->anggotaRegu->count() }} orang</strong> &bull; Nomor Regu: {{ $selectedRegu->nomor_regu }}
                            </p>
                        </div>

                        <div>
                            <button type="button" onclick="downloadAllPesertaCertificates({{ $selectedRegu->id }})"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition transform active:scale-95 cursor-pointer">
                                <i data-lucide="download-cloud" class="w-4 h-4 text-amber-400"></i>
                                <span>Cetak Seluruh Peserta (PDF Massal)</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/75">
                                <tr>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap Peserta</th>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">NTA / NISN</th>
                                    <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan Regu</th>
                                    <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Sertifikat (Urutan)</th>
                                    <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Aksi Cetak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($selectedRegu->anggotaRegu as $index => $anggota)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <td class="px-6 py-4 text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $anggota->nama }}</div>
                                            <div class="text-xs text-gray-400">Kelas {{ $anggota->kelas }} &bull; TKU {{ strtoupper($anggota->tingkatan_tku ?? '-') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-xs text-gray-500">
                                            {{ $anggota->nta ?? $anggota->nisn ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase {{ $anggota->jabatan === 'pinru' ? 'bg-amber-100 text-amber-900 border border-amber-300' : ($anggota->jabatan === 'wapinru' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-gray-100 text-gray-700') }}">
                                                {{ $anggota->jabatan }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-center whitespace-nowrap">
                                            @if($anggota->sertifikatPeserta)
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200 shadow-2xs">
                                                    <span class="text-[10px] font-black uppercase text-emerald-800 bg-emerald-200/60 px-1.5 py-0.5 rounded font-mono">
                                                        #{{ str_pad($anggota->sertifikatPeserta->nomor_urut, 3, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                    <span class="font-mono text-xs font-bold text-emerald-950">
                                                        {{ $anggota->sertifikatPeserta->nomor_sertifikat }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-semibold">
                                                    <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                                    <span>Belum Terbit (Otomatis saat cetak)</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <button type="button" onclick="downloadSinglePesertaCertificate({{ $anggota->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-brand-forest border border-emerald-200 rounded-lg text-xs font-bold transition cursor-pointer">
                                                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                                <span>Cetak Sertifikat</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-xs">
                                            Belum ada data anggota pada regu ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="award" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Pilih Regu Terlebih Dahulu</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Silakan pilih salah satu regu pada pilihan di atas untuk melihat daftar anggota dan mengunduh sertifikat resmi peserta.</p>
                </div>
            @endif
        </div>

        {{-- TAB 2: PENERBITAN SERTIFIKAT PANITIA (SESUAI DATA FORMULIR ID CARD PANITIA) --}}
        <div id="content-panitia" class="{{ $activeCertTab === 'panitia' ? '' : 'hidden' }} space-y-6">
            <div class="bg-gradient-to-r from-brand-forest to-emerald-900 rounded-3xl p-6 text-white shadow-md flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-emerald-700/40">
                <div class="space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950">
                        <i data-lucide="shield-check" class="w-3 h-3"></i> Data Otomatis dari Formulir ID Card Panitia
                    </span>
                    <h3 class="text-lg font-black text-white">Penerbitan Sertifikat Panitia Setelah Kegiatan</h3>
                    <p class="text-xs text-emerald-100 max-w-2xl leading-relaxed">
                        Semua panitia yang mengisi <strong>Formulir ID Card Panitia</strong> otomatis terdaftar di bawah ini dan dapat diterbitkan sertifikatnya dengan pengaturan format, template, TTD, dan posisi QR khusus Panitia.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('admin.sertifikat.peserta', ['tab' => 'pengaturan', 'scope' => 'panitia']) }}"
                        class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition inline-flex items-center gap-1.5">
                        <i data-lucide="sliders" class="w-3.5 h-3.5"></i> Atur Format Panitia
                    </a>
                    <a href="{{ route('panitia.form') }}" target="_blank"
                        class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition inline-flex items-center gap-1.5">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Buka Formulir Panitia
                    </a>
                    <a href="{{ route('admin.id-cards.index', ['tab' => 'panitia']) }}"
                        class="px-4 py-2.5 rounded-xl bg-white text-emerald-950 hover:bg-emerald-50 text-xs font-black transition inline-flex items-center gap-1.5 shadow-xs">
                        <i data-lucide="id-card" class="w-3.5 h-3.5 text-brand-forest"></i> Kelola ID Card Panitia
                    </a>
                    @if(isset($panitiaList) && $panitiaList->isNotEmpty())
                        <a href="{{ route('admin.sertifikat.panitia.all') }}" target="_blank"
                            class="px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 text-xs font-black uppercase tracking-wider transition inline-flex items-center gap-2 shadow-md">
                            <i data-lucide="download-cloud" class="w-4 h-4"></i> Cetak Semua Panitia (PDF Massal)
                        </a>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-xs">
                        <thead class="bg-gray-50/75 text-gray-600 uppercase font-bold">
                            <tr>
                                <th class="px-5 py-3.5 text-left w-12">No</th>
                                <th class="px-5 py-3.5 text-left">Nama Lengkap Panitia</th>
                                <th class="px-4 py-3.5 text-left">Jabatan &amp; Divisi</th>
                                <th class="px-4 py-3.5 text-left">Instansi / Pangkalan</th>
                                <th class="px-5 py-3.5 text-center">Nomor Sertifikat</th>
                                <th class="px-5 py-3.5 text-right">Aksi Cetak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse(($panitiaList ?? collect()) as $idx => $p)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-5 py-4 font-bold text-gray-500">{{ $idx + 1 }}</td>
                                    <td class="px-5 py-4 font-bold text-gray-900 text-sm">{{ $p->nama }}</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-amber-100 text-amber-900 border border-amber-200">{{ $p->jabatan }}</span>
                                        @if($p->divisi)
                                            <div class="text-[11px] text-gray-600 mt-1">{{ $p->divisi }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-gray-700 font-medium">{{ $p->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}</td>
                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        @if($p->nomor_sertifikat)
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200">
                                                <span class="font-mono text-xs font-bold text-emerald-950">{{ $p->nomor_sertifikat }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-semibold">
                                                <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i> Otomatis saat cetak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.sertifikat.panitia.single', $p->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-brand-forest border border-emerald-200 rounded-lg text-xs font-bold transition">
                                            <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Sertifikat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada data panitia dari formulir. Bagikan link <a href="{{ route('panitia.form') }}" target="_blank" class="text-brand-forest font-bold underline">Formulir ID Card Panitia</a> terlebih dahulu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 3: PENERBITAN SERTIFIKAT JUARA UNTUK SETIAP LOMBA --}}
        <div id="content-juara" class="{{ $activeCertTab === 'juara' ? '' : 'hidden' }} space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <form method="GET" action="{{ route('admin.sertifikat.peserta') }}" class="flex-1 max-w-xl">
                        <input type="hidden" name="tab" value="juara">
                        <label for="select-juara-lomba" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Pilih Mata Lomba untuk Penerbitan Sertifikat Juara
                        </label>
                        <select name="lomba_id" id="select-juara-lomba" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            @forelse(($mataLombaList ?? collect()) as $ml)
                                <option value="{{ $ml->id }}" {{ ($selectedLomba && $selectedLomba->id === $ml->id) ? 'selected' : '' }}>
                                    {{ $ml->nama }} ({{ ucfirst($ml->kategori ?? 'Lomba') }})
                                </option>
                            @empty
                                <option value="">Belum ada Mata Lomba</option>
                            @endforelse
                        </select>
                    </form>

                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <a href="{{ route('admin.sertifikat.peserta', ['tab' => 'pengaturan', 'scope' => 'juara']) }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold border border-gray-200 transition">
                            <i data-lucide="sliders" class="w-3.5 h-3.5 text-brand-forest"></i>
                            <span>Atur Format Juara</span>
                        </a>
                        @if($selectedLomba)
                            <a href="{{ route('admin.sertifikat.juara.lomba-all', ['mataLomba' => $selectedLomba->id, 'mode' => 'regu']) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold shadow-sm transition">
                                <i data-lucide="trophy" class="w-4 h-4 text-amber-400"></i>
                                <span>Cetak Semua Juara (Nama Regu)</span>
                            </a>
                            <a href="{{ route('admin.sertifikat.juara.lomba-all', ['mataLomba' => $selectedLomba->id, 'mode' => 'anggota']) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-emerald-950 text-xs font-black shadow-sm transition">
                                <i data-lucide="users" class="w-4 h-4"></i>
                                <span>Cetak Semua Juara (Per Anggota)</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($selectedLomba)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach(['putra' => ['label' => 'Kategori Regu Putra', 'badge' => 'bg-blue-100 text-blue-900 border-blue-200'], 'putri' => ['label' => 'Kategori Regu Putri', 'badge' => 'bg-rose-100 text-rose-900 border-rose-200']] as $gKey => $gMeta)
                        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $gMeta['badge'] }}">{{ $gMeta['label'] }}</span>
                                    <h3 class="text-base font-black text-brand-forest mt-1.5">{{ $selectedLomba->nama }}</h3>
                                </div>
                                <i data-lucide="award" class="w-6 h-6 text-amber-500"></i>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse(($lombaWinners[$gKey] ?? []) as $w)
                                    @php $reguWin = $w['regu']; @endphp
                                    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-50/70">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 {{ $w['rank'] === 1 ? 'bg-amber-400 text-emerald-950' : ($w['rank'] === 2 ? 'bg-gray-200 text-gray-800' : 'bg-orange-200 text-orange-950') }}">
                                                #{{ $w['rank'] }}
                                            </div>
                                            <div>
                                                <div class="text-xs font-black uppercase text-amber-700">{{ $w['peringkat'] }}</div>
                                                <div class="text-sm font-black text-gray-900 uppercase">Regu {{ $reguWin->nama_regu }}</div>
                                                <div class="text-[11px] text-gray-600 font-mono">Total Nilai: <strong>{{ $w['total_nilai'] }}</strong> &bull; {{ $reguWin->anggotaRegu->count() }} Anggota</div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-1.5 shrink-0">
                                            <a href="{{ route('admin.sertifikat.juara.download', ['mata_lomba_id' => $selectedLomba->id, 'regu_id' => $reguWin->id, 'kategori' => ucfirst($gKey), 'peringkat' => $w['peringkat'], 'mode' => 'regu']) }}" target="_blank"
                                                class="px-3 py-1.5 rounded-lg bg-brand-forest hover:bg-emerald-900 text-white text-[11px] font-bold transition inline-flex items-center gap-1">
                                                <i data-lucide="printer" class="w-3 h-3 text-amber-400"></i> Cetak Regu
                                            </a>
                                            <a href="{{ route('admin.sertifikat.juara.download', ['mata_lomba_id' => $selectedLomba->id, 'regu_id' => $reguWin->id, 'kategori' => ucfirst($gKey), 'peringkat' => $w['peringkat'], 'mode' => 'anggota']) }}" target="_blank"
                                                class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-brand-forest border border-emerald-200 text-[11px] font-bold transition inline-flex items-center gap-1">
                                                <i data-lucide="users" class="w-3 h-3"></i> Per Anggota
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-xs text-gray-500">
                                        Belum ada nilai juri tersubmit pada kategori ini untuk {{ $selectedLomba->nama }}.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Form Penerbitan Sertifikat Juara Manual / Tambahan --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-4">
                <div class="border-b border-gray-100 pb-3">
                    <h3 class="text-base font-black text-brand-forest uppercase flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-amber-600"></i>
                        Terbitkan Sertifikat Juara Manual / Khusus
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Gunakan formulir ini untuk menerbitkan sertifikat juara umum, harapan, atau jika penentuan pemenang perlu dipilih secara manual.</p>
                </div>

                <form method="GET" action="{{ route('admin.sertifikat.juara.download') }}" target="_blank" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end text-xs">
                    <div class="md:col-span-3">
                        <label class="block font-bold text-gray-700 uppercase mb-1.5">Mata Lomba</label>
                        <select name="mata_lomba_id" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-semibold text-gray-900">
                            @foreach(($mataLombaList ?? collect()) as $ml)
                                <option value="{{ $ml->id }}" {{ ($selectedLomba && $selectedLomba->id === $ml->id) ? 'selected' : '' }}>{{ $ml->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block font-bold text-gray-700 uppercase mb-1.5">Pilih Regu Pemenang</label>
                        <select name="regu_id" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-semibold text-gray-900">
                            <option value="">-- Atau ketik nama manual --</option>
                            @foreach($regus as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_regu }} ({{ ucfirst($r->jenis) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold text-gray-700 uppercase mb-1.5">Nama Manual (Opsional)</label>
                        <input type="text" name="nama_penerima" placeholder="Jika tanpa regu..." class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-semibold text-gray-900">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold text-gray-700 uppercase mb-1.5">Peringkat</label>
                        <input type="text" name="peringkat" value="Juara 1" required placeholder="Juara 1, Juara Umum..." class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-bold text-gray-900">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold text-gray-700 uppercase mb-1.5">Mode Cetak</label>
                        <select name="mode" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 font-bold text-gray-900">
                            <option value="regu">Nama Regu / Tunggal</option>
                            <option value="anggota">Per Anggota Regu</option>
                        </select>
                    </div>
                    <div class="md:col-span-12 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white font-black uppercase tracking-wider cursor-pointer transition inline-flex items-center gap-2 shadow-sm">
                            <i data-lucide="printer" class="w-4 h-4 text-amber-400"></i>
                            <span>Terbitkan &amp; Cetak PDF Sertifikat Juara</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB 4: PENGATURAN FORMAT & TATA LETAK PER JENIS SERTIFIKAT --}}
        <div id="content-pengaturan" class="{{ $activeCertTab === 'pengaturan' ? '' : 'hidden' }} space-y-8">
            @php
                $activeScope = $activeScope ?? 'peserta';
                $scopeOptions = [
                    'peserta' => [
                        'label' => 'Sertifikat Peserta',
                        'shortLabel' => 'Peserta',
                        'icon' => 'award',
                        'subtitle' => 'Nomor, Nama Peserta, Jabatan/Regu (opsional), TTD & QR Code',
                        'nomorSample' => 'Nomor : 001/SC-II/SERTIFIKAT/XI/2026',
                        'namaBadge' => 'Nama Peserta',
                        'namaSample' => 'MUHAMMAD RIZKY PRATAMA',
                        'ketBadge' => 'Jabatan & Regu',
                        'ketSample' => 'ANGGOTA REGU • RAJAWALI SMPN 1 CERBON',
                        'defaultNomorFormat' => '{NOMOR}/SC-II/SERTIFIKAT/{BULAN}/{TAHUN}',
                        'counterHint' => 'Nomor urut awal untuk peserta yang belum pernah dicetak.',
                        'bannerTitle' => 'Unduh Berkas Contoh Template Sertifikat Peserta Resmi',
                        'templateTitle' => 'Template Background Sertifikat Peserta',
                        'numberTitle' => 'Format Penomoran Sertifikat Peserta',
                        'saveLabel' => 'Simpan Pengaturan Sertifikat Peserta',
                    ],
                    'panitia' => [
                        'label' => 'Sertifikat Panitia',
                        'shortLabel' => 'Panitia',
                        'icon' => 'shield-check',
                        'subtitle' => 'Nomor, Nama Panitia, Jabatan Kepanitiaan, TTD & QR Code',
                        'nomorSample' => 'Nomor : 001/SC-II/PANITIA/XI/2026',
                        'namaBadge' => 'Nama Panitia',
                        'namaSample' => 'AHMAD FAUZI, S.PD.',
                        'ketBadge' => 'Jabatan Kepanitiaan',
                        'ketSample' => 'KETUA PANITIA • DIVISI ACARA',
                        'defaultNomorFormat' => '{NOMOR}/SC-II/PANITIA/{BULAN}/{TAHUN}',
                        'counterHint' => 'Nomor urut awal untuk panitia yang belum pernah dicetak.',
                        'bannerTitle' => 'Unduh Berkas Contoh Template Sertifikat Panitia',
                        'templateTitle' => 'Template Background Sertifikat Panitia',
                        'numberTitle' => 'Format Penomoran Sertifikat Panitia',
                        'saveLabel' => 'Simpan Pengaturan Sertifikat Panitia',
                    ],
                    'juara' => [
                        'label' => 'Sertifikat Juara Lomba',
                        'shortLabel' => 'Juara Lomba',
                        'icon' => 'trophy',
                        'subtitle' => 'Nomor, Nama Pemenang/Regu, Peringkat Juara, Mata Lomba, TTD & QR Code',
                        'nomorSample' => 'Nomor : 001/SC-II/JUARA/XI/2026',
                        'namaBadge' => 'Nama Pemenang / Regu',
                        'namaSample' => 'REGU GARUDA PUTRA • SMPN 1 CERBON',
                        'ketBadge' => 'Peringkat Juara',
                        'ketSample' => 'JUARA 1 PUTRA',
                        'defaultNomorFormat' => '{NOMOR}/SC-II/JUARA/{BULAN}/{TAHUN}',
                        'counterHint' => 'Nomor urut awal untuk sertifikat juara lomba yang belum pernah dicetak.',
                        'bannerTitle' => 'Unduh Berkas Contoh Template Sertifikat Juara Lomba',
                        'templateTitle' => 'Template Background Sertifikat Juara Lomba',
                        'numberTitle' => 'Format Penomoran Sertifikat Juara Lomba',
                        'saveLabel' => 'Simpan Pengaturan Sertifikat Juara Lomba',
                    ],
                ];
                $scopeMeta = $scopeOptions[$activeScope] ?? $scopeOptions['peserta'];

                $activeTemplateUrl = !empty($settings['cert_template_peserta']) 
                    ? asset('storage/' . $settings['cert_template_peserta']) 
                    : asset('images/certificates/template-peserta-official.png');

                $ttdCount = max(0, min(4, (int) ($settings['cert_ttd_count'] ?? (($settings['cert_show_ttd'] ?? '1') == '1' ? 1 : 0))));
                $showQr = ($settings['cert_show_qr'] ?? '1') == '1';
                $showKet = ($settings['cert_show_ket'] ?? ($activeScope === 'peserta' ? '0' : '1')) == '1';
                $showLomba = ($settings['cert_show_lomba'] ?? ($activeScope === 'juara' ? '1' : '0')) == '1';

                $ttdSlotConfigs = [
                    1 => ['sigKey' => 'cert_signature_peserta',   'prefix' => 'cert_pos_ttd',  'defTop' => 72.0, 'defLeft' => 68.0, 'defWidth' => 18.0, 'badgeBg' => 'bg-amber-600',  'border' => 'border-amber-500/80 hover:border-amber-600 bg-amber-50/25 hover:bg-amber-50/40',  'accent' => 'accent-amber-600',  'textCol' => 'text-amber-800',  'dotBg' => 'bg-amber-600'],
                    2 => ['sigKey' => 'cert_signature_peserta_2', 'prefix' => 'cert_pos_ttd2', 'defTop' => 72.0, 'defLeft' => 14.0, 'defWidth' => 18.0, 'badgeBg' => 'bg-purple-600', 'border' => 'border-purple-500/80 hover:border-purple-600 bg-purple-50/25 hover:bg-purple-50/40', 'accent' => 'accent-purple-600', 'textCol' => 'text-purple-800', 'dotBg' => 'bg-purple-600'],
                    3 => ['sigKey' => 'cert_signature_peserta_3', 'prefix' => 'cert_pos_ttd3', 'defTop' => 72.0, 'defLeft' => 41.0, 'defWidth' => 18.0, 'badgeBg' => 'bg-cyan-600',   'border' => 'border-cyan-500/80 hover:border-cyan-600 bg-cyan-50/25 hover:bg-cyan-50/40',     'accent' => 'accent-cyan-600',   'textCol' => 'text-cyan-800',   'dotBg' => 'bg-cyan-600'],
                    4 => ['sigKey' => 'cert_signature_peserta_4', 'prefix' => 'cert_pos_ttd4', 'defTop' => 56.0, 'defLeft' => 41.0, 'defWidth' => 18.0, 'badgeBg' => 'bg-rose-600',   'border' => 'border-rose-500/80 hover:border-rose-600 bg-rose-50/25 hover:bg-rose-50/40',     'accent' => 'accent-rose-600',   'textCol' => 'text-rose-800',   'dotBg' => 'bg-rose-600'],
                ];

                $ttdSlots = [];
                foreach ($ttdSlotConfigs as $idx => $cfg) {
                    $sigVal = $settings[$cfg['sigKey']] ?? null;
                    $ttdSlots[$idx] = array_merge($cfg, [
                        'index' => $idx,
                        'label' => $settings["cert_ttd_label_{$idx}"] ?? "TTD {$idx}",
                        'sigUrl' => !empty($sigVal) ? asset('storage/' . $sigVal) : null,
                        'top' => $settings["{$cfg['prefix']}_top"] ?? $cfg['defTop'],
                        'left' => $settings["{$cfg['prefix']}_left"] ?? $cfg['defLeft'],
                        'width' => $settings["{$cfg['prefix']}_width"] ?? $cfg['defWidth'],
                    ]);
                }

                $qrTop = $settings['cert_pos_qr_top'] ?? 82.0;
                $qrLeft = $settings['cert_pos_qr_left'] ?? 88.0;
                $qrWidth = $settings['cert_pos_qr_width'] ?? 8.0;
            @endphp

            {{-- SCOPE SWITCHER: PILIH JENIS SERTIFIKAT YANG DIATUR --}}
            <div class="bg-white rounded-2xl border border-emerald-200/80 shadow-sm p-5 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 block">Pengaturan Terpisah Per Jenis Sertifikat</span>
                        <h3 class="text-sm sm:text-base font-black text-brand-forest">Pilih Jenis Sertifikat yang Ingin Diatur</h3>
                    </div>
                    <span class="text-xs text-gray-500">Sedang mengedit: <strong class="text-brand-forest">{{ $scopeMeta['label'] }}</strong></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($scopeOptions as $sKey => $sInfo)
                        @php $isScopeActive = $activeScope === $sKey; @endphp
                        <a href="{{ route('admin.sertifikat.peserta', ['tab' => 'pengaturan', 'scope' => $sKey]) }}"
                            class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition-all {{ $isScopeActive ? 'border-brand-forest bg-emerald-50/70 shadow-xs' : 'border-gray-200 bg-gray-50/50 hover:border-emerald-300 hover:bg-white' }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $isScopeActive ? 'bg-brand-forest text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                                <i data-lucide="{{ $sInfo['icon'] }}" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-black {{ $isScopeActive ? 'text-brand-forest' : 'text-gray-800' }}">{{ $sInfo['label'] }}</span>
                                    @if($isScopeActive)
                                        <span class="px-1.5 py-0.5 rounded bg-brand-forest text-white text-[9px] font-black uppercase">Aktif</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">{{ $sInfo['subtitle'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('admin.sertifikat.update-settings') }}" enctype="multipart/form-data" id="form-cert-settings">
                @csrf
                <input type="hidden" name="scope" value="{{ $activeScope }}">
                <input type="hidden" name="signature_data" id="input-signature-data" value="">
                @for($i = 1; $i <= 4; $i++)
                    <input type="hidden" name="signature_data_{{ $i }}" id="input-signature-data-{{ $i }}" value="">
                @endfor

                {{-- Banner Download Contoh Template --}}
                <div class="bg-gradient-to-r from-emerald-900 to-brand-forest rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 border border-emerald-700/50">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950">
                            <i data-lucide="sparkles" class="w-3 h-3"></i> Pengaturan Khusus {{ $scopeMeta['label'] }}
                        </div>
                        <h4 class="text-base font-black text-white">{{ $scopeMeta['bannerTitle'] }}</h4>
                        <p class="text-xs text-emerald-100/90 max-w-2xl leading-relaxed">
                            Perubahan pada halaman ini <strong>hanya berlaku untuk {{ $scopeMeta['label'] }}</strong> ({{ $scopeMeta['subtitle'] }}). Atur jumlah TTD (0&ndash;4), geser posisi teks, template, QR Code, dan penomoran secara independen.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <a href="{{ route('admin.sertifikat.download-sample', ['format' => 'pdf']) }}"
                            class="px-3.5 py-2 rounded-xl bg-white text-emerald-950 hover:bg-emerald-50 text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm transition">
                            <i data-lucide="file-text" class="w-4 h-4 text-rose-600"></i> Download Template (.PDF)
                        </a>
                        <a href="{{ route('admin.sertifikat.download-sample', ['format' => 'png']) }}"
                            class="px-3.5 py-2 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 border border-emerald-600/60 shadow-sm transition">
                            <i data-lucide="image" class="w-4 h-4 text-amber-300"></i> Download Template (.PNG)
                        </a>
                    </div>
                </div>

                {{-- SECTION 1: PENGATURAN JUMLAH TTD & KOTAK TANDA TANGAN DIGITAL --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-6 mb-8">
                    <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-base font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                                <i data-lucide="pen-tool" class="w-4 h-4 text-emerald-600"></i>
                                Tanda Tangan Digital Sertifikat (TTD)
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Atur jumlah tanda tangan yang tampil di sertifikat (0 sampai 4 TTD), lalu goreskan atau unggah gambar TTD untuk masing-masing slot.
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 px-3.5 py-2 rounded-xl shrink-0">
                            <label for="select-ttd-count" class="text-xs font-black text-emerald-950 uppercase tracking-wide">Jumlah TTD:</label>
                            <select name="cert_ttd_count" id="select-ttd-count" onchange="updateTtdCount(this.value)"
                                class="px-3 py-1.5 rounded-lg bg-white border border-emerald-300 text-xs font-black text-brand-forest focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                <option value="0" {{ $ttdCount === 0 ? 'selected' : '' }}>0 TTD (Tidak Ditampilkan)</option>
                                <option value="1" {{ $ttdCount === 1 ? 'selected' : '' }}>1 TTD</option>
                                <option value="2" {{ $ttdCount === 2 ? 'selected' : '' }}>2 TTD</option>
                                <option value="3" {{ $ttdCount === 3 ? 'selected' : '' }}>3 TTD</option>
                                <option value="4" {{ $ttdCount === 4 ? 'selected' : '' }}>4 TTD</option>
                            </select>
                        </div>
                    </div>

                    <div id="ttd-config-empty" class="{{ $ttdCount === 0 ? '' : 'hidden' }} p-6 rounded-xl bg-gray-50 border border-dashed border-gray-300 text-center text-xs text-gray-600">
                        Tanda tangan dinonaktifkan (<strong>0 TTD</strong>). Pilih minimal <strong>1 TTD</strong> pada dropdown <em>Jumlah TTD</em> di atas jika ingin menambahkan tanda tangan digital.
                    </div>

                    <div id="ttd-config-body" class="{{ $ttdCount === 0 ? 'hidden' : '' }} grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        {{-- Signature Canvas Box --}}
                        <div class="lg:col-span-5 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Gores Tanda Tangan Untuk:
                                </label>
                                <div class="flex items-center gap-1" id="pad-slot-selector">
                                    @for($i = 1; $i <= 4; $i++)
                                        <button type="button" onclick="selectPadSlot({{ $i }})" id="pad-slot-btn-{{ $i }}"
                                            style="{{ $i <= $ttdCount ? '' : 'display: none;' }}"
                                            class="px-2.5 py-1 rounded-lg text-[11px] font-black transition cursor-pointer {{ $i === 1 ? 'bg-brand-forest text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                            TTD {{ $i }}
                                        </button>
                                    @endfor
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-emerald-300/80 rounded-2xl p-2 bg-gradient-to-b from-gray-50 to-white shadow-inner relative">
                                <canvas id="signature-pad" width="460" height="170"
                                    class="w-full h-40 bg-white rounded-xl border border-gray-200 cursor-crosshair touch-none shadow-2xs"></canvas>
                                <div id="pad-hint" class="pointer-events-none absolute inset-0 flex items-center justify-center text-xs font-medium text-gray-500 select-none">
                                    <span class="flex items-center gap-1.5 bg-white/95 px-3 py-1 rounded-full border border-gray-200 shadow-xs">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        <span>Goreskan <strong id="pad-active-slot-label">TTD 1</strong> di sini</span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-2 pt-1">
                                <button type="button" onclick="clearSignaturePad()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition cursor-pointer">
                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                    <span>Hapus Goresan</span>
                                </button>
                                <button type="button" onclick="applySignatureToPreview()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Terapkan ke <span id="btn-apply-slot-label">TTD 1</span></span>
                                </button>
                            </div>
                        </div>

                        {{-- Upload File & Status Cards per Active TTD Slot --}}
                        <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($ttdSlots as $i => $slot)
                                <div id="ttd-slot-card-{{ $i }}" style="{{ $i <= $ttdCount ? '' : 'display: none;' }}"
                                    class="p-4 rounded-xl border border-gray-200 bg-gray-50/80 space-y-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $slot['dotBg'] }} shrink-0"></span>
                                            <input type="text" name="cert_ttd_label_{{ $i }}" id="input-ttd-label-{{ $i }}"
                                                value="{{ $slot['label'] }}" oninput="updateTtdBadgeLabel({{ $i }}, this.value)"
                                                placeholder="Label TTD {{ $i }}"
                                                class="w-full px-2 py-1 rounded-lg bg-white border border-gray-200 text-xs font-black text-gray-900 focus:border-emerald-500">
                                        </div>
                                        <button type="button" onclick="selectPadSlot({{ $i }})"
                                            class="shrink-0 px-2 py-1 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-900 text-[10px] font-bold transition cursor-pointer">
                                            Gores Pad
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-lg border border-gray-200">
                                        <div class="w-20 h-12 bg-gray-50 border border-gray-200 rounded-md flex items-center justify-center p-1 overflow-hidden shrink-0" id="slot-thumb-wrap-{{ $i }}">
                                            @if(!empty($slot['sigUrl']))
                                                <img src="{{ $slot['sigUrl'] }}" id="current-saved-ttd-img-{{ $i }}" class="max-w-full max-h-full object-contain">
                                            @else
                                                <span class="text-[9px] text-gray-500 font-semibold text-center leading-tight">Kosong</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 space-y-1">
                                            @if(!empty($slot['sigUrl']))
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-800 bg-emerald-100/70 px-1.5 py-0.5 rounded">
                                                    <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i> Tersimpan
                                                </span>
                                                <label class="flex items-center gap-1.5 text-[11px] font-bold text-rose-600 cursor-pointer">
                                                    <input type="checkbox" name="delete_signature_{{ $i }}" value="1" onchange="handleDeleteSignatureCheck({{ $i }}, this.checked)" class="rounded border-gray-300 text-rose-600">
                                                    <span>Hapus TTD {{ $i }}</span>
                                                </label>
                                            @else
                                                <span class="text-[10px] text-gray-500 block">Gores di pad kiri atau unggah PNG.</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <input type="file" name="signature_file_{{ $i }}" accept=".png,.jpg,.jpeg" onchange="handleSigFileUpload(event, {{ $i }})"
                                            class="w-full px-2 py-1.5 bg-white border border-gray-200 rounded-lg text-[11px] text-gray-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-emerald-100 file:text-emerald-900 hover:file:bg-emerald-200 transition cursor-pointer">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: EDITOR VISUAL TATA LETAK & POSISI ELEMEN (LIVE DRAG & DROP) --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-6 mb-8">
                    <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-base font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                                <i data-lucide="move" class="w-4 h-4 text-emerald-600"></i>
                                Editor Visual Tata Letak Sertifikat (Drag &amp; Drop)
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Geser kotak <strong>Nomor</strong>, <strong>Nama</strong>, <strong>QR Code</strong>, maupun <strong>Tanda Tangan (TTD 1&ndash;4)</strong> langsung di atas template sertifikat di bawah ini.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="resetCertificatePositions()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition shadow-xs cursor-pointer">
                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                <span>Reset Posisi Bawaan</span>
                            </button>
                        </div>
                    </div>

                    {{-- LIVE INTERACTIVE CERTIFICATE BOARD --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs text-gray-500 px-1">
                            <span class="font-semibold flex items-center gap-1.5">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500"></i>
                                Pratinjau Interaktif (Skala Proporsional A4 Landscape)
                            </span>
                            <span class="text-[11px] text-gray-500">Klik &amp; seret elemen untuk memindahkan posisi</span>
                        </div>

                        <div class="relative w-full max-w-5xl mx-auto rounded-2xl overflow-hidden border-2 border-emerald-950/20 shadow-xl bg-gray-100 select-none"
                            id="cert-preview-board" style="aspect-ratio: 297 / 210;">
                            
                            {{-- Background Template Image --}}
                            <img src="{{ $activeTemplateUrl }}" id="preview-bg-template"
                                class="absolute inset-0 w-full h-full object-fill pointer-events-none z-0" alt="Template Sertifikat">

                            {{-- ELEMENT 1: NOMOR SERTIFIKAT (DRAGGABLE) --}}
                            <div id="drag-nomor"
                                style="position: absolute; top: {{ $settings['cert_pos_nomor_top'] ?? 35.2 }}%; left: {{ $settings['cert_pos_nomor_left'] ?? 10.0 }}%; width: {{ $settings['cert_pos_nomor_width'] ?? 80.0 }}%; text-align: {{ $settings['cert_pos_nomor_align'] ?? 'center' }}; z-index: 20; cursor: move;"
                                class="group border-2 border-dashed border-blue-500/80 hover:border-blue-600 bg-blue-50/25 hover:bg-blue-50/40 rounded-lg p-1 transition-colors">
                                <div class="pointer-events-none absolute -top-5 left-2 px-1.5 py-0.5 rounded bg-blue-600 text-white text-[9px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1">
                                    <i data-lucide="move" class="w-2.5 h-2.5"></i> Nomor Surat
                                </div>
                                <span id="preview-nomor-text"
                                    style="font-size: clamp(8px, 1.4vw, {{ ($settings['cert_pos_nomor_size'] ?? 11) * 1.5 }}px); font-family: Arial, Helvetica, sans-serif;"
                                    class="font-bold text-gray-900 tracking-wide block truncate">
                                    {{ $scopeMeta['nomorSample'] }}
                                </span>
                            </div>

                            {{-- ELEMENT 2: NAMA LENGKAP (DRAGGABLE) --}}
                            <div id="drag-nama"
                                style="position: absolute; top: {{ $settings['cert_pos_nama_top'] ?? 43.8 }}%; left: {{ $settings['cert_pos_nama_left'] ?? 10.0 }}%; width: {{ $settings['cert_pos_nama_width'] ?? 80.0 }}%; text-align: {{ $settings['cert_pos_nama_align'] ?? 'center' }}; z-index: 21; cursor: move;"
                                class="group border-2 border-dashed border-emerald-600/80 hover:border-emerald-700 bg-emerald-50/25 hover:bg-emerald-50/40 rounded-lg p-1 transition-colors">
                                <div class="pointer-events-none absolute -top-5 left-2 px-1.5 py-0.5 rounded bg-emerald-700 text-white text-[9px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1">
                                    <i data-lucide="move" class="w-2.5 h-2.5"></i> {{ $scopeMeta['namaBadge'] }}
                                </div>
                                <span id="preview-nama-text"
                                    style="font-size: clamp(12px, 2.4vw, {{ ($settings['cert_pos_nama_size'] ?? 24) * 1.5 }}px); font-family: 'Times New Roman', serif;"
                                    class="font-black text-emerald-950 uppercase tracking-wider inline-block border-b-2 border-emerald-950 px-2 truncate max-w-full">
                                    {{ $scopeMeta['namaSample'] }}
                                </span>
                            </div>

                            {{-- ELEMENT 2b: KETERANGAN / JABATAN PANITIA / PERINGKAT JUARA (DRAGGABLE) --}}
                            <div id="drag-ket"
                                style="position: absolute; top: {{ $settings['cert_pos_ket_top'] ?? 52.8 }}%; left: {{ $settings['cert_pos_ket_left'] ?? 10.0 }}%; width: {{ $settings['cert_pos_ket_width'] ?? 80.0 }}%; text-align: {{ $settings['cert_pos_ket_align'] ?? 'center' }}; z-index: 21; cursor: move; {{ $showKet ? '' : 'display: none;' }}"
                                class="group border-2 border-dashed border-indigo-500/80 hover:border-indigo-600 bg-indigo-50/25 hover:bg-indigo-50/40 rounded-lg p-1 transition-colors">
                                <div class="pointer-events-none absolute -top-5 left-2 px-1.5 py-0.5 rounded bg-indigo-600 text-white text-[9px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1 whitespace-nowrap">
                                    <i data-lucide="move" class="w-2.5 h-2.5"></i> {{ $scopeMeta['ketBadge'] }}
                                </div>
                                <span id="preview-ket-text"
                                    style="font-size: clamp(9px, 1.5vw, {{ ($settings['cert_pos_ket_size'] ?? 13) * 1.5 }}px); font-family: Arial, Helvetica, sans-serif;"
                                    class="font-bold text-brand-forest uppercase tracking-wide block truncate">
                                    {{ $scopeMeta['ketSample'] }}
                                </span>
                            </div>

                            @if($activeScope === 'juara')
                                {{-- ELEMENT 2c: NAMA MATA LOMBA KHUSUS JUARA LOMBA (DRAGGABLE) --}}
                                <div id="drag-lomba"
                                    style="position: absolute; top: {{ $settings['cert_pos_lomba_top'] ?? 58.2 }}%; left: {{ $settings['cert_pos_lomba_left'] ?? 10.0 }}%; width: {{ $settings['cert_pos_lomba_width'] ?? 80.0 }}%; text-align: {{ $settings['cert_pos_lomba_align'] ?? 'center' }}; z-index: 21; cursor: move; {{ $showLomba ? '' : 'display: none;' }}"
                                    class="group border-2 border-dashed border-amber-500/90 hover:border-amber-600 bg-amber-50/30 hover:bg-amber-50/50 rounded-lg p-1 transition-colors">
                                    <div class="pointer-events-none absolute -top-5 left-2 px-1.5 py-0.5 rounded bg-amber-600 text-white text-[9px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1 whitespace-nowrap">
                                        <i data-lucide="move" class="w-2.5 h-2.5"></i> Mata Lomba
                                    </div>
                                    <span id="preview-lomba-text"
                                        style="font-size: clamp(9px, 1.4vw, {{ ($settings['cert_pos_lomba_size'] ?? 12) * 1.5 }}px); font-family: Arial, Helvetica, sans-serif;"
                                        class="font-bold text-amber-900 uppercase tracking-wide block truncate">
                                        LOMBA PBB &amp; VARIASI FORMASI
                                    </span>
                                </div>
                            @endif

                            {{-- ELEMENT 3..6: TANDA TANGAN RESMI SLOT 1..4 (DRAGGABLE) --}}
                            @foreach($ttdSlots as $i => $slot)
                                <div id="drag-ttd-{{ $i }}"
                                    style="position: absolute; top: {{ $slot['top'] }}%; left: {{ $slot['left'] }}%; width: {{ $slot['width'] }}%; z-index: {{ 22 + $i }}; cursor: move; {{ $i <= $ttdCount ? '' : 'display: none;' }}"
                                    class="group border-2 border-dashed {{ $slot['border'] }} rounded-lg p-1 text-center transition-colors">
                                    <div class="pointer-events-none absolute -top-5 left-1 px-1.5 py-0.5 rounded {{ $slot['badgeBg'] }} text-white text-[9px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1 whitespace-nowrap">
                                        <i data-lucide="move" class="w-2.5 h-2.5"></i>
                                        <span id="drag-ttd-badge-{{ $i }}">{{ $slot['label'] }}</span>
                                    </div>
                                    <div id="preview-ttd-container-{{ $i }}" class="w-full flex items-center justify-center min-h-[26px]">
                                        @if(!empty($slot['sigUrl']))
                                            <img src="{{ $slot['sigUrl'] }}" id="preview-ttd-img-{{ $i }}" class="w-full h-auto max-h-24 object-contain pointer-events-none">
                                        @else
                                            <div id="preview-ttd-placeholder-{{ $i }}" class="py-1.5 px-2 bg-white/85 rounded border border-gray-200 text-center pointer-events-none w-full">
                                                <span class="text-[10px] font-mono font-bold {{ $slot['textCol'] }}">[{{ $slot['label'] }}]</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            {{-- ELEMENT 7: QR VERIFIKASI RESMI (DRAGGABLE) --}}
                            <div id="drag-qr"
                                style="position: absolute; top: {{ $qrTop }}%; left: {{ $qrLeft }}%; width: {{ $qrWidth }}%; z-index: 30; cursor: move; {{ $showQr ? '' : 'display: none;' }}"
                                class="group border-2 border-dashed border-teal-600/90 hover:border-teal-700 bg-white/90 hover:bg-white rounded-lg p-1 text-center shadow-sm transition-colors">
                                <div class="pointer-events-none absolute -top-5 left-0 px-1.5 py-0.5 rounded bg-teal-700 text-white text-[9px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1 whitespace-nowrap">
                                    <i data-lucide="qr-code" class="w-2.5 h-2.5"></i> QR
                                </div>
                                <div class="bg-white border border-gray-300 rounded p-0.5 mx-auto w-[78%] aspect-square flex items-center justify-center pointer-events-none">
                                    <i data-lucide="qr-code" class="w-full h-full text-gray-900"></i>
                                </div>
                                <div class="pointer-events-none text-[6px] font-black text-emerald-950 uppercase leading-tight mt-0.5 truncate">DOKUMEN RESMI</div>
                                <div class="pointer-events-none text-[5px] font-bold text-emerald-700 uppercase leading-tight truncate">SCAN VERIFIKASI</div>
                            </div>

                        </div>
                    </div>

                    {{-- COORDINATES & CONTROLS: NOMOR, NAMA, KETERANGAN, MATA LOMBA (JUARA), QR CODE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 {{ $activeScope === 'juara' ? 'xl:grid-cols-5 lg:grid-cols-3' : 'lg:grid-cols-4' }} gap-5 pt-4 border-t border-gray-100">
                        {{-- Controls 1: Nomor Sertifikat --}}
                        <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200 space-y-3">
                            <div class="flex items-center gap-2 border-b border-gray-200/80 pb-2">
                                <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Atur Posisi Nomor</h4>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Posisi Atas (Top %):</span>
                                        <span id="label-nomor-top" class="font-mono font-bold text-blue-700">{{ $settings['cert_pos_nomor_top'] ?? 35.2 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_nomor_top" id="input-nomor-top" min="5" max="95" step="0.2"
                                        value="{{ $settings['cert_pos_nomor_top'] ?? 35.2 }}" oninput="updateNomorFromInput()"
                                        class="w-full accent-blue-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Posisi Kiri (Left %):</span>
                                        <span id="label-nomor-left" class="font-mono font-bold text-blue-700">{{ $settings['cert_pos_nomor_left'] ?? 10.0 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_nomor_left" id="input-nomor-left" min="0" max="90" step="0.2"
                                        value="{{ $settings['cert_pos_nomor_left'] ?? 10.0 }}" oninput="updateNomorFromInput()"
                                        class="w-full accent-blue-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Lebar Kotak (Width %):</span>
                                        <span id="label-nomor-width" class="font-mono font-bold text-blue-700">{{ $settings['cert_pos_nomor_width'] ?? 80.0 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_nomor_width" id="input-nomor-width" min="10" max="100" step="1"
                                        value="{{ $settings['cert_pos_nomor_width'] ?? 80.0 }}" oninput="updateNomorFromInput()"
                                        class="w-full accent-blue-600 cursor-pointer">
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <div>
                                        <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Ukuran Font</label>
                                        <input type="number" name="cert_pos_nomor_size" id="input-nomor-size" min="8" max="24"
                                            value="{{ $settings['cert_pos_nomor_size'] ?? 11 }}" onchange="updateNomorFromInput()"
                                            class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-mono font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Rata Teks</label>
                                        <select name="cert_pos_nomor_align" id="input-nomor-align" onchange="updateNomorFromInput()"
                                            class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-bold">
                                            <option value="center" {{ ($settings['cert_pos_nomor_align'] ?? 'center') == 'center' ? 'selected' : '' }}>Tengah</option>
                                            <option value="left" {{ ($settings['cert_pos_nomor_align'] ?? '') == 'left' ? 'selected' : '' }}>Kiri</option>
                                            <option value="right" {{ ($settings['cert_pos_nomor_align'] ?? '') == 'right' ? 'selected' : '' }}>Kanan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Controls 2: Nama Lengkap Peserta --}}
                        <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200 space-y-3">
                            <div class="flex items-center gap-2 border-b border-gray-200/80 pb-2">
                                <div class="w-3 h-3 rounded-full bg-emerald-700"></div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Atur Posisi Nama</h4>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Posisi Atas (Top %):</span>
                                        <span id="label-nama-top" class="font-mono font-bold text-emerald-800">{{ $settings['cert_pos_nama_top'] ?? 43.8 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_nama_top" id="input-nama-top" min="10" max="95" step="0.2"
                                        value="{{ $settings['cert_pos_nama_top'] ?? 43.8 }}" oninput="updateNamaFromInput()"
                                        class="w-full accent-emerald-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Posisi Kiri (Left %):</span>
                                        <span id="label-nama-left" class="font-mono font-bold text-emerald-800">{{ $settings['cert_pos_nama_left'] ?? 10.0 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_nama_left" id="input-nama-left" min="0" max="90" step="0.2"
                                        value="{{ $settings['cert_pos_nama_left'] ?? 10.0 }}" oninput="updateNamaFromInput()"
                                        class="w-full accent-emerald-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Lebar Kotak (Width %):</span>
                                        <span id="label-nama-width" class="font-mono font-bold text-emerald-800">{{ $settings['cert_pos_nama_width'] ?? 80.0 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_nama_width" id="input-nama-width" min="10" max="100" step="1"
                                        value="{{ $settings['cert_pos_nama_width'] ?? 80.0 }}" oninput="updateNamaFromInput()"
                                        class="w-full accent-emerald-600 cursor-pointer">
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <div>
                                        <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Ukuran Font</label>
                                        <input type="number" name="cert_pos_nama_size" id="input-nama-size" min="12" max="40"
                                            value="{{ $settings['cert_pos_nama_size'] ?? 24 }}" onchange="updateNamaFromInput()"
                                            class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-mono font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Rata Teks</label>
                                        <select name="cert_pos_nama_align" id="input-nama-align" onchange="updateNamaFromInput()"
                                            class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-bold">
                                            <option value="center" {{ ($settings['cert_pos_nama_align'] ?? 'center') == 'center' ? 'selected' : '' }}>Tengah</option>
                                            <option value="left" {{ ($settings['cert_pos_nama_align'] ?? '') == 'left' ? 'selected' : '' }}>Kiri</option>
                                            <option value="right" {{ ($settings['cert_pos_nama_align'] ?? '') == 'right' ? 'selected' : '' }}>Kanan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Controls 3: Keterangan (Jabatan Panitia / Peringkat Juara) --}}
                        <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200 space-y-3">
                            <div class="flex items-center justify-between gap-2 border-b border-gray-200/80 pb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600"></div>
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">{{ $scopeMeta['ketBadge'] }}</h4>
                                </div>
                                <label class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-900 cursor-pointer">
                                    <input type="checkbox" name="cert_show_ket" value="1" id="toggle-show-ket"
                                        {{ $showKet ? 'checked' : '' }} onchange="toggleShowKetInPreview(this.checked)"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>Tampil</span>
                                </label>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Top (%):</span>
                                        <span id="label-ket-top" class="font-mono font-bold text-indigo-800">{{ $settings['cert_pos_ket_top'] ?? 52.8 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_ket_top" id="input-ket-top" min="10" max="95" step="0.2"
                                        value="{{ $settings['cert_pos_ket_top'] ?? 52.8 }}" oninput="updateKetFromInput()"
                                        class="w-full accent-indigo-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Left (%):</span>
                                        <span id="label-ket-left" class="font-mono font-bold text-indigo-800">{{ $settings['cert_pos_ket_left'] ?? 10.0 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_ket_left" id="input-ket-left" min="0" max="90" step="0.2"
                                        value="{{ $settings['cert_pos_ket_left'] ?? 10.0 }}" oninput="updateKetFromInput()"
                                        class="w-full accent-indigo-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Lebar (%):</span>
                                        <span id="label-ket-width" class="font-mono font-bold text-indigo-800">{{ $settings['cert_pos_ket_width'] ?? 80.0 }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_ket_width" id="input-ket-width" min="10" max="100" step="1"
                                        value="{{ $settings['cert_pos_ket_width'] ?? 80.0 }}" oninput="updateKetFromInput()"
                                        class="w-full accent-indigo-600 cursor-pointer">
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-1">
                                    <div>
                                        <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Ukuran Font</label>
                                        <input type="number" name="cert_pos_ket_size" id="input-ket-size" min="8" max="32"
                                            value="{{ $settings['cert_pos_ket_size'] ?? 13 }}" onchange="updateKetFromInput()"
                                            class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-mono font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Rata Teks</label>
                                        <select name="cert_pos_ket_align" id="input-ket-align" onchange="updateKetFromInput()"
                                            class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-bold">
                                            <option value="center" {{ ($settings['cert_pos_ket_align'] ?? 'center') == 'center' ? 'selected' : '' }}>Tengah</option>
                                            <option value="left" {{ ($settings['cert_pos_ket_align'] ?? '') == 'left' ? 'selected' : '' }}>Kiri</option>
                                            <option value="right" {{ ($settings['cert_pos_ket_align'] ?? '') == 'right' ? 'selected' : '' }}>Kanan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($activeScope === 'juara')
                            {{-- Controls 3b: Nama Mata Lomba (Khusus Sertifikat Juara Lomba) --}}
                            <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-200 space-y-3">
                                <div class="flex items-center justify-between gap-2 border-b border-amber-200/80 pb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-amber-600"></div>
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Mata Lomba</h4>
                                    </div>
                                    <label class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-900 cursor-pointer">
                                        <input type="checkbox" name="cert_show_lomba" value="1" id="toggle-show-lomba"
                                            {{ $showLomba ? 'checked' : '' }} onchange="toggleShowLombaInPreview(this.checked)"
                                            class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>Tampil</span>
                                    </label>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div>
                                        <div class="flex justify-between text-gray-600 mb-1">
                                            <span>Top (%):</span>
                                            <span id="label-lomba-top" class="font-mono font-bold text-amber-800">{{ $settings['cert_pos_lomba_top'] ?? 58.2 }}%</span>
                                        </div>
                                        <input type="range" name="cert_pos_lomba_top" id="input-lomba-top" min="10" max="95" step="0.2"
                                            value="{{ $settings['cert_pos_lomba_top'] ?? 58.2 }}" oninput="updateLombaFromInput()"
                                            class="w-full accent-amber-600 cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-gray-600 mb-1">
                                            <span>Left (%):</span>
                                            <span id="label-lomba-left" class="font-mono font-bold text-amber-800">{{ $settings['cert_pos_lomba_left'] ?? 10.0 }}%</span>
                                        </div>
                                        <input type="range" name="cert_pos_lomba_left" id="input-lomba-left" min="0" max="90" step="0.2"
                                            value="{{ $settings['cert_pos_lomba_left'] ?? 10.0 }}" oninput="updateLombaFromInput()"
                                            class="w-full accent-amber-600 cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-gray-600 mb-1">
                                            <span>Lebar (%):</span>
                                            <span id="label-lomba-width" class="font-mono font-bold text-amber-800">{{ $settings['cert_pos_lomba_width'] ?? 80.0 }}%</span>
                                        </div>
                                        <input type="range" name="cert_pos_lomba_width" id="input-lomba-width" min="10" max="100" step="1"
                                            value="{{ $settings['cert_pos_lomba_width'] ?? 80.0 }}" oninput="updateLombaFromInput()"
                                            class="w-full accent-amber-600 cursor-pointer">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 pt-1">
                                        <div>
                                            <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Ukuran Font</label>
                                            <input type="number" name="cert_pos_lomba_size" id="input-lomba-size" min="8" max="32"
                                                value="{{ $settings['cert_pos_lomba_size'] ?? 12 }}" onchange="updateLombaFromInput()"
                                                class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-mono font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] text-gray-600 uppercase font-semibold mb-1">Rata Teks</label>
                                            <select name="cert_pos_lomba_align" id="input-lomba-align" onchange="updateLombaFromInput()"
                                                class="w-full px-2 py-1 rounded bg-white border border-gray-200 text-xs font-bold">
                                                <option value="center" {{ ($settings['cert_pos_lomba_align'] ?? 'center') == 'center' ? 'selected' : '' }}>Tengah</option>
                                                <option value="left" {{ ($settings['cert_pos_lomba_align'] ?? '') == 'left' ? 'selected' : '' }}>Kiri</option>
                                                <option value="right" {{ ($settings['cert_pos_lomba_align'] ?? '') == 'right' ? 'selected' : '' }}>Kanan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Controls 4: QR Code Verifikasi --}}
                        <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200 space-y-3">
                            <div class="flex items-center justify-between gap-2 border-b border-gray-200/80 pb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-teal-600"></div>
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Atur Posisi QR Code</h4>
                                </div>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-bold text-teal-900 cursor-pointer">
                                    <input type="checkbox" name="cert_show_qr" value="1" id="toggle-show-qr"
                                        {{ $showQr ? 'checked' : '' }} onchange="toggleShowQrInPreview(this.checked)"
                                        class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    <span>Tampil</span>
                                </label>
                            </div>
                            <div class="space-y-2 text-xs">
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Posisi Atas (Top %):</span>
                                        <span id="label-qr-top" class="font-mono font-bold text-teal-800">{{ $qrTop }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_qr_top" id="input-qr-top" min="0" max="92" step="0.2"
                                        value="{{ $qrTop }}" oninput="updateQrFromInput()"
                                        class="w-full accent-teal-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Posisi Kiri (Left %):</span>
                                        <span id="label-qr-left" class="font-mono font-bold text-teal-800">{{ $qrLeft }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_qr_left" id="input-qr-left" min="0" max="94" step="0.2"
                                        value="{{ $qrLeft }}" oninput="updateQrFromInput()"
                                        class="w-full accent-teal-600 cursor-pointer">
                                </div>
                                <div>
                                    <div class="flex justify-between text-gray-600 mb-1">
                                        <span>Ukuran QR (Width %):</span>
                                        <span id="label-qr-width" class="font-mono font-bold text-teal-800">{{ $qrWidth }}%</span>
                                    </div>
                                    <input type="range" name="cert_pos_qr_width" id="input-qr-width" min="4" max="25" step="0.5"
                                        value="{{ $qrWidth }}" oninput="updateQrFromInput()"
                                        class="w-full accent-teal-600 cursor-pointer">
                                </div>
                                <p class="text-[10px] text-gray-500 pt-1 leading-relaxed">
                                    Seret kotak QR langsung di atas template atau atur slider untuk memindahkan ke pojok mana pun.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- COORDINATES & CONTROLS: TTD 1..4 --}}
                    <div id="ttd-position-controls-grid" class="{{ $ttdCount === 0 ? 'hidden' : '' }} grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 border-t border-gray-100">
                        @foreach($ttdSlots as $i => $slot)
                            <div id="ttd-control-card-{{ $i }}" style="{{ $i <= $ttdCount ? '' : 'display: none;' }}"
                                class="bg-gray-50/70 p-4 rounded-xl border border-gray-200 space-y-3">
                                <div class="flex items-center gap-2 border-b border-gray-200/80 pb-2">
                                    <div class="w-3 h-3 rounded-full {{ $slot['dotBg'] }}"></div>
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider truncate" id="control-ttd-title-{{ $i }}">
                                        Posisi {{ $slot['label'] }}
                                    </h4>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div>
                                        <div class="flex justify-between text-gray-600 mb-1">
                                            <span>Top (%):</span>
                                            <span id="label-ttd-{{ $i }}-top" class="font-mono font-bold {{ $slot['textCol'] }}">{{ $slot['top'] }}%</span>
                                        </div>
                                        <input type="range" name="{{ $slot['prefix'] }}_top" id="input-ttd-{{ $i }}-top" min="10" max="95" step="0.2"
                                            value="{{ $slot['top'] }}" oninput="updateTtdFromInput({{ $i }})"
                                            class="w-full {{ $slot['accent'] }} cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-gray-600 mb-1">
                                            <span>Left (%):</span>
                                            <span id="label-ttd-{{ $i }}-left" class="font-mono font-bold {{ $slot['textCol'] }}">{{ $slot['left'] }}%</span>
                                        </div>
                                        <input type="range" name="{{ $slot['prefix'] }}_left" id="input-ttd-{{ $i }}-left" min="0" max="90" step="0.2"
                                            value="{{ $slot['left'] }}" oninput="updateTtdFromInput({{ $i }})"
                                            class="w-full {{ $slot['accent'] }} cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-gray-600 mb-1">
                                            <span>Lebar TTD (%):</span>
                                            <span id="label-ttd-{{ $i }}-width" class="font-mono font-bold {{ $slot['textCol'] }}">{{ $slot['width'] }}%</span>
                                        </div>
                                        <input type="range" name="{{ $slot['prefix'] }}_width" id="input-ttd-{{ $i }}-width" min="5" max="40" step="0.5"
                                            value="{{ $slot['width'] }}" oninput="updateTtdFromInput({{ $i }})"
                                            class="w-full {{ $slot['accent'] }} cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SECTION 3: TEMPLATE BACKGROUND SERTIFIKAT --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-6 mb-8">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-base font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="image" class="w-4 h-4 text-emerald-600"></i>
                            {{ $scopeMeta['templateTitle'] }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Template khusus untuk <strong>{{ $scopeMeta['label'] }}</strong>. Jika tidak mengunggah template kustom, sistem memakai template resmi SMPN 1 Cerbon.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div>
                            @if(!empty($settings['cert_template_peserta']))
                                <div class="aspect-[1.414/1] bg-gray-100 rounded-xl overflow-hidden mb-3 border border-gray-200 relative group shadow-sm">
                                    <img src="{{ asset('storage/' . $settings['cert_template_peserta']) }}" class="w-full h-full object-cover">
                                </div>
                                <label class="inline-flex items-center gap-2 text-xs font-bold text-rose-600 cursor-pointer">
                                    <input type="checkbox" name="delete_template_peserta" value="1" class="rounded border-gray-300 text-rose-600">
                                    <span>Hapus template kustom &amp; kembali ke template resmi</span>
                                </label>
                            @else
                                <div class="aspect-[1.414/1] bg-gray-100 rounded-xl overflow-hidden mb-3 border border-gray-200 shadow-sm">
                                    <img src="{{ asset('images/certificates/template-peserta-official.png') }}" class="w-full h-full object-cover" alt="Template Resmi">
                                </div>
                                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    Template Resmi SMPN 1 Cerbon Aktif
                                </span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Unggah File Template Baru ({{ $scopeMeta['shortLabel'] }})</label>
                                <input type="file" name="template_peserta" accept=".pdf,.png,.jpg,.jpeg"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-900 hover:file:bg-emerald-200 transition cursor-pointer">
                                <p class="text-[11px] text-gray-400 mt-2">Mendukung berkas <strong>PDF hasil export Canva</strong> atau gambar PNG/JPG A4 Landscape. Maks: 10 MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: FORMAT PENOMORAN SERTIFIKAT --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 sm:p-8 space-y-6 mb-8">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-base font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="binary" class="w-4 h-4 text-emerald-600"></i>
                            {{ $scopeMeta['numberTitle'] }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Atur pola penomoran otomatis yang dicetak pada posisi Nomor di atas.
                            Placeholder: <code>{NOMOR}</code>, <code>{BULAN}</code> (Romawi), <code>{TAHUN}</code>, <code>{REGU}</code>, <code>{JENIS}</code>.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Format Nomor Surat
                            </label>
                            <input type="text" name="cert_number_format_peserta" 
                                value="{{ $settings['cert_number_format_peserta'] ?? $scopeMeta['defaultNomorFormat'] }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-mono font-bold text-gray-900 focus:bg-white focus:border-brand-forest focus:ring-2 focus:ring-emerald-500/20 transition">
                            <p class="text-[11px] text-gray-400 mt-1.5">Contoh bawaan: <code>{{ $scopeMeta['defaultNomorFormat'] }}</code></p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Nomor Urut Awal / Counter Berikutnya
                            </label>
                            <input type="number" name="cert_counter_peserta" 
                                value="{{ $settings['cert_counter_peserta'] ?? 1 }}" min="1" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-mono font-bold text-gray-900 focus:bg-white focus:border-brand-forest focus:ring-2 focus:ring-emerald-500/20 transition">
                            <p class="text-[11px] text-gray-400 mt-1.5">{{ $scopeMeta['counterHint'] }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-sm shadow-md transition cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>{{ $scopeMeta['saveLabel'] }}</span>
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
        const tabs = ['penerbitan', 'panitia', 'juara', 'pengaturan'];
        const activeCls = 'flex-1 min-w-[150px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer font-bold bg-brand-forest text-white shadow-sm';
        const idleCls = 'flex-1 min-w-[150px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50';

        tabs.forEach(t => {
            const btn = document.getElementById(`tab-btn-${t}`);
            const panel = document.getElementById(`content-${t}`);
            if (t === tab) {
                if (btn) btn.className = activeCls;
                if (panel) panel.classList.remove('hidden');
            } else {
                if (btn) btn.className = idleCls;
                if (panel) panel.classList.add('hidden');
            }
        });

        if (tab === 'pengaturan') {
            setTimeout(initSignaturePad, 50);
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function downloadSinglePesertaCertificate(anggotaId) {
        window.open(`{{ url('admin/sertifikat/download-single') }}/${anggotaId}?type=peserta&title=PESERTA`, '_blank');
    }

    function downloadAllPesertaCertificates(reguId) {
        window.open(`{{ url('admin/sertifikat/download-regu') }}/${reguId}?type=peserta&title=PESERTA`, '_blank');
    }

    // ==========================================
    // 1. MULTI-TTD SIGNATURE PAD & SLOT MANAGER
    // ==========================================
    let sigCanvas, sigCtx, isDrawingSig = false, hasDrawnSig = false;
    let currentTtdCount = {{ $ttdCount }};
    let activePadSlot = 1;

    function getSlotLabel(slot) {
        const input = document.getElementById(`input-ttd-label-${slot}`);
        return (input && input.value.trim()) ? input.value.trim() : `TTD ${slot}`;
    }

    function updateTtdBadgeLabel(slot, val) {
        const clean = (val || '').trim() || `TTD ${slot}`;
        const badge = document.getElementById(`drag-ttd-badge-${slot}`);
        const ctrlTitle = document.getElementById(`control-ttd-title-${slot}`);
        if (badge) badge.textContent = clean;
        if (ctrlTitle) ctrlTitle.textContent = 'Posisi ' + clean;
    }

    function updateTtdCount(countVal) {
        currentTtdCount = Math.max(0, Math.min(4, parseInt(countVal) || 0));

        document.getElementById('ttd-config-empty')?.classList.toggle('hidden', currentTtdCount > 0);
        document.getElementById('ttd-config-body')?.classList.toggle('hidden', currentTtdCount === 0);
        document.getElementById('ttd-position-controls-grid')?.classList.toggle('hidden', currentTtdCount === 0);

        for (let i = 1; i <= 4; i++) {
            const active = i <= currentTtdCount;
            const padBtn = document.getElementById(`pad-slot-btn-${i}`);
            const slotCard = document.getElementById(`ttd-slot-card-${i}`);
            const dragBox = document.getElementById(`drag-ttd-${i}`);
            const ctrlCard = document.getElementById(`ttd-control-card-${i}`);

            if (padBtn) padBtn.style.display = active ? '' : 'none';
            if (slotCard) slotCard.style.display = active ? '' : 'none';
            if (dragBox) dragBox.style.display = active ? '' : 'none';
            if (ctrlCard) ctrlCard.style.display = active ? '' : 'none';
        }

        if (currentTtdCount > 0 && activePadSlot > currentTtdCount) {
            selectPadSlot(1);
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function selectPadSlot(slot) {
        activePadSlot = slot;
        for (let i = 1; i <= 4; i++) {
            const btn = document.getElementById(`pad-slot-btn-${i}`);
            if (!btn) continue;
            btn.className = (i === slot)
                ? 'px-2.5 py-1 rounded-lg text-[11px] font-black transition cursor-pointer bg-brand-forest text-white shadow-xs'
                : 'px-2.5 py-1 rounded-lg text-[11px] font-black transition cursor-pointer bg-gray-100 text-gray-700 hover:bg-gray-200';
        }

        const padLabel = document.getElementById('pad-active-slot-label');
        const btnApplyLabel = document.getElementById('btn-apply-slot-label');
        if (padLabel) padLabel.textContent = `TTD ${slot}`;
        if (btnApplyLabel) btnApplyLabel.textContent = `TTD ${slot}`;

        // Reset canvas board visual for drawing on the selected slot without clearing other slots' hidden inputs
        if (sigCanvas && sigCtx) {
            sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            hasDrawnSig = false;
            const hint = document.getElementById('pad-hint');
            if (hint) hint.style.display = 'flex';
        }
    }

    function initSignaturePad() {
        sigCanvas = document.getElementById('signature-pad');
        if (!sigCanvas) return;
        sigCtx = sigCanvas.getContext('2d');

        sigCtx.strokeStyle = '#0B2317';
        sigCtx.lineWidth = 2.8;
        sigCtx.lineCap = 'round';
        sigCtx.lineJoin = 'round';

        function getCanvasCoords(e) {
            const rect = sigCanvas.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * (sigCanvas.width / rect.width),
                y: (clientY - rect.top) * (sigCanvas.height / rect.height)
            };
        }

        function startDrawing(e) {
            isDrawingSig = true;
            hasDrawnSig = true;
            const hint = document.getElementById('pad-hint');
            if (hint) hint.style.display = 'none';

            const pos = getCanvasCoords(e);
            sigCtx.beginPath();
            sigCtx.moveTo(pos.x, pos.y);
            if (e.cancelable && e.type.startsWith('touch')) e.preventDefault();
        }

        function draw(e) {
            if (!isDrawingSig) return;
            const pos = getCanvasCoords(e);
            sigCtx.lineTo(pos.x, pos.y);
            sigCtx.stroke();
            if (e.cancelable && e.type.startsWith('touch')) e.preventDefault();
        }

        function stopDrawing() {
            if (isDrawingSig) {
                isDrawingSig = false;
                applySignatureToPreview();
            }
        }

        sigCanvas.onmousedown = startDrawing;
        sigCanvas.onmousemove = draw;
        window.addEventListener('mouseup', stopDrawing);

        sigCanvas.addEventListener('touchstart', startDrawing, { passive: false });
        sigCanvas.addEventListener('touchmove', draw, { passive: false });
        window.addEventListener('touchend', stopDrawing);
    }

    function clearSignaturePad() {
        if (!sigCanvas || !sigCtx) return;
        sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        hasDrawnSig = false;

        const hiddenInput = document.getElementById(`input-signature-data-${activePadSlot}`);
        if (hiddenInput) hiddenInput.value = '';
        if (activePadSlot === 1) {
            const legacy = document.getElementById('input-signature-data');
            if (legacy) legacy.value = '';
        }

        const hint = document.getElementById('pad-hint');
        if (hint) hint.style.display = 'flex';

        const currentSavedImg = document.getElementById(`current-saved-ttd-img-${activePadSlot}`);
        const container = document.getElementById(`preview-ttd-container-${activePadSlot}`);
        const thumbWrap = document.getElementById(`slot-thumb-wrap-${activePadSlot}`);
        const slotLabel = getSlotLabel(activePadSlot);

        if (currentSavedImg && currentSavedImg.src) {
            if (container) container.innerHTML = `<img src="${currentSavedImg.src}" id="preview-ttd-img-${activePadSlot}" class="w-full h-auto max-h-24 object-contain pointer-events-none">`;
            if (thumbWrap) thumbWrap.innerHTML = `<img src="${currentSavedImg.src}" id="current-saved-ttd-img-${activePadSlot}" class="max-w-full max-h-full object-contain">`;
        } else {
            if (container) container.innerHTML = `<div id="preview-ttd-placeholder-${activePadSlot}" class="py-1.5 px-2 bg-white/85 rounded border border-gray-200 text-center pointer-events-none w-full"><span class="text-[10px] font-mono font-bold text-gray-800">[${slotLabel}]</span></div>`;
            if (thumbWrap) thumbWrap.innerHTML = `<span class="text-[9px] text-gray-500 font-semibold text-center leading-tight">Kosong</span>`;
        }
    }

    function applySignatureToPreview() {
        if (!sigCanvas || !hasDrawnSig) return;
        const dataUrl = sigCanvas.toDataURL('image/png');
        const hiddenInput = document.getElementById(`input-signature-data-${activePadSlot}`);
        if (hiddenInput) hiddenInput.value = dataUrl;
        if (activePadSlot === 1) {
            const legacy = document.getElementById('input-signature-data');
            if (legacy) legacy.value = dataUrl;
        }

        const container = document.getElementById(`preview-ttd-container-${activePadSlot}`);
        if (container) {
            container.innerHTML = `<img src="${dataUrl}" id="preview-ttd-img-${activePadSlot}" class="w-full h-auto max-h-24 object-contain pointer-events-none animate-pulse">`;
            setTimeout(() => {
                const img = document.getElementById(`preview-ttd-img-${activePadSlot}`);
                if (img) img.classList.remove('animate-pulse');
            }, 500);
        }

        const thumbWrap = document.getElementById(`slot-thumb-wrap-${activePadSlot}`);
        if (thumbWrap) {
            thumbWrap.innerHTML = `<img src="${dataUrl}" class="max-w-full max-h-full object-contain">`;
        }
    }

    function handleSigFileUpload(event, slot) {
        const file = event.target.files[0];
        if (!file) return;

        // Clear canvas hidden input for this slot so uploaded file takes precedence
        const hiddenInput = document.getElementById(`input-signature-data-${slot}`);
        if (hiddenInput) hiddenInput.value = '';
        if (slot === 1) {
            const legacy = document.getElementById('input-signature-data');
            if (legacy) legacy.value = '';
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const dataUrl = e.target.result;
            const container = document.getElementById(`preview-ttd-container-${slot}`);
            if (container) {
                container.innerHTML = `<img src="${dataUrl}" id="preview-ttd-img-${slot}" class="w-full h-auto max-h-24 object-contain pointer-events-none">`;
            }
            const thumbWrap = document.getElementById(`slot-thumb-wrap-${slot}`);
            if (thumbWrap) {
                thumbWrap.innerHTML = `<img src="${dataUrl}" class="max-w-full max-h-full object-contain">`;
            }
        };
        reader.readAsDataURL(file);
    }

    function handleDeleteSignatureCheck(slot, checked) {
        const container = document.getElementById(`preview-ttd-container-${slot}`);
        if (!container) return;

        if (checked) {
            container.innerHTML = `<div class="py-1.5 px-2 bg-white/85 rounded border border-rose-200 text-center pointer-events-none w-full"><span class="text-[10px] font-mono font-bold text-rose-700">[TTD ${slot} Dihapus]</span></div>`;
        } else {
            const currentSavedImg = document.getElementById(`current-saved-ttd-img-${slot}`);
            if (currentSavedImg) {
                container.innerHTML = `<img src="${currentSavedImg.src}" id="preview-ttd-img-${slot}" class="w-full h-auto max-h-24 object-contain pointer-events-none">`;
            }
        }
    }

    function toggleShowQrInPreview(show) {
        const el = document.getElementById('drag-qr');
        if (el) el.style.display = show ? '' : 'none';
    }

    function toggleShowKetInPreview(show) {
        const el = document.getElementById('drag-ket');
        if (el) el.style.display = show ? '' : 'none';
    }

    function toggleShowLombaInPreview(show) {
        const el = document.getElementById('drag-lomba');
        if (el) el.style.display = show ? '' : 'none';
    }

    // ==========================================
    // 2. DRAGGABLE POSITIONING SYSTEM
    // ==========================================
    function setupDraggable(elementId, topInputId, leftInputId, topLabelId, leftLabelId) {
        const el = document.getElementById(elementId);
        const board = document.getElementById('cert-preview-board');
        if (!el || !board) return;

        let isDragging = false;
        let startX = 0, startY = 0, origLeft = 0, origTop = 0;

        function onStart(e) {
            isDragging = true;
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            startX = clientX;
            startY = clientY;

            origLeft = parseFloat(el.style.left) || 0;
            origTop = parseFloat(el.style.top) || 0;

            if (e.cancelable && e.type.startsWith('touch')) e.preventDefault();
        }

        function onMove(e) {
            if (!isDragging) return;
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;

            const boardRect = board.getBoundingClientRect();
            const deltaXPercent = ((clientX - startX) / boardRect.width) * 100;
            const deltaYPercent = ((clientY - startY) / boardRect.height) * 100;

            let newLeft = Math.max(0, Math.min(95, origLeft + deltaXPercent));
            let newTop = Math.max(0, Math.min(95, origTop + deltaYPercent));

            newLeft = Math.round(newLeft * 10) / 10;
            newTop = Math.round(newTop * 10) / 10;

            el.style.left = newLeft + '%';
            el.style.top = newTop + '%';

            const topInput = document.getElementById(topInputId);
            const leftInput = document.getElementById(leftInputId);
            const topLabel = document.getElementById(topLabelId);
            const leftLabel = document.getElementById(leftLabelId);

            if (topInput) topInput.value = newTop;
            if (leftInput) leftInput.value = newLeft;
            if (topLabel) topLabel.textContent = newTop + '%';
            if (leftLabel) leftLabel.textContent = newLeft + '%';

            if (e.cancelable && e.type.startsWith('touch')) e.preventDefault();
        }

        function onEnd() {
            isDragging = false;
        }

        el.addEventListener('mousedown', onStart);
        window.addEventListener('mousemove', onMove);
        window.addEventListener('mouseup', onEnd);

        el.addEventListener('touchstart', onStart, { passive: false });
        window.addEventListener('touchmove', onMove, { passive: false });
        window.addEventListener('touchend', onEnd);
    }

    // ==========================================
    // 3. INPUT TO PREVIEW TWO-WAY BINDINGS
    // ==========================================
    function updateNomorFromInput() {
        const top = document.getElementById('input-nomor-top').value;
        const left = document.getElementById('input-nomor-left').value;
        const width = document.getElementById('input-nomor-width').value;
        const size = document.getElementById('input-nomor-size').value;
        const align = document.getElementById('input-nomor-align').value;

        document.getElementById('label-nomor-top').textContent = top + '%';
        document.getElementById('label-nomor-left').textContent = left + '%';
        document.getElementById('label-nomor-width').textContent = width + '%';

        const el = document.getElementById('drag-nomor');
        if (el) {
            el.style.top = top + '%';
            el.style.left = left + '%';
            el.style.width = width + '%';
            el.style.textAlign = align;
        }

        const text = document.getElementById('preview-nomor-text');
        if (text) {
            text.style.fontSize = `clamp(8px, 1.4vw, ${size * 1.5}px)`;
        }
    }

    function updateNamaFromInput() {
        const top = document.getElementById('input-nama-top').value;
        const left = document.getElementById('input-nama-left').value;
        const width = document.getElementById('input-nama-width').value;
        const size = document.getElementById('input-nama-size').value;
        const align = document.getElementById('input-nama-align').value;

        document.getElementById('label-nama-top').textContent = top + '%';
        document.getElementById('label-nama-left').textContent = left + '%';
        document.getElementById('label-nama-width').textContent = width + '%';

        const el = document.getElementById('drag-nama');
        if (el) {
            el.style.top = top + '%';
            el.style.left = left + '%';
            el.style.width = width + '%';
            el.style.textAlign = align;
        }

        const text = document.getElementById('preview-nama-text');
        if (text) {
            text.style.fontSize = `clamp(12px, 2.4vw, ${size * 1.5}px)`;
        }
    }

    function updateKetFromInput() {
        const top = document.getElementById('input-ket-top').value;
        const left = document.getElementById('input-ket-left').value;
        const width = document.getElementById('input-ket-width').value;
        const size = document.getElementById('input-ket-size').value;
        const align = document.getElementById('input-ket-align').value;

        document.getElementById('label-ket-top').textContent = top + '%';
        document.getElementById('label-ket-left').textContent = left + '%';
        document.getElementById('label-ket-width').textContent = width + '%';

        const el = document.getElementById('drag-ket');
        if (el) {
            el.style.top = top + '%';
            el.style.left = left + '%';
            el.style.width = width + '%';
            el.style.textAlign = align;
        }

        const text = document.getElementById('preview-ket-text');
        if (text) {
            text.style.fontSize = `clamp(9px, 1.5vw, ${size * 1.5}px)`;
        }
    }

    function updateLombaFromInput() {
        const topEl = document.getElementById('input-lomba-top');
        if (!topEl) return;

        const top = topEl.value;
        const left = document.getElementById('input-lomba-left').value;
        const width = document.getElementById('input-lomba-width').value;
        const size = document.getElementById('input-lomba-size').value;
        const align = document.getElementById('input-lomba-align').value;

        document.getElementById('label-lomba-top').textContent = top + '%';
        document.getElementById('label-lomba-left').textContent = left + '%';
        document.getElementById('label-lomba-width').textContent = width + '%';

        const el = document.getElementById('drag-lomba');
        if (el) {
            el.style.top = top + '%';
            el.style.left = left + '%';
            el.style.width = width + '%';
            el.style.textAlign = align;
        }

        const text = document.getElementById('preview-lomba-text');
        if (text) {
            text.style.fontSize = `clamp(9px, 1.4vw, ${size * 1.5}px)`;
        }
    }

    function updateQrFromInput() {
        const top = document.getElementById('input-qr-top').value;
        const left = document.getElementById('input-qr-left').value;
        const width = document.getElementById('input-qr-width').value;

        document.getElementById('label-qr-top').textContent = top + '%';
        document.getElementById('label-qr-left').textContent = left + '%';
        document.getElementById('label-qr-width').textContent = width + '%';

        const el = document.getElementById('drag-qr');
        if (el) {
            el.style.top = top + '%';
            el.style.left = left + '%';
            el.style.width = width + '%';
        }
    }

    function updateTtdFromInput(slot) {
        const top = document.getElementById(`input-ttd-${slot}-top`).value;
        const left = document.getElementById(`input-ttd-${slot}-left`).value;
        const width = document.getElementById(`input-ttd-${slot}-width`).value;

        document.getElementById(`label-ttd-${slot}-top`).textContent = top + '%';
        document.getElementById(`label-ttd-${slot}-left`).textContent = left + '%';
        document.getElementById(`label-ttd-${slot}-width`).textContent = width + '%';

        const el = document.getElementById(`drag-ttd-${slot}`);
        if (el) {
            el.style.top = top + '%';
            el.style.left = left + '%';
            el.style.width = width + '%';
        }
    }

    function resetCertificatePositions() {
        if (!confirm('Kembalikan posisi Nomor, Nama, QR Code, dan seluruh Tanda Tangan ke pengaturan rekomendasi bawaan?')) return;

        // Default Nomor
        document.getElementById('input-nomor-top').value = 35.2;
        document.getElementById('input-nomor-left').value = 10.0;
        document.getElementById('input-nomor-width').value = 80.0;
        document.getElementById('input-nomor-size').value = 11;
        document.getElementById('input-nomor-align').value = 'center';
        updateNomorFromInput();

        // Default Nama
        document.getElementById('input-nama-top').value = 43.8;
        document.getElementById('input-nama-left').value = 10.0;
        document.getElementById('input-nama-width').value = 80.0;
        document.getElementById('input-nama-size').value = 24;
        document.getElementById('input-nama-align').value = 'center';
        updateNamaFromInput();

        // Default Keterangan
        document.getElementById('input-ket-top').value = 52.8;
        document.getElementById('input-ket-left').value = 10.0;
        document.getElementById('input-ket-width').value = 80.0;
        document.getElementById('input-ket-size').value = 13;
        document.getElementById('input-ket-align').value = 'center';
        updateKetFromInput();

        // Default Mata Lomba (khusus scope juara)
        if (document.getElementById('input-lomba-top')) {
            document.getElementById('input-lomba-top').value = 58.2;
            document.getElementById('input-lomba-left').value = 10.0;
            document.getElementById('input-lomba-width').value = 80.0;
            document.getElementById('input-lomba-size').value = 12;
            document.getElementById('input-lomba-align').value = 'center';
            updateLombaFromInput();
        }

        // Default QR
        document.getElementById('input-qr-top').value = 82.0;
        document.getElementById('input-qr-left').value = 88.0;
        document.getElementById('input-qr-width').value = 8.0;
        updateQrFromInput();

        // Default TTD 1..4
        const ttdDefaults = {
            1: { top: 72.0, left: 68.0, width: 18.0 },
            2: { top: 72.0, left: 14.0, width: 18.0 },
            3: { top: 72.0, left: 41.0, width: 18.0 },
            4: { top: 56.0, left: 41.0, width: 18.0 },
        };
        for (let i = 1; i <= 4; i++) {
            const topEl = document.getElementById(`input-ttd-${i}-top`);
            const leftEl = document.getElementById(`input-ttd-${i}-left`);
            const widthEl = document.getElementById(`input-ttd-${i}-width`);
            if (topEl && leftEl && widthEl) {
                topEl.value = ttdDefaults[i].top;
                leftEl.value = ttdDefaults[i].left;
                widthEl.value = ttdDefaults[i].width;
                updateTtdFromInput(i);
            }
        }
    }

    // Init signature pad & draggables
    document.addEventListener('DOMContentLoaded', function() {
        initSignaturePad();

        setupDraggable('drag-nomor', 'input-nomor-top', 'input-nomor-left', 'label-nomor-top', 'label-nomor-left');
        setupDraggable('drag-nama', 'input-nama-top', 'input-nama-left', 'label-nama-top', 'label-nama-left');
        setupDraggable('drag-ket', 'input-ket-top', 'input-ket-left', 'label-ket-top', 'label-ket-left');
        setupDraggable('drag-lomba', 'input-lomba-top', 'input-lomba-left', 'label-lomba-top', 'label-lomba-left');
        setupDraggable('drag-qr', 'input-qr-top', 'input-qr-left', 'label-qr-top', 'label-qr-left');
        for (let i = 1; i <= 4; i++) {
            setupDraggable(`drag-ttd-${i}`, `input-ttd-${i}-top`, `input-ttd-${i}-left`, `label-ttd-${i}-top`, `label-ttd-${i}-left`);
        }

        const form = document.getElementById('form-cert-settings');
        if (form) {
            form.addEventListener('submit', function() {
                if (hasDrawnSig && sigCanvas) {
                    const data = sigCanvas.toDataURL('image/png');
                    const activeHidden = document.getElementById(`input-signature-data-${activePadSlot}`);
                    if (activeHidden) activeHidden.value = data;
                    if (activePadSlot === 1) {
                        const legacy = document.getElementById('input-signature-data');
                        if (legacy) legacy.value = data;
                    }
                }
            });
        }
    });
</script>
@endpush
