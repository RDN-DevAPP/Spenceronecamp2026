@extends('layouts.main')

@section('title', 'Penilaian ' . $lomba->nama . ' - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-8 sm:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('juri.dashboard') }}" 
                   class="inline-flex items-center text-scout-primary hover:text-scout-primary/80 font-semibold transition">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Header -->
            <div class="mb-8 bg-scout-primary rounded-2xl shadow-xl overflow-hidden relative border border-scout-primary/10">
                 <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i data-lucide="trophy" class="w-32 h-32 text-white"></i>
                </div>
                <div class="px-6 py-8 sm:p-10 relative z-10 text-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center mb-3">
                                <div class="bg-white/10 p-3 rounded-lg mr-4 backdrop-blur-sm">
                                    <i data-lucide="trophy" class="w-10 h-10 text-scout-accent"></i>
                                </div>
                                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-scout-accent">{{ $lomba->nama }}</h1>
                            </div>
                            <p class="text-scout-cream text-lg max-w-2xl ml-20">
                                Pilih regu di bawah ini untuk mulai memberikan penilaian.
                            </p>
                        </div>
                        <div class="bg-white/10 rounded-xl p-4 border border-white/10 text-center md:text-right min-w-[150px] backdrop-blur-sm">
                            <div class="text-sm text-scout-accent mb-1 font-semibold uppercase tracking-wider">Total Regu</div>
                            <div class="text-4xl font-bold text-white">{{ $regu->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm animate-fade-in-down">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Team List (Grid) -->
            @if ($regu->isEmpty())
                <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada peserta</h3>
                    <p class="text-gray-500">Belum ada regu yang terdaftar untuk mata lomba ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($regu as $r)
                        @php
                            $existingScore = $existingScores->get($r->id);
                            $isScored = $existingScore !== null;
                        @endphp
                        <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $r->id]) }}" 
                           class="group relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                            
                            <!-- Status Indicator Strip -->
                            <div class="absolute top-0 left-0 w-1.5 h-full {{ $isScored ? 'bg-green-500' : 'bg-gray-200 group-hover:bg-scout-accent' }} transition-colors"></div>
                            
                            <div class="p-5 sm:p-6 flex-1 flex flex-col pl-7 sm:pl-8">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $r->jenis === 'putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                            {{ ucfirst($r->jenis) }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-scout-surface text-scout-primary border border-scout-accent/20">
                                            Regu {{ $r->nomor_regu }}
                                        </span>
                                    </div>
                                    @if($isScored)
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                                    @else
                                        <i data-lucide="circle" class="w-5 h-5 text-gray-300"></i>
                                    @endif
                                </div>

                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-1 group-hover:text-scout-primary transition-colors line-clamp-1">
                                    {{ $r->nama_regu }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $r->user->name ?? 'Pembina' }}</p>

                                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        @if($isScored)
                                            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Total Nilai</div>
                                            <div class="text-xl font-bold text-scout-primary">{{ $existingScore->nilai }}</div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum dinilai - Klik untuk menilai</span>
                                        @endif
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-scout-primary group-hover:text-white transition-colors">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Reinitialize Lucide icons after page load
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
