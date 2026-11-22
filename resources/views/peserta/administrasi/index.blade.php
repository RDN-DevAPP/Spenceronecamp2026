@extends('layouts.peserta')

@section('title', 'Sistem Administrasi Regu - ' . $regu->nama_regu)

@section('content')
<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER BANNER -->
        <div class="relative bg-gradient-to-r from-brand-forest via-emerald-950 to-brand-forest rounded-3xl p-6 sm:p-8 text-white shadow-xl border-2 border-amber-400/40 overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <i data-lucide="book-open-check" class="w-72 h-72 text-amber-300"></i>
            </div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs font-black uppercase tracking-wider mb-2">
                        <i data-lucide="folder-kanban" class="w-3.5 h-3.5"></i>
                        Administrasi Regu Penggalang
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Sistem 6 Buku Pokok Administrasi Regu
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-50 mt-1 max-w-2xl leading-relaxed">
                        Pengelolaan tertib administrasi kepramukaan resmi untuk <strong>Regu {{ $regu->nama_regu }} ({{ ucfirst($regu->jenis) }})</strong> pangkalan SMP Negeri 1 Cerbon. Lengkapi dan cetak seluruh buku secara digital.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- FLASH NOTIFICATION -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold flex items-center gap-2 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- QUICK STATS -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl p-4 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-gray-600">
                    <span class="text-xs font-bold">Anggota</span>
                    <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-black text-brand-forest">{{ $totalAnggota }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Total Anggota</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-gray-600">
                    <span class="text-xs font-bold">Saldo Kas</span>
                    <i data-lucide="wallet" class="w-4 h-4 text-amber-600"></i>
                </div>
                <div class="mt-2">
                    <div class="text-lg font-black text-amber-700 truncate">Rp {{ number_format($saldoKas, 0, ',', '.') }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Kas Regu</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-gray-600">
                    <span class="text-xs font-bold">Jurnal</span>
                    <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-black text-blue-900">{{ $totalLogbook }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Catatan Kegiatan</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-gray-600">
                    <span class="text-xs font-bold">Notulen</span>
                    <i data-lucide="file-text" class="w-4 h-4 text-purple-600"></i>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-black text-purple-900">{{ $totalNotulen }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Rapat Regu</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-gray-600">
                    <span class="text-xs font-bold">Presensi</span>
                    <i data-lucide="calendar-check" class="w-4 h-4 text-teal-600"></i>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-black text-teal-900">{{ $totalPresensi }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Pertemuan / Latihan</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-900/10 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-gray-600">
                    <span class="text-xs font-bold">Inventaris</span>
                    <i data-lucide="boxes" class="w-4 h-4 text-orange-600"></i>
                </div>
                <div class="mt-2">
                    <div class="text-2xl font-black text-orange-900">{{ $totalInventaris }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">Barang & Alat</div>
                </div>
            </div>
        </div>

        <!-- 6 BUKU POKOK CARDS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- 1. BUKU ANGGOTA -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="id-card" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">Buku I</span>
                    <h3 class="text-lg font-black text-gray-900 mt-2">Buku Anggota Regu</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Data induk seluruh anggota regu lengkap dengan NTA (Nomor Tanda Anggota), jabatan (Pinru/Wapinru/Anggota), tingkatan TKU (Ramu, Rakit, Terap), TKK, dan biodata.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500">{{ $totalAnggota }} Anggota</span>
                    <a href="{{ route('peserta.administrasi.buku-anggota') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white text-xs font-bold transition">
                        Buka Buku <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- 2. BUKU KEUANGAN -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="coins" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full">Buku II</span>
                    <h3 class="text-lg font-black text-gray-900 mt-2">Buku Keuangan Kas Regu</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Pencatatan kas masuk dan kas keluar regu (iuran anggota, pembelian logistik, perlengkapan, konsumsi) beserta nota bukti pembayaran secara akuntabel.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700">Saldo: Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                    <a href="{{ route('peserta.administrasi.buku-keuangan') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition">
                        Buka Buku <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- 3. BUKU CATATAN KEGIATAN (LOGBOOK) -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="notebook-pen" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full">Buku III</span>
                    <h3 class="text-lg font-black text-gray-900 mt-2">Buku Catatan Kegiatan (Logbook)</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Jurnal harian perkemahan dan riwayat kegiatan regu, mencakup waktu, tempat, materi kepramukaan, dokumentasi foto, dan catatan evaluasi.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500">{{ $totalLogbook }} Catatan</span>
                    <a href="{{ route('peserta.administrasi.buku-logbook') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold transition">
                        Buka Buku <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- 4. BUKU NOTULEN RAPAT -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="messages-square" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full">Buku IV</span>
                    <h3 class="text-lg font-black text-gray-900 mt-2">Buku Notulen Rapat Regu</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Dokumentasi hasil musyawarah / dewan regu, agenda rapat, pemimpin rapat, notulis, daftar hadir, serta keputusan yang disepakati bersama.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500">{{ $totalNotulen }} Notulensi</span>
                    <a href="{{ route('peserta.administrasi.buku-notulen') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition">
                        Buka Buku <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- 5. BUKU PRESENSI -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="user-check" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full">Buku V</span>
                    <h3 class="text-lg font-black text-gray-900 mt-2">Buku Presensi Regu</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Daftar hadir keikutsertaan anggota regu pada setiap latihan, apel, kegiatan perkemahan, maupun perlombaan (Hadir, Izin, Sakit, Alpa).
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500">{{ $totalPresensi }} Pertemuan</span>
                    <a href="{{ route('peserta.administrasi.buku-presensi') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold transition">
                        Buka Buku <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- 6. BUKU INVENTARIS -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200/90 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-700 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="package-search" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-orange-700 bg-orange-50 px-2.5 py-0.5 rounded-full">Buku VI</span>
                    <h3 class="text-lg font-black text-gray-900 mt-2">Buku Inventaris Perlengkapan</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Daftar aset barang, tenda, tongkat, tali, kompas, alat masak, dan perlengkapan regu lengkap dengan jumlah, kondisi (Baik/Rusak), dan lokasi simpan.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500">{{ $totalInventaris }} Total Unit</span>
                    <a href="{{ route('peserta.administrasi.buku-inventaris') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-orange-700 hover:bg-orange-800 text-white text-xs font-bold transition">
                        Buka Buku <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- PETUNJUK TERTIB ADMINISTRASI PRAMUKA -->
        <div class="bg-amber-50 border border-amber-200/80 rounded-3xl p-6 text-amber-900">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-amber-500/20 text-amber-800 flex items-center justify-center shrink-0">
                    <i data-lucide="info" class="w-5 h-5"></i>
                </div>
                <div class="space-y-1 text-xs sm:text-sm">
                    <h4 class="font-black text-amber-950 uppercase tracking-wide">Panduan Tertib Administrasi Regu Penggalang:</h4>
                    <p class="leading-relaxed text-amber-900/90">
                        Sesuai petunjuk penyelenggaraan Gerakan Pramuka, administrasi regu dikelola mandiri oleh Pemimpin Regu (Pinru), Wakil Pemimpin Regu (Wapinru), serta petugas administrasi yang ditunjuk. Setiap buku dapat dicetak untuk ditunjukkan saat penilaian tapak kemah, penilaian administrasi regu, maupun untuk arsip regu.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
