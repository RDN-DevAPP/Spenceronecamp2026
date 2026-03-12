<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Regu - LT-I Spencerone Camp 2026</title>
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
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%235D4037' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(212, 165, 116, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 165, 116, 0.5);
        }
    </style>
</head>

<body class="min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="glass-card p-6 md:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="w-20 h-20 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <i data-lucide="user-plus" class="w-10 h-10 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Pendaftaran Regu</h1>
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

            <form action="{{ route('register.regu.submit') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Section 1: Data Regu -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-2 border-b border-white/10 pb-2">
                        <i data-lucide="shield" class="w-5 h-5 text-amber-400"></i>
                        <h2 class="text-xl font-bold text-white">Informasi Regu</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-2 text-sm">HEWAN / BUNGA</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="putra" class="hidden peer" required
                                        onchange="updateNameHint()">
                                    <div
                                        class="input-glass p-3 rounded-xl border border-white/10 text-center text-white/60 peer-checked:bg-amber-500/20 peer-checked:border-amber-500 peer-checked:text-white transition-all">
                                        <i data-lucide="paw-print" class="w-5 h-5 mx-auto mb-1"></i>
                                        Regu Putra
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis" value="putri" class="hidden peer"
                                        onchange="updateNameHint()">
                                    <div
                                        class="input-glass p-3 rounded-xl border border-white/10 text-center text-white/60 peer-checked:bg-pink-500/20 peer-checked:border-pink-500 peer-checked:text-white transition-all">
                                        <i data-lucide="flower" class="w-5 h-5 mx-auto mb-1"></i>
                                        Regu Putri
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="nama_regu" class="block text-white font-semibold mb-2 text-sm">NAMA REGU</label>
                            <input type="text" id="nama_regu" name="nama_regu" required value="{{ old('nama_regu') }}"
                                class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                                placeholder="...">
                            <p id="name-hint" class="text-white/40 text-[10px] mt-1 italic">Pilih kategori regu terlebih
                                dahulu</p>
                        </div>
                    </div>

                    <div id="regu-selection-container" class="hidden">
                        <label for="regu_profile_id" class="block text-white font-semibold mb-2 text-sm">PILIH REGU
                            TERDAFTAR (HASIL PENGACAKAN)</label>
                        <select id="regu_profile_id" name="regu_profile_id" onchange="fetchTeamMembers(this.value)"
                            class="input-glass w-full px-4 py-3 rounded-xl text-white focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                            <option value="">-- Buat Regu Baru --</option>
                            @foreach($availableTeams as $regu)
                                <option value="{{ $regu->id }}" data-jenis="{{ $regu->jenis }}">
                                    {{ $regu->nama_regu }} (Nomor {{ $regu->nomor_regu }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-white/40 text-[10px] mt-1 italic">Jika Anda adalah anggota dari regu hasil
                            pengacakan, silakan pilih nama regu Anda di atas.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="username" class="block text-white font-semibold mb-2 text-sm">USERNAME
                                AKUN</label>
                            <input type="text" id="username" name="username" required value="{{ old('username') }}"
                                class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                                placeholder="Untuk login regu">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-white font-semibold mb-2 text-sm">PASSWORD</label>
                            <input type="password" id="password" name="password" required
                                class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                                placeholder="Min. 8 karakter">
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-white font-semibold mb-2 text-sm">KONFIRMASI PASSWORD</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="input-glass w-full px-4 py-3 rounded-xl text-white placeholder-white/40 focus:bg-white/10"
                                placeholder="Ketik ulang password">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Anggota Regu -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-white/10 pb-2">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="users" class="w-5 h-5 text-amber-400"></i>
                            <h2 class="text-xl font-bold text-white">Anggota Regu</h2>
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="text-white/60 text-xs hidden md:block">JUMLAH ANGGOTA:</label>
                            <select id="jumlah_anggota" name="jumlah_anggota" onchange="generateRows()"
                                class="input-glass px-3 py-1.5 rounded-lg text-white text-sm focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                                <option value="8" selected>8 Orang</option>
                                <option value="7">7 Orang</option>
                                <option value="9">9 Orang</option>
                                <option value="10">10 Orang</option>
                            </select>
                        </div>
                    </div>

                    <div id="member-cards-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Dynamic Cards Here -->
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="btn-gradient w-full py-4 rounded-xl text-white font-bold text-lg shadow-2xl flex items-center justify-center space-x-2">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        <span>DAFTARKAN REGU SEKARANG</span>
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

        function updateNameHint() {
            const gender = document.querySelector('input[name="jenis"]:checked')?.value;
            const hint = document.getElementById('name-hint');
            const input = document.getElementById('nama_regu');
            const selectionContainer = document.getElementById('regu-selection-container');
            const reguSelect = document.getElementById('regu_profile_id');

            if (gender === 'putra') {
                hint.textContent = 'Tips: Regu Putra menggunakan nama Hewan (misal: Harimau, Elang)';
                hint.className = 'text-amber-400 text-[10px] mt-1 italic';
                input.placeholder = 'Contoh: Harimau';
            } else if (gender === 'putri') {
                hint.textContent = 'Tips: Regu Putri menggunakan nama Bunga (misal: Mawar, Melati)';
                hint.className = 'text-pink-400 text-[10px] mt-1 italic';
                input.placeholder = 'Contoh: Mawar';
            }

            // Show selection and filter options
            if (gender) {
                selectionContainer.classList.remove('hidden');
                Array.from(reguSelect.options).forEach(option => {
                    if (option.value === "") {
                        option.hidden = false;
                    } else {
                        option.hidden = option.getAttribute('data-jenis') !== gender;
                    }
                });
                reguSelect.value = ""; // Reset selection when category changes
                resetFormState(); // Reset read-only states
            }
        }

        let isRandomizedMode = false;

        function resetFormState() {
            isRandomizedMode = false;
            const inputNamaRegu = document.getElementById('nama_regu');
            const selectJumlah = document.getElementById('jumlah_anggota');

            inputNamaRegu.readOnly = false;
            selectJumlah.disabled = false;

            const hiddenJumlah = document.getElementById('hidden_jumlah_anggota');
            if (hiddenJumlah) hiddenJumlah.remove();

            generateRows();
        }

        function fetchTeamMembers(id) {
            if (!id) {
                resetFormState();
                return;
            }

            fetch(`/api/randomize-regu/${id}/members`)
                .then(response => response.json())
                .then(data => {
                    isRandomizedMode = true;

                    // Update Regu Name if it's empty or still a placeholder
                    const inputNamaRegu = document.getElementById('nama_regu');
                    const currentValue = inputNamaRegu.value.trim();
                    const isPlaceholder = currentValue.match(/^Regu (Putra|Putri) \d+$/i) || currentValue === "";

                    if (isPlaceholder) {
                        inputNamaRegu.value = data.nama_regu;
                    }
                    inputNamaRegu.readOnly = false; // Allow modification

                    // Update Member Rows
                    const selectJumlah = document.getElementById('jumlah_anggota');
                    selectJumlah.value = data.members.length;
                    selectJumlah.disabled = true;

                    // Add hidden input because disabled select won't be submitted
                    let hiddenJumlah = document.getElementById('hidden_jumlah_anggota');
                    if (!hiddenJumlah) {
                        hiddenJumlah = document.createElement('input');
                        hiddenJumlah.type = 'hidden';
                        hiddenJumlah.id = 'hidden_jumlah_anggota';
                        hiddenJumlah.name = 'jumlah_anggota';
                        selectJumlah.parentNode.appendChild(hiddenJumlah);
                    }
                    hiddenJumlah.value = data.members.length;

                    populateMembers(data.members);
                })
                .catch(error => {
                    console.error('Error fetching members:', error);
                    alert('Gagal mengambil data regu. Silakan coba lagi.');
                });
        }

        function populateMembers(members) {
            const container = document.getElementById('member-cards-container');
            container.innerHTML = '';

            members.forEach((m, i) => {
                const card = document.createElement('div');
                card.className = 'input-glass p-4 rounded-2xl border border-white/10 space-y-4 hover:border-amber-500/30 transition-all';

                let icon = 'user';
                let colorClass = 'text-white/40';
                let labelColor = 'text-white/60';

                if (m.jabatan === 'pinru') {
                    icon = 'crown';
                    colorClass = 'text-amber-400';
                    labelColor = 'text-amber-400 font-bold';
                } else if (m.jabatan === 'wapinru') {
                    icon = 'award';
                    colorClass = 'text-amber-200';
                    labelColor = 'text-amber-200 font-bold';
                }

                card.innerHTML = `
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="${icon}" class="w-4 h-4 ${colorClass}"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest ${labelColor}">
                                Anggota ${i + 1} ${m.jabatan ? `- ${m.jabatan}` : ''}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Nama Lengkap</label>
                                <input type="text" name="anggota[${i}][nama]" required
                                    value="${m.nama}"
                                    ${isRandomizedMode ? 'readonly' : ''}
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-sm focus:bg-white/10 ${isRandomizedMode ? 'opacity-70 cursor-not-allowed' : ''}"
                                    placeholder="Nama Lengkap">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Kelas</label>
                                <select name="anggota[${i}][kelas]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-sm focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                                    <option value="7" ${m.kelas == '7' ? 'selected' : ''}>7</option>
                                    <option value="8" ${m.kelas == '8' ? 'selected' : ''}>8</option>
                                    <option value="9" ${m.kelas == '9' ? 'selected' : ''}>9</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Tingkatan TKU</label>
                                <select name="anggota[${i}][tingkatan_tku]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-xs focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                                    <option value="ramu" ${m.tingkatan_tku === 'ramu' ? 'selected' : ''}>Ramu</option>
                                    <option value="rakit" ${m.tingkatan_tku === 'rakit' ? 'selected' : ''}>Rakit</option>
                                    <option value="terap" ${m.tingkatan_tku === 'terap' ? 'selected' : ''}>Terap</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Jabatan</label>
                                <select name="anggota[${i}][jabatan]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-xs font-bold uppercase focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                                    <option value="pinru" ${m.jabatan === 'pinru' ? 'selected' : ''}>PINRU</option>
                                    <option value="wapinru" ${m.jabatan === 'wapinru' ? 'selected' : ''}>WAPINRU</option>
                                    <option value="anggota" ${m.jabatan === 'anggota' || !m.jabatan ? 'selected' : ''}>ANGGOTA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
            lucide.createIcons();
        }

        function generateRows() {
            const count = document.getElementById('jumlah_anggota').value;
            const container = document.getElementById('member-cards-container');
            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                const card = document.createElement('div');
                card.className = 'input-glass p-4 rounded-2xl border border-white/10 space-y-4 hover:border-amber-500/30 transition-all';

                let jabatan = 'ANGGOTA';
                let jabatanValue = 'anggota';
                let icon = 'user';
                let colorClass = 'text-white/40';
                let labelColor = 'text-white/60';

                if (i === 0) {
                    jabatan = 'PINRU';
                    jabatanValue = 'pinru';
                    icon = 'crown';
                    colorClass = 'text-amber-400';
                    labelColor = 'text-amber-400 font-bold';
                } else if (i === 1) {
                    jabatan = 'WAPINRU';
                    jabatanValue = 'wapinru';
                    icon = 'award';
                    colorClass = 'text-amber-200';
                    labelColor = 'text-amber-200 font-bold';
                }

                card.innerHTML = `
                    <div class="flex items-center justify-between border-b border-white/5 pb-2">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="${icon}" class="w-4 h-4 ${colorClass}"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest ${labelColor}">
                                Anggota ${i + 1} - ${jabatan}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Nama Lengkap</label>
                                <input type="text" name="anggota[${i}][nama]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-sm focus:bg-white/10"
                                    placeholder="Masukkan nama lengkap">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Kelas</label>
                                <select name="anggota[${i}][kelas]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-sm focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Tingkatan TKU</label>
                                <select name="anggota[${i}][tingkatan_tku]" required
                                    class="input-glass w-full px-3 py-2 rounded-xl text-white text-xs focus:bg-white/10 outline-none [&>option]:bg-[#2d1f1a] [&>option]:text-white">
                                    <option value="ramu">Ramu</option>
                                    <option value="rakit">Rakit</option>
                                    <option value="terap">Terap</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-white/40 uppercase mb-1">Jabatan</label>
                                <input type="hidden" name="anggota[${i}][jabatan]" value="${jabatanValue}">
                                <div class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 text-white/60 text-xs font-bold uppercase tracking-wider">
                                    ${jabatan}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            }
            lucide.createIcons();
        }

        // Initialize with default
        generateRows();
    </script>
</body>

</html>