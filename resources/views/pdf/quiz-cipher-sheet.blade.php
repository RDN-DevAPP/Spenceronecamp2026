<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Soal A4 - Cipher Word-Search</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 7mm 8mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 8.5pt;
            color: #0F172A;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .sheet-header {
            border-bottom: 2.5px solid #1B4332;
            padding-bottom: 5px;
            margin-bottom: 6px;
            display: table;
            width: 100%;
        }
        .sheet-header-left {
            display: table-cell;
            vertical-align: middle;
        }
        .sheet-header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 7.5pt;
            color: #475569;
        }
        .category-title {
            font-size: 13pt;
            font-weight: bold;
            color: #1B4332;
            margin: 0;
            text-transform: uppercase;
        }
        .category-meta {
            font-size: 8pt;
            color: #D97706;
            font-weight: bold;
            margin: 2px 0 0 0;
        }
        .regu-box {
            border: 1px solid #CBD5E1;
            border-radius: 5px;
            padding: 5px 8px;
            margin-bottom: 7px;
            background: #F8FAFC;
            font-size: 8pt;
        }
        .matrix-wrap {
            text-align: center;
            margin: 4px 0 8px 0;
        }
        .matrix-table {
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 auto;
        }
        .matrix-table td {
            border: 1.2px solid #475569;
            text-align: center;
            vertical-align: middle;
            padding: 0;
            background: #FFFFFF;
        }
        .matrix-table td.key-cell {
            background: #FEF3C7;
            border-color: #D97706;
        }
        .targets-box {
            border: 1.5px solid #1B4332;
            border-radius: 6px;
            padding: 6px 9px;
            background: #F8FAFC;
            page-break-inside: avoid;
        }
        .targets-title {
            font-size: 9pt;
            font-weight: bold;
            color: #1B4332;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .targets-table {
            width: 100%;
            border-collapse: collapse;
        }
        .targets-table th, .targets-table td {
            border: 1px solid #CBD5E1;
            padding: 3.5px 6px;
            font-size: 8pt;
            text-align: left;
        }
        .targets-table th {
            background: #E2E8F0;
            color: #0F172A;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @forelse($grids as $gIdx => $grid)
        @php
            $size = max(1, (int) $grid['grid_size']);
            // Maximize usable A4 width (192mm) & height (~188mm max for grid so target box fits cleanly on 1 A4 page)
            $maxWidthMm = 190;
            $wordRowsCount = count($grid['words']);
            $targetReserveMm = min(78, 22 + ($wordRowsCount * 6));
            $maxHeightMm = 248 - $targetReserveMm;
            $cellMm = min(14.5, floor(min($maxWidthMm, $maxHeightMm) / $size * 10) / 10);
            $imgMm = max(6.5, round($cellMm * 0.86, 1));
            $tableWidthMm = round($cellMm * $size, 1);
        @endphp

        <div class="{{ $loop->last && !$withKey ? '' : 'page-break' }}">
            <div class="sheet-header">
                <div class="sheet-header-left">
                    <p class="category-title">{{ $grid['title'] }}</p>
                    <p class="category-meta">
                        Babak 2: Cipher Word-Search • {{ $grid['cipher_label'] }} • Matriks {{ $size }}×{{ $size }}
                    </p>
                </div>
                <div class="sheet-header-right">
                    Spencerone Camp Season II<br>
                    Lembar Coretan Peserta • {{ $generatedAt }}
                </div>
            </div>

            <div class="regu-box">
                <strong>Nama Regu:</strong> ........................................................
                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <strong>No. Regu:</strong> ..............
                &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                <strong>Petunjuk:</strong> Terjemahkan simbol sandi (horizontal / vertikal / diagonal), lalu klaim kata tercepat!
            </div>

            <div class="matrix-wrap">
                <table class="matrix-table" style="width: {{ $tableWidthMm }}mm;">
                    @foreach($grid['matrix'] as $rIdx => $row)
                        <tr>
                            @foreach($row as $cIdx => $letter)
                                @php
                                    $upper = strtoupper((string) $letter);
                                    $uri = $grid['symbol_uris'][$upper] ?? '';
                                @endphp
                                <td style="width: {{ $cellMm }}mm; height: {{ $cellMm }}mm;">
                                    @if($uri)
                                        <img src="{{ $uri }}" width="{{ round($imgMm * 3.78) }}" height="{{ round($imgMm * 3.78) }}" style="width: {{ $imgMm }}mm; height: {{ $imgMm }}mm; display: block; margin: 0 auto;" alt="">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="targets-box">
                <p class="targets-title">Daftar Target Kata ({{ count($grid['words']) }} Kata)</p>
                <table class="targets-table">
                    <thead>
                        <tr>
                            <th style="width: 38px;">No</th>
                            <th style="width: 110px;">Panjang Kata</th>
                            <th style="width: 65px; text-align:center;">Poin</th>
                            <th>Catatan Terjemahan Regu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grid['words'] as $wIdx => $w)
                            <tr>
                                <td><strong>#{{ $wIdx + 1 }}</strong></td>
                                <td>{{ strlen($w->word) }} Huruf</td>
                                <td style="text-align:center;"><strong>{{ $w->points }}</strong></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($withKey)
            <div class="{{ $loop->last ? '' : 'page-break' }}">
                <div class="sheet-header">
                    <div class="sheet-header-left">
                        <p class="category-title">KUNCI JAWABAN: {{ $grid['title'] }}</p>
                        <p class="category-meta">{{ $grid['cipher_label'] }} • Matriks {{ $size }}×{{ $size }}</p>
                    </div>
                    <div class="sheet-header-right">KHUSUS PANITIA / JURI</div>
                </div>

                <div class="matrix-wrap">
                    <table class="matrix-table" style="width: {{ $tableWidthMm }}mm;">
                        @foreach($grid['matrix'] as $rIdx => $row)
                            <tr>
                                @foreach($row as $cIdx => $letter)
                                    @php
                                        $isKey = isset($grid['highlight_set'][$rIdx . '-' . $cIdx]);
                                    @endphp
                                    <td class="{{ $isKey ? 'key-cell' : '' }}" style="width: {{ $cellMm }}mm; height: {{ $cellMm }}mm; font-weight: {{ $isKey ? 'bold' : 'normal' }}; font-size: {{ $size >= 18 ? '9pt' : '11pt' }};">
                                        {{ $letter }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="targets-box">
                    <p class="targets-title">Daftar Kata Kunci</p>
                    <p style="margin:0; font-size:9pt;">
                        @foreach($grid['words'] as $w)
                            <strong>{{ $w->word }}</strong> ({{ $w->points }} pts){{ !$loop->last ? ' • ' : '' }}
                        @endforeach
                    </p>
                </div>
            </div>
        @endif
    @empty
        <p style="text-align:center; padding: 30px;">Belum ada kategori Cipher Word-Search yang aktif.</p>
    @endforelse
</body>
</html>
