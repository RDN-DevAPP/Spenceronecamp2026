<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ID Card Digital Pramuka' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-forest': '#0B2317',
                        'brand-moss': '#1B4332',
                        'brand-amber': '#F59E0B',
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');
        
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 8mm 8mm 8mm 8mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #FFFFFF !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-container {
                page-break-after: always;
                break-after: page;
                margin-bottom: 0 !important;
                padding: 0 !important;
            }
            .page-container:last-child {
                page-break-after: avoid;
                break-after: avoid;
            }
            .id-card-wrapper {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        /* 4 ID Cards per A4 page (2 columns x 2 rows) */
        .id-card {
            width: 85.6mm;
            height: 125mm;
            background-color: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            border: 2px solid #0B2317;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        /* Pattern kotak-kotak persis seperti latar belakang halaman login */
        .cyber-grid {
            background-image: linear-gradient(to right, rgba(217, 119, 6, 0.14) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(217, 119, 6, 0.14) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">

    <!-- Print Control Bar -->
    <div class="max-w-4xl mx-auto mb-8 no-print bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-lg font-black text-[#0B2317] uppercase tracking-tight">{{ $title }}</h1>
            <p class="text-xs text-gray-500">Format Kertas: A4 Portrait • Tepat 4 ID Card per lembar • Lengkap dengan Pola Grid Kotak-Kotak & QR Code Verifikasi</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.id-cards.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition flex items-center gap-1.5">
                <span>&larr;</span> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 bg-[#0B2317] hover:bg-[#153e2b] text-white font-black rounded-xl text-xs shadow-md hover:shadow-lg transition flex items-center gap-2 border border-amber-400/50 cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4 text-amber-400"></i>
                <span>Cetak Sekarang (Print / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Printable ID Cards Pages (Grouped into chunks of 4 for clean A4 printing) -->
    @php
        $cardChunks = $cards->chunk(4);
    @endphp

    @forelse($cardChunks as $pageIndex => $chunk)
        <div class="page-container max-w-[190mm] mx-auto grid grid-cols-2 gap-4 justify-items-center mb-8">
            @foreach($chunk as $item)
                @php
                    $m = $item['anggota'];
                    $regu = $item['regu'];
                    $qrCode = $item['qr_code'];
                @endphp
                <div class="id-card-wrapper p-1">
                    <div class="id-card cyber-grid flex flex-col justify-between p-3.5">
                        
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-[#0B2317] to-[#1B4332] text-white rounded-lg p-2 text-center relative border border-amber-400/40 shadow-xs">
                            <div class="flex items-center justify-between px-1">
                                <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo" class="w-7 h-7 rounded-full object-cover border border-amber-400 shadow-xs">
                                <div>
                                    <span class="text-[11px] font-black tracking-tight text-white block uppercase leading-tight">
                                        Spencerone Camp <span class="text-amber-400">II</span>
                                    </span>
                                    <span class="text-[7.5pt] text-amber-200 font-bold block uppercase tracking-wider">
                                        SMPN 1 Cerbon • Barito Kuala
                                    </span>
                                </div>
                                <div class="w-7 h-7 bg-amber-400/20 rounded-full flex items-center justify-center text-[10px] font-black text-amber-300 border border-amber-400/30">
                                    {{ $regu->nomor_regu }}
                                </div>
                            </div>
                        </div>

                        <!-- Avatar & Identitas Diri (Foto Rasio 4:5) -->
                        <div class="my-auto text-center py-1">
                            <div class="relative w-[96px] h-[120px] aspect-[4/5] mx-auto mb-2.5">
                                <div class="w-full h-full rounded-xl bg-white border-2 border-brand-forest overflow-hidden flex items-center justify-center shadow-inner">
                                    @if($m->foto_path)
                                        <img src="{{ asset('storage/' . $m->foto_path) }}" alt="{{ $m->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-center p-2 bg-emerald-50 w-full h-full flex flex-col items-center justify-center">
                                            <i data-lucide="user" class="w-10 h-10 text-emerald-800/40 mx-auto"></i>
                                            <span class="text-[8px] font-bold text-emerald-800/60 uppercase mt-1">Foto 4:5</span>
                                        </div>
                                    @endif
                                </div>
                                <!-- Badge Role -->
                                <span class="absolute -bottom-2.5 inset-x-0 mx-auto w-fit px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider text-white shadow-sm border border-white
                                    {{ $m->jabatan === 'pinru' ? 'bg-rose-600' : ($m->jabatan === 'wapinru' ? 'bg-amber-600' : 'bg-emerald-700') }}">
                                    {{ strtoupper($m->jabatan) }}
                                </span>
                            </div>

                            <h2 class="text-sm font-black text-gray-900 tracking-tight leading-tight mt-3 uppercase px-1 truncate" title="{{ $m->nama }}">
                                {{ $m->nama }}
                            </h2>
                            
                            @if($m->nisn)
                                <p class="text-[9px] font-mono font-bold text-gray-500 mt-0.5">NISN: {{ $m->nisn }}</p>
                            @endif

                            <div class="flex items-center justify-center gap-1.5 mt-1">
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-amber-100 text-amber-900 uppercase border border-amber-200">
                                    Kelas {{ $m->kelas }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-emerald-100 text-emerald-900 uppercase border border-emerald-200">
                                    TKU: {{ $m->tingkatan_tku ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Themed Footer Container -->
                        <div class="bg-gradient-to-r from-[#0B2317] via-[#133A27] to-[#0A2015] text-white rounded-2xl p-2.5 border border-amber-400/40 shadow-sm relative overflow-hidden shrink-0">
                            <!-- Subtle Ambient Gold Glow inside footer -->
                            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-amber-400/10 rounded-full blur-lg pointer-events-none"></div>

                            <div class="flex items-center justify-between gap-2 relative z-10">
                                <!-- Left: Mascot + Regu Details -->
                                <div class="flex items-center gap-2 overflow-hidden flex-1">
                                    <!-- Official Mascot -->
                                    <div class="shrink-0 flex items-center justify-center">
                                        <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Maskot Resmi" class="h-20 w-auto object-contain drop-shadow-md">
                                    </div>

                                    <!-- Regu Details -->
                                    <div class="space-y-0.5 overflow-hidden">
                                        <span class="text-[7px] font-extrabold uppercase tracking-wider text-amber-300/90 block">PANGKALAN REGU</span>
                                        <span class="text-[11px] font-black text-white uppercase block truncate leading-tight tracking-tight">{{ $regu->nama_regu }}</span>
                                        <span class="text-[8.5px] font-bold text-emerald-200 block truncate">
                                            Regu {{ ucfirst($regu->jenis) }}
                                        </span>
                                        <div class="pt-0.5">
                                            <span class="inline-block px-2 py-0.5 rounded text-[8px] font-black bg-amber-400 text-brand-forest uppercase tracking-wider shadow-xs">
                                                {{ $regu->nomor_kavling ?? 'Kavling Regu' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Larger QR Code with White Base -->
                                <div class="shrink-0 flex flex-col items-center">
                                    <div class="bg-white p-1 rounded-xl shadow-md border border-amber-400/50 flex items-center justify-center" style="width: 78px; height: 78px; min-width: 78px; min-height: 78px;">
                                        <img src="{{ $qrCode }}" alt="QR Verifikasi" class="rounded-lg block" style="width: 70px; height: 70px; min-width: 70px; min-height: 70px; display: block; object-fit: contain;">
                                    </div>
                                    <span class="text-[6.5px] font-mono text-amber-300 font-extrabold mt-1 uppercase tracking-wider">VERIFIKASI ID</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="p-8 text-center bg-white rounded-2xl max-w-md mx-auto shadow-sm">
            <p class="text-sm font-bold text-gray-500">Tidak ada anggota yang dapat dicetak ID Card-nya.</p>
        </div>
    @endforelse

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
