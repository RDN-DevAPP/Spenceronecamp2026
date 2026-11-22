<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Peserta - LT-I 2026')</title>

    <!-- Vue 3 -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Define initTailwind BEFORE loading the script -->
    <script>
        function initTailwind() {
            if (typeof tailwind === 'undefined') {
                console.error('Tailwind CSS failed to load');
                return;
            }
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                forest: '#1B4332',
                                amber: '#D97706',
                                cyber: '#1E3A8A',
                                purple: '#6B21A8',
                                khaki: '#F4EDE4',
                                dark: '#1F2937',
                                light: '#F9FAFB'
                            },
                            scout: {
                                primary: '#1B4332',
                                secondary: '#2D6A4F',
                                accent: '#D97706',
                                cyber: '#1E3A8A',
                                purple: '#6B21A8',
                                light: '#F9FAFB',
                                surface: '#F4EDE4',
                                dark: '#1F2937'
                            }
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        }
    </script>

    <!-- Tailwind CSS with Onload Handler -->
    <script src="https://cdn.tailwindcss.com" onload="initTailwind()"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F4EDE4;
            color: #1F2937;
        }

        h1, h2, h3, h4, h5, h6 {
            color: inherit;
        }

        .card-scout {
            background-color: #FFFFFF;
            border: 1px solid rgba(27, 67, 50, 0.15);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-scout-primary {
            background-color: #1B4332;
            color: #FFFFFF;
        }

        .btn-scout-primary:hover {
            background-color: #2D6A4F;
        }

        .btn-scout-accent {
            background-color: #D97706;
            color: #FFFFFF;
        }

        .btn-scout-accent:hover {
            background-color: #B45309;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#F4EDE4]/60 text-gray-800 antialiased">

    <div id="app" class="min-h-screen flex flex-col">

        <!-- REGUS NAVIGATION BAR -->
        <nav class="bg-brand-forest text-white shadow-lg sticky top-0 z-50 border-b-2 border-brand-amber">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                            <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Season II"
                                class="w-10 h-10 rounded-full object-cover shadow border-2 border-amber-400 group-hover:scale-105 transition-transform">
                            <div>
                                <span class="font-black text-lg block leading-none tracking-tight text-white flex items-center gap-1">
                                    Spencerone Camp <span class="text-amber-400">II</span>
                                </span>
                                <span class="text-[11px] text-amber-200/90 font-bold">Portal Peserta</span>
                            </div>
                        </a>
                        <div class="hidden md:flex items-center space-x-2">
                            <a href="{{ route('peserta.dashboard') }}"
                                class="{{ request()->routeIs('peserta.dashboard') ? 'bg-brand-amber text-white font-black' : 'text-white/95 hover:bg-white/15 hover:text-white font-medium' }} px-3 py-1.5 rounded-lg text-sm transition">
                                Dashboard
                            </a>
                            <a href="{{ route('peserta.hasta-karya') }}"
                                class="{{ request()->routeIs('peserta.hasta-karya') ? 'bg-brand-amber text-white font-black' : 'text-white/95 hover:bg-white/15 hover:text-white font-medium' }} px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1.5">
                                <i data-lucide="hammer" class="w-4 h-4 text-amber-300"></i> Hasta Karya
                            </a>
                            @php
                                $navRegu = Auth::user()->reguProfile;
                                $navCcEligible = $navRegu && $navRegu->isEligibleForCerdasCermat();
                                $navMinAnggota = \App\Models\ReguProfile::getMinAnggotaRequired();
                            @endphp
                            @if($navCcEligible)
                                <a href="{{ route('peserta.cerdas-cermat.index') }}"
                                    class="{{ request()->routeIs('peserta.cerdas-cermat.*') ? 'bg-brand-amber text-white font-black' : 'text-white/95 hover:bg-white/15 hover:text-white font-medium' }} px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1.5">
                                    <i data-lucide="brain" class="w-4 h-4 text-amber-300"></i> Cerdas Cermat
                                </a>
                            @else
                                <span
                                    title="Menu terkunci: daftarkan minimal {{ $navMinAnggota }} anggota regu terlebih dahulu"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium text-white/45 bg-black/15 border border-white/10 cursor-not-allowed flex items-center gap-1.5">
                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-amber-400/60"></i> Cerdas Cermat
                                </span>
                            @endif
                            <a href="{{ route('peserta.administrasi.index') }}"
                                class="{{ request()->routeIs('peserta.administrasi.*') ? 'bg-brand-amber text-white font-black' : 'text-white/95 hover:bg-white/15 hover:text-white font-medium' }} px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1.5">
                                <i data-lucide="book-open" class="w-4 h-4 text-amber-300"></i> Administrasi Regu
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        @if(Auth::user()->reguProfile)
                            <div class="hidden md:block text-right">
                                <div class="text-sm font-black text-amber-300 uppercase tracking-wide">{{ Auth::user()->reguProfile->nama_regu }}</div>
                                <div class="text-xs text-emerald-100 font-semibold">
                                    {{ ucfirst(Auth::user()->reguProfile->jenis) }} • Regu {{ Auth::user()->reguProfile->nomor_regu }}
                                    @if(Auth::user()->reguProfile->nomor_kavling)
                                        • <span class="text-amber-300">{{ Auth::user()->reguProfile->nomor_kavling }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="hidden md:block text-sm font-bold text-amber-400">{{ Auth::user()->name }}</div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">
                            @csrf
                            <button type="submit"
                                class="bg-brand-amber hover:bg-amber-600 text-white px-4 py-1.5 rounded-full text-xs font-bold transition shadow-md flex items-center gap-1.5">
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                <span>Logout</span>
                            </button>
                        </form>

                        <!-- Mobile Menu Button -->
                        <button @click="toggleMobileMenu"
                            class="md:hidden text-white hover:text-amber-300 focus:outline-none">
                            <svg v-if="!mobileMenuOpen" class="w-8 h-8" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                            <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="mobileMenuOpen"
                class="md:hidden bg-scout-primary border-t border-scout-secondary shadow-xl absolute top-full left-0 w-full z-50 transition-all duration-300 ease-in-out">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <div
                        class="flex items-center px-3 py-2 text-scout-accent font-bold border-b border-scout-secondary mb-2">
                        @if(Auth::user()->reguProfile)
                            <span>{{ strtoupper(Auth::user()->reguProfile->nama_regu) }}</span>
                        @else
                            <span>{{ Auth::user()->name }}</span>
                        @endif
                    </div>

                    <a href="{{ route('peserta.dashboard') }}"
                        class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 inline-block mr-2 text-amber-300"></i> Dashboard
                    </a>

                    <a href="{{ route('peserta.hasta-karya') }}"
                        class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition">
                        <i data-lucide="hammer" class="w-4 h-4 inline-block mr-2 text-amber-300"></i> Hasta Karya
                    </a>

                    @if($navCcEligible ?? (Auth::user()->reguProfile && Auth::user()->reguProfile->isEligibleForCerdasCermat()))
                        <a href="{{ route('peserta.cerdas-cermat.index') }}"
                            class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition">
                            <i data-lucide="brain" class="w-4 h-4 inline-block mr-2 text-amber-300"></i> Cerdas Cermat
                        </a>
                    @else
                        <div class="text-white/50 block px-3 py-2 rounded-md text-base font-medium cursor-not-allowed">
                            <i data-lucide="lock" class="w-4 h-4 inline-block mr-2 text-amber-400/60"></i> Cerdas Cermat (Daftarkan Anggota)
                        </div>
                    @endif
                    <a href="{{ route('peserta.administrasi.index') }}"
                        class="text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-white/10 transition">
                        <i data-lucide="book-open" class="w-4 h-4 inline-block mr-2 text-amber-300"></i> Administrasi Regu
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                        @csrf
                        <button type="submit"
                            class="w-full text-left text-red-300 block px-3 py-2 rounded-md text-base font-medium hover:bg-red-800 hover:text-white transition mt-4 border-t border-scout-secondary pt-4">
                            <i data-lucide="log-out" class="w-4 h-4 inline-block mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- FOOTER -->
        @include('partials.footer')

    </div>

    @stack('scripts')

    <script>
        const { createApp, ref, onMounted, nextTick } = Vue;

        createApp({
            setup() {
                const mobileMenuOpen = ref(false);

                const toggleMobileMenu = () => {
                    mobileMenuOpen.value = !mobileMenuOpen.value;
                    if (mobileMenuOpen.value) {
                        nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    }
                };

                onMounted(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
                return { mobileMenuOpen, toggleMobileMenu };
            }
        }).mount('#app');
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#5D4037',
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#5D4037',
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('info') }}",
                confirmButtonColor: '#5D4037',
            });
        </script>
    @endif
</body>

</html>