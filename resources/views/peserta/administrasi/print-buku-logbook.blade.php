<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Logbook Kegiatan - Regu {{ $regu->nama_regu }}</title>
    <style>
        @page { size: a4 portrait; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 9.5pt; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #1B4332; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1B4332; }
        .header h3 { margin: 2px 0; font-size: 11pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0 0; font-size: 8.5pt; color: #444; }
        .doc-title { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 15px; text-transform: uppercase; }
        .log-item { border: 1px solid #333; margin-bottom: 12px; padding: 10px; page-break-inside: avoid; }
        .log-header { font-weight: bold; font-size: 10.5pt; color: #1B4332; border-bottom: 1px dashed #666; padding-bottom: 4px; margin-bottom: 6px; }
        .log-meta { font-size: 8.5pt; color: #444; margin-bottom: 6px; }
        .log-body { font-size: 9pt; line-height: 1.4; margin-bottom: 6px; }
        .footer-sig { margin-top: 30px; width: 100%; }
        .footer-sig td { border: none; padding: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fef3c7; border: 1px solid #f59e0b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <span><strong>Pratinjau Cetak:</strong> Gunakan tombol di sebelah kanan atau tekan Ctrl+P untuk mencetak Buku Logbook.</span>
        <button onclick="window.print()" style="background: #1B4332; color: white; border: none; padding: 6px 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h2>GERAKAN PRAMUKA GUGUS DEPAN 03.059 – 03.060</h2>
        <h3>PANGKALAN SMP NEGERI 1 CERBON</h3>
        <p>Jl. HM. Ruslan RT.008 Desa Bantuil, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan</p>
    </div>

    <div class="doc-title">BUKU III : BUKU CATATAN KEGIATAN (LOGBOOK) REGU {{ strtoupper($regu->nama_regu) }}</div>

    @forelse($logbooks as $idx => $log)
        <div class="log-item">
            <div class="log-header">
                #{{ $idx + 1 }} {{ $log->nama_kegiatan }}
            </div>
            <div class="log-meta">
                <strong>Hari/Tanggal:</strong> {{ $log->tanggal->translatedFormat('l, d F Y') }} |
                <strong>Waktu:</strong> {{ $log->waktu_mulai ?? '-' }} s/d {{ $log->waktu_selesai ?? '-' }} |
                <strong>Tempat:</strong> {{ $log->tempat ?? '-' }} |
                <strong>Instruktur/Pemimpin:</strong> {{ $log->pemimpin_kegiatan ?? '-' }}
            </div>
            <div class="log-body">
                <strong>Materi & Jalannya Aktivitas:</strong><br>
                {{ $log->ringkasan_materi ?: '-' }}
            </div>
            @if($log->hasil_kegiatan)
                <div class="log-body">
                    <strong>Hasil / Pencapaian:</strong><br>
                    {{ $log->hasil_kegiatan }}
                </div>
            @endif
            @if($log->catatan_evaluasi)
                <div class="log-body" style="color: #666; font-style: italic;">
                    <strong>Evaluasi:</strong> {{ $log->catatan_evaluasi }}
                </div>
            @endif
        </div>
    @empty
        <p style="text-align: center; color: #777;">Belum ada catatan logbook kegiatan.</p>
    @endforelse

    <table class="footer-sig">
        <tr>
            <td width="50%" style="text-align: center;">
                Mengetahui,<br>
                Pembina Pendamping<br><br><br><br>
                <strong><u>( ......................................... )</u></strong>
            </td>
            <td width="50%" style="text-align: center;">
                Cerbon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pemimpin Regu {{ $regu->nama_regu }}<br><br><br><br>
                <strong><u>{{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nama') ?? '( .............................. )' }}</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
