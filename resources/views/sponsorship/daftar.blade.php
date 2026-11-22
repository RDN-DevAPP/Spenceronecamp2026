@extends('layouts.main')

@section('title', 'Pendaftaran Sponsorship - Spencerone Camp Season II')

@section('content')
<!-- SUB-HERO BANNER (Senada dengan Desain Beranda Season II) -->
<section class="relative bg-[#0B2317] overflow-hidden border-b-4 border-amber-500 text-white select-none">
    
    <!-- Cyber-Scout Background Mesh -->
    <div class="absolute inset-0 pointer-events-none opacity-25">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="sponsor-mesh" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#D97706" stroke-width="0.8" stroke-opacity="0.35"/>
                    <circle cx="48" cy="0" r="1.5" fill="#D97706" fill-opacity="0.7"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#sponsor-mesh)"/>
        </svg>
    </div>

    <!-- Ambient Lighting Glows -->
    <div class="absolute top-1/2 left-1/4 -translate-y-1/2 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto py-10 sm:py-16 px-4 sm:px-6 lg:px-8 z-10 text-center">
        
        <!-- Logo & Mascot Badges -->
        <div class="flex items-center justify-center gap-3 sm:gap-4 mb-4">
            <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Spencerone Camp Season II Logo"
                class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-amber-400 shadow-2xl ring-4 ring-emerald-500/30">
            <img src="{{ asset('images/season2/mascot-duo.png') }}" alt="Dwi Pandu Digital"
                class="h-16 sm:h-20 object-contain drop-shadow-md hidden sm:block">
        </div>

        <!-- Tagline Pill -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/15 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-extrabold tracking-wide uppercase mb-3 shadow-inner">
            <i data-lucide="handshake" class="w-4 h-4 text-amber-400"></i>
            <span>Ruang Kemitraan, Donatur & Dukungan UMKM</span>
        </div>

        <!-- Main Title -->
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
            Pendaftaran Sponsor & <span class="bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-300 bg-clip-text text-transparent">Mitra Pendukung</span>
        </h1>

        <p class="text-xs sm:text-base text-amber-100/90 max-w-2xl mx-auto font-medium leading-relaxed mb-4">
            Sinergi bersama membangun generasi muda yang tangkas, cerdas, dan berkarakter unggul pada Spencerone Camp Season II SMPN 1 Cerbon.
        </p>

        <!-- Guarantee Pill -->
        <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-white font-semibold bg-emerald-950/70 backdrop-blur-md px-5 py-2 rounded-full border border-emerald-500/30 shadow-md inline-flex">
            <span class="flex items-center gap-1.5 text-amber-300">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Tercatat Resmi dalam Laporan Pertanggungjawaban Panitia</span>
            </span>
        </div>

    </div>
</section>

