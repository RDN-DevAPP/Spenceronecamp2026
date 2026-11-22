<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sertifikat Peserta Resmi - Spencerone Camp II</title>
    <style>
        @page {
            size: 297mm 210mm landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, Georgia, serif;
            color: #111827;
            background-color: #FFFFFF;
            -webkit-print-color-adjust: exact;
        }
        .page-cert {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            page-break-after: always;
        }
        .page-cert:last-child {
            page-break-after: avoid;
        }
        .bg-template {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 1;
        }

        /* Nomor Surat: Posisi dinamis yang diatur admin */
        .cert-nomor-box {
            position: absolute;
            top: {{ $settings['cert_pos_nomor_top'] ?? 35.2 }}%;
            left: {{ $settings['cert_pos_nomor_left'] ?? 10.0 }}%;
            width: {{ $settings['cert_pos_nomor_width'] ?? 80.0 }}%;
            text-align: {{ $settings['cert_pos_nomor_align'] ?? 'center' }};
            z-index: 10;
        }
        .cert-nomor-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ $settings['cert_pos_nomor_size'] ?? 11 }}pt;
            font-weight: bold;
            color: #1F2937;
            letter-spacing: 0.5px;
            display: inline-block;
            background-color: transparent;
            padding: 0 4px;
        }

        /* Nama Lengkap Peserta: Posisi dinamis yang diatur admin */
        .cert-nama-box {
            position: absolute;
            top: {{ $settings['cert_pos_nama_top'] ?? 43.8 }}%;
            left: {{ $settings['cert_pos_nama_left'] ?? 10.0 }}%;
            width: {{ $settings['cert_pos_nama_width'] ?? 80.0 }}%;
            text-align: {{ $settings['cert_pos_nama_align'] ?? 'center' }};
            z-index: 10;
        }
        .cert-nama-text {
            font-family: 'Times New Roman', Times, Georgia, serif;
            font-size: {{ $settings['cert_pos_nama_size'] ?? 24 }}pt;
            font-weight: bold;
            color: #0B2317;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-block;
            border-bottom: 2px solid #0B2317;
            padding-bottom: 2px;
            background-color: transparent;
        }

        /* Keterangan / Jabatan / Peringkat Juara: Posisi dinamis yang diatur admin */
        .cert-ket-box {
            position: absolute;
            top: {{ $settings['cert_pos_ket_top'] ?? 52.8 }}%;
            left: {{ $settings['cert_pos_ket_left'] ?? 10.0 }}%;
            width: {{ $settings['cert_pos_ket_width'] ?? 80.0 }}%;
            text-align: {{ $settings['cert_pos_ket_align'] ?? 'center' }};
            z-index: 10;
        }
        .cert-ket-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ $settings['cert_pos_ket_size'] ?? 13 }}pt;
            font-weight: bold;
            color: #1B4332;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-block;
            background-color: transparent;
        }

        /* Nama Mata Lomba (khusus Sertifikat Juara): Posisi dinamis yang diatur admin */
        .cert-lomba-box {
            position: absolute;
            top: {{ $settings['cert_pos_lomba_top'] ?? 59.5 }}%;
            left: {{ $settings['cert_pos_lomba_left'] ?? 10.0 }}%;
            width: {{ $settings['cert_pos_lomba_width'] ?? 80.0 }}%;
            text-align: {{ $settings['cert_pos_lomba_align'] ?? 'center' }};
            z-index: 10;
        }
        .cert-lomba-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ $settings['cert_pos_lomba_size'] ?? 13 }}pt;
            font-weight: bold;
            color: #0B2317;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-block;
            background-color: transparent;
        }

        /* Tanda Tangan Resmi (TTD): Posisi dinamis yang diatur admin */
        .cert-ttd-box {
            position: absolute;
            top: {{ $settings['cert_pos_ttd_top'] ?? 72.0 }}%;
            left: {{ $settings['cert_pos_ttd_left'] ?? 68.0 }}%;
            width: {{ $settings['cert_pos_ttd_width'] ?? 18.0 }}%;
            text-align: center;
            z-index: 12;
        }
        .cert-ttd-img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        @php
            $qrWidthPct = max(4.0, min(35.0, (float) ($settings['cert_pos_qr_width'] ?? 8.0)));
            $qrWidthMm = round(297 * ($qrWidthPct / 100), 2);
            $qrImgMm = round($qrWidthMm * 0.73, 2);
            $qrScale = $qrWidthPct / 8.0;
        @endphp

        /* QR-Code & Keterangan Terbit Online: Posisi & ukuran dinamis yang diatur admin */
        .cert-qr-container {
            position: absolute;
            top: {{ $settings['cert_pos_qr_top'] ?? 82.0 }}%;
            left: {{ $settings['cert_pos_qr_left'] ?? 88.0 }}%;
            width: {{ $qrWidthPct }}%;
            text-align: center;
            z-index: 15;
        }
        .qr-badge-wrapper {
            background-color: #FFFFFF;
            border: 1px solid #9CA3AF;
            padding: 0.8mm;
            border-radius: 1.5mm;
            display: inline-block;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .qr-badge-img {
            width: {{ $qrImgMm }}mm;
            height: {{ $qrImgMm }}mm;
            display: block;
        }
        .qr-title-badge {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ round(4.8 * $qrScale, 2) }}pt;
            font-weight: 800;
            color: #1B4332;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 0.8mm;
            line-height: 1;
        }
        .qr-online-caption {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ round(4.0 * $qrScale, 2) }}pt;
            color: #374151;
            line-height: 1.15;
            margin-top: 0.3mm;
        }
        .qr-verify-badge {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ round(3.8 * $qrScale, 2) }}pt;
            color: #047857;
            font-weight: 800;
            text-transform: uppercase;
            margin-top: 0.3mm;
            letter-spacing: 0.2px;
        }
    </style>
