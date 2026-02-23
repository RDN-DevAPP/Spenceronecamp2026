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
                @if(isset($isVisual) && $isVisual)
                    <!-- Visual Gallery View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($regu as $r)
                            @php
                                $existingScore = $existingScores->get($r->id);
                                $isScored = $existingScore !== null;
                                $photoPath = $r->getCompetitionPhoto($lomba->nama);
                            @endphp
                            
                            <!-- Visual Card -->
                            <div @click="openModal({{ $r->id }})" 
                               class="group relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col h-full transform hover:-translate-y-1 cursor-pointer">
                                
                                <div class="absolute top-0 left-0 w-full h-1.5 {{ $isScored ? 'bg-green-500' : 'bg-scout-accent' }} transition-colors z-10"></div>
                                
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    @if($photoPath)
                                        <img src="{{ asset('storage/' . $photoPath) }}" alt="Karya {{ $r->nama_regu }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="absolute inset-0 flex flex-col justify-center items-center text-gray-400">
                                            <i data-lucide="image-off" class="w-12 h-12 mb-2 opacity-50"></i>
                                            <span class="text-xs font-semibold uppercase tracking-wider">Belum Upload</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <span class="bg-scout-primary text-white font-bold py-2 px-4 rounded-full flex items-center shadow-lg">
                                            <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Nilai Sekarang
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-4 bg-white relative">
                                    @if($isScored)
                                        <div class="absolute -top-4 right-4 bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow border-2 border-white">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="font-bold text-gray-900 group-hover:text-scout-primary transition-colors line-clamp-1">
                                            {{ $r->nama_regu }}
                                        </h3>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500 justify-between">
                                        <span>Regu {{ $r->nomor_regu }} &bull; {{ ucfirst($r->jenis) }}</span>
                                        @if($isScored)
                                            <span class="font-bold text-scout-primary">Nilai: {{ $existingScore->nilai }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Modals (One for each Regu) -->
                    @foreach ($regu as $r)
                        @php
                            $existingScore = $existingScores->get($r->id);
                            $photoPath = $r->getCompetitionPhoto($lomba->nama);
                            $hasCriteria = $criteria && $criteria->isNotEmpty();
                        @endphp
                        
                        <div id="modal-{{ $r->id }}" style="display: none;" 
                             class="fixed inset-0 z-[100] items-center justify-center bg-black/90 p-2 sm:p-6 backdrop-blur-sm">
                             
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[95vh] sm:max-h-[90vh] flex flex-col lg:flex-row overflow-hidden relative">
                                
                                <button type="button" @click="closeModal({{ $r->id }})" class="absolute top-4 right-4 z-50 bg-white/50 hover:bg-white text-gray-900 rounded-full p-2 backdrop-blur shadow-sm transition-colors border border-gray-200">
                                    <i data-lucide="x" class="w-6 h-6"></i>
                                </button>
                                
                                <!-- Left side: Photo Preview -->
                                <div class="w-full lg:w-3/5 bg-gray-900 border-r border-gray-200 flex flex-col relative h-[40vh] lg:h-auto overflow-hidden">
                                    @if($photoPath)
                                        <div class="flex-1 w-full h-full relative group">
                                            <img src="{{ asset('storage/' . $photoPath) }}" class="w-full h-full object-contain">
                                            <div class="absolute bottom-4 right-4">
                                                <a href="{{ asset('storage/' . $photoPath) }}" target="_blank" class="bg-black/50 hover:bg-black/80 text-white p-2 rounded-lg backdrop-blur flex items-center text-sm font-semibold transition">
                                                    <i data-lucide="external-link" class="w-4 h-4 mr-2"></i> Buka Tab Baru
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                                            <i data-lucide="image-off" class="w-16 h-16 mb-4 opacity-50 text-gray-400"></i>
                                            <p class="font-semibold text-gray-400">Peserta belum mengunggah foto</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Right side: Form Penilaian -->
                                <div class="w-full lg:w-2/5 flex flex-col bg-gray-50 h-[55vh] lg:h-auto overflow-y-auto">
                                    <div class="p-6 border-b border-gray-200 bg-white sticky top-0 z-10 shadow-sm">
                                        <h2 class="text-xl font-bold text-scout-primary">{{ $r->nama_regu }}</h2>
                                        <p class="text-sm text-gray-500">Regu {{ $r->nomor_regu }} ({{ ucfirst($r->jenis) }})</p>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('juri.scores.store') }}" class="p-6 flex-1 flex flex-col">
                                        @csrf
                                        <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">
                                        <input type="hidden" name="regu_profile_id" value="{{ $r->id }}">

                                        @if ($hasCriteria)
                                            <!-- Criteria Based Scoring -->
                                            <div class="mb-6 flex-1">
                                                @php
                                                    $existingCriteriaScores = [];
                                                    if ($existingScore && $existingScore->scoreDetails) {
                                                        foreach ($existingScore->scoreDetails as $detail) {
                                                            $existingCriteriaScores[$detail->scoring_criteria_id] = $detail->nilai;
                                                        }
                                                    }
                                                @endphp

                                                <div class="space-y-4">
                                                    @foreach ($criteria as $index => $criterion)
                                                        @php
                                                            $existingValue = $existingCriteriaScores[$criterion->id] ?? '';
                                                        @endphp
                                                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                                            <div class="flex justify-between items-start mb-3">
                                                                <label class="text-sm font-bold text-gray-900">
                                                                    {{ $criterion->urutan }}. {{ $criterion->nama_kriteria }}
                                                                </label>
                                                                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded text-gray-600">
                                                                    Max: {{ $criterion->nilai_max }}
                                                                </span>
                                                            </div>

                                                            <input type="hidden" name="criteria[{{ $index }}][criteria_id]" value="{{ $criterion->id }}">

                                                            <div class="relative">
                                                                <div class="grid grid-cols-5 gap-2">
                                                                    @for($i = $criterion->nilai_min; $i <= $criterion->nilai_max; $i++)
                                                                        <button type="button"
                                                                            class="w-full h-10 sm:h-12 rounded-lg border-2 font-bold text-sm sm:text-base transition-all duration-200
                                                                                {{ (old('criteria.' . $index . '.nilai', $existingValue) == $i && $existingValue !== '') ? 'bg-scout-primary border-scout-primary text-white shadow-md transform scale-105' : 'bg-gray-50 border-gray-200 text-gray-700 hover:border-scout-primary/40' }}"
                                                                            @click="selectScore($event.currentTarget, 'criteria-{{ $r->id }}-{{ $index }}', {{ $i }}, {{ $r->id }})">
                                                                            {{ $i }}
                                                                        </button>
                                                                    @endfor
                                                                </div>
                                                                <input type="hidden" id="criteria-{{ $r->id }}-{{ $index }}" name="criteria[{{ $index }}][nilai]"
                                                                    value="{{ old('criteria.' . $index . '.nilai', $existingValue) }}" class="criteria-input-{{ $r->id }}"
                                                                    @change="calculateTotal({{ $r->id }})">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <div class="bg-gray-100 p-4 rounded-lg mb-6 flex justify-between items-center border border-gray-200">
                                                <span class="font-bold text-gray-700">Total Skor:</span>
                                                <span class="text-2xl font-bold text-scout-primary" id="total-preview-{{ $r->id }}">{{ $existingScore ? $existingScore->nilai : '0.00' }}</span>
                                            </div>
                                            
                                        @else
                                            <!-- Simple Scoring inside Modal -->
                                            <div class="mb-6 flex-1">
                                                <label class="block text-sm font-bold text-gray-800 mb-2">Nilai Akhir (0-100)</label>
                                                <input type="number" name="nilai" min="0" max="100" step="0.01"
                                                    value="{{ old('nilai', $existingScore?->nilai) }}" placeholder="0.00"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary focus:border-scout-primary text-xl font-bold text-scout-primary">
                                            </div>
                                        @endif

                                        <div class="mb-6">
                                            <label class="block text-sm font-bold text-gray-800 mb-2">Catatan</label>
                                            <textarea name="catatan" rows="2" placeholder="Tulis catatan (opsional)..."
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary text-sm">{{ old('catatan', $existingScore?->catatan) }}</textarea>
                                        </div>

                                        <div class="mt-auto pt-4 border-t border-gray-200">
                                            <button type="submit" class="w-full py-4 bg-scout-primary text-white rounded-xl font-bold shadow-lg hover:bg-scout-primary/90 transition flex items-center justify-center text-lg">
                                                <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                                                Simpan Penilaian
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                @else
                    <!-- STANDARD SCORING GRID (Non Visual) -->
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
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.pageData = function() {
            return {
                openModal(id) {
                    const modal = document.getElementById('modal-' + id);
                    if (modal) {
                        modal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    }
                },
                closeModal(id) {
                    const modal = document.getElementById('modal-' + id);
                    if (modal) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                },
                calculateTotal(reguId) {
                    let total = 0;
                    const inputs = document.querySelectorAll('.criteria-input-' + reguId);
                    inputs.forEach(input => {
                        total += parseFloat(input.value) || 0;
                    });
                    const previewElement = document.getElementById('total-preview-' + reguId);
                    if (previewElement) {
                        previewElement.textContent = total.toFixed(2);
                    }
                },
                selectScore(btn, inputId, value, reguId) {
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.value = value;
                        // Dispatch event so calculateTotal triggers or anything listening
                        input.dispatchEvent(new Event('change'));
                    }

                    const container = btn.closest('.grid');
                    if (container) {
                        const buttons = container.querySelectorAll('button');
                        buttons.forEach(b => {
                            b.classList.remove('bg-scout-primary', 'border-scout-primary', 'text-white', 'shadow-md', 'transform', 'scale-105');
                            b.classList.add('bg-gray-50', 'border-gray-200', 'text-gray-700', 'hover:border-scout-primary/40');
                        });
                    }

                    btn.classList.remove('bg-gray-50', 'border-gray-200', 'text-gray-700', 'hover:border-scout-primary/40');
                    btn.classList.add('bg-scout-primary', 'border-scout-primary', 'text-white', 'shadow-md', 'transform', 'scale-105');

                    this.calculateTotal(reguId);
                },
                onMounted() {
                    // Initialize totals for prepopulated fields
                    @if(isset($isVisual) && $isVisual)
                        @foreach($regu as $r)
                            this.calculateTotal({{ $r->id }});
                        @endforeach
                    @endif
                }
            };
        };
    </script>
@endpush
