<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Keuangan - Regu {{ $regu->nama_regu }}</title>
    <style>
        @page { size: a4 portrait; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #1B4332; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1B4332; }
        .header h3 { margin: 2px 0; font-size: 11pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0 0; font-size: 8.5pt; color: #444; }
        .doc-title { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 12px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background-color: #E8F5E9; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer-sig { margin-top: 30px; width: 100%; }
        .footer-sig td { border: none; padding: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fef3c7; border: 1px solid #f59e0b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <span><strong>Pratinjau Cetak:</strong> Gunakan tombol di sebelah kanan atau tekan Ctrl+P untuk mencetak Buku Keuangan.</span>
        <button onclick="window.print()" style="background: #1B4332; color: white; border: none; padding: 6px 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h2>GERAKAN PRAMUKA GUGUS DEPAN 03.059 – 03.060</h2>
        <h3>PANGKALAN SMP NEGERI 1 CERBON</h3>
        <p>Jl. HM. Ruslan RT.008 Desa Bantuil, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan</p>
    </div>

    <div class="doc-title">BUKU II : BUKU KEUANGAN KAS REGU {{ strtoupper($regu->nama_regu) }}</div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="45%">Uraian Transaksi</th>
                <th width="17%">Masuk (Rp)</th>
                <th width="18%">Keluar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $saldo = 0; @endphp
            @forelse($finances as $idx => $f)
                @php
                    if ($f->jenis === 'masuk') $saldo += $f->nominal;
                    else $saldo -= $f->nominal;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $f->tanggal->format('d/m/Y') }}</td>
                    <td>
                        {{ $f->uraian }}
                        @if($f->kategori)<small> [{{ $f->kategori }}]</small>@endif
                    </td>
                    <td class="text-right">{{ $f->jenis === 'masuk' ? number_format($f->nominal, 0, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $f->jenis === 'keluar' ? number_format($f->nominal, 0, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada transaksi tercatat.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">Rp {{ number_format($kasMasuk, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($kasKeluar, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #E8F5E9; font-weight: bold; font-size: 10pt;">
                <td colspan="3" class="text-right">SISA SALDO AKHIR</td>
                <td colspan="2" class="text-center" style="color: #1B4332;">Rp {{ number_format($saldoKas, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-sig">
        <tr>
            <td width="50%" style="text-align: center;">
                Mengetahui,<br>
                Pemimpin Regu {{ $regu->nama_regu }}<br><br><br><br>
                <strong><u>{{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nama') ?? '( .............................. )' }}</u></strong>
            </td>
            <td width="50%" style="text-align: center;">
                Cerbon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Bendahara / Petugas Kas Regu<br><br><br><br>
                <strong><u>( ......................................... )</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
