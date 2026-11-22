@extends('layouts.main')

@section('title', 'Katalog & Juknis Lomba - Spencerone Camp Season II')

@section('content')
<!-- SUB-HERO BANNER (Senada dengan Desain Beranda Season II) -->
<section class="relative bg-[#0B2317] overflow-hidden border-b-4 border-amber-500 text-white select-none">
    
    <!-- Cyber-Scout Background Mesh -->
    <div class="absolute inset-0 pointer-events-none opacity-25">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lomba-mesh" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#D97706" stroke-width="0.8" stroke-opacity="0.35"/>
                    <circle cx="48" cy="0" r="1.5" fill="#D97706" fill-opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#lomba-mesh)"/>
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
            <i data-lucide="award" class="w-4 h-4 text-amber-400"></i>
            <span>Panduan Teknis Resmi & Standar Penilaian 2026</span>
        </div>

        <!-- Main Title -->
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
            Katalog Lomba & <span class="bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-300 bg-clip-text text-transparent">Petunjuk Teknis</span>
        </h1>

        <p class="text-xs sm:text-base text-amber-100/90 max-w-2xl mx-auto font-medium leading-relaxed mb-4">
            Pedoman 5 Mata Lomba Perebutan Medali Juara Umum serta 3 Kegiatan Partisipatif Kepramukaan Spencerone Camp Season II.
        </p>

        <!-- Date & Location -->
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs text-white font-semibold bg-emerald-950/70 backdrop-blur-md px-5 py-2 rounded-full border border-emerald-500/30 shadow-md inline-flex">
            <span class="flex items-center gap-1.5 text-amber-300">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Gudep 03.059 - 03.060 SMPN 1 Cerbon</span>
            </span>
            <span class="text-emerald-500/60 hidden sm:inline">•</span>
            <span class="flex items-center gap-1.5 text-white/90">
                <i data-lucide="map-pin" class="w-4 h-4 text-amber-400"></i>
                <span>Bumi Perkemahan SMPN 1 Cerbon</span>
            </span>
        </div>

    </div>
</section>

