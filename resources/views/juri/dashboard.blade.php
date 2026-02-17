@extends('layouts.main')

@section('title', 'Dashboard Juri - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-8 sm:py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-scout-primary mb-2">Dashboard Juri</h1>
                <p class="text-sm sm:text-base text-gray-600">Pilih mata lomba untuk memberikan penilaian</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md max-w-3xl mx-auto">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Competition Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($mataLomba as $lomba)
                    @php
                        $stats = $scoringStats[$lomba->id];
                        $isComplete = $stats['scored'] === $stats['total'] && $stats['total'] > 0;
                        $isPartial = $stats['scored'] > 0 && $stats['scored'] < $stats['total'];
                    @endphp
                    <div class="card-scout rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="bg-scout-primary text-white p-4 sm:p-5 border-b-4 border-scout-accent relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-10 transform translate-x-2 -translate-y-2">
                                <i data-lucide="award" class="w-16 h-16"></i>
                            </div>
                            <div class="flex items-start justify-between mb-2 relative z-10">
                                <div class="flex items-center flex-1">
                                    <i data-lucide="trophy" class="w-6 h-6 mr-3 text-scout-accent flex-shrink-0"></i>
                                    <h3 class="text-base sm:text-lg font-bold leading-tight text-white">{{ $lomba->nama }}</h3>
                                </div>
                                <span class="text-xs bg-scout-accent text-white px-2 py-1 rounded-full font-bold ml-2 shadow-sm">
                                    #{{ $lomba->urutan }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 sm:p-5 bg-white">
                            <!-- Progress Stats -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Progress Penilaian</span>
                                    <span class="text-sm font-bold text-scout-primary">{{ $stats['percentage'] }}%</span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $isComplete ? 'bg-green-500' : ($isPartial ? 'bg-yellow-500' : 'bg-gray-400') }}" 
                                         style="width: {{ $stats['percentage'] }}%"></div>
                                </div>
                                
                                <div class="mt-2 text-xs text-gray-600">
                                    <span class="font-semibold">{{ $stats['scored'] }}</span> dari 
                                    <span class="font-semibold">{{ $stats['total'] }}</span> regu dinilai
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                @if ($isComplete)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i>
                                        Selesai
                                    </span>
                                @elseif ($isPartial)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <i data-lucide="clock" class="w-4 h-4 mr-1.5"></i>
                                        Dalam Progress
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        <i data-lucide="circle" class="w-4 h-4 mr-1.5"></i>
                                        Belum Dimulai
                                    </span>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('juri.lomba.score', $lomba->slug) }}" 
                               class="block w-full btn-scout-primary text-center px-4 py-3 rounded-lg font-bold shadow-md hover:shadow-lg transition-all duration-200">
                                <span class="flex items-center justify-center">
                                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                    {{ $isComplete ? 'Lihat / Edit Nilai' : ($isPartial ? 'Lanjutkan Penilaian' : 'Mulai Penilaian') }}
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($mataLomba->isEmpty())
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada mata lomba yang tersedia.</p>
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
