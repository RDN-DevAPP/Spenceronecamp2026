<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Notulen Rapat - Regu {{ $regu->nama_regu }}</title>
    <style>
        @page { size: a4 portrait; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 9.5pt; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #1B4332; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; color: #1B4332; }
        .header h3 { margin: 2px 0; font-size: 11pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0 0; font-size: 8.5pt; color: #444; }
        .doc-title { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 15px; text-transform: uppercase; }
        .note-item { border: 1px solid #333; margin-bottom: 15px; padding: 12px; page-break-inside: avoid; }
        .note-header { font-weight: bold; font-size: 11pt; color: #4A154B; border-bottom: 1px dashed #666; padding-bottom: 4px; margin-bottom: 8px; }
        .note-meta { font-size: 8.5pt; color: #444; margin-bottom: 8px; }
        .note-body { font-size: 9pt; line-height: 1.4; margin-bottom: 8px; }
        .decision-box { background-color: #F3E8FF; border: 1px solid #D8B4FE; padding: 8px 10px; border-radius: 4px; margin-top: 6px; }
        .footer-sig { margin-top: 30px; width: 100%; }
        .footer-sig td { border: none; padding: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fef3c7; border: 1px solid #f59e0b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <span><strong>Pratinjau Cetak:</strong> Gunakan tombol di sebelah kanan atau tekan Ctrl+P untuk mencetak Buku Notulen Rapat.</span>
        <button onclick="window.print()" style="background: #1B4332; color: white; border: none; padding: 6px 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h2>GERAKAN PRAMUKA GUGUS DEPAN 03.059 – 03.060</h2>
        <h3>PANGKALAN SMP NEGERI 1 CERBON</h3>
        <p>Jl. HM. Ruslan RT.008 Desa Bantuil, Kec. Cerbon, Kab. Barito Kuala, Kalimantan Selatan</p>
    </div>

    <div class="doc-title">BUKU IV : BUKU NOTULEN RAPAT REGU {{ strtoupper($regu->nama_regu) }}</div>

    @forelse($notes as $idx => $note)
        <div class="note-item">
            <div class="note-header">
                NOTULENSI RAPAT #{{ $idx + 1 }} : {{ $note->agenda }}
            </div>
            <div class="note-meta">
                <strong>Hari/Tanggal:</strong> {{ $note->tanggal->translatedFormat('l, d F Y') }} |
                <strong>Waktu:</strong> {{ $note->waktu ?: '-' }} |
                <strong>Tempat:</strong> {{ $note->tempat ?: '-' }}<br>
                <strong>Pemimpin Rapat:</strong> {{ $note->pemimpin_rapat ?: 'Pinru' }} |
                <strong>Notulis:</strong> {{ $note->notulis ?: 'Wapinru' }} |
                <strong>Jumlah Hadir:</strong> {{ $note->jumlah_hadir }} orang
            </div>
            @if($note->daftar_hadir_teks)
                <div class="note-body">
                    <strong>Anggota yang Hadir:</strong><br>
                    {{ $note->daftar_hadir_teks }}
                </div>
            @endif
            @if($note->jalannya_rapat)
                <div class="note-body">
                    <strong>Jalannya Musyawarah:</strong><br>
                    {{ $note->jalannya_rapat }}
                </div>
            @endif
            <div class="decision-box">
                <strong>KEPUTUSAN RAPAT REGU:</strong><br>
                {{ $note->keputusan }}
            </div>
        </div>
    @empty
        <p style="text-align: center; color: #777;">Belum ada data notulen rapat regu.</p>
    @endforelse

    <table class="footer-sig">
        <tr>
            <td width="50%" style="text-align: center;">
                Pemimpin Rapat,<br><br><br><br>
                <strong><u>{{ $regu->anggotaRegu()->where('jabatan', 'pinru')->value('nama') ?? '( .............................. )' }}</u></strong>
            </td>
            <td width="50%" style="text-align: center;">
                Cerbon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Notulis Rapat Regu,<br><br><br><br>
                <strong><u>( ......................................... )</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
