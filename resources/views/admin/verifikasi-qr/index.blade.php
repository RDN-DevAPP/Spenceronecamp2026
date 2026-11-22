@extends('layouts.main')

@section('title', 'Verifikasi QR ID Card Peserta - Admin')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                    <i data-lucide="qr-code" class="w-3.5 h-3.5 text-emerald-700"></i>
                    <span>Sistem Validasi &amp; Check-In Otomatis</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">Verifikasi QR ID Card</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pindai QR ID Card peserta: Sistem langsung <strong>otomatis check-in</strong> kehadiran tanpa perlu klik tombol konfirmasi. Anda dapat membatalkan dan scan ulang kapan saja.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.id-cards.index') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold shadow-xs transition cursor-pointer">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke ID Card</span>
                </a>
                <a href="{{ route('verifikasi-sertifikat.index') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-brand-forest border border-emerald-200 rounded-xl text-xs font-bold shadow-xs transition cursor-pointer">
                    <i data-lucide="award" class="w-4 h-4 text-emerald-700"></i>
                    <span>Cek Sertifikat</span>
                </a>
            </div>
        </div>

        {{-- Main Scanner Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Left Column: Camera Scanner & Input --}}
            <div class="lg:col-span-6 space-y-6">
                {{-- Scanner Box --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></div>
                            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Kamera Scanner Interaktif</h2>
                        </div>
                        <span id="scanner-status-tag" class="text-xs text-emerald-800 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200/60">Live Feed</span>
                    </div>

                    <div class="p-6">
                        <div id="reader-container" class="relative rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 bg-gray-900 aspect-square flex items-center justify-center">
                            <div id="qr-reader" class="w-full h-full"></div>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <button id="btn-toggle-camera" onclick="toggleCamera()"
                                class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                                <i data-lucide="camera" class="w-4 h-4"></i>
                                <span id="camera-btn-text">Buka Kamera</span>
                            </button>
                            <button id="btn-quick-reset" onclick="resetForNextScan()"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition cursor-pointer">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                <span>Reset / Scan Ulang</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Manual Input Box (Barcode Gun Compatible) --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i data-lucide="keyboard" class="w-4 h-4 text-gray-400"></i>
                        Input Manual / Barcode Scanner Gun (Otomatis Check-In)
                    </h3>
                    <p class="text-[11px] text-gray-500 mb-3">
                        Gunakan barcode scanner USB/Bluetooth atau ketik ID/NISN/URL lalu tekan Enter.
                    </p>
                    <form onsubmit="event.preventDefault(); submitManualInput();" class="flex gap-2">
                        <input type="text" id="manual-input" placeholder="Scan barcode atau ketik ID/NISN..." autofocus
                            class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-medium">
                        <button type="submit"
                            class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-emerald-950 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-xs shrink-0 cursor-pointer flex items-center gap-1.5">
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                            <span>Check-In</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: Scan Result Card & Recent Verifications --}}
            <div class="lg:col-span-6 space-y-6">
                {{-- Result Card --}}
                <div id="result-card" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6 relative">
                    
                    {{-- Empty State --}}
                    <div id="result-empty" class="py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <i data-lucide="scan-line" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Menunggu Pemindaian QR</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">
                            Arahkan kamera ke QR ID Card peserta. Sistem akan <strong>langsung check-in otomatis</strong> tanpa perlu klik tombol lagi.
                        </p>
                    </div>

                    {{-- Result Content --}}
                    <div id="result-content" class="hidden space-y-5">
                        
                        {{-- Auto Check-In Status Banner --}}
                        <div id="res-banner-box"></div>

                        {{-- Header Result --}}
                        <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-900 mb-1.5">
                                    <i data-lucide="badge-check" class="w-3 h-3 text-emerald-700"></i>
                                    <span>Identitas Resmi Terdaftar</span>
                                </span>
                                <h3 id="res-nama" class="text-xl font-black text-brand-forest uppercase leading-tight"></h3>
                                <p id="res-jabatan-badge" class="text-xs font-bold text-amber-700 uppercase mt-0.5"></p>
                            </div>
                            <div id="res-status-badge"></div>
                        </div>

                        {{-- Photo and Grid Details --}}
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <div class="w-28 h-28 rounded-2xl bg-emerald-50 border-2 border-brand-forest overflow-hidden shrink-0 flex items-center justify-center shadow-inner">
                                <img id="res-foto" src="" alt="Foto" class="w-full h-full object-cover hidden">
                                <i id="res-foto-placeholder" data-lucide="user" class="w-12 h-12 text-emerald-800/40"></i>
                            </div>
                            <div class="grid grid-cols-2 gap-3 w-full text-xs">
                                <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block">NISN</span>
                                    <span id="res-nisn" class="font-mono font-bold text-gray-800"></span>
                                </div>
                                <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Kelas &amp; TKU</span>
                                    <span id="res-kelas-tku" class="font-bold text-gray-800"></span>
                                </div>
                                <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Regu Peserta</span>
                                    <span id="res-regu" class="font-black text-brand-forest uppercase truncate block"></span>
                                </div>
                                <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Kavling Tenda</span>
                                    <span id="res-kavling" class="font-bold text-gray-800"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Pangkalan & Timestamp Footer --}}
                        <div class="bg-gray-50/70 p-3 rounded-xl border border-gray-100 flex items-center justify-between text-xs">
                            <span class="font-semibold text-gray-700" id="res-pangkalan"></span>
                            <span id="res-verified-time" class="font-mono font-bold text-emerald-800"></span>
                        </div>

                        {{-- Action Buttons: Batalkan Check-In & Scan Ulang --}}
                        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <button id="btn-cancel-checkin" onclick="cancelCurrentCheckIn()"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl font-bold text-xs transition bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                                <i data-lucide="x-circle" class="w-4 h-4 text-rose-600"></i>
                                <span id="cancel-btn-text">Batalkan Check-In</span>
                            </button>

                            <button id="btn-scan-again" onclick="resetForNextScan()"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-xs transition bg-brand-forest hover:bg-emerald-900 text-white shadow-md flex items-center justify-center gap-2 cursor-pointer transform active:scale-95">
                                <i data-lucide="scan-line" class="w-4 h-4 text-amber-400"></i>
                                <span>Scan Ulang / Kartu Berikutnya</span>
                            </button>
                        </div>

                        {{-- Auto Resume Next Scan Notice --}}
                        <div id="auto-resume-notice" class="text-center text-[11px] text-gray-400 font-medium">
                            <span>Kamera siap scan kartu berikutnya otomatis dalam <strong id="countdown-sec" class="text-brand-forest font-bold">3</strong> detik...</span>
                        </div>
                    </div>
                </div>

                {{-- Recent Verifications Table --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="history" class="w-4 h-4 text-gray-400"></i>
                            Riwayat Verifikasi Terbaru
                        </h3>
                        <span id="stats-counter" class="text-xs font-bold text-brand-forest">
                            {{ $stats['total_verified'] }} / {{ $stats['total_anggota'] }} Terverifikasi
                        </span>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto" id="recent-list">
                        @forelse($recentVerifications as $item)
                            <div class="p-3.5 hover:bg-gray-50/60 transition flex items-center justify-between gap-3" id="recent-item-{{ $item->id }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden">
                                        @if($item->foto_path)
                                            <img src="{{ asset('storage/' . $item->foto_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="user" class="w-4 h-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900 leading-tight">{{ $item->nama }}</h4>
                                        <p class="text-[10px] text-gray-500 font-medium">{{ $item->reguProfile->nama_regu ?? '-' }} &bull; {{ strtoupper($item->jabatan) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 block">Hadir</span>
                                    <span class="text-[9px] text-gray-400 font-mono">{{ $item->verified_at ? $item->verified_at->format('H:i') : '' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-gray-400" id="recent-empty">Belum ada peserta yang diverifikasi.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode = null;
    let isScanning = false;
    let isPausedForProcess = false;
    let currentMemberId = null;
    let autoResumeTimer = null;
    let countdownInterval = null;

    // Web Audio Chime Beep Synthesizer
    function playSuccessBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime); // Note A5
            osc.frequency.exponentialRampToValueAtTime(1174.66, ctx.currentTime + 0.12); // Note D6
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.2);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.2);
        } catch (e) {
            console.log('Audio error:', e);
        }
    }

    function toggleCamera() {
        if (isScanning) {
            stopCamera();
        } else {
            startCamera();
        }
    }

    function startCamera() {
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("qr-reader");
        }

        const config = { fps: 12, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .then(() => {
                isScanning = true;
                isPausedForProcess = false;
                document.getElementById('camera-btn-text').innerText = 'Tutup Kamera';
                document.getElementById('btn-toggle-camera').className = 'w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer';
                document.getElementById('scanner-status-tag').innerText = 'Kamera Aktif';
                document.getElementById('scanner-status-tag').className = 'text-xs text-emerald-800 font-bold bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200/60';
            })
            .catch(err => {
                alert("Gagal mengakses kamera: " + err);
            });
    }

    function stopCamera() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                document.getElementById('camera-btn-text').innerText = 'Buka Kamera';
                document.getElementById('btn-toggle-camera').className = 'w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer';
                document.getElementById('scanner-status-tag').innerText = 'Kamera Mati';
                document.getElementById('scanner-status-tag').className = 'text-xs text-gray-500 font-bold bg-gray-100 px-2.5 py-0.5 rounded-lg border border-gray-200';
            }).catch(e => console.log(e));
        }
    }

    function onScanSuccess(decodedText) {
        if (isPausedForProcess) return; // Mencegah multi-scan dalam interval cepat
        isPausedForProcess = true;
        playSuccessBeep();
        processLookup(decodedText);
    }

    function submitManualInput() {
        const inputEl = document.getElementById('manual-input');
        const code = inputEl.value.trim();
        if (!code) return;
        
        isPausedForProcess = true;
        playSuccessBeep();
        processLookup(code);
        inputEl.value = '';
        inputEl.focus();
    }

    function processLookup(code) {
        if (!code) {
            isPausedForProcess = false;
            return;
        }

        // Tampilkan indikator loading di status tag
        document.getElementById('scanner-status-tag').innerText = 'Memproses...';

        fetch(`{{ route('admin.verifikasi-qr.lookup') }}?code=${encodeURIComponent(code)}&auto_checkin=1`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderResult(data.data, data.message, data.just_checked_in);
                if (data.stats && data.recent) {
                    updateRecentList(data.recent, data.stats);
                }
                startAutoResumeCountdown();
            } else {
                alert(data.message || 'Data peserta tidak ditemukan.');
                isPausedForProcess = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat memproses data QR.');
            isPausedForProcess = false;
        })
        .finally(() => {
            if (isScanning) {
                document.getElementById('scanner-status-tag').innerText = 'Kamera Aktif';
            }
        });
    }

    function renderResult(member, message, justCheckedIn) {
        currentMemberId = member.id;

        document.getElementById('result-empty').classList.add('hidden');
        document.getElementById('result-content').classList.remove('hidden');

        // Banner Status Otomatis Check-In
        const bannerBox = document.getElementById('res-banner-box');
        if (justCheckedIn) {
            bannerBox.innerHTML = `
                <div class="p-4 bg-emerald-600 text-white rounded-2xl shadow-md flex items-center justify-between gap-3 animate-pulse">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-amber-300"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider block">BERHASIL CHECK-IN OTOMATIS!</span>
                            <span class="text-[11px] text-emerald-100">Kehadiran fisik peserta telah dicatat otomatis.</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono font-bold bg-black/20 px-2.5 py-1 rounded-lg shrink-0">${member.verified_at || 'Baru Saja'}</span>
                </div>
            `;
        } else {
            bannerBox.innerHTML = `
                <div class="p-3.5 bg-blue-700 text-white rounded-2xl shadow-md flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-300"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider block">SUDAH TERVERIFIKASI HADIR</span>
                            <span class="text-[10px] text-blue-100">Peserta sudah tercatat check-in sebelumnya.</span>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono font-bold bg-black/20 px-2.5 py-1 rounded-lg shrink-0">${member.verified_at || ''}</span>
                </div>
            `;
        }

        // Tampilkan Detail Peserta
        document.getElementById('res-nama').innerText = member.nama;
        document.getElementById('res-jabatan-badge').innerText = `${member.jabatan} • ${member.regu.nama_regu}`;
        document.getElementById('res-nisn').innerText = member.nisn;
        document.getElementById('res-kelas-tku').innerText = `${member.kelas} • TKU: ${member.tku}`;
        document.getElementById('res-regu').innerText = `${member.regu.nama_regu} (${member.regu.jenis})`;
        document.getElementById('res-kavling').innerText = member.regu.nomor_kavling;
        document.getElementById('res-pangkalan').innerText = `${member.regu.sekolah} • ${member.regu.gudep}`;

        const fotoEl = document.getElementById('res-foto');
        const placeholderEl = document.getElementById('res-foto-placeholder');
        if (member.foto_url) {
            fotoEl.src = member.foto_url;
            fotoEl.classList.remove('hidden');
            placeholderEl.classList.add('hidden');
        } else {
            fotoEl.classList.add('hidden');
            placeholderEl.classList.remove('hidden');
        }

        updateStatusBadge(member.is_verified, member.verified_at);
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function updateStatusBadge(isVerified, verifiedAt) {
        const badgeEl = document.getElementById('res-status-badge');
        const timeEl = document.getElementById('res-verified-time');
        const cancelBtn = document.getElementById('btn-cancel-checkin');
        const cancelText = document.getElementById('cancel-btn-text');

        if (isVerified) {
            badgeEl.innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">HADIR</span>`;
            timeEl.innerText = `Check-In: ${verifiedAt || 'Baru Saja'}`;
            cancelBtn.className = 'w-full sm:w-auto px-4 py-2.5 rounded-xl font-bold text-xs transition bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 flex items-center justify-center gap-1.5 cursor-pointer shadow-xs';
            cancelText.innerText = 'Batalkan Check-In';
        } else {
            badgeEl.innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-200">DIBATALKAN</span>`;
            timeEl.innerText = 'Status check-in telah dibatalkan';
            cancelBtn.className = 'w-full sm:w-auto px-4 py-2.5 rounded-xl font-bold text-xs transition bg-amber-50 text-amber-900 hover:bg-amber-100 border border-amber-300 flex items-center justify-center gap-1.5 cursor-pointer shadow-xs';
            cancelText.innerText = 'Check-In Ulang';
        }
    }

    // Batalkan Check-in (atau Check-in Ulang)
    function cancelCurrentCheckIn() {
        if (!currentMemberId) return;

        const cancelText = document.getElementById('cancel-btn-text').innerText;
        const isCurrentlyVerified = cancelText.includes('Batalkan');

        const endpoint = isCurrentlyVerified 
            ? `/admin/verifikasi-qr/cancel-check-in/${currentMemberId}`
            : `/admin/verifikasi-qr/check-in/${currentMemberId}`;

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateStatusBadge(data.is_verified, data.verified_at);
                
                // Ubah banner
                const bannerBox = document.getElementById('res-banner-box');
                if (data.is_verified) {
                    bannerBox.innerHTML = `
                        <div class="p-3 bg-emerald-600 text-white rounded-2xl shadow-md text-xs font-black flex items-center justify-between">
                            <span>✅ CHECK-IN DIULANG BERHASIL!</span>
                            <span class="text-[10px] font-mono bg-black/20 px-2 py-0.5 rounded">${data.verified_at}</span>
                        </div>
                    `;
                } else {
                    bannerBox.innerHTML = `
                        <div class="p-3 bg-amber-500 text-white rounded-2xl shadow-md text-xs font-bold flex items-center justify-between">
                            <span>⚠️ CHECK-IN BERHASIL DIBATALKAN</span>
                            <span class="text-[10px] bg-black/20 px-2 py-0.5 rounded">Status Reset</span>
                        </div>
                    `;
                }

                if (data.stats && data.recent) {
                    updateRecentList(data.recent, data.stats);
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        })
        .catch(err => {
            alert('Gagal memperbarui status check-in.');
        });
    }

    // Hitung mundur auto-resume scan kartu berikutnya
    function startAutoResumeCountdown() {
        clearCountdown();
        let seconds = 3;
        const noticeEl = document.getElementById('auto-resume-notice');
        const countdownEl = document.getElementById('countdown-sec');
        noticeEl.classList.remove('hidden');
        countdownEl.innerText = seconds;

        countdownInterval = setInterval(() => {
            seconds--;
            if (seconds > 0) {
                countdownEl.innerText = seconds;
            } else {
                clearCountdown();
                isPausedForProcess = false; // Buka kunci scanner kembali
                noticeEl.innerHTML = `<span class="text-emerald-700 font-bold">Kamera siap memindai kartu berikutnya!</span>`;
            }
        }, 1000);
    }

    function clearCountdown() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }

    // Reset untuk Scan Ulang atau Memindai Kartu Berikutnya
    function resetForNextScan() {
        clearCountdown();
        isPausedForProcess = false;
        
        const noticeEl = document.getElementById('auto-resume-notice');
        noticeEl.classList.remove('hidden');
        noticeEl.innerHTML = `<span class="text-emerald-700 font-bold">Kamera siap! Arahkan ke ID Card peserta berikutnya.</span>`;

        const manualInput = document.getElementById('manual-input');
        if (manualInput) {
            manualInput.value = '';
            manualInput.focus();
        }

        if (isScanning) {
            document.getElementById('scanner-status-tag').innerText = 'Kamera Aktif';
        }
    }

    // Update real-time daftar verifikasi terbaru & counter
    function updateRecentList(recentItems, stats) {
        if (stats && stats.total_verified !== undefined) {
            document.getElementById('stats-counter').innerText = `${stats.total_verified} / ${stats.total_anggota} Terverifikasi`;
        }

        const listContainer = document.getElementById('recent-list');
        if (!recentItems || recentItems.length === 0) {
            listContainer.innerHTML = '<div class="p-6 text-center text-xs text-gray-400">Belum ada peserta yang diverifikasi.</div>';
            return;
        }

        let html = '';
        recentItems.forEach(item => {
            const avatarHtml = item.foto_url 
                ? `<img src="${item.foto_url}" class="w-full h-full object-cover">`
                : `<i data-lucide="user" class="w-4 h-4"></i>`;

            html += `
                <div class="p-3.5 hover:bg-gray-50/60 transition flex items-center justify-between gap-3" id="recent-item-${item.id}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden">
                            ${avatarHtml}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 leading-tight">${item.nama}</h4>
                            <p class="text-[10px] text-gray-500 font-medium">${item.nama_regu} &bull; ${item.jabatan}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 block">Hadir</span>
                        <span class="text-[9px] text-gray-400 font-mono">${item.verified_time || ''}</span>
                    </div>
                </div>
            `;
        });
        listContainer.innerHTML = html;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
</script>
@endpush
