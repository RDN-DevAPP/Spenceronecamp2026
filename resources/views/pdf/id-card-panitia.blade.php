<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ID Card Panitia - {{ $panitia->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 15mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            background-color: #ffffff;
            color: #111827;
        }
        .sheet-wrap {
            width: 100%;
            text-align: center;
            padding-top: 10mm;
        }
        .sheet-note {
            font-size: 9pt;
            color: #4b5563;
            margin-bottom: 6mm;
        }
        .cut-guide {
            display: inline-block;
            padding: 3mm;
            border: 1px dashed #9ca3af;
            border-radius: 10px;
        }
        .id-card {
            width: 85.6mm;
            height: 125mm;
            border: 2px solid #0B2317;
            border-radius: 10px;
            background-color: #fffdf8;
            overflow: hidden;
            position: relative;
            text-align: center;
        }
        .card-inner {
            padding: 3.5mm;
            height: 118mm;
            position: relative;
        }
        .card-header {
            background-color: #0B2317;
            color: #ffffff;
            border: 1.5px solid #F59E0B;
            border-radius: 7px;
            padding: 2.2mm 2.5mm;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 8mm;
            height: 8mm;
            border-radius: 50%;
            border: 1px solid #F59E0B;
            object-fit: cover;
        }
        .header-title {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #ffffff;
            line-height: 1.15;
        }
        .header-title span {
            color: #FBBF24;
        }
        .header-sub {
            font-size: 6pt;
            font-weight: bold;
            color: #FDE68A;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 0.5mm;
        }
        .badge-panitia {
            display: inline-block;
            background-color: #FBBF24;
            color: #0B2317;
            font-size: 6.5pt;
            font-weight: bold;
            padding: 1mm 1.8mm;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .photo-section {
            margin-top: 6mm;
            text-align: center;
        }
        .photo-frame {
            width: 26mm;
            height: 32.5mm;
            margin: 0 auto;
            border: 2px solid #0B2317;
            border-radius: 8px;
            background-color: #ECFDF5;
            overflow: hidden;
            text-align: center;
        }
        .photo-frame img {
            width: 26mm;
            height: 32.5mm;
            object-fit: cover;
            display: block;
        }
        .photo-placeholder {
            padding-top: 12mm;
            font-size: 7.5pt;
            font-weight: bold;
            color: #065F46;
            text-transform: uppercase;
        }
        .jabatan-pill {
            display: inline-block;
            margin-top: -2.5mm;
            background-color: #FBBF24;
            color: #0B2317;
            border: 1.5px solid #ffffff;
            border-radius: 99px;
            padding: 1mm 3.5mm;
            font-size: 7.2pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .member-name {
            margin-top: 3mm;
            font-size: 11pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            line-height: 1.2;
            padding: 0 2mm;
        }
        .member-divisi {
            margin-top: 1mm;
            font-size: 7.5pt;
            font-weight: bold;
            color: #065F46;
            text-transform: uppercase;
        }
        .member-instansi {
            margin-top: 1mm;
            font-size: 7pt;
            color: #4B5563;
            font-weight: bold;
        }
        .card-footer {
            position: absolute;
            left: 3.5mm;
            right: 3.5mm;
            bottom: 3.5mm;
            background-color: #0B2317;
            border: 1.5px solid #F59E0B;
            border-radius: 10px;
            padding: 2.2mm 2.5mm;
            color: #ffffff;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-mascot {
            width: 12mm;
            height: 15mm;
            object-fit: contain;
        }
        .footer-label {
            font-size: 5.5pt;
            font-weight: bold;
            color: #FCD34D;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .footer-role {
            font-size: 8pt;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            margin-top: 0.5mm;
        }
        .footer-gudep {
            font-size: 6.2pt;
            color: #A7F3D0;
            margin-top: 0.5mm;
        }
        .qr-box {
            width: 17.5mm;
            height: 17.5mm;
            background-color: #ffffff;
            border-radius: 6px;
            border: 1px solid #FBBF24;
            padding: 1mm;
            text-align: center;
            margin-left: auto;
        }
        .qr-box img {
            width: 15.5mm;
            height: 15.5mm;
            display: block;
        }
        .qr-caption {
            font-size: 5pt;
            font-weight: bold;
            color: #FCD34D;
            text-transform: uppercase;
            margin-top: 0.8mm;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sheet-wrap">
        <p class="sheet-note">
            Kartu Tanda Panitia Resmi &bull; Ukuran Standar 85,6 mm &times; 125 mm (Gunting mengikuti garis putus-putus)
        </p>
        <div class="cut-guide">
            <div class="id-card">
                <div class="card-inner">
                    {{-- Header --}}
                    <div class="card-header">
                        <table class="header-table">
                            <tr>
                                <td style="width: 10mm; text-align: left; vertical-align: middle;">
                                    @if(!empty($logoDataUri))
                                        <img src="{{ $logoDataUri }}" class="header-logo" alt="Logo">
                                    @endif
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <div class="header-title">Spencerone Camp <span>II</span></div>
                                    <div class="header-sub">SMPN 1 Cerbon &bull; Barito Kuala</div>
                                </td>
                                <td style="width: 14mm; text-align: right; vertical-align: middle;">
                                    <span class="badge-panitia">PANITIA</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Foto & Identitas --}}
                    <div class="photo-section">
                        <div class="photo-frame">
                            @if(!empty($fotoDataUri))
                                <img src="{{ $fotoDataUri }}" alt="{{ $panitia->nama }}">
                            @else
                                <div class="photo-placeholder">Foto 4:5</div>
                            @endif
                        </div>
                        <div class="jabatan-pill">{{ strtoupper($panitia->jabatan) }}</div>

                        <div class="member-name">{{ strtoupper($panitia->nama) }}</div>
                        @if($panitia->divisi)
                            <div class="member-divisi">{{ strtoupper($panitia->divisi) }}</div>
                        @endif
                        <div class="member-instansi">{{ $panitia->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}</div>
                    </div>

                    {{-- Footer + Mascot + QR --}}
                    <div class="card-footer">
                        <table class="footer-table">
                            <tr>
                                <td style="width: 13mm; vertical-align: middle; text-align: center;">
                                    @if(!empty($mascotDataUri))
                                        <img src="{{ $mascotDataUri }}" class="footer-mascot" alt="Maskot">
                                    @endif
                                </td>
                                <td style="vertical-align: middle; text-align: left; padding-left: 1.5mm;">
                                    <div class="footer-label">KARTU TANDA PANITIA</div>
                                    <div class="footer-role">{{ strtoupper($panitia->jabatan) }}</div>
                                    <div class="footer-gudep">Gudep 03.059 - 03.060</div>
                                </td>
                                <td style="width: 19mm; vertical-align: middle; text-align: center;">
                                    <div class="qr-box">
                                        <img src="{{ $qrCode }}" alt="QR">
                                    </div>
                                    <div class="qr-caption">VERIFIKASI ID</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
