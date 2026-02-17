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

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="{{ request()->routeIs('admin.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                                Penilaian
                            </a>
                            <a href="{{ route('admin.sponsorships.index') }}"
                                class="{{ request()->routeIs('admin.sponsorships.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                                Sponsorship
                            </a>
                            <a href="{{ route('admin.cerdas-cermat.index') }}"
                                class="{{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                                Cerdas Cermat
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                                class="{{ request()->routeIs('admin.users.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} px-4 py-2 rounded-md text-sm font-semibold transition duration-200">
                                Users
                            </a>
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

            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Penilaian
                    </a>
                    <a href="{{ route('admin.sponsorships.index') }}"
                        class="{{ request()->routeIs('admin.sponsorships.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Sponsorship
                    </a>
                    <a href="{{ route('admin.cerdas-cermat.index') }}"
                        class="{{ request()->routeIs('admin.cerdas-cermat.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Cerdas Cermat
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Users
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="text-scout-accent hover:bg-scout-secondary hover:text-scout-primary font-bold block px-4 py-3 rounded-md text-base w-full text-left transition duration-200 border-2 border-transparent hover:border-scout-accent">
                            Logout
                        </button>
                    </form>

                @elseif(Auth::user()->isJuri())
                    <a href="{{ route('juri.dashboard') }}"
                        class="{{ request()->routeIs('juri.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Dashboard Juri
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="text-scout-accent hover:bg-scout-secondary hover:text-scout-primary font-bold block px-4 py-3 rounded-md text-base w-full text-left transition duration-200 border-2 border-transparent hover:border-scout-accent">
                            Logout
                        </button>
                    </form>
                @elseif(Auth::user()->isRegu())
                    <a href="{{ route('peserta.dashboard') }}"
                        class="{{ request()->routeIs('peserta.dashboard') ? 'bg-scout-secondary text-scout-primary' : 'text-scout-light hover:bg-scout-secondary hover:text-scout-primary' }} block px-4 py-3 rounded-md text-base font-semibold w-full text-left transition duration-200">
                        Dashboard Peserta
                    </a>
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