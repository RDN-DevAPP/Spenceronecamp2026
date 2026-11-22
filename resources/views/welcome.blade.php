@extends('layouts.main')

@section('title', 'Spencerone Camp Season II - SMPN 1 Cerbon')

@section('content')
    <!-- HERO SECTION (Mirip Referensi Banner Season II: Desktop & Mobile) -->
    <section class="relative bg-[#0B2317] overflow-hidden border-b-4 border-amber-500 text-white select-none">
        
        <!-- Cyber-Scout Decorative Background Mesh -->
        <div class="absolute inset-0 pointer-events-none opacity-25">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="cyber-mesh" width="48" height="48" patternUnits="userSpaceOnUse">
                        <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#D97706" stroke-width="0.8" stroke-opacity="0.35"/>
                        <circle cx="48" cy="0" r="1.5" fill="#D97706" fill-opacity="0.7"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#cyber-mesh)"/>
            </svg>
        </div>

        <!-- Ambient Lighting Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 sm:w-[650px] h-96 sm:h-[400px] bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 left-1/4 w-72 h-72 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto pt-8 pb-14 sm:pt-14 sm:pb-20 px-4 sm:px-6 lg:px-8 z-10">
            
            <!-- TOP HERO BADGE & MASKOT DUO -->
            <div class="flex flex-col items-center text-center">
                
                <!-- Desktop & Tablet Hero Grid Layout: Mascot Putra (Left) - Logo & Identity (Center) - Mascot Putri (Right) -->
                <div class="w-full grid grid-cols-1 lg:grid-cols-12 items-center gap-6 mb-6">
                    
                    <!-- Sisi Kiri: Maskot Putra (Hidden di Mobile Kecil, Tampil di Tablet & Desktop) -->
                    <div class="hidden lg:flex lg:col-span-3 flex-col items-center justify-center transform hover:scale-105 transition-all duration-300">
                        <div class="relative group">
                            <div class="absolute -inset-2 bg-gradient-to-r from-amber-500/40 to-emerald-500/40 rounded-3xl blur-md group-hover:blur-lg transition-all opacity-80"></div>
                            <img src="{{ asset('images/season2/mascot-putra.jpg') }}" alt="Maskot Pandu Putra"
                                class="relative w-44 xl:w-52 h-56 xl:h-64 object-cover object-top rounded-2xl border-2 border-amber-400/80 shadow-2xl ring-2 ring-emerald-400/30">
                            <div class="mt-2 text-center">
                                <span class="inline-block px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/40 text-[11px] font-black text-amber-300 uppercase tracking-wider">
                                    Pandu Putra • Tangkas
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tengah: Identitas Utama, Logo Perisai & Judul -->
                    <div class="lg:col-span-6 flex flex-col items-center">
                        
                        <!-- Official Logo Badge -->
                        <div class="relative mb-4 group cursor-pointer">
                            <div class="absolute -inset-3 bg-gradient-to-r from-amber-400 to-yellow-500 rounded-full blur-lg opacity-60 group-hover:opacity-100 transition duration-500"></div>
                            <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Spencerone Camp Season II Official Patch"
                                class="relative w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-full shadow-2xl border-4 border-amber-400 ring-4 ring-emerald-500/50 transform group-hover:scale-105 transition-all">
                        </div>

                        <!-- Tagline Pill -->
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/15 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-extrabold tracking-wide uppercase mb-3 shadow-inner text-center">
                            <i data-lucide="compass" class="w-4 h-4 text-amber-400 shrink-0"></i>
                            <span>{{ str_replace(', ', ' • ', $appSlogan ?? 'Tangkas di Alam • Adaptif di Zaman • Unggul Berkarakter') }}</span>
                        </div>

                        <!-- Main Headlines -->
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-2 drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
                            SPENCERONE CAMP <span class="bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-300 bg-clip-text text-transparent">SEASON II</span>
                        </h1>

                        <p class="text-sm sm:text-lg text-amber-100/90 max-w-2xl font-semibold drop-shadow-sm px-2 mb-2 italic">
                            "Empowering Next-Gen Scouts: Smart, Resilient, Eco-Digital Pioneers"
                        </p>

                        <p class="text-xs sm:text-sm text-emerald-200/90 font-bold mb-4">
                            Gugus Depan 03.059 - 03.060 Pangkalan SMP Negeri 1 Cerbon, Barito Kuala
                        </p>

                        <!-- Mobile Duo Mascot Pill (Tampil di Mobile) -->
                        <div class="lg:hidden flex items-center gap-3 bg-emerald-950/70 backdrop-blur-md px-4 py-2 rounded-2xl border border-amber-400/30 mb-5 shadow-lg">
                            <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Maskot Dwi Pandu Digital" class="h-14 object-contain">
                            <div class="text-left">
                                <span class="text-[10px] uppercase font-bold text-amber-400 tracking-wider block">Maskot Resmi Season II</span>
                                <span class="text-xs font-black text-white">Dwi Pandu Digital (Cakra & Kirana)</span>
                            </div>
                        </div>

                        <!-- Date & Location Badges -->
                        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs sm:text-sm text-white font-semibold bg-emerald-950/70 backdrop-blur-md px-4 sm:px-6 py-2 rounded-full border border-emerald-500/30 shadow-md">
                            <span class="flex items-center gap-1.5 text-amber-300">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                {{ $eventSettings['formatted_date'] ?? '15 - 17 September 2026' }}
                            </span>
                            <span class="text-emerald-500/60 hidden sm:inline">•</span>
                            <span class="flex items-center gap-1.5 text-white/90">
                                <i data-lucide="map-pin" class="w-4 h-4 text-amber-400"></i>
                                {{ $eventSettings['event_location'] ?? 'Bumi Perkemahan SMPN 1 Cerbon' }}
                            </span>
                        </div>

                    </div>

                    <!-- Sisi Kanan: Maskot Putri (Hidden di Mobile Kecil, Tampil di Tablet & Desktop) -->
                    <div class="hidden lg:flex lg:col-span-3 flex-col items-center justify-center transform hover:scale-105 transition-all duration-300">
                        <div class="relative group">
                            <div class="absolute -inset-2 bg-gradient-to-r from-emerald-500/40 to-amber-500/40 rounded-3xl blur-md group-hover:blur-lg transition-all opacity-80"></div>
                            <img src="{{ asset('images/season2/mascot-putri.jpg') }}" alt="Maskot Pandu Putri"
                                class="relative w-44 xl:w-52 h-56 xl:h-64 object-cover object-top rounded-2xl border-2 border-amber-400/80 shadow-2xl ring-2 ring-emerald-400/30">
                            <div class="mt-2 text-center">
                                <span class="inline-block px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/40 text-[11px] font-black text-amber-300 uppercase tracking-wider">
                                    Pandu Putri • Cerdas
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COUNTDOWN TIMER (Digital LED Glassmorphism - 4 Kotak Sesuai Banner) -->
                <div class="w-full max-w-xl sm:max-w-2xl mt-4 sm:mt-6">
                    <p class="text-[11px] sm:text-xs uppercase font-extrabold text-amber-300 tracking-widest mb-3 flex items-center justify-center gap-2">
                        <i data-lucide="timer" class="w-4 h-4 text-amber-400"></i>
                        @if(!empty($eventSettings['first_schedule_activity']))
                        Hitung Mundur: {{ $eventSettings['first_schedule_activity'] }} (Pukul {{ $eventSettings['first_schedule_time_formatted'] }})
                    @else
                        Hitung Mundur Pembukaan Perkemahan ({{ $eventSettings['countdown_target_readable'] ?? '15 September 2026' }})
                    @endif
                    </p>
                    <div class="grid grid-cols-4 gap-2 sm:gap-4">
                        <div class="bg-[#071910]/80 backdrop-blur-md p-2.5 sm:p-4 rounded-2xl border-2 border-amber-500/50 shadow-lg shadow-black/40 text-center">
                            <span class="block text-2xl sm:text-4xl lg:text-5xl font-black text-amber-400 font-mono tracking-wider">@{{ countdown.days }}</span>
                            <span class="text-[9px] sm:text-xs uppercase font-bold tracking-widest text-emerald-200/80">Hari</span>
                        </div>
                        <div class="bg-[#071910]/80 backdrop-blur-md p-2.5 sm:p-4 rounded-2xl border-2 border-amber-500/50 shadow-lg shadow-black/40 text-center">
                            <span class="block text-2xl sm:text-4xl lg:text-5xl font-black text-amber-400 font-mono tracking-wider">@{{ countdown.hours }}</span>
                            <span class="text-[9px] sm:text-xs uppercase font-bold tracking-widest text-emerald-200/80">Jam</span>
                        </div>
                        <div class="bg-[#071910]/80 backdrop-blur-md p-2.5 sm:p-4 rounded-2xl border-2 border-amber-500/50 shadow-lg shadow-black/40 text-center">
                            <span class="block text-2xl sm:text-4xl lg:text-5xl font-black text-amber-400 font-mono tracking-wider">@{{ countdown.minutes }}</span>
                            <span class="text-[9px] sm:text-xs uppercase font-bold tracking-widest text-emerald-200/80">Menit</span>
                        </div>
                        <div class="bg-[#071910]/80 backdrop-blur-md p-2.5 sm:p-4 rounded-2xl border-2 border-amber-500/50 shadow-lg shadow-black/40 text-center">
                            <span class="block text-2xl sm:text-4xl lg:text-5xl font-black text-amber-400 font-mono tracking-wider">@{{ countdown.seconds }}</span>
                            <span class="text-[9px] sm:text-xs uppercase font-bold tracking-widest text-emerald-200/80">Detik</span>
                        </div>
                    </div>
                </div>

                <!-- 4 PILAR KARAKTER RESMI (Sesuai Referensi Banner) -->
                <div class="mt-8 w-full max-w-4xl">
                    <p class="text-[10px] sm:text-xs uppercase font-black text-emerald-300 tracking-widest mb-3">
                        4 Pilar Karakter Season II
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
                        <div class="bg-emerald-950/60 border border-emerald-500/30 rounded-xl p-2.5 text-center shadow-xs hover:border-amber-400/60 transition-all">
                            <span class="block text-xs sm:text-sm font-black text-amber-400 tracking-wider">SMART</span>
                            <span class="text-[10px] text-emerald-200/70 font-medium">Cerdas & Kritis</span>
                        </div>
                        <div class="bg-emerald-950/60 border border-emerald-500/30 rounded-xl p-2.5 text-center shadow-xs hover:border-amber-400/60 transition-all">
                            <span class="block text-xs sm:text-sm font-black text-amber-400 tracking-wider">RESILIENT</span>
                            <span class="text-[10px] text-emerald-200/70 font-medium">Tangguh & Disiplin</span>
                        </div>
                        <div class="bg-emerald-950/60 border border-emerald-500/30 rounded-xl p-2.5 text-center shadow-xs hover:border-amber-400/60 transition-all">
                            <span class="block text-xs sm:text-sm font-black text-amber-400 tracking-wider">ECO-DIGITAL</span>
                            <span class="text-[10px] text-emerald-200/70 font-medium">Bijak Teknologi</span>
                        </div>
                        <div class="bg-emerald-950/60 border border-emerald-500/30 rounded-xl p-2.5 text-center shadow-xs hover:border-amber-400/60 transition-all">
                            <span class="block text-xs sm:text-sm font-black text-amber-400 tracking-wider">PIONEERS</span>
                            <span class="text-[10px] text-emerald-200/70 font-medium">Jiwa Pelopor</span>
                        </div>
                    </div>
                </div>

                <!-- ACTION CTA BUTTONS -->
                <div class="mt-8 sm:mt-10 flex flex-wrap gap-3 sm:gap-4 justify-center">
                    <a href="{{ route('informasi-lomba') }}"
                        class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white px-6 sm:px-8 py-3 sm:py-3.5 text-sm sm:text-base font-extrabold rounded-xl shadow-lg shadow-amber-900/40 border-2 border-amber-400/80 flex items-center justify-center gap-2 transform transition hover:-translate-y-0.5">
                        <i data-lucide="book-open" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        Katalog & Juknis Lomba
                    </a>
                    <a href="{{ route('live-display') }}"
                        class="bg-emerald-800/80 hover:bg-emerald-700 text-white px-6 sm:px-8 py-3 sm:py-3.5 text-sm sm:text-base font-extrabold rounded-xl shadow-lg border-2 border-emerald-400/50 flex items-center justify-center gap-2 transform transition hover:-translate-y-0.5">
                        <i data-lucide="monitor" class="w-4 h-4 sm:w-5 sm:h-5 text-amber-400"></i>
                        Live Papan Skor
                    </a>
                    <a href="{{ route('jadwal') }}"
                        class="bg-white/10 hover:bg-white/20 text-white px-5 sm:px-7 py-3 sm:py-3.5 text-sm sm:text-base font-bold rounded-xl backdrop-blur-md border border-white/20 flex items-center justify-center gap-2 transition hover:-translate-y-0.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-amber-300"></i>
                        Rundown Acara
                    </a>
                    <a href="{{ route('verifikasi-sertifikat.index') }}"
                        class="bg-emerald-900/80 hover:bg-emerald-800 text-white px-5 sm:px-7 py-3 sm:py-3.5 text-sm sm:text-base font-bold rounded-xl border border-emerald-400/60 flex items-center justify-center gap-2 transition hover:-translate-y-0.5 shadow-md">
                        <i data-lucide="qr-code" class="w-4 h-4 text-amber-300"></i>
                        Verifikasi Sertifikat
                    </a>
                    @if($showFinancialReport == '1' && $activeReport)
                        <a href="{{ Storage::url($activeReport->file_path) }}" target="_blank"
                            class="bg-blue-900/80 hover:bg-blue-800 text-white px-5 sm:px-7 py-3 sm:py-3.5 text-sm sm:text-base font-bold rounded-xl border border-blue-400/40 flex items-center justify-center gap-2 transition hover:-translate-y-0.5 shadow-md">
                            <i data-lucide="file-check" class="w-4 h-4 text-blue-300"></i>
                            {{ $activeReport->title }}
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- PORTAL TERPADU KONTEN (SEMUA INFORMASI DALAM SATU TEMPAT) -->
    <section class="py-12 sm:py-16 bg-[#F4F1EA]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-10 sm:mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-brand-forest/10 border border-brand-forest/20 text-brand-forest text-xs font-black uppercase tracking-wider mb-2">
                    Pusat Informasi & Navigasi Cepat
                </span>
                <h2 class="text-2xl sm:text-4xl font-black text-brand-forest tracking-tight">
                    Semua Informasi Dalam Satu Portal
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-2 max-w-xl mx-auto">
                    Akses langsung seluruh fitur resmi kompetisi, jadwal kegiatan, hasil penilaian dewan juri, hingga pendaftaran online.
                </p>
            </div>

            <!-- GRID 6 MENU KARTU -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                
                <!-- 1. Katalog Lomba -->
                <a href="{{ route('informasi-lomba') }}"
                    class="group bg-white rounded-3xl p-6 border-2 border-gray-200 shadow-sm hover:shadow-xl hover:border-amber-500 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                            <i data-lucide="award" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-brand-forest group-hover:text-amber-600 transition-colors">
                            Katalog & Juknis Lomba
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2 leading-relaxed">
                            Panduan teknis resmi 5 mata lomba peraih medali, bobot kriteria penilaian juri, serta pedoman giat kepramukaan.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-amber-600">
                        <span>Lihat Petunjuk Teknis</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <!-- 2. Live Score & Panggung -->
                <a href="{{ route('live-display') }}" target="_blank"
                    class="group bg-white rounded-3xl p-6 border-2 border-gray-200 shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-700 flex items-center justify-center mb-4 group-hover:bg-emerald-700 group-hover:text-white transition-all duration-300">
                            <i data-lucide="tv" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-brand-forest group-hover:text-emerald-700 transition-colors">
                            Papan Skor & Layar Proyektor
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2 leading-relaxed">
                            Tampilan klasemen real-time proyektor panggung, animasi buka sensor 3 besar, dan grafik perolehan poin regu.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-emerald-700">
                        <span>Buka Layar Panggung</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <!-- 3. Rundown Acara -->
                <a href="{{ route('jadwal') }}"
                    class="group bg-white rounded-3xl p-6 border-2 border-gray-200 shadow-sm hover:shadow-xl hover:border-blue-600 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <i data-lucide="calendar" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-brand-forest group-hover:text-blue-600 transition-colors">
                            Rundown & Jadwal Kegiatan
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2 leading-relaxed">
                            Susunan acara perkemahan 3 hari 2 malam, waktu registrasi ulang, pelaksanaan lomba, serta malam api unggun.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-blue-600">
                        <span>Jelajahi Jadwal</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <!-- 4. Daftar Regu Partisipan -->
                <a href="{{ route('daftar-regu') }}"
                    class="group bg-white rounded-3xl p-6 border-2 border-gray-200 shadow-sm hover:shadow-xl hover:border-brand-forest transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-brand-forest/10 text-brand-forest flex items-center justify-center mb-4 group-hover:bg-brand-forest group-hover:text-white transition-all duration-300">
                            <i data-lucide="tent" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-brand-forest group-hover:text-emerald-800 transition-colors">
                            Daftar Regu Terdaftar
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2 leading-relaxed">
                            Daftar regu Gudep 03.059 (Putra) dan 03.060 (Putri) yang telah terdaftar resmi beserta pimpinan regu (Pinru).
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-brand-forest">
                        <span>Lihat Semua Regu</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <!-- 5. Sponsorship & Kemitraan -->
                <a href="{{ route('sponsorship.daftar') }}"
                    class="group bg-white rounded-3xl p-6 border-2 border-gray-200 shadow-sm hover:shadow-xl hover:border-purple-600 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                            <i data-lucide="handshake" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-brand-forest group-hover:text-purple-600 transition-colors">
                            Sponsor & Kemitraan
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2 leading-relaxed">
                            Ruang apresiasi donatur, mitra UMKM, dan sponsor pendukung kegiatan Spencerone Camp Season II.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-purple-600">
                        <span>Dukung Kegiatan</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

                <!-- 6. Portal Akses Masuk -->
                <a href="{{ route('login') }}"
                    class="group bg-gradient-to-br from-[#0B2317] to-[#143D2B] rounded-3xl p-6 border-2 border-amber-500/50 shadow-md hover:shadow-xl hover:border-amber-400 transition-all duration-300 flex flex-col justify-between text-white">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-300 flex items-center justify-center mb-4 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                            <i data-lucide="log-in" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-white group-hover:text-amber-300 transition-colors">
                            Portal Akses Juri & Admin
                        </h3>
                        <p class="text-xs sm:text-sm text-emerald-100/80 mt-2 leading-relaxed">
                            Akses terautentikasi dewan juri penilaian, kontrol arena cerdas cermat, serta panel administrasi lomba.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-emerald-700/50 flex items-center justify-between text-xs font-bold text-amber-300">
                        <span>Masuk ke Portal</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- SECTION 5 LOMBA RESMI PREVIEW -->
    <section class="py-12 sm:py-16 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-xs uppercase font-extrabold text-brand-forest tracking-widest flex items-center justify-center gap-2">
                    <span class="w-8 h-0.5 bg-amber-500"></span>
                    5 Lomba Resmi Season II
                    <span class="w-8 h-0.5 bg-amber-500"></span>
                </h2>
                <p class="text-2xl sm:text-3xl font-black text-brand-forest mt-1">Standar Kompetisi Kepanduan Modern</p>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-lg mx-auto">Setiap mata lomba dinilai dengan kriteria terukur dan transparan oleh dewan juri ahli.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($mataLomba as $lomba)
                    @php
                        $iconName = match($lomba->slug) {
                            'tapak-kemah' => 'tent',
                            'lkbb' => 'shield',
                            'cerdas-cermat' => 'brain',
                            'masak-rimba' => 'flame',
                            'hasta-karya' => 'sparkles',
                            default => 'award'
                        };
                    @endphp
                    <div class="bg-gray-50 rounded-2xl p-4 sm:p-5 border border-gray-200 hover:border-amber-500 hover:bg-white hover:shadow-lg transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-brand-forest/10 text-brand-forest flex items-center justify-center mb-3">
                                <i data-lucide="{{ $iconName }}" class="w-6 h-6 text-brand-forest"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-0.5 rounded">
                                {{ $lomba->kode ?? 'LOMBA' }}
                            </span>
                            <h3 class="text-base font-black text-brand-forest mt-2 mb-1">{{ $lomba->nama }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                {{ $lomba->deskripsi }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200/60 flex items-center justify-between text-[11px] font-bold text-gray-600">
                            <span>Maks {{ (int)$lomba->nilai_maksimal }} Poin</span>
                            <a href="{{ route('informasi-lomba') }}" class="text-amber-600 hover:text-amber-700 font-extrabold flex items-center gap-1">
                                Juknis <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION MASKOT RESMI "DWI PANDU DIGITAL" -->
    <section class="py-12 sm:py-16 bg-[#0E2C1D] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-amber-400 to-emerald-400 rounded-full blur-2xl opacity-30"></div>
                        <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Maskot Resmi Dwi Pandu Digital"
                            class="relative max-h-80 sm:max-h-96 object-contain drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]">
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <span class="px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs font-black uppercase tracking-wider mb-3 inline-block">
                        Ikon Resmi Perkemahan
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight mb-4">
                        Dwi Pandu Digital: <span class="text-amber-400">Cakra & Kirana</span>
                    </h2>
                    <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed mb-6 font-medium">
                        Maskot resmi Spencerone Camp Season II melambangkan sinergi harmonis antara nilai-nilai luhur Dasa Darma Pramuka dengan kecakapan era digital. <strong>Cakra</strong> mewakili ketangkasan fisik, kemandirian alam terbuka, dan ketahanan navigasi, sedangkan <strong>Kirana</strong> mencerminkan kecerdasan literasi digital, kecepatan analisis, serta kepemimpinan yang solutif.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-emerald-950/60 border border-emerald-500/30 p-4 rounded-2xl">
                            <div class="flex items-center gap-2 text-amber-400 font-black text-sm mb-1">
                                <i data-lucide="shield" class="w-4 h-4"></i>
                                <span>Tangkas di Alam Bebas</span>
                            </div>
                            <p class="text-xs text-emerald-200/80">Kecakapan pionering, ketahanan fisik, dan cinta kelestarian lingkungan bumi perkemahan.</p>
                        </div>
                        <div class="bg-emerald-950/60 border border-emerald-500/30 p-4 rounded-2xl">
                            <div class="flex items-center gap-2 text-amber-400 font-black text-sm mb-1">
                                <i data-lucide="zap" class="w-4 h-4"></i>
                                <span>Cakap di Era Digital & Adaptif</span>
                            </div>
                            <p class="text-xs text-emerald-200/80">Penguasaan kuis cyber interaktif, pemanfaatan sistem real-time, adaptif terhadap inovasi, dan unggul berkarakter.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION SPONSORSHIP & MITRA RESMI -->
    <section class="py-12 sm:py-16 bg-white border-t border-b border-gray-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-black uppercase tracking-wider mb-3">
                    <i data-lucide="handshake" class="w-3.5 h-3.5 text-amber-600"></i>
                    Kemitraan Resmi
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mb-2">
                    Didukung Oleh Mitra & Sponsor Kami
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 font-medium">
                    Terima kasih atas dukungan penuh para mitra dan sponsor dalam menyukseskan perhelatan akbar Spencerone Camp Season II.
                </p>
            </div>

            @if(isset($sponsorships) && $sponsorships->isNotEmpty())
                <!-- Platinum Sponsors -->
                @if(isset($sponsorships['platinum']) && $sponsorships['platinum']->count() > 0)
                    <div class="mb-10">
                        <div class="flex items-center justify-center gap-2 mb-5">
                            <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent w-16"></div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 text-amber-400 text-xs font-black uppercase tracking-wider shadow-sm">
                                <i data-lucide="crown" class="w-3.5 h-3.5"></i> Platinum Sponsors
                            </span>
                            <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent w-16"></div>
                        </div>
                        <div class="flex flex-wrap justify-center items-center gap-6">
                            @foreach($sponsorships['platinum'] as $sponsor)
                                <div class="bg-gradient-to-b from-white to-gray-50 border-2 border-amber-300/80 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 w-full sm:w-80 flex flex-col items-center justify-center text-center group cursor-pointer"
                                    {{ $sponsor->website_url ? 'onclick=window.open("' . e($sponsor->website_url) . '")' : '' }}>
                                    <div class="h-24 w-full flex items-center justify-center p-2 mb-3">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}" class="max-h-20 max-w-full object-contain filter group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <span class="text-lg font-black text-gray-800">{{ $sponsor->name }}</span>
                                        @endif
                                    </div>
                                    <h4 class="font-extrabold text-sm text-gray-900">{{ $sponsor->name }}</h4>
                                    @if($sponsor->website_url)
                                        <span class="text-[11px] text-amber-600 font-bold mt-1 flex items-center gap-1 hover:underline">
                                            Kunjungi Website <i data-lucide="external-link" class="w-3 h-3"></i>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Gold Sponsors -->
                @if(isset($sponsorships['gold']) && $sponsorships['gold']->count() > 0)
                    <div class="mb-10">
                        <div class="flex items-center justify-center gap-2 mb-5">
                            <div class="h-px bg-gradient-to-r from-transparent via-amber-200 to-transparent w-16"></div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-black uppercase tracking-wider shadow-sm">
                                <i data-lucide="medal" class="w-3.5 h-3.5 text-amber-600"></i> Gold Sponsors
                            </span>
                            <div class="h-px bg-gradient-to-r from-transparent via-amber-200 to-transparent w-16"></div>
                        </div>
                        <div class="flex flex-wrap justify-center items-center gap-5">
                            @foreach($sponsorships['gold'] as $sponsor)
                                <div class="bg-white border border-amber-200/90 rounded-xl p-5 shadow-sm hover:shadow-lg transition-all transform hover:-translate-y-1 w-full sm:w-64 flex flex-col items-center justify-center text-center group cursor-pointer"
                                    {{ $sponsor->website_url ? 'onclick=window.open("' . e($sponsor->website_url) . '")' : '' }}>
                                    <div class="h-16 w-full flex items-center justify-center p-1 mb-2">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}" class="max-h-14 max-w-full object-contain filter group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <span class="text-sm font-bold text-gray-800">{{ $sponsor->name }}</span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-xs text-gray-900">{{ $sponsor->name }}</h4>
                                    @if($sponsor->website_url)
                                        <span class="text-[10px] text-amber-600 font-bold mt-0.5 flex items-center gap-0.5">
                                            Kunjungi <i data-lucide="external-link" class="w-2.5 h-2.5"></i>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Silver Sponsors -->
                @if(isset($sponsorships['silver']) && $sponsorships['silver']->count() > 0)
                    <div class="mb-10">
                        <div class="flex items-center justify-center gap-2 mb-4">
                            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent w-12"></div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-300 text-xs font-black uppercase tracking-wider">
                                <i data-lucide="award" class="w-3.5 h-3.5 text-slate-500"></i> Silver Sponsors
                            </span>
                            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent w-12"></div>
                        </div>
                        <div class="flex flex-wrap justify-center items-center gap-4">
                            @foreach($sponsorships['silver'] as $sponsor)
                                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 w-[45%] sm:w-48 flex flex-col items-center justify-center text-center group cursor-pointer"
                                    {{ $sponsor->website_url ? 'onclick=window.open("' . e($sponsor->website_url) . '")' : '' }}>
                                    <div class="h-12 w-full flex items-center justify-center p-1 mb-1.5">
                                        @if($sponsor->logo)
                                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}" class="max-h-10 max-w-full object-contain filter group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <span class="text-xs font-bold text-gray-700">{{ $sponsor->name }}</span>
                                        @endif
                                    </div>
                                    <h4 class="font-bold text-[11px] text-gray-800">{{ $sponsor->name }}</h4>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <!-- Call to Action Sponsorship Banner -->
            <div class="mt-8 bg-gradient-to-r from-[#0B2317] via-[#0E2C1D] to-[#0B2317] rounded-3xl p-6 sm:p-8 text-white text-center sm:text-left border-2 border-amber-400/40 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden">
                <div class="relative z-10 max-w-xl">
                    <span class="inline-block px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 text-[11px] font-black uppercase tracking-wider mb-2">
                        Peluang Kerjasama Terbuka
                    </span>
                    <h3 class="text-lg sm:text-2xl font-black text-white tracking-tight mb-2">
                        Ingin Menjadi Bagian dari Spencerone Camp II?
                    </h3>
                    <p class="text-xs sm:text-sm text-emerald-100/85 leading-relaxed font-medium">
                        Promosikan brand, produk, atau usaha Anda ke ratusan peserta, pembina, serta keluarga besar Gerakan Pramuka melalui program sponsorship resmi kami.
                    </p>
                </div>
                <div class="relative z-10 shrink-0">
                    <a href="{{ route('sponsorship.daftar') }}"
                        class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-lg shadow-amber-500/30 hover:scale-105 transition-all">
                        <i data-lucide="sparkles" class="w-4 h-4 text-slate-950"></i>
                        <span>Daftar Kemitraan</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    window.pageData = function () {
        const eventDate = new Date("{{ $countdownTarget }}").getTime();

        const countdown = Vue.reactive({
            days: '00',
            hours: '00',
            minutes: '00',
            seconds: '00'
        });

        const updateCountdown = () => {
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance < 0) {
                countdown.days = '00';
                countdown.hours = '00';
                countdown.minutes = '00';
                countdown.seconds = '00';
                return;
            }

            const d = Math.floor(distance / (1000 * 60 * 60 * 24));
            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            countdown.days = String(d).padStart(2, '0');
            countdown.hours = String(h).padStart(2, '0');
            countdown.minutes = String(m).padStart(2, '0');
            countdown.seconds = String(s).padStart(2, '0');
        };

        return {
            countdown,
            onMounted: () => {
                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
        };
    };

</script>
@endpush