<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Presensi - Regu {{ $regu->nama_regu }}</title>
    <style>
        @page { size: a4 landscape; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #1B4332; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1B4332; }
        .header h3 { margin: 2px 0; font-size: 11pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0 0; font-size: 8.5pt; color: #444; }
        .doc-title { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 15px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        th, td { border: 1px solid #333; padding: 5px 6px; }
        th { background-color: #E8F5E9; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .footer-sig { margin-top: 30px; width: 100%; }
        .footer-sig td { border: none; padding: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fef3c7; border: 1px solid #f59e0b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <span><strong>Pratinjau Cetak:</strong> Gunakan tombol di sebelah kanan atau tekan Ctrl+P untuk mencetak Buku Presensi.</span>
        <button onclick="window.print()" style="background: #1B4332; color: white; border: none; padding: 6px 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h2>GERAKAN PRAMUKA GUGUS DEPAN 03.059 – 03.060</h2>
        <h3>PANGKALAN SMP NEGERI 1 CERBON</h3>
        <p>Jl. HM. Ruslan RT.008 Desa Bantuil, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan</p>
    </div>

    <div class="doc-title">BUKU V : BUKU PRESENSI / DAFTAR HADIR REGU {{ strtoupper($regu->nama_regu) }}</div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="4%">No</th>
                <th rowspan="2" width="22%">Nama Anggota</th>
                <th rowspan="2" width="10%">Jabatan</th>
                <th colspan="{{ max(1, $attendances->count()) }}">Pertemuan / Tanggal Latihan</th>
                <th colspan="4" width="16%">Rekapitulasi</th>
            </tr>
            <tr>
                @forelse($attendances as $att)
                    <th style="font-size: 7.5pt; font-weight: bold;">
                        {{ $att->tanggal->format('d/m') }}<br>
                        <span style="font-weight: normal;">{{ Str::limit($att->nama_kegiatan, 10) }}</span>
                    </th>
                @empty
                    <th>-</th>
                @endforelse
                <th width="4%">H</th>
                <th width="4%">I</th>
                <th width="4%">S</th>
                <th width="4%">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggotaList as $idx => $anggota)
                @php
                    $h = 0; $i = 0; $s = 0; $a = 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $anggota->nama }}</strong></td>
                    <td class="text-center uppercase">{{ $anggota->jabatan }}</td>
                    @forelse($attendances as $att)
                        @php
                            $det = $att->details->firstWhere('anggota_regu_id', $anggota->id);
                            $st = $det ? $det->status : '-';
                            if ($st === 'hadir') { $h++; $badge = '✓'; }
                            elseif ($st === 'izin') { $i++; $badge = 'I'; }
                            elseif ($st === 'sakit') { $s++; $badge = 'S'; }
                            elseif ($st === 'alpa') { $a++; $badge = 'A'; }
                            else { $badge = '-'; }
                        @endphp
                        <td class="text-center" style="font-weight: bold;">{{ $badge }}</td>
                    @empty
                        <td class="text-center">-</td>
                    @endforelse
                    <td class="text-center" style="background: #E8F5E9; font-weight: bold;">{{ $h }}</td>
                    <td class="text-center" style="background: #E0F2FE;">{{ $i }}</td>
                    <td class="text-center" style="background: #FEF3C7;">{{ $s }}</td>
                    <td class="text-center" style="background: #FEE2E2;">{{ $a }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-sig">
        <tr>
            <td width="60%">
                <small><strong>Keterangan Status:</strong> ✓ = Hadir, I = Izin, S = Sakit, A = Alpa tanpa keterangan</small>
            </td>
            <td width="40%" style="text-align: center;">
                Cerbon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pemimpin Regu {{ $regu->nama_regu }}<br><br><br><br>
                <strong><u>{{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nama') ?? '( .............................. )' }}</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
