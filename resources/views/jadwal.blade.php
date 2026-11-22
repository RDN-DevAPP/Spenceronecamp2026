@extends('layouts.main')

@section('title', 'Rundown & Jadwal Kegiatan - Spencerone Camp Season II')

@section('content')
<!-- SUB-HERO BANNER (Senada dengan Desain Beranda Season II) -->
<section class="relative bg-[#0B2317] overflow-hidden border-b-4 border-amber-500 text-white select-none">
    
    <!-- Cyber-Scout Background Mesh -->
    <div class="absolute inset-0 pointer-events-none opacity-25">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="jadwal-mesh" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#D97706" stroke-width="0.8" stroke-opacity="0.35"/>
                    <circle cx="48" cy="0" r="1.5" fill="#D97706" fill-opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#jadwal-mesh)"/>
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
            <i data-lucide="calendar" class="w-4 h-4 text-amber-400"></i>
            <span>Agenda & Susunan Acara Resmi 3 Hari 2 Malam</span>
        </div>

        <!-- Main Title -->
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
            Rundown & <span class="bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-300 bg-clip-text text-transparent">Jadwal Kegiatan</span>
        </h1>

        <p class="text-xs sm:text-base text-amber-100/90 max-w-2xl mx-auto font-medium leading-relaxed mb-4">
            Panduan rangkaian upacara, waktu pelaksanaan 5 mata lomba resmi, giat penjelajahan alam, ibadah, dan malam api unggun.
        </p>

        <!-- Date & Location -->
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs text-white font-semibold bg-emerald-950/70 backdrop-blur-md px-5 py-2 rounded-full border border-emerald-500/30 shadow-md inline-flex mb-6">
            <span class="flex items-center gap-1.5 text-amber-300">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>{{ $eventSettings['formatted_date'] ?? '15 - 17 September 2026' }}</span>
            </span>
            <span class="text-emerald-500/60 hidden sm:inline">•</span>
            <span class="flex items-center gap-1.5 text-white/90">
                <i data-lucide="map-pin" class="w-4 h-4 text-amber-400"></i>
                <span>{{ $eventSettings['event_location'] ?? 'Bumi Perkemahan SMPN 1 Cerbon' }}</span>
            </span>
        </div>

        <!-- CATEGORY FILTER BUTTONS (Modern Season II Pills) -->
        <div class="flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
            <button @click="selectedCategory = 'semua'"
                :class="selectedCategory === 'semua' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black shadow-lg border-amber-300' : 'bg-white/10 hover:bg-white/20 text-white/90 font-semibold border-white/20'"
                class="px-4 py-2 rounded-xl text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i> Semua Agenda
            </button>
            <button @click="selectedCategory = 'lomba'"
                :class="selectedCategory === 'lomba' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black shadow-lg border-amber-300' : 'bg-white/10 hover:bg-white/20 text-white/90 font-semibold border-white/20'"
                class="px-4 py-2 rounded-xl text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="trophy" class="w-3.5 h-3.5"></i> Giat Lomba
            </button>
            <button @click="selectedCategory = 'upacara'"
                :class="selectedCategory === 'upacara' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black shadow-lg border-amber-300' : 'bg-white/10 hover:bg-white/20 text-white/90 font-semibold border-white/20'"
                class="px-4 py-2 rounded-xl text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="flag" class="w-3.5 h-3.5"></i> Upacara & Apel
            </button>
            <button @click="selectedCategory = 'alam'"
                :class="selectedCategory === 'alam' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black shadow-lg border-amber-300' : 'bg-white/10 hover:bg-white/20 text-white/90 font-semibold border-white/20'"
                class="px-4 py-2 rounded-xl text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="compass" class="w-3.5 h-3.5"></i> Giat Alam / Seni
            </button>
            <button @click="selectedCategory = 'istirahat'"
                :class="selectedCategory === 'istirahat' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-black shadow-lg border-amber-300' : 'bg-white/10 hover:bg-white/20 text-white/90 font-semibold border-white/20'"
                class="px-4 py-2 rounded-xl text-xs border transition-all flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="moon" class="w-3.5 h-3.5"></i> Ibadah & Rehat
            </button>
        </div>

    </div>
</section>

