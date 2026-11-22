<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Regu - Spencerone Camp Season II</title>
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
            background: rgba(15, 45, 31, 0.88);
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

        .alert-success-glass {
            background: rgba(34, 197, 94, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(34, 197, 94, 0.4);
        }
    </style>
</head>

<body class="cyber-grid min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="glass-card rounded-3xl p-6 md:p-10 shadow-2xl">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="relative inline-block mb-3">
                    <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo Season II"
                        class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full object-cover shadow-2xl border-2 border-amber-400 ring-4 ring-emerald-500/30">
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight">Pendaftaran Regu Baru</h1>
                <p class="text-amber-400 text-xs sm:text-sm font-extrabold uppercase tracking-wider mt-1">Spencerone Camp Season II</p>
                <p class="text-emerald-200/70 text-xs mt-0.5">Gudep 03.059 (Putra) & Gudep 03.060 (Putri) SMPN 1 Cerbon</p>
            </div>

            <!-- Messages -->
            @if (session('error'))
                <div class="alert-glass rounded-xl p-3.5 mb-6 text-red-200 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 text-red-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-glass rounded-xl p-3.5 mb-6 text-red-200 text-xs font-semibold">
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

            <form action="{{ route('register.regu.submit') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Section 1: Data Regu -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-2 border-b border-emerald-500/30 pb-2">
                        <i data-lucide="shield" class="w-5 h-5 text-amber-400"></i>
                        <h2 class="text-lg sm:text-xl font-black text-white uppercase tracking-wide">1. Informasi & Akun Regu</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-emerald-100 font-bold mb-2 text-xs uppercase tracking-wider">Kategori Regu (Gudep)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="putra" class="hidden peer" required
                                        onchange="updateNameHint()">
                                    <div
                                        class="input-glass p-3 rounded-xl border border-emerald-500/30 text-center text-emerald-200/70 peer-checked:bg-amber-500/20 peer-checked:border-amber-400 peer-checked:text-amber-300 transition-all font-bold text-xs">
                                        <i data-lucide="paw-print" class="w-5 h-5 mx-auto mb-1"></i>
                                        Regu Putra (03.059)
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="putri" class="hidden peer"
                                        onchange="updateNameHint()">
                                    <div
                                        class="input-glass p-3 rounded-xl border border-emerald-500/30 text-center text-emerald-200/70 peer-checked:bg-pink-500/20 peer-checked:border-pink-400 peer-checked:text-pink-300 transition-all font-bold text-xs">
                                        <i data-lucide="flower" class="w-5 h-5 mx-auto mb-1"></i>
                                        Regu Putri (03.060)
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="nama_regu" class="block text-emerald-100 font-bold mb-2 text-xs uppercase tracking-wider">Nama Regu</label>
                            <input type="text" id="nama_regu" name="nama_regu" required value="{{ old('nama_regu') }}"
                                class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                                placeholder="Pilih kategori regu terlebih dahulu">
                            <p id="name-hint" class="text-emerald-300/50 text-[11px] mt-1.5 italic font-medium">Pilih kategori regu putra atau putri terlebih dahulu</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="username" class="block text-emerald-100 font-bold mb-1.5 text-xs uppercase tracking-wider">Username Akun Regu</label>
                            <input type="text" id="username" name="username" required value="{{ old('username') }}"
                                class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/30 text-sm focus:text-white"
                                placeholder="Untuk login regu (tanpa spasi)">
                        </div>
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
                </div>

                <!-- Section 2: Anggota Regu -->
                <div class="space-y-6 pt-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-emerald-500/30 pb-2 gap-3">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="users" class="w-5 h-5 text-amber-400"></i>
                            <h2 class="text-lg sm:text-xl font-black text-white uppercase tracking-wide">2. Anggota Regu (Per Kelas)</h2>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-emerald-200/80 text-xs font-bold uppercase tracking-wider">Jumlah Anggota:</label>
                            <select id="jumlah_anggota" name="jumlah_anggota" onchange="generateRows()"
                                class="input-glass px-3 py-1.5 rounded-xl text-white text-xs font-bold focus:bg-emerald-950 outline-none [&>option]:bg-[#071910] [&>option]:text-white cursor-pointer border-amber-400/40">
                                <option value="8" selected>8 Orang</option>
                                <option value="7">7 Orang</option>
                                <option value="9">9 Orang</option>
                                <option value="10">10 Orang</option>
                            </select>
                        </div>
                    </div>

                    <div id="member-cards-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Dynamic Member Cards Populated by JS -->
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="btn-gradient w-full py-4 rounded-xl text-white font-extrabold text-base shadow-2xl flex items-center justify-center space-x-2 border border-amber-400/40 cursor-pointer">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Daftarkan Regu Sekarang</span>
                    </button>
                    <p class="text-emerald-300/40 text-center text-xs mt-3 uppercase tracking-widest font-semibold">
                        Spencerone Camp Season II • Pangkalan SMPN 1 Cerbon
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center space-y-2">
            <a href="{{ route('login') }}"
                class="text-amber-400 hover:text-amber-300 transition text-xs sm:text-sm font-bold block">
                Sudah memiliki akun regu? Masuk di sini
            </a>
            <a href="{{ route('home') }}"
                class="text-white/60 hover:text-white transition text-xs inline-flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Beranda Utama
            </a>
        </div>
    </div>

    <script>
        function updateNameHint() {
            const gender = document.querySelector('input[name="jenis"]:checked')?.value;
            const hint = document.getElementById('name-hint');
            const input = document.getElementById('nama_regu');

            if (gender === 'putra') {
                hint.textContent = 'Tips: Regu Putra menggunakan nama Hewan (misal: Harimau, Elang, Rajawali, Garuda)';
                hint.className = 'text-amber-400 text-xs mt-1.5 font-semibold';
                input.placeholder = 'Contoh: Harimau';
            } else if (gender === 'putri') {
                hint.textContent = 'Tips: Regu Putri menggunakan nama Bunga (misal: Mawar, Melati, Dahlia, Teratai)';
                hint.className = 'text-pink-400 text-xs mt-1.5 font-semibold';
                input.placeholder = 'Contoh: Mawar';
            }
        }

        function generateRows() {
            const count = document.getElementById('jumlah_anggota').value;
            const container = document.getElementById('member-cards-container');
            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                const card = document.createElement('div');
                card.className = 'bg-[#071910]/70 p-4 rounded-2xl border border-emerald-500/25 space-y-3 hover:border-amber-400/40 transition-all';

                let jabatan = 'ANGGOTA';
                let jabatanValue = 'anggota';
                let icon = 'user';
                let colorClass = 'text-emerald-300/60';
                let labelColor = 'text-emerald-200/80';

                if (i === 0) {
                    jabatan = 'PINRU (Pemimpin Regu)';
                    jabatanValue = 'pinru';
                    icon = 'crown';
                    colorClass = 'text-amber-400';
                    labelColor = 'text-amber-400 font-extrabold';
                } else if (i === 1) {
                    jabatan = 'WAPINRU (Wakil Pemimpin)';
                    jabatanValue = 'wapinru';
                    icon = 'award';
                    colorClass = 'text-amber-200';
                    labelColor = 'text-amber-200 font-bold';
                }

                card.innerHTML = `
                    <div class="flex items-center justify-between border-b border-emerald-500/20 pb-2">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="${icon}" class="w-4 h-4 ${colorClass}"></i>
                            <span class="text-[11px] font-bold uppercase tracking-wider ${labelColor}">
                                Anggota ${i + 1}: ${jabatan}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200/70 uppercase mb-1">Nama Lengkap</label>
                                <input type="text" name="anggota[${i}][nama]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-xs focus:text-white"
                                    placeholder="Nama siswa">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200/70 uppercase mb-1">NTA (Opsional)</label>
                                <input type="text" name="anggota[${i}][nisn]"
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-xs focus:text-white"
                                    placeholder="Nomor Tanda Anggota">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200/70 uppercase mb-1">Kelas</label>
                                <select name="anggota[${i}][kelas]" required
                                    class="input-glass w-full px-2.5 py-2 rounded-xl text-white text-xs outline-none [&>option]:bg-[#071910] [&>option]:text-white cursor-pointer">
                                    <option value="7">Kelas 7</option>
                                    <option value="8">Kelas 8</option>
                                    <option value="9">Kelas 9</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200/70 uppercase mb-1">TKU</label>
                                <select name="anggota[${i}][tingkatan_tku]" required
                                    class="input-glass w-full px-2.5 py-2 rounded-xl text-white text-xs outline-none [&>option]:bg-[#071910] [&>option]:text-white cursor-pointer">
                                    <option value="ramu">Ramu</option>
                                    <option value="rakit">Rakit</option>
                                    <option value="terap">Terap</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200/70 uppercase mb-1">Jabatan</label>
                                <input type="hidden" name="anggota[${i}][jabatan]" value="${jabatanValue}">
                                <div class="px-2 py-2 rounded-xl bg-emerald-950/60 border border-emerald-500/20 text-emerald-200/90 text-center text-[10px] font-bold uppercase tracking-wider truncate">
                                    ${jabatanValue.toUpperCase()}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

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
            generateRows();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>