</head>
<body>

    @foreach($certificates as $c)
        @php
            $m = $c['anggota'] ?? null;
            $regu = $c['regu'] ?? null;
            $recipientName = $c['nama'] ?? ($m ? $m->nama : '-');
            $keteranganText = $c['keterangan'] ?? null;
            $lombaText = $c['lomba'] ?? null;
            $nomor = $c['nomor'];
            $qrCode = $c['qrCode'] ?? null;
            $bgTemplate = $pesertaTemplatePath;
        @endphp

        <div class="page-cert">
            @if(!empty($bgTemplate) && file_exists($bgTemplate))
                <img src="{{ $bgTemplate }}" class="bg-template" alt="Background Template">
            @endif

            {{-- 1. Nomor Surat: Otomatis di bawah kalimat "Sertifikat" --}}
            <div class="cert-nomor-box">
                <span class="cert-nomor-text">Nomor : {{ $nomor }}</span>
            </div>

            {{-- 2. Nama Lengkap: Otomatis di bawah kalimat "Diberikan kepada :" --}}
            <div class="cert-nama-box">
                <span class="cert-nama-text">{{ $recipientName }}</span>
            </div>

            {{-- 2b. Keterangan (Jabatan Panitia / Peringkat Juara / Jabatan Regu) --}}
            @if(!empty($keteranganText))
                <div class="cert-ket-box">
                    <span class="cert-ket-text">{{ $keteranganText }}</span>
                </div>
            @endif

            {{-- 2c. Nama Mata Lomba (khusus Sertifikat Juara Lomba) --}}
            @if(!empty($lombaText))
                <div class="cert-lomba-box">
                    <span class="cert-lomba-text">{{ $lombaText }}</span>
                </div>
            @endif

            {{-- 3. Tanda Tangan Resmi (TTD) sesuai jumlah & posisi masing-masing --}}
            @if(!empty($signatures))
                @foreach($signatures as $sig)
                    @if(!empty($sig['path']) && file_exists($sig['path']))
                        <div class="cert-ttd-box" style="top: {{ $sig['top'] }}%; left: {{ $sig['left'] }}%; width: {{ $sig['width'] }}%;">
                            <img src="{{ $sig['path'] }}" class="cert-ttd-img" alt="Tanda Tangan {{ $sig['index'] }}">
                        </div>
                    @endif
                @endforeach
            @elseif(!empty($signaturePath) && file_exists($signaturePath) && ($settings['cert_show_ttd'] ?? '1') == '1')
                <div class="cert-ttd-box">
                    <img src="{{ $signaturePath }}" class="cert-ttd-img" alt="Tanda Tangan">
                </div>
            @endif

            {{-- 4. QR Code & Keterangan Resmi Terbit Online: Posisi dinamis yang diatur admin --}}
            @if(!empty($qrCode) && ($settings['cert_show_qr'] ?? '1') == '1')
                <div class="cert-qr-container">
                    <div class="qr-badge-wrapper">
                        <img src="{{ $qrCode }}" class="qr-badge-img" alt="QR Verifikasi">
                    </div>
                    <div class="qr-title-badge">
                        DOKUMEN RESMI
                    </div>
                    <div class="qr-online-caption">
                        Sertifikat ini diterbitkan secara online
                    </div>
                    <div class="qr-verify-badge">
                        SCAN UNTUK VERIFIKASI
                    </div>
                </div>
            @endif
        </div>
    @endforeach

</body>
</html>
