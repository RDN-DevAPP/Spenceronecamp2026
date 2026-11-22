<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Piagam Penghargaan - Spencerone Camp Season II</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Serif, Georgia, serif;
            color: #111827;
            margin: 0;
            padding: 0;
        }
        .piagam-page {
            border: 6px double #1B4332;
            padding: 12mm 16mm;
            height: 180mm;
            position: relative;
            page-break-after: always;
            background: #FFFDF8;
        }
        .piagam-page:last-child {
            page-break-after: avoid;
        }
        .inner-border {
            border: 2px solid #D97706;
            padding: 10mm 12mm;
            height: 100%;
            text-align: center;
        }
        .org {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            font-weight: bold;
            color: #1B4332;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .title {
            font-size: 28pt;
            font-weight: bold;
            color: #1B4332;
            letter-spacing: 3px;
            margin: 8px 0 4px 0;
            text-transform: uppercase;
        }
        .nomor {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9.5pt;
            color: #4B5563;
            margin-bottom: 16px;
        }
        .lead {
            font-size: 11pt;
            color: #374151;
            margin: 6px 0;
        }
        .regu-name {
            font-size: 24pt;
            font-weight: bold;
            color: #D97706;
            text-transform: uppercase;
            margin: 10px 0 6px 0;
            border-bottom: 2px solid #1B4332;
            display: inline-block;
            padding: 0 18px 4px 18px;
        }
        .rank-badge {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            color: #FFFFFF;
            background: #1B4332;
            display: inline-block;
            padding: 5px 20px;
            border-radius: 20px;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .meta {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            color: #1F2937;
            margin-top: 6px;
        }
        .sign-table {
            width: 100%;
            margin-top: 22px;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9.5pt;
        }
        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 45px;
        }
    </style>
</head>
<body>
    @forelse($certificates as $cert)
        <div class="piagam-page">
            <div class="inner-border">
                <div class="org">Gerakan Pramuka Gudep 03.059 - 03.060 SMPN 1 Cerbon</div>
                <div class="title">Piagam Penghargaan</div>
                <div class="nomor">Nomor: {{ $cert['nomor_piagam'] }}</div>

                <p class="lead">Diberikan dengan hormat kepada:</p>
                <div class="regu-name">{{ $cert['regu']->nama_regu }}</div>
                <div class="meta">{{ $cert['kategori'] }} • Regu {{ $cert['regu']->nomor_regu }}</div>

                <p class="lead" style="margin-top: 12px;">Atas prestasi gemilang meraih predikat:</p>
                <div class="rank-badge">{{ $cert['peringkat'] }}</div>
                <div class="meta">
                    Total Akumulasi Skor: <strong>{{ number_format((float) $cert['total_skor'], 2) }}</strong><br>
                    Kegiatan Lomba Tingkat I (Spencerone Camp Season II)
                </div>

                <table class="sign-table">
                    <tr>
                        <td>
                            Ketua Panitia Pelaksana<br>
                            <div class="sign-space"></div>
                            <strong>( ........................................ )</strong>
                        </td>
                        <td>
                            Barito Kuala, {{ now()->translatedFormat('d F Y') }}<br>
                            Kepala SMPN 1 Cerbon / Ka. Mabigus
                            <div class="sign-space"></div>
                            <strong>( ........................................ )</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @empty
        <div class="piagam-page">
            <div class="inner-border">
                <div class="title">Piagam Penghargaan</div>
                <p class="lead">Belum ada data regu pemenang yang tersedia untuk dicetak.</p>
            </div>
        </div>
    @endforelse
</body>
</html>
