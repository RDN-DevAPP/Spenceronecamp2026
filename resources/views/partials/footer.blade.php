<footer class="bg-brand-forest text-white border-t-4 border-brand-amber relative overflow-hidden">
    <!-- Background SVG Pattern -->
    <div class="absolute inset-0 opacity-10 pointer-events-none"
        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.386 7.9l-7.9-7.9h2.828zM16.686 0L10.2 6.485 11.614 7.9l7.9-7.9h-2.828zM37.657 0l7.9 7.9-1.414 1.414L34.83 0h2.828zM22.343 0l-7.9 7.9 1.414 1.414L25.17 0h-2.828zM32 0l1.414 1.414L30.586 4.243 29.172 2.828 30.586 1.414 32 0zM28 0l-1.414 1.414 2.828 2.828 1.414-1.414L29.414 1.414 28 0z\' fill=\'%23D97706\' fill-opacity=\'0.3\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');">
    </div>

    <div class="max-w-7xl mx-auto py-10 sm:py-14 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 pb-8 border-b border-white/15">
            
            <!-- Brand Column -->
            <div class="space-y-3 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3">
                    <img src="{{ asset('images/season2/logo-season2.jpg') }}" alt="Spencerone Camp Season II Logo"
                        class="w-12 h-12 rounded-full object-cover shadow-lg border-2 border-amber-400 ring-2 ring-brand-forest">
                    <div>
                        <span class="font-black text-lg text-white block leading-tight">Spencerone Camp <span class="text-amber-400">II</span></span>
                        <span class="text-xs text-amber-200 font-bold">Gudep 03.059 - 03.060 SMPN 1 Cerbon</span>
                    </div>
                </div>
                <p class="text-xs text-white/90 leading-relaxed">
                    Sistem Informasi & Penilaian Lomba Resmi Gerakan Pramuka Pangkalan SMP Negeri 1 Cerbon, Kabupaten Barito Kuala, Kalimantan Selatan.
                </p>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/25 text-[11px] font-bold text-amber-300 border border-amber-400/40">
                    <i data-lucide="compass" class="w-3.5 h-3.5"></i>
                    <span>{{ str_replace(', ', ' • ', $appSlogan ?? 'Tangkas di Alam • Adaptif di Zaman • Unggul Berkarakter') }}</span>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="text-center md:text-left">
                <h4 class="text-xs font-black uppercase tracking-wider text-amber-300 mb-3">Tautan Cepat</h4>
                <ul class="space-y-2 text-xs font-semibold text-white/90">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-300 transition">Beranda Utama</a></li>
                    <li><a href="{{ route('informasi-lomba') }}" class="hover:text-amber-300 transition">Katalog & Juknis Lomba</a></li>
                    <li><a href="{{ route('jadwal') }}" class="hover:text-amber-300 transition">Rundown Jadwal Kegiatan</a></li>
                    <li><a href="{{ route('daftar-regu') }}" class="hover:text-amber-300 transition">Daftar Regu Putra & Putri</a></li>
                    <li><a href="{{ route('sponsorship.daftar') }}" class="hover:text-amber-300 transition">Pendaftaran Sponsorship</a></li>
                </ul>
            </div>

            <!-- Social & Location -->
            <div class="text-center md:text-right space-y-3">
                <h4 class="text-xs font-black uppercase tracking-wider text-amber-300 mb-2">Pangkalan & Kontak</h4>
                <p class="text-xs text-white/90 leading-relaxed">
                    SMP Negeri 1 Cerbon<br>
                    Kecamatan Cerbon, Kabupaten Barito Kuala<br>
                    Provinsi Kalimantan Selatan
                </p>
                <div class="flex justify-center md:justify-end gap-3 pt-2">
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-amber text-white flex items-center justify-center transition transform hover:scale-110">
                        <i data-lucide="instagram" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-amber text-white flex items-center justify-center transition transform hover:scale-110">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand-amber text-white flex items-center justify-center transition transform hover:scale-110">
                        <i data-lucide="globe" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-white/85 font-medium">
            <p>&copy; 2026 Gudep 03.059-03.060 SMPN 1 Cerbon. Spencerone Camp Season II.</p>
            <p class="text-amber-300">Platform Penilaian Terintegrasi Multi-Event</p>
        </div>
    </div>
</footer>