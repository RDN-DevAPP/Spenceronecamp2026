<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Juri - LT-I Spencerone Camp 2026</title>
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
            background-color: #FFF8E1;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%235D4037' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .glass-card {
            background: rgba(45, 31, 26, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(212, 165, 116, 0.3);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.3);
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

        .alert-glass {
            background: rgba(239, 68, 68, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .kode-input {
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 1.25rem;
            text-align: center;
        }
    </style>
</head>

<body class="min-h-screen py-10 px-4">
    <div class="max-w-lg mx-auto">
        <div class="glass-card p-6 md:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="w-20 h-20 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <i data-lucide="gavel" class="w-10 h-10 text-amber-400"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Registrasi Juri</h1>
                <p class="text-white/80">LT-I Spencerone Camp 2026</p>
            </div>

            <!-- Messages -->
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
                    <div class="flex items-start">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('register.juri.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Kode Mata Lomba (prominent) -->
                <div>
                    <label class="block text-amber-400 font-bold mb-2 text-sm uppercase tracking-wider">
                        <i data-lucide="key" class="w-4 h-4 inline mr-1"></i>
                        Kode Mata Lomba
                    </label>
                    <input type="text" name="kode_mata_lomba" required value="{{ old('kode_mata_lomba') }}"
                        class="input-glass kode-input w-full px-4 py-4 rounded-xl text-amber-400 placeholder-white/30 focus:bg-white/10"
                        placeholder="••••••" maxlength="6" minlength="6" pattern="[A-Za-z0-9]{6}"
                        oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                    <p class="text-white/40 text-xs mt-2 italic text-center">Masukkan kode 6 karakter (huruf & angka)
                        dari panitia</p>
                </div>

                <hr class="border-white/10">

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-white font-semibold mb-2 text-sm">NAMA LENGKAP</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                        placeholder="Nama lengkap Anda">
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-white font-semibold mb-2 text-sm">USERNAME</label>
                    <input type="text" id="username" name="username" required value="{{ old('username') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                        placeholder="Username untuk login">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-white font-semibold mb-2 text-sm">EMAIL</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                        placeholder="email@contoh.com">
                </div>

                <!-- PASSWORD -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-white font-semibold mb-2 text-sm">SANDI</label>
                        <input type="password" id="password" name="password" required
                            class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                            placeholder="Min. 8 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="block text-white font-semibold mb-2 text-sm">KONFIRMASI SANDI</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                            placeholder="Ketik ulang sandi">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="btn-gradient w-full py-4 rounded-xl text-white font-bold text-lg shadow-2xl flex items-center justify-center space-x-2">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        <span>DAFTAR SEBAGAI JURI</span>
                    </button>
                    <p class="text-white/40 text-center text-xs mt-4 uppercase tracking-widest">LT-I Spencerone Camp
                        2026 • Gerakan Pramuka SMPN 1 Cerbon</p>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}"
                class="text-amber-400 hover:text-amber-300 transition-colors text-sm font-medium">
                Sudah punya akun? Login di sini
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>