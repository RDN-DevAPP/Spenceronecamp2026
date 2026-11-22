@extends('layouts.main')

@section('title', 'Daftar Regu Terdaftar - Spencerone Camp Season II')

@section('content')
<!-- SUB-HERO BANNER (Senada dengan Desain Beranda Season II) -->
<section class="relative bg-[#0B2317] overflow-hidden border-b-4 border-amber-500 text-white select-none">
    
    <!-- Cyber-Scout Background Mesh -->
    <div class="absolute inset-0 pointer-events-none opacity-25">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="regu-mesh" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#D97706" stroke-width="0.8" stroke-opacity="0.35"/>
                    <circle cx="48" cy="0" r="1.5" fill="#D97706" fill-opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#regu-mesh)"/>
        </svg>
    </div>

    <!-- Ambient Lighting Glows -->
    <div class="absolute top-1/2 left-1/4 -translate-y-1/2 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto py-10 sm:py-16 px-4 sm:px-6 lg:px-8 z-10 text-center">
        
        <!-- Logo & Mascot Badges -->
        <div class="flex items-center justify-center gap-3 sm:gap-4 mb-4">
            <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Spencerone Camp Season II Logo"
                class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-amber-400 shadow-2xl ring-4 ring-emerald-500/30">
            <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Dwi Pandu Digital"
                class="h-16 sm:h-20 object-contain drop-shadow-md hidden sm:block">
        </div>

        <!-- Tagline Pill -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/15 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-extrabold tracking-wide uppercase mb-3 shadow-inner">
            <i data-lucide="tent" class="w-4 h-4 text-amber-400"></i>
            <span>Partisipan Resmi & Pangkalan Terverifikasi</span>
        </div>

        <!-- Main Title -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)] px-2">
            Daftar Regu <span class="bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-300 bg-clip-text text-transparent">Peserta Perkemahan</span>
        </h1>

        <p class="text-xs sm:text-base text-amber-100/90 max-w-2xl mx-auto font-medium leading-relaxed mb-6 px-3">
            Daftar seluruh regu terdaftar Gudep 03.059 (Regu Putra) dan Gudep 03.060 (Regu Putri) Pangkalan SMPN 1 Cerbon.
        </p>

        <!-- Stats Counter Badges -->
        <div class="flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 text-xs text-white font-bold px-2">
            <div class="bg-emerald-950/80 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-amber-400/30 flex items-center gap-2 shadow-md">
                <i data-lucide="paw-print" class="w-4 h-4 text-amber-400"></i>
                <span>Putra: <strong>{{ count($reguPutra) }} Regu</strong></span>
            </div>
            <div class="bg-emerald-950/80 backdrop-blur-md px-3.5 py-2 rounded-2xl border border-pink-400/30 flex items-center gap-2 shadow-md">
                <i data-lucide="flower" class="w-4 h-4 text-pink-400"></i>
                <span>Putri: <strong>{{ count($reguPutri) }} Regu</strong></span>
            </div>
            <a href="{{ route('register.regu') }}"
                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 text-white px-4 py-2 rounded-2xl border border-amber-400/60 flex items-center gap-1.5 shadow-md transform hover:scale-105 transition">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Daftar Regu Baru</span>
            </a>
        </div>

    </div>
</section>

