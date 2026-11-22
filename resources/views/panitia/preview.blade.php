@extends('layouts.main')

@section('title', 'Preview & Download ID Card Panitia - ' . $panitia->nama)

@section('content')
<div class="min-h-screen py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-6">

        @if(session('success'))
            <div class="bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-4 sm:p-5 shadow-sm flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="check-check" class="w-5 h-5"></i>
                </div>
                <div class="text-xs sm:text-sm text-emerald-950">
                    <h3 class="font-black uppercase text-emerald-900">Pendaftaran ID Card Panitia Berhasil!</h3>
                    <p class="mt-0.5">{{ session('success') }} Silakan periksa pratinjau ID Card Anda di bawah dan unduh sebagai PDF atau Gambar PNG.</p>
                </div>
            </div>
        @endif

        {{-- Header Bar --}}
        <div class="bg-gradient-to-r from-brand-forest via-emerald-900 to-emerald-950 rounded-3xl p-6 sm:p-7 text-white shadow-xl border-2 border-amber-400/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950 mb-1.5">
                    <i data-lucide="id-card" class="w-3.5 h-3.5"></i> Pratinjau ID Card Resmi
                </span>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight uppercase">
                    ID Card Panitia &mdash; {{ $panitia->nama }}
                </h1>
                <p class="text-xs text-emerald-100 mt-1">
                    Data otomatis terhubung dengan panel ID Card &amp; Sertifikat Panitia Admin.
                </p>
            </div>
            <a href="{{ route('panitia.form') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition shrink-0">
                <i data-lucide="plus-circle" class="w-4 h-4 text-amber-300"></i>
                <span>Isi Formulir Lainnya</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- LEFT: VISUAL ID CARD PREVIEW --}}
            <div class="lg:col-span-6 bg-white rounded-3xl border border-gray-200 shadow-lg p-6 flex flex-col items-center">
                <div class="w-full flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <span class="text-xs font-black text-brand-forest uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                        Preview Tampilan Kartu
                    </span>
                    <span class="text-[11px] font-mono font-bold text-gray-500">85,6 &times; 125 mm</span>
                </div>

                <div class="p-3 bg-gray-100 rounded-2xl border border-dashed border-gray-300 shadow-inner">
                    <div id="panitia-id-card-preview"
                        style="width: 324px; height: 472px; background-image: linear-gradient(to right, rgba(217, 119, 6, 0.14) 1px, transparent 1px), linear-gradient(to bottom, rgba(217, 119, 6, 0.14) 1px, transparent 1px); background-size: 28px 28px;"
                        class="bg-white rounded-xl overflow-hidden relative border-2 border-[#0B2317] shadow-md flex flex-col justify-between p-3.5">

                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-[#0B2317] to-[#1B4332] text-white rounded-lg p-2 text-center border border-amber-400/40 shadow-xs">
                            <div class="flex items-center justify-between px-1">
                                <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo"
                                    class="w-7 h-7 rounded-full object-cover border border-amber-400 shadow-xs">
                                <div>
                                    <span class="text-[11px] font-black tracking-tight text-white block uppercase leading-tight">
                                        Spencerone Camp <span class="text-amber-400">II</span>
                                    </span>
                                    <span class="text-[9px] text-amber-200 font-bold block uppercase tracking-wider">
                                        SMPN 1 Cerbon &bull; Barito Kuala
                                    </span>
                                </div>
                                <div class="px-1.5 py-0.5 bg-amber-400 text-[#0B2317] rounded-md text-[8px] font-black uppercase">
                                    PANITIA
                                </div>
                            </div>
                        </div>

                        {{-- Avatar & Identitas Panitia --}}
                        <div class="my-auto text-center py-1">
                            <div class="relative w-[96px] h-[120px] aspect-[4/5] mx-auto mb-2.5">
                                <div class="w-full h-full rounded-xl bg-white border-2 border-brand-forest overflow-hidden flex items-center justify-center shadow-inner">
                                    @if($panitia->foto_path)
                                        <img src="{{ asset('storage/' . $panitia->foto_path) }}" alt="{{ $panitia->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-center p-2 bg-emerald-50 w-full h-full flex flex-col items-center justify-center">
                                            <i data-lucide="user" class="w-10 h-10 text-emerald-800/40 mx-auto"></i>
                                            <span class="text-[8px] font-bold text-emerald-800/60 uppercase mt-1">Foto 4:5</span>
                                        </div>
                                    @endif
                                </div>
                                <span class="absolute -bottom-2.5 inset-x-0 mx-auto w-fit px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider text-emerald-950 bg-amber-400 shadow-sm border border-white max-w-[160px] truncate">
                                    {{ strtoupper($panitia->jabatan) }}
                                </span>
                            </div>

                            <h2 class="text-sm font-black text-gray-900 tracking-tight leading-tight mt-3 uppercase px-1 line-clamp-2">
                                {{ $panitia->nama }}
                            </h2>

                            @if($panitia->divisi)
                                <p class="text-[9.5px] font-bold text-emerald-800 mt-0.5 uppercase">{{ $panitia->divisi }}</p>
                            @endif

                            <p class="text-[9px] font-semibold text-gray-600 mt-0.5 truncate px-2">
                                {{ $panitia->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}
                            </p>
                        </div>

                        {{-- Card Footer --}}
                        <div class="bg-gradient-to-r from-[#0B2317] via-[#133A27] to-[#0A2015] text-white rounded-2xl p-2.5 border border-amber-400/40 shadow-sm relative overflow-hidden shrink-0">
                            <div class="flex items-center justify-between gap-2 relative z-10">
                                <div class="flex items-center gap-2 overflow-hidden flex-1">
                                    <div class="shrink-0 flex items-center justify-center">
                                        <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Maskot Resmi" class="h-14 w-auto object-contain drop-shadow-md">
                                    </div>
                                    <div class="space-y-0.5 overflow-hidden text-left">
                                        <span class="text-[7px] font-extrabold uppercase tracking-wider text-amber-300 block">KARTU TANDA PANITIA</span>
                                        <span class="text-[10px] font-black text-white uppercase block truncate leading-tight">{{ $panitia->jabatan }}</span>
                                        <span class="text-[8px] font-bold text-emerald-200 block truncate">Gudep 03.059 - 03.060</span>
                                    </div>
                                </div>

                                <div class="shrink-0 flex flex-col items-center">
                                    <div class="bg-white p-1 rounded-xl shadow-md border border-amber-400/50 flex items-center justify-center w-[68px] h-[68px]">
                                        <img src="{{ $qrCode }}" alt="QR Verifikasi" class="w-[60px] h-[60px] object-contain rounded-lg block">
                                    </div>
                                    <span class="text-[6px] font-mono text-amber-300 font-extrabold mt-1 uppercase tracking-wider">VERIFIKASI ID</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: DOWNLOAD & ACTIONS PANEL --}}
            <div class="lg:col-span-6 space-y-5">
                <div class="bg-white rounded-3xl border border-gray-200 shadow-lg p-6 space-y-5">
                    <div>
                        <h2 class="text-base font-black text-brand-forest uppercase tracking-wide flex items-center gap-2">
                            <i data-lucide="download-cloud" class="w-5 h-5 text-emerald-600"></i>
                            Unduh &amp; Cetak ID Card Panitia
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Pilih format unduhan sesuai kebutuhan Anda: file PDF siap potong untuk dicetak, atau gambar PNG resolusi tinggi untuk disimpan di HP.
                        </p>
                    </div>

                    <div class="space-y-3">
                        {{-- Download PDF --}}
                        <a href="{{ route('panitia.download-pdf', $panitia->id) }}"
                            class="w-full px-5 py-3.5 rounded-2xl bg-brand-forest hover:bg-emerald-900 text-white font-black text-sm shadow-md transition flex items-center justify-between gap-3">
                            <span class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-5 h-5 text-amber-400"></i>
                                </span>
                                <span class="text-left">
                                    <span class="block leading-tight">Download ID Card (.PDF)</span>
                                    <span class="text-[11px] font-normal text-emerald-100">Siap cetak A4 dengan garis potong presisi</span>
                                </span>
                            </span>
                            <i data-lucide="download" class="w-5 h-5 text-amber-300 shrink-0"></i>
                        </a>

                        {{-- Download PNG --}}
                        <button type="button" onclick="downloadPanitiaIdCardPng()" id="btn-download-png"
                            class="w-full px-5 py-3.5 rounded-2xl bg-amber-400 hover:bg-amber-300 text-emerald-950 font-black text-sm shadow-md transition flex items-center justify-between gap-3 cursor-pointer">
                            <span class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-emerald-950/10 flex items-center justify-center">
                                    <i data-lucide="image" class="w-5 h-5 text-emerald-950"></i>
                                </span>
                                <span class="text-left">
                                    <span class="block leading-tight">Download Gambar (.PNG)</span>
                                    <span class="text-[11px] font-semibold text-emerald-900/80">Resolusi tinggi langsung ke perangkat</span>
                                </span>
                            </span>
                            <i data-lucide="download" class="w-5 h-5 text-emerald-950 shrink-0"></i>
                        </button>

                        {{-- Print Preview Page --}}
                        <a href="{{ route('panitia.print', $panitia->id) }}" target="_blank"
                            class="w-full px-5 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs sm:text-sm transition flex items-center justify-between gap-3 border border-gray-200">
                            <span class="flex items-center gap-2.5">
                                <i data-lucide="printer" class="w-4 h-4 text-brand-forest"></i>
                                <span>Buka Halaman Cetak Langsung (Print Browser)</span>
                            </span>
                            <i data-lucide="external-link" class="w-4 h-4 text-gray-500 shrink-0"></i>
                        </a>
                    </div>

                    {{-- Ringkasan Data --}}
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200/80 space-y-2 text-xs">
                        <div class="font-black text-gray-800 uppercase tracking-wider text-[11px] pb-1 border-b border-gray-200">
                            Detail Data Tercatat
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500">Nama Lengkap</span>
                            <span class="col-span-2 font-bold text-gray-900">{{ $panitia->nama }}</span>

                            <span class="text-gray-500">Jabatan</span>
                            <span class="col-span-2 font-bold text-emerald-800">{{ $panitia->jabatan }}</span>

                            @if($panitia->divisi)
                                <span class="text-gray-500">Bidang / Seksi</span>
                                <span class="col-span-2 font-semibold text-gray-800">{{ $panitia->divisi }}</span>
                            @endif

                            <span class="text-gray-500">Asal Instansi</span>
                            <span class="col-span-2 font-semibold text-gray-800">{{ $panitia->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}</span>

                            <span class="text-gray-500">Verifikasi QR</span>
                            <span class="col-span-2">
                                <a href="{{ $verifyUrl }}" target="_blank" class="text-brand-forest font-bold underline hover:text-emerald-700 inline-flex items-center gap-1">
                                    <span>Cek Laman Verifikasi</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<canvas id="panitia-card-export-canvas" width="856" height="1250" class="hidden"></canvas>
@endsection

@push('scripts')
<script>
    const panitiaCardPayload = {
        nama: @json(strtoupper($panitia->nama)),
        jabatan: @json(strtoupper($panitia->jabatan)),
        divisi: @json($panitia->divisi ? strtoupper($panitia->divisi) : ''),
        instansi: @json($panitia->asal_instansi ?: 'SMP Negeri 1 Cerbon'),
        slug: @json(\Illuminate\Support\Str::slug($panitia->nama)),
        logoUrl: @json(asset('images/season2/logo-season2.jpg')),
        mascotUrl: @json(asset('images/season2/mascot-duo.png')),
        fotoUrl: @json($panitia->foto_path ? asset('storage/' . $panitia->foto_path) : ''),
        qrUrl: @json($qrCode),
    };

    function loadCardImage(src) {
        return new Promise((resolve) => {
            if (!src) return resolve(null);
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => resolve(img);
            img.onerror = () => resolve(null);
            img.src = src;
        });
    }

    function drawRoundRect(ctx, x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
        ctx.lineTo(x + w, y + h - r);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        ctx.lineTo(x + r, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.closePath();
    }

    function wrapCenteredText(ctx, text, x, startY, maxWidth, lineHeight, maxLines) {
        const words = String(text || '').split(' ');
        const lines = [];
        let line = '';

        for (let i = 0; i < words.length; i++) {
            const testLine = line ? line + ' ' + words[i] : words[i];
            if (ctx.measureText(testLine).width > maxWidth && line) {
                lines.push(line);
                line = words[i];
            } else {
                line = testLine;
            }
        }
        if (line) lines.push(line);

        const slice = lines.slice(0, maxLines);
        slice.forEach((ln, idx) => {
            ctx.fillText(ln, x, startY + idx * lineHeight);
        });
        return slice.length;
    }

    async function downloadPanitiaIdCardPng() {
        const btn = document.getElementById('btn-download-png');
        const canvas = document.getElementById('panitia-card-export-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        if (btn) btn.disabled = true;

        try {
            const [logoImg, mascotImg, fotoImg, qrImg] = await Promise.all([
                loadCardImage(panitiaCardPayload.logoUrl),
                loadCardImage(panitiaCardPayload.mascotUrl),
                loadCardImage(panitiaCardPayload.fotoUrl),
                loadCardImage(panitiaCardPayload.qrUrl),
            ]);

            const W = 856;
            const H = 1250;
            ctx.clearRect(0, 0, W, H);

            // Base Card & Border
            drawRoundRect(ctx, 4, 4, W - 8, H - 8, 32);
            ctx.fillStyle = '#FFFFFF';
            ctx.fill();
            ctx.lineWidth = 8;
            ctx.strokeStyle = '#0B2317';
            ctx.stroke();

            // Subtle Cyber Grid inside Card
            ctx.save();
            drawRoundRect(ctx, 8, 8, W - 16, H - 16, 28);
            ctx.clip();
            ctx.strokeStyle = 'rgba(217, 119, 6, 0.14)';
            ctx.lineWidth = 2;
            for (let x = 0; x <= W; x += 68) {
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, H);
                ctx.stroke();
            }
            for (let y = 0; y <= H; y += 68) {
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(W, y);
                ctx.stroke();
            }
            ctx.restore();

            // Header Box
            drawRoundRect(ctx, 36, 36, W - 72, 128, 20);
            const hdrGrad = ctx.createLinearGradient(36, 36, W - 36, 164);
            hdrGrad.addColorStop(0, '#0B2317');
            hdrGrad.addColorStop(1, '#1B4332');
            ctx.fillStyle = hdrGrad;
            ctx.fill();
            ctx.lineWidth = 3;
            ctx.strokeStyle = 'rgba(251, 191, 36, 0.55)';
            ctx.stroke();

            if (logoImg) {
                ctx.save();
                ctx.beginPath();
                ctx.arc(98, 100, 36, 0, Math.PI * 2);
                ctx.closePath();
                ctx.clip();
                ctx.drawImage(logoImg, 62, 64, 72, 72);
                ctx.restore();
                ctx.beginPath();
                ctx.arc(98, 100, 36, 0, Math.PI * 2);
                ctx.lineWidth = 3;
                ctx.strokeStyle = '#FBBF24';
                ctx.stroke();
            }

            ctx.textAlign = 'center';
            ctx.fillStyle = '#FFFFFF';
            ctx.font = '900 28px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText('SPENCERONE CAMP II', W / 2 - 12, 92);
            ctx.fillStyle = '#FDE68A';
            ctx.font = '700 18px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText('SMPN 1 CERBON • BARITO KUALA', W / 2 - 12, 126);

            // PANITIA Pill
            drawRoundRect(ctx, W - 178, 76, 120, 48, 12);
            ctx.fillStyle = '#FBBF24';
            ctx.fill();
            ctx.fillStyle = '#0B2317';
            ctx.font = '900 20px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText('PANITIA', W - 118, 108);

            // Photo Frame (4:5)
            const photoW = 264;
            const photoH = 330;
            const photoX = (W - photoW) / 2;
            const photoY = 230;
            drawRoundRect(ctx, photoX, photoY, photoW, photoH, 24);
            ctx.fillStyle = '#ECFDF5';
            ctx.fill();

            if (fotoImg) {
                ctx.save();
                drawRoundRect(ctx, photoX, photoY, photoW, photoH, 24);
                ctx.clip();
                const scale = Math.max(photoW / fotoImg.width, photoH / fotoImg.height);
                const dw = fotoImg.width * scale;
                const dh = fotoImg.height * scale;
                ctx.drawImage(fotoImg, photoX + (photoW - dw) / 2, photoY + (photoH - dh) / 2, dw, dh);
                ctx.restore();
            } else {
                ctx.fillStyle = '#065F46';
                ctx.font = '800 26px "Plus Jakarta Sans", Arial, sans-serif';
                ctx.fillText('FOTO 4:5', W / 2, photoY + photoH / 2 + 8);
            }

            drawRoundRect(ctx, photoX, photoY, photoW, photoH, 24);
            ctx.lineWidth = 6;
            ctx.strokeStyle = '#0B2317';
            ctx.stroke();

            // Jabatan Pill
            ctx.font = '900 22px "Plus Jakarta Sans", Arial, sans-serif';
            const pillW = Math.min(540, Math.max(220, ctx.measureText(panitiaCardPayload.jabatan).width + 64));
            const pillX = (W - pillW) / 2;
            const pillY = photoY + photoH - 26;
            drawRoundRect(ctx, pillX, pillY, pillW, 50, 25);
            ctx.fillStyle = '#FBBF24';
            ctx.fill();
            ctx.lineWidth = 5;
            ctx.strokeStyle = '#FFFFFF';
            ctx.stroke();
            ctx.fillStyle = '#0B2317';
            ctx.fillText(panitiaCardPayload.jabatan, W / 2, pillY + 33);

            // Name, Divisi, Instansi
            ctx.fillStyle = '#111827';
            ctx.font = '900 36px "Plus Jakarta Sans", Arial, sans-serif';
            const lineCount = wrapCenteredText(ctx, panitiaCardPayload.nama, W / 2, 660, W - 96, 46, 2);

            let nextY = 660 + lineCount * 46;
            if (panitiaCardPayload.divisi) {
                ctx.fillStyle = '#065F46';
                ctx.font = '800 24px "Plus Jakarta Sans", Arial, sans-serif';
                ctx.fillText(panitiaCardPayload.divisi, W / 2, nextY + 6);
                nextY += 38;
            }
            ctx.fillStyle = '#4B5563';
            ctx.font = '700 22px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText(panitiaCardPayload.instansi, W / 2, nextY + 6);

            // Footer Container
            const ftrY = H - 270;
            const ftrH = 234;
            drawRoundRect(ctx, 36, ftrY, W - 72, ftrH, 32);
            const ftrGrad = ctx.createLinearGradient(36, ftrY, W - 36, ftrY + ftrH);
            ftrGrad.addColorStop(0, '#0B2317');
            ftrGrad.addColorStop(0.5, '#133A27');
            ftrGrad.addColorStop(1, '#0A2015');
            ctx.fillStyle = ftrGrad;
            ctx.fill();
            ctx.lineWidth = 3;
            ctx.strokeStyle = 'rgba(251, 191, 36, 0.5)';
            ctx.stroke();

            if (mascotImg) {
                const mH = 175;
                const mW = (mascotImg.width / mascotImg.height) * mH;
                ctx.drawImage(mascotImg, 60, ftrY + (ftrH - mH) / 2, mW, mH);
            }

            ctx.textAlign = 'left';
            ctx.fillStyle = '#FCD34D';
            ctx.font = '800 18px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText('KARTU TANDA PANITIA', 210, ftrY + 84);
            ctx.fillStyle = '#FFFFFF';
            ctx.font = '900 26px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText(panitiaCardPayload.jabatan.slice(0, 26), 210, ftrY + 122);
            ctx.fillStyle = '#A7F3D0';
            ctx.font = '700 20px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText('Gudep 03.059 - 03.060', 210, ftrY + 158);

            // QR Code Frame
            const qrBoxSize = 166;
            const qrX = W - 226;
            const qrY = ftrY + 22;
            drawRoundRect(ctx, qrX, qrY, qrBoxSize, qrBoxSize, 22);
            ctx.fillStyle = '#FFFFFF';
            ctx.fill();
            ctx.lineWidth = 3;
            ctx.strokeStyle = 'rgba(251, 191, 36, 0.65)';
            ctx.stroke();

            if (qrImg) {
                ctx.drawImage(qrImg, qrX + 12, qrY + 12, qrBoxSize - 24, qrBoxSize - 24);
            }

            ctx.textAlign = 'center';
            ctx.fillStyle = '#FCD34D';
            ctx.font = '800 16px "Plus Jakarta Sans", Arial, sans-serif';
            ctx.fillText('VERIFIKASI ID', qrX + qrBoxSize / 2, qrY + qrBoxSize + 24);

            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = `ID-Card-Panitia-${panitiaCardPayload.slug || 'panitia'}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } finally {
            if (btn) btn.disabled = false;
        }
    }
</script>
@endpush
