<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Spencerone Camp Season II - SMPN 1 Cerbon')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/season2/logo-season2.jpg') }}">

    <!-- Vue 3 -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            background-image: radial-gradient(#1B4332 0.75px, transparent 0.75px), radial-gradient(#D97706 0.75px, #F4EDE4 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            background-opacity: 0.05;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #1B4332;
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
            background: #F4EDE4;
        }

        ::-webkit-scrollbar-thumb {
            background: #1B4332;
            border-radius: 4px;
            border: 2px solid #F4EDE4;
        }

        .card-scout {
            background-color: #FFFFFF;
            border: 2px solid #E5DFD5;
            box-shadow: 0 4px 14px -2px rgba(27, 67, 50, 0.08), 0 2px 6px -1px rgba(27, 67, 50, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-scout:hover {
            border-color: #1B4332;
            box-shadow: 0 12px 24px -4px rgba(27, 67, 50, 0.15), 0 4px 10px -2px rgba(27, 67, 50, 0.08);
        }

        .btn-scout-primary {
            background-color: #1B4332;
            color: #F9FAFB;
            transition: all 0.25s ease;
        }

        .btn-scout-primary:hover {
            background-color: #143527;
            box-shadow: 0 6px 16px -2px rgba(27, 67, 50, 0.35);
        }

        .btn-scout-accent {
            background-color: #D97706;
            color: #FFFFFF;
            transition: all 0.25s ease;
        }

        .btn-scout-accent:hover {
            background-color: #B45309;
            box-shadow: 0 6px 16px -2px rgba(217, 119, 6, 0.4);
        }

        .btn-scout-cyber {
            background-color: #1E3A8A;
            color: #FFFFFF;
            transition: all 0.25s ease;
        }

        .btn-scout-cyber:hover {
            background-color: #172554;
            box-shadow: 0 6px 16px -2px rgba(30, 58, 138, 0.4);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Interactive Cursor Dot Trail Canvas -->
    <canvas id="cursor-dots-canvas" aria-hidden="true" style="position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:99999;"></canvas>

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

    <!-- Interactive Cursor Dot Trail Effect (Titik-Titik Mengikuti Arah Kursor) -->
    <script>
        (function () {
            const canvas = document.getElementById('cursor-dots-canvas');
            if (!canvas) return;

            // Only run on devices with mouse/pointer support
            if (window.matchMedia && window.matchMedia('(pointer: coarse)').matches) return;

            const ctx = canvas.getContext('2d');
            let width = 0;
            let height = 0;
            const dpr = Math.min(window.devicePixelRatio || 1, 2);

            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                canvas.width = Math.floor(width * dpr);
                canvas.height = Math.floor(height * dpr);
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            }

            window.addEventListener('resize', resize, { passive: true });
            resize();

            // Spencerone Camp Season II Stardust Dot Palette
            const dotPalette = [
                { r: 217, g: 119, b: 6, glow: '#D97706' },   // Campfire Amber Gold
                { r: 245, g: 158, b: 11, glow: '#F59E0B' },  // Bright Golden Amber
                { r: 45, g: 106, b: 79, glow: '#2D6A4F' },   // Scout Forest Green
                { r: 30, g: 58, b: 138, glow: '#3B82F6' },   // Cyber Tech Blue
                { r: 107, g: 33, b: 168, glow: '#A855F7' },  // Scout Royal Purple
                { r: 254, g: 243, b: 199, glow: '#FDE68A' }  // Warm Khaki Stardust
            ];

            const dots = [];
            let lastX = null;
            let lastY = null;
            let animId = null;

            function addDot(x, y, vx, vy) {
                const color = dotPalette[Math.floor(Math.random() * dotPalette.length)];
                const radius = Math.random() * 2.5 + 2.0; // 2.0px - 4.5px (sleek glowing dots)
                dots.push({
                    x: x,
                    y: y,
                    vx: vx,
                    vy: vy,
                    radius: radius,
                    initialRadius: radius,
                    color: color,
                    alpha: 1.0,
                    decay: Math.random() * 0.025 + 0.02 // Dissolves smoothly over 35-50 frames
                });
            }

            function updateAndRender() {
                ctx.clearRect(0, 0, width, height);

                for (let i = dots.length - 1; i >= 0; i--) {
                    const dot = dots[i];
                    dot.x += dot.vx;
                    dot.y += dot.vy;
                    dot.vx *= 0.93; // Fluid friction
                    dot.vy *= 0.93;
                    dot.alpha -= dot.decay;
                    dot.radius = Math.max(0, dot.initialRadius * dot.alpha);

                    if (dot.alpha <= 0.02 || dot.radius <= 0.2) {
                        dots.splice(i, 1);
                        continue;
                    }

                    ctx.save();
                    ctx.beginPath();
                    ctx.arc(dot.x, dot.y, dot.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${dot.color.r}, ${dot.color.g}, ${dot.color.b}, ${dot.alpha})`;
                    ctx.shadowColor = dot.color.glow;
                    ctx.shadowBlur = 8;
                    ctx.fill();
                    ctx.restore();
                }

                if (dots.length > 0) {
                    animId = requestAnimationFrame(updateAndRender);
                } else {
                    animId = null;
                }
            }

            document.addEventListener('mousemove', function (e) {
                const curX = e.clientX;
                const curY = e.clientY;

                if (lastX !== null && lastY !== null) {
                    const dx = curX - lastX;
                    const dy = curY - lastY;
                    const dist = Math.hypot(dx, dy);

                    if (dist > 2) {
                        // Interpolate points along the cursor path to produce an unbroken trail of dots
                        const steps = Math.min(Math.floor(dist / 5) + 1, 8);
                        for (let s = 1; s <= steps; s++) {
                            const t = s / steps;
                            const interpX = lastX + dx * t;
                            const interpY = lastY + dy * t;

                            // Velocity follows the cursor's movement direction with gentle momentum
                            const flowVx = (dx * 0.09) + (Math.random() - 0.5) * 0.8;
                            const flowVy = (dy * 0.09) + (Math.random() - 0.5) * 0.8;

                            addDot(interpX, interpY, flowVx, flowVy);
                        }
                    }
                } else {
                    addDot(curX, curY, 0, 0);
                }

                lastX = curX;
                lastY = curY;

                if (!animId) {
                    animId = requestAnimationFrame(updateAndRender);
                }
            }, { passive: true });

            document.addEventListener('mouseleave', function () {
                lastX = null;
                lastY = null;
            });
        })();
    </script>
</body>

</html>