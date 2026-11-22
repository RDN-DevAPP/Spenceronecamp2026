@extends('layouts.main')

@section('title', 'Verifikasi Sertifikat Peserta Resmi - Spencerone Camp II')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-8 sm:py-14">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200/80 transition-all">
            
            {{-- Header Visual --}}
            <div class="bg-gradient-to-r from-[#0B2317] via-brand-forest to-[#1B4332] text-white p-6 sm:p-8 text-center relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-400/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-8 -bottom-8 w-32 h-32 bg-emerald-400/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-emerald-950 mb-3 shadow-xs">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        <span>Sertifikat Digital Resmi Terdaftar</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-white">
                        Spencerone Camp Season II
                    </h1>
                    <p class="text-xs text-emerald-200/90 font-medium mt-1">
                        Gugus Depan 03.059 – 03.060 • SMP Negeri 1 Cerbon
                    </p>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="p-6 sm:p-8 space-y-6">

                @if(!empty($isSearch))
                    {{-- HALAMAN UTAMA PENCARIAN & SCAN QR KAMERA --}}
                    <div class="text-center space-y-2">
                        <h2 class="text-xl font-black text-brand-forest uppercase tracking-tight">Verifikasi Sertifikat Peserta</h2>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto leading-relaxed">
                            Pindai kode QR pada fisik sertifikat menggunakan kamera atau masukkan nomor sertifikat / kode token secara manual di bawah ini.
                        </p>
                    </div>

                    {{-- Form Pencarian Manual --}}
                    <form method="GET" action="{{ route('verifikasi-sertifikat.index') }}" class="space-y-3">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" required
                                placeholder="Nomor sertifikat (cth: 001/SC-II/...) atau kode token"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold text-gray-800 focus:bg-white focus:border-brand-forest focus:ring-2 focus:ring-emerald-500/20 transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-brand-forest hover:bg-emerald-900 text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="check-circle" class="w-4 h-4 text-amber-400"></i>
                            <span>Cek Keaslian Sertifikat</span>
                        </button>
                    </form>

                    {{-- Divider --}}
                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink mx-4 text-gray-400 text-[10px] font-black uppercase tracking-widest">ATAU SCAN DENGAN KAMERA</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    {{-- Interactive Camera Scanner --}}
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 text-center space-y-3">
                        <button type="button" id="start-scan-btn" onclick="startQrScanner()"
                            class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-emerald-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="camera" class="w-4 h-4"></i>
                            <span>Buka Kamera Pemindai QR</span>
                        </button>

                        <div id="scanner-container" class="hidden overflow-hidden rounded-xl border-2 border-brand-forest relative bg-black">
                            <div id="reader" class="w-full aspect-square"></div>
                            <button type="button" onclick="stopQrScanner()"
                                class="absolute top-2 right-2 px-2.5 py-1 bg-red-600 text-white text-[10px] font-bold uppercase rounded-lg shadow-md z-20">
                                Tutup Kamera
                            </button>
                        </div>

                        <p class="text-[11px] text-gray-400">
                            Arahkan kamera smartphone atau webcam ke gambar QR-Code pada sertifikat.
                        </p>
                    </div>

                @elseif($isValid && (!empty($panitia) || !empty($juara) || !empty($anggota)))
                    {{-- Status Badge Valid --}}
                    <div class="bg-emerald-50 border-2 border-emerald-500/30 rounded-2xl p-5 text-center relative overflow-hidden">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-600/30 mb-3">
                            <i data-lucide="check-check" class="w-7 h-7"></i>
                        </div>
                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-xs">
                            Status : Sah &amp; Terverifikasi
                        </span>
                        <p class="text-xs text-emerald-800 font-medium mt-2 leading-relaxed max-w-sm mx-auto">
                            Sertifikat ini sah dan resmi tercatat dalam sistem pangkalan data Spencerone Camp Season II.
                        </p>
                    </div>

                    @if(!empty($panitia))
                        <div class="text-center pt-2">
                            <span class="text-[10px] font-black text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Diberikan Kepada :</span>
                            <h2 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight mt-2">{{ $panitia->nama }}</h2>
                            <div class="inline-flex items-center gap-2 mt-3 flex-wrap justify-center">
                                <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-brand-forest text-amber-300 shadow-xs">PANITIA RESMI</span>
                                <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-200">{{ $panitia->jabatan }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 space-y-3.5 text-xs">
                            <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60">
                                <span class="font-bold text-gray-500 uppercase">Nomor Sertifikat</span>
                                <span class="font-mono font-black text-brand-forest">{{ $panitia->nomor_sertifikat }}</span>
                            </div>
                            @if($panitia->divisi)
                                <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60">
                                    <span class="font-bold text-gray-500 uppercase">Bidang / Seksi</span>
                                    <span class="font-bold text-gray-900">{{ $panitia->divisi }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60">
                                <span class="font-bold text-gray-500 uppercase">Instansi / Pangkalan</span>
                                <span class="font-bold text-gray-800">{{ $panitia->asal_instansi ?: 'SMP Negeri 1 Cerbon' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-500 uppercase">Tanggal Terbit</span>
                                <span class="font-bold text-gray-800">{{ $panitia->diterbitkan_pada ? $panitia->diterbitkan_pada->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    @elseif(!empty($juara))
                        <div class="text-center pt-2">
                            <span class="text-[10px] font-black text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Diberikan Kepada :</span>
                            <h2 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight mt-2">{{ $juara->nama_penerima }}</h2>
                            <div class="inline-flex items-center gap-2 mt-3 flex-wrap justify-center">
                                <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-amber-500 text-emerald-950 shadow-xs">
                                    {{ $juara->peringkat }} {{ $juara->kategori ? '(' . $juara->kategori . ')' : '' }}
                                </span>
                                <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-200">
                                    {{ $juara->nama_lomba }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 space-y-3.5 text-xs">
                            <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60">
                                <span class="font-bold text-gray-500 uppercase">Nomor Sertifikat</span>
                                <span class="font-mono font-black text-brand-forest">{{ $juara->nomor_sertifikat }}</span>
                            </div>
                            @if($juara->reguProfile)
                                <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60">
                                    <span class="font-bold text-gray-500 uppercase">Regu</span>
                                    <span class="font-black text-gray-900 uppercase">{{ $juara->reguProfile->nama_regu }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-500 uppercase">Tanggal Terbit</span>
                                <span class="font-bold text-gray-800">{{ $juara->diterbitkan_pada ? $juara->diterbitkan_pada->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    @else
                        {{-- Data Peserta --}}
                        <div class="text-center pt-2">
                            <span class="text-[10px] font-black text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                Diberikan Kepada :
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-brand-forest uppercase tracking-tight mt-2">
                                {{ $anggota->nama }}
                            </h2>
                            @if($anggota->nisn)
                                <p class="text-xs font-mono font-bold text-gray-500 mt-1">NISN: {{ $anggota->nisn }}</p>
                            @endif

                            <div class="inline-flex items-center gap-2 mt-3">
                                <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-brand-forest text-amber-300 shadow-xs">
                                    PESERTA RESMI
                                </span>
                                @if($anggota->jabatan)
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-900 border border-emerald-200">
                                        {{ strtoupper($anggota->jabatan) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Detail Info Card --}}
                        <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80 space-y-3.5">
                            <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60 text-xs">
                                <span class="font-bold text-gray-500 uppercase tracking-wide">Nomor Sertifikat</span>
                                <span class="font-mono font-black text-brand-forest text-right">
                                    {{ $sertifikat->nomor_sertifikat ?? '001/SC-II/SERTIFIKAT/IV/2027' }}
                                </span>
                            </div>

                            @if($regu)
                                <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60 text-xs">
                                    <span class="font-bold text-gray-500 uppercase tracking-wide">Regu</span>
                                    <span class="font-black text-gray-900 uppercase text-right">
                                        {{ $regu->nama_regu }} ({{ ucfirst($regu->jenis) }})
                                    </span>
                                </div>

                                <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60 text-xs">
                                    <span class="font-bold text-gray-500 uppercase tracking-wide">Pangkalan / Sekolah</span>
                                    <span class="font-bold text-gray-800 text-right">
                                        {{ $regu->sekolah ?? 'SMP Negeri 1 Cerbon' }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60 text-xs">
                                    <span class="font-bold text-gray-500 uppercase tracking-wide">Gugus Depan</span>
                                    <span class="font-bold text-gray-800 text-right">
                                        {{ $regu->jenis === 'putri' ? 'Gudep 03.060' : 'Gudep 03.059' }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center pb-2.5 border-b border-gray-200/60 text-xs">
                                <span class="font-bold text-gray-500 uppercase tracking-wide">Kegiatan</span>
                                <span class="font-bold text-gray-800 text-right">
                                    Spencerone Camp Season II (2027)
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-gray-500 uppercase tracking-wide">Tanggal Terbit</span>
                                <span class="font-bold text-gray-800 text-right">
                                    23 April 2027
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Pengesahan Info --}}
                    <div class="bg-emerald-950 text-white rounded-2xl p-4 sm:p-5 text-xs space-y-2 border border-emerald-700/40">
                        <div class="flex items-center gap-2 font-black text-amber-300 uppercase tracking-wider text-[11px]">
                            <i data-lucide="award" class="w-4 h-4"></i>
                            <span>Pengesahan Pejabat Resmi</span>
                        </div>
                        <p class="text-emerald-100/90 leading-relaxed text-[11px]">
                            Ditandatangani secara resmi oleh Ka. Mabigus <strong>Noormaidah, S. Pd., M.Pd.</strong> (NIP. 19790705 200604 2 031) dan Pembina Pramuka <strong>Rabianti, S.Pd.</strong> (NIP. 19940412 202221 2 003).
                        </p>
                    </div>

                @else
                    {{-- Status Badge Tidak Ditemukan --}}
                    <div class="bg-rose-50 border-2 border-rose-500/30 rounded-2xl p-6 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-600/30 mb-3">
                            <i data-lucide="alert-triangle" class="w-7 h-7"></i>
                        </div>
                        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-rose-600 text-white shadow-xs">
                            Sertifikat Tidak Ditemukan
                        </span>
                        <p class="text-xs text-rose-800 font-medium mt-3 leading-relaxed max-w-sm mx-auto">
                            Nomor sertifikat atau kode verifikasi tidak valid atau sertifikat belum terdaftar dalam sistem Spencerone Camp II.
                        </p>
                        @if(!empty($token))
                            <p class="text-[10px] font-mono text-gray-400 mt-2">Pencarian: {{ $token }}</p>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('verifikasi-sertifikat.index') }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-forest text-white text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-emerald-900 transition">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i> Coba Cari Lagi
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Footer Action --}}
                <div class="pt-2 flex items-center justify-center gap-3">
                    @if(empty($isSearch))
                        <a href="{{ route('verifikasi-sertifikat.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs uppercase tracking-wider transition">
                            <i data-lucide="scan" class="w-4 h-4"></i>
                            <span>Scan Sertifikat Lain</span>
                        </a>
                    @endif
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs uppercase tracking-wider transition">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        <span>Beranda</span>
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrCode = null;

    function startQrScanner() {
        const container = document.getElementById('scanner-container');
        const startBtn = document.getElementById('start-scan-btn');
        container.classList.remove('hidden');
        startBtn.classList.add('hidden');

        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
            // Berhasil scan!
            html5QrCode.stop().then(() => {
                if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                    window.location.href = decodedText;
                } else {
                    window.location.href = `{{ url('verifikasi-sertifikat') }}/${encodeURIComponent(decodedText)}`;
                }
            });
        }).catch(err => {
            console.warn("Camera start error:", err);
            alert("Tidak dapat mengakses kamera. Pastikan izin kamera aktif pada peramban Anda.");
            stopQrScanner();
        });
    }

    function stopQrScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(e => console.log(e));
        }
        document.getElementById('scanner-container').classList.add('hidden');
        document.getElementById('start-scan-btn').classList.remove('hidden');
    }
</script>
@endpush
