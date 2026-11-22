<nav class="bg-brand-forest text-white shadow-lg sticky top-0 z-50 border-b-2 border-brand-amber">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            {{-- Brand Logo & Title --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-3 cursor-pointer group shrink-0">
                <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Spencerone Camp Season II Logo"
                    class="w-10 h-10 rounded-full object-cover shadow-md border-2 border-amber-400 transform group-hover:scale-105 transition-transform ring-1 ring-amber-300/40">
                <div>
                    <span class="font-black text-lg sm:text-xl block leading-tight tracking-tight text-white flex items-center gap-1.5">
                        Spencerone Camp <span class="text-amber-400">II</span>
                    </span>
                    <span class="text-[11px] text-amber-200/80 font-bold tracking-wide block leading-none">Gudep 03.059 - 03.060 SMPN 1 Cerbon</span>
                </div>
            </a>

            <!-- Desktop Menu (Minimalis & Rapi) -->
            <div class="hidden md:block">
                <div class="ml-4 lg:ml-8 flex items-center space-x-1 lg:space-x-2">
                    
                    {{-- 1. Beranda --}}
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150">
                        Beranda
                    </a>

                    {{-- 2. Dropdown Informasi Kegiatan --}}
                    <div class="relative group h-full flex items-center">
                        <button
                            class="{{ request()->routeIs('informasi-lomba', 'jadwal') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150 flex items-center gap-1.5 cursor-pointer">
                            <span>Informasi</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180 text-amber-300"></i>
                        </button>
                        <div class="absolute left-0 top-full w-48 pt-2 hidden group-hover:block z-[60]">
                            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden p-1.5 ring-1 ring-black/5 text-xs font-semibold">
                                <a href="{{ route('informasi-lomba') }}"
                                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('informasi-lomba') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                    <i data-lucide="trophy" class="w-4 h-4 {{ request()->routeIs('informasi-lomba') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                    <span>Info Lomba</span>
                                </a>
                                <a href="{{ route('jadwal') }}"
                                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('jadwal') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                    <i data-lucide="calendar" class="w-4 h-4 {{ request()->routeIs('jadwal') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                    <span>Jadwal Acara</span>
                                </a>
                                <a href="{{ route('panitia.form') }}"
                                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('panitia.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                    <i data-lucide="id-card" class="w-4 h-4 {{ request()->routeIs('panitia.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                    <span>Formulir Panitia</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Daftar Regu --}}
                    <a href="{{ route('daftar-regu') }}"
                        class="{{ request()->routeIs('daftar-regu') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150">
                        Daftar Regu
                    </a>

                    {{-- 4. Sponsorship --}}
                    <a href="{{ route('sponsorship.daftar') }}"
                        class="{{ request()->routeIs('sponsorship.daftar') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150">
                        Sponsorship
                    </a>

                    {{-- 5. Verifikasi Sertifikat (Single Link, Minimalis Pill) --}}
                    <a href="{{ route('verifikasi-sertifikat.index') }}"
                        class="{{ request()->routeIs('verifikasi-sertifikat.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-amber-300 hover:bg-white/10 hover:text-white font-semibold border border-amber-400/30' }} px-3 py-1.5 rounded-xl text-xs lg:text-sm transition duration-150 flex items-center gap-1.5">
                        <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                        <span>Verifikasi Sertifikat</span>
                    </a>

                    {{-- User Authenticated & Action Section --}}
                    @auth
                        @if(Auth::user()->isAdmin())
                            <div class="relative group h-full flex items-center ml-2">
                                <button
                                    class="{{ request()->routeIs('admin.*') ? 'bg-emerald-900/90 text-amber-300 font-black border border-amber-400/40 shadow-sm' : 'bg-black/20 text-white hover:bg-black/30 font-bold border border-white/10' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150 flex items-center gap-1.5 cursor-pointer">
                                    <i data-lucide="{{ Auth::user()->isSuperAdmin() ? 'shield-check' : 'shield' }}" class="w-4 h-4 text-amber-400"></i>
                                    <span>{{ Auth::user()->isSuperAdmin() ? 'Menu Admin' : 'Admin Terbatas' }}</span>
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180 text-amber-300"></i>
                                </button>

                                <div class="absolute right-0 top-full w-64 pt-2 hidden group-hover:block z-[60]">
                                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 ring-1 ring-black/5">
                                        <div class="px-4 py-3 bg-gradient-to-r from-brand-forest to-emerald-800 border-b border-emerald-900/20 text-white flex items-center justify-between">
                                            <p class="text-xs font-black uppercase tracking-wider flex items-center gap-2">
                                                <i data-lucide="shield" class="w-4 h-4 text-amber-400"></i>
                                                {{ Auth::user()->isSuperAdmin() ? 'Panel Admin' : 'Admin Terbatas' }}
                                            </p>
                                            <span class="text-[10px] font-bold bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded border border-amber-300/30">
                                                {{ Auth::user()->isSuperAdmin() ? 'Utama' : 'Khusus' }}
                                            </span>
                                        </div>
                                        <div class="p-2 flex flex-col gap-0.5 bg-white text-xs font-semibold max-h-[75vh] overflow-y-auto">
                                            @if(Auth::user()->hasPermission('dashboard'))
                                                <a href="{{ route('admin.dashboard') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Dashboard Admin
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('scores'))
                                                <a href="{{ route('admin.scores.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.scores.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="clipboard-check" class="w-4 h-4 {{ request()->routeIs('admin.scores.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Verifikasi Nilai
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('pbb_rubrik'))
                                                <a href="{{ route('admin.pbb-rubrik.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.pbb-rubrik.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="clipboard-list" class="w-4 h-4 {{ request()->routeIs('admin.pbb-rubrik.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Lembar Penilaian PBB
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('rekap_nilai'))
                                                <a href="{{ route('admin.rekap-nilai.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.rekap-nilai.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="file-text" class="w-4 h-4 {{ request()->routeIs('admin.rekap-nilai.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Rekap &amp; Dokumen Resmi
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('id_cards'))
                                                <a href="{{ route('admin.id-cards.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.id-cards.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="id-card" class="w-4 h-4 {{ request()->routeIs('admin.id-cards.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    ID Card (Peserta &amp; Panitia)
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('verifikasi_qr'))
                                                <a href="{{ route('admin.verifikasi-qr.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.verifikasi-qr.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="qr-code" class="w-4 h-4 {{ request()->routeIs('admin.verifikasi-qr.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Verifikasi QR ID Card
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('sertifikat'))
                                                <a href="{{ route('admin.sertifikat.peserta') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.sertifikat.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="award" class="w-4 h-4 {{ request()->routeIs('admin.sertifikat.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Sertifikat (Peserta, Panitia, Juara)
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('informasi_lomba'))
                                                <a href="{{ route('admin.informasi-lomba.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.informasi-lomba.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="trophy" class="w-4 h-4 {{ request()->routeIs('admin.informasi-lomba.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Kelola Mata Lomba
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('jadwal'))
                                                <a href="{{ route('admin.jadwal.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.jadwal.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="calendar" class="w-4 h-4 {{ request()->routeIs('admin.jadwal.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Kelola Jadwal &amp; Countdown
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('cerdas_cermat'))
                                                <a href="{{ route('admin.cerdas-cermat.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="brain" class="w-4 h-4 {{ request()->routeIs('admin.cerdas-cermat.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Cerdas Cermat &amp; Sesi
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('sponsorships'))
                                                <a href="{{ route('admin.sponsorships.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.sponsorships.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="handshake" class="w-4 h-4 {{ request()->routeIs('admin.sponsorships.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Sponsorship
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('financial_reports'))
                                                <a href="{{ route('admin.financial-reports.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.financial-reports.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="banknote" class="w-4 h-4 {{ request()->routeIs('admin.financial-reports.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Laporan Keuangan
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('voting'))
                                                <a href="{{ route('admin.voting.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.voting.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="heart" class="w-4 h-4 {{ request()->routeIs('admin.voting.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Data Voting Favorit
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('siswa'))
                                                <a href="{{ route('admin.siswa.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.siswa.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="book-user" class="w-4 h-4 {{ request()->routeIs('admin.siswa.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Master Data Siswa
                                                </a>
                                            @endif
                                            @if(Auth::user()->isSuperAdmin())
                                                <div class="border-t border-gray-100 my-1"></div>
                                                <a href="{{ route('admin.users.index') }}"
                                                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-brand-forest font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-brand-forest' }}">
                                                    <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-brand-forest' : 'text-gray-400' }}"></i>
                                                    Kelola Pengguna
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                                @csrf
                                <button type="submit"
                                    class="bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md border border-amber-400/50 cursor-pointer flex items-center gap-1.5">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Logout</span>
                                </button>
                            </form>

                        @elseif(Auth::user()->isJuri())
                            <a href="{{ route('juri.dashboard') }}"
                                class="{{ request()->routeIs('juri.dashboard') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150">
                                Dashboard Juri
                            </a>
                            <a href="{{ route('juri.cerdas-cermat.index') }}"
                                class="{{ request()->routeIs('juri.cerdas-cermat.*') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150 flex items-center gap-1.5">
                                <i data-lucide="brain" class="w-4 h-4 text-amber-300"></i>
                                <span>Cerdas Cermat</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                                @csrf
                                <button type="submit"
                                    class="bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md border border-amber-400/50 cursor-pointer flex items-center gap-1.5">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Logout</span>
                                </button>
                            </form>

                        @elseif(Auth::user()->isRegu())
                            @php
                                $topRegu = Auth::user()->reguProfile;
                                $topCcEligible = $topRegu && $topRegu->isEligibleForCerdasCermat();
                            @endphp
                            <a href="{{ route('peserta.dashboard') }}"
                                class="{{ request()->routeIs('peserta.dashboard') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150">
                                Dashboard Peserta
                            </a>
                            @if($topCcEligible)
                                <a href="{{ route('peserta.cerdas-cermat.index') }}"
                                    class="{{ request()->routeIs('peserta.cerdas-cermat.*') ? 'bg-brand-amber text-white font-black shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white font-semibold' }} px-3 py-2 rounded-xl text-xs lg:text-sm transition duration-150 flex items-center gap-1.5">
                                    <i data-lucide="brain" class="w-4 h-4 text-amber-300"></i>
                                    <span>Cerdas Cermat</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                                @csrf
                                <button type="submit"
                                    class="bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md border border-amber-400/50 cursor-pointer flex items-center gap-1.5">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="ml-2 bg-brand-amber hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs lg:text-sm font-extrabold shadow-md border border-amber-400/50 flex items-center gap-1.5 transition-all">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>Masuk Portal</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Toggle Button (High Contrast Gold) -->
            <div class="-mr-2 flex md:hidden">
                <button @click="toggleMobileMenu"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-amber-400 hover:text-white hover:bg-white/10 border border-amber-400/40 focus:outline-none transition duration-200 cursor-pointer shadow-sm">
                    <i data-lucide="menu" v-show="!mobileMenuOpen" class="w-6 h-6"></i>
                    <i data-lucide="x" v-show="mobileMenuOpen" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Drawer (Desain Kontras Tinggi & Bersih) -->
    <div v-show="mobileMenuOpen" class="md:hidden bg-[#0D2418] border-b-2 border-brand-amber shadow-2xl">
        <div class="px-4 pt-3 pb-5 space-y-1.5">
            
            <!-- Beranda -->
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                <i data-lucide="home" class="w-4 h-4 {{ request()->routeIs('home') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                <span>Beranda</span>
            </a>

            <!-- Dropdown Informasi Kegiatan -->
            <div class="mobile-dropdown">
                <button onclick="toggleMobileDropdown(this)"
                    class="flex items-center justify-between w-full px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('informasi-lomba', 'jadwal') ? 'bg-emerald-900/60 text-amber-300 border border-amber-400/40' : 'text-white hover:bg-white/10 hover:text-amber-300' }} transition cursor-pointer">
                    <span class="flex items-center gap-3">
                        <i data-lucide="info" class="w-4 h-4 text-amber-400"></i>
                        <span>Informasi Kegiatan</span>
                    </span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 dropdown-arrow text-amber-400 {{ request()->routeIs('informasi-lomba', 'jadwal') ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="dropdown-content ml-3 mt-1 space-y-1 border-l-2 border-amber-400/40 pl-3 {{ request()->routeIs('informasi-lomba', 'jadwal') ? '' : 'hidden' }}">
                    <a href="{{ route('informasi-lomba') }}"
                        class="{{ request()->routeIs('informasi-lomba') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition">
                        <i data-lucide="trophy" class="w-3.5 h-3.5 {{ request()->routeIs('informasi-lomba') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                        <span>Info Lomba</span>
                    </a>
                    <a href="{{ route('jadwal') }}"
                        class="{{ request()->routeIs('jadwal') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 {{ request()->routeIs('jadwal') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                        <span>Jadwal Kegiatan</span>
                    </a>
                    <a href="{{ route('panitia.form') }}"
                        class="{{ request()->routeIs('panitia.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition">
                        <i data-lucide="id-card" class="w-3.5 h-3.5 {{ request()->routeIs('panitia.*') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                        <span>Formulir Panitia</span>
                    </a>
                </div>
            </div>

            <!-- Daftar Regu -->
            <a href="{{ route('daftar-regu') }}"
                class="{{ request()->routeIs('daftar-regu') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('daftar-regu') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                <span>Daftar Regu</span>
            </a>

            <!-- Sponsorship -->
            <a href="{{ route('sponsorship.daftar') }}"
                class="{{ request()->routeIs('sponsorship.daftar') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                <i data-lucide="handshake" class="w-4 h-4 {{ request()->routeIs('sponsorship.daftar') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                <span>Sponsorship</span>
            </a>

            <!-- Verifikasi Sertifikat (Single Link, Kontras Tinggi) -->
            <a href="{{ route('verifikasi-sertifikat.index') }}"
                class="{{ request()->routeIs('verifikasi-sertifikat.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-amber-300 bg-amber-400/10 border border-amber-400/30 hover:bg-amber-400/20 font-bold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                <i data-lucide="qr-code" class="w-4 h-4 {{ request()->routeIs('verifikasi-sertifikat.*') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                <span>Verifikasi Sertifikat</span>
            </a>

            {{-- Mobile User Authentication Section --}}
            @auth
                <div class="border-t border-white/15 my-3"></div>

                @if(Auth::user()->isAdmin())
                    <!-- Mobile Dropdown: Menu Admin -->
                    <div class="mobile-dropdown">
                        <button onclick="toggleMobileDropdown(this)"
                            class="flex items-center justify-between w-full px-4 py-2.5 rounded-xl text-sm font-black text-amber-300 bg-black/25 border border-amber-400/30 hover:bg-white/10 transition cursor-pointer">
                            <span class="flex items-center gap-2.5">
                                <i data-lucide="{{ Auth::user()->isSuperAdmin() ? 'shield-check' : 'shield' }}" class="w-4 h-4 text-amber-400"></i>
                                <span>{{ Auth::user()->isSuperAdmin() ? 'Menu Panel Admin' : 'Panel Admin Terbatas' }}</span>
                            </span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 dropdown-arrow text-amber-400 {{ request()->routeIs('admin.*') ? 'rotate-180' : '' }}"></i>
                        </button>
                        
                        <div class="dropdown-content mt-2 space-y-3 bg-black/30 rounded-2xl border border-white/10 p-3 {{ request()->routeIs('admin.*') ? '' : 'hidden' }}">
                            
                            {{-- Seksi 1: Administrasi & ID Card --}}
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block px-2 mb-1.5">Administrasi &amp; ID Card</span>
                                <div class="space-y-0.5">
                                    @if(Auth::user()->hasPermission('dashboard'))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="{{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard Admin
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('rekap_nilai'))
                                        <a href="{{ route('admin.rekap-nilai.index') }}"
                                            class="{{ request()->routeIs('admin.rekap-nilai.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Rekap &amp; Dokumen Resmi
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('id_cards'))
                                        <a href="{{ route('admin.id-cards.index') }}"
                                            class="{{ request()->routeIs('admin.id-cards.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="id-card" class="w-3.5 h-3.5"></i> ID Card (Peserta &amp; Panitia)
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('verifikasi_qr'))
                                        <a href="{{ route('admin.verifikasi-qr.index') }}"
                                            class="{{ request()->routeIs('admin.verifikasi-qr.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="qr-code" class="w-3.5 h-3.5"></i> Verifikasi QR ID Card
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('sertifikat'))
                                        <a href="{{ route('admin.sertifikat.peserta') }}"
                                            class="{{ request()->routeIs('admin.sertifikat.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="award" class="w-3.5 h-3.5"></i> Sertifikat (Peserta, Panitia, Juara)
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Seksi 2: Perlombaan --}}
                            <div class="border-t border-white/10 pt-2">
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block px-2 mb-1.5">Perlombaan &amp; Penilaian</span>
                                <div class="space-y-0.5">
                                    @if(Auth::user()->hasPermission('scores'))
                                        <a href="{{ route('admin.scores.index') }}"
                                            class="{{ request()->routeIs('admin.scores.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i> Verifikasi Nilai
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('pbb_rubrik'))
                                        <a href="{{ route('admin.pbb-rubrik.index') }}"
                                            class="{{ request()->routeIs('admin.pbb-rubrik.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="clipboard-list" class="w-3.5 h-3.5"></i> Lembar Penilaian PBB
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('cerdas_cermat'))
                                        <a href="{{ route('admin.cerdas-cermat.index') }}"
                                            class="{{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="brain" class="w-3.5 h-3.5"></i> Cerdas Cermat &amp; Sesi
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('informasi_lomba'))
                                        <a href="{{ route('admin.informasi-lomba.index') }}"
                                            class="{{ request()->routeIs('admin.informasi-lomba.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="trophy" class="w-3.5 h-3.5"></i> Kelola Mata Lomba
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('jadwal'))
                                        <a href="{{ route('admin.jadwal.index') }}"
                                            class="{{ request()->routeIs('admin.jadwal.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Kelola Jadwal
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Seksi 3: Master Data & Kelola --}}
                            <div class="border-t border-white/10 pt-2">
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block px-2 mb-1.5">Master Data &amp; Operasional</span>
                                <div class="space-y-0.5">
                                    @if(Auth::user()->hasPermission('siswa'))
                                        <a href="{{ route('admin.siswa.index') }}"
                                            class="{{ request()->routeIs('admin.siswa.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="book-user" class="w-3.5 h-3.5"></i> Master Data Siswa
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('sponsorships'))
                                        <a href="{{ route('admin.sponsorships.index') }}"
                                            class="{{ request()->routeIs('admin.sponsorships.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="handshake" class="w-3.5 h-3.5"></i> Kelola Sponsorship
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('financial_reports'))
                                        <a href="{{ route('admin.financial-reports.index') }}"
                                            class="{{ request()->routeIs('admin.financial-reports.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="banknote" class="w-3.5 h-3.5"></i> Laporan Keuangan
                                        </a>
                                    @endif
                                    @if(Auth::user()->hasPermission('voting'))
                                        <a href="{{ route('admin.voting.index') }}"
                                            class="{{ request()->routeIs('admin.voting.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="heart" class="w-3.5 h-3.5"></i> Data Voting Favorit
                                        </a>
                                    @endif
                                    @if(Auth::user()->isSuperAdmin())
                                        <a href="{{ route('admin.users.index') }}"
                                            class="{{ request()->routeIs('admin.users.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-sm' : 'text-white/90 hover:text-white hover:bg-white/10 font-medium' }} flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition">
                                            <i data-lucide="users" class="w-3.5 h-3.5"></i> Kelola Pengguna
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                @elseif(Auth::user()->isJuri())
                    <a href="{{ route('juri.dashboard') }}"
                        class="{{ request()->routeIs('juri.dashboard') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                        <i data-lucide="gavel" class="w-4 h-4 {{ request()->routeIs('juri.dashboard') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                        <span>Dashboard Juri</span>
                    </a>
                    <a href="{{ route('juri.cerdas-cermat.index') }}"
                        class="{{ request()->routeIs('juri.cerdas-cermat.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                        <i data-lucide="brain" class="w-4 h-4 {{ request()->routeIs('juri.cerdas-cermat.*') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                        <span>Cerdas Cermat</span>
                    </a>

                @elseif(Auth::user()->isRegu())
                    <a href="{{ route('peserta.dashboard') }}"
                        class="{{ request()->routeIs('peserta.dashboard') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                        <i data-lucide="tent" class="w-4 h-4 {{ request()->routeIs('peserta.dashboard') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                        <span>Dashboard Peserta</span>
                    </a>
                    @if($topCcEligible ?? (Auth::user()->reguProfile && Auth::user()->reguProfile->isEligibleForCerdasCermat()))
                        <a href="{{ route('peserta.cerdas-cermat.index') }}"
                            class="{{ request()->routeIs('peserta.cerdas-cermat.*') ? 'bg-amber-400 text-emerald-950 font-black shadow-md' : 'text-white hover:bg-white/10 hover:text-amber-300 font-semibold' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition">
                            <i data-lucide="brain" class="w-4 h-4 {{ request()->routeIs('peserta.cerdas-cermat.*') ? 'text-emerald-950' : 'text-amber-400' }}"></i>
                            <span>Cerdas Cermat</span>
                        </a>
                    @endif
                @endif

                <div class="border-t border-white/15 my-3"></div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="bg-rose-500/20 text-rose-200 hover:bg-rose-600 hover:text-white border border-rose-500/40 font-bold flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl text-sm w-full transition cursor-pointer shadow-sm">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Keluar Akun (Logout)</span>
                    </button>
                </form>
            @else
                <div class="border-t border-white/15 my-3"></div>
                <a href="{{ route('login') }}"
                    class="bg-amber-400 hover:bg-amber-500 text-emerald-950 font-black flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm w-full transition shadow-md">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk Portal Spencerone</span>
                </a>
            @endauth
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        function toggleMobileDropdown(btn) {
            const parent = btn.closest('.mobile-dropdown');
            const content = parent.querySelector('.dropdown-content');
            const arrow = parent.querySelector('.dropdown-arrow');
            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
            if (typeof Vue !== 'undefined' && Vue.nextTick) {
                Vue.nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
            } else if (typeof nextTick === 'function') {
                nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
            } else {
                setTimeout(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); }, 50);
            }
        }
    </script>
@endpush
