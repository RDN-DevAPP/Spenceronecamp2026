@extends('layouts.main')

@section('title', 'Admin Dashboard - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60 text-xs font-semibold uppercase tracking-wider mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Panel Kontrol Utama
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight flex items-center gap-3">
                        <span>Dashboard Admin</span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola publikasi klasemen, pantau rekapitulasi poin juara umum, dan lihat progres penilaian.</p>
                </div>
            </div>

            <!-- Global Settings & Control Cards -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl mx-auto">
                <!-- Toggle 1: Sensor Juara Umum -->
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200/60">
                                <i data-lucide="trophy" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Buka Sensor Juara Umum</h3>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Publikasikan regu 3 besar klasemen juara umum kepada publik di halaman utama.</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.toggle.reveal') }}" method="POST" class="shrink-0 pt-1">
                            @csrf
                            <label for="reveal-toggle" class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="reveal" value="0">
                                <input type="checkbox" id="reveal-toggle" name="reveal" value="1" class="sr-only peer" onchange="this.form.submit()" {{ $revealLeaderboard == '1' ? 'checked' : '' }}>
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none ring-2 ring-transparent peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 shadow-inner"></div>
                            </label>
                        </form>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-gray-400">Status Saat Ini:</span>
                        @if($revealLeaderboard == '1')
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Terbuka untuk Publik
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                                <i data-lucide="lock" class="w-3.5 h-3.5"></i> Masih Disensor
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Toggle 2: Tombol Laporan Keuangan -->
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center shrink-0 border border-emerald-200/60">
                                <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Tombol Laporan Keuangan</h3>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Tampilkan tombol akses transparan Laporan Keuangan menggantikan info lomba di Beranda.</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.toggle.financial-report') }}" method="POST" class="shrink-0 pt-1">
                            @csrf
                            <label for="show-report-toggle" class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="show" value="0">
                                <input type="checkbox" id="show-report-toggle" name="show" value="1" class="sr-only peer" onchange="this.form.submit()" {{ $showFinancialReport == '1' ? 'checked' : '' }}>
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none ring-2 ring-transparent peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 shadow-inner"></div>
                            </label>
                        </form>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-gray-400">Status Saat Ini:</span>
                        @if($showFinancialReport == '1')
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Ditampilkan di Beranda
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                                <i data-lucide="eye-off" class="w-3.5 h-3.5"></i> Tersembunyi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Toggle 3: Gabung / Pisah Juara Putra & Putri -->
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-emerald-500/40 transition-colors md:col-span-2">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 border border-indigo-200/60">
                                <i data-lucide="git-merge" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">Format Klasemen Juara (Putra & Putri)</h3>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pilih apakah penentuan dan tampilan Juara Umum digabung dalam 1 klasemen campuran atau dipisah menjadi Juara Putra & Juara Putri.</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.toggle.combine-gender') }}" method="POST" class="shrink-0 pt-1">
                            @csrf
                            <label for="combine-gender-toggle" class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="combine" value="0">
                                <input type="checkbox" id="combine-gender-toggle" name="combine" value="1" class="sr-only peer" onchange="this.form.submit()" {{ ($combineGender ?? '0') == '1' ? 'checked' : '' }}>
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none ring-2 ring-transparent peer-focus:ring-indigo-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                            </label>
                        </form>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="text-gray-400">Mode Saat Ini:</span>
                        @if(($combineGender ?? '0') == '1')
                            <span class="inline-flex items-center gap-1.5 font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-200/60">
                                <i data-lucide="layers" class="w-3.5 h-3.5"></i> <strong>DIGABUNG</strong> (Satu Klasemen Campuran Putra & Putri)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200/60">
                                <i data-lucide="split" class="w-3.5 h-3.5"></i> <strong>DIPISAH</strong> (Juara Putra Gudep 03.059 & Juara Putri Gudep 03.060)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Access Bar for Voting -->
            <div class="mb-8 max-w-4xl mx-auto">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 hover:border-pink-400/50 transition">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-700 flex items-center justify-center shrink-0 border border-pink-200/60">
                            <i data-lucide="heart" class="w-5 h-5 text-pink-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base">Data Voting Regu Terfavorit</h3>
                            <p class="text-xs text-gray-500">Lihat rekapitulasi suara publik, daftar voter, dan kelola atau hapus data vote.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <a href="{{ route('admin.voting.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-pink-600 text-white font-bold text-xs hover:bg-pink-700 transition shadow-sm">
                            <i data-lucide="list" class="w-3.5 h-3.5"></i>
                            Kelola Data Vote
                        </a>
                    </div>
                </div>
            </div>

            <!-- Juara Umum Leaderboard -->
            @if(count($juaraUmum) > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 mb-8 overflow-hidden">
                    <div class="px-6 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gradient-to-r from-brand-forest to-emerald-800 text-white gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-400/20 border border-amber-300/30 text-amber-300 flex items-center justify-center shrink-0">
                                <i data-lucide="crown" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-black tracking-wide uppercase">Klasemen Juara Umum</h3>
                                    @if(($combineGender ?? '0') == '1')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-indigo-500/30 text-indigo-200 border border-indigo-300/30 uppercase">Digabung</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-500/30 text-emerald-200 border border-emerald-300/30 uppercase">Dipisah Putra / Putri</span>
                                    @endif
                                </div>
                                <p class="text-xs text-emerald-200/90 font-medium">Akumulasi perolehan medali juara 1 (3 poin), juara 2 (2 poin), dan juara 3 (1 poin)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(($combineGender ?? '0') != '1')
                                <div class="inline-flex p-1 bg-black/20 rounded-xl border border-white/10 text-xs font-bold" id="admin-juara-tabs">
                                    <button type="button" onclick="showAdminJuaraTab('putra')" id="btn-juara-putra" class="px-3 py-1.5 rounded-lg transition bg-blue-600 text-white shadow-sm flex items-center gap-1.5">
                                        <i data-lucide="shield" class="w-3.5 h-3.5"></i> Putra ({{ count($juaraUmumPutra) }})
                                    </button>
                                    <button type="button" onclick="showAdminJuaraTab('putri')" id="btn-juara-putri" class="px-3 py-1.5 rounded-lg transition text-white/80 hover:text-white flex items-center gap-1.5">
                                        <i data-lucide="heart" class="w-3.5 h-3.5"></i> Putri ({{ count($juaraUmumPutri) }})
                                    </button>
                                </div>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-emerald-950 shadow-sm">
                                    {{ count($juaraUmum) }} Regu Terdata
                                </span>
                            @endif
                        </div>
                    </div>

                    @php
                        $isCombined = ($combineGender ?? '0') == '1';
                    @endphp

                    <!-- Desktop View -->
                    <div class="hidden md:block overflow-x-auto">
                        @if($isCombined)
                            {{-- Tabel Gabungan (Satu Klasemen Campuran) --}}
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/80 text-gray-600">
                                    <tr>
                                        <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider w-20">Rank</th>
                                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu</th>
                                        @foreach($allMataLombaFiltered as $ml)
                                            <th scope="col" class="px-3 py-3.5 text-center text-xs font-bold uppercase tracking-wider">{{ $ml->nama }}</th>
                                        @endforeach
                                        <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-amber-700 uppercase tracking-wider bg-amber-50/60">Total Poin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($juaraUmum as $ju)
                                        <tr class="hover:bg-emerald-50/30 transition-colors {{ $loop->iteration <= 3 ? 'bg-amber-50/20' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($ju['peringkat'] == 1)
                                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500 text-white font-black shadow-sm ring-2 ring-amber-200">1</span>
                                                @elseif($ju['peringkat'] == 2)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-400 text-white font-black shadow-sm ring-2 ring-slate-200">2</span>
                                                @elseif($ju['peringkat'] == 3)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-700 text-white font-black shadow-sm ring-2 ring-amber-300">3</span>
                                                @else
                                                    <span class="font-bold text-gray-500 text-sm">{{ $ju['peringkat'] }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full {{ strtolower($ju['reguProfile']->jenis) == 'putra' ? 'bg-blue-500' : 'bg-pink-500' }}"></span>
                                                    <span>{{ ucfirst($ju['reguProfile']->jenis) }}</span>
                                                    <span>&bull;</span>
                                                    <span>Regu {{ $ju['reguProfile']->nomor_regu }}</span>
                                                </div>
                                            </td>
                                            @foreach($allMataLombaFiltered as $ml)
                                                <td class="px-3 py-4 whitespace-nowrap text-center">
                                                    @if(isset($ju['lomba_poin'][$ml->id]))
                                                        @if($ju['lomba_poin'][$ml->id] == 3)
                                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-amber-100 text-amber-800 font-black text-xs ring-1 ring-amber-300" title="Juara 1 (3 Poin)">3</span>
                                                        @elseif($ju['lomba_poin'][$ml->id] == 2)
                                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 text-slate-800 font-black text-xs ring-1 ring-slate-300" title="Juara 2 (2 Poin)">2</span>
                                                        @elseif($ju['lomba_poin'][$ml->id] == 1)
                                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-amber-50 text-amber-900 font-black text-xs ring-1 ring-amber-200" title="Juara 3 (1 Poin)">1</span>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-300 font-light">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="px-6 py-4 whitespace-nowrap text-center bg-amber-50/30">
                                                <div class="text-xl font-black text-amber-700">{{ $ju['poin'] }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            {{-- Tabel Terpisah: Panel Putra --}}
                            <div id="panel-admin-juara-putra">
                                <div class="px-6 py-2 bg-blue-50 border-b border-blue-100 flex items-center justify-between">
                                    <span class="text-xs font-black uppercase tracking-wider text-blue-800 flex items-center gap-1.5">
                                        <i data-lucide="shield" class="w-3.5 h-3.5"></i> Klasemen Juara Umum Putra (Gudep 03.059)
                                    </span>
                                    <span class="text-xs font-bold text-blue-600">{{ count($juaraUmumPutra) }} Regu</span>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50/80 text-gray-600">
                                        <tr>
                                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider w-20">Rank</th>
                                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu Putra</th>
                                            @foreach($allMataLombaFiltered as $ml)
                                                <th scope="col" class="px-3 py-3.5 text-center text-xs font-bold uppercase tracking-wider">{{ $ml->nama }}</th>
                                            @endforeach
                                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-amber-700 uppercase tracking-wider bg-amber-50/60">Total Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @forelse($juaraUmumPutra as $ju)
                                            <tr class="hover:bg-blue-50/30 transition-colors {{ $ju['peringkat_gender'] <= 3 ? 'bg-amber-50/20' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($ju['peringkat_gender'] == 1)
                                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500 text-white font-black shadow-sm ring-2 ring-amber-200">1</span>
                                                    @elseif($ju['peringkat_gender'] == 2)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-400 text-white font-black shadow-sm ring-2 ring-slate-200">2</span>
                                                    @elseif($ju['peringkat_gender'] == 3)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-700 text-white font-black shadow-sm ring-2 ring-amber-300">3</span>
                                                    @else
                                                        <span class="font-bold text-gray-500 text-sm">{{ $ju['peringkat_gender'] }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                                    <div class="text-xs text-gray-500 mt-0.5">Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                                </td>
                                                @foreach($allMataLombaFiltered as $ml)
                                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                                        @if(isset($ju['lomba_poin'][$ml->id]))
                                                            @if($ju['lomba_poin'][$ml->id] == 3)
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-amber-100 text-amber-800 font-black text-xs ring-1 ring-amber-300" title="Juara 1">3</span>
                                                            @elseif($ju['lomba_poin'][$ml->id] == 2)
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 text-slate-800 font-black text-xs ring-1 ring-slate-300" title="Juara 2">2</span>
                                                            @elseif($ju['lomba_poin'][$ml->id] == 1)
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-amber-50 text-amber-900 font-black text-xs ring-1 ring-amber-200" title="Juara 3">1</span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-300 font-light">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="px-6 py-4 whitespace-nowrap text-center bg-amber-50/30">
                                                    <div class="text-xl font-black text-amber-700">{{ $ju['poin'] }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="{{ 3 + count($allMataLombaFiltered) }}" class="p-6 text-center text-gray-400">Belum ada data regu putra.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tabel Terpisah: Panel Putri --}}
                            <div id="panel-admin-juara-putri" class="hidden">
                                <div class="px-6 py-2 bg-pink-50 border-b border-pink-100 flex items-center justify-between">
                                    <span class="text-xs font-black uppercase tracking-wider text-pink-800 flex items-center gap-1.5">
                                        <i data-lucide="heart" class="w-3.5 h-3.5"></i> Klasemen Juara Umum Putri (Gudep 03.060)
                                    </span>
                                    <span class="text-xs font-bold text-pink-600">{{ count($juaraUmumPutri) }} Regu</span>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50/80 text-gray-600">
                                        <tr>
                                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider w-20">Rank</th>
                                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu Putri</th>
                                            @foreach($allMataLombaFiltered as $ml)
                                                <th scope="col" class="px-3 py-3.5 text-center text-xs font-bold uppercase tracking-wider">{{ $ml->nama }}</th>
                                            @endforeach
                                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-amber-700 uppercase tracking-wider bg-amber-50/60">Total Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @forelse($juaraUmumPutri as $ju)
                                            <tr class="hover:bg-pink-50/30 transition-colors {{ $ju['peringkat_gender'] <= 3 ? 'bg-amber-50/20' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($ju['peringkat_gender'] == 1)
                                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500 text-white font-black shadow-sm ring-2 ring-amber-200">1</span>
                                                    @elseif($ju['peringkat_gender'] == 2)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-400 text-white font-black shadow-sm ring-2 ring-slate-200">2</span>
                                                    @elseif($ju['peringkat_gender'] == 3)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-700 text-white font-black shadow-sm ring-2 ring-amber-300">3</span>
                                                    @else
                                                        <span class="font-bold text-gray-500 text-sm">{{ $ju['peringkat_gender'] }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                                    <div class="text-xs text-gray-500 mt-0.5">Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                                </td>
                                                @foreach($allMataLombaFiltered as $ml)
                                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                                        @if(isset($ju['lomba_poin'][$ml->id]))
                                                            @if($ju['lomba_poin'][$ml->id] == 3)
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-amber-100 text-amber-800 font-black text-xs ring-1 ring-amber-300" title="Juara 1">3</span>
                                                            @elseif($ju['lomba_poin'][$ml->id] == 2)
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-slate-100 text-slate-800 font-black text-xs ring-1 ring-slate-300" title="Juara 2">2</span>
                                                            @elseif($ju['lomba_poin'][$ml->id] == 1)
                                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-amber-50 text-amber-900 font-black text-xs ring-1 ring-amber-200" title="Juara 3">1</span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-300 font-light">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="px-6 py-4 whitespace-nowrap text-center bg-amber-50/30">
                                                    <div class="text-xl font-black text-amber-700">{{ $ju['poin'] }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="{{ 3 + count($allMataLombaFiltered) }}" class="p-6 text-center text-gray-400">Belum ada data regu putri.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Mobile View -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @if(!$isCombined)
                            <div class="p-2.5 bg-gray-50 flex gap-2 border-b border-gray-200">
                                <button type="button" onclick="showAdminJuaraTabMobile('putra')" id="btn-juara-putra-m" class="flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-blue-600 text-white">Putra ({{ count($juaraUmumPutra) }})</button>
                                <button type="button" onclick="showAdminJuaraTabMobile('putri')" id="btn-juara-putri-m" class="flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-gray-200 text-gray-700">Putri ({{ count($juaraUmumPutri) }})</button>
                            </div>

                            {{-- Mobile Putra --}}
                            <div id="panel-admin-juara-putra-m" class="divide-y divide-gray-100">
                                @foreach($juaraUmumPutra as $ju)
                                    <div class="p-4 {{ $ju['peringkat_gender'] <= 3 ? 'bg-amber-50/20' : 'bg-white' }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-xs font-black {{ $ju['peringkat_gender'] == 1 ? 'bg-amber-500 text-white' : ($ju['peringkat_gender'] == 2 ? 'bg-slate-400 text-white' : ($ju['peringkat_gender'] == 3 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }}">{{ $ju['peringkat_gender'] }}</span>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                                    <div class="text-xs text-gray-500">Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-[10px] text-gray-400 uppercase font-semibold">Total Poin</div>
                                                <div class="text-lg font-black text-amber-700">{{ $ju['poin'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Mobile Putri --}}
                            <div id="panel-admin-juara-putri-m" class="divide-y divide-gray-100 hidden">
                                @foreach($juaraUmumPutri as $ju)
                                    <div class="p-4 {{ $ju['peringkat_gender'] <= 3 ? 'bg-amber-50/20' : 'bg-white' }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-xs font-black {{ $ju['peringkat_gender'] == 1 ? 'bg-amber-500 text-white' : ($ju['peringkat_gender'] == 2 ? 'bg-slate-400 text-white' : ($ju['peringkat_gender'] == 3 ? 'bg-amber-700 text-white' : 'bg-gray-100 text-gray-600')) }}">{{ $ju['peringkat_gender'] }}</span>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                                    <div class="text-xs text-gray-500">Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-[10px] text-gray-400 uppercase font-semibold">Total Poin</div>
                                                <div class="text-lg font-black text-amber-700">{{ $ju['poin'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Mobile Gabungan --}}
                            @foreach($juaraUmum as $ju)
                                <div class="p-4 {{ $loop->iteration <= 3 ? 'bg-amber-50/20' : 'bg-white' }}">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="shrink-0">
                                                @if($ju['peringkat'] == 1)
                                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500 text-white font-black shadow-sm ring-2 ring-amber-200">1</span>
                                                @elseif($ju['peringkat'] == 2)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-400 text-white font-black shadow-sm ring-2 ring-slate-200">2</span>
                                                @elseif($ju['peringkat'] == 3)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-700 text-white font-black shadow-sm ring-2 ring-amber-300">3</span>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gray-100 text-gray-600 font-bold border border-gray-200 text-xs">{{ $ju['peringkat'] }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $ju['reguProfile']->nama_regu }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($ju['reguProfile']->jenis) }} &bull; Regu {{ $ju['reguProfile']->nomor_regu }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Total Poin</div>
                                            <div class="text-lg font-black text-amber-700">{{ $ju['poin'] }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-2 bg-gray-50/80 rounded-xl p-2.5 border border-gray-100">
                                        @foreach($allMataLombaFiltered as $ml)
                                            @if(isset($ju['lomba_poin'][$ml->id]))
                                                <div class="text-center p-1 bg-white rounded-lg border border-gray-100 shadow-2xs">
                                                    <div class="text-[9px] text-gray-500 font-medium mb-0.5 truncate" title="{{ $ml->nama }}">{{ Str::limit($ml->nama, 9) }}</div>
                                                    @if($ju['lomba_poin'][$ml->id] == 3)
                                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-amber-100 text-amber-800 font-black text-[10px]">3</span>
                                                    @elseif($ju['lomba_poin'][$ml->id] == 2)
                                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-slate-100 text-slate-800 font-black text-[10px]">2</span>
                                                    @elseif($ju['lomba_poin'][$ml->id] == 1)
                                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-amber-50 text-amber-900 font-black text-[10px]">1</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Filter Mata Lomba -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-200/80 gap-3">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center mr-3 border border-emerald-200/50">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Filter Leaderboard Mata Lomba</h3>
                        <p class="text-xs text-gray-400">Pilih mata lomba untuk melihat rincian penilaian juri</p>
                    </div>
                </div>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="w-full sm:w-auto flex items-center">
                    <select name="lomba_filter" id="lomba_filter" class="block w-full sm:w-64 px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 truncate transition" onchange="this.form.submit()">
                        <option value="" class="truncate">Semua Mata Lomba</option>
                        @foreach($allMataLomba as $ml)
                            <option value="{{ $ml->id }}" {{ (isset($lombaFilterId) && $lombaFilterId == $ml->id) ? 'selected' : '' }} class="truncate">
                                {{ $ml->nama }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Leaderboard Tables -->
            @forelse($leaderboards as $lombaId => $lombaData)
                @php
                    $lomba = $lombaData['mata_lomba'];
                    $lb = $lombaData['leaderboard'];
                    $juris = $lombaData['juri_columns'];
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-8">
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between bg-gradient-to-r from-brand-forest to-emerald-800 text-white gap-2">
                        <h3 class="text-base sm:text-lg font-bold flex items-center gap-2.5">
                            <i data-lucide="medal" class="w-5 h-5 text-amber-400"></i>
                            <span>Leaderboard: {{ $lomba->nama }}</span>
                        </h3>
                        <span class="text-xs text-emerald-200 bg-white/10 px-3 py-1 rounded-full w-fit">
                            {{ count($juris) }} Juri Ditugaskan
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-100">
                            @forelse($lb as $row)
                                <div class="p-4 {{ $loop->iteration <= 3 ? 'bg-amber-50/20' : 'bg-white' }}">
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="shrink-0">
                                                @if($row['peringkat'] == 1)
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-500 text-white font-black text-xs shadow-sm ring-1 ring-amber-300">1</span>
                                                @elseif($row['peringkat'] == 2)
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-slate-400 text-white font-black text-xs shadow-sm ring-1 ring-slate-300">2</span>
                                                @elseif($row['peringkat'] == 3)
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-amber-700 text-white font-black text-xs shadow-sm ring-1 ring-amber-300">3</span>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-gray-100 text-gray-600 font-bold text-xs border border-gray-200">
                                                        {{ $row['peringkat'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $row['reguProfile']->nama_regu }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($row['reguProfile']->jenis) }} &bull; Regu {{ $row['reguProfile']->nomor_regu }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Total Nilai</div>
                                            <div class="text-base font-black text-emerald-800">{{ number_format($row['total_nilai'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2 bg-gray-50/80 rounded-xl p-2.5 border border-gray-100">
                                        @foreach($juris as $juri)
                                            <div class="text-center bg-white p-2 rounded-lg shadow-2xs border border-gray-100">
                                                <div class="text-[10px] text-gray-400 font-semibold mb-0.5 truncate uppercase" title="{{ $juri->name }}">{{ Str::limit($juri->name, 12) }}</div>
                                                @if(isset($row['juri_scores'][$juri->id]))
                                                    <div class="text-sm font-black text-gray-800">{{ $row['juri_scores'][$juri->id] }}</div>
                                                @else
                                                    <div class="text-sm text-gray-300 font-light">-</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400">
                                    <i data-lucide="clipboard-x" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                    <p class="text-sm">Belum ada regu yang dinilai pada mata lomba ini.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80 text-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold uppercase tracking-wider w-20">Peringkat</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Nama Regu</th>
                                    @foreach($juris as $juri)
                                        <th scope="col" class="px-4 py-3.5 text-center text-xs font-bold uppercase tracking-wider min-w-[110px]">
                                            <div class="truncate" title="{{ $juri->name }}">{{ Str::limit($juri->name, 15) }}</div>
                                        </th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-brand-forest uppercase tracking-wider bg-emerald-50/50">Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($lb as $row)
                                    <tr class="hover:bg-gray-50/60 transition-colors {{ $loop->iteration <= 3 ? 'bg-amber-50/15' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($row['peringkat'] == 1)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-500 text-white font-black text-xs shadow-sm ring-1 ring-amber-200">1</span>
                                            @elseif($row['peringkat'] == 2)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-slate-400 text-white font-black text-xs shadow-sm ring-1 ring-slate-200">2</span>
                                            @elseif($row['peringkat'] == 3)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-amber-700 text-white font-black text-xs shadow-sm ring-1 ring-amber-200">3</span>
                                            @else
                                                <span class="font-bold text-gray-500 text-sm">{{ $row['peringkat'] }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $row['reguProfile']->nama_regu }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($row['reguProfile']->jenis) }} &bull; Regu {{ $row['reguProfile']->nomor_regu }}</div>
                                        </td>
                                        @foreach($juris as $juri)
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                @if(isset($row['juri_scores'][$juri->id]))
                                                    <span class="text-sm text-gray-800 font-bold inline-block px-2.5 py-1 bg-gray-50 rounded-lg border border-gray-100 min-w-[2.5rem]">{{ $row['juri_scores'][$juri->id] }}</span>
                                                @else
                                                    <span class="text-sm text-gray-300 font-light">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-emerald-50/30">
                                            <div class="text-base font-black text-brand-forest">{{ number_format($row['total_nilai'], 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + count($juris) }}" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="bg-gray-50 p-4 rounded-full mb-3">
                                                    <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-300"></i>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada data penilaian untuk mata lomba ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-12 text-center">
                    <i data-lucide="folder-search" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <h3 class="text-base font-bold text-gray-800">Tidak ada data ditemukan</h3>
                    <p class="text-xs text-gray-500 mt-1">Belum ada mata lomba atau penilaian yang tersedia.</p>
                </div>
            @endforelse

        </div>
    </div>

    <script>
        function showAdminJuaraTab(tab) {
            const panelPutra = document.getElementById('panel-admin-juara-putra');
            const panelPutri = document.getElementById('panel-admin-juara-putri');
            const btnPutra = document.getElementById('btn-juara-putra');
            const btnPutri = document.getElementById('btn-juara-putri');

            if (!panelPutra || !panelPutri) return;

            if (tab === 'putra') {
                panelPutra.classList.remove('hidden');
                panelPutri.classList.add('hidden');
                if (btnPutra) {
                    btnPutra.className = 'px-3 py-1.5 rounded-lg transition bg-blue-600 text-white shadow-sm flex items-center gap-1.5';
                }
                if (btnPutri) {
                    btnPutri.className = 'px-3 py-1.5 rounded-lg transition text-white/80 hover:text-white flex items-center gap-1.5';
                }
            } else {
                panelPutra.classList.add('hidden');
                panelPutri.classList.remove('hidden');
                if (btnPutri) {
                    btnPutri.className = 'px-3 py-1.5 rounded-lg transition bg-pink-600 text-white shadow-sm flex items-center gap-1.5';
                }
                if (btnPutra) {
                    btnPutra.className = 'px-3 py-1.5 rounded-lg transition text-white/80 hover:text-white flex items-center gap-1.5';
                }
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function showAdminJuaraTabMobile(tab) {
            const panelPutraM = document.getElementById('panel-admin-juara-putra-m');
            const panelPutriM = document.getElementById('panel-admin-juara-putri-m');
            const btnPutraM = document.getElementById('btn-juara-putra-m');
            const btnPutriM = document.getElementById('btn-juara-putri-m');

            if (!panelPutraM || !panelPutriM) return;

            if (tab === 'putra') {
                panelPutraM.classList.remove('hidden');
                panelPutriM.classList.add('hidden');
                if (btnPutraM) {
                    btnPutraM.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-blue-600 text-white';
                }
                if (btnPutriM) {
                    btnPutriM.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-gray-200 text-gray-700';
                }
            } else {
                panelPutraM.classList.add('hidden');
                panelPutriM.classList.remove('hidden');
                if (btnPutriM) {
                    btnPutriM.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-pink-600 text-white';
                }
                if (btnPutraM) {
                    btnPutraM.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-gray-200 text-gray-700';
                }
            }
        }
    </script>
@endsection