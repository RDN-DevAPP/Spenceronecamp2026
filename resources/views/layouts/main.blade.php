<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LT-I Spencerone Camp 2026 - SMPN 1 Cerbon')</title>

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
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%235D4037' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #5D4037;
        }

        .fade-enter-active,
        .fade-leave-active {
            transition: opacity 0.3s ease;
        }

        .fade-enter-from,
        .fade-leave-to {
            opacity: 0;
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

        .card-scout {
            background-color: #FFF8E1;
            border: 2px solid #D7CCC8;
            box-shadow: 0 4px 6px -1px rgba(93, 64, 55, 0.1), 0 2px 4px -1px rgba(93, 64, 55, 0.06);
        }

        .card-scout:hover {
            border-color: #5D4037;
            box-shadow: 0 10px 15px -3px rgba(93, 64, 55, 0.1), 0 4px 6px -2px rgba(93, 64, 55, 0.05);
        }

        .btn-scout-primary {
            background-color: #5D4037;
            color: #FFF8E1;
        }

        .btn-scout-primary:hover {
            background-color: #4E342E;
            box-shadow: 0 4px 6px -1px rgba(93, 64, 55, 0.2);
        }

        .btn-scout-accent {
            background-color: #FFC107;
            color: #5D4037;
        }

        .btn-scout-accent:hover {
            background-color: #FFB300;
            box-shadow: 0 4px 6px -1px rgba(255, 193, 7, 0.3);
        }
    </style>
    @stack('styles')
</head>

<body>

    <div id="app" class="min-h-screen flex flex-col">

        <!-- NAVIGATION BAR -->
        @include('partials.navbar')

        <!-- MAIN CONTENT AREA -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- FOOTER -->
        @include('partials.footer')

    </div>

    @stack('scripts')

    <script>
        const { createApp, ref, reactive, onMounted, nextTick, watch } = Vue;

        const app = createApp({
            setup() {
                const mobileMenuOpen = ref(false);

                const toggleMobileMenu = () => {
                    mobileMenuOpen.value = !mobileMenuOpen.value;
                    nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                };

                // Expose any page specific data if defined
                const pageData = window.pageData ? window.pageData() : {};

                onMounted(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    if (pageData.onMounted) {
                        pageData.onMounted();
                    }
                });

                watch(mobileMenuOpen, () => {
                    nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });
                });

                return {
                    mobileMenuOpen,
                    toggleMobileMenu,
                    ...pageData
                };
            }
        });

        app.mount('#app');

        // Global Confirmation Dialog for Deletion
        window.confirmDelete = function (formId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5D4037',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        };
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