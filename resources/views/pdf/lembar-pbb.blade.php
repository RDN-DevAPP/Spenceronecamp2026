<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Penilaian PBB (OMR Scanner) - {{ $lomba->nama }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7.2pt;
            color: #000;
            margin: 0;
            padding: 0;
            background: #cbd5e1;
        }

        /* Toolbar Layar */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 9999;
            background: #064E3B;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-family: system-ui, -apple-system, sans-serif;
        }
        .toolbar a, .toolbar button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }
        .btn-print { background: #F59E0B; color: #064E3B; }
        .btn-print:hover { background: #D97706; color: #fff; }
        .btn-scan { background: #10B981; color: #fff; }
        .btn-scan:hover { background: #059669; }
        .btn-back { background: rgba(255,255,255,0.15); color: #fff; }
        .btn-back:hover { background: rgba(255,255,255,0.25); }
        .select-regu {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.3);
            background: #fff;
            color: #064E3B;
            outline: none;
        }

        /* Lembar Formulir Presisi A4 Landscape (297mm x 210mm) */
        .sheet-page {
            position: relative;
            width: 297mm;
            height: 210mm;
            margin: 15px auto;
            background: #fff;
            padding: 6mm 7mm;
            page-break-after: always;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            overflow: hidden;
            box-sizing: border-box;
        }
        .sheet-page:last-child {
            page-break-after: avoid;
        }

        /* 4 Kotak Sudut Hitam (Marker Presisi 7mm x 7mm - Tidak Menutupi Teks) */
        .omr-corner {
            position: absolute;
            width: 7mm;
            height: 7mm;
            background-color: #000 !important;
            z-index: 100;
        }
        .corner-tl { top: 5mm; left: 5mm; }
        .corner-tr { top: 5mm; right: 5mm; }
        .corner-bl { bottom: 5mm; left: 5mm; }
        .corner-br { bottom: 5mm; right: 5mm; }

        /* Pembungkus Konten dengan Margin Aman Bebas dari Kotak Sudut */
        .sheet-content {
            margin-left: 9mm;
            margin-right: 9mm;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Header Lembar */
        .sheet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
            margin-bottom: 2px;
        }
        .header-title h1 {
            font-size: 11pt;
            font-weight: 900;
            text-transform: uppercase;
            margin: 0;
            color: #000;
            letter-spacing: -0.2px;
        }
        .header-title p {
            font-size: 6.8pt;
            margin: 1px 0 0;
            color: #222;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header-regu {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1.5px solid #000;
            padding: 2.5px 8px;
            border-radius: 4px;
        }
        .regu-info-box {
            line-height: 1.25;
        }
        .regu-name {
            font-size: 10pt;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
        }
        .regu-meta {
            font-size: 6.8pt;
            color: #333;
            font-weight: bold;
        }
        .regu-no-box {
            text-align: center;
            border-left: 1.5px solid #000;
            padding-left: 8px;
        }
        .regu-no-title {
            font-size: 5.8pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #444;
            display: block;
        }
        .regu-no-val {
            font-size: 13pt;
            font-weight: 900;
            color: #000;
            line-height: 1;
        }
        .header-qr {
            display: flex;
            align-items: center;
            gap: 6px;
            text-align: right;
        }
        .header-qr img {
            width: 15mm;
            height: 15mm;
            display: block;
        }
        .qr-label {
            font-size: 5.8pt;
            font-weight: bold;
            font-family: monospace;
            color: #000;
            margin-top: 1px;
            display: block;
        }

        /* Petunjuk Ringkas */
        .sheet-notice {
            background: #f1f5f9;
            border: 1px dashed #64748b;
            padding: 1.5px 6px;
            font-size: 6.2pt;
            font-weight: bold;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        /* 2 Kolom Matriks Penilaian */
        .sheet-body {
            display: flex;
            justify-content: space-between;
            gap: 3mm;
            flex-grow: 1;
        }
        .col-half {
            width: 49.6%;
        }

        /* Tabel Penilaian */
        table.omr-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.6pt;
        }
        table.omr-table th, table.omr-table td {
            border: 1px solid #000;
            padding: 1px 1.5px;
            text-align: center;
            vertical-align: middle;
        }
        table.omr-table th {
            background: #e2e8f0;
            font-weight: 800;
            font-size: 6pt;
            text-transform: uppercase;
            padding: 1.5px 1px;
        }
        table.omr-table td.materi-cell {
            text-align: left;
            font-weight: 700;
            font-size: 6.6pt;
            padding-left: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 36mm;
        }
        table.omr-table tr.group-head td {
            background: #0f172a;
            color: #fff;
            font-weight: 900;
            font-size: 6.2pt;
            text-align: left;
            padding: 1.5px 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Kotak Nilai OMR (Target Coretan / Tanda Juri) */
        .omr-box {
            display: inline-block;
            width: 6.6mm;
            height: 4.4mm;
            line-height: 4.4mm;
            border: 1.3px solid #000;
            text-align: center;
            font-size: 6.5pt;
            font-weight: 900;
            background: #fff;
            border-radius: 1px;
            color: #000;
        }

        /* Footer Lembar */
        .sheet-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2px;
            padding-top: 2px;
            border-top: 1.5px solid #000;
            font-size: 6.8pt;
        }
        .footer-total-box {
            border: 1.5px solid #000;
            padding: 2.5px 8px;
            background: #f8fafc;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .footer-total-box strong {
            font-size: 9pt;
            font-weight: 900;
        }
        .footer-ttd {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .ttd-line {
            display: inline-block;
            width: 42mm;
            border-bottom: 1.5px solid #000;
            margin-left: 4px;
        }

        /* Pengaturan Cetak Browser */
        @media print {
            .toolbar { display: none !important; }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                width: 297mm !important;
                height: 210mm !important;
            }
            .sheet-page {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 5mm 6mm !important;
                width: 297mm !important;
                height: 210mm !important;
                page-break-after: always !important;
                overflow: hidden !important;
            }
            .sheet-page:last-child {
                page-break-after: avoid !important;
            }
        }
    </style>
</head>
<body>

    {{-- Toolbar Layar (Tidak Tercetak) --}}
    <div class="toolbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('admin.pbb-rubrik.index') }}" class="btn-back">
                &larr; Kembali ke Rubrik
            </a>
            <span style="font-weight:800; font-size:13px; text-transform:uppercase;">
                Lembar Formulir PBB (OMR A4 Landscape)
            </span>
            <span style="background:rgba(255,255,255,0.2); padding:3px 8px; border-radius:6px; font-size:11px;">
                Total Regu: {{ $regus->count() }}
            </span>
        </div>

        <div style="display:flex; align-items:center; gap:8px;">
            <label style="font-size:11px; font-weight:600;">Pilih Regu:</label>
            <select class="select-regu" onchange="location.href='{{ route('admin.pbb-rubrik.print') }}?regu_id=' + this.value">
                <option value="">-- Cetak Per Regu --</option>
                @foreach($regus as $r)
                    <option value="{{ $r->id }}" {{ $selectedReguId == $r->id ? 'selected' : '' }}>
                        No. {{ $r->nomor_regu }} - {{ $r->nama_regu }} ({{ ucfirst($r->jenis) }})
                    </option>
                @endforeach
            </select>

            <a href="{{ route('admin.pbb-rubrik.print') }}?all=1" class="btn-print" style="background:#fff; color:#064E3B;">
                Cetak Semua Regu ({{ $regus->count() }} Lembar)
            </a>

            <button onclick="window.print()" class="btn-print">
                &#128424; Cetak Halaman Ini
            </button>

            <a href="{{ route('admin.pbb-rubrik.scan') }}" class="btn-scan">
                &#128247; Buka Scanner Lembar PBB
            </a>
        </div>
    </div>

    {{-- Looping Lembar (1 Lembar per Regu / Peserta) --}}
    @foreach($sheets as $sheet)
        @php
            $regu = $sheet['regu'];
            $qrDataUri = $sheet['qr'];
            $sheetCode = $sheet['code'];
            $fmt = fn ($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');
        @endphp

        <div class="sheet-page">
            {{-- 4 Kotak Sudut Hitam (Marker Presisi OMR 7mm x 7mm di Sudut Luar) --}}
            <div class="omr-corner corner-tl"></div>
            <div class="omr-corner corner-tr"></div>
            <div class="omr-corner corner-bl"></div>
            <div class="omr-corner corner-br"></div>

            {{-- Pembungkus Konten Bersih dengan Jarak Bebas dari Sudut --}}
            <div class="sheet-content">

                {{-- Header Lembar --}}
                <div class="sheet-header">
                    <div class="header-title">
                        <h1>LEMBAR PENILAIAN PBB &amp; KOMANDAN PASUKAN</h1>
                        <p>FORUM BARIS INDONESIA (FORBASI) KABUPATEN BARITO KUALA &bull; TINGKAT U-16</p>
                    </div>

                    <div class="header-regu">
                        <div class="regu-info-box">
                            <span class="regu-name">{{ $regu ? $regu->nama_regu : '____________________________________' }}</span>
                            <div class="regu-meta">
                                {{ $regu ? ($regu->user->pangkalan ?? ($regu->user->sekolah ?? 'SMPN 1 Cerbon')) : 'Pangkalan: ____________________' }}
                                &bull; {{ $regu ? ucfirst($regu->jenis) : 'Putra/Putri' }}
                            </div>
                        </div>
                        <div class="regu-no-box">
                            <span class="regu-no-title">Nomor Urut</span>
                            <span class="regu-no-val">{{ $regu ? $regu->nomor_regu : '____' }}</span>
                        </div>
                    </div>

                    <div class="header-qr">
                        <div style="display:flex; flex-direction:column; align-items:flex-end;">
                            <img src="{{ $qrDataUri }}" alt="QR Code Regu">
                            <span class="qr-label">{{ $sheetCode }}</span>
                        </div>
                    </div>
                </div>

                {{-- Petunjuk Pengisian OMR --}}
                <div class="sheet-notice">
                    <span><strong>PETUNJUK JURI:</strong> Berikan coretan, silang (X), atau tanda centang di dalam KOTAK NILAI yang dipilih. Coretan harus berada di dalam batas kotak.</span>
                    <span><strong>PENTING:</strong> 4 Kotak hitam di sudut lembar tidak boleh terlipat/rusak untuk verifikasi scanner otomatis.</span>
                </div>

                {{-- 2 Kolom Matriks Penilaian (Total 56 Kriteria) --}}
                <div class="sheet-body">
                    
                    {{-- Kolom Kiri: 28 Baris (Kriteria 1 - 28) --}}
                    <div class="col-half">
                        <table class="omr-table">
                            <thead>
                                <tr>
                                    <th style="width:5mm">No</th>
                                    <th style="width:36mm">Materi Penilaian</th>
                                    <th style="width:7mm">0</th>
                                    <th style="width:7mm">Min</th>
                                    <th style="width:7mm">K1</th>
                                    <th style="width:7mm">K2</th>
                                    <th style="width:7mm">C1</th>
                                    <th style="width:7mm">C2</th>
                                    <th style="width:7mm">B1</th>
                                    <th style="width:7mm">B2</th>
                                    <th style="width:7mm">SB</th>
                                    <th style="width:9mm">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $prevGroup1 = null; @endphp
                                @foreach($col1 as $c)
                                    @php
                                        $rubrik = array_values($c->rubrik ?? []);
                                        $groupName = $c->kelompok ?: 'Penilaian';
                                    @endphp

                                    @if($groupName !== $prevGroup1)
                                        @php $prevGroup1 = $groupName; @endphp
                                        <tr class="group-head">
                                            <td colspan="12">{{ $groupName }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td><strong>{{ $c->urutan }}</strong></td>
                                        <td class="materi-cell" title="{{ $c->nama_kriteria }}">{{ $c->nama_kriteria }}</td>
                                        @for($li = 0; $li < 9; $li++)
                                            @php $val = $rubrik[$li] ?? 0; @endphp
                                            <td>
                                                <div class="omr-box" data-criteria="{{ $c->id }}" data-level="{{ $li }}">
                                                    {{ $fmt($val) }}
                                                </div>
                                            </td>
                                        @endfor
                                        <td style="background:#fafafa;"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Kolom Kanan: 28 Baris (Kriteria 29 - 56) --}}
                    <div class="col-half">
                        <table class="omr-table">
                            <thead>
                                <tr>
                                    <th style="width:5mm">No</th>
                                    <th style="width:36mm">Materi Penilaian</th>
                                    <th style="width:7mm">0</th>
                                    <th style="width:7mm">Min</th>
                                    <th style="width:7mm">K1</th>
                                    <th style="width:7mm">K2</th>
                                    <th style="width:7mm">C1</th>
                                    <th style="width:7mm">C2</th>
                                    <th style="width:7mm">B1</th>
                                    <th style="width:7mm">B2</th>
                                    <th style="width:7mm">SB</th>
                                    <th style="width:9mm">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $prevGroup2 = null; @endphp
                                @foreach($col2 as $c)
                                    @php
                                        $rubrik = array_values($c->rubrik ?? []);
                                        $groupName = $c->kelompok ?: 'Penilaian';
                                    @endphp

                                    @if($groupName !== $prevGroup2)
                                        @php $prevGroup2 = $groupName; @endphp
                                        <tr class="group-head">
                                            <td colspan="12">{{ $groupName }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td><strong>{{ $c->urutan }}</strong></td>
                                        <td class="materi-cell" title="{{ $c->nama_kriteria }}">{{ $c->nama_kriteria }}</td>
                                        @for($li = 0; $li < 9; $li++)
                                            @php $val = $rubrik[$li] ?? 0; @endphp
                                            <td>
                                                <div class="omr-box" data-criteria="{{ $c->id }}" data-level="{{ $li }}">
                                                    {{ $fmt($val) }}
                                                </div>
                                            </td>
                                        @endfor
                                        <td style="background:#fafafa;"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- Footer Lembar --}}
                <div class="sheet-footer">
                    <div class="footer-total-box">
                        <span>TOTAL NILAI FORMULIR:</span>
                        <strong>_________________ Poin</strong>
                        <span style="font-size:6.2pt; color:#555;">(Maksimal: {{ $fmt($col1->sum('nilai_max') + $col2->sum('nilai_max')) }})</span>
                    </div>

                    <div class="footer-ttd">
                        <span>Tanggal: ____ / ____ / 2026</span>
                        <span>Tanda Tangan Juri: <span class="ttd-line"></span></span>
                    </div>
                </div>

            </div>

        </div>
    @endforeach

</body>
</html>
