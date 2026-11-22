<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Juri - Spencerone Camp Season II</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/season2/logo-season2.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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

        .kode-input {
            letter-spacing: 0.35em;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 1.35rem;
            text-align: center;
        }
    </style>
</head>

<body class="cyber-grid min-h-screen py-10 px-4 flex items-center justify-center">
    <div class="max-w-lg w-full mx-auto">
        
        <div class="glass-card rounded-3xl p-6 sm:p-10">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="relative inline-block mb-3">
                    <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Season II"
                        class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full object-cover shadow-2xl border-2 border-amber-400 ring-4 ring-emerald-500/30">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Registrasi Juri</h1>
                <p class="text-amber-400 text-xs sm:text-sm font-extrabold uppercase tracking-wider mt-1">Dewan Juri Spencerone Camp Season II</p>
                <p class="text-emerald-200/70 text-xs mt-0.5">Pangkalan SMP Negeri 1 Cerbon</p>
            </div>

            <!-- Messages -->
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
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('register.juri.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Kode Mata Lomba (prominent) -->
                <div class="bg-emerald-950/60 p-4 rounded-2xl border border-amber-400/40">
                    <label class="block text-amber-400 font-extrabold mb-2 text-xs uppercase tracking-wider text-center">
                        <i data-lucide="key" class="w-3.5 h-3.5 inline mr-1"></i>
                        Kode Otorisasi Mata Lomba
                    </label>
                    <input type="text" name="kode_mata_lomba" required value="{{ old('kode_mata_lomba') }}"
                        class="input-glass kode-input w-full px-4 py-3.5 rounded-xl text-amber-300 placeholder-white/20 focus:text-amber-300 font-mono"
                        placeholder="••••••" maxlength="6" minlength="6" pattern="[A-Za-z0-9]{6}"
                        oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                    <p class="text-emerald-200/60 text-[11px] mt-2 italic text-center font-medium">
                        Masukkan kode 6 karakter resmi yang diberikan oleh Panitia Lomba
                    </p>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">Nama Lengkap & Gelar</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                        placeholder="Contoh: Kak Ahmad Fauzi, S.Pd">
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">Username Akun</label>
                    <input type="text" id="username" name="username" required value="{{ old('username') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                        placeholder="Untuk login juri (huruf kecil, tanpa spasi)">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">Alamat Email</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                        placeholder="email@contoh.com">
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="input-glass w-full pl-4 pr-11 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                                placeholder="Min. 8 karakter">
                            <button type="button" onclick="togglePassVisibility('password', this)"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-emerald-300/70 hover:text-amber-400 transition cursor-pointer focus:outline-none"
                                title="Lihat kata sandi">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="input-glass w-full pl-4 pr-11 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                                placeholder="Ulangi password">
                            <button type="button" onclick="togglePassVisibility('password_confirmation', this)"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-emerald-300/70 hover:text-amber-400 transition cursor-pointer focus:outline-none"
                                title="Lihat konfirmasi kata sandi">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit"
                        class="btn-gradient w-full py-4 rounded-xl text-white font-extrabold text-sm sm:text-base shadow-xl flex items-center justify-center gap-2 border border-amber-400/40 cursor-pointer">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Daftar Sebagai Dewan Juri</span>
                    </button>
                    <p class="text-emerald-300/50 text-center text-[11px] mt-4 uppercase tracking-widest font-semibold">
                        Gudep 03.059 - 03.060 SMPN 1 Cerbon
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center space-y-2">
            <a href="{{ route('login') }}"
                class="text-amber-400 hover:text-amber-300 transition text-xs sm:text-sm font-bold block">
                Sudah memiliki akun? Masuk di sini
            </a>
            <a href="{{ route('home') }}"
                class="text-white/60 hover:text-white transition text-xs inline-flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function togglePassVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            btn.innerHTML = isPassword 
                ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
                : '<i data-lucide="eye" class="w-4 h-4"></i>';
            btn.setAttribute('title', isPassword ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>