@extends('layouts.main')

@section('title', 'Scan Formulir Lembar Penilaian PBB - OMR Scanner')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-12">
    <div class="max-w-[100rem] mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                    <i data-lucide="scan-line" class="w-3.5 h-3.5"></i>
                    <span>Sistem Verifikasi Sudut OMR &amp; Scan Otomatis</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight">
                    Scan Formulir Penilaian PBB
                </h1>
                <p class="text-sm text-gray-500 mt-1 max-w-3xl">
                    Pindai formulir cetak juri menggunakan kamera atau unggah foto/scan. Sistem otomatis memverifikasi 4 kotak hitam di sudut untuk validasi lembar dan membaca coretan nilai per kriteria.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.pbb-rubrik.print') }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold shadow-xs transition">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Formulir OMR
                </a>
                <a href="{{ route('admin.pbb-rubrik.index') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold shadow-xs transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Rubrik
                </a>
            </div>
        </div>

        {{-- Main Grid: Left Scanner / Canvas, Right Review & Simpan --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Kolom Kiri: Kamera / Upload & Canvas Scanner (6 Kolom) --}}
            <div class="lg:col-span-6 space-y-6">

                {{-- Mode Switcher & Input Card --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    {{-- Tabs: Kamera vs Upload --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                        <div class="inline-flex p-1 bg-gray-200/70 rounded-xl text-xs font-bold">
                            <button type="button" id="tab-camera-btn" onclick="switchInputMode('camera')"
                                class="px-3 py-1.5 rounded-lg transition bg-white text-brand-forest shadow-xs flex items-center gap-1.5">
                                <i data-lucide="camera" class="w-3.5 h-3.5"></i> Kamera Langsung
                            </button>
                            <button type="button" id="tab-upload-btn" onclick="switchInputMode('upload')"
                                class="px-3 py-1.5 rounded-lg transition text-gray-600 hover:text-gray-900 flex items-center gap-1.5">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i> Unggah Foto / Scan
                            </button>
                        </div>

                        <div id="status-tag" class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 border border-gray-200 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                            <span id="status-tag-text">Siap Memindai</span>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Mode Kamera --}}
                        <div id="mode-camera-wrap" class="space-y-4">
                            <div class="relative bg-black rounded-xl overflow-hidden aspect-[4/3] flex items-center justify-center border-2 border-gray-200 shadow-inner">
                                <video id="camera-video" autoplay playsinline class="w-full h-full object-contain"></video>

                                {{-- Overlay Target 4 Sudut --}}
                                <div id="camera-guide-overlay" class="absolute inset-0 pointer-events-none p-6 flex flex-col justify-between">
                                    <div class="flex justify-between">
                                        <div class="w-8 h-8 border-t-4 border-l-4 border-emerald-400 rounded-tl"></div>
                                        <div class="w-8 h-8 border-t-4 border-r-4 border-emerald-400 rounded-tr"></div>
                                    </div>
                                    <div class="text-center">
                                        <span class="bg-black/70 text-emerald-300 text-[11px] font-bold px-3 py-1 rounded-full backdrop-blur-xs">
                                            Arahkan 4 sudut kertas ke dalam bingkai
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <div class="w-8 h-8 border-b-4 border-l-4 border-emerald-400 rounded-bl"></div>
                                        <div class="w-8 h-8 border-b-4 border-r-4 border-emerald-400 rounded-br"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" id="btn-toggle-cam" onclick="toggleCamera()"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold shadow-xs transition cursor-pointer">
                                    <i data-lucide="video" class="w-4 h-4"></i>
                                    <span id="btn-cam-label">Nyalakan Kamera</span>
                                </button>
                                <button type="button" id="btn-capture" onclick="captureAndProcess()" disabled
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 disabled:opacity-50 text-emerald-950 rounded-xl text-xs font-black shadow-xs transition cursor-pointer">
                                    <i data-lucide="aperture" class="w-4 h-4"></i>
                                    <span>Bidik &amp; Baca Lembar</span>
                                </button>
                            </div>
                        </div>

                        {{-- Mode Unggah Foto --}}
                        <div id="mode-upload-wrap" class="hidden space-y-4">
                            <label for="image-upload-input"
                                class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 hover:border-emerald-500 bg-gray-50/50 hover:bg-emerald-50/20 rounded-2xl p-8 cursor-pointer transition text-center group">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-100 group-hover:bg-emerald-200 text-brand-forest flex items-center justify-center mb-3 transition">
                                    <i data-lucide="image-up" class="w-7 h-7"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-800">Pilih Foto Formulir atau Tarik ke Sini</span>
                                <span class="text-xs text-gray-400 mt-1">Mendukung format JPG, PNG, WEBP dari kamera ponsel atau mesin scan</span>
                                <input type="file" id="image-upload-input" accept="image/*" capture="environment" class="hidden" onchange="handleFileUpload(event)">
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Status Verifikasi Sudut Card --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-5">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                        Status Verifikasi Formulir Resmi
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
                        <div id="corner-tag-tl" class="p-2.5 rounded-xl border border-gray-200 bg-gray-50 text-center">
                            <span class="text-[10px] text-gray-400 block font-bold">Kiri Atas (TL)</span>
                            <span class="text-xs font-black text-gray-600 corner-status">Menunggu</span>
                        </div>
                        <div id="corner-tag-tr" class="p-2.5 rounded-xl border border-gray-200 bg-gray-50 text-center">
                            <span class="text-[10px] text-gray-400 block font-bold">Kanan Atas (TR)</span>
                            <span class="text-xs font-black text-gray-600 corner-status">Menunggu</span>
                        </div>
                        <div id="corner-tag-bl" class="p-2.5 rounded-xl border border-gray-200 bg-gray-50 text-center">
                            <span class="text-[10px] text-gray-400 block font-bold">Kiri Bawah (BL)</span>
                            <span class="text-xs font-black text-gray-600 corner-status">Menunggu</span>
                        </div>
                        <div id="corner-tag-br" class="p-2.5 rounded-xl border border-gray-200 bg-gray-50 text-center">
                            <span class="text-[10px] text-gray-400 block font-bold">Kanan Bawah (BR)</span>
                            <span class="text-xs font-black text-gray-600 corner-status">Menunggu</span>
                        </div>
                    </div>

                    <div id="verification-banner" class="p-3 rounded-xl bg-gray-100 border border-gray-200 text-xs font-semibold text-gray-600 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 shrink-0 text-gray-400"></i>
                        <span>Ambil foto lembar atau pilih file untuk mendeteksi 4 kotak sudut hitam.</span>
                    </div>
                </div>

                {{-- Preview Canvas Pemrosesan --}}
                <div id="canvas-card" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4 hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-700">Visual Deteksi Kotak &amp; Coretan:</span>
                        <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">
                            Kotak Hijau = Nilai Terdeteksi
                        </span>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-gray-200 bg-slate-900 flex items-center justify-center">
                        <canvas id="omr-canvas" class="max-w-full h-auto block"></canvas>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Hasil Pembacaan & Verifikasi Nilai (6 Kolom) --}}
            <div class="lg:col-span-6 space-y-6">

                {{-- Card Target Regu & Juri --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-6">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                        Data Peserta &amp; Juri Penilai
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Pilih Regu --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Regu / Satuan Peserta <span class="text-rose-500">*</span>
                            </label>
                            <select id="select-regu" onchange="onReguChanged(this.value)"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                <option value="">-- Pilih Regu Peserta --</option>
                                @foreach($regus as $r)
                                    <option value="{{ $r->id }}" {{ $selectedReguId == $r->id ? 'selected' : '' }}
                                        data-no="{{ $r->nomor_regu }}" data-sekolah="{{ $r->user->pangkalan ?? ($r->user->sekolah ?? '-') }}" data-jenis="{{ ucfirst($r->jenis) }}">
                                        No. {{ $r->nomor_regu }} - {{ $r->nama_regu }} ({{ ucfirst($r->jenis) }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Otomatis terisi bila QR di sudut header terbaca.</p>
                        </div>

                        {{-- Pilih Juri --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Juri Penilai <span class="text-rose-500">*</span>
                            </label>
                            <select id="select-juri"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600">
                                @foreach($juris as $j)
                                    <option value="{{ $j->id }}" {{ Auth::id() == $j->id ? 'selected' : '' }}>
                                        {{ $j->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Nilai akan disimpan atas nama juri ini.</p>
                        </div>
                    </div>
                </div>

                {{-- Banner Grand Total Terbaca --}}
                <div class="bg-gradient-to-r from-emerald-950 to-brand-forest rounded-2xl shadow-lg p-5 text-white flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-200 block">Total Nilai Formulir Terbaca</span>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span id="grand-total-display" class="text-3xl sm:text-4xl font-black text-amber-300">0.00</span>
                            <span class="text-xs text-emerald-200/90 font-medium">/ {{ number_format($criteria->sum('nilai_max'), 2) }} Poin</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span id="detected-count-badge" class="px-3 py-1 rounded-full text-xs font-black bg-white/10 text-emerald-100 border border-white/20">
                            0 / {{ $criteria->count() }} Poin Terdeteksi
                        </span>
                    </div>
                </div>

                {{-- Tabel Interaktif Review & Konfirmasi Nilai --}}
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="list-checks" class="w-4 h-4 text-emerald-600"></i>
                            Review Poin Penilaian (Dapat Diklik untuk Koreksi)
                        </h3>
                        <span class="text-[11px] text-gray-500">56 Poin Materi</span>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto divide-y divide-gray-100 p-2" id="criteria-review-list">
                        @foreach($criteria as $idx => $c)
                            @php
                                $levels = array_values($c->rubrik ?? []);
                                $groupName = $c->kelompok ?: 'Penilaian';
                            @endphp
                            <div class="criteria-item p-3 hover:bg-gray-50/80 rounded-xl transition" data-id="{{ $c->id }}" data-idx="{{ $idx }}">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-900 text-[10px] font-black flex items-center justify-center shrink-0">
                                            {{ $c->urutan }}
                                        </span>
                                        <span class="text-xs font-bold text-gray-800">{{ $c->nama_kriteria }}</span>
                                        <span class="text-[9px] text-gray-400 font-medium">({{ $groupName }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-gray-400">Maks: {{ !empty($levels) ? max($levels) : ($c->nilai_max ?? 0) }}</span>
                                        <div class="px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-brand-forest font-black text-xs score-badge">
                                            0
                                        </div>
                                    </div>
                                </div>

                                {{-- Pilihan 9 Jenjang Nilai --}}
                                <div class="grid grid-cols-9 gap-1">
                                    @foreach($labels as $li => $label)
                                        @php
                                            $val = $levels[$li] ?? 0;
                                            $shortLabel = match($li) {
                                                0 => '0',
                                                1 => 'Min',
                                                2 => 'K1',
                                                3 => 'K2',
                                                4 => 'C1',
                                                5 => 'C2',
                                                6 => 'B1',
                                                7 => 'B2',
                                                8 => 'SB',
                                                default => $label
                                            };
                                        @endphp
                                        <button type="button"
                                            data-criteria="{{ $c->id }}"
                                            data-level="{{ $li }}"
                                            data-val="{{ $val }}"
                                            onclick="selectManualScore({{ $c->id }}, {{ $li }}, {{ $val }})"
                                            title="{{ $label }}: {{ $val }} Poin"
                                            class="btn-lvl-{{ $c->id }}-{{ $li }} flex flex-col items-center justify-center py-1 px-0.5 rounded-lg border text-center transition select-none cursor-pointer {{ $li === 0 ? 'bg-brand-forest text-amber-300 border-emerald-950 font-black' : 'bg-gray-50 hover:bg-emerald-50 text-gray-700 border-gray-200' }}">
                                            <span class="text-[8px] font-extrabold uppercase leading-tight opacity-75">{{ $shortLabel }}</span>
                                            <span class="text-[10px] font-black leading-tight mt-0.5">{{ $val }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Simpan Nilai --}}
                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3">
                        <button type="button" onclick="resetForm()"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold transition cursor-pointer">
                            Reset Pemindaian
                        </button>

                        <button type="button" id="btn-save-score" onclick="submitScannedScore()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-forest hover:bg-emerald-900 text-white font-bold text-xs shadow-md transition cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Simpan Nilai ke Sistem</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- jsQR library untuk decode QR code langsung dari canvas --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
    // Data Kriteria dari Server
    const criteriaData = @json($criteria);
    const totalCriteriaCount = {{ $criteria->count() }};
    const saveScoreUrl = "{{ route('admin.pbb-rubrik.save-scan') }}";

    // State Aplikasi
    let currentInputMode = 'camera';
    let videoStream = null;
    let isCameraActive = false;
    let selectedScores = {}; // criteria_id => { level: int, nilai: float }
    let scannedImageBase64 = null;

    // Inisialisasi awal skor default ke 0
    criteriaData.forEach(c => {
        selectedScores[c.id] = { level: 0, nilai: 0 };
    });

    // Switch Input Mode (Camera vs Upload)
    function switchInputMode(mode) {
        currentInputMode = mode;
        const camBtn = document.getElementById('tab-camera-btn');
        const upBtn = document.getElementById('tab-upload-btn');
        const camWrap = document.getElementById('mode-camera-wrap');
        const upWrap = document.getElementById('mode-upload-wrap');

        if (mode === 'camera') {
            camBtn.className = 'px-3 py-1.5 rounded-lg transition bg-white text-brand-forest shadow-xs flex items-center gap-1.5';
            upBtn.className = 'px-3 py-1.5 rounded-lg transition text-gray-600 hover:text-gray-900 flex items-center gap-1.5';
            camWrap.classList.remove('hidden');
            upWrap.classList.add('hidden');
        } else {
            stopCamera();
            upBtn.className = 'px-3 py-1.5 rounded-lg transition bg-white text-brand-forest shadow-xs flex items-center gap-1.5';
            camBtn.className = 'px-3 py-1.5 rounded-lg transition text-gray-600 hover:text-gray-900 flex items-center gap-1.5';
            upWrap.classList.remove('hidden');
            camWrap.classList.add('hidden');
        }
    }

    // Toggle Kamera
    async function toggleCamera() {
        if (isCameraActive) {
            stopCamera();
        } else {
            await startCamera();
        }
    }

    async function startCamera() {
        try {
            updateStatusTag('Menghubungkan Kamera...', 'warning');
            const constraints = {
                video: {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                }
            };
            videoStream = await navigator.mediaDevices.getUserMedia(constraints);
            const video = document.getElementById('camera-video');
            video.srcObject = videoStream;
            video.onloadedmetadata = () => {
                video.play();
                isCameraActive = true;
                document.getElementById('btn-cam-label').innerText = 'Matikan Kamera';
                document.getElementById('btn-capture').disabled = false;
                updateStatusTag('Kamera Aktif - Siap Bidik', 'success');
            };
        } catch (err) {
            console.error('Kamera error:', err);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan atau gunakan opsi Unggah Foto.');
            updateStatusTag('Kamera Gagal', 'error');
        }
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }
        const video = document.getElementById('camera-video');
        video.srcObject = null;
        isCameraActive = false;
        document.getElementById('btn-cam-label').innerText = 'Nyalakan Kamera';
        document.getElementById('btn-capture').disabled = true;
        updateStatusTag('Kamera Nonaktif', 'default');
    }

    // Capture dari Kamera
    function captureAndProcess() {
        const video = document.getElementById('camera-video');
        if (!video.videoWidth) return;

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        scannedImageBase64 = canvas.toDataURL('image/jpeg', 0.9);
        processSheetImage(canvas);
    }

    // Handle Upload File
    function handleFileUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        updateStatusTag('Memuat File...', 'warning');
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                scannedImageBase64 = e.target.result;
                processSheetImage(canvas);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Inti Pemrosesan Lembar Formulir OMR
    function processSheetImage(sourceCanvas) {
        updateStatusTag('Memverifikasi 4 Sudut...', 'warning');

        // Buka kartu canvas
        const canvasCard = document.getElementById('canvas-card');
        canvasCard.classList.remove('hidden');

        const displayCanvas = document.getElementById('omr-canvas');
        displayCanvas.width = sourceCanvas.width;
        displayCanvas.height = sourceCanvas.height;
        const ctx = displayCanvas.getContext('2d');
        ctx.drawImage(sourceCanvas, 0, 0);

        // 1. Deteksi 4 Kotak Sudut Hitam (Marker Verifikasi)
        const corners = detectCorners(sourceCanvas);
        updateCornerStatusUI(corners);

        if (!corners.allFound) {
            updateStatusTag('4 Sudut Tidak Lengkap', 'error');
            const banner = document.getElementById('verification-banner');
            banner.className = 'p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-800 flex items-center gap-2';
            banner.innerHTML = `<i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i><span>Peringatan: 4 Kotak hitam di sudut tidak terdeteksi lengkap. Pastikan seluruh lembar terlihat jelas dan pencahayaan rata.</span>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } else {
            updateStatusTag('4 Sudut Terverifikasi ✓', 'success');
            const banner = document.getElementById('verification-banner');
            banner.className = 'p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 flex items-center gap-2';
            banner.innerHTML = `<i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i><span>Lembar Penilaian Terverifikasi Resmi! (4 Kotak Hitam Sudut Terkonfirmasi).</span>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // Gambar polygon bingkai 4 sudut
        drawCornerOverlay(ctx, corners);

        // 2. Baca QR Code di Header (Identitas Regu)
        readSheetQrCode(sourceCanvas);

        // 3. Baca Coretan pada Kotak Nilai OMR
        readOmrMarkings(sourceCanvas, displayCanvas, ctx, corners);
    }

    // Deteksi 4 Kotak Sudut Hitam
    function detectCorners(canvas) {
        const W = canvas.width;
        const H = canvas.height;
        const ctx = canvas.getContext('2d');

        // Definisikan kuadran pencarian (20% tepi setiap sudut)
        const quadrants = {
            tl: { x0: 0, y0: 0, x1: Math.floor(W * 0.22), y1: Math.floor(H * 0.22) },
            tr: { x0: Math.floor(W * 0.78), y0: 0, x1: W, y1: Math.floor(H * 0.22) },
            bl: { x0: 0, y0: Math.floor(H * 0.78), x1: Math.floor(W * 0.22), y1: H },
            br: { x0: Math.floor(W * 0.78), y0: Math.floor(H * 0.78), x1: W, y1: H }
        };

        const result = {
            tl: findDarkMarkerInRegion(ctx, quadrants.tl, W, H),
            tr: findDarkMarkerInRegion(ctx, quadrants.tr, W, H),
            bl: findDarkMarkerInRegion(ctx, quadrants.bl, W, H),
            br: findDarkMarkerInRegion(ctx, quadrants.br, W, H)
        };

        result.allFound = (result.tl && result.tr && result.bl && result.br);

        // Fallback koordinat jika ada sudut yang tidak terdeteksi
        if (!result.tl) result.tl = { x: Math.floor(W * 0.03), y: Math.floor(H * 0.03), fallback: true };
        if (!result.tr) result.tr = { x: Math.floor(W * 0.97), y: Math.floor(H * 0.03), fallback: true };
        if (!result.bl) result.bl = { x: Math.floor(W * 0.03), y: Math.floor(H * 0.97), fallback: true };
        if (!result.br) result.br = { x: Math.floor(W * 0.97), y: Math.floor(H * 0.97), fallback: true };

        return result;
    }

    // Mencari gumpalan hitam (kotak marker) di kuadran
    function findDarkMarkerInRegion(ctx, region, imgW, imgH) {
        const w = region.x1 - region.x0;
        const h = region.y1 - region.y0;
        if (w <= 0 || h <= 0) return null;

        const imgData = ctx.getImageData(region.x0, region.y0, w, h);
        const data = imgData.data;

        let totalDark = 0;
        let sumX = 0;
        let sumY = 0;
        const darkThreshold = 90; // Ambang kegelapan piksel hitam

        for (let y = 0; y < h; y += 2) {
            for (let x = 0; x < w; x += 2) {
                const idx = (y * w + x) * 4;
                const r = data[idx];
                const g = data[idx + 1];
                const b = data[idx + 2];
                const lum = 0.299 * r + 0.587 * g + 0.114 * b;

                if (lum < darkThreshold) {
                    totalDark++;
                    sumX += x;
                    sumY += y;
                }
            }
        }

        // Jika jumlah piksel hitam memenuhi ukuran kotak sudut (7mm)
        if (totalDark > 60) {
            return {
                x: region.x0 + Math.floor(sumX / totalDark),
                y: region.y0 + Math.floor(sumY / totalDark),
                fallback: false
            };
        }

        return null;
    }

    // Gambar Overlay Visual 4 Sudut
    function drawCornerOverlay(ctx, corners) {
        ctx.save();
        ctx.lineWidth = Math.max(3, Math.floor(ctx.canvas.width * 0.003));
        ctx.strokeStyle = '#10B981';

        // Garis batas bingkai lembar
        ctx.beginPath();
        ctx.moveTo(corners.tl.x, corners.tl.y);
        ctx.lineTo(corners.tr.x, corners.tr.y);
        ctx.lineTo(corners.br.x, corners.br.y);
        ctx.lineTo(corners.bl.x, corners.bl.y);
        ctx.closePath();
        ctx.stroke();

        // Gambar kotak hijau presisi di setiap sudut
        const cornerSize = Math.max(10, Math.floor(ctx.canvas.width * 0.014));
        [corners.tl, corners.tr, corners.bl, corners.br].forEach((pt, i) => {
            ctx.fillStyle = pt.fallback ? 'rgba(239, 68, 68, 0.4)' : 'rgba(16, 185, 129, 0.4)';
            ctx.strokeStyle = pt.fallback ? '#EF4444' : '#059669';
            ctx.fillRect(pt.x - cornerSize / 2, pt.y - cornerSize / 2, cornerSize, cornerSize);
            ctx.strokeRect(pt.x - cornerSize / 2, pt.y - cornerSize / 2, cornerSize, cornerSize);
        });

        ctx.restore();
    }

    // Perbarui UI Tag Status Sudut
    function updateCornerStatusUI(corners) {
        const updateBox = (id, pt) => {
            const el = document.getElementById(id);
            const statusEl = el.querySelector('.corner-status');
            if (pt && !pt.fallback) {
                el.className = 'p-2.5 rounded-xl border border-emerald-300 bg-emerald-50 text-center';
                statusEl.className = 'text-xs font-black text-emerald-700 corner-status';
                statusEl.innerText = 'Terdeteksi ✓';
            } else {
                el.className = 'p-2.5 rounded-xl border border-amber-300 bg-amber-50 text-center';
                statusEl.className = 'text-xs font-black text-amber-700 corner-status';
                statusEl.innerText = 'Perlu Dicek';
            }
        };

        updateBox('corner-tag-tl', corners.tl);
        updateBox('corner-tag-tr', corners.tr);
        updateBox('corner-tag-bl', corners.bl);
        updateBox('corner-tag-br', corners.br);
    }

    // Baca QR Code Lembar
    function readSheetQrCode(canvas) {
        if (typeof jsQR === 'undefined') return;

        const ctx = canvas.getContext('2d');
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imgData.data, canvas.width, canvas.height, {
            inversionAttempts: 'dontInvert'
        });

        if (code && code.data) {
            const match = code.data.match(/PBB[:\-_]REGU[:\-_](\d+)/i);
            if (match && match[1]) {
                const reguId = match[1];
                const selectEl = document.getElementById('select-regu');
                selectEl.value = reguId;
                onReguChanged(reguId);
            }
        }
    }

    // Interpolasi Bilinear Koordinat Antara 4 Sudut
    function interpolate(corners, u, v) {
        const x = (1 - u) * (1 - v) * corners.tl.x + u * (1 - v) * corners.tr.x + (1 - u) * v * corners.bl.x + u * v * corners.br.x;
        const y = (1 - u) * (1 - v) * corners.tl.y + u * (1 - v) * corners.tr.y + (1 - u) * v * corners.bl.y + u * v * corners.br.y;
        return { x, y };
    }

    // Pembacaan Coretan pada Kotak OMR
    function readOmrMarkings(sourceCanvas, displayCanvas, ctx, corners) {
        const half = Math.ceil(criteriaData.length / 2);
        const col1 = criteriaData.slice(0, half);
        const col2 = criteriaData.slice(half);

        const sampleCtx = sourceCanvas.getContext('2d');
        let detectedCount = 0;

        // Koordinat Relatif Kotak OMR (9 Jenjang Level Nilai)
        // Kolom Kiri
        const uBoxesCol1 = [0.183, 0.207, 0.232, 0.256, 0.280, 0.305, 0.329, 0.354, 0.378];
        // Kolom Kanan
        const uBoxesCol2 = [0.669, 0.693, 0.717, 0.742, 0.766, 0.791, 0.815, 0.839, 0.864];

        const vStart = 0.188;
        const vEnd = 0.908;
        const vStep = (vEnd - vStart) / 28;

        const sampleW = Math.max(10, Math.floor(sourceCanvas.width * 0.015));
        const sampleH = Math.max(8, Math.floor(sourceCanvas.height * 0.012));

        // Proses Kolom 1 (Baris 0 s.d. 27)
        col1.forEach((c, rowIdx) => {
            const v = vStart + (rowIdx + 0.5) * vStep;
            const res = readRowMarkings(sampleCtx, corners, uBoxesCol1, v, sampleW, sampleH, c);
            applyDetectedScore(c.id, res.level, res.nilai);
            if (res.level > 0) detectedCount++;

            // Gambar kotak deteksi di canvas
            drawDetectedBox(ctx, corners, uBoxesCol1[res.level], v, sampleW, sampleH);
        });

        // Proses Kolom 2 (Baris 0 s.d. 27)
        col2.forEach((c, rowIdx) => {
            const v = vStart + (rowIdx + 0.5) * vStep;
            const res = readRowMarkings(sampleCtx, corners, uBoxesCol2, v, sampleW, sampleH, c);
            applyDetectedScore(c.id, res.level, res.nilai);
            if (res.level > 0) detectedCount++;

            // Gambar kotak deteksi di canvas
            drawDetectedBox(ctx, corners, uBoxesCol2[res.level], v, sampleW, sampleH);
        });

        recalculateGrandTotal();
        document.getElementById('detected-count-badge').innerText = `${detectedCount} / ${totalCriteriaCount} Poin Terdeteksi`;
        updateStatusTag('Nilai Berhasil Dibaca ✓', 'success');
    }

    // Membaca intensitas coretan pada 9 kotak dalam satu baris
    function readRowMarkings(ctx, corners, uBoxes, v, sW, sH, criterion) {
        const rubrik = criterion.rubrik || [];
        const densities = [];

        uBoxes.forEach(u => {
            const pt = interpolate(corners, u, v);
            const density = sampleBoxDarkness(ctx, pt.x, pt.y, sW, sH);
            densities.push(density);
        });

        // Cari kotak dengan kepadatan coretan tertinggi
        let maxIdx = 0;
        let maxVal = densities[0];
        densities.forEach((d, i) => {
            if (d > maxVal) {
                maxVal = d;
                maxIdx = i;
            }
        });

        // Hitung rata-rata kotak lainnya (baseline cetakan angka bawaan)
        const otherDensities = densities.filter((_, i) => i !== maxIdx);
        const avgOther = otherDensities.reduce((a, b) => a + b, 0) / (otherDensities.length || 1);

        // Jika terdapat coretan signifikan melebihi baseline
        if (maxVal > 0.22 && (maxVal - avgOther) > 0.07) {
            return {
                level: maxIdx,
                nilai: parseFloat(rubrik[maxIdx] || 0)
            };
        }

        // Default jika tidak dicoret: level 0 (Kosong)
        return {
            level: 0,
            nilai: parseFloat(rubrik[0] || 0)
        };
    }

    // Mengukur proporsi piksel gelap di dalam kotak
    function sampleBoxDarkness(ctx, cx, cy, w, h) {
        const x = Math.max(0, Math.floor(cx - w / 2));
        const y = Math.max(0, Math.floor(cy - h / 2));
        if (x + w > ctx.canvas.width || y + h > ctx.canvas.height) return 0;

        const imgData = ctx.getImageData(x, y, w, h);
        const data = imgData.data;
        let darkCount = 0;
        const total = (data.length / 4);

        for (let i = 0; i < data.length; i += 4) {
            const lum = 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
            if (lum < 135) darkCount++;
        }

        return darkCount / total;
    }

    // Gambar Kotak Hijau Penanda di Canvas
    function drawDetectedBox(ctx, corners, u, v, w, h) {
        const pt = interpolate(corners, u, v);
        ctx.save();
        ctx.strokeStyle = '#10B981';
        ctx.lineWidth = Math.max(2, Math.floor(ctx.canvas.width * 0.002));
        ctx.fillStyle = 'rgba(16, 185, 129, 0.35)';
        ctx.fillRect(pt.x - w / 2, pt.y - h / 2, w, h);
        ctx.strokeRect(pt.x - w / 2, pt.y - h / 2, w, h);
        ctx.restore();
    }

    // Terapkan Nilai Terdeteksi ke State & UI
    function applyDetectedScore(criteriaId, levelIdx, nilai) {
        selectedScores[criteriaId] = { level: levelIdx, nilai: nilai };

        // Perbarui highlight tombol di baris review
        for (let li = 0; li < 9; li++) {
            const btn = document.querySelector(`.btn-lvl-${criteriaId}-${li}`);
            if (btn) {
                if (li === levelIdx) {
                    btn.className = `btn-lvl-${criteriaId}-${li} flex flex-col items-center justify-center py-1 px-0.5 rounded-lg border text-center transition select-none cursor-pointer bg-brand-forest text-amber-300 border-emerald-950 ring-2 ring-amber-400 font-black shadow-sm`;
                } else {
                    btn.className = `btn-lvl-${criteriaId}-${li} flex flex-col items-center justify-center py-1 px-0.5 rounded-lg border text-center transition select-none cursor-pointer bg-gray-50 hover:bg-emerald-50 text-gray-700 border-gray-200`;
                }
            }
        }

        // Perbarui badge nilai di baris review
        const row = document.querySelector(`.criteria-item[data-id="${criteriaId}"]`);
        if (row) {
            const badge = row.querySelector('.score-badge');
            if (badge) badge.innerText = nilai;
        }
    }

    // Pilih Nilai Manual Saat Review
    function selectManualScore(criteriaId, levelIdx, nilai) {
        applyDetectedScore(criteriaId, levelIdx, nilai);
        recalculateGrandTotal();
    }

    // Hitung Ulang Grand Total
    function recalculateGrandTotal() {
        let grandTotal = 0;
        Object.values(selectedScores).forEach(s => {
            grandTotal += (parseFloat(s.nilai) || 0);
        });
        document.getElementById('grand-total-display').innerText = grandTotal.toFixed(2);
    }

    // Update Status Tag
    function updateStatusTag(text, type) {
        const tag = document.getElementById('status-tag');
        const textEl = document.getElementById('status-tag-text');
        textEl.innerText = text;

        if (type === 'success') {
            tag.className = 'text-[11px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5';
            tag.querySelector('span').className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
        } else if (type === 'warning') {
            tag.className = 'text-[11px] font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1.5';
            tag.querySelector('span').className = 'w-2 h-2 rounded-full bg-amber-500 animate-pulse';
        } else if (type === 'error') {
            tag.className = 'text-[11px] font-bold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 flex items-center gap-1.5';
            tag.querySelector('span').className = 'w-2 h-2 rounded-full bg-rose-500';
        } else {
            tag.className = 'text-[11px] font-bold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 border border-gray-200 flex items-center gap-1.5';
            tag.querySelector('span').className = 'w-2 h-2 rounded-full bg-gray-400';
        }
    }

    function onReguChanged(reguId) {
        // Bisa digunakan untuk logging atau validasi regu
    }

    function resetForm() {
        criteriaData.forEach(c => {
            applyDetectedScore(c.id, 0, 0);
        });
        recalculateGrandTotal();
        document.getElementById('canvas-card').classList.add('hidden');
        document.getElementById('image-upload-input').value = '';
        scannedImageBase64 = null;
        updateStatusTag('Siap Memindai', 'default');
    }

    // Simpan Nilai ke Database
    function submitScannedScore() {
        const reguId = document.getElementById('select-regu').value;
        const juriId = document.getElementById('select-juri').value;

        if (!reguId) {
            alert('Mohon pilih Regu Peserta terlebih dahulu.');
            document.getElementById('select-regu').focus();
            return;
        }
        if (!juriId) {
            alert('Mohon pilih Juri Penilai.');
            document.getElementById('select-juri').focus();
            return;
        }

        const btnSave = document.getElementById('btn-save-score');
        btnSave.disabled = true;
        btnSave.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i><span>Menyimpan Nilai...</span>`;
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Siapkan array scores
        const scoresPayload = [];
        criteriaData.forEach(c => {
            const scoreItem = selectedScores[c.id] || { level: 0, nilai: 0 };
            scoresPayload.push({
                criteria_id: c.id,
                nilai: scoreItem.nilai
            });
        });

        const payload = {
            _token: "{{ csrf_token() }}",
            mata_lomba_id: {{ $lomba->id }},
            regu_profile_id: reguId,
            juri_id: juriId,
            scores: scoresPayload,
            scanned_image: scannedImageBase64,
            catatan: 'Nilai terbaca melalui OMR Scanner Formulir Cetak PBB'
        };

        fetch(saveScoreUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`✅ BERHASIL!\n\n${data.message}\nTotal Nilai: ${data.total_nilai} Poin`);
            } else {
                alert('Gagal menyimpan nilai: ' + (data.message || 'Terjadi kesalahan sistem.'));
            }
        })
        .catch(err => {
            console.error('Save error:', err);
            alert('Terjadi kesalahan jaringan saat menyimpan nilai.');
        })
        .finally(() => {
            btnSave.disabled = false;
            btnSave.innerHTML = `<i data-lucide="save" class="w-4 h-4"></i><span>Simpan Nilai ke Sistem</span>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    }
</script>
@endpush
