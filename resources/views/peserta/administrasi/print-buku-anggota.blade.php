<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Anggota - Regu {{ $regu->nama_regu }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1B4332;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
            color: #1B4332;
        }
        .header h3 {
            margin: 2px 0;
            font-size: 12pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #444;
        }
        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px 6px;
        }
        th {
            background-color: #E8F5E9;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .footer-sig {
            margin-top: 30px;
            width: 100%;
        }
        .footer-sig td {
            border: none;
            padding: 0;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fef3c7; border: 1px solid #f59e0b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <span><strong>Pratinjau Cetak:</strong> Gunakan tombol di sebelah kanan atau tekan Ctrl+P untuk mencetak Buku Anggota.</span>
        <button onclick="window.print()" style="background: #1B4332; color: white; border: none; padding: 6px 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h2>GERAKAN PRAMUKA GUGUS DEPAN 03.059 – 03.060</h2>
        <h3>PANGKALAN SMP NEGERI 1 CERBON</h3>
        <p>Jl. HM. Ruslan RT.008 Desa Bantuil, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan</p>
    </div>

    <div class="doc-title">BUKU I : BUKU INDUK ANGGOTA REGU {{ strtoupper($regu->nama_regu) }} ({{ strtoupper($regu->jenis) }})</div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">NTA / NISN</th>
                <th width="20%">Nama Lengkap</th>
                <th width="10%">Jabatan</th>
                <th width="10%">Tingkat TKU</th>
                <th width="6%">Kelas</th>
                <th width="15%">Tempat, Tgl Lahir</th>
                <th width="8%">Agama</th>
                <th width="15%">Alamat & TKK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggotaList as $idx => $anggota)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center"><strong>{{ $anggota->nta ?: ($anggota->nisn ?: '-') }}</strong></td>
                    <td><strong>{{ $anggota->nama }}</strong></td>
                    <td class="text-center">{{ strtoupper($anggota->jabatan) }}</td>
                    <td class="text-center">Penggalang {{ ucfirst($anggota->tingkatan_tku) }}</td>
                    <td class="text-center">Kelas {{ $anggota->kelas }}</td>
                    <td>
                        {{ $anggota->tempat_lahir ?? '-' }}, {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-center">{{ $anggota->agama ?: '-' }}</td>
                    <td>
                        {{ $anggota->alamat ?: '-' }}
                        @if($anggota->tkk)
                            <br><small><strong>TKK:</strong> {{ $anggota->tkk }}</small>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-sig">
        <tr>
            <td width="60%"></td>
            <td width="40%" style="text-align: center;">
                Cerbon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pemimpin Regu {{ $regu->nama_regu }}<br><br><br><br>
                <strong><u>{{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nama') ?? '( .............................. )' }}</u></strong><br>
                NTA: {{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nta') ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
