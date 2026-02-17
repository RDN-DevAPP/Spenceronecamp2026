<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            scout: {
                                primary: '#5D4037',
                                secondary: '#D7CCC8',
                                accent: '#FFC107',
                                light: '#FFF8E1',
                                surface: '#FFF8E1'
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
            background-color: #FFF8E1;
            color: #3E2723;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%235D4037' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #5D4037;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #FFF8E1;
        }

        ::-webkit-scrollbar-thumb {
            background: #5D4037;
            border-radius: 4px;
            border: 2px solid #FFF8E1;
        }
    </style>
    @stack('styles')
</head>

<body>

    <div id="app" class="min-h-screen flex flex-col">

        <!-- REGUS NAVIGATION BAR -->
        <nav class="bg-scout-primary text-scout-light shadow-lg sticky top-0 z-50 border-b-2 border-scout-secondary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-9 h-9 bg-scout-accent rounded-full flex items-center justify-center text-scout-primary font-bold shadow-sm border-2 border-scout-primary">
                                <i data-lucide="tent" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="font-bold text-xl block leading-none tracking-tight">LT-I 2026</span>
                                <span class="text-xs text-scout-secondary font-semibold">Dashboard Peserta</span>
                            </div>
                        </div>
                        <div class="hidden md:flex space-x-4">
                            <a href="{{ route('peserta.dashboard') }}"
                                class="text-scout-surface hover:text-scout-accent font-medium transition">Dashboard</a>
                            <a href="{{ route('peserta.cerdas-cermat.index') }}"
                                class="text-scout-surface hover:text-scout-accent font-medium transition">Cerdas
                                Cermat</a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        @if(Auth::user()->reguProfile)
                            <div class="hidden md:block text-right">
                                <div class="text-sm font-bold text-scout-accent">{{ Auth::user()->reguProfile->nama_regu }}
                                </div>
                                <div class="text-xs text-scout-secondary">{{ Auth::user()->reguProfile->jenis }} -
                                    {{ Auth::user()->reguProfile->nomor_regu }}
                                </div>
                            </div>
                        @else
                            <div class="hidden md:block text-sm font-bold text-scout-accent">{{ Auth::user()->name }}</div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">
                            @csrf
                            <button type="submit"
                                class="bg-scout-accent text-scout-primary px-4 py-1.5 rounded-full text-sm font-bold hover:bg-yellow-500 transition shadow-md border-2 border-scout-accent hover:border-yellow-500 flex items-center gap-2">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>

                        <!-- Mobile Menu Button -->
                        <button @click="toggleMobileMenu"
                            class="md:hidden text-scout-secondary hover:text-white focus:outline-none">
                            <svg v-if="!isMobileMenuOpen" class="w-8 h-8" fill="none" stroke="currentColor"
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
            <div v-show="isMobileMenuOpen"
                class="md:hidden bg-scout-primary border-t border-scout-secondary shadow-xl absolute top-full left-0 w-full z-50 transition-all duration-300 ease-in-out">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <div
                        class="flex items-center px-3 py-2 text-scout-accent font-bold border-b border-scout-secondary mb-2">
                        @if(Auth::user()->reguProfile)
                            <span>{{ Auth::user()->reguProfile->nama_regu }}</span>
                        @else
                            <span>{{ Auth::user()->name }}</span>
                        @endif
                    </div>

                    <a href="{{ route('peserta.dashboard') }}"
                        class="text-scout-surface block px-3 py-2 rounded-md text-base font-medium hover:bg-scout-secondary hover:text-scout-primary transition">
                        <i data-lucide="home" class="w-4 h-4 inline-block mr-2"></i> Dashboard
                    </a>

                    <a href="{{ route('peserta.cerdas-cermat.index') }}"
                        class="text-scout-surface block px-3 py-2 rounded-md text-base font-medium hover:bg-scout-secondary hover:text-scout-primary transition">
                        <i data-lucide="book-open" class="w-4 h-4 inline-block mr-2"></i> Cerdas Cermat
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
                const isMobileMenuOpen = ref(false);

                const toggleMobileMenu = () => {
                    isMobileMenuOpen.value = !isMobileMenuOpen.value;
                    if (isMobileMenuOpen.value) {
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
                return { isMobileMenuOpen, toggleMobileMenu };
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