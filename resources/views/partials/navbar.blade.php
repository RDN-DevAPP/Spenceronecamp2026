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
                                            <div class="border-t border-gray-100 my-1"></div>
                                            <a href="{{ route('admin.users.index') }}"
                                                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-scout-primary/10 text-scout-primary font-bold' : 'text-gray-700 hover:bg-scout-secondary/30 hover:text-scout-primary' }}">
                                                <i data-lucide="users"
                                                    class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-scout-primary' : 'text-gray-400' }}"></i>
                                                Kelola Pengguna
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
        <div class="px-2 pt-2 pb-3 space-y-2 sm:px-3">
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                Beranda
            </a>
            <a href="{{ route('informasi-lomba') }}"
                class="{{ request()->routeIs('informasi-lomba') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                Info Lomba
            </a>
            <a href="{{ route('jadwal') }}"
                class="{{ request()->routeIs('jadwal') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                Jadwal
            </a>
            <a href="{{ route('sponsorship.daftar') }}"
                class="{{ request()->routeIs('sponsorship.daftar') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                Daftar Sponsorship
            </a>

            @auth
                @if(Auth::user()->isAdmin())
                    <div class="mt-2 mb-4 pt-4 pb-2 border-y border-scout-primary-light/30">
                        <p class="px-4 text-xs font-bold text-scout-accent uppercase tracking-wider mb-3">Akses Admin</p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}"
                                class="{{ request()->routeIs('admin.dashboard') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200">
                                <i data-lucide="layout-dashboard"
                                    class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Dashboard Admin
                            </a>
                            <a href="{{ route('admin.scores.index') }}"
                                class="{{ request()->routeIs('admin.scores.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200">
                                <i data-lucide="clipboard-list"
                                    class="w-5 h-5 {{ request()->routeIs('admin.scores.*') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Verifikasi Nilai
                            </a>
                            <a href="{{ route('admin.cerdas-cermat.index') }}"
                                class="{{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200">
                                <i data-lucide="brain"
                                    class="w-5 h-5 {{ request()->routeIs('admin.cerdas-cermat.*') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Cerdas Cermat
                            </a>
                            <a href="{{ route('admin.sponsorships.index') }}"
                                class="{{ request()->routeIs('admin.sponsorships.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200">
                                <i data-lucide="handshake"
                                    class="w-5 h-5 {{ request()->routeIs('admin.sponsorships.*') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Kelola Sponsorship
                            </a>
                            <a href="{{ route('admin.informasi-lomba.index') }}"
                                class="{{ request()->routeIs('admin.informasi-lomba.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200">
                                <i data-lucide="info"
                                    class="w-5 h-5 {{ request()->routeIs('admin.informasi-lomba.*') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Kelola Info Lomba
                            </a>
                            <a href="{{ route('admin.jadwal.index') }}"
                                class="{{ request()->routeIs('admin.jadwal.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200">
                                <i data-lucide="calendar"
                                    class="w-5 h-5 {{ request()->routeIs('admin.jadwal.*') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Kelola Jadwal
                            </a>
                            <div class="border-t border-scout-primary-light/10 my-2 mx-4"></div>
                            <a href="{{ route('admin.users.index') }}"
                                class="{{ request()->routeIs('admin.users.*') ? 'bg-scout-secondary text-scout-primary font-bold' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200 mb-2">
                                <i data-lucide="users"
                                    class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-scout-primary' : 'text-scout-secondary' }}"></i>
                                Kelola Pengguna
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
                        @csrf
                        <button type="submit"
                            class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white font-bold flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200 border-2 border-transparent">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                            Logout
                        </button>
                    </form>

                @elseif(Auth::user()->isJuri())
                    <a href="{{ route('juri.dashboard') }}"
                        class="{{ request()->routeIs('juri.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Dashboard Juri
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
                        @csrf
                        <button type="submit"
                            class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white font-bold flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200 border-2 border-transparent">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                            Logout
                        </button>
                    </form>
                @elseif(Auth::user()->isRegu())
                    <a href="{{ route('peserta.dashboard') }}"
                        class="{{ request()->routeIs('peserta.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Dashboard Peserta
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-2">
                        @csrf
                        <button type="submit"
                            class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white font-bold flex items-center gap-3 px-4 py-3 rounded-md text-base w-full text-left transition duration-200 border-2 border-transparent">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                            Logout
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}"
                    class="text-scout-accent hover:bg-scout-secondary hover:text-scout-primary font-bold block px-4 py-3 rounded-md text-base w-full text-left transition duration-200 border-2 border-transparent hover:border-scout-accent">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>