<!-- MAIN CONTENT WRAPPER (Khaki Cream Background senada Beranda) -->
<div class="bg-[#F4F1EA] py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" v-pre>
        
        <!-- SECTION 1: 5 MATA LOMBA RESMI (SCORED) -->
        <div class="mb-14 sm:mb-16">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 sm:mb-8 pb-4 border-b-2 border-gray-200/80">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#0B2317] text-amber-400 flex items-center justify-center shadow-md flex-shrink-0">
                        <i data-lucide="trophy" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-brand-forest uppercase tracking-tight">5 Mata Lomba Berbobot Medali</h2>
                        <p class="text-xs text-gray-600 font-semibold">Penilaian resmi dewan juri untuk menentukan klasemen Juara Umum</p>
                    </div>
                </div>

                <span class="self-start sm:self-auto px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-black uppercase tracking-wider">
                    Bobot 100% per Lomba
                </span>
            </div>

            <div class="space-y-4 sm:space-y-5">
                @php
                    $scoredLombas = $mataLombas->where('is_scored', true)->sortBy('urutan')->values();
                @endphp

                @forelse($scoredLombas as $index => $lomba)
                    @php
                        $icon = match($lomba->slug) {
                            'tapak-kemah' => 'tent',
                            'lkbb' => 'shield',
                            'cerdas-cermat' => 'brain',
                            'masak-rimba' => 'flame',
                            'hasta-karya' => 'sparkles',
                            default => 'award'
                        };
                        $badgeBg = match($lomba->slug) {
                            'cerdas-cermat' => 'bg-blue-600',
                            'hasta-karya' => 'bg-purple-600',
                            'masak-rimba' => 'bg-orange-600',
                            default => 'bg-amber-600'
                        };
                    @endphp

                    <!-- ACCORDION CARD (Native HTML5 Details/Summary, 100% Reliable & Elegant) -->
                    <details class="group bg-white rounded-3xl border-2 border-gray-200 shadow-sm hover:border-amber-500/70 hover:shadow-lg transition-all duration-200 overflow-hidden" {{ $index === 0 ? 'open' : '' }}>
                        <summary class="list-none cursor-pointer p-5 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-4 select-none hover:bg-amber-50/25 transition-colors">
                            <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                                <div class="w-13 h-13 sm:w-16 sm:h-16 rounded-2xl bg-[#0B2317] text-amber-400 flex items-center justify-center shadow-md flex-shrink-0 group-hover:scale-105 transition-transform">
                                    <i data-lucide="{{ $icon }}" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black {{ $badgeBg }} text-white tracking-wider uppercase shadow-xs">
                                            {{ $lomba->kode ?? 'LOMBA' }}
                                        </span>
                                        <span class="text-xs font-bold text-gray-500">Lomba #{{ $lomba->urutan }}</span>
                                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Maks: {{ (int)$lomba->nilai_maksimal }} Poin
                                        </span>
                                        @if($lomba->slug === 'cerdas-cermat')
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200 flex items-center gap-1">
                                                <i data-lucide="zap" class="w-3 h-3"></i> Real-Time WebSocket Arena
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg sm:text-2xl font-black text-[#0B2317] leading-tight group-hover:text-amber-600 transition-colors">
                                        {{ $lomba->nama }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1 line-clamp-2">
                                        {{ $lomba->deskripsi }}
                                    </p>
                                </div>
                            </div>

                            <!-- Chevron Indicator -->
                            <div class="flex items-center justify-between md:justify-end gap-3 self-end md:self-center w-full md:w-auto pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                                <span class="text-xs font-bold text-emerald-800 group-open:hidden">
                                    Buka Juknis & Bobot
                                </span>
                                <span class="text-xs font-bold text-amber-600 hidden group-open:inline">
                                    Tutup Detail
                                </span>
                                <div class="w-9 h-9 rounded-full bg-emerald-900/10 flex items-center justify-center text-emerald-900 group-hover:bg-[#0B2317] group-hover:text-amber-400 group-open:rotate-180 group-open:bg-amber-500 group-open:text-white transition-all duration-300">
                                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </summary>

                        <!-- Accordion Content -->
                        <div class="border-t border-gray-200/80 bg-stone-50/70 p-5 sm:p-8 space-y-6">
                            
                            <!-- Indikator & Bobot Penilaian -->
                            @php
                                $isPbb = ($lomba->slug === 'lkbb' || $lomba->slug === 'lkbb-tongkat' || str_contains(strtolower($lomba->nama), 'pbb') || $lomba->scoringCriteria->contains(fn($c) => !empty($c->rubrik)));
                            @endphp

                            @if($lomba->scoringCriteria && $lomba->scoringCriteria->count() > 0)
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                        <h4 class="text-xs sm:text-sm uppercase font-extrabold text-[#0B2317] tracking-wider flex items-center gap-2">
                                            <i data-lucide="check-circle-2" class="w-4 h-4 text-amber-500"></i>
                                            Indikator & Aspek Penilaian Resmi
                                        </h4>
                                        @if($isPbb)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 rounded-full text-[11px] font-bold">
                                                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-emerald-700"></i>
                                                Sistem Rubrik 9 Jenjang Penilaian (Kosong s/d Sangat Baik)
                                            </span>
                                        @endif
                                    </div>

                                    @if($isPbb)
                                        @php
                                            $pbbGroups = $lomba->scoringCriteria->sortBy('urutan')->groupBy(fn($c) => $c->kelompok ?: 'Penilaian Umum');
                                        @endphp
                                        <div class="space-y-3">
                                            @foreach($pbbGroups as $groupName => $items)
                                                <div class="bg-white rounded-2xl border border-gray-200/90 shadow-2xs overflow-hidden">
                                                    <div class="px-4 py-2.5 bg-emerald-950 text-white flex items-center justify-between">
                                                        <span class="text-xs font-black uppercase tracking-wider flex items-center gap-2">
                                                            <i data-lucide="bookmark" class="w-3.5 h-3.5 text-amber-400"></i>
                                                            {{ $groupName }}
                                                        </span>
                                                        <span class="text-[11px] text-emerald-200 font-bold bg-white/10 px-2 py-0.5 rounded-md">
                                                            {{ $items->count() }} Poin Penilaian
                                                        </span>
                                                    </div>
                                                    <div class="p-3.5 sm:p-4">
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 text-xs">
                                                            @foreach($items as $idx => $kriteria)
                                                                <div class="flex items-center gap-2 p-2 rounded-xl bg-stone-50 border border-gray-100 hover:bg-emerald-50/50 hover:border-emerald-200 transition-colors">
                                                                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-900 font-black text-[11px] flex items-center justify-center shrink-0">
                                                                        {{ $loop->iteration }}
                                                                    </span>
                                                                    <span class="font-bold text-gray-800 truncate" title="{{ $kriteria->nama_kriteria }}">
                                                                        {{ $kriteria->nama_kriteria }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                            @foreach($lomba->scoringCriteria->sortBy('urutan') as $kriteria)
                                                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-xs flex flex-col justify-between hover:border-amber-400 transition-colors">
                                                    <span class="text-xs font-bold text-gray-800 leading-snug">{{ $kriteria->nama_kriteria }}</span>
                                                    <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-2">
                                                        <span class="text-[11px] text-gray-500 font-semibold">Bobot:</span>
                                                        <span class="text-xs sm:text-sm font-black text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-lg border border-amber-200">
                                                            {{ (int)$kriteria->nilai_max }}%
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @elseif($lomba->kriteria_penilaian)
                                <div>
                                    <h4 class="text-xs sm:text-sm uppercase font-extrabold text-[#0B2317] tracking-wider flex items-center gap-2 mb-3">
                                        <i data-lucide="check-circle-2" class="w-4 h-4 text-amber-500"></i>
                                        Kriteria & Aspek Penilaian
                                    </h4>
                                    <div class="bg-white p-4 rounded-2xl border border-gray-200 text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line font-medium">
                                        {{ $lomba->kriteria_penilaian }}
                                    </div>
                                </div>
                            @endif

                            <!-- Petunjuk Teknis & Ketentuan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pt-1">
                                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs">
                                    <h5 class="text-xs font-black uppercase tracking-wider text-[#0B2317] mb-2.5 flex items-center gap-1.5">
                                        <i data-lucide="file-text" class="w-4 h-4 text-amber-500"></i> Petunjuk Teknis (Juknis)
                                    </h5>
                                    <div class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line font-medium">
                                        {{ $lomba->petunjuk_teknis ?: 'Petunjuk teknis resmi akan dijelaskan pada saat technical meeting panitia.' }}
                                    </div>
                                </div>

                                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs">
                                    <h5 class="text-xs font-black uppercase tracking-wider text-[#0B2317] mb-2.5 flex items-center gap-1.5">
                                        <i data-lucide="shield-alert" class="w-4 h-4 text-emerald-600"></i> Ketentuan Pelaksanaan
                                    </h5>
                                    <div class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line font-medium">
                                        {{ $lomba->ketentuan_pelaksanaan ?: 'Ketentuan pelaksanaan mengikuti arahan panitia dan tata tertib perkemahan.' }}
                                    </div>
                                </div>
                            </div>

                            @if($lomba->slug === 'cerdas-cermat')
                                <div class="bg-blue-50/80 border border-blue-200 rounded-2xl p-4 sm:p-5 text-blue-950 text-xs sm:text-sm">
                                    <div class="flex items-center gap-2 font-bold mb-1">
                                        <i data-lucide="laptop" class="w-4 h-4 text-blue-600"></i>
                                        <span>Fitur Scout Cyber Quiz Arena Season II:</span>
                                    </div>
                                    <p class="text-blue-800 leading-relaxed">
                                        Lomba ini menggunakan platform digital terpusat dengan sinkronisasi soal real-time, anti-cheat detection, auto-save jawaban bertahap, dan klasemen langsung di layar proyektor panggung.
                                    </p>
                                </div>
                            @endif

                        </div>
                    </details>
                @empty
                    <div class="bg-white p-8 rounded-2xl border text-center text-gray-500">
                        Belum ada mata lomba resmi yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- SECTION 2: KEGIATAN PENDUKUNG (NON-SKOR) -->
        <div>
            <div class="flex items-center gap-3.5 mb-6 sm:mb-8 pb-4 border-b-2 border-gray-200/80">
                <div class="w-12 h-12 rounded-2xl bg-[#0B2317] text-white flex items-center justify-center shadow-md flex-shrink-0">
                    <i data-lucide="sparkles" class="w-6 h-6 text-amber-400"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-brand-forest uppercase tracking-tight">Kegiatan Partisipatif & Non-Skor</h2>
                    <p class="text-xs text-gray-600 font-semibold">Giat Alam Bebas, Bakti Lingkungan, dan Kreasi Seni Malam Keakraban</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                @php
                    $activities = $mataLombas->where('is_scored', false)->sortBy('urutan')->values();
                @endphp

                @forelse($activities as $act)
                    @php
                        $actIcon = match($act->slug) {
                            'wide-game' => 'map',
                            'operasi-semut' => 'leaf',
                            'api-unggun' => 'flame',
                            default => 'compass'
                        };
                        $categoryColor = match($act->kategori) {
                            'giat_seni' => 'bg-purple-100 text-purple-800 border-purple-200',
                            default => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                        };
                    @endphp
                    <div class="bg-white rounded-3xl border-2 border-gray-200 p-6 shadow-sm hover:shadow-xl hover:border-amber-500/60 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-[#0B2317]/10 text-[#0B2317] flex items-center justify-center">
                                    <i data-lucide="{{ $actIcon }}" class="w-6 h-6 text-[#0B2317]"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black border uppercase tracking-wider {{ $categoryColor }}">
                                    {{ $act->kategori == 'giat_seni' ? 'Giat Seni' : 'Giat Alam' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-black text-[#0B2317] mb-2">{{ $act->nama }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-4">
                                {{ $act->deskripsi }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <span class="text-[11px] font-bold text-gray-500 block uppercase tracking-wider">Ketentuan:</span>
                            <p class="text-xs text-gray-700 mt-0.5 font-medium">
                                {{ $act->ketentuan_pelaksanaan ?: 'Diikuti seluruh peserta regu secara aktif dan penuh kegembiraan.' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-6 rounded-2xl border text-center text-gray-500 text-sm">
                        Belum ada kegiatan non-skor yang dikonfigurasi.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush