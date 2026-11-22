<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Tanda Peserta (ID Card) - {{ $regu->nama_regu }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');
        
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .id-card {
                box-shadow: none !important;
                border: 1px solid #1B4332 !important;
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        .id-card {
            width: 85.6mm;
            height: 125mm;
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            border: 2px solid #1B4332;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">

    <!-- Print Control Bar -->
    <div class="max-w-4xl mx-auto mb-8 no-print bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-emerald-950 uppercase">ID Card Digital Pramuka</h1>
            <p class="text-xs text-gray-500">Regu {{ $regu->nama_regu }} ({{ ucfirst($regu->jenis) }}) • {{ $regu->nomor_kavling ?? 'Kavling Tenda' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('peserta.dashboard') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">
                &larr; Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-black rounded-xl text-xs shadow-md transition flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4 text-amber-400"></i> Cetak Semua ID Card
            </button>
        </div>
    </div>

    <!-- Cards Grid (Printable Layout) -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8 justify-items-center">
        @foreach($anggota as $m)
            <div class="id-card flex flex-col justify-between p-3.5">
                
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-emerald-900 to-[#1B4332] text-white rounded-lg p-2.5 text-center relative border border-amber-400/40">
                    <div class="flex items-center justify-between px-1">
                        <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Logo" class="w-7 h-7 rounded-full object-cover border border-amber-400 shadow-xs">
                        <div>
                            <span class="text-[11px] font-black tracking-tight text-white block uppercase leading-tight">
                                Spencerone Camp <span class="text-amber-400">II</span>
                            </span>
                            <span class="text-[8px] text-amber-200 font-bold block uppercase tracking-wider">
                                SMPN 1 Cerbon • Barito Kuala
                            </span>
                        </div>
                        <div class="w-7 h-7 bg-amber-400/20 rounded-full flex items-center justify-center text-[10px] font-black text-amber-300 border border-amber-400/30">
                            {{ $regu->nomor_regu }}
                        </div>
                    </div>
                </div>

                <!-- Avatar & Identitas Diri (Foto Rasio 4:5) -->
                <div class="my-auto text-center py-2">
                    <div class="relative w-[96px] h-[120px] aspect-[4/5] mx-auto mb-2.5">
                        <div class="w-full h-full rounded-xl bg-emerald-50 border-2 border-emerald-800 overflow-hidden flex items-center justify-center shadow-inner">
                            @if($m->foto_path)
                                <img src="{{ asset('storage/' . $m->foto_path) }}" alt="{{ $m->nama }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-2">
                                    <i data-lucide="user" class="w-10 h-10 text-emerald-800/40 mx-auto"></i>
                                    <span class="text-[8px] font-bold text-emerald-800/60 uppercase">Foto 4:5</span>
                                </div>
                            @endif
                        </div>
                        <!-- Badge Role -->
                        <span class="absolute -bottom-2.5 inset-x-0 mx-auto w-fit px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider text-white shadow-sm border border-white
                            {{ $m->jabatan === 'pinru' ? 'bg-red-600' : ($m->jabatan === 'wapinru' ? 'bg-amber-600' : 'bg-emerald-700') }}">
                            {{ strtoupper($m->jabatan) }}
                        </span>
                    </div>

                    <h2 class="text-base font-black text-gray-900 tracking-tight leading-tight mt-3 uppercase px-2 truncate" title="{{ $m->nama }}">
                        {{ $m->nama }}
                    </h2>
                    
                    @if($m->nisn)
                        <p class="text-[10px] font-mono font-bold text-gray-500">NISN: {{ $m->nisn }}</p>
                    @endif

                    <div class="flex items-center justify-center gap-1.5 mt-1.5">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-900 uppercase">
                            Kelas {{ $m->kelas }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-900 uppercase">
                            TKU: {{ $m->tingkatan_tku ?? '-' }}
                        </span>
                    </div>
                </div>

                <!-- Info Regu & QR Code Footer -->
                <div class="border-t-2 border-dashed border-gray-200 pt-2.5 flex items-center justify-between">
                    <div class="text-left space-y-0.5">
                        <span class="text-[8px] font-bold uppercase tracking-widest text-gray-400 block">PANGKALAN REGU</span>
                        <span class="text-xs font-black text-emerald-950 uppercase block leading-tight">{{ $regu->nama_regu }}</span>
                        <span class="text-[9px] font-bold text-amber-700 block">
                            Regu {{ ucfirst($regu->jenis) }} • {{ $regu->nomor_kavling ?? 'Kavling Tenda' }}
                        </span>
                    </div>

                    <!-- Dynamic SVG QR Code to Verification Route -->
                    <div class="shrink-0 flex flex-col items-center">
                        <a href="{{ route('verifikasi-regu', $regu->id) }}" target="_blank" title="Scan untuk verifikasi regu">
                            <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="100" height="100" rx="6" fill="#F4EDE4"/>
                                <!-- Simulated QR patterns with clean scout styling -->
                                <rect x="10" y="10" width="28" height="28" rx="4" fill="#1B4332"/>
                                <rect x="16" y="16" width="16" height="16" rx="2" fill="#F4EDE4"/>
                                <rect x="20" y="20" width="8" height="8" fill="#D97706"/>

                                <rect x="62" y="10" width="28" height="28" rx="4" fill="#1B4332"/>
                                <rect x="68" y="16" width="16" height="16" rx="2" fill="#F4EDE4"/>
                                <rect x="72" y="20" width="8" height="8" fill="#D97706"/>

                                <rect x="10" y="62" width="28" height="28" rx="4" fill="#1B4332"/>
                                <rect x="16" y="68" width="16" height="16" rx="2" fill="#F4EDE4"/>
                                <rect x="20" y="72" width="8" height="8" fill="#D97706"/>

                                <!-- Center Data Pattern -->
                                <rect x="44" y="44" width="12" height="12" rx="2" fill="#1B4332"/>
                                <rect x="46" y="14" width="8" height="20" fill="#1B4332"/>
                                <rect x="14" y="46" width="20" height="8" fill="#1B4332"/>
                                <rect x="66" y="46" width="20" height="8" fill="#1B4332"/>
                                <rect x="46" y="66" width="8" height="20" fill="#1B4332"/>
                                <rect x="66" y="66" width="14" height="14" fill="#D97706"/>
                            </svg>
                        </a>
                        <span class="text-[7px] font-mono text-gray-400 mt-0.5">VERIFIKASI ID</span>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
