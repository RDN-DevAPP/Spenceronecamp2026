<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar QR Massal - QR-Hunt Trivia</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9pt;
            color: #111827;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #1B4332;
            padding-bottom: 6px;
            margin-bottom: 10px;
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 7.5pt;
            color: #4B5563;
        }
        .title {
            font-size: 13pt;
            font-weight: bold;
            color: #1B4332;
            text-transform: uppercase;
            margin: 0;
        }
        .subtitle {
            font-size: 8pt;
            color: #D97706;
            font-weight: bold;
            margin: 2px 0 0 0;
        }
        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6mm;
        }
        .card-cell {
            width: 50%;
            vertical-align: top;
            border: 1.5px dashed #1B4332;
            border-radius: 8px;
            padding: 8px 10px;
            background: #FFFFFF;
            page-break-inside: avoid;
        }
        .card-top {
            display: table;
            width: 100%;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }
        .card-code {
            display: table-cell;
            font-size: 11pt;
            font-weight: bold;
            color: #1B4332;
        }
        .card-points {
            display: table-cell;
            text-align: right;
        }
        .points-badge {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #F59E0B;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8pt;
            font-weight: bold;
        }
        .qr-wrap {
            text-align: center;
            padding: 4px 0;
        }
        .qr-wrap img {
            width: 38mm;
            height: 38mm;
        }
        .token-text {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 6.5pt;
            color: #6B7280;
            text-align: center;
            margin-top: 3px;
            word-break: break-all;
        }
        .footer-note {
            text-align: center;
            font-size: 7.5pt;
            color: #374151;
            margin-top: 5px;
            padding-top: 4px;
            border-top: 1px solid #F3F4F6;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <p class="title">QR-Hunt Trivia • Lembar QR Pos Soal</p>
            <p class="subtitle">Scout Cyber Quiz Arena — Spencerone Camp Season II (SMPN 1 Cerbon)</p>
        </div>
        <div class="header-right">
            Dicetak: {{ $generatedAt }}<br>
            Total Soal: {{ count($qrQuestions) }} Pos QR
        </div>
    </div>

    @if(count($qrQuestions) === 0)
        <p style="text-align:center; padding: 30px; color: #6B7280;">Belum ada soal QR-Hunt yang terdaftar.</p>
    @else
        <table class="grid-table">
            @foreach($qrQuestions->chunk(2) as $row)
                <tr>
                    @foreach($row as $q)
                        <td class="card-cell">
                            <div class="card-top">
                                <div class="card-code">{{ $q['code_label'] }}</div>
                                <div class="card-points">
                                    <span class="points-badge">{{ $q['points'] }} POIN</span>
                                </div>
                            </div>
                            <div class="qr-wrap">
                                <img src="{{ $q['qr_data_uri'] }}" alt="{{ $q['code_label'] }}">
                            </div>
                            <div class="token-text">{{ $q['qr_token'] }}</div>
                            <div class="footer-note">
                                <strong>Scan untuk membuka soal!</strong><br>
                                1 Soal = 1 Regu Tercepat • Gudep 03.059-03.060
                            </div>
                        </td>
                    @endforeach
                    @if($row->count() === 1)
                        <td style="width:50%;"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endif
</body>
</html>