<!-- MAIN TIMELINE WRAPPER (Khaki Cream Background) -->
<div class="bg-[#F4F1EA] py-10 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($jadwals->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border-2 border-gray-200 shadow-sm">
                <div class="w-16 h-16 bg-[#0B2317]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#0B2317]">
                    <i data-lucide="calendar-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-[#0B2317]">Jadwal Belum Dipublikasikan</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-sm mx-auto">Panitia sedang memfinalisasi susunan agenda acara. Silakan cek kembali secara berkala.</p>
            </div>
        @else
            <div class="space-y-12">
                @foreach($jadwals as $tanggal => $items)
                    <div class="relative bg-white rounded-3xl p-6 sm:p-8 border-2 border-gray-200 shadow-sm">
                        
                        <!-- Day Header -->
                        <div class="flex items-center gap-4 mb-8 pb-4 border-b-2 border-gray-100">
                            <div class="w-14 h-14 rounded-2xl bg-[#0B2317] text-amber-400 flex items-center justify-center shadow-md font-black text-xl flex-shrink-0 border-2 border-amber-400/50">
                                H{{ $items->first()->hari_ke }}
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-[#0B2317] uppercase tracking-tight">
                                    Hari Ke-{{ $items->first()->hari_ke }}
                                </h3>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-amber-700">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    <span>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Track -->
                        <div class="relative pl-6 sm:pl-8 border-l-2 border-emerald-900/20 space-y-5 ml-4 sm:ml-6">
                            @foreach($items as $j)
                                @php
                                    $category = 'semua';
                                    $lowKegiatan = strtolower($j->kegiatan);
                                    if (str_contains($lowKegiatan, 'lomba') || str_contains($lowKegiatan, 'quiz') || str_contains($lowKegiatan, 'craftsmanship') || str_contains($lowKegiatan, 'masterchef') || str_contains($lowKegiatan, 'pbb') || str_contains($lowKegiatan, 'bivak') || str_contains($lowKegiatan, 'lkbb') || str_contains($lowKegiatan, 'tapak kemah')) {
                                        $category = 'lomba';
                                    } elseif (str_contains($lowKegiatan, 'upacara') || str_contains($lowKegiatan, 'apel') || str_contains($lowKegiatan, 'registrasi')) {
                                        $category = 'upacara';
                                    } elseif (str_contains($lowKegiatan, 'wide game') || str_contains($lowKegiatan, 'operasi semut') || str_contains($lowKegiatan, 'senam') || str_contains($lowKegiatan, 'api unggun')) {
                                        $category = 'alam';
                                    } elseif (str_contains($lowKegiatan, 'ishoma') || str_contains($lowKegiatan, 'sholat') || str_contains($lowKegiatan, 'tidur') || str_contains($lowKegiatan, 'istirahat')) {
                                        $category = 'istirahat';
                                    }

                                    $catBadge = match($category) {
                                        'lomba' => 'bg-amber-100 text-amber-900 border-amber-200',
                                        'upacara' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                                        'alam' => 'bg-purple-100 text-purple-900 border-purple-200',
                                        'istirahat' => 'bg-blue-100 text-blue-900 border-blue-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp

                                <div v-show="matchesFilter('{{ $category }}')"
                                    class="relative bg-stone-50/80 rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-xs hover:border-amber-400 hover:bg-white hover:shadow-md transition-all group">
                                    
                                    <!-- Timeline Node Pin -->
                                    <div class="absolute -left-[31px] sm:-left-[39px] top-5 w-4 h-4 rounded-full bg-white border-4 border-amber-500 shadow-sm group-hover:scale-125 group-hover:border-[#0B2317] transition-all"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                        <!-- Time Badge -->
                                        <div class="inline-flex items-center gap-2 bg-[#0B2317]/10 px-3 py-1 rounded-xl text-[#0B2317] font-extrabold text-xs w-fit">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                            <span>{{ substr($j->waktu_mulai, 0, 5) }} - {{ $j->waktu_selesai ? substr($j->waktu_selesai, 0, 5) : 'Selesai' }} WITA</span>
                                        </div>

                                        <!-- Category Pill -->
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black border uppercase tracking-wider {{ $catBadge }} w-fit">
                                            {{ strtoupper($category) }}
                                        </span>
                                    </div>

                                    <!-- Kegiatan Title -->
                                    <h4 class="text-base sm:text-lg font-black text-[#0B2317] leading-snug group-hover:text-amber-600 transition-colors">
                                        {{ $j->kegiatan }}
                                    </h4>

                                    <!-- Lokasi -->
                                    @if($j->lokasi)
                                        <div class="mt-2.5 flex items-center gap-1.5 text-xs font-semibold text-gray-600">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-500 flex-shrink-0"></i>
                                            <span>{{ $j->lokasi }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    window.pageData = function () {
        const selectedCategory = Vue.ref('semua');

        const matchesFilter = (itemCategory) => {
            if (selectedCategory.value === 'semua') return true;
            return selectedCategory.value === itemCategory;
        };

        return {
            selectedCategory,
            matchesFilter
        };
    };
</script>
@endpush