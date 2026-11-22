<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PANGGUNG UTAMA & LIVE SCOREBOARD - Spencerone Camp Season II</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/season2/logo-season2.jpg') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with Onload Handler -->
    <script>
        function initTailwind() {
            if (typeof tailwind === 'undefined') return;
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            forest: '#1B4332',
                            emeraldDark: '#0B2317',
                            campfire: '#D97706',
                            goldLight: '#FBBF24',
                            cyberBlue: '#1E3A8A',
                            cyberCyan: '#06B6D4',
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            mono: ['JetBrains Mono', 'monospace'],
                        }
                    }
                }
            };
        }
    </script>
    <script src="https://cdn.tailwindcss.com" onload="initTailwind()"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    @vite(['resources/js/cyber-quiz.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0B2317;
            background-image:
                radial-gradient(circle at 15% 15%, rgba(27, 67, 50, 0.8) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(217, 119, 6, 0.25) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(30, 58, 138, 0.2) 0%, transparent 60%);
            background-attachment: fixed;
            background-size: cover;
            color: #F9FAFB;
            min-height: 100vh;
        }

        /* ============ DESKTOP: 16:9 Projector Layout ============ */
        @media (min-width: 1024px) {
            body {
                overflow: hidden;
                user-select: none;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem;
                height: 100vh;
            }
            .screen-ratio-16-9 {
                aspect-ratio: 16 / 9;
                max-height: 100vh;
                max-width: calc(100vh * (16 / 9));
                width: 100%;
            }
            .mobile-only { display: none !important; }
            .desktop-only { display: flex !important; }
        }

        /* ============ MOBILE: Linear Scroll Layout ============ */
        @media (max-width: 1023px) {
            body {
                overflow-y: auto;
                padding: 0;
                display: block;
            }
            .screen-ratio-16-9 {
                width: 100%;
                min-height: 100vh;
                border-radius: 0 !important;
            }
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
            .tab-nav-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            .tab-nav-scroll::-webkit-scrollbar { display: none; }
        }

        .gold-glow { text-shadow: 0 0 25px rgba(251, 191, 36, 0.6); }
        .cyber-glow { text-shadow: 0 0 25px rgba(6, 182, 212, 0.6); }
        .reveal-box-shuffling { letter-spacing: 0.15em; font-family: 'JetBrains Mono', monospace; }

        @keyframes pulseBorder {
            0%, 100% { border-color: rgba(217, 119, 6, 0.8); box-shadow: 0 0 20px rgba(217, 119, 6, 0.4); }
            50% { border-color: rgba(251, 191, 36, 1); box-shadow: 0 0 35px rgba(251, 191, 36, 0.8); }
        }
        .pulsing-champion { animation: pulseBorder 2s infinite ease-in-out; }

        /* Phone verification modal & Share Card modal */
        #phone-modal, #share-card-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.88);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        #phone-modal.open, #share-card-modal.open { display: flex; }

        /* Mobile section cards */
        .mobile-section {
            border-radius: 1rem;
            border: 1px solid rgba(16,185,129,0.3);
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(12px);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .mobile-section-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(16,185,129,0.2);
            font-weight: 800;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Toast */
        .live-toast {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9998;
            padding: 0.75rem 1.25rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 700;
            max-width: 90vw;
            text-align: center;
            animation: toastIn 0.3s ease;
        }
        @keyframes toastIn { from { opacity:0; bottom:0.5rem; } to { opacity:1; bottom:1.5rem; } }
    </style>
</head>

<body>

    <!-- =====================================================================
         VOTER DUAL-MODE MODAL (VOTE GRATIS & VOTE BERBAYAR)
    ===================================================================== -->
    <div id="phone-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950 rounded-3xl border-2 border-emerald-600/60 shadow-2xl p-5 sm:p-6 w-full max-w-lg mx-auto max-h-[92vh] overflow-y-auto">
            
            {{-- Modal Top Close & Icon --}}
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-400/50 flex items-center justify-center shrink-0">
                        <i data-lucide="heart" class="w-5 h-5 text-amber-400 fill-amber-400"></i>
                    </div>
                    <div>
                        <h2 id="modal-title" class="text-base sm:text-lg font-black text-white">Voting Regu Terfavorit</h2>
                        <p class="text-xs text-amber-300 font-bold" id="modal-regu-name">-</p>
                    </div>
                </div>
                <button onclick="closePhoneModal()" type="button" class="p-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Tab Selection between Free and Paid --}}
            @if($paid_voting_enabled ?? true)
                <div class="grid grid-cols-2 p-1 bg-black/50 rounded-2xl border border-emerald-700/40 mb-4 gap-1">
                    <button type="button" id="tab-btn-modal-free" onclick="switchVoteModalTab('free')"
                            class="py-2 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center justify-center gap-1.5 bg-amber-500 text-emerald-950 shadow">
                        <i data-lucide="gift" class="w-3.5 h-3.5"></i>
                        <span>Vote Gratis</span>
                    </button>
                    <button type="button" id="tab-btn-modal-paid" onclick="switchVoteModalTab('paid')"
                            class="py-2 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-emerald-300 hover:text-white">
                        <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                        <span>Vote DANA</span>
                    </button>
                </div>
            @endif

            {{-- TAB CONTENT: FREE VOTE --}}
            <div id="modal-tab-free" class="space-y-4">
                <div class="bg-black/30 rounded-2xl p-3.5 border border-emerald-700/30 text-xs text-gray-300">
                    <div class="flex items-center gap-2 text-emerald-300 font-bold mb-1">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        <span>Ketentuan Vote Gratis:</span>
                    </div>
                    <p class="text-[11px] text-gray-400 leading-relaxed">
                        @if(($free_vote_reset_mode ?? 'daily') === 'daily')
                            1 perangkat mendapatkan <strong>1 suara gratis setiap hari</strong> (direset otomatis pukul 00:00 WITA).
                        @elseif(($free_vote_reset_mode ?? 'daily') === 'interval')
                            1 perangkat mendapatkan <strong>1 suara gratis setiap {{ $free_vote_reset_interval_hours ?? 24 }} jam</strong> setelah pemungutan suara.
                        @else
                            1 perangkat hanya dapat memberikan 1 suara gratis selama kegiatan berlangsung.
                        @endif
                    </p>
                </div>

                {{-- If user already voted free in current cycle --}}
                <div id="free-voted-notice" class="{{ ($hasVoted ?? false) ? '' : 'hidden' }} p-4 rounded-2xl bg-amber-950/60 border border-amber-500/50 text-center">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-sm font-extrabold text-amber-300">Hak Suara Gratis Sudah Digunakan</h4>
                    <p class="text-xs text-gray-300 mt-1 leading-relaxed">
                        Anda telah menggunakan kuota suara gratis untuk periode saat ini.
                    </p>
                    <div class="mt-3 p-3 rounded-xl bg-black/40 border border-amber-500/30">
                        <div id="free-vote-timer-wrap-modal" class="{{ ($free_vote_reset_mode ?? 'daily') === 'once' ? 'hidden' : '' }}">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-amber-300/90 flex items-center justify-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span>Reset Kuota Gratis Berikutnya</span>
                            </div>
                            <div id="free-vote-countdown-modal" class="text-2xl font-mono font-black text-amber-300 tracking-wider mt-1">--:--:--</div>
                            <div id="free-vote-reset-desc-modal" class="text-[10px] text-gray-400 mt-0.5">
                                @if(($free_vote_reset_mode ?? 'daily') === 'daily')
                                    Otomatis terbuka kembali pukul 00:00 WITA
                                @elseif(($free_vote_reset_mode ?? 'daily') === 'interval')
                                    Reset otomatis setiap {{ $free_vote_reset_interval_hours ?? 24 }} jam
                                @endif
                            </div>
                        </div>
                        <div id="free-vote-once-note-modal" class="{{ ($free_vote_reset_mode ?? 'daily') === 'once' ? '' : 'hidden' }} text-[11px] font-bold text-amber-200">
                            Kuota suara gratis hanya berlaku 1x selama kegiatan berlangsung.
                        </div>
                    </div>
                    @if($paid_voting_enabled ?? true)
                        <div class="mt-3">
                            <button type="button" onclick="switchVoteModalTab('paid')"
                                    class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-emerald-950 font-black text-xs uppercase tracking-wider hover:from-amber-400 hover:to-amber-500 transition shadow flex items-center justify-center gap-2">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                                <span>Beralih ke Vote DANA (Dukungan Ekstra)</span>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Free Vote Form (shown when eligible) --}}
                <div id="free-vote-form" class="{{ ($hasVoted ?? false) ? 'hidden' : '' }} space-y-4">
                    <div>
                        <label for="phone-input" class="block text-xs font-bold text-emerald-300 mb-1.5">Nama Kamu</label>
                        <div class="flex items-center gap-2 bg-black/50 rounded-xl border-2 border-emerald-700/50 focus-within:border-amber-400/80 transition overflow-hidden">
                            <span class="pl-3 text-sm font-bold text-gray-400 shrink-0">✍️</span>
                            <input
                                id="phone-input"
                                type="text"
                                inputmode="text"
                                placeholder="Tulis namamu di sini..."
                                autocomplete="name"
                                maxlength="100"
                                class="flex-1 bg-transparent py-3 pr-3 text-sm font-semibold text-white placeholder-gray-600 outline-none"
                            >
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1.5">Contoh: Budi Santoso atau nama panggilanmu.</p>
                        <p id="phone-error" class="text-[11px] text-red-400 mt-1.5 hidden font-semibold bg-red-900/30 px-2 py-1 rounded-lg border border-red-700/40"></p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closePhoneModal()" class="flex-1 py-3 rounded-xl bg-gray-800 text-gray-300 font-bold text-sm hover:bg-gray-700 transition active:scale-95">
                            Batal
                        </button>
                        <button type="button" onclick="confirmVote()" id="confirm-vote-btn" class="flex-[2] py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-emerald-950 font-black text-sm hover:from-amber-400 hover:to-amber-500 transition flex items-center justify-center gap-2 active:scale-95 shadow-lg">
                            <i data-lucide="heart" class="w-4 h-4 fill-emerald-950"></i>
                            <span>Kirim 1 Suara Gratis</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- TAB CONTENT: PAID VOTE (KHUSUS DANA QRIS, MIN RP 1, OTOMATIS TERVERIFIKASI) --}}
            @if($paid_voting_enabled ?? true)
                <div id="modal-tab-paid" class="space-y-4 hidden">
                    
                    @if(!($dana_configured ?? false))
                        <div class="p-4 rounded-2xl bg-red-950/60 border border-red-500/50 text-center space-y-2">
                            <i data-lucide="alert-triangle" class="w-8 h-8 text-amber-400 mx-auto"></i>
                            <h4 class="text-sm font-black text-white">QRIS DANA Belum Diatur</h4>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Admin belum mengunggah gambar atau kode <strong>QRIS DANA</strong> pada panel pengaturan voting.
                            </p>
                        </div>
                    @else
                        {{-- STEP 1: FORM INPUT NAMA & JUMLAH SUARA --}}
                        <div id="paid-step-form" class="space-y-4">
                            {{-- Notice DANA only & verifikasi nyata --}}
                            <div class="p-3 bg-gradient-to-r from-sky-500/20 via-emerald-500/20 to-sky-500/20 rounded-2xl border border-sky-400/40 flex items-start gap-2.5">
                                <i data-lucide="shield-check" class="w-5 h-5 text-sky-400 shrink-0 mt-0.5"></i>
                                <div class="text-[11px] text-gray-200 leading-relaxed">
                                    <strong class="text-sky-300 font-black">Pembayaran Khusus DANA (Mulai Rp {{ number_format($paid_vote_price ?? 1, 0, ',', '.') }}/vote):</strong>
                                    Scan QRIS menggunakan aplikasi <strong>DANA</strong>. Suara otomatis masuk setelah dana berhasil diterima sistem DANA!
                                </div>
                            </div>

                            <div class="space-y-3.5">
                                {{-- Nama Pemilih --}}
                                <div>
                                    <label for="paid-voter-name" class="block text-xs font-bold text-emerald-300 mb-1">Nama Pemilih / Pendukung</label>
                                    <input id="paid-voter-name" type="text" placeholder="Nama Anda / Alumni / Simpatisan"
                                           class="w-full bg-black/50 border border-emerald-700/50 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-white placeholder-gray-600 focus:border-sky-400 outline-none">
                                </div>

                                {{-- Jumlah Suara --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label for="paid-votes-count" class="text-xs font-bold text-emerald-300">Jumlah Suara Dukungan</label>
                                        <span class="text-[11px] text-sky-300 font-mono font-bold">@ Rp {{ number_format($paid_vote_price ?? 1, 0, ',', '.') }}/suara</span>
                                    </div>

                                    {{-- Quick Chips --}}
                                    <div class="flex items-center gap-1.5 mb-2 flex-wrap">
                                        <button type="button" onclick="setPaidVotes(1)" class="chip-vote px-2.5 py-1 rounded-lg text-xs font-bold bg-white/10 hover:bg-sky-500 hover:text-white text-gray-200 transition">+1 Suara</button>
                                        <button type="button" onclick="setPaidVotes(5)" class="chip-vote px-2.5 py-1 rounded-lg text-xs font-bold bg-white/10 hover:bg-sky-500 hover:text-white text-gray-200 transition">+5 Suara</button>
                                        <button type="button" onclick="setPaidVotes(10)" class="chip-vote px-2.5 py-1 rounded-lg text-xs font-bold bg-white/10 hover:bg-sky-500 hover:text-white text-gray-200 transition">+10 Suara</button>
                                        <button type="button" onclick="setPaidVotes(50)" class="chip-vote px-2.5 py-1 rounded-lg text-xs font-bold bg-white/10 hover:bg-sky-500 hover:text-white text-gray-200 transition">+50 Suara</button>
                                        <button type="button" onclick="setPaidVotes(100)" class="chip-vote px-2.5 py-1 rounded-lg text-xs font-bold bg-white/10 hover:bg-sky-500 hover:text-white text-gray-200 transition">+100 Suara</button>
                                    </div>

                                    <input id="paid-votes-count" type="number" min="{{ $paid_vote_min ?? 1 }}" value="{{ $paid_vote_min ?? 1 }}"
                                           oninput="calculatePaidTotal()"
                                           class="w-full bg-black/50 border border-emerald-700/50 rounded-xl px-3.5 py-2.5 text-base font-black text-sky-300 focus:border-sky-400 outline-none font-mono">
                                </div>

                                {{-- Total Calculation Box --}}
                                <div class="p-3.5 bg-black/40 rounded-2xl border border-sky-700/40 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Total Bayar via DANA:</span>
                                        <span class="text-[10px] text-emerald-400 font-semibold">100% Murni Tanpa Potongan Suara</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xl font-black text-sky-300 font-mono" id="paid-total-display">Rp {{ number_format(($paid_vote_price ?? 1) * ($paid_vote_min ?? 1), 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <p id="paid-error" class="text-[11px] text-red-400 hidden font-semibold bg-red-900/30 px-3 py-1.5 rounded-lg border border-red-700/40"></p>
                            </div>

                            {{-- Action buttons Step 1 --}}
                            <div class="flex gap-3 pt-1">
                                <button type="button" onclick="closePhoneModal()" class="flex-1 py-3.5 rounded-2xl bg-gray-800 text-gray-300 font-bold text-sm hover:bg-gray-700 transition active:scale-95">
                                    Batal
                                </button>
                                <button type="button" onclick="createPaidQrisInvoice()" id="confirm-paid-btn" class="flex-[2] py-3.5 rounded-2xl bg-gradient-to-r from-sky-500 via-sky-400 to-sky-500 text-slate-950 font-black text-sm hover:brightness-105 transition flex items-center justify-center gap-2 active:scale-95 shadow-xl">
                                    <i data-lucide="qr-code" class="w-4 h-4"></i>
                                    <span>Lanjut Bayar DANA</span>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 2: DISPLAY QRIS DANA & LIVE PAYMENT VERIFICATION --}}
                        <div id="paid-step-qris" class="space-y-4 hidden">
                            <div class="p-4 bg-white rounded-2xl text-center space-y-2.5 border border-gray-200 shadow-lg">
                                <div class="flex items-center justify-between text-[11px] font-bold text-gray-600 border-b border-gray-100 pb-2">
                                    <span class="px-2.5 py-0.5 rounded bg-sky-600 text-white font-black uppercase text-[10px]">DANA QRIS</span>
                                    <span class="font-mono text-gray-700" id="qris-tx-code">-</span>
                                </div>

                                <p class="text-xs font-black text-gray-800">Scan QRIS Menggunakan Aplikasi DANA</p>
                                <p class="text-[10px] text-sky-700 font-bold">{{ $dana_account_name ?? 'DANA Panitia' }}</p>

                                <div class="w-56 h-56 mx-auto bg-white p-2 rounded-2xl border-2 border-sky-200 flex items-center justify-center overflow-hidden">
                                    <img id="qris-dynamic-img" src="" alt="QRIS DANA" class="w-full h-full object-contain">
                                </div>

                                <div class="bg-sky-50 border border-sky-200 rounded-xl p-2.5 text-center space-y-0.5">
                                    <div class="text-[10px] font-bold text-sky-800 uppercase">Nominal Pembayaran DANA</div>
                                    <div class="text-xl font-black text-sky-900 font-mono" id="qris-total-pay">Rp 1</div>
                                    <div class="text-[10px] text-sky-700" id="qris-fee-note"></div>
                                </div>

                                @if(!empty($paid_vote_instructions))
                                    <p class="text-[10px] text-gray-500 italic">{{ $paid_vote_instructions }}</p>
                                @endif

                                {{-- Tombol Download QRIS (PNG) & Buka Aplikasi DANA --}}
                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100">
                                    <button type="button" onclick="downloadQrisPng()"
                                            class="py-2.5 px-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-black text-[11px] flex items-center justify-center gap-1.5 border border-gray-300 transition active:scale-95">
                                        <i data-lucide="download" class="w-3.5 h-3.5 text-sky-700"></i>
                                        <span>Download QRIS</span>
                                    </button>
                                    <button type="button" onclick="openDanaApp()"
                                            class="py-2.5 px-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-black text-[11px] flex items-center justify-center gap-1.5 shadow transition active:scale-95">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        <span>Buka App DANA</span>
                                    </button>
                                </div>
                                <p class="text-[9px] text-gray-400">
                                    Tip HP: Unduh gambar QRIS di atas lalu scan lewat fitur <strong>Galeri</strong> di dalam aplikasi DANA.
                                </p>
                            </div>

                            {{-- Live Waiting Status Indicator --}}
                            <div id="qris-status-box" class="p-3 rounded-2xl bg-sky-950/60 border border-sky-500/50 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <svg class="animate-spin w-4 h-4 text-sky-400 shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <div>
                                        <div class="text-xs font-black text-sky-300" id="qris-status-text">Menunggu Pembayaran DANA Masuk...</div>
                                        <div class="text-[10px] text-gray-300">Otomatis terhitung begitu dana diterima DANA</div>
                                    </div>
                                </div>
                                <span class="font-mono text-xs font-black text-sky-300 shrink-0" id="qris-countdown">15:00</span>
                            </div>

                            <p id="qris-poll-error" class="text-[11px] text-red-400 hidden font-semibold bg-red-900/30 px-3 py-1.5 rounded-lg border border-red-700/40"></p>

                            {{-- Tombol Cek Status (hanya mengecek status webhook DANA, TIDAK bisa approve sendiri) --}}
                            <div class="flex gap-2.5">
                                <button type="button" onclick="resetPaidStepToForm()" class="px-4 py-3 rounded-xl bg-gray-800 text-gray-300 font-bold text-xs hover:bg-gray-700 transition">
                                    Ubah Jumlah
                                </button>
                                <button type="button" onclick="checkPaidVoteStatusNow()" id="check-qris-status-btn" class="flex-1 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5">
                                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                    <span>Periksa Status Pembayaran</span>
                                </button>
                            </div>

                            {{-- Sandbox Simulation Button (ONLY shown when Sandbox Mode is active in Admin) --}}
                            <div id="sandbox-simulate-wrapper" class="hidden pt-1 border-t border-white/10">
                                <button type="button" onclick="triggerSandboxSimulate()" id="sandbox-simulate-btn"
                                        class="w-full py-2.5 rounded-xl bg-purple-700/80 hover:bg-purple-600 text-white font-bold text-xs flex items-center justify-center gap-1.5 border border-purple-400/40 transition">
                                    <i data-lucide="flask-conical" class="w-3.5 h-3.5"></i>
                                    <span>Simulasi Terima Pembayaran DANA (Mode Sandbox)</span>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            @endif

        </div>
    </div>

    <!-- =====================================================================
         SHARE CARD MODAL (POSTER REGU DENGAN MASKOT, LOGO & QR CODE VOTING)
    ===================================================================== -->
    <div id="share-card-modal" role="dialog" aria-modal="true" aria-labelledby="share-modal-title">
        <div class="bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950 rounded-3xl border-2 border-amber-500/60 shadow-2xl p-5 w-full max-w-md mx-auto max-h-[94vh] overflow-y-auto">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-400/50 flex items-center justify-center shrink-0">
                        <i data-lucide="share-2" class="w-4 h-4 text-amber-400"></i>
                    </div>
                    <div>
                        <h3 id="share-modal-title" class="text-sm sm:text-base font-black text-white">Bagikan Poster Dukungan</h3>
                        <p class="text-[11px] text-amber-300 font-bold" id="share-modal-regu-name">-</p>
                    </div>
                </div>
                <button onclick="closeShareCardModal()" type="button" class="p-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Preview Poster Canvas Rendered PNG --}}
            <div class="bg-black/50 rounded-2xl p-2.5 border border-emerald-700/40 flex flex-col items-center justify-center mb-4">
                <div id="share-card-loading" class="py-12 text-center text-xs text-emerald-300 font-semibold flex flex-col items-center gap-2">
                    <svg class="animate-spin w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>Membuat Poster Card...</span>
                </div>
                <img id="share-card-preview-img" src="" alt="Poster Share Regu" class="hidden w-full max-w-[300px] rounded-xl shadow-2xl border border-amber-400/30">
                <canvas id="share-card-canvas" width="1080" height="1350" class="hidden"></canvas>
            </div>

            {{-- Direct URL Preview & Copy --}}
            <div class="mb-4">
                <label class="block text-[10px] font-bold text-emerald-300 uppercase tracking-wider mb-1">Link Voting Langsung Regu Ini:</label>
                <div class="flex items-center gap-1.5 bg-black/50 p-1.5 rounded-xl border border-emerald-700/40">
                    <input type="text" readonly id="share-regu-url-input" class="flex-1 bg-transparent px-2 text-[11px] font-mono text-gray-300 outline-none truncate">
                    <button type="button" onclick="copyShareReguUrl()" id="btn-copy-share-url"
                            class="px-2.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-600 text-white text-[10px] font-bold transition shrink-0">
                        Salin Link
                    </button>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-2 gap-2.5">
                <button type="button" onclick="downloadShareCardPng()"
                        class="py-3 px-3 rounded-xl bg-gray-800 hover:bg-gray-700 text-white font-black text-xs flex items-center justify-center gap-1.5 border border-white/10 transition active:scale-95">
                    <i data-lucide="download" class="w-4 h-4 text-amber-400"></i>
                    <span>Download Card</span>
                </button>
                <button type="button" onclick="shareReguCard()"
                        class="py-3 px-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-emerald-950 font-black text-xs flex items-center justify-center gap-1.5 shadow-lg transition active:scale-95">
                    <i data-lucide="share-2" class="w-4 h-4"></i>
                    <span>Bagikan Sekarang</span>
                </button>
            </div>
        </div>
    </div>



    <!-- =====================================================================
         MAIN SCREEN CONTAINER
    ===================================================================== -->
    <div id="live-screen-root" class="screen-ratio-16-9 w-full flex flex-col justify-between bg-black/40 backdrop-blur-md rounded-2xl sm:rounded-3xl border-2 border-emerald-800/50 shadow-2xl p-3 sm:p-5 lg:p-7 relative overflow-hidden">

        <!-- Header -->
        <header class="flex items-center justify-between pb-3 border-b border-emerald-700/30 relative z-20 shrink-0">
            <div class="flex items-center gap-2 sm:gap-4">
                <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Spencerone" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl shadow-lg border-2 border-amber-400/80 object-cover shrink-0">
                <div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="hidden sm:inline px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-widest uppercase bg-amber-500 text-emerald-950">PANGGUNG PROYEKTOR UTAMA</span>
                        <span id="ws-badge" class="px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-400 animate-ping"></span>LIVE
                        </span>
                    </div>
                    <h1 class="text-base sm:text-xl lg:text-2xl font-black tracking-tight text-white mt-0.5">
                        SPENCERONE CAMP <span class="text-amber-400">SEASON II</span>
                    </h1>
                </div>
            </div>

            <!-- DESKTOP: Tab nav inside header -->
            <div class="desktop-only flex-wrap items-center gap-2 sm:gap-3 hidden">
                <div class="flex p-1 sm:p-1.5 bg-emerald-950/80 rounded-2xl border border-emerald-700/40 gap-1">
                    @if($isCombinedGender)
                        <button onclick="setTab('gabungan')" id="tab-btn-gabungan" class="px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition bg-amber-500 text-emerald-950 shadow-md whitespace-nowrap">🏆 Juara Umum</button>
                    @else
                        <button onclick="setTab('putra')" id="tab-btn-putra" class="px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition bg-amber-500 text-emerald-950 shadow-md whitespace-nowrap">Juara Putra</button>
                        <button onclick="setTab('putri')" id="tab-btn-putri" class="px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition text-emerald-200 hover:text-white whitespace-nowrap">Juara Putri</button>
                    @endif
                    <button onclick="setTab('voting')" id="tab-btn-voting" class="px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition text-amber-300 hover:text-white flex items-center gap-1 whitespace-nowrap">
                        <i data-lucide="award" class="w-3.5 h-3.5 inline"></i><span>Vote Favorit</span>
                    </button>
                    <button onclick="setTab('quiz')" id="tab-btn-quiz" class="px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition text-emerald-200 hover:text-white whitespace-nowrap">Cyber Quiz Live</button>
                </div>
                <div class="text-right pl-3 border-l border-emerald-800/40 hidden xl:block">
                    <div id="live-clock" class="text-lg font-black font-mono text-amber-400">00:00:00</div>
                    <div class="text-[10px] uppercase tracking-wider text-emerald-200/70">Gudep 03.059 - 03.060</div>
                </div>
            </div>

            <!-- MOBILE: clock -->
            <div class="mobile-only text-right">
                <div id="live-clock-mobile" class="text-sm font-black font-mono text-amber-400">00:00:00</div>
                <div class="text-[9px] uppercase tracking-wider text-emerald-200/70">03.059 - 03.060</div>
            </div>
        </header>

        <!-- ================================================================
             DESKTOP TAB CONTENT (hidden on mobile)
        ================================================================ -->
        <main class="desktop-only flex-1 my-3 relative z-10 flex-col justify-center overflow-hidden hidden">

            @if($isCombinedGender)
                <!-- TAB GABUNGAN: JUARA UMUM CAMPURAN -->
                <div id="view-gabungan" class="view-panel h-full flex flex-col justify-center">
                    <div class="text-center mb-4">
                        <span class="text-xs font-black uppercase tracking-widest text-amber-400 bg-amber-950/60 px-3 py-1 rounded-full border border-amber-800/50">KLASEMEN JUARA UMUM — SPENCERONE CAMP SEASON II</span>
                    </div>
                    @include('partials.live-display-leaderboard', ['data' => $juaraUmumAll, 'gender' => 'all', 'isRevealed' => $isRevealed])
                </div>
            @else
                <!-- TAB 1: JUARA PUTRA -->
                <div id="view-putra" class="view-panel h-full flex flex-col justify-center">
                    <div class="text-center mb-4">
                        <span class="text-xs font-black uppercase tracking-widest text-blue-400 bg-blue-950/60 px-3 py-1 rounded-full border border-blue-800/50">KLASEMEN JUARA UMUM - REGU PUTRA (GUDEP 03.059)</span>
                    </div>
                    @include('partials.live-display-leaderboard', ['data' => $juaraUmumPutra, 'gender' => 'putra', 'isRevealed' => $isRevealed])
                </div>

                <!-- TAB 2: JUARA PUTRI -->
                <div id="view-putri" class="view-panel hidden h-full flex flex-col justify-center">
                    <div class="text-center mb-4">
                        <span class="text-xs font-black uppercase tracking-widest text-pink-400 bg-pink-950/60 px-3 py-1 rounded-full border border-pink-800/50">KLASEMEN JUARA UMUM - REGU PUTRI (GUDEP 03.060)</span>
                    </div>
                    @include('partials.live-display-leaderboard', ['data' => $juaraUmumPutri, 'gender' => 'putri', 'isRevealed' => $isRevealed])
                </div>
            @endif

            <!-- TAB 3: QUIZ ARENA -->
            <div id="view-quiz" class="view-panel hidden h-full flex flex-col justify-center overflow-y-auto pr-1">
                <div class="text-center mb-3 shrink-0">
                    <span class="text-xs font-black uppercase tracking-widest text-cyan-400 bg-cyan-950/60 px-3 py-1 rounded-full border border-cyan-800/50">LIVE MONITORING &amp; COIN ROYALE ARENA — SCOUT CYBER QUIZ</span>
                </div>
                @php
                    $liveQuizPayload = [
                        'coinRoyaleState' => $coinRoyaleState ?? ['rounds' => [], 'finalists' => []],
                        'quizLeaderboard' => $quizLeaderboard ?? [],
                    ];
                @endphp
                <div
                    id="live-coin-royale-root"
                    data-component="LiveCoinRoyaleBoard"
                    data-payload-b64="{{ base64_encode(json_encode($liveQuizPayload)) }}"
                    class="w-full"
                ></div>
            </div>

            <!-- TAB 4: VOTING -->
            <div id="view-voting" class="view-panel hidden h-full flex flex-col overflow-y-auto pr-1">
                <div class="text-center mb-3 shrink-0">
                    <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/40 px-3 py-1 rounded-full text-amber-300 text-xs font-black uppercase tracking-wider">
                        <i data-lucide="award" class="w-4 h-4 text-amber-400"></i>
                        <span>Voting Regu Terfavorit Spencerone Camp</span>
                    </div>
                    <p class="text-[11px] text-gray-300 mt-1 max-w-xl mx-auto">
                        Pilih regu favoritmu!
                        @if(($free_vote_reset_mode ?? 'daily') === 'daily')
                            1 perangkat = 1 suara gratis per hari (reset 00:00 WITA).
                        @elseif(($free_vote_reset_mode ?? 'daily') === 'interval')
                            1 perangkat = 1 suara gratis setiap {{ $free_vote_reset_interval_hours ?? 24 }} jam.
                        @else
                            1 perangkat = 1 suara gratis selama kegiatan.
                        @endif
                    </p>
                    <div class="mt-2 flex items-center justify-center gap-3 text-xs font-mono flex-wrap">
                        <span class="text-emerald-300">Total Vote: <strong class="text-amber-400" id="total-vote-counter">{{ $totalVotesCount }}</strong></span>
                        @if($is_vote_count_locked ?? false)
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-950/90 text-amber-300 border border-amber-500/50 text-[10px] font-bold flex items-center gap-1"
                                  title="{{ $unlock_at_formatted ? 'Hasil dibuka otomatis pada ' . $unlock_at_formatted : 'Perolehan suara dirahasiakan oleh panitia' }}">
                                <i data-lucide="lock" class="w-3 h-3 text-amber-400"></i> Suara Dikunci {{ $unlock_at_formatted ? 's/d ' . $unlock_at_formatted : '(Dirahasiakan)' }}
                            </span>
                        @endif
                        @if($is_voting_closed ?? false)
                            <span class="px-2.5 py-0.5 rounded-full bg-red-950/90 text-red-300 border border-red-500/50 text-[10px] font-bold flex items-center gap-1"
                                  title="{{ $voting_end_at_formatted ? 'Telah berakhir pada ' . $voting_end_at_formatted : 'Voting telah resmi ditutup oleh panitia' }}">
                                <i data-lucide="ban" class="w-3 h-3 text-red-400"></i> Voting Ditutup
                            </span>
                        @endif
                        <span id="desktop-free-voted-badge" class="{{ $hasVoted ? '' : 'hidden' }} px-2.5 py-0.5 rounded-full bg-emerald-900/80 text-emerald-200 border border-emerald-500/50 text-[10px] font-bold flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3 h-3 text-emerald-400"></i>
                            <span>Sudah Memilih</span>
                            <span id="free-vote-countdown-desktop-wrap" class="{{ ($free_vote_reset_mode ?? 'daily') === 'once' ? 'hidden' : '' }} inline-flex items-center gap-1 text-amber-300 font-mono border-l border-emerald-600/60 pl-1.5">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                <span id="free-vote-countdown-desktop">--:--:--</span>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 overflow-y-auto flex-1 p-0.5" id="voting-cards-grid-desktop">
                    @forelse($votingRegus as $vRegu)
                        <div class="regu-vote-card bg-gradient-to-br from-slate-900/95 via-emerald-950/80 to-slate-950 rounded-2xl p-3 border {{ $vRegu['is_my_vote'] ? 'border-amber-400 shadow-amber-500/20 shadow-lg ring-2 ring-amber-400/40' : 'border-emerald-600/30 shadow-md' }} flex flex-col justify-between transition hover:border-amber-400/60" data-regu-id="{{ $vRegu['id'] }}">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $vRegu['jenis'] === 'putra' ? 'bg-blue-900/80 text-blue-200 border border-blue-700/50' : 'bg-pink-900/80 text-pink-200 border border-pink-700/50' }}">{{ $vRegu['jenis'] }} &bull; {{ $vRegu['nomor_kavling'] }}</span>
                                    <div class="flex items-center gap-1 bg-black/50 px-2 py-0.5 rounded-full border border-amber-400/30">
                                        <i data-lucide="heart" class="w-3 h-3 text-amber-400 fill-amber-400"></i>
                                        <span class="text-xs font-mono font-black text-amber-300" id="vote-count-{{ $vRegu['id'] }}">{{ $vRegu['votes_count'] }}</span>
                                    </div>
                                </div>
                                <div class="aspect-[4/3] rounded-xl overflow-hidden bg-black/40 border border-white/10 relative mb-2.5 group">
                                    @if($vRegu['foto_regu_path'])
                                        <img src="{{ asset('storage/' . $vRegu['foto_regu_path']) }}" alt="Foto {{ $vRegu['nama_regu'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 text-xs p-2 text-center">
                                            <i data-lucide="image" class="w-8 h-8 opacity-40 mb-1"></i>
                                            <span class="text-[10px]">Belum unggah foto</span>
                                        </div>
                                    @endif
                                    @if($vRegu['is_my_vote'])
                                        <div class="absolute top-2 left-2 bg-amber-500 text-emerald-950 font-black text-[9px] px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <i data-lucide="check" class="w-2.5 h-2.5"></i> Pilihan Anda
                                        </div>
                                    @endif
                                </div>
                                <h4 class="font-black text-sm text-white tracking-tight line-clamp-1 uppercase" title="{{ $vRegu['nama_regu'] }}">{{ $vRegu['nama_regu'] }}</h4>
                                <p class="text-[10px] text-emerald-300 font-mono">Regu #{{ $vRegu['nomor_regu'] }}</p>
                                @if($vRegu['foto_regu_caption'])
                                    <p class="text-[11px] text-gray-300 italic mt-1 line-clamp-2 bg-black/20 p-1.5 rounded border border-white/5">"{{ $vRegu['foto_regu_caption'] }}"</p>
                                @endif
                            </div>
                            <div class="mt-3 pt-2 border-t border-white/10 flex items-center gap-1.5">
                                @if($vRegu['is_my_vote'])
                                    <button onclick="submitVote({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}', 'paid')" class="flex-1 py-2.5 px-2.5 rounded-xl bg-amber-500 text-emerald-950 font-black text-xs uppercase tracking-wider hover:bg-amber-400 transition flex items-center justify-center gap-1.5 shadow">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i><span>Pilihan Anda (Vote Lagi)</span>
                                    </button>
                                @elseif($is_voting_closed ?? false)
                                    <button disabled class="flex-1 py-2.5 px-2.5 rounded-xl bg-red-950/60 border border-red-800/40 text-red-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed flex items-center justify-center gap-1">
                                        <i data-lucide="lock" class="w-3.5 h-3.5"></i><span>Voting Ditutup</span>
                                    </button>
                                @elseif($hasVoted)
                                    @if($paid_voting_enabled ?? true)
                                        <button onclick="submitVote({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}', 'paid')" class="vote-btn-action flex-1 py-2.5 px-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xs uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1.5">
                                            <i data-lucide="sparkles" class="w-4 h-4 text-amber-300"></i><span>Vote DANA</span>
                                        </button>
                                    @else
                                        <button disabled class="flex-1 py-2.5 px-2.5 rounded-xl bg-gray-800/80 text-gray-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed flex items-center justify-center gap-1">
                                            <i data-lucide="lock" class="w-3.5 h-3.5"></i><span>Sudah Memilih</span>
                                        </button>
                                    @endif
                                @else
                                    <button onclick="submitVote({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}', 'free')" class="vote-btn-action flex-1 py-2.5 px-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-emerald-950 font-black text-xs uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1.5">
                                        <i data-lucide="heart" class="w-4 h-4 fill-emerald-950"></i><span>Vote Regu</span>
                                    </button>
                                @endif
                                <button type="button" onclick="openShareCardModal({{ $vRegu['id'] }})" title="Bagikan Poster Card Regu"
                                        class="shrink-0 py-2.5 px-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-amber-300 border border-amber-400/30 transition active:scale-95 flex items-center justify-center">
                                    <i data-lucide="share-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12 text-gray-400">Belum ada regu terdaftar untuk voting terfavorit.</div>
                    @endforelse
                </div>
            </div>

        </main>

        <!-- ================================================================
             MOBILE SCROLLABLE CONTENT (hidden on desktop)
        ================================================================ -->
        <div class="mobile-only w-full relative z-10 py-3 space-y-0">

            <!-- Mobile Quick Nav -->
            <div class="tab-nav-scroll flex gap-2 pb-2 px-0.5 mb-3">
                @if($isCombinedGender)
                    <button onclick="scrollToSection('mobile-sec-gabungan')" class="shrink-0 px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/50 whitespace-nowrap">🏆 Juara Umum</button>
                @else
                    <button onclick="scrollToSection('mobile-sec-putra')" class="shrink-0 px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-blue-900/80 text-blue-200 border border-blue-700/50 whitespace-nowrap">🏆 Juara Putra</button>
                    <button onclick="scrollToSection('mobile-sec-putri')" class="shrink-0 px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-pink-900/80 text-pink-200 border border-pink-700/50 whitespace-nowrap">🏆 Juara Putri</button>
                @endif
                <button onclick="scrollToSection('mobile-sec-voting')" class="shrink-0 px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/50 whitespace-nowrap">❤️ Vote Favorit</button>
                <button onclick="scrollToSection('mobile-sec-quiz')" class="shrink-0 px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider bg-cyan-900/80 text-cyan-200 border border-cyan-700/50 whitespace-nowrap">⚡ Quiz Live</button>
            </div>

            @if($isCombinedGender)
                <!-- MOBILE: JUARA UMUM GABUNGAN -->
                <div id="mobile-sec-gabungan" class="mobile-section scroll-mt-3 mb-4">
                    <div class="mobile-section-header bg-amber-950/60 text-amber-300" style="border-bottom-color: rgba(217,119,6,0.3)">
                        <i data-lucide="crown" class="w-4 h-4 text-amber-400 shrink-0"></i>
                        KLASEMEN JUARA UMUM — SPENCERONE CAMP SEASON II
                    </div>
                    <div class="p-3">
                        @include('partials.live-display-leaderboard', ['data' => $juaraUmumAll, 'gender' => 'all', 'isRevealed' => $isRevealed])
                    </div>
                </div>
            @else
                <!-- MOBILE: JUARA PUTRA -->
                <div id="mobile-sec-putra" class="mobile-section scroll-mt-3 mb-4">
                    <div class="mobile-section-header bg-blue-950/60 text-blue-300" style="border-bottom-color: rgba(37,99,235,0.3)">
                        <i data-lucide="trophy" class="w-4 h-4 text-blue-400 shrink-0"></i>
                        KLASEMEN JUARA UMUM PUTRA — GUDEP 03.059
                    </div>
                    <div class="p-3">
                        @include('partials.live-display-leaderboard', ['data' => $juaraUmumPutra, 'gender' => 'putra', 'isRevealed' => $isRevealed])
                    </div>
                </div>

                <!-- MOBILE: JUARA PUTRI -->
                <div id="mobile-sec-putri" class="mobile-section scroll-mt-3 mb-4">
                    <div class="mobile-section-header bg-pink-950/60 text-pink-300" style="border-bottom-color: rgba(219,39,119,0.3)">
                        <i data-lucide="trophy" class="w-4 h-4 text-pink-400 shrink-0"></i>
                        KLASEMEN JUARA UMUM PUTRI — GUDEP 03.060
                    </div>
                    <div class="p-3">
                        @include('partials.live-display-leaderboard', ['data' => $juaraUmumPutri, 'gender' => 'putri', 'isRevealed' => $isRevealed])
                    </div>
                </div>
            @endif

            <!-- MOBILE: VOTING -->
            <div id="mobile-sec-voting" class="mobile-section scroll-mt-3 mb-4">
                <div class="mobile-section-header bg-amber-950/60 text-amber-300" style="border-bottom-color: rgba(217,119,6,0.3)">
                    <i data-lucide="award" class="w-4 h-4 text-amber-400 shrink-0"></i>
                    VOTING REGU TERFAVORIT
                    <span class="ml-auto text-[10px] font-mono bg-black/40 px-2 py-0.5 rounded-full border border-amber-500/30">
                        Total: <strong id="total-vote-counter-mobile" class="text-amber-400">{{ $totalVotesCount }}</strong>
                    </span>
                </div>
                <div class="p-3">
                    <p class="text-[11px] text-gray-400 mb-2 text-center leading-relaxed">
                        Pilih regu favoritmu! Masukkan nama kamu untuk memberikan suara.<br>
                        <strong class="text-amber-400/90">
                            @if(($free_vote_reset_mode ?? 'daily') === 'daily')
                                1 perangkat = 1 suara gratis per hari (reset 00:00 WITA).
                            @elseif(($free_vote_reset_mode ?? 'daily') === 'interval')
                                1 perangkat = 1 suara gratis setiap {{ $free_vote_reset_interval_hours ?? 24 }} jam.
                            @else
                                1 perangkat = 1 suara gratis selama kegiatan.
                            @endif
                        </strong>
                    </p>
                    @if($is_vote_count_locked ?? false)
                        <div class="mb-2.5 px-3 py-1.5 rounded-xl bg-amber-950/70 border border-amber-500/50 flex items-center justify-center gap-1.5 text-xs text-amber-300 font-bold text-center">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                            Perolehan suara dirahasiakan {{ $unlock_at_formatted ? 'sampai ' . $unlock_at_formatted : 'oleh panitia' }}.
                        </div>
                    @endif
                    @if($is_voting_closed ?? false)
                        <div class="mb-2.5 px-3 py-1.5 rounded-xl bg-red-950/70 border border-red-500/50 flex items-center justify-center gap-1.5 text-xs text-red-300 font-bold text-center">
                            <i data-lucide="ban" class="w-3.5 h-3.5 text-red-400 shrink-0"></i>
                            Voting telah resmi ditutup {{ $voting_end_at_formatted ? 'pada ' . $voting_end_at_formatted : '' }}.
                        </div>
                    @endif
                    <div id="mobile-free-voted-banner" class="{{ $hasVoted ? '' : 'hidden' }} mb-3 px-3 py-2 rounded-xl bg-emerald-900/60 border border-emerald-500/50 flex flex-wrap items-center justify-between gap-2 text-xs text-emerald-300 font-bold">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                            <span>Suara gratis telah digunakan.</span>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <span id="free-vote-countdown-mobile-wrap" class="{{ ($free_vote_reset_mode ?? 'daily') === 'once' ? 'hidden' : '' }} inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-black/40 border border-amber-500/30 text-[11px] font-mono font-black text-amber-300">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                <span id="free-vote-countdown-mobile">--:--:--</span>
                            </span>
                            @if($paid_voting_enabled ?? true)
                                <span class="text-[10px] text-amber-300 font-extrabold uppercase">Vote DANA ✨</span>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @forelse($votingRegus as $vRegu)
                            <div class="regu-vote-card bg-gradient-to-br from-slate-900/95 via-emerald-950/80 to-slate-950 rounded-2xl p-3 border {{ $vRegu['is_my_vote'] ? 'border-amber-400 ring-2 ring-amber-400/30' : 'border-emerald-600/30' }} flex flex-col justify-between transition" data-regu-id="{{ $vRegu['id'] }}">
                                <div>
                                    <div class="flex items-center justify-between gap-1 mb-2">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase {{ $vRegu['jenis'] === 'putra' ? 'bg-blue-900/80 text-blue-200' : 'bg-pink-900/80 text-pink-200' }}">{{ $vRegu['jenis'] }}</span>
                                        <div class="flex items-center gap-0.5 text-amber-300 text-[10px] font-mono font-black">
                                            ❤ <span id="vote-count-{{ $vRegu['id'] }}-mobile">{{ $vRegu['votes_count'] }}</span>
                                        </div>
                                    </div>
                                    <div class="aspect-[4/3] rounded-xl overflow-hidden bg-black/40 border border-white/10 relative mb-2 group">
                                        @if($vRegu['foto_regu_path'])
                                            <img src="{{ asset('storage/' . $vRegu['foto_regu_path']) }}" alt="Foto {{ $vRegu['nama_regu'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 p-2 text-center">
                                                <i data-lucide="image" class="w-6 h-6 opacity-40 mb-1"></i>
                                                <span class="text-[9px]">Belum ada foto</span>
                                            </div>
                                        @endif
                                        @if($vRegu['is_my_vote'])
                                            <div class="absolute top-1.5 left-1.5 bg-amber-500 text-emerald-950 font-black text-[8px] px-1.5 py-0.5 rounded-full">✓ Pilihan Anda</div>
                                        @endif
                                    </div>
                                    <h4 class="font-black text-xs text-white tracking-tight line-clamp-2 uppercase leading-tight" title="{{ $vRegu['nama_regu'] }}">{{ $vRegu['nama_regu'] }}</h4>
                                    <p class="text-[9px] text-emerald-300 font-mono mt-0.5">Regu #{{ $vRegu['nomor_regu'] }}</p>
                                </div>
                                <div class="mt-2 pt-2 border-t border-white/10 flex items-center gap-1">
                                    @if($vRegu['is_my_vote'])
                                        <button onclick="submitVote({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}', 'paid')" class="flex-1 py-2 px-1.5 rounded-lg bg-amber-500 text-emerald-950 font-black text-[10px] uppercase cursor-pointer flex items-center justify-center gap-1 shadow">
                                            ✓ Pilihan Anda
                                        </button>
                                    @elseif($is_voting_closed ?? false)
                                        <button disabled class="flex-1 py-2 px-1.5 rounded-lg bg-red-950/60 border border-red-800/40 text-red-400 font-bold text-[10px] uppercase cursor-not-allowed flex items-center justify-center gap-1">
                                            🔒 Ditutup
                                        </button>
                                    @elseif($hasVoted)
                                        @if($paid_voting_enabled ?? true)
                                            <button onclick="submitVote({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}', 'paid')" class="vote-btn-action flex-1 py-2.5 px-1.5 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-black text-[10px] uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1">
                                                ✨ Vote DANA
                                            </button>
                                        @else
                                            <button disabled class="flex-1 py-2 px-1.5 rounded-lg bg-gray-800/80 text-gray-400 font-bold text-[10px] uppercase cursor-not-allowed flex items-center justify-center gap-1">
                                                🔒 Selesai
                                            </button>
                                        @endif
                                    @else
                                        <button onclick="submitVote({{ $vRegu['id'] }}, '{{ addslashes($vRegu['nama_regu']) }}', 'free')" class="vote-btn-action flex-1 py-2.5 px-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 text-emerald-950 font-black text-[10px] uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1">
                                            ❤ Vote
                                        </button>
                                    @endif
                                    <button type="button" onclick="openShareCardModal({{ $vRegu['id'] }})" title="Bagikan Card Regu"
                                            class="shrink-0 py-2 px-2 rounded-lg bg-white/10 hover:bg-white/20 text-amber-300 border border-amber-400/30 transition active:scale-95 flex items-center justify-center">
                                        <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8 text-gray-400 text-sm">Belum ada regu terdaftar.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- MOBILE: QUIZ -->
            <div id="mobile-sec-quiz" class="mobile-section scroll-mt-3 mb-4">
                <div class="mobile-section-header bg-cyan-950/60 text-cyan-300" style="border-bottom-color: rgba(6,182,212,0.3)">
                    <i data-lucide="zap" class="w-4 h-4 text-cyan-400 shrink-0"></i>
                    SCOUT CYBER QUIZ LIVE ARENA
                </div>
                <div class="p-3">
                    @if(count($quizLeaderboard) > 0)
                        <div class="space-y-2">
                            @foreach($quizLeaderboard as $qRegu)
                                <div class="bg-gradient-to-r from-slate-900/80 to-emerald-950/60 rounded-xl p-3 border border-emerald-700/30 flex items-center gap-3">
                                    <div class="shrink-0">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $qRegu['jenis'] === 'putra' ? 'bg-blue-900 text-blue-200' : 'bg-pink-900 text-pink-200' }}">{{ $qRegu['jenis'] }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-black text-white truncate">{{ strtoupper($qRegu['regu_name']) }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono">B1:{{ $qRegu['b1'] }} &bull; B2:{{ $qRegu['b2'] }} &bull; B3:{{ $qRegu['b3'] }}</p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <div class="text-xl font-mono font-black text-amber-400">{{ $qRegu['total'] }}</div>
                                        @if($qRegu['is_locked'])
                                            <span class="text-[9px] text-red-400 font-bold uppercase">LOCKED</span>
                                        @else
                                            <span class="text-[9px] text-emerald-400 font-bold uppercase">AKTIF</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 text-sm">Belum ada aktivitas sesi cerdas cermat.</div>
                    @endif
                </div>
            </div>

        </div>

        <!-- DESKTOP Footer -->
        <footer class="desktop-only pt-3 border-t border-emerald-800/40 items-center justify-between text-xs text-emerald-300/70 relative z-20 shrink-0 hidden">
            <div class="flex items-center gap-4">
                <span>Auto-slide: <strong class="text-amber-400" id="slide-timer">15s</strong></span>
                <span class="hidden sm:inline">&bull; Keyboard: <kbd class="px-1.5 py-0.5 bg-black/40 rounded border border-white/20 font-mono text-[10px]">Spasi</kbd> (Jeda/Lanjut), <kbd class="px-1.5 py-0.5 bg-black/40 rounded border border-white/20 font-mono text-[10px]">&larr;</kbd> <kbd class="px-1.5 py-0.5 bg-black/40 rounded border border-white/20 font-mono text-[10px]">&rarr;</kbd> (Ganti Slide)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-bold text-white tracking-wide">SMPN 1 CERBON</span>
            </div>
        </footer>

        <!-- MOBILE Footer -->
        <div class="mobile-only border-t border-emerald-800/30 pt-3 mt-1 flex items-center justify-between text-[10px] text-emerald-300/60 shrink-0">
            <span>Spencerone Camp Season II &mdash; SMPN 1 Cerbon</span>
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-bold text-emerald-300">LIVE</span>
            </div>
        </div>

        <!-- REVEAL OVERLAY -->
        <div id="reveal-countdown-overlay" class="hidden absolute inset-0 z-50 bg-black/90 backdrop-blur-xl flex flex-col items-center justify-center text-center p-8">
            <div class="text-amber-400 uppercase font-mono tracking-widest text-sm sm:text-base mb-4 font-bold flex items-center gap-2 animate-pulse">
                <i data-lucide="sparkles" class="w-5 h-5"></i> UPACARA PENGUMUMAN SENSOR JUARA UMUM
            </div>
            <div id="countdown-number" class="text-8xl sm:text-9xl font-black font-mono text-transparent bg-clip-text bg-gradient-to-b from-amber-300 via-amber-500 to-yellow-600 gold-glow scale-125 transition-all duration-500">5</div>
            <p class="text-gray-400 text-sm mt-6 max-w-md">Menyingkap tirai kemenangan 3 Besar Spencerone Camp Season II...</p>
        </div>

    </div><!-- end #live-screen-root -->

    <script>
        // ============================================================
        // STATE
        // ============================================================
        @if($isCombinedGender)
            let currentTab = 'gabungan';
            const tabs = ['gabungan', 'voting', 'quiz'];
        @else
            let currentTab = 'putra';
            const tabs = ['putra', 'putri', 'voting', 'quiz'];
        @endif
        let slideRemaining = 15;
        let isSlidePaused = false;
        let isRevealedState = {{ $isRevealed ? 'true' : 'false' }};
        let pendingVoteReguId = null;
        let pendingVoteNama = null;

        // ============================================================
        // CLOCKS
        // ============================================================
        setInterval(() => {
            const timeStr = new Date().toLocaleTimeString('id-ID');
            ['live-clock', 'live-clock-mobile'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = timeStr;
            });
        }, 1000);

        // ============================================================
        // MOBILE SCROLL NAV
        // ============================================================
        function scrollToSection(id) {
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // ============================================================
        // VOTING CONSTANTS & TAB SWITCHING
        // ============================================================
        const unitPrice = {{ $paid_vote_price ?? 1 }};
        const minVotes = {{ $paid_vote_min ?? 1 }};
        const paidVotingEnabled = {{ ($paid_voting_enabled ?? true) ? 'true' : 'false' }};
        let userHasVotedFree = {{ ($hasVoted ?? false) ? 'true' : 'false' }};
        let currentModalTab = 'free';
        const freeVoteResetMode = @json($free_vote_reset_mode ?? 'daily');
        const freeVoteIntervalHours = {{ $free_vote_reset_interval_hours ?? 24 }};
        let nextFreeVoteAtIso = @json($next_free_vote_at ?? null);
        let freeVoteCountdownTimer = null;

        function formatCountdownDuration(diffMs) {
            const totalSeconds = Math.max(0, Math.floor(diffMs / 1000));
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            const hhmmss = [hours, minutes, seconds].map(n => String(n).padStart(2, '0')).join(':');
            return days > 0 ? `${days}h ${hhmmss}` : hhmmss;
        }

        function unlockFreeVoteQuota() {
            userHasVotedFree = false;
            nextFreeVoteAtIso = null;

            document.getElementById('free-voted-notice')?.classList.add('hidden');
            document.getElementById('free-vote-form')?.classList.remove('hidden');
            document.getElementById('desktop-free-voted-badge')?.classList.add('hidden');
            document.getElementById('mobile-free-voted-banner')?.classList.add('hidden');

            if (typeof isVotingClosedGlobal !== 'undefined' && isVotingClosedGlobal) return;

            document.querySelectorAll('.regu-vote-card').forEach(card => {
                const reguId = parseInt(card.getAttribute('data-regu-id') || '0');
                const reguObj = typeof votingRegusMap !== 'undefined' ? votingRegusMap[reguId] : null;
                const namaRegu = reguObj ? reguObj.nama_regu : '';
                const isMobile = Boolean(card.closest('#mobile-sec-voting'));
                const btn = card.querySelector('button');
                if (!btn) return;

                btn.disabled = false;
                btn.onclick = () => submitVote(reguId, namaRegu, 'free');
                if (isMobile) {
                    btn.className = 'vote-btn-action flex-1 py-2.5 px-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 text-emerald-950 font-black text-[10px] uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1';
                    btn.innerHTML = '❤ Vote';
                } else {
                    btn.className = 'vote-btn-action flex-1 py-2.5 px-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-emerald-950 font-black text-xs uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1.5';
                    btn.innerHTML = '<i data-lucide="heart" class="w-4 h-4 fill-emerald-950"></i><span>Vote Regu</span>';
                }
            });

            if (typeof lucide !== 'undefined') lucide.createIcons();
            showToast('Kuota vote gratis sudah aktif kembali! Silakan pilih regu favoritmu.', 'success');
        }

        function startFreeVoteCountdown(isoTimestamp) {
            if (freeVoteCountdownTimer) {
                clearInterval(freeVoteCountdownTimer);
                freeVoteCountdownTimer = null;
            }

            const timerWrapModal = document.getElementById('free-vote-timer-wrap-modal');
            const onceNoteModal = document.getElementById('free-vote-once-note-modal');
            const desktopWrap = document.getElementById('free-vote-countdown-desktop-wrap');
            const mobileWrap = document.getElementById('free-vote-countdown-mobile-wrap');

            if (freeVoteResetMode === 'once' || !isoTimestamp) {
                if (freeVoteResetMode === 'once') {
                    timerWrapModal?.classList.add('hidden');
                    onceNoteModal?.classList.remove('hidden');
                    desktopWrap?.classList.add('hidden');
                    mobileWrap?.classList.add('hidden');
                }
                return;
            }

            nextFreeVoteAtIso = isoTimestamp;
            const targetMs = new Date(isoTimestamp).getTime();
            if (isNaN(targetMs)) return;

            timerWrapModal?.classList.remove('hidden');
            onceNoteModal?.classList.add('hidden');
            desktopWrap?.classList.remove('hidden');
            mobileWrap?.classList.remove('hidden');

            const targets = [
                document.getElementById('free-vote-countdown-modal'),
                document.getElementById('free-vote-countdown-desktop'),
                document.getElementById('free-vote-countdown-mobile'),
            ];

            const tick = () => {
                const diff = targetMs - Date.now();
                if (diff <= 0) {
                    clearInterval(freeVoteCountdownTimer);
                    freeVoteCountdownTimer = null;
                    targets.forEach(el => { if (el) el.textContent = '00:00:00'; });
                    if (userHasVotedFree) unlockFreeVoteQuota();
                    return;
                }
                const text = formatCountdownDuration(diff);
                targets.forEach(el => { if (el) el.textContent = text; });
            };

            tick();
            freeVoteCountdownTimer = setInterval(tick, 1000);
        }

        function switchVoteModalTab(tab) {
            currentModalTab = tab;
            const btnFree = document.getElementById('tab-btn-modal-free');
            const btnPaid = document.getElementById('tab-btn-modal-paid');
            const tabFree = document.getElementById('modal-tab-free');
            const tabPaid = document.getElementById('modal-tab-paid');

            if (tab === 'free') {
                if (btnFree) {
                    btnFree.className = 'py-2 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center justify-center gap-1.5 bg-amber-500 text-emerald-950 shadow';
                }
                if (btnPaid) {
                    btnPaid.className = 'py-2 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-emerald-300 hover:text-white';
                }
                if (tabFree) tabFree.classList.remove('hidden');
                if (tabPaid) tabPaid.classList.add('hidden');
                setTimeout(() => document.getElementById('phone-input')?.focus(), 100);
            } else {
                if (btnPaid) {
                    btnPaid.className = 'py-2 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center justify-center gap-1.5 bg-amber-500 text-emerald-950 shadow';
                }
                if (btnFree) {
                    btnFree.className = 'py-2 px-3 rounded-xl text-xs font-black uppercase tracking-wider transition flex items-center justify-center gap-1.5 text-emerald-300 hover:text-white';
                }
                if (tabPaid) tabPaid.classList.remove('hidden');
                if (tabFree) tabFree.classList.add('hidden');
                calculatePaidTotal();
                setTimeout(() => document.getElementById('paid-voter-name')?.focus(), 100);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function setPaidVotes(qty) {
            const el = document.getElementById('paid-votes-count');
            if (el) {
                el.value = Math.max(minVotes, qty);
                calculatePaidTotal();
            }
        }

        function calculatePaidTotal() {
            const el = document.getElementById('paid-votes-count');
            const displayEl = document.getElementById('paid-total-display');
            if (!el || !displayEl) return;
            let qty = parseInt(el.value) || minVotes;
            if (qty < minVotes) qty = minVotes;
            const total = qty * unitPrice;
            displayEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function copyAccountNumber() {
            const text = document.getElementById('bank-acc-no')?.textContent?.trim();
            if (text) {
                navigator.clipboard.writeText(text).then(() => {
                    const btn = document.getElementById('copy-btn-text');
                    if (btn) {
                        btn.textContent = 'Tersalin!';
                        setTimeout(() => btn.textContent = 'Salin', 2000);
                    }
                });
            }
        }

        // ============================================================
        // PHONE / VOTE MODAL & PAKASIR QRIS STATE
        // ============================================================
        let activeTransactionCode = null;
        let activeTransactionReguId = null;
        let qrisPollingTimer = null;
        let qrisCountdownTimer = null;

        function stopQrisTimers() {
            if (qrisPollingTimer) {
                clearInterval(qrisPollingTimer);
                qrisPollingTimer = null;
            }
            if (qrisCountdownTimer) {
                clearInterval(qrisCountdownTimer);
                qrisCountdownTimer = null;
            }
        }

        function resetPaidStepToForm() {
            stopQrisTimers();
            activeTransactionCode = null;
            document.getElementById('paid-step-qris')?.classList.add('hidden');
            document.getElementById('paid-step-form')?.classList.remove('hidden');
            const pollErr = document.getElementById('qris-poll-error');
            if (pollErr) {
                pollErr.textContent = '';
                pollErr.classList.add('hidden');
            }
        }

        function openPhoneModal(reguId, namaRegu, defaultTab = null) {
            pendingVoteReguId = reguId;
            pendingVoteNama = namaRegu;
            resetPaidStepToForm();
            document.getElementById('modal-regu-name').textContent = namaRegu;
            document.getElementById('phone-input').value = '';
            const errEl = document.getElementById('phone-error');
            if (errEl) {
                errEl.textContent = '';
                errEl.classList.add('hidden');
            }
            const paidErrEl = document.getElementById('paid-error');
            if (paidErrEl) {
                paidErrEl.textContent = '';
                paidErrEl.classList.add('hidden');
            }

            // Pilih tab awal sesuai prioritas
            if (defaultTab) {
                switchVoteModalTab(defaultTab);
            } else if (userHasVotedFree && paidVotingEnabled) {
                switchVoteModalTab('paid');
            } else {
                switchVoteModalTab('free');
            }

            document.getElementById('phone-modal').classList.add('open');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closePhoneModal() {
            stopQrisTimers();
            activeTransactionCode = null;
            activeTransactionReguId = null;
            document.getElementById('phone-modal').classList.remove('open');
            pendingVoteReguId = null;
            pendingVoteNama = null;
        }

        document.getElementById('phone-modal').addEventListener('click', function(e) {
            if (e.target === this) closePhoneModal();
        });

        document.getElementById('phone-input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') confirmVote();
        });

        async function confirmVote() {
            const voterName = document.getElementById('phone-input').value.trim();
            const errorEl = document.getElementById('phone-error');
            const btn = document.getElementById('confirm-vote-btn');

            if (!voterName || voterName.length < 2) {
                errorEl.textContent = 'Masukkan nama yang valid (minimal 2 huruf).';
                errorEl.classList.remove('hidden');
                document.getElementById('phone-input').focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-1.5 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Mengirim...';

            try {
                await submitVoteWithName(pendingVoteReguId, pendingVoteNama, voterName);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="heart" class="w-4 h-4 fill-emerald-950"></i><span>Kirim 1 Suara Gratis</span>';
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        // ============================================================
        // PAKASIR DYNAMIC QRIS CREATION & REAL-TIME VERIFICATION
        // ============================================================
        async function createPaidQrisInvoice() {
            const voterName = document.getElementById('paid-voter-name')?.value?.trim();
            const votesCount = parseInt(document.getElementById('paid-votes-count')?.value) || 0;
            const errorEl = document.getElementById('paid-error');
            const btn = document.getElementById('confirm-paid-btn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (!voterName || voterName.length < 2) {
                errorEl.textContent = 'Masukkan nama pemilih / pendukung yang valid (minimal 2 huruf).';
                errorEl.classList.remove('hidden');
                document.getElementById('paid-voter-name')?.focus();
                return;
            }

            if (!votesCount || votesCount < minVotes) {
                errorEl.textContent = `Minimal pembelian suara adalah ${minVotes} suara.`;
                errorEl.classList.remove('hidden');
                return;
            }

            errorEl.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-1.5 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Membuat QRIS...';

            try {
                const res = await fetch(`/live-display/vote/${pendingVoteReguId}/paid`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        voter_name: voterName,
                        votes_count: votesCount,
                    }),
                });

                const data = await res.json();

                if (data.success && data.qris_image) {
                    activeTransactionCode = data.transaction_code;
                    activeTransactionReguId = data.regu_id;

                    // Tampilkan Step 2 (QRIS dinamis dari Pakasir)
                    document.getElementById('paid-step-form').classList.add('hidden');
                    document.getElementById('paid-step-qris').classList.remove('hidden');

                    document.getElementById('qris-tx-code').textContent = data.transaction_code;
                    document.getElementById('qris-dynamic-img').src = data.qris_image;
                    document.getElementById('qris-total-pay').textContent = 'Rp ' + Number(data.total_payment || data.amount).toLocaleString('id-ID');

                    const feeNoteEl = document.getElementById('qris-fee-note');
                    if (feeNoteEl) {
                        if (Number(data.unique_code) > 0) {
                            feeNoteEl.textContent = `(${votesCount} suara = Rp ${Number(data.amount).toLocaleString('id-ID')} + kode unik Rp ${Number(data.unique_code).toLocaleString('id-ID')})`;
                        } else {
                            feeNoteEl.textContent = `${votesCount} suara murni untuk ${data.regu_name}`;
                        }
                    }

                    // Tampilkan tombol simulasi HANYA jika Sandbox aktif di server
                    const simWrap = document.getElementById('sandbox-simulate-wrapper');
                    if (simWrap) {
                        if (data.is_sandbox) {
                            simWrap.classList.remove('hidden');
                        } else {
                            simWrap.classList.add('hidden');
                        }
                    }

                    startQrisCountdown(data.expired_at);
                    startPaidVotePolling();
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                } else {
                    errorEl.textContent = data.message || 'Gagal membuat tagihan QRIS.';
                    errorEl.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
                errorEl.textContent = 'Terjadi kesalahan jaringan saat membuat QRIS. Coba lagi.';
                errorEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="qr-code" class="w-4 h-4"></i><span>Lanjut Bayar DANA</span>';
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        function startQrisCountdown(expiredIso) {
            const countdownEl = document.getElementById('qris-countdown');
            if (!countdownEl) return;
            const targetTime = expiredIso ? new Date(expiredIso).getTime() : (Date.now() + 15 * 60 * 1000);

            const updateTimer = () => {
                const diffSec = Math.max(0, Math.floor((targetTime - Date.now()) / 1000));
                const m = String(Math.floor(diffSec / 60)).padStart(2, '0');
                const s = String(diffSec % 60).padStart(2, '0');
                countdownEl.textContent = `${m}:${s}`;
                if (diffSec <= 0) {
                    stopQrisTimers();
                    const errEl = document.getElementById('qris-poll-error');
                    if (errEl) {
                        errEl.textContent = 'Waktu pembayaran QRIS telah habis. Silakan buat tagihan baru.';
                        errEl.classList.remove('hidden');
                    }
                }
            };

            updateTimer();
            qrisCountdownTimer = setInterval(updateTimer, 1000);
        }

        function startPaidVotePolling() {
            if (qrisPollingTimer) clearInterval(qrisPollingTimer);
            qrisPollingTimer = setInterval(() => {
                checkPaidVoteStatus(false);
            }, 3500);
        }

        async function checkPaidVoteStatusNow() {
            const btn = document.getElementById('check-qris-status-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin w-3.5 h-3.5 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Memeriksa...';
            }
            try {
                await checkPaidVoteStatus(true);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i><span>Periksa Status Pembayaran</span>';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }
        }

        async function checkPaidVoteStatus(isManualClick = false) {
            if (!activeTransactionCode) return;
            const pollErr = document.getElementById('qris-poll-error');

            try {
                const res = await fetch(`/live-display/vote/paid/${encodeURIComponent(activeTransactionCode)}/status`, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (data.status === 'verified') {
                    handlePaidVoteSettled(data);
                } else if (data.status === 'rejected') {
                    stopQrisTimers();
                    if (pollErr) {
                        pollErr.textContent = data.message || 'Transaksi dibatalkan atau kedaluwarsa.';
                        pollErr.classList.remove('hidden');
                    }
                } else if (isManualClick && pollErr) {
                    pollErr.textContent = 'Pembayaran DANA belum diterima sistem. Pastikan transfer via aplikasi DANA telah berhasil.';
                    pollErr.classList.remove('hidden');
                }
            } catch (err) {
                console.error('Polling DANA error:', err);
            }
        }

        function handlePaidVoteSettled(data) {
            const targetReguId = data.regu_id || activeTransactionReguId;
            stopQrisTimers();
            closePhoneModal();
            showToast(data.message, 'success');

            // Update vote counts (desktop + mobile variants)
            const suffixes = ['', '-mobile'];
            suffixes.forEach(sfx => {
                const el = document.getElementById(`vote-count-${targetReguId}${sfx}`);
                if (el) el.textContent = data.new_votes_count;
            });

            // Update total counters
            if (!data.is_vote_count_locked) {
                ['total-vote-counter', 'total-vote-counter-mobile'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el && !isNaN(parseInt(el.textContent))) {
                        el.textContent = parseInt(el.textContent) + (data.votes_added || 0);
                    }
                });
            }

            if (typeof confetti === 'function') {
                confetti({ particleCount: 120, spread: 100, origin: { y: 0.65 }, colors: ['#D97706', '#FBBF24', '#10B981'] });
            }
        }

        async function triggerSandboxSimulate() {
            if (!activeTransactionCode) return;
            const btn = document.getElementById('sandbox-simulate-btn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const pollErr = document.getElementById('qris-poll-error');

            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Mengirim simulasi notifikasi DANA...';
            }

            try {
                const res = await fetch(`/live-display/vote/paid/${encodeURIComponent(activeTransactionCode)}/sandbox-simulate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (data.status === 'verified') {
                    handlePaidVoteSettled(data);
                } else if (pollErr) {
                    pollErr.textContent = data.message || 'Simulasi gagal.';
                    pollErr.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i data-lucide="flask-conical" class="w-3.5 h-3.5"></i><span>Simulasi Terima Pembayaran DANA (Mode Sandbox)</span>';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }
        }

        // ============================================================
        // SUBMIT FREE VOTE (AJAX with voter_name)
        // ============================================================
        async function submitVoteWithName(reguId, namaRegu, voterName) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const errorEl = document.getElementById('phone-error');

            try {
                const res = await fetch(`/live-display/vote/${reguId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ voter_name: voterName }),
                });

                const data = await res.json();

                if (data.success) {
                    userHasVotedFree = true;
                    closePhoneModal();
                    showToast(data.message, 'success');

                    // Update notice on modal and banners for future visits
                    document.getElementById('free-voted-notice')?.classList.remove('hidden');
                    document.getElementById('free-vote-form')?.classList.add('hidden');
                    document.getElementById('desktop-free-voted-badge')?.classList.remove('hidden');
                    document.getElementById('mobile-free-voted-banner')?.classList.remove('hidden');
                    startFreeVoteCountdown(data.next_allowed_at || null);

                    // Update vote counts (desktop + mobile variants)
                    const suffixes = ['', '-mobile'];
                    suffixes.forEach(sfx => {
                        const el = document.getElementById(`vote-count-${reguId}${sfx}`);
                        if (el) el.textContent = data.new_votes_count;
                    });

                    // Update total counters
                    if (!data.is_vote_count_locked) {
                        ['total-vote-counter', 'total-vote-counter-mobile'].forEach(id => {
                            const el = document.getElementById(id);
                            if (el && !isNaN(parseInt(el.textContent))) {
                                el.textContent = parseInt(el.textContent) + 1;
                            }
                        });
                    }

                    // Update card buttons
                    if (paidVotingEnabled) {
                        // Switch all buttons to "Vote DANA"
                        document.querySelectorAll('.vote-btn-action').forEach(btn => {
                            const isMobileBtn = Boolean(btn.closest('#mobile-sec-voting'));
                            btn.disabled = false;
                            if (isMobileBtn) {
                                btn.className = 'vote-btn-action flex-1 py-2.5 px-1.5 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-black text-[10px] uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1';
                                btn.innerHTML = '✨ Vote DANA';
                            } else {
                                btn.className = 'vote-btn-action flex-1 py-2.5 px-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xs uppercase tracking-wider shadow-md transition active:scale-95 flex items-center justify-center gap-1.5';
                                btn.innerHTML = '<i data-lucide="sparkles" class="w-4 h-4 text-amber-300"></i><span>Vote DANA</span>';
                            }
                        });
                    } else {
                        // Disable all vote buttons
                        document.querySelectorAll('.vote-btn-action').forEach(btn => {
                            btn.disabled = true;
                            btn.className = btn.className
                                .replace(/bg-gradient-to-r|from-amber-500|to-amber-600|hover:from-amber-400|hover:to-amber-500/g, '')
                                .trim() + ' bg-gray-800/80 text-gray-400 cursor-not-allowed';
                            btn.innerHTML = '🔒 Sudah Memilih';
                        });
                    }

                    // Highlight chosen cards
                    document.querySelectorAll(`.regu-vote-card[data-regu-id="${reguId}"]`).forEach(card => {
                        card.classList.add('border-amber-400', 'ring-2', 'ring-amber-400/40');
                        const isMobileCard = Boolean(card.closest('#mobile-sec-voting'));
                        const cardBtn = card.querySelector('button');
                        if (cardBtn) {
                            if (isMobileCard) {
                                cardBtn.className = 'flex-1 py-2 px-1.5 rounded-lg bg-amber-500 text-emerald-950 font-black text-[10px] uppercase cursor-pointer flex items-center justify-center gap-1 shadow';
                                cardBtn.innerHTML = '✓ Pilihan Anda';
                            } else {
                                cardBtn.className = 'flex-1 py-2.5 px-2.5 rounded-xl bg-amber-500 text-emerald-950 font-black text-xs uppercase cursor-pointer flex items-center justify-center gap-1 shadow';
                                cardBtn.innerHTML = '✓ Pilihan Anda (Vote Lagi)';
                            }
                            cardBtn.onclick = () => submitVote(reguId, namaRegu, 'paid');
                        }
                    });

                    if (typeof confetti === 'function') {
                        confetti({ particleCount: 70, spread: 90, origin: { y: 0.65 }, colors: ['#D97706', '#FBBF24', '#1B4332'] });
                    }
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                } else {
                    if (data.has_voted_free) {
                        userHasVotedFree = true;
                        document.getElementById('free-voted-notice')?.classList.remove('hidden');
                        document.getElementById('free-vote-form')?.classList.add('hidden');
                        document.getElementById('desktop-free-voted-badge')?.classList.remove('hidden');
                        document.getElementById('mobile-free-voted-banner')?.classList.remove('hidden');
                        startFreeVoteCountdown(data.next_allowed_at || null);
                    }
                    errorEl.textContent = data.message || 'Gagal mengirimkan vote.';
                    errorEl.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
                errorEl.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
                errorEl.classList.remove('hidden');
            }
        }

        // General alias
        function submitVote(reguId, namaRegu, defaultTab = null) {
            openPhoneModal(reguId, namaRegu, defaultTab);
        }

        // ============================================================
        // TOAST
        // ============================================================
        function showToast(message, type = 'success') {
            const el = document.createElement('div');
            el.className = `live-toast ${type === 'success' ? 'bg-emerald-700 text-white border border-emerald-500' : 'bg-red-700 text-white border border-red-500'}`;
            el.textContent = message;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 4500);
        }

        // ============================================================
        // DESKTOP TABS
        // ============================================================
        function setTab(tabName) {
            currentTab = tabName;
            tabs.forEach(t => {
                const view = document.getElementById(`view-${t}`);
                const btn = document.getElementById(`tab-btn-${t}`);
                if (t === tabName) {
                    if (view) view.classList.remove('hidden');
                    if (btn) { btn.classList.add('bg-amber-500', 'text-emerald-950', 'shadow-md'); btn.classList.remove('text-emerald-200', 'text-amber-300'); }
                } else {
                    if (view) view.classList.add('hidden');
                    if (btn) { btn.classList.remove('bg-amber-500', 'text-emerald-950', 'shadow-md'); btn.classList.add('text-emerald-200'); }
                }
            });
            slideRemaining = 15;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function nextTab() { setTab(tabs[(tabs.indexOf(currentTab) + 1) % tabs.length]); }
        function prevTab() { setTab(tabs[(tabs.indexOf(currentTab) - 1 + tabs.length) % tabs.length]); }

        // Auto-slide (desktop only)
        setInterval(() => {
            if (isSlidePaused || window.innerWidth < 1024) return;
            slideRemaining--;
            const timerEl = document.getElementById('slide-timer');
            if (timerEl) timerEl.textContent = `${slideRemaining}s`;
            if (slideRemaining <= 0) nextTab();
        }, 1000);

        // Keyboard
        window.addEventListener('keydown', (e) => {
            if (e.code === 'Escape') {
                closePhoneModal();
                closeShareCardModal();
                return;
            }
            if (document.activeElement && ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;
            if (window.innerWidth < 1024) return;
            if (e.code === 'Space') { e.preventDefault(); isSlidePaused = !isSlidePaused; const t = document.getElementById('slide-timer'); if(t) t.textContent = isSlidePaused ? 'PAUSED' : `${slideRemaining}s`; }
            else if (e.code === 'ArrowRight') nextTab();
            else if (e.code === 'ArrowLeft') prevTab();
        });

        // ============================================================
        // REVEAL CEREMONY
        // ============================================================
        function launchVictoryConfetti() {
            const end = Date.now() + 6000;
            (function frame() {
                confetti({ particleCount: 7, angle: 60, spread: 65, origin: { x: 0, y: 0.8 }, colors: ['#D97706', '#FBBF24', '#1B4332', '#06B6D4', '#ffffff'] });
                confetti({ particleCount: 7, angle: 120, spread: 65, origin: { x: 1, y: 0.8 }, colors: ['#D97706', '#FBBF24', '#1B4332', '#06B6D4', '#ffffff'] });
                if (Date.now() < end) requestAnimationFrame(frame);
            }());
        }

        function triggerRevealCeremony() {
            const overlay = document.getElementById('reveal-countdown-overlay');
            const countdownEl = document.getElementById('countdown-number');
            if (!overlay || !countdownEl) return;
            overlay.classList.remove('hidden');
            let count = 5;
            countdownEl.textContent = count;
            const interval = setInterval(() => {
                count--;
                if (count > 0) {
                    countdownEl.textContent = count;
                    countdownEl.classList.remove('scale-125'); void countdownEl.offsetWidth; countdownEl.classList.add('scale-125');
                } else {
                    clearInterval(interval);
                    countdownEl.textContent = "JUARA!";
                    launchVictoryConfetti();
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                        document.querySelectorAll('.censored-target').forEach(el => el.classList.remove('hidden'));
                        document.querySelectorAll('.sensor-placeholder').forEach(el => el.classList.add('hidden'));
                        isRevealedState = true;
                    }, 1200);
                }
            }, 1000);
        }

        // ============================================================
        // WEBSOCKET
        // ============================================================
        if (typeof Pusher !== 'undefined' && typeof Echo !== 'undefined') {
            const echo = new Echo({
                broadcaster: 'reverb',
                key: '{{ env("REVERB_APP_KEY", "spencerone_key") }}',
                wsHost: window.location.hostname,
                wsPort: {{ env("REVERB_PORT", 8081) }},
                wssPort: {{ env("REVERB_PORT", 8081) }},
                forceTLS: false,
                enabledTransports: ['ws', 'wss'],
            });
            echo.channel('public-live-screen').listen('.RevealChampionEvent', (e) => {
                if (e.isRevealed) { triggerRevealCeremony(); } else {
                    document.querySelectorAll('.censored-target').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.sensor-placeholder').forEach(el => el.classList.remove('hidden'));
                    isRevealedState = false;
                }
            });
        }

        // ============================================================
        // MATRIX SCRAMBLE
        // ============================================================
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        setInterval(() => {
            if (isRevealedState) return;
            document.querySelectorAll('.shuffling-text').forEach(el => {
                let s = '';
                for (let i = 0; i < 10; i++) s += chars[Math.floor(Math.random() * chars.length)];
                el.textContent = s;
            });
        }, 80);

        // ============================================================
        // DOWNLOAD QRIS (PNG) & BUKA APLIKASI DANA
        // ============================================================
        function downloadQrisPng() {
            const qrisImg = document.getElementById('qris-dynamic-img');
            const txCode = document.getElementById('qris-tx-code')?.textContent?.trim() || 'DANA-QRIS';
            const totalText = document.getElementById('qris-total-pay')?.textContent?.trim() || '';
            if (!qrisImg || !qrisImg.src) return;

            const canvas = document.createElement('canvas');
            canvas.width = 800;
            canvas.height = 960;
            const ctx = canvas.getContext('2d');

            // Background putih bersih (penting untuk scanner galeri DANA)
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Header bar biru DANA
            ctx.fillStyle = '#0284C7';
            ctx.fillRect(0, 0, canvas.width, 130);

            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'center';
            ctx.font = 'bold 34px Inter, sans-serif';
            ctx.fillText('PEMBAYARAN DANA QRIS', 400, 62);
            ctx.font = '600 22px Inter, sans-serif';
            ctx.fillStyle = '#E0F2FE';
            ctx.fillText('SPENCERONE CAMP SEASON II • VOTE RESMI', 400, 102);

            const drawAndSave = () => {
                // Draw centered QR code dengan margin lebar agar mudah terbaca Galeri DANA
                ctx.drawImage(qrisImg, 110, 175, 580, 580);

                // Nominal & Kode Transaksi
                ctx.fillStyle = '#0F172A';
                ctx.font = 'bold 42px monospace';
                ctx.fillText(totalText, 400, 825);

                ctx.fillStyle = '#475569';
                ctx.font = '600 20px monospace';
                ctx.fillText('Kode: ' + txCode, 400, 868);

                ctx.fillStyle = '#64748B';
                ctx.font = '500 18px Inter, sans-serif';
                ctx.fillText('Scan lewat aplikasi DANA -> Pindai -> Galeri', 400, 912);

                const pngUrl = canvas.toDataURL('image/png');
                const a = document.createElement('a');
                a.href = pngUrl;
                a.download = `QRIS-DANA-${txCode}.png`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                showToast('Gambar QRIS (PNG) berhasil diunduh! Buka DANA -> Pindai -> pilih dari Galeri.', 'success');
            };

            if (qrisImg.complete && qrisImg.naturalWidth > 0) {
                drawAndSave();
            } else {
                qrisImg.onload = drawAndSave;
            }
        }

        function openDanaApp() {
            const ua = navigator.userAgent || '';
            const isAndroid = /android/i.test(ua);
            const isIOS = /iPad|iPhone|iPod/i.test(ua);

            if (isAndroid) {
                showToast('Membuka aplikasi DANA... Pilih Pindai -> Galeri jika ingin scan QRIS yang diunduh.', 'success');
                window.location.href = 'intent://#Intent;package=id.dana;scheme=dana;S.browser_fallback_url=https%3A%2F%2Flink.dana.id%2F;end';
            } else if (isIOS) {
                showToast('Membuka aplikasi DANA...', 'success');
                window.location.href = 'dana://';
                setTimeout(() => {
                    window.open('https://link.dana.id/', '_blank');
                }, 1200);
            } else {
                showToast('Di komputer: Unduh QRIS PNG lalu scan memakai HP di aplikasi DANA.', 'success');
                window.open('https://link.dana.id/', '_blank');
            }
        }

        // ============================================================
        // SHARE CARD GENERATOR (MASKOT, LOGO & QR MENU VOTING REGU)
        // ============================================================
        const votingRegusMap = @json(collect($votingRegus)->keyBy('id'));
        const isVotingClosedGlobal = {{ ($is_voting_closed ?? false) ? 'true' : 'false' }};
        const appLogoUrl = "{{ asset('images/season2/logo-season2.jpg') }}";
        const appMascotUrl = "{{ asset('images/season2/mascot-duo.png') }}";
        const storageBaseUrl = "{{ asset('storage') }}";
        let currentShareRegu = null;
        let currentShareDataUrl = null;
        let currentShareBlob = null;

        function loadImageSafe(src) {
            return new Promise((resolve) => {
                if (!src) return resolve(null);
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => resolve(img);
                img.onerror = () => resolve(null);
                img.src = src;
            });
        }

        function drawRoundedRect(ctx, x, y, w, h, r) {
            ctx.beginPath();
            ctx.moveTo(x + r, y);
            ctx.arcTo(x + w, y, x + w, y + h, r);
            ctx.arcTo(x + w, y + h, x, y + h, r);
            ctx.arcTo(x, y + h, x, y, r);
            ctx.arcTo(x, y, x + w, y, r);
            ctx.closePath();
        }

        async function openShareCardModal(reguId) {
            const regu = votingRegusMap[reguId];
            if (!regu) return;

            currentShareRegu = regu;
            currentShareDataUrl = null;
            currentShareBlob = null;

            document.getElementById('share-modal-regu-name').textContent = regu.nama_regu;
            document.getElementById('share-regu-url-input').value = regu.share_url || window.location.href;
            document.getElementById('share-card-preview-img').classList.add('hidden');
            document.getElementById('share-card-loading').classList.remove('hidden');
            document.getElementById('share-card-modal').classList.add('open');
            if (typeof lucide !== 'undefined') lucide.createIcons();

            await renderShareCardPoster(regu);
        }

        function closeShareCardModal() {
            document.getElementById('share-card-modal').classList.remove('open');
            currentShareRegu = null;
        }

        document.getElementById('share-card-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeShareCardModal();
        });

        async function renderShareCardPoster(regu) {
            const canvas = document.getElementById('share-card-canvas');
            const ctx = canvas.getContext('2d');
            const W = 1080;
            const H = 1350;

            const reguPhotoUrl = regu.foto_regu_path ? `${storageBaseUrl}/${regu.foto_regu_path}` : null;
            const [logoImg, mascotImg, qrImg, photoImg] = await Promise.all([
                loadImageSafe(appLogoUrl),
                loadImageSafe(appMascotUrl),
                loadImageSafe(regu.share_qr),
                loadImageSafe(reguPhotoUrl),
            ]);

            // 1. Background Deep Forest Gradient
            const bgGrad = ctx.createLinearGradient(0, 0, W, H);
            bgGrad.addColorStop(0, '#071B11');
            bgGrad.addColorStop(0.5, '#123724');
            bgGrad.addColorStop(1, '#091526');
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, W, H);

            // Subtle radial spotlight behind mascot
            const glow = ctx.createRadialGradient(W * 0.72, 390, 40, W * 0.72, 390, 420);
            glow.addColorStop(0, 'rgba(251, 191, 36, 0.25)');
            glow.addColorStop(1, 'rgba(251, 191, 36, 0)');
            ctx.fillStyle = glow;
            ctx.fillRect(0, 0, W, H);

            // Outer Gold Frame
            ctx.strokeStyle = 'rgba(251, 191, 36, 0.55)';
            ctx.lineWidth = 8;
            drawRoundedRect(ctx, 28, 28, W - 56, H - 56, 42);
            ctx.stroke();

            // 2. Top Header: Logo + Judul Kegiatan
            if (logoImg) {
                ctx.save();
                drawRoundedRect(ctx, 72, 68, 128, 128, 28);
                ctx.clip();
                ctx.drawImage(logoImg, 72, 68, 128, 128);
                ctx.restore();
                ctx.strokeStyle = '#FBBF24';
                ctx.lineWidth = 5;
                drawRoundedRect(ctx, 72, 68, 128, 128, 28);
                ctx.stroke();
            }

            ctx.textAlign = 'left';
            ctx.fillStyle = '#FBBF24';
            ctx.font = '800 26px Inter, sans-serif';
            ctx.fillText('VOTING REGU TERFAVORIT', 226, 108);

            ctx.fillStyle = '#FFFFFF';
            ctx.font = '900 44px Inter, sans-serif';
            ctx.fillText('SPENCERONE CAMP', 226, 158);
            ctx.fillStyle = '#6EE7B7';
            ctx.font = '800 28px Inter, sans-serif';
            ctx.fillText('SEASON II • GUDEP 03.059 - 03.060', 226, 194);

            // Divider line
            ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
            ctx.fillRect(72, 224, W - 144, 3);

            // 3. Hero Section: Foto Regu / Identitas di Kiri + Maskot Resmi di Kanan
            if (photoImg) {
                ctx.save();
                drawRoundedRect(ctx, 72, 265, 450, 340, 32);
                ctx.clip();
                // Cover crop
                const scale = Math.max(450 / photoImg.width, 340 / photoImg.height);
                const dw = photoImg.width * scale;
                const dh = photoImg.height * scale;
                ctx.drawImage(photoImg, 72 + (450 - dw) / 2, 265 + (340 - dh) / 2, dw, dh);
                ctx.restore();
                ctx.strokeStyle = 'rgba(251, 191, 36, 0.7)';
                ctx.lineWidth = 5;
                drawRoundedRect(ctx, 72, 265, 450, 340, 32);
                ctx.stroke();
            } else {
                ctx.fillStyle = 'rgba(0, 0, 0, 0.35)';
                drawRoundedRect(ctx, 72, 265, 450, 340, 32);
                ctx.fill();
                ctx.strokeStyle = 'rgba(16, 185, 129, 0.4)';
                ctx.lineWidth = 4;
                ctx.stroke();
                ctx.textAlign = 'center';
                ctx.fillStyle = '#6EE7B7';
                ctx.font = '800 32px Inter, sans-serif';
                ctx.fillText('REGU #' + regu.nomor_regu, 297, 445);
            }

            // Maskot Resmi di kanan
            if (mascotImg) {
                const maxMW = 470;
                const maxMH = 430;
                const mScale = Math.min(maxMW / mascotImg.width, maxMH / mascotImg.height);
                const mw = mascotImg.width * mScale;
                const mh = mascotImg.height * mScale;
                ctx.drawImage(mascotImg, 540 + (maxMW - mw) / 2, 230 + (maxMH - mh) / 2, mw, mh);
            }

            // 4. Nama Regu Banner
            ctx.textAlign = 'center';
            const badgeColor = regu.jenis === 'putra' ? '#1D4ED8' : '#DB2777';
            ctx.fillStyle = badgeColor;
            drawRoundedRect(ctx, W / 2 - 220, 640, 440, 52, 26);
            ctx.fill();
            ctx.fillStyle = '#FFFFFF';
            ctx.font = '900 24px Inter, sans-serif';
            ctx.fillText(`${String(regu.jenis || '').toUpperCase()} • REGU #${regu.nomor_regu} • ${regu.nomor_kavling || ''}`, W / 2, 675);

            // Regu Name (auto-fit font size)
            const reguTitle = String(regu.nama_regu || 'REGU').toUpperCase();
            let fontSize = 68;
            ctx.font = `900 ${fontSize}px Inter, sans-serif`;
            while (ctx.measureText(reguTitle).width > W - 140 && fontSize > 34) {
                fontSize -= 4;
                ctx.font = `900 ${fontSize}px Inter, sans-serif`;
            }
            ctx.fillStyle = '#FBBF24';
            ctx.fillText(reguTitle, W / 2, 765);

            ctx.fillStyle = '#D1FAE5';
            ctx.font = '700 28px Inter, sans-serif';
            ctx.fillText('Ayo dukung kami menjadi Regu Terfavorit!', W / 2, 815);

            // 5. White QR Code Callout Card
            ctx.fillStyle = '#FFFFFF';
            drawRoundedRect(ctx, 72, 860, W - 144, 340, 36);
            ctx.fill();

            if (qrImg) {
                ctx.drawImage(qrImg, 108, 892, 276, 276);
            }

            ctx.textAlign = 'left';
            ctx.fillStyle = '#065F46';
            ctx.font = '900 38px Inter, sans-serif';
            ctx.fillText('SCAN QR INI', 416, 958);
            ctx.fillText('UNTUK VOTE!', 416, 1006);

            ctx.fillStyle = '#334155';
            ctx.font = '700 25px Inter, sans-serif';
            ctx.fillText('• Vote Gratis Harian (1x/hari)', 416, 1064);
            ctx.fillText('• Vote DANA QRIS (Mulai Rp 1)', 416, 1104);

            ctx.fillStyle = '#0284C7';
            ctx.font = '800 22px Inter, sans-serif';
            ctx.fillText('Arahkan kamera HP atau Scanner QR', 416, 1158);

            // 6. Footer
            ctx.textAlign = 'center';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.65)';
            ctx.font = '600 22px Inter, sans-serif';
            ctx.fillText('SMPN 1 CERBON • SPENCERONE CAMP SEASON II', W / 2, 1272);

            currentShareDataUrl = canvas.toDataURL('image/png');
            canvas.toBlob((blob) => { currentShareBlob = blob; }, 'image/png');

            const previewImg = document.getElementById('share-card-preview-img');
            previewImg.src = currentShareDataUrl;
            document.getElementById('share-card-loading').classList.add('hidden');
            previewImg.classList.remove('hidden');
        }

        function downloadShareCardPng() {
            if (!currentShareDataUrl || !currentShareRegu) return;
            const slug = String(currentShareRegu.nama_regu || 'regu').toLowerCase().replace(/[^a-z0-9]+/g, '-');
            const a = document.createElement('a');
            a.href = currentShareDataUrl;
            a.download = `Poster-Vote-${slug}.png`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            showToast('Poster dukungan regu berhasil diunduh!', 'success');
        }

        function copyShareReguUrl() {
            const input = document.getElementById('share-regu-url-input');
            const btn = document.getElementById('btn-copy-share-url');
            if (!input) return;
            navigator.clipboard.writeText(input.value).then(() => {
                if (btn) {
                    btn.textContent = 'Tersalin!';
                    setTimeout(() => { btn.textContent = 'Salin Link'; }, 2000);
                }
                showToast('Link voting regu disalin!', 'success');
            });
        }

        async function shareReguCard() {
            if (!currentShareRegu) return;
            const shareUrl = currentShareRegu.share_url;
            const shareTitle = `Dukung ${currentShareRegu.nama_regu} - Spencerone Camp Season II`;
            const shareText = `Ayo vote ${currentShareRegu.nama_regu} menjadi Regu Terfavorit di Spencerone Camp Season II! Klik link berikut:`;

            if (navigator.share) {
                try {
                    const sharePayload = { title: shareTitle, text: shareText, url: shareUrl };
                    if (currentShareBlob && navigator.canShare) {
                        const file = new File([currentShareBlob], `poster-vote-${currentShareRegu.id}.png`, { type: 'image/png' });
                        if (navigator.canShare({ files: [file] })) {
                            sharePayload.files = [file];
                        }
                    }
                    await navigator.share(sharePayload);
                    return;
                } catch (err) {
                    if (err && err.name === 'AbortError') return;
                }
            }

            // Fallback WhatsApp Share jika browser desktop tidak support Web Share API
            const waUrl = `https://wa.me/?text=${encodeURIComponent(shareText + ' ' + shareUrl)}`;
            window.open(waUrl, '_blank');
        }

        // ============================================================
        // INIT & DEEP-LINK ?tab=voting&vote_regu=ID HANDLER
        // ============================================================
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();

            if (userHasVotedFree) {
                startFreeVoteCountdown(nextFreeVoteAtIso);
            }

            const urlParams = new URLSearchParams(window.location.search);
            const targetTab = urlParams.get('tab');
            const voteReguId = urlParams.get('vote_regu');

            if (targetTab === 'voting' || voteReguId) {
                isSlidePaused = true;
                if (typeof setTab === 'function') setTab('voting');

                const mobileSec = document.getElementById('mobile-sec-voting');
                if (mobileSec && window.innerWidth < 1024) {
                    setTimeout(() => mobileSec.scrollIntoView({ behavior: 'smooth', block: 'start' }), 200);
                }

                if (voteReguId && votingRegusMap[voteReguId]) {
                    const targetRegu = votingRegusMap[voteReguId];
                    document.querySelectorAll(`.regu-vote-card[data-regu-id="${voteReguId}"]`).forEach(card => {
                        card.classList.add('ring-4', 'ring-amber-400', 'border-amber-400');
                    });

                    if (!isVotingClosedGlobal) {
                        setTimeout(() => openPhoneModal(targetRegu.id, targetRegu.nama_regu), 350);
                    }
                }
            } else if (targetTab && typeof tabs !== 'undefined' && tabs.includes(targetTab)) {
                isSlidePaused = true;
                if (typeof setTab === 'function') setTab(targetTab);
                if (targetTab === 'quiz' && window.innerWidth < 1024) {
                    const mobileQuiz = document.getElementById('mobile-sec-quiz');
                    if (mobileQuiz) setTimeout(() => mobileQuiz.scrollIntoView({ behavior: 'smooth', block: 'start' }), 200);
                }
            }
        });
    </script>
</body>
</html>