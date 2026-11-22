<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Inventaris - Regu {{ $regu->nama_regu }}</title>
    <style>
        @page { size: a4 portrait; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 9.5pt; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #1B4332; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1B4332; }
        .header h3 { margin: 2px 0; font-size: 11pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0 0; font-size: 8.5pt; color: #444; }
        .doc-title { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 15px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background-color: #E8F5E9; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .footer-sig { margin-top: 30px; width: 100%; }
        .footer-sig td { border: none; padding: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fef3c7; border: 1px solid #f59e0b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <span><strong>Pratinjau Cetak:</strong> Gunakan tombol di sebelah kanan atau tekan Ctrl+P untuk mencetak Buku Inventaris.</span>
        <button onclick="window.print()" style="background: #1B4332; color: white; border: none; padding: 6px 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h2>GERAKAN PRAMUKA GUGUS DEPAN 03.059 – 03.060</h2>
        <h3>PANGKALAN SMP NEGERI 1 CERBON</h3>
        <p>Jl. HM. Ruslan RT.008 Desa Bantuil, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan</p>
    </div>

    <div class="doc-title">BUKU VI : BUKU INVENTARIS PERLENGKAPAN REGU {{ strtoupper($regu->nama_regu) }}</div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode</th>
                <th width="33%">Nama Barang / Alat</th>
                <th width="12%">Jumlah</th>
                <th width="14%">Kondisi</th>
                <th width="24%">Asal & Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $idx => $inv)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $inv->kode_barang ?: '-' }}</td>
                    <td><strong>{{ $inv->nama_barang }}</strong></td>
                    <td class="text-center">{{ $inv->jumlah }} {{ $inv->satuan }}</td>
                    <td class="text-center">
                        {{ $inv->kondisi === 'baik' ? 'Baik' : ($inv->kondisi === 'rusak_ringan' ? 'Rusak Ringan' : 'Rusak Berat') }}
                    </td>
                    <td>
                        {{ $inv->asal_barang ?: 'Beli Regu' }}
                        @if($inv->lokasi_penyimpanan) | Lokasi: {{ $inv->lokasi_penyimpanan }}@endif
                        @if($inv->keterangan) <br><small>({{ $inv->keterangan }})</small>@endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada inventaris barang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
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
                Petugas Perlengkapan Regu<br><br><br><br>
                <strong><u>( ......................................... )</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
