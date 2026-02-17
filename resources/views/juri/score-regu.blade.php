@extends('layouts.main')

@section('title', 'Nilai ' . $regu->nama_regu . ' - ' . $lomba->nama)

@section('content')
    <div class="py-8 sm:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('juri.lomba.score', $lomba->slug) }}"
                    class="inline-flex items-center text-scout-primary hover:text-scout-primary/80 font-semibold transition">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Kembali ke Daftar Regu
                </a>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg run-in-animation mb-6 overflow-hidden">
                <div class="bg-white border-b border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <i data-lucide="award" class="w-24 h-24 text-scout-primary"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold mb-2 text-scout-primary">{{ $regu->nama_regu }}</h1>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span
                                        class="px-3 py-1 text-sm font-semibold rounded-full {{ $regu->jenis === 'putra' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ ucfirst($regu->jenis) }}
                                    </span>
                                    <span
                                        class="px-3 py-1 text-sm font-semibold rounded-full bg-scout-accent text-white shadow-sm">
                                        Regu {{ $regu->nomor_regu }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Team Photo Display (Inside Header) -->
                        @php
                            $photoPath = $regu->getCompetitionPhoto($lomba->nama);
                        @endphp
                        
                        @if(!in_array($lomba->nama, ['Cerdas Cermat', 'LKBB Tongkat']))
                            <div class="mt-4 rounded-xl overflow-hidden shadow-sm border border-gray-200">
                                @if($photoPath)
                                    <div class="aspect-video w-full bg-gray-50 relative group">
                                        <img src="{{ asset('storage/' . $photoPath) }}" 
                                             alt="Foto Karya {{ $regu->nama_regu }}" 
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <a href="{{ asset('storage/' . $photoPath) }}" target="_blank" class="text-white bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full font-semibold hover:bg-white/30 transition">
                                                <i data-lucide="zoom-in" class="w-5 h-5 inline-block mr-2"></i>
                                                Lihat Ukuran Penuh
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="aspect-video w-full bg-gray-50 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-200">
                                        <i data-lucide="image-off" class="w-12 h-12 mb-2 opacity-50"></i>
                                        <span class="text-sm font-medium">Belum ada foto yang diunggah</span>
                                        <span class="text-xs">Regu belum mengupload foto untuk mata lomba ini</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex items-start">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
                        <div>
                            <p class="text-red-700 font-medium mb-2">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside text-red-600 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif



            <!-- Scoring Form -->
            <form method="POST" action="{{ route('juri.scores.store') }}" class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
                @csrf
                <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">
                <input type="hidden" name="regu_profile_id" value="{{ $regu->id }}">

                @if ($hasCriteria)
                    <!-- Criteria Based Scoring -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4 border-b pb-2">
                            <h2 class="text-lg font-bold text-gray-800">Kriteria Penilaian</h2>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Isi nilai sesuai range</span>
                        </div>

                        @php
                            $existingCriteriaScores = [];
                            if ($existingScore && $existingScore->scoreDetails) {
                                foreach ($existingScore->scoreDetails as $detail) {
                                    $existingCriteriaScores[$detail->scoring_criteria_id] = $detail->nilai;
                                }
                            }
                        @endphp

                        <div class="space-y-3 sm:space-y-4">
                            @foreach ($criteria as $index => $criterion)
                                @php
                                    $existingValue = $existingCriteriaScores[$criterion->id] ?? '';
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <label class="text-sm sm:text-base font-semibold text-gray-900 flex-1 mr-2">
                                            <span class="text-scout-primary mr-1">{{ $criterion->urutan }}.</span>
                                            {{ $criterion->nama_kriteria }}
                                        </label>
                                        <span
                                            class="text-[10px] sm:text-xs font-medium px-1.5 py-0.5 bg-white border border-gray-200 rounded text-gray-500 whitespace-nowrap">
                                            Max: {{ $criterion->nilai_max }}
                                        </span>
                                    </div>

                                    <input type="hidden" name="criteria[{{ $index }}][criteria_id]" value="{{ $criterion->id }}">

                                    <div class="relative">
                                        {{-- Selection Buttons for all ranges --}}
                                        <div class="grid grid-cols-5 gap-2 sm:gap-3">
                                            @for($i = $criterion->nilai_min; $i <= $criterion->nilai_max; $i++)
                                                <button type="button"
                                                    class="score-btn w-full h-12 sm:h-14 rounded-lg border-2 font-bold text-base sm:text-lg transition-all duration-200 flex items-center justify-center touch-manipulation
                                                                    {{ (old('criteria.' . $index . '.nilai', $existingValue) == $i && $existingValue !== '') ? 'bg-scout-primary border-scout-primary text-white shadow-md transform scale-105' : 'bg-white border-gray-200 text-gray-700 hover:border-scout-primary/50' }}"
                                                    onclick="selectScore(this, 'criteria-{{ $index }}', {{ $i }})">
                                                    {{ $i }}
                                                </button>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="criteria-{{ $index }}" name="criteria[{{ $index }}][nilai]"
                                            value="{{ old('criteria.' . $index . '.nilai', $existingValue) }}" class="criteria-input"
                                            onchange="calculateTotal()">
                                    </div>
                                    <div class="mt-1 text-right">
                                        <span class="text-[10px] text-gray-400">Range: {{ $criterion->nilai_min }} -
                                            {{ $criterion->nilai_max }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Preview (Sticky Bottom for Mobile) -->
                        <div
                            class="sticky bottom-0 z-20 mt-6 -mx-6 -mb-6 sm:mx-0 sm:mb-0 bg-white border-t border-gray-200 p-4 sm:rounded-xl sm:border sm:shadow-lg sm:static">
                            <div class="flex items-center justify-between">
                                <span class="text-sm sm:text-lg font-bold text-gray-600">Total Skor</span>
                                <span class="text-2xl sm:text-3xl font-bold text-scout-primary" id="total-preview">0.00</span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Simple Scoring -->
                    <div class="mb-8">
                        <label class="block text-lg font-bold text-gray-800 mb-2">Nilai Akhir</label>
                        <p class="text-gray-500 mb-4 text-sm">Berikan nilai antara 0 - 100.</p>

                        <div class="relative max-w-xs">
                            <input type="number" name="nilai" min="0" max="100" step="0.01"
                                value="{{ old('nilai', $existingScore?->nilai) }}" placeholder="0.00"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary focus:border-scout-primary text-2xl font-bold text-scout-primary">
                        </div>
                    </div>
                @endif

                <!-- Catatan -->
                <div class="mb-8 mt-6">
                    <label class="block text-base sm:text-lg font-bold text-gray-800 mb-2">Catatan Juri (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Catatan..."
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary focus:border-scout-primary text-sm sm:text-base">{{ old('catatan', $existingScore?->catatan) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-100 items-center justify-between">
                    <div class="flex gap-2 w-full sm:w-auto">
                        @if($prevReguId)
                            <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $prevReguId]) }}"
                                class="px-5 py-3 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition text-center flex-1 sm:flex-none">
                                &larr; Sebelumnya
                            </a>
                        @endif
                    </div>

                    <div class="flex gap-3 w-full sm:w-auto">
                        <a href="{{ route('juri.lomba.score', $lomba->slug) }}"
                            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition w-full sm:w-auto text-center">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-scout-primary text-white rounded-lg font-bold shadow-lg hover:bg-scout-primary/90 transition w-full sm:w-auto flex items-center justify-center">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            Simpan Nilai
                        </button>
                    </div>

                    <div class="flex gap-2 w-full sm:w-auto justify-end">
                        @if($nextReguId)
                            <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $nextReguId]) }}"
                                class="px-5 py-3 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition text-center flex-1 sm:flex-none">
                                Selanjutnya &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function calculateTotal() {
            const inputs = document.querySelectorAll('.criteria-input');
            let total = 0;
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-preview').textContent = total.toFixed(2);
        }

        function selectScore(btn, inputId, value) {
            // Update hidden input
            const input = document.getElementById(inputId);
            input.value = value;

            // Update UI
            // Remove active class from all buttons in this group
            const container = btn.closest('.grid');
            const buttons = container.querySelectorAll('button');
            buttons.forEach(b => {
                b.classList.remove('bg-scout-primary', 'border-scout-primary', 'text-white', 'shadow-md');
                b.classList.add('bg-white', 'border-gray-200', 'text-gray-700', 'hover:border-scout-primary/50');
            });

            // Add active class to clicked button
            btn.classList.remove('bg-white', 'border-gray-200', 'text-gray-700', 'hover:border-scout-primary/50');
            btn.classList.add('bg-scout-primary', 'border-scout-primary', 'text-white', 'shadow-md');

            // Recalculate total
            calculateTotal();
        }

        document.addEventListener('DOMContentLoaded', () => {
            calculateTotal();
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush