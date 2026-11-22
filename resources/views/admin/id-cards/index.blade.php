@extends('layouts.main')

@section('title', 'Cetak ID Card Peserta - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        @php $activeTab = request('tab') === 'panitia' ? 'panitia' : 'peserta'; @endphp
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 mb-2">
                    <i data-lucide="id-card" class="w-3.5 h-3.5 text-amber-700"></i>
                    <span>Pencetakan Kartu Tanda Peserta &amp; Panitia</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Cetak ID Card Resmi</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola dan cetak ID Card peserta maupun panitia dari formulir resmi dengan QR Code verifikasi.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.verifikasi-qr.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-emerald-900 border border-emerald-200 rounded-xl font-bold text-xs shadow-xs transition">
                    <i data-lucide="qr-code" class="w-4 h-4 text-emerald-700"></i>
                    <span>Scanner / Verifikasi QR</span>
                </a>
                <a href="{{ route('admin.id-cards.print-all', request()->query()) }}" target="_blank" id="btn-print-all-peserta"
                    class="{{ $activeTab === 'panitia' ? 'hidden' : 'inline-flex' }} items-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-xs shadow-sm transition">
                    <i data-lucide="printer" class="w-4 h-4 text-amber-400"></i>
                    <span>Cetak Semua ID Card Peserta (A4)</span>
                </a>
                <a href="{{ route('admin.id-cards.panitia.print-all') }}" target="_blank" id="btn-print-all-panitia"
                    class="{{ $activeTab === 'panitia' ? 'inline-flex' : 'hidden' }} items-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl font-bold text-xs shadow-sm transition">
                    <i data-lucide="printer" class="w-4 h-4 text-amber-400"></i>
                    <span>Cetak Semua ID Card Panitia (A4)</span>
                </a>
            </div>
        </div>

        {{-- Tabs Peserta vs Panitia --}}
        <div class="bg-white p-1.5 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col sm:flex-row gap-1.5 max-w-md mb-8">
            <button type="button" onclick="switchIdCardTab('peserta')" id="idcard-tab-btn-peserta"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm transition cursor-pointer {{ $activeTab === 'peserta' ? 'font-bold bg-brand-forest text-white shadow-sm' : 'font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>ID Card Peserta</span>
            </button>
            <button type="button" onclick="switchIdCardTab('panitia')" id="idcard-tab-btn-panitia"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm transition cursor-pointer {{ $activeTab === 'panitia' ? 'font-bold bg-brand-forest text-white shadow-sm' : 'font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50' }}">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>ID Card Panitia ({{ $panitiaStats['total_panitia'] ?? 0 }})</span>
            </button>
        </div>

        {{-- TAB 1: PESERTA --}}
        <div id="idcard-tab-peserta" class="{{ $activeTab === 'panitia' ? 'hidden' : '' }}">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-800 shrink-0">
                    <i data-lucide="shield" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Regu Terdaftar</span>
                    <span class="text-2xl font-black text-brand-forest">{{ $stats['total_regu'] }} Regu</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-800 shrink-0">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Anggota Terdaftar</span>
                    <span class="text-2xl font-black text-amber-950">{{ $stats['total_anggota'] }} Anggota</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800 shrink-0">
                    <i data-lucide="camera" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Kelengkapan Foto</span>
                    <span class="text-2xl font-black text-blue-950">{{ $stats['total_foto'] }} / {{ $stats['total_anggota'] }}</span>
                </div>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-xs mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.id-cards.index') }}"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ !request('jenis') ? 'bg-brand-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.id-cards.index', ['jenis' => 'putra']) }}"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request('jenis') === 'putra' ? 'bg-amber-400 text-amber-950' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Regu Putra
                </a>
                <a href="{{ route('admin.id-cards.index', ['jenis' => 'putri']) }}"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request('jenis') === 'putri' ? 'bg-rose-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Regu Putri
                </a>
            </div>
            <form method="GET" action="{{ route('admin.id-cards.index') }}" class="w-full sm:w-80">
                @if(request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama regu atau pangkalan..."
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-medium transition">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
                </div>
            </form>
        </div>

        {{-- Regu Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($regus as $regu)
                @php
                    $fotoCount = $regu->anggotaRegu->whereNotNull('foto_path')->count();
                    $totalCount = $regu->anggotaRegu->count();
                    $isComplete = $totalCount > 0 && $fotoCount === $totalCount;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $regu->isPutra() ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-rose-100 text-rose-900 border border-rose-200' }}">
                                Regu {{ ucfirst($regu->jenis) }}
                            </span>
                            <span class="text-xs font-mono font-bold text-gray-400">
                                #{{ $regu->nomor_regu }}
                            </span>
                        </div>

                        <h3 class="text-lg font-black text-gray-900 leading-tight mb-1 uppercase">{{ $regu->nama_regu }}</h3>
                        <p class="text-xs text-gray-500 mb-4">{{ $regu->sekolah ?? 'SMPN 1 Cerbon' }}</p>

                        {{-- Kelengkapan Anggota & Foto --}}
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 mb-4 space-y-2">
                            <div class="flex items-center justify-between text-xs font-semibold">
                                <span class="text-gray-500">Anggota Terdaftar:</span>
                                <span class="text-gray-800">{{ $totalCount }} / 8 Orang</span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-semibold">
                                <span class="text-gray-500">Kelengkapan Foto:</span>
                                <span class="{{ $isComplete ? 'text-emerald-700 font-bold' : 'text-amber-700' }}">
                                    {{ $fotoCount }} / {{ $totalCount }} Foto
                                </span>
                            </div>
                        </div>

                        {{-- Anggota Preview --}}
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($regu->anggotaRegu as $m)
                                <span class="px-2 py-0.5 text-[10px] rounded-md font-medium bg-gray-100 text-gray-700 truncate max-w-[120px]" title="{{ $m->nama }}">
                                    {{ $m->nama }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400 italic">Belum ada anggota</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50/70 border-t border-gray-100 flex items-center justify-between gap-3">
                        <span class="text-[11px] text-gray-500 font-medium">
                            <i data-lucide="tent" class="w-3.5 h-3.5 inline text-gray-400 mr-1"></i>{{ $regu->nomor_kavling ?? 'Kavling Tenda' }}
                        </span>
                        <a href="{{ route('admin.id-cards.print', $regu) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold transition shadow-xs">
                            <i data-lucide="printer" class="w-3.5 h-3.5 text-amber-400"></i>
                            <span>Cetak ID Card</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-gray-200">
                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="inbox" class="w-6 h-6"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-500">Tidak ada regu yang ditemukan.</p>
                </div>
            @endforelse
        </div>
        </div>{{-- End TAB 1 PESERTA --}}

        {{-- TAB 2: PANITIA --}}
        <div id="idcard-tab-panitia" class="{{ $activeTab === 'panitia' ? '' : 'hidden' }} space-y-6">
            {{-- Share Link Formulir Panitia --}}
            <div class="bg-gradient-to-r from-brand-forest to-emerald-900 rounded-2xl p-6 text-white shadow-md flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-emerald-700/40">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-400 text-emerald-950">
                        <i data-lucide="link" class="w-3 h-3"></i> Link Formulir Pendaftaran ID Card Panitia
                    </span>
                    <h3 class="text-base font-black text-white">Bagikan Formulir ke Seluruh Panitia &amp; Pembina</h3>
                    <p class="text-xs text-emerald-100">Data dari formulir ini langsung muncul di sini untuk cetak ID Card serta siap diterbitkan menjadi Sertifikat Panitia setelah kegiatan selesai.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <input type="text" id="panitia-form-link-input" readonly value="{{ route('panitia.form') }}"
                        class="px-3 py-2 rounded-xl bg-black/30 border border-white/20 text-xs font-mono text-amber-200 w-64 sm:w-72 outline-none">
                    <button type="button" onclick="copyPanitiaFormLink()" id="btn-copy-panitia-link"
                        class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-emerald-950 text-xs font-black uppercase cursor-pointer transition">
                        Salin Link
                    </button>
                    <a href="{{ route('panitia.form') }}" target="_blank"
                        class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition">
                        Buka Form
                    </a>
                </div>
            </div>

            {{-- Panitia Stats + Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-800 shrink-0">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Total Data Panitia</span>
                        <span class="text-2xl font-black text-brand-forest">{{ $panitiaStats['total_panitia'] ?? 0 }} Orang</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-800 shrink-0">
                        <i data-lucide="camera" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Kelengkapan Pasfoto</span>
                        <span class="text-2xl font-black text-amber-950">{{ $panitiaStats['total_foto'] ?? 0 }} / {{ $panitiaStats['total_panitia'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-xs flex items-center justify-between gap-3">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Sertifikat Panitia</span>
                        <span class="text-lg font-black text-blue-950">{{ $panitiaStats['total_sertifikat'] ?? 0 }} Terbit</span>
                    </div>
                    <a href="{{ route('admin.sertifikat.peserta', ['tab' => 'panitia']) }}"
                        class="px-3 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 text-xs font-bold transition">
                        Terbitkan Sertifikat &rarr;
                    </a>
                </div>
            </div>

            {{-- Table Panitia --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-black text-brand-forest uppercase">Daftar Panitia Terdaftar</h3>
                        <p class="text-xs text-gray-500">Data otomatis masuk dari formulir panitia atau ditambahkan manual oleh admin.</p>
                    </div>
                    <button type="button" onclick="openAddPanitiaModal()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold cursor-pointer transition shadow-xs">
                        <i data-lucide="user-plus" class="w-4 h-4 text-amber-400"></i>
                        <span>Tambah Panitia Manual</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-xs">
                        <thead class="bg-gray-50 text-gray-600 uppercase font-bold">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">No</th>
                                <th class="px-4 py-3 text-left">Foto</th>
                                <th class="px-4 py-3 text-left">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left">Jabatan &amp; Bidang</th>
                                <th class="px-4 py-3 text-left">Instansi / No. HP</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($panitiaList as $idx => $p)
                                <tr class="hover:bg-gray-50/70">
                                    <td class="px-4 py-3 font-bold text-gray-500">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="w-10 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                            @if($p->foto_path)
                                                <img src="{{ asset('storage/' . $p->foto_path) }}" alt="{{ $p->nama }}" class="w-full h-full object-cover">
                                            @else
                                                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-900 text-sm">{{ $p->nama }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-950 font-black uppercase text-[10px] border border-amber-200">{{ $p->jabatan }}</span>
                                        @if($p->divisi)
                                            <div class="text-[11px] text-gray-600 mt-0.5">{{ $p->divisi }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        <div class="font-semibold">{{ $p->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}</div>
                                        @if($p->no_hp)
                                            <div class="text-[11px] font-mono text-gray-500">{{ $p->no_hp }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap space-x-1.5">
                                        <a href="{{ route('admin.id-cards.panitia.print', $p->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-brand-forest hover:bg-emerald-900 text-white font-bold transition">
                                            <i data-lucide="printer" class="w-3.5 h-3.5 text-amber-400"></i> Cetak ID
                                        </a>
                                        <button type="button" onclick='openEditPanitiaModal(@json($p))'
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-bold cursor-pointer transition">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.id-cards.panitia.destroy', $p->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus data panitia {{ addslashes($p->nama) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-bold cursor-pointer transition">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada data panitia. Bagikan link formulir di atas atau klik <strong>Tambah Panitia Manual</strong>.
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

{{-- MODAL TAMBAH PANITIA --}}
<div id="addPanitiaModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h3 class="text-base font-black text-brand-forest uppercase">Tambah Data Panitia</h3>
            <button type="button" onclick="closeAddPanitiaModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.id-cards.panitia.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap &amp; Gelar *</label>
                <input type="text" name="nama" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jabatan *</label>
                    <input type="text" name="jabatan" required placeholder="Ketua Panitia, Sie Acara..." class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Bidang / Divisi</label>
                    <input type="text" name="divisi" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Instansi / Pangkalan</label>
                    <input type="text" name="asal_instansi" value="SMP Negeri 1 Cerbon" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">No. HP / WA</label>
                    <input type="text" name="no_hp" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-mono">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Pasfoto (Opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-100 file:text-emerald-900 file:font-bold">
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeAddPanitiaModal()" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-forest text-white text-xs font-bold cursor-pointer">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PANITIA --}}
<div id="editPanitiaModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h3 class="text-base font-black text-brand-forest uppercase">Edit Data Panitia</h3>
            <button type="button" onclick="closeEditPanitiaModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form id="editPanitiaForm" method="POST" action="" enctype="multipart/form-data" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap &amp; Gelar *</label>
                <input type="text" name="nama" id="edit_panitia_nama" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jabatan *</label>
                    <input type="text" name="jabatan" id="edit_panitia_jabatan" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Bidang / Divisi</label>
                    <input type="text" name="divisi" id="edit_panitia_divisi" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Instansi / Pangkalan</label>
                    <input type="text" name="asal_instansi" id="edit_panitia_instansi" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">No. HP / WA</label>
                    <input type="text" name="no_hp" id="edit_panitia_hp" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-mono">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Ganti Pasfoto (Opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-100 file:text-emerald-900 file:font-bold">
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditPanitiaModal()" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-forest text-white text-xs font-bold cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchIdCardTab(tab) {
        const pesertaPanel = document.getElementById('idcard-tab-peserta');
        const panitiaPanel = document.getElementById('idcard-tab-panitia');
        const btnPeserta = document.getElementById('idcard-tab-btn-peserta');
        const btnPanitia = document.getElementById('idcard-tab-btn-panitia');
        const printPeserta = document.getElementById('btn-print-all-peserta');
        const printPanitia = document.getElementById('btn-print-all-panitia');

        if (tab === 'panitia') {
            pesertaPanel?.classList.add('hidden');
            panitiaPanel?.classList.remove('hidden');
            if (btnPanitia) btnPanitia.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm transition cursor-pointer font-bold bg-brand-forest text-white shadow-sm';
            if (btnPeserta) btnPeserta.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm transition cursor-pointer font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50';
            printPeserta?.classList.add('hidden');
            printPeserta?.classList.remove('inline-flex');
            printPanitia?.classList.remove('hidden');
            printPanitia?.classList.add('inline-flex');
        } else {
            panitiaPanel?.classList.add('hidden');
            pesertaPanel?.classList.remove('hidden');
            if (btnPeserta) btnPeserta.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm transition cursor-pointer font-bold bg-brand-forest text-white shadow-sm';
            if (btnPanitia) btnPanitia.className = 'flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm transition cursor-pointer font-semibold text-gray-600 hover:text-brand-forest hover:bg-gray-50';
            printPanitia?.classList.add('hidden');
            printPanitia?.classList.remove('inline-flex');
            printPeserta?.classList.remove('hidden');
            printPeserta?.classList.add('inline-flex');
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function copyPanitiaFormLink() {
        const input = document.getElementById('panitia-form-link-input');
        const btn = document.getElementById('btn-copy-panitia-link');
        if (!input) return;
        navigator.clipboard.writeText(input.value).then(() => {
            if (btn) {
                btn.textContent = 'Tersalin!';
                setTimeout(() => { btn.textContent = 'Salin Link'; }, 2000);
            }
        });
    }

    function openAddPanitiaModal() { document.getElementById('addPanitiaModal')?.classList.remove('hidden'); }
    function closeAddPanitiaModal() { document.getElementById('addPanitiaModal')?.classList.add('hidden'); }

    function openEditPanitiaModal(p) {
        document.getElementById('editPanitiaForm').action = `/admin/id-cards/panitia/${p.id}`;
        document.getElementById('edit_panitia_nama').value = p.nama || '';
        document.getElementById('edit_panitia_jabatan').value = p.jabatan || '';
        document.getElementById('edit_panitia_divisi').value = p.divisi || '';
        document.getElementById('edit_panitia_instansi').value = p.asal_instansi || '';
        document.getElementById('edit_panitia_hp').value = p.no_hp || '';
        document.getElementById('editPanitiaModal')?.classList.remove('hidden');
    }
    function closeEditPanitiaModal() { document.getElementById('editPanitiaModal')?.classList.add('hidden'); }
</script>
@endpush
