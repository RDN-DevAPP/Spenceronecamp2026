<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - LT-I Spencerone Camp 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #FFF8E1;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%235D4037' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            height: 100vh;
        }

        .cursor-bubble {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            /* mix-blend-mode: multiply; */
            animation: pop 1s ease-out forwards;
        }

        @keyframes pop {
            0% {
                transform: scale(0.5);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.5) translateY(-20px);
                opacity: 0;
            }
        }

        .glass-card {
            background: rgba(45, 31, 26, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(212, 165, 116, 0.3);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }

        .input-glass {
            background: rgba(30, 20, 15, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 116, 0.3);
            transition: all 0.3s ease;
        }

        .input-glass:focus {
            background: rgba(45, 31, 26, 0.8);
            border-color: rgba(212, 165, 116, 0.8);
            outline: none;
            box-shadow: 0 0 15px rgba(212, 165, 116, 0.2);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #4a3426 0%, #6d4c3d 50%, #8b6f47 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(139, 111, 71, 0.4);
        }

        .logo-container {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .alert-glass {
            background: rgba(239, 68, 68, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.4);
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .checkbox-glass {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(212, 165, 116, 0.5);
            border-radius: 4px;
            background: rgba(30, 20, 15, 0.6);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .checkbox-glass:checked {
            background: linear-gradient(135deg, #6d4c3d 0%, #8b6f47 100%);
            border-color: #8b6f47;
        }

        .checkbox-glass:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 14px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>
    <!-- Cursor Bubbles Container -->
    <div id="bubbles-container"></div>

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="glass-card w-full max-w-sm p-6 md:p-8">
            <!-- Logo & Title -->
            <div class="text-center mb-6 logo-container">
                <div
                    class="w-16 h-16 mx-auto mb-3 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <i data-lucide="tent" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Login</h1>
                <p class="text-white/80 text-xs">LT-I Spencerone Camp 2026</p>
            </div>

            <!-- Error Messages -->
            @if (session('error'))
                <div class="alert-glass rounded-xl p-4 mb-6 text-white text-sm">
                    <div class="flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert-glass rounded-xl p-4 mb-6 text-white text-sm">
                    <div class="flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-2 flex-shrink-0"></i>
                        <div>
                            @foreach ($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf

                <!-- Username -->
                <div>
                    <label for="username" class="block text-white font-semibold mb-1.5 text-xs">
                        <i data-lucide="user" class="w-3.5 h-3.5 inline mr-1"></i>
                        Username
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="input-glass w-full px-3 py-2.5 rounded-lg text-white placeholder-white/50 text-sm focus:bg-white/10"
                        placeholder="Masukkan username">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-white font-semibold mb-1.5 text-xs">
                        <i data-lucide="lock" class="w-3.5 h-3.5 inline mr-1"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="input-glass w-full px-3 py-2.5 rounded-lg text-white placeholder-white/50 text-sm focus:bg-white/10"
                        placeholder="Masukkan password">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="checkbox-glass">
                    <label for="remember" class="ml-2 text-white/90 text-xs cursor-pointer select-none">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="btn-gradient w-full py-2.5 rounded-lg text-white font-bold text-base shadow-lg">
                    <i data-lucide="log-in" class="w-4 h-4 inline mr-1.5"></i>
                    Login
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 flex flex-col items-center space-y-3">
                <a href="{{ route('register.regu') }}"
                    class="text-amber-400 hover:text-amber-300 text-sm font-semibold transition duration-200">
                    Daftar Regu Baru
                </a>
                <a href="{{ route('register.juri') }}"
                    class="text-amber-400/70 hover:text-amber-300 text-sm font-semibold transition duration-200">
                    Daftar Juri
                </a>
                <a href="{{ route('home') }}"
                    class="text-white/70 hover:text-white text-xs inline-flex items-center transition duration-200">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Cursor Bubble Effect
        const container = document.getElementById('bubbles-container');
        const colors = [
            'rgba(139, 111, 71, 0.4)',    // Emas gelap
            'rgba(212, 165, 116, 0.4)',   // Khaki/Tan
            'rgba(109, 76, 61, 0.3)',     // Cokelat medium gelap
            'rgba(74, 52, 38, 0.3)',      // Cokelat tua gelap
        ];

        let bubbles = [];
        const maxBubbles = 15;

        document.addEventListener('mousemove', (e) => {
            // Create new bubble
            const bubble = document.createElement('div');
            bubble.className = 'cursor-bubble';

            const size = Math.random() * 60 + 40; // 40-100px
            const color = colors[Math.floor(Math.random() * colors.length)];

            bubble.style.width = size + 'px';
            bubble.style.height = size + 'px';
            bubble.style.left = (e.clientX - size / 2) + 'px';
            bubble.style.top = (e.clientY - size / 2) + 'px';
            bubble.style.background = `radial-gradient(circle, ${color}, transparent)`;
            bubble.style.opacity = '0';

            container.appendChild(bubble);
            bubbles.push(bubble);

            // Fade in
            setTimeout(() => {
                bubble.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                bubble.style.opacity = '1';
            }, 10);

            // Remove old bubbles
            if (bubbles.length > maxBubbles) {
                const oldBubble = bubbles.shift();
                oldBubble.style.opacity = '0';
                setTimeout(() => {
                    oldBubble.remove();
                }, 300);
            }

            // Auto fade out after 2 seconds
            setTimeout(() => {
                bubble.style.opacity = '0';
                bubble.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    bubble.remove();
                    bubbles = bubbles.filter(b => b !== bubble);
                }, 300);
            }, 2000);
        });

        // Smooth input animations
        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', () => {
                input.style.transform = 'scale(1)';
            });
        });

        // Registration Success Alert
        @if(session('reg_username') && session('reg_password'))
            Swal.fire({
                title: 'Registrasi Berhasil! 🎉',
                html: `
                        <div class="text-left mt-4 p-4 bg-gray-50 rounded-xl border-2 border-dashed border-amber-200">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-3">Simpan Data Akun Anda:</p>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-2.5 bg-white rounded-lg border border-gray-100 shadow-sm">
                                    <span class="text-xs font-bold text-scout-primary">Username:</span>
                                    <span class="text-sm font-mono font-black text-scout-accent">{{ session('reg_username') }}</span>
                                </div>
                                <div class="flex items-center justify-between p-2.5 bg-white rounded-lg border border-gray-100 shadow-sm">
                                    <span class="text-xs font-bold text-scout-primary">Password:</span>
                                    <span class="text-sm font-mono font-black text-scout-accent">{{ session('reg_password') }}</span>
                                </div>
                            </div>
                            <p class="mt-4 text-[10px] text-red-500 font-bold italic text-center italic">
                                *Harap catat atau screenshot halaman ini sebelum ditutup!
                            </p>
                        </div>
                    `,
                icon: 'success',
                confirmButtonText: 'Saya Sudah Mencatatnya',
                confirmButtonColor: '#8B6F47',
                background: '#ffffff',
                customClass: {
                    title: 'text-2xl font-black text-scout-primary',
                    popup: 'rounded-3xl border-4 border-scout-accent/20'
                }
            });
        @endif
    </script>
</body>

</html>