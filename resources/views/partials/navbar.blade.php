<nav class="bg-scout-primary text-scout-light shadow-lg sticky top-0 z-50 border-b-2 border-scout-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 cursor-pointer">
                <div
                    class="w-9 h-9 bg-scout-accent rounded-full flex items-center justify-center text-scout-primary font-bold shadow-sm border-2 border-scout-primary">
                    <i data-lucide="tent" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="font-bold text-xl block leading-none tracking-tight">LT-I 2026</span>
                    <span class="text-xs text-scout-secondary font-semibold">Gudep 03.059-03.060</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                        Beranda
                    </a>
                    <a href="{{ route('informasi-lomba') }}"
                        class="{{ request()->routeIs('informasi-lomba') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                        Info Lomba
                    </a>
                    <a href="{{ route('jadwal') }}"
                        class="{{ request()->routeIs('jadwal') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                        Jadwal
                    </a>
                    <a href="{{ route('sponsorship.daftar') }}"
                        class="{{ request()->routeIs('sponsorship.daftar') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                        Daftar Sponsorship
                    </a>
                    <a href="{{ route('daftar-regu') }}"
                        class="{{ request()->routeIs('daftar-regu') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                        Daftar Regu
                    </a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <div class="relative group h-full flex items-center">
                                <button
                                    class="{{ request()->routeIs('admin.dashboard', 'admin.cerdas-cermat.*', 'admin.sponsorships.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200 flex items-center gap-1">
                                    Menu Admin <i data-lucide="chevron-down"
                                        class="w-4 h-4 transition-transform group-hover:rotate-180"></i>
                                </button>

                                <div
                                    class="absolute left-1/2 -translate-x-1/2 top-full w-56 pt-2 hidden group-hover:block z-[60]">
                                    <div
                                        class="bg-white rounded-xl shadow-2xl border border-scout-secondary/30 overflow-hidden transform transition-all duration-300 ring-1 ring-black/5">
                                        <div
                                            class="px-4 py-3 bg-gradient-to-r from-scout-primary to-scout-accent/80 border-b border-scout-secondary/20">
                                            <p class="text-xs font-bold text-white tracking-wider flex items-center gap-2">
                                                <i data-lucide="shield-check" class="w-4 h-4"></i> Akses Admin
                                            </p>
                                        </div>
                                        <div class="p-2 flex flex-col gap-1 bg-white">
                                            <a href="{{ route('admin.dashboard') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="layout-dashboard"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Dashboard Admin
                                            </a>
                                            <a href="{{ route('admin.scores.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.scores.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="clipboard-list"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.scores.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Verifikasi Nilai
                                            </a>
                                            <a href="{{ route('admin.cerdas-cermat.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="brain"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.cerdas-cermat.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Cerdas Cermat
                                            </a>
                                            <a href="{{ route('admin.sponsorships.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.sponsorships.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="handshake"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.sponsorships.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Kelola Sponsorship
                                            </a>
                                            <a href="{{ route('admin.informasi-lomba.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.informasi-lomba.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="info"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.informasi-lomba.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Kelola Info Lomba
                                            </a>
                                            <a href="{{ route('admin.jadwal.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="calendar"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.jadwal.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Kelola Jadwal
                                            </a>
                                            <a href="{{ route('admin.financial-reports.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.financial-reports.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="banknote"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.financial-reports.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Laporan Keuangan
                                            </a>
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <a href="{{ route('admin.users.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="users"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Kelola Pengguna
                                            </a>
                                            <a href="{{ route('admin.siswa.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.siswa.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="book-user"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.siswa.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Daftar Siswa
                                            </a>
                                            <a href="{{ route('admin.randomize-regu.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.randomize-regu.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="shuffle"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.randomize-regu.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Pengacakan Regu
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-scout-accent text-scout-primary px-5 py-2 rounded-full text-sm font-bold hover:bg-yellow-500 transition shadow-md border-2 border-scout-accent hover:border-yellow-500">
                                    Logout
                                </button>
                            </form>

                        @elseif(Auth::user()->isJuri())
                            <a href="{{ route('juri.dashboard') }}"
                                class="{{ request()->routeIs('juri.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                                Dashboard Juri
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-scout-accent text-scout-primary px-5 py-2 rounded-full text-sm font-bold hover:bg-yellow-500 transition shadow-md border-2 border-scout-accent hover:border-yellow-500">
                                    Logout
                                </button>
                            </form>
                        @elseif(Auth::user()->isRegu())
                            <a href="{{ route('peserta.dashboard') }}"
                                class="{{ request()->routeIs('peserta.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                                Dashboard Peserta
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-scout-accent text-scout-primary px-5 py-2 rounded-full text-sm font-bold hover:bg-yellow-500 transition shadow-md border-2 border-scout-accent hover:border-yellow-500">
                            Login
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="toggleMobileMenu"
                    class="inline-flex items-center justify-center p-2 rounded-md text-scout-secondary hover:text-scout-primary hover:bg-scout-secondary focus:outline-none border-2 border-transparent hover:border-scout-primary transition duration-200">
                    <i data-lucide="menu" v-show="!mobileMenuOpen" class="w-6 h-6"></i>
                    <i data-lucide="x" v-show="mobileMenuOpen" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div v-show="mobileMenuOpen" class="md:hidden bg-scout-primary border-b-2 border-scout-secondary shadow-xl">
        <div class="px-3 pt-3 pb-4 space-y-1">
            <!-- Beranda -->
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary/20' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                <i data-lucide="home" class="w-4 h-4"></i> Beranda
            </a>

            <!-- Dropdown: Informasi -->
            <div class="mobile-dropdown">
                <button onclick="toggleMobileDropdown(this)"
                    class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-sm font-semibold text-scout-light hover:bg-scout-secondary/20 transition">
                    <span class="flex items-center gap-3"><i data-lucide="info" class="w-4 h-4"></i> Informasi</span>
                    <i data-lucide="chevron-down"
                        class="w-4 h-4 transition-transform duration-200 dropdown-arrow {{ request()->routeIs('informasi-lomba', 'jadwal') ? 'rotate-180' : '' }}"></i>
                </button>
                <div
                    class="dropdown-content ml-4 mt-1 space-y-0.5 border-l-2 border-scout-secondary/30 pl-3 {{ request()->routeIs('informasi-lomba', 'jadwal') ? '' : 'hidden' }}">
                    <a href="{{ route('informasi-lomba') }}"
                        class="{{ request()->routeIs('informasi-lomba') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/80 hover:text-white' }} block px-3 py-2 rounded-md text-sm transition">
                        Info Lomba
                    </a>
                    <a href="{{ route('jadwal') }}"
                        class="{{ request()->routeIs('jadwal') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/80 hover:text-white' }} block px-3 py-2 rounded-md text-sm transition">
                        Jadwal
                    </a>
                </div>
            </div>

            <!-- Daftar Sponsorship -->
            <a href="{{ route('sponsorship.daftar') }}"
                class="{{ request()->routeIs('sponsorship.daftar') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary/20' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                <i data-lucide="handshake" class="w-4 h-4"></i> Daftar Sponsorship
            </a>

            <!-- Daftar Regu -->
            <a href="{{ route('daftar-regu') }}"
                class="{{ request()->routeIs('daftar-regu') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary/20' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                <i data-lucide="users" class="w-4 h-4"></i> Daftar Regu
            </a>

            @auth
                <div class="border-t border-scout-secondary/30 my-2"></div>

                @if(Auth::user()->isAdmin())
                    <!-- Dropdown: Menu Admin -->
                    <div class="mobile-dropdown">
                        <button onclick="toggleMobileDropdown(this)"
                            class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-sm font-bold text-scout-accent hover:bg-scout-secondary/20 transition">
                            <span class="flex items-center gap-3"><i data-lucide="shield-check" class="w-4 h-4"></i> Menu
                                Admin</span>
                            <i data-lucide="chevron-down"
                                class="w-4 h-4 transition-transform duration-200 dropdown-arrow {{ request()->routeIs('admin.*') ? 'rotate-180' : '' }}"></i>
                        </button>
                        <div
                            class="dropdown-content ml-4 mt-1 space-y-0.5 border-l-2 border-scout-accent/30 pl-3 {{ request()->routeIs('admin.*') ? '' : 'hidden' }}">
                            <a href="{{ route('admin.dashboard') }}"
                                class="{{ request()->routeIs('admin.dashboard') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/80 hover:text-white' }} flex items-center gap-2 px-3 py-2 rounded-md text-sm transition">
                                <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard
                            </a>

                            {{-- Sub-dropdown: Lomba --}}
                            <div class="mobile-dropdown">
                                <button onclick="toggleMobileDropdown(this)"
                                    class="flex items-center justify-between w-full px-3 py-2 rounded-md text-sm font-semibold text-scout-secondary hover:text-white transition">
                                    <span class="flex items-center gap-2"><i data-lucide="trophy" class="w-3.5 h-3.5"></i>
                                        Lomba</span>
                                    <i data-lucide="chevron-down"
                                        class="w-3 h-3 transition-transform duration-200 dropdown-arrow {{ request()->routeIs('admin.scores.*', 'admin.cerdas-cermat.*', 'admin.informasi-lomba.*') ? 'rotate-180' : '' }}"></i>
                                </button>
                                <div
                                    class="dropdown-content ml-3 mt-0.5 space-y-0.5 border-l border-scout-secondary/20 pl-2 {{ request()->routeIs('admin.scores.*', 'admin.cerdas-cermat.*', 'admin.informasi-lomba.*') ? '' : 'hidden' }}">
                                    <a href="{{ route('admin.scores.index') }}"
                                        class="{{ request()->routeIs('admin.scores.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="clipboard-list" class="w-3 h-3"></i> Verifikasi Nilai
                                    </a>
                                    <a href="{{ route('admin.cerdas-cermat.index') }}"
                                        class="{{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="brain" class="w-3 h-3"></i> Cerdas Cermat
                                    </a>
                                    <a href="{{ route('admin.informasi-lomba.index') }}"
                                        class="{{ request()->routeIs('admin.informasi-lomba.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="info" class="w-3 h-3"></i> Kelola Info Lomba
                                    </a>
                                </div>
                            </div>

                            {{-- Sub-dropdown: Data --}}
                            <div class="mobile-dropdown">
                                <button onclick="toggleMobileDropdown(this)"
                                    class="flex items-center justify-between w-full px-3 py-2 rounded-md text-sm font-semibold text-scout-secondary hover:text-white transition">
                                    <span class="flex items-center gap-2"><i data-lucide="database" class="w-3.5 h-3.5"></i>
                                        Data</span>
                                    <i data-lucide="chevron-down"
                                        class="w-3 h-3 transition-transform duration-200 dropdown-arrow {{ request()->routeIs('admin.siswa.*', 'admin.randomize-regu.*') ? 'rotate-180' : '' }}"></i>
                                </button>
                                <div
                                    class="dropdown-content ml-3 mt-0.5 space-y-0.5 border-l border-scout-secondary/20 pl-2 {{ request()->routeIs('admin.siswa.*', 'admin.randomize-regu.*') ? '' : 'hidden' }}">
                                    <a href="{{ route('admin.siswa.index') }}"
                                        class="{{ request()->routeIs('admin.siswa.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="book-user" class="w-3 h-3"></i> Daftar Siswa
                                    </a>
                                    <a href="{{ route('admin.randomize-regu.index') }}"
                                        class="{{ request()->routeIs('admin.randomize-regu.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="shuffle" class="w-3 h-3"></i> Pengacakan Regu
                                    </a>
                                </div>
                            </div>

                            {{-- Sub-dropdown: Kelola --}}
                            <div class="mobile-dropdown">
                                <button onclick="toggleMobileDropdown(this)"
                                    class="flex items-center justify-between w-full px-3 py-2 rounded-md text-sm font-semibold text-scout-secondary hover:text-white transition">
                                    <span class="flex items-center gap-2"><i data-lucide="settings" class="w-3.5 h-3.5"></i>
                                        Kelola</span>
                                    <i data-lucide="chevron-down"
                                        class="w-3 h-3 transition-transform duration-200 dropdown-arrow {{ request()->routeIs('admin.sponsorships.*', 'admin.jadwal.*', 'admin.users.*') ? 'rotate-180' : '' }}"></i>
                                </button>
                                <div
                                    class="dropdown-content ml-3 mt-0.5 space-y-0.5 border-l border-scout-secondary/20 pl-2 {{ request()->routeIs('admin.sponsorships.*', 'admin.jadwal.*', 'admin.users.*') ? '' : 'hidden' }}">
                                    <a href="{{ route('admin.sponsorships.index') }}"
                                        class="{{ request()->routeIs('admin.sponsorships.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="handshake" class="w-3 h-3"></i> Kelola Sponsorship
                                    </a>
                                    <a href="{{ route('admin.jadwal.index') }}"
                                        class="{{ request()->routeIs('admin.jadwal.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="calendar" class="w-3 h-3"></i> Kelola Jadwal
                                    </a>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="{{ request()->routeIs('admin.users.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light/70 hover:text-white' }} flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition">
                                        <i data-lucide="users" class="w-3 h-3"></i> Kelola Pengguna
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(Auth::user()->isJuri())
                    <a href="{{ route('juri.dashboard') }}"
                        class="{{ request()->routeIs('juri.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary/20' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i data-lucide="gavel" class="w-4 h-4"></i> Dashboard Juri
                    </a>

                @elseif(Auth::user()->isRegu())
                    <a href="{{ route('peserta.dashboard') }}"
                        class="{{ request()->routeIs('peserta.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary/20' }} flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i data-lucide="tent" class="w-4 h-4"></i> Dashboard Peserta
                    </a>
                @endif

                <div class="border-t border-scout-secondary/30 my-2"></div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white font-bold flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm w-full text-left transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </button>
                </form>
            @else
                <div class="border-t border-scout-secondary/30 my-2"></div>
                <a href="{{ route('login') }}"
                    class="text-scout-accent hover:bg-scout-secondary hover:text-scout-primary font-bold flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm w-full text-left transition border-2 border-transparent hover:border-scout-accent">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Login
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
            nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
        }
    </script>
@endpush