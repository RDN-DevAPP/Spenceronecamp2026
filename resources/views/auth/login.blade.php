<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Portal - Spencerone Camp Season II</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/season2/logo-season2.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #0B2317;
            background-image: 
                radial-gradient(at 15% 15%, rgba(16, 185, 129, 0.18) 0px, transparent 50%),
                radial-gradient(at 85% 85%, rgba(217, 119, 6, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(5, 46, 27, 0.9) 0px, #04140C 100%);
            color: #F3F4F6;
        }

        .cyber-grid {
            background-image: linear-gradient(to right, rgba(217, 119, 6, 0.08) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(217, 119, 6, 0.08) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .glass-card {
            background: rgba(15, 45, 31, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(52, 211, 153, 0.25);
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.6), 0 0 25px rgba(16, 185, 129, 0.1);
        }

        .input-glass {
            background: rgba(7, 25, 16, 0.7);
            border: 1px solid rgba(52, 211, 153, 0.3);
            transition: all 0.25s ease;
        }

        .input-glass:focus {
            background: rgba(11, 35, 23, 0.95);
            border-color: #F59E0B;
            outline: none;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #D97706 0%, #B45309 50%, #92400E 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 50%, #B45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(217, 119, 6, 0.4);
        }

        .alert-glass {
            background: rgba(239, 68, 68, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .checkbox-custom {
            width: 18px;
            height: 18px;
            border: 1.5px solid rgba(245, 158, 11, 0.6);
            border-radius: 4px;
            background: rgba(7, 25, 16, 0.6);
            accent-color: #D97706;
            cursor: pointer;
        }
    </style>
</head>

<body class="cyber-grid min-h-screen flex items-center justify-center p-4 relative">
    
    <!-- Interactive Cursor Dot Trail Canvas -->
    <canvas id="cursor-dots-canvas" aria-hidden="true" style="position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:99999;"></canvas>

    <!-- Ambient Lighting Element -->
    <div class="absolute w-80 sm:w-96 h-80 sm:h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none -top-10 -left-10"></div>
    <div class="absolute w-80 sm:w-96 h-80 sm:h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -bottom-10 -right-10"></div>

    <!-- Container Kartu Login (Fixed Box Structure) -->
    <div class="relative z-10 w-full max-w-md my-8">
        
        <div class="glass-card rounded-3xl p-6 sm:p-8">
            
            <!-- Logo & Title Header -->
            <div class="text-center mb-6">
                <div class="relative inline-block mb-3">
                    <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Spencerone Camp Season II"
                        class="w-20 h-20 sm:w-22 sm:h-22 mx-auto rounded-full object-cover shadow-2xl border-2 border-amber-400 ring-4 ring-emerald-500/30">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Masuk Portal</h1>
                <p class="text-amber-400 text-xs sm:text-sm font-extrabold uppercase tracking-wider mt-1">Spencerone Camp Season II</p>
                <p class="text-emerald-200/70 text-xs mt-0.5">Gudep 03.059 - 03.060 SMPN 1 Cerbon</p>
            </div>

            <!-- Error Notification Messages -->
            @if (session('error'))
                <div class="alert-glass rounded-xl p-3.5 mb-5 text-red-200 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 text-red-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert-glass rounded-xl p-3.5 mb-5 text-red-200 text-xs font-semibold">
                    <div class="flex items-start gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-400"></i>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form Login -->
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf

                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">
                        <i data-lucide="user" class="w-3.5 h-3.5 inline mr-1 text-amber-400"></i>
                        Username / ID Akses
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                        placeholder="Masukkan username atau ID regu">
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">
                        <i data-lucide="lock" class="w-3.5 h-3.5 inline mr-1 text-amber-400"></i>
                        Kata Sandi / Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="input-glass w-full pl-4 pr-11 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                            placeholder="••••••••">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-emerald-300/70 hover:text-amber-400 transition cursor-pointer focus:outline-none"
                            title="Lihat kata sandi" aria-label="Lihat kata sandi">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember" class="checkbox-custom">
                        <span class="text-xs text-emerald-200/90 font-medium">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="btn-gradient w-full py-3.5 rounded-xl text-white font-extrabold text-sm shadow-xl flex items-center justify-center gap-2 border border-amber-400/40 cursor-pointer mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk ke Portal</span>
                </button>
            </form>

            <!-- Registrasi & Navigasi Links -->
            <div class="mt-6 pt-5 border-t border-emerald-500/20 flex flex-col items-center space-y-2.5">
                <a href="{{ route('register.regu') }}"
                    class="text-amber-400 hover:text-amber-300 text-xs sm:text-sm font-bold transition flex items-center gap-1.5">
                    <i data-lucide="tent" class="w-3.5 h-3.5"></i>
                    Belum Terdaftar? Pendaftaran Regu Baru
                </a>
                <a href="{{ route('register.juri') }}"
                    class="text-emerald-300/80 hover:text-emerald-200 text-xs font-semibold transition flex items-center gap-1.5">
                    <i data-lucide="gavel" class="w-3.5 h-3.5"></i>
                    Registrasi Akun Juri Lomba
                </a>
                <a href="{{ route('home') }}"
                    class="text-white/60 hover:text-white text-xs inline-flex items-center gap-1.5 pt-2 transition">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Kembali ke Beranda Utama
                </a>
            </div>

        </div>

        <p class="text-center text-[11px] text-emerald-300/40 mt-4 font-semibold uppercase tracking-wider">
            Spencerone Camp Season II • Barito Kuala
        </p>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    toggleBtn.innerHTML = isPassword 
                        ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
                        : '<i data-lucide="eye" class="w-4 h-4"></i>';
                        
                    toggleBtn.setAttribute('title', isPassword ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
                    
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            }
        });
    </script>
</body>

</html>