<!-- MAIN CONTENT WRAPPER (Khaki Cream Background) -->
<div class="bg-[#F4F1EA] py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI: BENEFIT & REKENING RESMI PANITIA -->
            <div class="lg:col-span-5 space-y-6">
                
                @php
                    $bank = $bankSettings ?? [
                        'bank_name' => \App\Models\Setting::where('key', 'sponsor_bank_name')->value('value') ?? 'Bank BRI',
                        'bank_account' => \App\Models\Setting::where('key', 'sponsor_bank_account')->value('value') ?? '0643-01-002891-53-4',
                        'bank_owner' => \App\Models\Setting::where('key', 'sponsor_bank_owner')->value('value') ?? 'Gudep SMPN 1 Cerbon',
                        'bank_instruction' => \App\Models\Setting::where('key', 'sponsor_bank_instruction')->value('value') ?? 'Seluruh kontribusi kemitraan dan sponsorship disalurkan langsung melalui rekening bendahara kegiatan resmi:',
                        'bank_note' => \App\Models\Setting::where('key', 'sponsor_bank_note')->value('value') ?? 'Harap simpan bukti transfer untuk dilampirkan pada formulir.',
                    ];

                    if (!isset($benefits)) {
                        $benefitsJson = \App\Models\Setting::where('key', 'sponsor_benefits')->value('value');
                        $benefits = $benefitsJson ? json_decode($benefitsJson, true) : [
                            ['title' => 'Logo Ditampilkan di Beranda Web', 'description' => 'Logo dan nama usaha tampil langsung pada portal resmi Spencerone Camp Season II.'],
                            ['title' => 'Pemasangan Banner & Spanduk', 'description' => 'Hak pemasangan standing banner / umbul-umbul di area utama Bumi Perkemahan.'],
                            ['title' => 'Piagam Penghargaan Mitra Resmi', 'description' => 'Panitia memberikan piagam apresiasi resmi bertandatangan Kepala Sekolah & Kamabigus.'],
                        ];
                    }
                @endphp

                <!-- Card Rekening Resmi -->
                <div class="bg-[#0B2317] rounded-3xl p-6 sm:p-7 text-white border-2 border-amber-500/50 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-400/30">
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-400">Rekening Resmi Panitia</span>
                            <h3 class="text-lg font-black text-white">Bank Penampung Dana</h3>
                        </div>
                    </div>

                    <p class="text-xs text-emerald-100/80 leading-relaxed mb-4">
                        {{ $bank['bank_instruction'] ?: 'Seluruh kontribusi kemitraan dan sponsorship disalurkan langsung melalui rekening bendahara kegiatan resmi:' }}
                    </p>

                    <div class="bg-[#071910] p-4 rounded-2xl border border-emerald-500/30 space-y-2 font-mono text-xs">
                        <div class="flex justify-between items-center text-gray-400">
                            <span>Bank:</span>
                            <span class="text-white font-bold">{{ $bank['bank_name'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-400">
                            <span>Nomor Rekening:</span>
                            <span class="text-amber-400 font-black text-sm tracking-wider">{{ $bank['bank_account'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-400">
                            <span>Atas Nama:</span>
                            <span class="text-white font-bold">{{ $bank['bank_owner'] }}</span>
                        </div>
                    </div>

                    @if(!empty($bank['bank_note']))
                        <div class="mt-4 pt-3 border-t border-emerald-800/60 flex items-center gap-2 text-[11px] text-emerald-200/70">
                            <i data-lucide="info" class="w-4 h-4 text-amber-400 flex-shrink-0"></i>
                            <span>{{ $bank['bank_note'] }}</span>
                        </div>
                    @endif
                </div>

                <!-- Card Benefit Mitra -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border-2 border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-base font-black text-[#0B2317] uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-5 h-5 text-amber-500"></i>
                        Apresiasi Untuk Mitra Sponsor
                    </h3>

                    <div class="space-y-3 text-xs text-gray-600">
                        @forelse($benefits as $index => $b)
                            <div class="flex items-start gap-3 bg-stone-50 p-3 rounded-2xl border border-gray-100">
                                <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold flex-shrink-0 mt-0.5">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <strong class="text-[#0B2317] block text-xs sm:text-sm font-bold">{{ $b['title'] ?? '' }}</strong>
                                    <span class="text-gray-600 text-xs block mt-0.5">{{ $b['description'] ?? '' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic p-3 text-center">Belum ada daftar keuntungan yang ditentukan.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: FORMULIR PENDAFTARAN -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-gray-200 shadow-lg">
                    
                    <div class="mb-8 pb-4 border-b border-gray-100">
                        <span class="text-xs font-black uppercase text-amber-600 tracking-wider">Formulir Kemitraan</span>
                        <h2 class="text-2xl font-black text-[#0B2317] tracking-tight">Ajukan Dukungan Sponsor</h2>
                        <p class="text-xs text-gray-500 mt-1">Lengkapi data berikut. Tim panitia akan segera memverifikasi pengajuan Anda.</p>
                    </div>

                    @if (isset($errors) && $errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-2xl shadow-xs text-xs">
                            <div class="flex items-center gap-2 font-bold text-red-800 mb-1">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                                <span>Terdapat kesalahan pada isian form:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-0.5 text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('sponsorship.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Brand / Perusahaan -->
                        <div>
                            <label for="name" class="block text-xs font-black uppercase text-[#0B2317] tracking-wider mb-2">
                                Nama Usaha / Brand / Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="briefcase" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium"
                                    placeholder="Contoh: Toko Berkah Pramuka / PT Cahaya Sejahtera">
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Nama ini akan dipublikasikan di halaman portal dan laporan resmi.</p>
                        </div>

                        <!-- Nama PIC -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="pic_name" class="block text-xs font-black uppercase text-[#0B2317] tracking-wider mb-2">
                                    Nama Lengkap PIC / Narahubung <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="user" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium"
                                    placeholder="Nama perwakilan sponsor">
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-black uppercase text-[#0B2317] tracking-wider mb-2">
                                    No. WhatsApp / Telepon <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="phone" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium"
                                    placeholder="081234567890">
                                </div>
                            </div>
                        </div>

                        <!-- Email PIC -->
                        <div>
                            <label for="email" class="block text-xs font-black uppercase text-[#0B2317] tracking-wider mb-2">
                                Alamat Email Aktif <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-sm font-medium"
                                    placeholder="email@perusahaan.com">
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Konfirmasi verifikasi dan bukti penerimaan akan dikirim ke email ini.</p>
                        </div>

                        <!-- Upload File Logo & Bukti TF (Modern Dropzones) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            
                            <!-- Logo Upload Dropzone -->
                            <div>
                                <label class="block text-xs font-black uppercase text-[#0B2317] tracking-wider mb-2">
                                    Logo Perusahaan / Usaha <span class="text-red-500">*</span>
                                </label>
                                <div onclick="document.getElementById('logo').click()"
                                    class="border-2 border-dashed border-gray-300 hover:border-amber-500 rounded-2xl p-4 bg-stone-50 hover:bg-white text-center cursor-pointer transition group">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                        <i data-lucide="image" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-[#0B2317] block">Pilih File Logo</span>
                                    <p class="text-[11px] text-gray-400 mt-0.5" id="logo-filename">PNG, JPG, maks. 2MB</p>
                                    <input id="logo" name="logo" type="file" class="hidden" required accept="image/*"
                                        onchange="document.getElementById('logo-filename').textContent = this.files[0] ? this.files[0].name : 'PNG, JPG, maks. 2MB'">
                                </div>
                            </div>

                            <!-- Bukti Transfer Dropzone -->
                            <div>
                                <label class="block text-xs font-black uppercase text-[#0B2317] tracking-wider mb-2">
                                    Bukti Dukungan / Transfer <span class="text-red-500">*</span>
                                </label>
                                <div onclick="document.getElementById('receipt').click()"
                                    class="border-2 border-dashed border-gray-300 hover:border-emerald-600 rounded-2xl p-4 bg-stone-50 hover:bg-white text-center cursor-pointer transition group">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-700 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                        <i data-lucide="receipt" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-[#0B2317] block">Upload Bukti TF / Surat</span>
                                    <p class="text-[11px] text-gray-400 mt-0.5" id="receipt-filename">PNG, JPG, PDF maks. 3MB</p>
                                    <input id="receipt" name="receipt" type="file" class="hidden" required accept="image/*,.pdf"
                                        onchange="document.getElementById('receipt-filename').textContent = this.files[0] ? this.files[0].name : 'PNG, JPG, PDF maks. 3MB'">
                                </div>
                            </div>

                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="w-full sm:w-auto flex-1 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-black py-4 px-6 rounded-2xl shadow-xl shadow-amber-900/30 flex items-center justify-center gap-2 border-2 border-amber-400 cursor-pointer transform hover:-translate-y-0.5 transition-all">
                                <i data-lucide="send" class="w-5 h-5 text-white flex-shrink-0"></i>
                                <span class="text-white text-sm sm:text-base font-black tracking-wide">Kirim Pengajuan Sponsorship</span>
                            </button>
                            <a href="{{ route('home') }}"
                                class="px-6 py-4 rounded-2xl font-bold text-xs sm:text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 text-center transition flex items-center justify-center">
                                Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush