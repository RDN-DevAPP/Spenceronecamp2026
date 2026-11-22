@extends('layouts.main')

@section('title', 'Data & Pengaturan Voting Regu Terfavorit - Admin')

@section('content')
@php
    $initialTab = request('tab', 'settings');
    if (!in_array($initialTab, ['settings', 'leaderboard', 'transactions', 'history'], true)) {
        $initialTab = 'settings';
    }
@endphp

<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-pink-100 text-pink-900 mb-2">
                    <i data-lucide="heart" class="w-3.5 h-3.5 text-pink-700"></i>
                    <span>Manajemen Voting Publik &amp; Pakasir QRIS</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Data &amp; Pengaturan Voting Regu</h1>
                <p class="text-sm text-gray-500 mt-1">Buka/tutup pemungutan suara, atur tarif vote Pakasir QRIS, pantau transaksi lunas otomatis, dan kelola reset voting gratis.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('live-display') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 text-white text-sm font-bold hover:bg-emerald-800 shadow-sm transition">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    Buka Live Display
                </a>
            </div>
        </div>

        {{-- Flash Notification & Validation Errors --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-sm flex items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 font-semibold text-sm flex items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                    <span>Terdapat kesalahan pengisian:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 pl-6">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- QUICK CONTROL BAR (STATUS VOTING & TARIF QRIS) --}}
        <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-emerald-900 rounded-3xl p-5 sm:p-6 text-white shadow-xl mb-8 border border-emerald-700/50">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-500 text-emerald-950 mb-2">
                        KONTROL UTAMA
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5" style="color: #FFFFFF;">
                        Status Voting &amp; Tarif QRIS
                    </h2>
                    <p class="text-xs text-emerald-200/80 mt-1 max-w-xl">
                        Atur status buka/tutup voting dan tarif suara Pakasir QRIS langsung dari panel ini. Perubahan akan langsung aktif di halaman publik.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @if($isCurrentlyClosed)
                        <div class="px-4 py-2 rounded-2xl bg-red-500/20 border border-red-500/50 text-red-300 flex items-center gap-2 font-black text-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
                            <span>VOTING DITUTUP</span>
                        </div>
                    @else
                        <div class="px-4 py-2 rounded-2xl bg-emerald-500/20 border border-emerald-400/50 text-emerald-300 flex items-center gap-2 font-black text-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>VOTING DIBUKA (AKTIF)</span>
                        </div>
                    @endif

                    @if($paidVotingEnabled && $danaConfigured)
                        <div class="px-4 py-2 rounded-2xl bg-sky-500/20 border border-sky-400/50 text-sky-200 flex items-center gap-2 font-black text-xs font-mono">
                            <i data-lucide="qr-code" class="w-4 h-4"></i>
                            <span>DANA QRIS {{ $danaSandboxMode ? '(Sandbox)' : '' }}: Rp {{ number_format($paidVotePrice, 0, ',', '.') }}/suara</span>
                        </div>
                    @elseif($paidVotingEnabled && !$danaConfigured)
                        <div class="px-4 py-2 rounded-2xl bg-amber-500/20 border border-amber-500/60 text-amber-200 flex items-center gap-2 font-bold text-xs">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-400"></i>
                            <span>QRIS DANA Belum Diatur</span>
                        </div>
                    @else
                        <div class="px-4 py-2 rounded-2xl bg-gray-800 text-gray-400 border border-gray-700 flex items-center gap-2 font-bold text-xs">
                            <i data-lucide="ban" class="w-4 h-4"></i>
                            <span>DANA Nonaktif</span>
                        </div>
                    @endif

                    <button type="button" onclick="switchVotingTab('settings'); document.getElementById('settings-card').scrollIntoView({behavior: 'smooth'})"
                            class="px-4 py-2.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-emerald-950 font-black text-xs uppercase tracking-wider transition shadow-lg flex items-center gap-1.5 active:scale-95">
                        <i data-lucide="sliders" class="w-4 h-4"></i>
                        <span>Ubah Pengaturan</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- 4 Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Suara Masuk</span>
                    <span class="p-2 rounded-xl bg-emerald-50 text-emerald-700"><i data-lucide="heart" class="w-4 h-4"></i></span>
                </div>
                <div class="text-3xl font-black text-brand-forest">{{ number_format($totalVotes) }}</div>
                <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-gray-500">
                    <span class="inline-flex items-center gap-1 text-emerald-700"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ number_format($freeVotesCount) }} Gratis</span>
                    <span>&bull;</span>
                    <span class="inline-flex items-center gap-1 text-amber-700"><span class="w-2 h-2 rounded-full bg-amber-500"></span>{{ number_format($paidVotesCount) }} Berbayar</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pemasukan QRIS</span>
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-700"><i data-lucide="qr-code" class="w-4 h-4"></i></span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-amber-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>100% Masuk ke Regu (Tanpa Potongan)</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Transaksi QRIS</span>
                    <span class="p-2 rounded-xl bg-blue-50 text-blue-700"><i data-lucide="receipt" class="w-4 h-4"></i></span>
                </div>
                <div class="text-3xl font-black text-blue-700">{{ $transactions->total() }}</div>
                <div class="mt-2 text-xs text-gray-500 font-medium">
                    @if($pendingTransactionsCount > 0)
                        <span class="text-amber-600 font-bold">{{ $pendingTransactionsCount }} menunggu pembayaran</span>
                    @else
                        Semua pembayaran tercatat rapi
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Regu Berpartisipasi</span>
                    <span class="p-2 rounded-xl bg-purple-50 text-purple-700"><i data-lucide="users" class="w-4 h-4"></i></span>
                </div>
                <div class="text-3xl font-black text-purple-700">{{ $summary->count() }}</div>
                <div class="mt-2 text-xs text-gray-500 font-medium">Regu aktif di voting publik</div>
            </div>
        </div>

        {{-- Navigation Tabs (Vanilla JS, 100% Bebas Konflik Vue/Alpine) --}}
        <div class="flex items-center border-b border-gray-200 mb-6 gap-2 sm:gap-4 overflow-x-auto pb-1">
            <button type="button" id="tab-btn-settings" onclick="switchVotingTab('settings')"
                    class="voting-tab-btn py-3 px-3 sm:px-4 border-b-2 text-sm flex items-center gap-2 whitespace-nowrap transition cursor-pointer {{ $initialTab === 'settings' ? 'border-emerald-700 text-emerald-800 font-black' : 'border-transparent text-gray-500 hover:text-gray-800 font-medium' }}">
                <i data-lucide="sliders" class="w-4 h-4"></i>
                <span>Pengaturan Voting &amp; QRIS</span>
            </button>

            <button type="button" id="tab-btn-leaderboard" onclick="switchVotingTab('leaderboard')"
                    class="voting-tab-btn py-3 px-3 sm:px-4 border-b-2 text-sm flex items-center gap-2 whitespace-nowrap transition cursor-pointer {{ $initialTab === 'leaderboard' ? 'border-emerald-700 text-emerald-800 font-black' : 'border-transparent text-gray-500 hover:text-gray-800 font-medium' }}">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                <span>Perolehan Suara Regu</span>
            </button>

            <button type="button" id="tab-btn-transactions" onclick="switchVotingTab('transactions')"
                    class="voting-tab-btn py-3 px-3 sm:px-4 border-b-2 text-sm flex items-center gap-2 whitespace-nowrap transition cursor-pointer {{ $initialTab === 'transactions' ? 'border-emerald-700 text-emerald-800 font-black' : 'border-transparent text-gray-500 hover:text-gray-800 font-medium' }}">
                <i data-lucide="receipt" class="w-4 h-4"></i>
                <span>Riwayat Transaksi QRIS</span>
                @if($pendingTransactionsCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white">
                        {{ $pendingTransactionsCount }}
                    </span>
                @endif
            </button>

            <button type="button" id="tab-btn-history" onclick="switchVotingTab('history')"
                    class="voting-tab-btn py-3 px-3 sm:px-4 border-b-2 text-sm flex items-center gap-2 whitespace-nowrap transition cursor-pointer {{ $initialTab === 'history' ? 'border-emerald-700 text-emerald-800 font-black' : 'border-transparent text-gray-500 hover:text-gray-800 font-medium' }}">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Riwayat Log Seluruh Suara</span>
                <span class="text-xs text-gray-400 font-mono">({{ number_format($totalVotes) }})</span>
            </button>
        </div>

        {{-- TAB 1: PENGATURAN VOTING & QRIS --}}
        <div id="tab-panel-settings" class="voting-tab-panel space-y-8 {{ $initialTab === 'settings' ? '' : 'hidden' }}">
            <div id="settings-card"></div>

            <form method="POST" action="{{ route('admin.voting.update-settings') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- PANEL 1: STATUS PEMUNGUTAN SUARA (BUKA / TUTUP) --}}
                <div class="bg-white rounded-3xl border-2 {{ $isCurrentlyClosed ? 'border-red-300' : 'border-emerald-300' }} shadow-sm p-6 sm:p-7 relative overflow-hidden">
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-100">
                        <div class="w-11 h-11 rounded-2xl {{ $isCurrentlyClosed ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }} flex items-center justify-center shrink-0">
                            <i data-lucide="power" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-gray-900 text-lg">Status Pemungutan Suara (Buka / Tutup)</h2>
                            <p class="text-xs text-gray-500">Tentukan apakah voting saat ini aktif menerima suara atau ditutup oleh panitia.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition select-none
                            {{ !$isVotingLocked ? 'border-emerald-500 bg-emerald-50/60 ring-2 ring-emerald-500/20' : 'border-gray-200 bg-white hover:bg-gray-50' }}">
                            <input type="radio" name="voting_status" value="open" {{ !$isVotingLocked ? 'checked' : '' }}
                                   class="w-5 h-5 text-emerald-600 focus:ring-emerald-500">
                            <div class="ml-3.5">
                                <span class="block font-black text-sm text-gray-900 flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    Buka Pemungutan Suara (Aktif)
                                </span>
                                <span class="block text-xs text-gray-500 mt-0.5">Pengunjung dan peserta dapat memberikan suara (gratis &amp; QRIS).</span>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 rounded-2xl border-2 cursor-pointer transition select-none
                            {{ $isVotingLocked ? 'border-red-500 bg-red-50/60 ring-2 ring-red-500/20' : 'border-gray-200 bg-white hover:bg-gray-50' }}">
                            <input type="radio" name="voting_status" value="closed" {{ $isVotingLocked ? 'checked' : '' }}
                                   class="w-5 h-5 text-red-600 focus:ring-red-500">
                            <div class="ml-3.5">
                                <span class="block font-black text-sm text-gray-900 flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                    Tutup Pemungutan Suara (Selesai)
                                </span>
                                <span class="block text-xs text-gray-500 mt-0.5">Tombol voting dinonaktifkan di live-display publik.</span>
                            </div>
                        </label>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 text-xs text-gray-600 space-y-2">
                        <label class="block font-bold text-gray-700">Batas Waktu Penutupan Otomatis (Opsional):</label>
                        <input type="datetime-local" name="voting_end_at"
                               value="{{ $votingEndAt ? \Carbon\Carbon::parse($votingEndAt)->format('Y-m-d\TH:i') : '' }}"
                               class="w-full sm:w-80 px-3.5 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-white font-mono">
                        <p class="text-[11px] text-gray-500">Kosongkan jika ingin membuka/menutup voting secara manual tanpa batas waktu.</p>
                    </div>
                </div>

                {{-- PANEL 2: PENGATURAN VOTING BERBAYAR KHUSUS DANA (QRIS DANA, MIN RP 1, OTOMATIS TERVERIFIKASI) --}}
                <div class="bg-white rounded-3xl border-2 border-sky-300 shadow-sm p-6 sm:p-7 relative overflow-hidden">
                    <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-sky-100">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                                <i data-lucide="qr-code" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h2 class="font-black text-gray-900 text-lg">Pembayaran DANA Saja (QRIS DANA &bull; Minimal 1 Vote = Rp 1)</h2>
                                <p class="text-xs text-gray-500">Khusus pembayaran DANA tanpa potongan biaya dan tanpa batas minimal Rp 500/Rp 1.000. Suara otomatis masuk begitu dana diterima DANA.</p>
                            </div>
                        </div>
                        @if($danaConfigured)
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0">
                                ✓ DANA Siap {{ $danaSandboxMode ? '(Sandbox)' : '(Live)' }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-red-100 text-red-800 border border-red-300 shrink-0">
                                ⚠ QRIS DANA Kosong
                            </span>
                        @endif
                    </div>

                    <div class="p-4 mb-6 rounded-2xl bg-gradient-to-r from-sky-500/10 via-emerald-500/10 to-sky-500/10 border border-sky-300 flex items-start gap-3">
                        <i data-lucide="shield-check" class="w-6 h-6 text-sky-700 shrink-0 mt-0.5"></i>
                        <div class="text-xs text-gray-800 leading-relaxed">
                            <strong class="font-black text-sky-900 block text-sm mb-0.5">QRIS Dinamis DANA Mulai Rp 1 &amp; Verifikasi Otomatis:</strong>
                            Unggah gambar QRIS dari aplikasi <strong>DANA</strong> (atau tempel string QRIS-nya). Website otomatis mengubah QRIS menjadi <strong>QRIS Dinamis</strong> dengan nominal tepat (bisa <strong>Rp 1</strong>!) sehingga saat user scan di aplikasi DANA, nominal langsung terisi otomatis. Suara <strong>tidak masuk</strong> saat user klik tombol — suara baru terhitung otomatis ketika webhook notifikasi saldo masuk DANA diterima server!
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 space-y-2">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="paid_voting_enabled" value="1" {{ $paidVotingEnabled ? 'checked' : '' }}
                                       class="w-5 h-5 text-sky-600 rounded border-gray-300 focus:ring-sky-500">
                                <span class="font-black text-sm text-gray-900">Aktifkan Fitur Vote Berbayar DANA</span>
                            </label>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Jika dicentang, pemilih dapat memilih tab "Vote DANA" di live-display untuk membayar menggunakan aplikasi DANA.
                            </p>
                        </div>

                        <div class="bg-purple-50/60 rounded-2xl p-4 border border-purple-200 space-y-2">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="dana_sandbox_mode" value="1" {{ $danaSandboxMode ? 'checked' : '' }}
                                       class="w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                <span class="font-black text-sm text-gray-900">Mode Sandbox (Simulasi Notifikasi DANA)</span>
                            </label>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Aktifkan <strong>hanya saat uji coba</strong> untuk memunculkan tombol simulasi notifikasi DANA masuk. Matikan saat acara berlangsung!
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                                Biaya Pembayaran per 1 Suara (Min Rp 1):
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-black text-gray-500">Rp</span>
                                <input type="number" name="paid_vote_price" value="{{ (int) $paidVotePrice }}" min="1" step="1" required
                                       class="w-full pl-11 pr-3.5 py-3 text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-sky-500 font-black text-sky-700 bg-white shadow-sm">
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1.5">Bisa diisi <strong>1</strong> (1 vote = Rp 1), 100, 500, 1.000, dll. Tanpa potongan biaya.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                                Minimal Pembelian Suara per Transaksi:
                            </label>
                            <input type="number" name="paid_vote_min" value="{{ $paidVoteMin }}" min="1" max="1000" required
                                   class="w-full px-3.5 py-3 text-base rounded-xl border border-gray-300 focus:ring-2 focus:ring-sky-500 font-black text-gray-800 bg-white shadow-sm">
                            <p class="text-[11px] text-gray-500 mt-1.5">Default: 1 suara (Rp {{ number_format($paidVotePrice, 0, ',', '.') }}).</p>
                        </div>
                    </div>

                    {{-- Pengaturan QRIS DANA & Webhook Notifikasi DANA --}}
                    <div class="p-5 bg-sky-50/50 rounded-2xl border border-sky-200 space-y-5">
                        <div class="flex items-center gap-2 text-sm font-black text-gray-900">
                            <i data-lucide="smartphone" class="w-4 h-4 text-sky-700"></i>
                            <span>Pengaturan Akun &amp; QRIS DANA Panitia</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Akun / Merchant DANA:</label>
                                <input type="text" name="dana_account_name" value="{{ $danaAccountName }}"
                                       placeholder="Contoh: DANA Spencerone Camp"
                                       class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-sky-500 bg-white font-bold">
                            </div>

                            <div class="flex items-center bg-white p-3.5 rounded-xl border border-sky-200/80">
                                <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                    <input type="checkbox" name="dana_unique_code_enabled" value="1" {{ $danaUniqueCodeEnabled ? 'checked' : '' }}
                                           class="w-4 h-4 mt-0.5 text-sky-600 rounded border-gray-300 focus:ring-sky-500">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-900">Tambahkan Kode Unik Acak (+Rp 1–99)</span>
                                        <span class="block text-[11px] text-gray-500">Nonaktifkan jika ingin 1 vote tepat Rp 1 persis tanpa tambahan kode unik.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                            <div class="md:col-span-2 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Upload Gambar QRIS dari Aplikasi DANA (JPG / PNG):</label>
                                    <input type="file" name="dana_qris_image" id="dana-qris-file-input" accept="image/*" onchange="extractDanaQrisFromImage(this)"
                                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-600 file:text-white hover:file:bg-sky-700 cursor-pointer bg-white rounded-xl border border-gray-300">
                                    <p id="qris-extract-status" class="text-[11px] text-gray-500 mt-1">
                                        Saat Anda memilih gambar QRIS DANA, sistem otomatis membaca kode QRIS-nya agar dapat menghasilkan nominal dinamis (mulai Rp 1).
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Payload / String QRIS DANA (Terisi Otomatis atau Tempel Manual):</label>
                                    <textarea name="dana_qris_payload" id="dana-qris-payload-input" rows="2"
                                              placeholder="000201010211..."
                                              class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-gray-300 focus:ring-2 focus:ring-sky-500 bg-white">{{ $danaQrisPayload }}</textarea>
                                </div>
                            </div>

                            <div class="text-center p-3 bg-white rounded-2xl border border-gray-200">
                                @if(!empty($danaQrisImagePath))
                                    <div class="w-32 h-32 mx-auto overflow-hidden bg-white p-1 rounded-xl border border-gray-100 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $danaQrisImagePath) }}" alt="QRIS DANA" class="max-w-full max-h-full object-contain">
                                    </div>
                                    <span class="text-[11px] font-bold text-emerald-700 block mt-1.5">✓ QRIS DANA Tersimpan</span>
                                @elseif(!empty($danaQrisPayload))
                                    <div class="w-32 h-32 mx-auto bg-emerald-50 rounded-xl flex flex-col items-center justify-center text-emerald-700 p-2 text-center border border-emerald-200">
                                        <i data-lucide="qr-code" class="w-10 h-10 mb-1"></i>
                                        <span class="text-[10px] font-bold">Payload QRIS Aktif</span>
                                    </div>
                                @else
                                    <div class="w-32 h-32 mx-auto bg-gray-100 rounded-xl flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                        <i data-lucide="qr-code" class="w-8 h-8 opacity-40 mb-1"></i>
                                        <span class="text-[10px]">Belum ada QRIS DANA</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Webhook Otomatis Notifikasi HP DANA --}}
                        <div class="bg-white p-4 rounded-xl border border-sky-200/80 space-y-3">
                            <div class="text-xs font-black text-gray-900 flex items-center gap-1.5">
                                <i data-lucide="webhook" class="w-4 h-4 text-sky-700"></i>
                                <span>Webhook Notifikasi Otomatis DANA (MacroDroid / Tasker Android)</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1">URL Webhook DANA:</label>
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" readonly value="{{ url('/api/dana/webhook') }}" id="dana-webhook-url"
                                               class="flex-1 px-3 py-2 text-xs font-mono font-bold text-sky-900 bg-sky-50/70 rounded-lg border border-sky-200 outline-none">
                                        <button type="button" id="btn-copy-webhook" onclick="copyTextById('dana-webhook-url', 'btn-copy-webhook')"
                                                class="px-3 py-2 rounded-lg bg-sky-700 hover:bg-sky-800 text-white text-xs font-bold transition shrink-0">
                                            Salin
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1">Webhook Secret Token:</label>
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" name="dana_webhook_secret" value="{{ $danaWebhookSecret }}" id="dana-webhook-secret"
                                               class="flex-1 px-3 py-2 text-xs font-mono font-bold text-gray-800 bg-gray-50 rounded-lg border border-gray-300 outline-none">
                                        <button type="button" id="btn-copy-secret" onclick="copyTextById('dana-webhook-secret', 'btn-copy-secret')"
                                                class="px-3 py-2 rounded-lg bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold transition shrink-0">
                                            Salin
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 rounded-lg bg-slate-900 text-slate-100 text-[11px] font-mono space-y-1">
                                <div class="text-sky-300 font-bold">Panduan Koneksi HP DANA Otomatis (MacroDroid):</div>
                                <div>1. Trigger: <strong>Notification Received</strong> &rarr; Aplikasi: <strong>DANA</strong> (atau DANA Bisnis).</div>
                                <div>2. Action: <strong>HTTP Request (POST)</strong> &rarr; URL: <code>{{ url('/api/dana/webhook') }}</code></div>
                                <div>3. Body JSON: <code>{"secret": "{{ $danaWebhookSecret }}", "message": "{not_ticker}"}</code></div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Petunjuk Pembayaran DANA untuk Voter:</label>
                            <input type="text" name="paid_vote_instructions" value="{{ $paidVoteInstructions }}"
                                   placeholder="Contoh: Scan QRIS menggunakan aplikasi DANA Anda. Suara otomatis terhitung begitu pembayaran berhasil diterima DANA."
                                   class="w-full px-3.5 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-sky-500 bg-white font-medium">
                        </div>
                    </div>
                </div>

                {{-- PANEL 3: RESET VOTING GRATIS HARIAN --}}
                <div class="bg-white rounded-3xl border-2 border-purple-200 shadow-sm p-6 sm:p-7 relative overflow-hidden">
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-purple-100">
                        <div class="w-11 h-11 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                            <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-gray-900 text-lg">Pengaturan Reset Voting Gratis Harian</h2>
                            <p class="text-xs text-gray-500">Atur siklus otomatis reset hak suara gratis voter (1 voter = 1 suara gratis per hari).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                                Mode Reset Kuota Vote Gratis:
                            </label>
                            <select name="free_vote_reset_mode" id="free_vote_reset_mode"
                                    onchange="document.getElementById('interval-hours-wrapper').style.display = this.value === 'interval' ? 'block' : 'none'"
                                    class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 font-bold bg-white">
                                <option value="daily" {{ $freeVoteResetMode === 'daily' ? 'selected' : '' }}>
                                    Reset Harian (Setiap Hari Pukul 00:00 WITA) - Default
                                </option>
                                <option value="interval" {{ $freeVoteResetMode === 'interval' ? 'selected' : '' }}>
                                    Reset Tiap Interval Jam
                                </option>
                                <option value="once" {{ $freeVoteResetMode === 'once' ? 'selected' : '' }}>
                                    Sekali Seumur Kegiatan
                                </option>
                            </select>
                        </div>

                        <div id="interval-hours-wrapper" style="{{ $freeVoteResetMode === 'interval' ? '' : 'display: none;' }}">
                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">
                                Interval Reset (Dalam Jam):
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="free_vote_reset_interval_hours" value="{{ $freeVoteResetIntervalHours }}" min="1" max="720"
                                       class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 font-bold">
                                <span class="text-sm font-bold text-gray-600 shrink-0">Jam</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PANEL 4: KUNCI SUARA DARI PUBLIK & SLOGAN --}}
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                            <i data-lucide="lock" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-gray-900 text-lg">Perahasiaan Angka Suara (Secret Freeze) &amp; Slogan</h2>
                            <p class="text-xs text-gray-500">Sembunyikan angka suara dari publik menjelang pengumuman juara.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 space-y-3">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="voting_counts_locked" value="1" {{ $isCountsLocked ? 'checked' : '' }}
                                       class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                <span class="font-black text-sm text-gray-900">Kunci Angka Suara dari Publik</span>
                            </label>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Jika dicentang, angka suara di layar publik diganti icon gembok (Dirahasiakan) dan urutan regu diacak.
                            </p>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Buka Kunci Otomatis Pada (WITA):</label>
                                <input type="datetime-local" name="voting_counts_unlock_at"
                                       value="{{ $unlockAt ? \Carbon\Carbon::parse($unlockAt)->format('Y-m-d\TH:i') : '' }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-white">
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 space-y-3">
                            <label class="block font-black text-sm text-gray-900">Slogan / Tagline Acara:</label>
                            <input type="text" name="app_slogan" value="{{ $appSlogan }}"
                                   placeholder="Contoh: Tangkas di Alam, Adaptif di Zaman, Unggul Berkarakter"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-white font-medium">
                            <p class="text-[11px] text-gray-500">Tampil pada footer dan header website.</p>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN SEMUA PENGATURAN --}}
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white text-base font-black shadow-xl hover:shadow-2xl transition active:scale-95">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>SIMPAN SEMUA PENGATURAN VOTING</span>
                    </button>
                </div>
            </form>

            {{-- ACTION BOX: RESET MANUAL HAK SUARA GRATIS SEKARANG --}}
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-3xl border-2 border-purple-300 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-5 h-5 text-purple-700"></i>
                        <h3 class="font-black text-gray-900 text-base">Reset Hak Suara Gratis Sekarang (Manual Reset)</h3>
                    </div>
                    <p class="text-xs text-gray-600 mt-1 max-w-2xl leading-relaxed">
                        Tindakan ini akan <strong>mereset cooldown hak vote gratis seluruh pengguna/perangkat</strong> sekarang juga tanpa menghapus suara yang sudah masuk. Semua voter langsung dapat memberikan 1 suara gratis kembali!
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.voting.reset-free') }}"
                      onsubmit="return confirm('Apakah Anda yakin ingin mereset kuota vote gratis sekarang? Seluruh voter/perangkat akan langsung dapat memberikan 1 suara gratis kembali tanpa mempengaruhi akumulasi perolehan suara yang sudah masuk.')">
                    @csrf
                    <button type="submit"
                            class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-purple-700 text-white text-xs font-bold hover:bg-purple-800 shadow-md transition">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        <span>Reset Kuota Gratis Sekarang</span>
                    </button>
                </form>
            </div>

            {{-- DANGER ZONE: RESET TOTAL SEMUA VOTE KE NOL --}}
            <div class="bg-red-50/50 rounded-3xl border border-red-200 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-red-800 font-bold text-sm">
                        <i data-lucide="alert-octagon" class="w-4 h-4"></i>
                        <span>Zona Bahaya: Reset Total Seluruh Data Voting</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Hapus seluruh riwayat suara yang telah masuk (gratis &amp; berbayar) dan mulai ulang dari 0 suara.</p>
                </div>
                <form method="POST" action="{{ route('admin.voting.destroy-all') }}"
                      onsubmit="return confirm('PERHATIAN SANGAT PENTING!\nTindakan ini akan MENGHAPUS PERMANEN seluruh data suara yang pernah masuk.\nPerolehan suara seluruh regu akan kembali ke 0.\nTindakan ini TIDAK DAPAT DIBATALKAN.\n\nApakah Anda yakin ingin melanjutkan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Hapus &amp; Reset Semua Suara ke 0</span>
                    </button>
                </form>
            </div>

        </div>

        {{-- TAB 2: PEROLEHAN SUARA REGU (LEADERBOARD) --}}
        <div id="tab-panel-leaderboard" class="voting-tab-panel space-y-6 {{ $initialTab === 'leaderboard' ? '' : 'hidden' }}">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 text-emerald-700"></i>
                        <div>
                            <h2 class="font-black text-gray-900 text-base">Perolehan Suara Seluruh Regu</h2>
                            <p class="text-xs text-gray-500">Rincian perolehan suara gratis dan berbayar QRIS untuk setiap regu.</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        Total Suara: {{ number_format($totalVotes) }}
                    </span>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($summary as $i => $row)
                        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50/50 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black shrink-0
                                    {{ $i === 0 ? 'bg-amber-100 text-amber-700 ring-2 ring-amber-400/50' : ($i === 1 ? 'bg-slate-100 text-slate-600' : ($i === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500')) }}">
                                    {{ $i === 0 ? '👑' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '#' . ($i+1))) }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="font-extrabold text-gray-900 text-sm truncate">{{ $row->nama_regu }}</p>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $row->jenis === 'putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                            {{ $row->jenis }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">Regu #{{ $row->nomor_regu }} &bull; Kavling {{ $row->nomor_kavling ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 sm:gap-6 mt-2 sm:mt-0">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ number_format($row->free_votes) }} Suara Gratis
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 font-semibold border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ number_format($row->paid_votes) }} Suara QRIS
                                    </span>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="text-xl font-black text-emerald-700">{{ number_format($row->total_votes) }}</span>
                                    <span class="text-xs text-gray-400 block font-medium">total suara</span>
                                </div>

                                <div class="w-full sm:w-36 bg-gray-100 rounded-full h-2.5 overflow-hidden shrink-0">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full transition-all"
                                         style="width: {{ $totalVotes > 0 ? round(($row->total_votes / $totalVotes) * 100) : 0 }}%"></div>
                                </div>

                                <span class="text-xs font-bold text-gray-600 w-12 text-right shrink-0">
                                    {{ $totalVotes > 0 ? round(($row->total_votes / $totalVotes) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada regu atau suara yang masuk.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB 3: RIWAYAT TRANSAKSI QRIS --}}
        <div id="tab-panel-transactions" class="voting-tab-panel space-y-6 {{ $initialTab === 'transactions' ? '' : 'hidden' }}">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-black text-gray-900 text-base flex items-center gap-2">
                            <i data-lucide="receipt" class="w-5 h-5 text-emerald-700"></i>
                            Daftar Transaksi Pembayaran QRIS
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Setiap transaksi terverifikasi langsung menambahkan suara murni ke regu tujuan 100% tanpa potongan biaya.</p>
                    </div>

                    <div class="flex items-center gap-1.5 flex-wrap">
                        <a href="{{ route('admin.voting.index', ['tab' => 'transactions']) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !$statusFilter ? 'bg-emerald-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.voting.index', ['tab' => 'transactions', 'status' => 'pending']) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                            Pending ({{ $pendingTransactionsCount }})
                        </a>
                        <a href="{{ route('admin.voting.index', ['tab' => 'transactions', 'status' => 'verified']) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $statusFilter === 'verified' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                            Lunas / Disetujui
                        </a>
                        <a href="{{ route('admin.voting.index', ['tab' => 'transactions', 'status' => 'rejected']) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $statusFilter === 'rejected' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-800 hover:bg-red-100' }}">
                            Dibatalkan / Ditolak
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-500 font-bold">
                                <th class="px-4 py-3 text-left">Kode &amp; Waktu</th>
                                <th class="px-4 py-3 text-left">Nama Pemilih</th>
                                <th class="px-4 py-3 text-left">Regu Pilihan</th>
                                <th class="px-4 py-3 text-right">Jumlah &amp; Nominal</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="font-mono text-xs font-bold text-gray-900">{{ $tx->transaction_code }}</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $tx->created_at->format('d/m/Y H:i:s') }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-800">{{ $tx->voter_name }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono">IP: {{ $tx->ip_address ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($tx->reguProfile)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-gray-900">{{ $tx->reguProfile->nama_regu }}</span>
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                                    {{ $tx->reguProfile->jenis === 'putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                                    {{ $tx->reguProfile->jenis }}
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-gray-400">Kavling {{ $tx->reguProfile->nomor_kavling ?? '-' }}</span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Regu dihapus</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <div class="font-black text-emerald-700 text-sm">
                                            +{{ number_format($tx->votes_count) }} Suara
                                        </div>
                                        <div class="text-xs font-bold text-gray-600">
                                            Rp {{ number_format($tx->gateway_total_payment ?? $tx->total_amount, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400">
                                            (@ Rp {{ number_format($tx->unit_price, 0, ',', '.') }})
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        @if($tx->isVerified())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Lunas (DANA)
                                            </span>
                                            @if($tx->verified_at)
                                                <div class="text-[10px] text-gray-400 mt-0.5">{{ $tx->verified_at->format('d/m H:i') }}</div>
                                            @endif
                                        @elseif($tx->isRejected())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800" title="{{ $tx->rejection_reason }}">
                                                <i data-lucide="x-circle" class="w-3.5 h-3.5 text-red-600"></i> Ditolak / Batal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 animate-pulse">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Menunggu Bayar DANA
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($tx->isPending())
                                                <form method="POST" action="{{ route('admin.voting.transactions.sync', $tx) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold hover:bg-blue-100 transition" title="Periksa Kedaluwarsa Transaksi">
                                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                                        Cek Status
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.voting.transactions.approve', $tx) }}"
                                                      onsubmit="return confirm('Konfirmasi dan setujui manual transaksi {{ $tx->transaction_code }}? Sebanyak {{ $tx->votes_count }} suara akan langsung ditambahkan ke regu {{ addslashes($tx->reguProfile?->nama_regu ?? '') }}.')">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 shadow-sm transition">
                                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                        Setujui
                                                    </button>
                                                </form>

                                                <button type="button"
                                                        onclick="openRejectModal('{{ route('admin.voting.transactions.reject', $tx) }}', '{{ $tx->transaction_code }}')"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-red-50 text-red-700 border border-red-200 text-xs font-bold hover:bg-red-100 transition">
                                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                    Tolak
                                                </button>
                                            @endif

                                            <form method="POST" action="{{ route('admin.voting.transactions.destroy', $tx) }}"
                                                  onsubmit="return confirm('Hapus riwayat transaksi {{ $tx->transaction_code }}? Jika transaksi sudah disetujui, suara yang terkait juga akan terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-700 hover:bg-red-50 transition" title="Hapus Transaksi">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        Belum ada data transaksi QRIS yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $transactions->appends(['tab' => 'transactions', 'status' => $statusFilter])->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- TAB 4: RIWAYAT LOG SELURUH SUARA --}}
        <div id="tab-panel-history" class="voting-tab-panel space-y-6 {{ $initialTab === 'history' ? '' : 'hidden' }}">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="list" class="w-5 h-5 text-emerald-700"></i>
                        <div>
                            <h2 class="font-black text-gray-900 text-base">Riwayat Log Suara Masuk</h2>
                            <p class="text-xs text-gray-500">Log waktu, nama pemilih, jenis vote (gratis / QRIS), dan IP address.</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 font-mono">{{ $votes->total() }} entri total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-500 font-bold">
                                <th class="px-4 py-3 text-left">#ID</th>
                                <th class="px-4 py-3 text-left">Nama Pemilih</th>
                                <th class="px-4 py-3 text-left">Tipe Vote</th>
                                <th class="px-4 py-3 text-left">Memilih Regu</th>
                                <th class="px-4 py-3 text-left">Waktu Vote</th>
                                <th class="px-4 py-3 text-left">IP Address</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($votes as $vote)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $vote->id }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-black shrink-0">
                                                {{ strtoupper(substr($vote->voter_name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-gray-800">{{ $vote->voter_name ?? '(tanpa nama)' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($vote->isPaid())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                <i data-lucide="qr-code" class="w-3 h-3 text-amber-700"></i> QRIS
                                            </span>
                                            @if($vote->paidVoteTransaction)
                                                <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $vote->paidVoteTransaction->transaction_code }}</div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i data-lucide="gift" class="w-3 h-3 text-emerald-700"></i> Gratis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($vote->reguProfile)
                                            <div>
                                                <span class="font-bold text-gray-900">{{ $vote->reguProfile->nama_regu }}</span>
                                                <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
                                                    {{ $vote->reguProfile->jenis === 'putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                                    {{ $vote->reguProfile->jenis }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Regu dihapus</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs font-mono whitespace-nowrap">
                                        {{ $vote->voted_at ? $vote->voted_at->format('d/m/Y H:i:s') : $vote->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ $vote->ip_address ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <form method="POST" action="{{ route('admin.voting.destroy', $vote) }}"
                                              onsubmit="return confirm('Hapus suara #{{ $vote->id }} dari {{ addslashes($vote->voter_name ?? 'voter ini') }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-red-50 text-red-600 border border-red-100 text-xs font-bold hover:bg-red-100 transition">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        Belum ada data suara yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($votes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $votes->appends(['tab' => 'history'])->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- MODAL TOLAK TRANSAKSI --}}
    <div id="reject-modal" onclick="if (event.target === this) closeRejectModal()"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/75 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-gray-900">Tolak Transaksi</h3>
                    <p class="text-xs text-gray-500" id="reject-modal-code">Kode: -</p>
                </div>
            </div>

            <form id="reject-modal-form" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan:</label>
                    <textarea name="rejection_reason" rows="3" required
                              placeholder="Contoh: Pembayaran tidak masuk / transaksi dibatalkan"
                              class="w-full px-3 py-2 text-xs rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 font-medium"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition">
                        Tolak Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    window.switchVotingTab = function (tabName) {
        const tabs = ['settings', 'leaderboard', 'transactions', 'history'];
        tabs.forEach(function (t) {
            const panel = document.getElementById('tab-panel-' + t);
            const btn = document.getElementById('tab-btn-' + t);
            if (!panel || !btn) return;

            if (t === tabName) {
                panel.classList.remove('hidden');
                btn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
                btn.classList.add('border-emerald-700', 'text-emerald-800', 'font-black');
            } else {
                panel.classList.add('hidden');
                btn.classList.remove('border-emerald-700', 'text-emerald-800', 'font-black');
                btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
            }
        });

        try {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url.toString());
        } catch (e) {}

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    };

    window.copyTextById = function (inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);
        if (!input) return;

        navigator.clipboard.writeText(input.value).then(function () {
            if (btn) {
                const orig = btn.textContent;
                btn.textContent = 'Tersalin!';
                setTimeout(function () {
                    btn.textContent = orig;
                }, 2000);
            }
        });
    };

    window.extractDanaQrisFromImage = function (input) {
        const file = input.files && input.files[0];
        const statusEl = document.getElementById('qris-extract-status');
        const payloadInput = document.getElementById('dana-qris-payload-input');
        if (!file || typeof jsQR === 'undefined') return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = new Image();
            img.onload = function () {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imgData.data, imgData.width, imgData.height);

                if (code && code.data && code.data.startsWith('000201')) {
                    if (payloadInput) payloadInput.value = code.data;
                    if (statusEl) {
                        statusEl.textContent = '✓ Payload QRIS DANA berhasil diekstrak otomatis! QRIS dinamis Rp 1+ siap digunakan.';
                        statusEl.className = 'text-[11px] text-emerald-700 font-bold mt-1';
                    }
                } else if (statusEl) {
                    statusEl.textContent = 'Gambar QRIS siap diunggah. Jika ingin nominal dinamis otomatis di aplikasi DANA, tempel payload string QRIS di kotak bawah (opsional).';
                    statusEl.className = 'text-[11px] text-amber-700 font-semibold mt-1';
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    };

    window.openRejectModal = function (actionUrl, txCode) {
        const modal = document.getElementById('reject-modal');
        const form = document.getElementById('reject-modal-form');
        const codeEl = document.getElementById('reject-modal-code');
        if (!modal || !form) return;

        form.action = actionUrl;
        if (codeEl) codeEl.textContent = 'Kode: ' + txCode;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeRejectModal = function () {
        const modal = document.getElementById('reject-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };
</script>
@endpush
@endsection
