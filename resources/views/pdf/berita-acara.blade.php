<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Rekapitulasi Nilai & Pengesahan Juara Umum</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 20mm 15mm 20mm 20mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1B4332;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h3 {
            margin: 0;
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1B4332;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 14pt;
            color: #1B4332;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 8.5pt;
            font-style: italic;
            color: #4b5563;
        }
        .title {
            text-align: center;
            margin: 10px 0 8px 0;
        }
        .title h4 {
            margin: 0;
            font-size: 11.5pt;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
            color: #111827;
        }
        .title p {
            margin: 2px 0 0 0;
            font-size: 9pt;
            font-family: Arial, sans-serif;
            color: #374151;
            font-weight: 600;
        }
        .content-text {
            text-align: justify;
            margin-bottom: 10px;
            font-size: 10pt;
            line-height: 1.35;
        }
        table.matrix {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8.5pt;
            font-family: Arial, sans-serif;
            page-break-inside: auto;
        }
        table.matrix thead {
            display: table-header-group;
        }
        table.matrix tr {
            page-break-inside: avoid;
        }
        table.matrix th, table.matrix td {
            border: 1px solid #4b5563;
            padding: 4px 5px;
            text-align: center;
        }
        table.matrix th {
            background-color: #1B4332;
            color: #ffffff;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        table.matrix tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .highlight-winner {
            background-color: #fef3c7 !important;
            font-weight: bold;
            color: #92400e;
        }
        .section-label {
            font-weight: bold;
            font-size: 9.5pt;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 4px;
            color: #1B4332;
            border-left: 3px solid #D97706;
            padding-left: 6px;
            page-break-after: avoid;
        }
        .signatures {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            font-size: 9.5pt;
            page-break-inside: avoid;
        }
        .signatures td {
            text-align: center;
            vertical-align: top;
            width: 50%;
            padding: 6px 12px;
        }
        .signature-space {
            height: 50px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-title {
            font-size: 8.5pt;
            color: #4b5563;
        }
        .badge-winner {
            display: inline-block;
            background-color: #D97706;
            color: #ffffff;
            padding: 1.5px 5px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Arial, sans-serif");
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $pdf->page_text(595.28 - 42 - $width, 841.89 - 30, $text, $font, $size, array(0.45, 0.45, 0.45));
            $docText = "Berita Acara Rekapitulasi Nilai Spencerone Camp Season II";
            $pdf->page_text(55, 841.89 - 30, $docText, $font, $size, array(0.45, 0.45, 0.45));
        }
    </script>

    {{-- KOP SURAT RESMI GUDEP --}}
    <div class="header">
        <h3>GERAKAN PRAMUKA GUGUS DEPAN 03.059 - 03.060</h3>
        <h2>PANGKALAN SMP NEGERI 1 CERBON</h2>
        <p>Alamat: Jl. Pelajar No. 12 Cerbon, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan (70562)</p>
    </div>

    {{-- JUDUL BERITA ACARA --}}
    <div class="title">
        <h4>BERITA ACARA REKAPITULASI NILAI & PENGESAHAN JUARA UMUM</h4>
        <p>NOMOR: 042/SC-II/PAN-JURI/{{ date('m/Y') }}</p>
    </div>

    <div class="content-text">
        Pada hari ini, <strong>{{ $tanggal }}</strong>, bertempat di Bumi Perkemahan SMP Negeri 1 Cerbon, telah dilaksanakan Sidang Pleno Dewan Juri Spencerone Camp Season II untuk merekapitulasi, memverifikasi seluruh hasil penilaian cabang lomba, dan mengesahkan para pemenang kejuaraan secara sah dan mengikat.
    </div>

    {{-- TABEL REKAP REGU PUTRA --}}
    <div class="section-label">A. REKAPITULASI HASIL LOMBA - KATEGORI REGU PUTRA (GUDEP 03.059)</div>
    <table class="matrix">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="text-align: left; width: 140px;">Nama Regu</th>
                @foreach($mataLombaList as $ml)
                    <th>{{ Str::limit($ml->nama, 9) }}</th>
                @endforeach
                <th style="width: 55px;">Total</th>
                <th style="width: 70px;">Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapPutra as $idx => $r)
                <tr class="{{ $idx === 0 ? 'highlight-winner' : '' }}">
                    <td>{{ $idx + 1 }}</td>
                    <td style="text-align: left; font-weight: {{ $idx < 3 ? 'bold' : 'normal' }};">{{ $r['nama_regu'] }}</td>
                    @foreach($mataLombaList as $ml)
                        <td>{{ number_format($r['scores'][$ml->id] ?? 0, 1) }}</td>
                    @endforeach
                    <td style="font-weight: bold;">{{ number_format($r['total_skor'], 1) }}</td>
                    <td>
                        @if($idx === 0)
                            <span class="badge-winner">JUARA UMUM 1</span>
                        @elseif($idx === 1)
                            Runner-up 1
                        @elseif($idx === 2)
                            Runner-up 2
                        @else
                            Peringkat {{ $idx + 1 }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($mataLombaList) + 3 }}">Belum ada data nilai peserta putra</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- TABEL REKAP REGU PUTRI --}}
    <div class="section-label" style="margin-top: 15px;">B. REKAPITULASI HASIL LOMBA - KATEGORI REGU PUTRI (GUDEP 03.060)</div>
    <table class="matrix">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="text-align: left; width: 140px;">Nama Regu</th>
                @foreach($mataLombaList as $ml)
                    <th>{{ Str::limit($ml->nama, 9) }}</th>
                @endforeach
                <th style="width: 55px;">Total</th>
                <th style="width: 70px;">Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapPutri as $idx => $r)
                <tr class="{{ $idx === 0 ? 'highlight-winner' : '' }}">
                    <td>{{ $idx + 1 }}</td>
                    <td style="text-align: left; font-weight: {{ $idx < 3 ? 'bold' : 'normal' }};">{{ $r['nama_regu'] }}</td>
                    @foreach($mataLombaList as $ml)
                        <td>{{ number_format($r['scores'][$ml->id] ?? 0, 1) }}</td>
                    @endforeach
                    <td style="font-weight: bold;">{{ number_format($r['total_skor'], 1) }}</td>
                    <td>
                        @if($idx === 0)
                            <span class="badge-winner">JUARA UMUM 1</span>
                        @elseif($idx === 1)
                            Runner-up 1
                        @elseif($idx === 2)
                            Runner-up 2
                        @else
                            Peringkat {{ $idx + 1 }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($mataLombaList) + 3 }}">Belum ada data nilai peserta putri</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="content-text" style="margin-top: 15px;">
        Demikian Berita Acara ini dibuat dengan sebenar-benarnya tanpa intervensi pihak manapun dan keputusan Dewan Juri bersifat final serta tidak dapat diganggu gugat.
    </div>

    {{-- 4 KOLOM PENGESAHAN / TANDA TANGAN --}}
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Ketua Pelaksana Season II</strong>
                <div class="signature-space"></div>
                <div class="signature-name">M. RAFLI AL-FARIZI</div>
                <div class="signature-title">NTA: 22.04.03.059.001</div>
            </td>
            <td>
                Ditetapkan di: Cerbon<br>
                <strong>Koordinator Dewan Juri</strong>
                <div class="signature-space"></div>
                <div class="signature-name">FACHRURRAZI, S.Pd.</div>
                <div class="signature-title">Ketua Tim Penilai</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px;">
                Menyetujui,<br>
                <strong>Pembina Pramuka Penggalang</strong>
                <div class="signature-space"></div>
                <div class="signature-name">SYAHRIL, S.Pd., M.M.</div>
                <div class="signature-title">NIP. 19820512 200801 1 012</div>
            </td>
            <td style="padding-top: 20px;">
                Mengesahkan,<br>
                <strong>Ka Mabigus SMPN 1 Cerbon</strong>
                <div class="signature-space"></div>
                <div class="signature-name">H. AKHMAD BAHRAN, M.Pd.</div>
                <div class="signature-title">NIP. 19700315 199802 1 004</div>
            </td>
        </tr>
    </table>

</body>
</html>