<!-- MAIN CONTENT WRAPPER (Khaki Cream Background) -->
<div class="bg-[#F4F1EA] py-8 sm:py-16">
    <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8">
        
        <!-- TABS PUTRA / PUTRI (Responsive Switcher with Ample Breathing Room) -->
        <div class="flex justify-center mb-8 sm:mb-10 px-1">
            <div class="bg-white p-1.5 rounded-2xl border-2 border-gray-200 shadow-sm grid grid-cols-2 gap-2 w-full max-w-md">
                <button onclick="showTab('putra')" id="tab-putra"
                    class="w-full py-3 px-3 sm:px-6 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-300 bg-[#0B2317] text-amber-300 shadow-md flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer border border-amber-400/40">
                    <i data-lucide="paw-print" class="w-4 h-4 text-amber-400 flex-shrink-0"></i>
                    <span class="truncate">Putra ({{ count($reguPutra) }})</span>
                </button>
                <button onclick="showTab('putri')" id="tab-putri"
                    class="w-full py-3 px-3 sm:px-6 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-300 bg-white text-gray-700 hover:text-[#0B2317] flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer border border-transparent">
                    <i data-lucide="flower" class="w-4 h-4 text-pink-500 flex-shrink-0"></i>
                    <span class="truncate">Putri ({{ count($reguPutri) }})</span>
                </button>
            </div>
        </div>

        <!-- TAB CONTENT: REGU PUTRA (Gudep 03.059) -->
        <div id="content-putra" class="space-y-6 sm:space-y-8">
            @forelse($reguPutra as $regu)
                <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border-2 border-gray-200 hover:border-amber-500/60 transition-all duration-300 overflow-hidden">
                    
                    <!-- Card Header Putra (Spacious Padding for Mobile & Desktop) -->
                    <div class="bg-gradient-to-r from-[#0B2317] via-[#123824] to-[#071910] p-4 sm:px-7 sm:py-5 border-b-2 border-amber-500/40">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start sm:items-center gap-3.5">
                                <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-2xl bg-amber-500/20 border-2 border-amber-400/50 flex items-center justify-center text-amber-400 font-black text-lg sm:text-xl shadow-inner flex-shrink-0 mt-0.5 sm:mt-0">
                                    {{ $regu->nomor_regu }}
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-white font-black text-lg sm:text-xl tracking-wide leading-snug">
                                            REGU {{ strtoupper($regu->nama_regu) }}
                                        </h3>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                            Gudep 03.059 Putra
                                        </span>
                                        @if($regu->user && $regu->user->role === 'regu')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500 text-white shadow-xs">
                                                Akun Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-emerald-200/80 text-xs font-semibold">
                                        Nomor Urut Regu {{ $regu->nomor_regu }} • {{ $regu->anggotaRegu->count() }} Anggota Pramuka Terdaftar
                                    </p>
                                </div>
                            </div>

                            @if($regu->user)
                                <div class="self-start sm:self-auto pl-14 sm:pl-0">
                                    <span class="text-[11px] text-emerald-200/80 font-mono bg-emerald-950/80 px-3 py-1 rounded-xl border border-emerald-500/30 inline-block">
                                        ID: {{ $regu->user->username }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Table Anggota (Desktop & Tablet View) -->
                    <div class="hidden sm:block overflow-x-auto p-4 sm:p-6">
                        <table class="w-full">
                            <thead>
                                <tr class="text-[11px] text-gray-500 font-black uppercase tracking-wider bg-stone-100/80 rounded-xl">
                                    <th class="px-4 py-3 text-left rounded-l-xl">No</th>
                                    <th class="px-4 py-3 text-left">Nama Anggota</th>
                                    <th class="px-4 py-3 text-center">Kelas</th>
                                    <th class="px-4 py-3 text-center">Tingkatan TKU</th>
                                    <th class="px-4 py-3 text-left rounded-r-xl">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($regu->anggotaRegu->sortBy('urutan') as $anggota)
                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                        <td class="px-4 py-3 text-xs font-bold text-gray-500">{{ $anggota->urutan }}</td>
                                        <td class="px-4 py-3 text-sm font-black text-[#0B2317]">{{ $anggota->nama }}</td>
                                        <td class="px-4 py-3 text-xs text-center font-bold">
                                            <span class="px-2.5 py-1 bg-stone-100 text-stone-800 rounded-lg">Kelas {{ $anggota->kelas ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center font-semibold text-gray-600 uppercase">
                                            {{ $anggota->tingkatan_tku ?? 'Ramu' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($anggota->jabatan === 'pinru')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-900 border border-amber-300">
                                                    <i data-lucide="crown" class="w-3.5 h-3.5 mr-1 text-amber-600"></i> PINRU
                                                </span>
                                            @elseif($anggota->jabatan === 'wapinru')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                                    <i data-lucide="award" class="w-3.5 h-3.5 mr-1 text-emerald-600"></i> WAPINRU
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-600 font-medium px-2 py-0.5">Anggota</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards Anggota (Mobile View - Redesigned with Ample Margins & Zero Tight Text) -->
                    <div class="sm:hidden p-4 space-y-3 bg-stone-50/50">
                        <div class="text-[11px] font-black text-gray-500 uppercase tracking-wider px-1 mb-1">
                            Susunan Anggota Regu ({{ $regu->anggotaRegu->count() }} Orang):
                        </div>
                        @foreach($regu->anggotaRegu->sortBy('urutan') as $anggota)
                            <div class="bg-white p-3.5 rounded-2xl border border-gray-200 shadow-xs space-y-2.5 hover:border-amber-400 transition-all">
                                
                                <!-- Top Row: Number & Name & Jabatan Badge -->
                                <div class="flex items-start justify-between gap-2.5">
                                    <div class="flex items-start gap-2.5 flex-1 min-w-0">
                                        <span class="w-6 h-6 rounded-lg bg-[#0B2317]/10 text-[#0B2317] flex items-center justify-center text-xs font-black flex-shrink-0 mt-0.5">
                                            {{ $anggota->urutan }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-xs font-black text-[#0B2317] leading-snug break-words">
                                                {{ $anggota->nama }}
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <!-- Jabatan Badge -->
                                    <div class="flex-shrink-0">
                                        @if($anggota->jabatan === 'pinru')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                                <i data-lucide="crown" class="w-3 h-3 mr-1 text-amber-600"></i> PINRU
                                            </span>
                                        @elseif($anggota->jabatan === 'wapinru')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                                <i data-lucide="award" class="w-3 h-3 mr-1 text-emerald-600"></i> WAPINRU
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                Anggota
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bottom Row: Kelas & TKU Pills -->
                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 text-[11px] pl-8">
                                    <span class="px-2 py-0.5 bg-stone-100 text-stone-800 font-bold rounded-md">
                                        Kelas {{ $anggota->kelas ?? '-' }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-gray-600 font-semibold uppercase">
                                        TKU {{ $anggota->tingkatan_tku ?? 'Ramu' }}
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border-2 border-gray-200 shadow-sm">
                    <p class="text-gray-500 font-semibold">Belum ada regu putra yang terdaftar.</p>
                </div>
            @endforelse
        </div>

        <!-- TAB CONTENT: REGU PUTRI (Gudep 03.060) -->
        <div id="content-putri" class="space-y-6 sm:space-y-8 hidden">
            @forelse($reguPutri as $regu)
                <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border-2 border-gray-200 hover:border-pink-500/60 transition-all duration-300 overflow-hidden">
                    
                    <!-- Card Header Putri (Spacious Padding for Mobile & Desktop) -->
                    <div class="bg-gradient-to-r from-[#1E1121] via-[#2A162D] to-[#120914] p-4 sm:px-7 sm:py-5 border-b-2 border-pink-400/40">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start sm:items-center gap-3.5">
                                <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-2xl bg-pink-500/20 border-2 border-pink-400/50 flex items-center justify-center text-pink-300 font-black text-lg sm:text-xl shadow-inner flex-shrink-0 mt-0.5 sm:mt-0">
                                    {{ $regu->nomor_regu }}
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-white font-black text-lg sm:text-xl tracking-wide leading-snug">
                                            REGU {{ strtoupper($regu->nama_regu) }}
                                        </h3>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-pink-500/20 text-pink-300 border border-pink-400/30">
                                            Gudep 03.060 Putri
                                        </span>
                                        @if($regu->user && $regu->user->role === 'regu')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-pink-500 text-white shadow-xs">
                                                Akun Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-pink-200/80 text-xs font-semibold">
                                        Nomor Urut Regu {{ $regu->nomor_regu }} • {{ $regu->anggotaRegu->count() }} Anggota Pramuka Terdaftar
                                    </p>
                                </div>
                            </div>

                            @if($regu->user)
                                <div class="self-start sm:self-auto pl-14 sm:pl-0">
                                    <span class="text-[11px] text-pink-200/80 font-mono bg-[#120914] px-3 py-1 rounded-xl border border-pink-500/30 inline-block">
                                        ID: {{ $regu->user->username }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Table Anggota (Desktop & Tablet View) -->
                    <div class="hidden sm:block overflow-x-auto p-4 sm:p-6">
                        <table class="w-full">
                            <thead>
                                <tr class="text-[11px] text-gray-500 font-black uppercase tracking-wider bg-stone-100/80 rounded-xl">
                                    <th class="px-4 py-3 text-left rounded-l-xl">No</th>
                                    <th class="px-4 py-3 text-left">Nama Anggota</th>
                                    <th class="px-4 py-3 text-center">Kelas</th>
                                    <th class="px-4 py-3 text-center">Tingkatan TKU</th>
                                    <th class="px-4 py-3 text-left rounded-r-xl">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($regu->anggotaRegu->sortBy('urutan') as $anggota)
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="px-4 py-3 text-xs font-bold text-gray-500">{{ $anggota->urutan }}</td>
                                        <td class="px-4 py-3 text-sm font-black text-[#1E1121]">{{ $anggota->nama }}</td>
                                        <td class="px-4 py-3 text-xs text-center font-bold">
                                            <span class="px-2.5 py-1 bg-stone-100 text-stone-800 rounded-lg">Kelas {{ $anggota->kelas ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center font-semibold text-gray-600 uppercase">
                                            {{ $anggota->tingkatan_tku ?? 'Ramu' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($anggota->jabatan === 'pinru')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-pink-100 text-pink-900 border border-pink-300">
                                                    <i data-lucide="crown" class="w-3.5 h-3.5 mr-1 text-pink-600"></i> PINRU
                                                </span>
                                            @elseif($anggota->jabatan === 'wapinru')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-900 border border-rose-300">
                                                    <i data-lucide="award" class="w-3.5 h-3.5 mr-1 text-rose-600"></i> WAPINRU
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-600 font-medium px-2 py-0.5">Anggota</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards Anggota (Mobile View - Redesigned with Ample Margins & Zero Tight Text) -->
                    <div class="sm:hidden p-4 space-y-3 bg-stone-50/50">
                        <div class="text-[11px] font-black text-gray-500 uppercase tracking-wider px-1 mb-1">
                            Susunan Anggota Regu ({{ $regu->anggotaRegu->count() }} Orang):
                        </div>
                        @foreach($regu->anggotaRegu->sortBy('urutan') as $anggota)
                            <div class="bg-white p-3.5 rounded-2xl border border-gray-200 shadow-xs space-y-2.5 hover:border-pink-400 transition-all">
                                
                                <!-- Top Row: Number & Name & Jabatan Badge -->
                                <div class="flex items-start justify-between gap-2.5">
                                    <div class="flex items-start gap-2.5 flex-1 min-w-0">
                                        <span class="w-6 h-6 rounded-lg bg-pink-500/10 text-pink-700 flex items-center justify-center text-xs font-black flex-shrink-0 mt-0.5">
                                            {{ $anggota->urutan }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-xs font-black text-[#1E1121] leading-snug break-words">
                                                {{ $anggota->nama }}
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <!-- Jabatan Badge -->
                                    <div class="flex-shrink-0">
                                        @if($anggota->jabatan === 'pinru')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-pink-100 text-pink-900 border border-pink-300">
                                                <i data-lucide="crown" class="w-3 h-3 mr-1 text-pink-600"></i> PINRU
                                            </span>
                                        @elseif($anggota->jabatan === 'wapinru')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-900 border border-rose-300">
                                                <i data-lucide="award" class="w-3 h-3 mr-1 text-rose-600"></i> WAPINRU
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                Anggota
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bottom Row: Kelas & TKU Pills -->
                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 text-[11px] pl-8">
                                    <span class="px-2 py-0.5 bg-stone-100 text-stone-800 font-bold rounded-md">
                                        Kelas {{ $anggota->kelas ?? '-' }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-gray-600 font-semibold uppercase">
                                        TKU {{ $anggota->tingkatan_tku ?? 'Ramu' }}
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border-2 border-gray-200 shadow-sm">
                    <p class="text-gray-500 font-semibold">Belum ada regu putri yang terdaftar.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTab(type) {
        const btnPutra = document.getElementById('tab-putra');
        const btnPutri = document.getElementById('tab-putri');
        const contentPutra = document.getElementById('content-putra');
        const contentPutri = document.getElementById('content-putri');

        if (type === 'putra') {
            btnPutra.className = 'w-full py-3 px-3 sm:px-6 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-300 bg-[#0B2317] text-amber-300 shadow-md flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer border border-amber-400/40';
            btnPutri.className = 'w-full py-3 px-3 sm:px-6 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-300 bg-white text-gray-700 hover:text-[#0B2317] flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer border border-transparent';
            contentPutra.classList.remove('hidden');
            contentPutri.classList.add('hidden');
        } else {
            btnPutri.className = 'w-full py-3 px-3 sm:px-6 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-300 bg-[#1E1121] text-pink-300 shadow-md flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer border border-pink-400/40';
            btnPutra.className = 'w-full py-3 px-3 sm:px-6 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-300 bg-white text-gray-700 hover:text-[#0B2317] flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer border border-transparent';
            contentPutri.classList.remove('hidden');
            contentPutra.classList.add('hidden');
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush