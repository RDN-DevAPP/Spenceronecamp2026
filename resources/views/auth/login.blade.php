<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - LT-I Spencerone Camp 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #1a1410 0%, #2d1f1a 25%, #3d2a1f 50%, #4a3426 75%, #5d4037 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            height: 100vh;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .cursor-bubble {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            mix-blend-mode: screen;
            animation: bubble-float 3s ease-in-out infinite;
        }

        @keyframes bubble-float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.1);
            }
        }

        .glass-card {
            background: rgba(30, 20, 15, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(212, 165, 116, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }

        .input-glass {
            background: rgba(20, 15, 10, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 165, 116, 0.3);
            transition: all 0.3s ease;
        }

        .input-glass:focus {
            background: rgba(30, 20, 15, 0.7);
            border-color: rgba(212, 165, 116, 0.6);
            outline: none;
            box-shadow: 0 0 20px rgba(212, 165, 116, 0.3);
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
            box-shadow: 0 10px 30px rgba(139, 111, 71, 0.5);
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
                transform: translateY(-10px);
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
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .checkbox-glass {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(212, 165, 116, 0.4);
            border-radius: 4px;
            background: rgba(20, 15, 10, 0.5);
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
                    class="w-16 h-16 mx-auto mb-3 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/30">
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
                        class="input-glass w-full px-3 py-2.5 rounded-lg text-white placeholder-white/60 text-sm"
                        placeholder="Masukkan username">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-white font-semibold mb-1.5 text-xs">
                        <i data-lucide="lock" class="w-3.5 h-3.5 inline mr-1"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="input-glass w-full px-3 py-2.5 rounded-lg text-white placeholder-white/60 text-sm"
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
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}"
                    class="text-white/80 hover:text-white text-xs inline-flex items-center transition">
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
            'rgba(74, 52, 38, 0.5)',      // Cokelat tua gelap
            'rgba(109, 76, 61, 0.5)',     // Cokelat medium gelap
            'rgba(139, 111, 71, 0.5)',    // Emas gelap
            'rgba(212, 165, 116, 0.4)',   // Khaki/Tan
            'rgba(93, 64, 55, 0.5)',      // Cokelat
            'rgba(77, 54, 38, 0.5)',      // Cokelat sangat gelap
            'rgba(121, 85, 72, 0.4)',     // Cokelat medium
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
    </script>
</body>

</html>