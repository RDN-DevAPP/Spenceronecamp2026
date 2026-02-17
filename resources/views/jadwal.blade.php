@extends('layouts.main')

@section('title', 'Jadwal Kegiatan - LT-I Spencerone Camp 2026')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-12 sm:py-16">
        <!-- Header -->
        <div class="text-center mb-10 sm:mb-14">
            <div class="inline-flex items-center justify-center p-3 bg-scout-primary/10 rounded-2xl mb-4">
                <i data-lucide="calendar" class="w-10 h-10 sm:w-12 sm:h-12 text-scout-primary"></i>
            </div>
            <h1
                class="text-4xl sm:text-5xl font-extrabold mb-4 bg-gradient-to-r from-scout-primary to-scout-accent bg-clip-text text-transparent">
                Jadwal Kegiatan
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 font-medium max-w-2xl mx-auto px-4">
                LT-I Spencerone Camp 2026
            </p>
            <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-500">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                <span>Lingkungan SMP Negeri 1 Cerbon</span>
            </div>
        </div>

        <!-- Schedule Card -->
        <div class="card-scout rounded-3xl overflow-hidden shadow-xl p-6 sm:p-10 border-2 border-scout-secondary/20">
            <!-- Info Tanggal & Tempat -->
            <div class="grid md:grid-cols-2 gap-5 sm:gap-6 mb-8 sm:mb-10">
                <div
                    class="flex items-start p-5 sm:p-6 bg-gradient-to-br from-scout-primary/5 to-scout-primary/10 rounded-2xl border-2 border-scout-primary/20 hover:border-scout-primary/40 transition-all duration-300 hover:shadow-lg">
                    <div class="p-3 sm:p-4 bg-scout-primary rounded-xl mr-4 sm:mr-5 shadow-md">
                        <i data-lucide="calendar-days" class="w-6 h-6 sm:w-7 sm:h-7 text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg sm:text-xl mb-2 text-scout-primary">Tanggal</h3>
                        <p class="text-base sm:text-lg text-gray-700 font-medium">24 - 25 April 2026</p>
                        <p class="text-sm text-gray-500 mt-1">2 Hari Kegiatan</p>
                    </div>
                </div>
                <div
                    class="flex items-start p-5 sm:p-6 bg-gradient-to-br from-scout-accent/5 to-scout-accent/10 rounded-2xl border-2 border-scout-accent/30 hover:border-scout-accent/50 transition-all duration-300 hover:shadow-lg">
                    <div class="p-3 sm:p-4 bg-scout-accent rounded-xl mr-4 sm:mr-5 shadow-md">
                        <i data-lucide="map-pin" class="w-6 h-6 sm:w-7 sm:h-7 text-scout-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg sm:text-xl mb-2 text-scout-primary">Tempat</h3>
                        <p class="text-base sm:text-lg text-gray-700 font-medium">Lingkungan SMP N 1 Cerbon</p>
                        <p class="text-sm text-gray-500 mt-1">Bumi Perkemahan</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="space-y-8 sm:space-y-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-scout-primary/10 rounded-lg">
                        <i data-lucide="clock" class="w-6 h-6 sm:w-7 sm:h-7 text-scout-primary"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-scout-primary">
                        Rundown Acara
                    </h3>
                </div>

                <!-- Hari 1 -->
                <div class="border-l-4 border-scout-primary pl-4 sm:pl-6 pb-4 sm:pb-6">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div
                            class="bg-scout-primary text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-full font-bold text-xs sm:text-sm shadow-md">
                            Hari 1 - Jumat, 24 April 2026
                        </div>
                    </div>
                    <div class="divide-y-2 divide-gray-300">
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                08.00 - 11.00</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Mendirikan Tenda</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                11.00 - 13.30</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Isoma</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                13.30 - 14.30</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Upacara Pembukaan</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                14.30 - 17.00</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Lomba Baris-Berbaris Tongkat</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                17.00 - 17.15</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Apel Sore</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hari 2 -->
                <div class="border-l-4 border-scout-accent pl-4 sm:pl-6 pb-4 sm:pb-6">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div
                            class="bg-scout-accent text-scout-primary px-3 py-1.5 sm:px-4 sm:py-2 rounded-full font-bold text-xs sm:text-sm shadow-md">
                            Hari 2 - Sabtu, 25 April 2026
                        </div>
                    </div>
                    <div class="divide-y-2 divide-gray-300">
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                07.45 - 08.00</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Apel Pagi</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                08.00 - 11.00</div>
                            <div class="flex-1">
                                <ul class="list-disc list-inside text-sm sm:text-base font-medium space-y-1">
                                    <li>Pengumpulan Karya Upcycle Art</li>
                                    <li>Pengumpulan Poster Digital</li>
                                    <li>Lomba Cerdas - Cermat</li>
                                    <li>Giat Materi</li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                11.00 - 12.30</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Lomba Masak</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                12.30 - 13.30</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Isoma</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                13.30 - 14.30</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Giat Materi</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                14.30 - 16.00</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Anjangsana</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                16.00 - 16.30</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Upacara Penutupan</p>
                            </div>
                        </div>
                        <div class="flex items-start py-3 sm:py-4">
                            <div
                                class="min-w-[100px] sm:min-w-[130px] font-semibold text-scout-primary text-sm sm:text-base">
                                16.30 - 17.00</div>
                            <div class="flex-1">
                                <p class="font-medium text-sm sm:text-base">Operasi Semut</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection