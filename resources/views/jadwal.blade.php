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
            <div class="mt-4 flex items-center justify-center gap-2 text-sm text-gray-500 font-semibold italic">
                <i data-lucide="map-pin" class="w-4 h-4 text-scout-accent"></i>
                <span>{{ $eventSettings['event_location'] ?? 'Lingkungan SMP Negeri 1 Cerbon - Bumi Perkemahan' }}</span>
            </div>
        </div>

        @if($jadwals->isEmpty())
            <div class="card-scout rounded-3xl p-16 text-center shadow-xl border-2 border-scout-secondary/20">
                <div class="mb-6 flex justify-center">
                    <div class="p-6 bg-scout-primary/5 rounded-full border-2 border-scout-secondary/30">
                        <i data-lucide="calendar-x" class="w-16 h-16 text-scout-primary/20"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-scout-primary mb-3">Jadwal Belum Tersedia</h3>
                <p class="text-gray-500 font-medium max-w-md mx-auto">Informasi rundown kegiatan sedang disiapkan oleh panitia.
                    Mohon cek kembali secara berkala.</p>
            </div>
        @else
            <!-- Schedule Card -->
            <div
                class="card-scout rounded-3xl overflow-hidden shadow-xl p-6 sm:p-10 border-2 border-scout-secondary/20 bg-white">

                <!-- Main Timeline Container -->
                <div class="space-y-16">
                    @foreach($jadwals as $tanggal => $items)
                        <div class="relative">
                            <!-- Section Title -->
                            <div class="flex items-center gap-4 mb-10">
                                <div
                                    class="flex-shrink-0 w-14 h-14 bg-scout-primary rounded-2xl flex items-center justify-center shadow-lg shadow-scout-primary/30 text-white transform -rotate-3 group-hover:rotate-0 transition-transform">
                                    <i data-lucide="{{ $loop->first ? 'flag' : 'star' }}" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <h3
                                        class="text-2xl sm:text-3xl font-black text-scout-primary uppercase italic tracking-tighter">
                                        Hari {{ $items->first()->hari_ke }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-scout-primary/60 font-bold text-sm">
                                        <i data-lucide="calendar-days" class="w-4 h-4"></i>
                                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                                    </div>
                                </div>
                                <div class="hidden sm:block flex-1 border-b-4 border-scout-secondary/30 border-dashed ml-4"></div>
                            </div>

                            <div class="relative">
                                <!-- Vertical Line -->
                                <div class="absolute left-[26px] top-0 bottom-0 w-1 bg-scout-primary/10 rounded-full"></div>

                                <!-- Items -->
                                <div class="space-y-8 relative">
                                    @foreach($items as $j)
                                        <div class="flex items-start group">
                                            <!-- Dot -->
                                            <div class="relative z-10 flex-shrink-0 w-14 h-14 flex items-center justify-center">
                                                <div
                                                    class="w-4 h-4 rounded-full bg-white border-4 border-scout-accent shadow-sm group-hover:scale-150 group-hover:bg-scout-accent transition-all duration-300">
                                                </div>
                                            </div>

                                            <div class="flex-1 ml-4 sm:ml-8 -mt-0.5">
                                                <div
                                                    class="bg-scout-surface/50 p-6 rounded-2xl border border-scout-secondary/30 group-hover:border-scout-primary/30 group-hover:bg-scout-surface transition-all duration-300 transform group-hover:translate-x-1 shadow-sm">
                                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                                        <div
                                                            class="inline-flex items-center gap-2 bg-scout-primary/10 px-4 py-1.5 rounded-full text-scout-primary font-bold text-xs">
                                                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                                            {{ substr($j->waktu_mulai, 0, 5) }}
                                                            @if($j->waktu_selesai)
                                                                - {{ substr($j->waktu_selesai, 0, 5) }}
                                                            @else
                                                                - Selesai
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <h4
                                                        class="text-lg sm:text-xl font-black text-scout-primary leading-tight group-hover:text-scout-primary group-transition duration-300">
                                                        {{ $j->kegiatan }}
                                                    </h4>
                                                    @if($j->lokasi)
                                                        <div class="mt-1 flex items-center gap-1.5 text-xs font-bold text-scout-primary/50 italic">
                                                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                                            {{ $j->lokasi }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div
                    class="mt-16 pt-8 border-t-4 border-scout-secondary/20 border-dotted flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-scout-accent/10 flex items-center justify-center text-scout-primary">
                            <i data-lucide="help-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-scout-primary italic leading-tight">Jadwal sewaktu-waktu dapat
                                berubah sesuai instruksi Panitia di lapangan.</p>
                            <span
                                class="text-xs text-scout-primary/50 font-bold uppercase tracking-widest mt-1 inline-block">Spencerone
                                Camp Management</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection