@extends('layouts.main')

@section('title', 'Penilaian ' . $lomba->nama . ' - LT-I Spencerone Camp 2026')

@section('content')
    <div class="py-8 sm:py-12 min-h-screen">
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
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div class="bg-white/10 rounded-xl p-4 border border-white/10 text-center md:text-right min-w-[150px] backdrop-blur-sm">
                                <div class="text-sm text-scout-accent mb-1 font-semibold uppercase tracking-wider">Total Regu</div>
                                <div class="text-4xl font-bold text-white">{{ $regu->count() }}</div>
                            </div>
                        </div>
                    </div>

                    @if(isset($isBulkCapable) && $isBulkCapable)
                        <!-- Mode Selector (Kartu vs Matriks Massal) -->
                        <div class="mt-6 pt-6 border-t border-white/10 flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase font-bold text-scout-accent tracking-wider">Mode Penilaian:</span>
                                <div class="inline-flex p-1 bg-black/20 rounded-xl border border-white/10">
                                    <button type="button" onclick="switchView('card')" id="btn-view-card"
                                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition bg-scout-accent text-white shadow-sm flex items-center gap-1.5">
                                        <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Tampilan Kartu
                                    </button>
                                    <button type="button" onclick="switchView('matrix')" id="btn-view-matrix"
                                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition text-white/80 hover:text-white flex items-center gap-1.5">
                                        <i data-lucide="table" class="w-3.5 h-3.5"></i> Matriks Massal (Bulk)
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-scout-cream/80">
                                <i data-lucide="zap" class="w-3.5 h-3.5 inline text-amber-400"></i> Mode Matriks Massal memungkinkan Anda mengisi skor seluruh regu dalam satu layar.
                            </p>
                        </div>
                    @endif
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

            @if(isset($isBulkCapable) && $isBulkCapable)
                <!-- BULK MATRIX SCORING SECTION -->
                <div id="bulk-matrix-container" class="hidden mb-12">
                    <form action="{{ route('juri.scores.storeBulk') }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        @csrf
                        <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">

                        <div class="p-5 sm:p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                                    <i data-lucide="table-2" class="w-5 h-5 text-scout-primary"></i>
                                    Tabel Penilaian Massal (Bulk Matrix Scoring)
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">Input nilai dan bobot kriteria seluruh regu secara langsung. Total skor dihitung otomatis.</p>
                            </div>
                            <button type="submit"
                                class="px-6 py-2.5 bg-scout-primary hover:bg-scout-primary/90 text-white text-xs font-black rounded-xl shadow-lg transition flex items-center gap-2 active:scale-95">
                                <i data-lucide="save" class="w-4 h-4 text-scout-accent"></i>
                                Simpan Semua Nilai Massal
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-scout-primary text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider w-16">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[180px]">Regu</th>
                                        @if($criteria->isNotEmpty())
                                            @foreach($criteria as $crit)
                                                <th class="px-3 py-3 text-center text-xs font-bold uppercase tracking-wider min-w-[110px]">
                                                    <div>{{ $crit->nama_kriteria }}</div>
                                                    <span class="text-[10px] text-amber-300 font-normal">Max: {{ $crit->nilai_max }}</span>
                                                </th>
                                            @endforeach
                                        @else
                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-32">Nilai (0-100)</th>
                                        @endif
                                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-28 bg-emerald-900">Total Skor</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider min-w-[160px]">Catatan Evaluasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($regu as $idx => $r)
                                        @php
                                            $existingScore = $existingScores->get($r->id);
                                            $critScores = [];
                                            if ($existingScore && $existingScore->scoreDetails) {
                                                foreach ($existingScore->scoreDetails as $d) {
                                                    $critScores[$d->scoring_criteria_id] = $d->nilai;
                                                }
                                            }
                                        @endphp
                                        <tr class="hover:bg-amber-50/40 transition-colors {{ $existingScore?->isLocked() ? 'bg-gray-50/70' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap text-xs font-bold text-gray-500 text-center">
                                                {{ $loop->iteration }}
                                                <input type="hidden" name="scores[{{ $idx }}][regu_profile_id]" value="{{ $r->id }}">
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                                    {{ strtoupper($r->nama_regu) }}
                                                    @if($existingScore?->isLocked())
                                                        <i data-lucide="lock" class="w-3.5 h-3.5 text-amber-600" title="Nilai Terkunci"></i>
                                                    @endif
                                                </div>
                                                <div class="text-[11px] text-gray-500">{{ ucfirst($r->jenis) }} &bull; Regu {{ $r->nomor_regu }}</div>
                                            </td>

                                            @if($criteria->isNotEmpty())
                                                @foreach($criteria as $cIdx => $crit)
                                                    @php $val = $critScores[$crit->id] ?? ''; @endphp
                                                    <td class="px-2 py-2 whitespace-nowrap text-center">
                                                        <input type="hidden" name="scores[{{ $idx }}][criteria][{{ $cIdx }}][criteria_id]" value="{{ $crit->id }}">
                                                        <input type="number" 
                                                            name="scores[{{ $idx }}][criteria][{{ $cIdx }}][nilai]" 
                                                            min="{{ $crit->nilai_min }}" 
                                                            max="{{ $crit->nilai_max }}" 
                                                            step="0.5" 
                                                            value="{{ old("scores.{$idx}.criteria.{$cIdx}.nilai", $val) }}"
                                                            placeholder="0"
                                                            {{ $existingScore?->isLocked() ? 'readonly disabled' : '' }}
                                                            class="bulk-crit-input w-20 px-2 py-1.5 text-center font-bold text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary focus:border-scout-primary {{ $existingScore?->isLocked() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                                            data-regu-index="{{ $idx }}"
                                                            oninput="updateBulkTotal({{ $idx }})">
                                                    </td>
                                                @endforeach
                                            @else
                                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                                    <input type="number" 
                                                        name="scores[{{ $idx }}][nilai]" 
                                                        min="0" 
                                                        max="100" 
                                                        step="0.5" 
                                                        value="{{ old("scores.{$idx}.nilai", $existingScore?->nilai) }}"
                                                        placeholder="0.00"
                                                        {{ $existingScore?->isLocked() ? 'readonly disabled' : '' }}
                                                        class="bulk-simple-input w-24 px-2 py-1.5 text-center font-bold text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-scout-primary focus:border-scout-primary {{ $existingScore?->isLocked() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                                        data-regu-index="{{ $idx }}"
                                                        oninput="updateBulkTotal({{ $idx }})">
                                                </td>
                                            @endif

                                            <td class="px-4 py-3 whitespace-nowrap text-center font-mono font-black text-base text-emerald-800 bg-emerald-50/50">
                                                <span id="bulk-total-{{ $idx }}">{{ number_format($existingScore?->nilai ?? 0, 2) }}</span>
                                            </td>

                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <input type="text" 
                                                    name="scores[{{ $idx }}][catatan]" 
                                                    value="{{ old("scores.{$idx}.catatan", $existingScore?->catatan) }}"
                                                    placeholder="Catatan..."
                                                    {{ $existingScore?->isLocked() ? 'readonly disabled' : '' }}
                                                    class="w-full text-xs px-2.5 py-1.5 border border-gray-200 rounded-lg focus:ring-1 focus:ring-scout-primary {{ $existingScore?->isLocked() ? 'bg-gray-100' : '' }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-scout-primary hover:bg-scout-primary/90 text-white text-xs font-black rounded-xl shadow-lg transition flex items-center gap-2 active:scale-95">
                                <i data-lucide="save" class="w-4 h-4 text-scout-accent"></i>
                                Simpan Semua Nilai Massal
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- CARDS VIEW WRAPPER -->
            <div id="cards-view-container">

            <!-- Team List (Grid) -->
            @if ($regu->isEmpty())
                <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada peserta</h3>
                    <p class="text-gray-500">Belum ada regu yang terdaftar untuk mata lomba ini.</p>
                </div>
            @else
                @if(isset($isVisual) && $isVisual)
                    <!-- Visual Gallery View (Redirects to score-regu for one-by-one) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($regu as $r)
                            @php
                                $existingScore = $existingScores->get($r->id);
                                $isScored = $existingScore !== null;
                                $photoPath = $r->getCompetitionPhoto($lomba->nama);
                            @endphp
                            
                            <!-- Visual Card -->
                            <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $r->id]) }}" 
                               class="group relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                                
                                <div class="absolute top-0 left-0 w-full h-1.5 {{ $isScored ? 'bg-green-500' : 'bg-scout-accent' }} transition-colors z-10"></div>
                                
                                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                    @if($photoPath)
                                        <img src="{{ asset('storage/' . $photoPath) }}" alt="Karya {{ strtoupper($r->nama_regu) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="absolute inset-0 flex flex-col justify-center items-center text-gray-400 bg-gray-50">
                                            <i data-lucide="lock" class="w-12 h-12 mb-2 text-amber-500 opacity-50"></i>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Belum Upload</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge Overlays -->
                                    <div class="absolute top-3 left-3 flex flex-col gap-2 z-20">
                                        @if($isScored)
                                            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm flex items-center">
                                                <i data-lucide="check" class="w-3 h-3 mr-1"></i> Dinilai
                                            </span>
                                        @elseif(empty($photoPath))
                                            <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm flex items-center">
                                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i> Menunggu Upload
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        @if(empty($photoPath))
                                            <span class="bg-amber-600 text-white font-bold py-2 px-4 rounded-full flex items-center shadow-lg border border-amber-400">
                                                <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Belum Bisa Dinilai
                                            </span>
                                        @else
                                            <span class="bg-scout-primary text-white font-bold py-2 px-4 rounded-full flex items-center shadow-lg">
                                                <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i> Nilai Sekarang
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="p-4 bg-white relative">
                                    @if($isScored)
                                        <div class="absolute -top-4 right-4 bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow border-2 border-white">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="font-bold text-gray-900 group-hover:text-scout-primary transition-colors line-clamp-1 text-sm sm:text-base">
                                            {{ strtoupper($r->nama_regu) }}
                                        </h3>
                                    </div>
                                    <div class="flex items-center text-[10px] sm:text-xs text-gray-500 justify-between">
                                        <span>B{{ $r->nomor_regu }} &bull; {{ ucfirst($r->jenis) }}</span>
                                        @if($isScored)
                                            <span class="font-bold text-scout-primary">Skor: {{ $existingScore->nilai }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
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
                                        {{ strtoupper($r->nama_regu) }}
                                    </h3>
                                    @if(strtoupper($r->user->name ?? '') !== strtoupper($r->nama_regu))
                                        <p class="text-xs sm:text-sm text-gray-500 mb-4 line-clamp-1">{{ $r->user->name ?? 'Pembina' }}</p>
                                    @endif
    
                                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                        <div>
                                            @if($isScored)
                                                <div class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Total Nilai</div>
                                                <div class="text-xl font-bold text-scout-primary">{{ $existingScore->nilai }}</div>
                                            @else
                                                <span class="text-[10px] sm:text-xs text-gray-400 italic">Klik untuk menilai</span>
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
            </div><!-- /#cards-view-container -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchView(mode) {
            const cardsContainer = document.getElementById('cards-view-container');
            const bulkContainer = document.getElementById('bulk-matrix-container');
            const btnCard = document.getElementById('btn-view-card');
            const btnMatrix = document.getElementById('btn-view-matrix');

            if (mode === 'matrix') {
                if (cardsContainer) cardsContainer.classList.add('hidden');
                if (bulkContainer) bulkContainer.classList.remove('hidden');

                if (btnCard) {
                    btnCard.classList.remove('bg-scout-accent', 'text-white', 'shadow-sm');
                    btnCard.classList.add('text-white/80');
                }
                if (btnMatrix) {
                    btnMatrix.classList.add('bg-scout-accent', 'text-white', 'shadow-sm');
                    btnMatrix.classList.remove('text-white/80');
                }
            } else {
                if (cardsContainer) cardsContainer.classList.remove('hidden');
                if (bulkContainer) bulkContainer.classList.add('hidden');

                if (btnCard) {
                    btnCard.classList.add('bg-scout-accent', 'text-white', 'shadow-sm');
                    btnCard.classList.remove('text-white/80');
                }
                if (btnMatrix) {
                    btnMatrix.classList.remove('bg-scout-accent', 'text-white', 'shadow-sm');
                    btnMatrix.classList.add('text-white/80');
                }
            }

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function updateBulkTotal(reguIdx) {
            let total = 0;
            // Criteria inputs
            const critInputs = document.querySelectorAll(`.bulk-crit-input[data-regu-index="${reguIdx}"]`);
            if (critInputs.length > 0) {
                critInputs.forEach(inp => {
                    total += parseFloat(inp.value) || 0;
                });
            } else {
                // Simple input
                const simpleInp = document.querySelector(`.bulk-simple-input[data-regu-index="${reguIdx}"]`);
                if (simpleInp) {
                    total = parseFloat(simpleInp.value) || 0;
                }
            }

            const totalEl = document.getElementById(`bulk-total-${reguIdx}`);
            if (totalEl) {
                totalEl.textContent = total.toFixed(2);
            }
        }

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
