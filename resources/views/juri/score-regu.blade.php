@extends('layouts.main')

@section('title', 'Nilai ' . $regu->nama_regu . ' - ' . $lomba->nama)

@section('content')
    <div class="py-8 sm:py-12 min-h-screen">
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
                                <h1 class="text-2xl sm:text-3xl font-bold mb-2 text-scout-primary">{{ strtoupper($regu->nama_regu) }}</h1>
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
                            $isVisualCompetition = !in_array($lomba->slug, ['cerdas-cermat', 'lkbb-tongkat']);
                        @endphp
                        
                        @if($isVisualCompetition)
                            <div class="mt-4 rounded-xl overflow-hidden shadow-sm border border-gray-200">
                                @if($photoPath)
                                    <div class="aspect-video w-full bg-gray-50 relative group">
                                        <img src="{{ asset('storage/' . $photoPath) }}" 
                                             alt="Foto Karya {{ strtoupper($regu->nama_regu) }}" 
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
                        @php
                            $description = match ($lomba->slug) {
                                'tapak-kemah' => $regu->tenda_description,
                                'masak-konvensional' => $regu->masakan_description,
                                'upcycle-art' => $regu->upcycle_description,
                                'desain-poster-digital' => $regu->poster_description,
                                default => null,
                            };
                        @endphp

                        @if ($description)
                            <div class="mt-4 bg-amber-50 rounded-xl p-4 border border-amber-100 shadow-sm">
                                <h3 class="text-xs font-black text-amber-800 uppercase tracking-widest mb-2 flex items-center">
                                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Deskripsi Karya
                                </h3>
                                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $description }}</p>
                            </div>
                        @endif
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

            @php
                $scoreStatus = $existingScore?->status ?? 'draft';
                $isLocked = $existingScore ? $existingScore->isLocked() : false;
                $isUnlockRequested = $existingScore ? $existingScore->unlock_requested : false;
                $isHastaKarya = ($lomba->slug === 'hasta-karya');
            @endphp

            <!-- Status Banner & Score Freezing Alert -->
            <div class="mb-6 bg-white rounded-xl shadow-md p-4 sm:p-5 border-l-4 {{ $scoreStatus === 'verified' ? 'border-emerald-600 bg-emerald-50/40' : ($scoreStatus === 'submitted' ? 'border-amber-600 bg-amber-50/40' : 'border-blue-600 bg-blue-50/40') }} flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $scoreStatus === 'verified' ? 'bg-emerald-100 text-emerald-700' : ($scoreStatus === 'submitted' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                        <i data-lucide="{{ $scoreStatus === 'verified' ? 'check-check' : ($scoreStatus === 'submitted' ? 'lock' : 'edit-3') }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm sm:text-base font-black uppercase tracking-wider text-gray-900">
                                Status: 
                                @if($scoreStatus === 'verified')
                                    <span class="text-emerald-700">VERIFIKASI & DISAHKAN</span>
                                @elseif($scoreStatus === 'submitted')
                                    <span class="text-amber-700">TERKUNCI (SUBMITTED)</span>
                                @else
                                    <span class="text-blue-700">DRAF (DRAFT)</span>
                                @endif
                            </h3>
                            @if($isUnlockRequested)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 border border-purple-200 animate-pulse">
                                    Menunggu Persetujuan Buka Kunci
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 mt-0.5">
                            @if($scoreStatus === 'verified')
                                Nilai telah disahkan oleh Administrator/Ketua Dewan Juri.
                            @elseif($scoreStatus === 'submitted')
                                Nilai telah difinalisasi dan dikunci untuk menjaga integritas kompetisi.
                            @else
                                Nilai draf masih dapat disunting bebas sebelum Anda menekan Finalisasi & Kunci Nilai.
                            @endif
                        </p>
                    </div>
                </div>

                @if($isLocked && !$isUnlockRequested)
                    <button type="button" onclick="document.getElementById('unlockModal').classList.remove('hidden')"
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-black rounded-lg shadow-sm transition flex items-center gap-1.5 active:scale-95 whitespace-nowrap">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                        Ajukan Buka Kunci (Request Edit)
                    </button>
                @endif
            </div>

            <!-- Unlock Request Modal -->
            <div id="unlockModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 animate-scale-up">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b">
                        <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                            <i data-lucide="unlock" class="w-5 h-5 text-amber-600"></i>
                            Permohonan Buka Kunci Nilai
                        </h3>
                        <button type="button" onclick="document.getElementById('unlockModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    @if($existingScore)
                    <form action="{{ route('juri.scores.request-unlock', $existingScore->id) }}" method="POST">
                        @csrf
                        <p class="text-xs text-gray-600 mb-3">
                            Setelah diajukan, Administrator akan meninjau alasan perubahan nilai sebelum form dapat disunting kembali.
                        </p>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alasan Perubahan Nilai *</label>
                            <textarea name="unlock_reason" rows="3" required placeholder="Contoh: Terjadi kesalahan penulisan skor kriteria 2..."
                                class="w-full text-sm border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('unlockModal').classList.add('hidden')"
                                class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg shadow transition">
                                Kirim Permohonan
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Side-by-Side View for Hasta Karya & Visual Competitions -->
            <div class="{{ $isHastaKarya || $karyaLomba ? 'grid grid-cols-1 lg:grid-cols-12 gap-6' : '' }}">
                
                @if($isHastaKarya || $karyaLomba)
                    <!-- Sisi Kiri: Detail Visual, Spesifikasi Bahan, & Filosofi Karya -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 sticky top-6">
                            <div class="flex items-center justify-between mb-3 pb-2 border-b">
                                <h2 class="text-base font-black text-scout-primary flex items-center gap-2">
                                    <i data-lucide="palette" class="w-5 h-5 text-scout-accent"></i>
                                    Portofolio Karya Regu
                                </h2>
                                @if($karyaLomba && $karyaLomba->status === 'submitted')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        SUBMITTED
                                    </span>
                                @endif
                            </div>

                            @if($karyaLomba)
                                <div class="space-y-4">
                                    <!-- Judul Karya -->
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">Judul Karya</span>
                                        <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $karyaLomba->judul_karya ?? 'Belum ada judul' }}</h3>
                                    </div>

                                    <!-- Galeri Foto Multi-Sudut -->
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 block">Dokumentasi Foto Karya</span>
                                        <div class="grid grid-cols-2 gap-2">
                                            @if($karyaLomba->foto_utama_path)
                                                <a href="{{ asset('storage/' . $karyaLomba->foto_utama_path) }}" target="_blank" class="group relative rounded-xl overflow-hidden aspect-square bg-gray-100 border border-gray-200 shadow-sm">
                                                    <img src="{{ asset('storage/' . $karyaLomba->foto_utama_path) }}" alt="Foto Utama" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded backdrop-blur-xs">Utama</span>
                                                </a>
                                            @endif
                                            @if($karyaLomba->foto_detail_path)
                                                <a href="{{ asset('storage/' . $karyaLomba->foto_detail_path) }}" target="_blank" class="group relative rounded-xl overflow-hidden aspect-square bg-gray-100 border border-gray-200 shadow-sm">
                                                    <img src="{{ asset('storage/' . $karyaLomba->foto_detail_path) }}" alt="Foto Detail" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded backdrop-blur-xs">Detail Sudut</span>
                                                </a>
                                            @endif
                                            @if($karyaLomba->foto_proses_path)
                                                <a href="{{ asset('storage/' . $karyaLomba->foto_proses_path) }}" target="_blank" class="group relative rounded-xl overflow-hidden aspect-square bg-gray-100 border border-gray-200 shadow-sm">
                                                    <img src="{{ asset('storage/' . $karyaLomba->foto_proses_path) }}" alt="Foto Proses" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded backdrop-blur-xs">Proses Pembuatan</span>
                                                </a>
                                            @endif
                                            @if($karyaLomba->foto_extra_path)
                                                <a href="{{ asset('storage/' . $karyaLomba->foto_extra_path) }}" target="_blank" class="group relative rounded-xl overflow-hidden aspect-square bg-gray-100 border border-gray-200 shadow-sm">
                                                    <img src="{{ asset('storage/' . $karyaLomba->foto_extra_path) }}" alt="Foto Tambahan" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded backdrop-blur-xs">Uji Fungsi</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Rincian Bahan & Alat -->
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-200 text-xs">
                                        <span class="font-bold text-scout-primary flex items-center gap-1.5 mb-1">
                                            <i data-lucide="wrench" class="w-3.5 h-3.5 text-scout-accent"></i> Rincian Bahan & Alat
                                        </span>
                                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $karyaLomba->rincian_bahan_alat ?? '-' }}</p>
                                    </div>

                                    <!-- Fungsi Guna -->
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-200 text-xs">
                                        <span class="font-bold text-scout-primary flex items-center gap-1.5 mb-1">
                                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-scout-accent"></i> Nilai Guna / Fungsi Praktis
                                        </span>
                                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $karyaLomba->fungsi_guna ?? '-' }}</p>
                                    </div>

                                    <!-- Deskripsi Filosofi -->
                                    <div class="bg-amber-50/60 rounded-xl p-3 border border-amber-200/80 text-xs">
                                        <span class="font-bold text-amber-900 flex items-center gap-1.5 mb-1">
                                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-600"></i> Filosofi & Narasi Konsep
                                        </span>
                                        <p class="text-amber-950 whitespace-pre-line leading-relaxed">{{ $karyaLomba->deskripsi_filosofi ?? '-' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="py-12 text-center text-gray-400">
                                    <i data-lucide="folder-x" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                                    <p class="text-xs font-bold uppercase tracking-wider">Belum Mengunggah Portofolio</p>
                                    <p class="text-[11px] text-gray-500 mt-1">Regu belum melengkapi berkas karya hasta karya pada sistem.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Sisi Kanan: Form Penilaian Kriteria -->
                <div class="{{ $isHastaKarya || $karyaLomba ? 'lg:col-span-7' : 'w-full' }}">
                    <!-- Scoring Form -->
                    <form method="POST" action="{{ route('juri.scores.store') }}" class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100">
                        @csrf
                        <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">
                        <input type="hidden" name="regu_profile_id" value="{{ $regu->id }}">

                        @if ($hasCriteria)
                            <!-- Criteria Based Scoring -->
                            <div class="mb-8">
                                <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h2 class="text-lg font-bold text-gray-800">Kriteria Penilaian</h2>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-bold">
                                        {{ $isLocked ? 'Mode Terkunci (Read Only)' : 'Pilih Nilai' }}
                                    </span>
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
                                        <div class="bg-gray-50 rounded-xl p-3 sm:p-4 border border-gray-200">
                                            <div class="flex justify-between items-start mb-2">
                                                <label class="text-sm sm:text-base font-semibold text-gray-900 flex-1 mr-2">
                                                    <span class="text-scout-primary font-bold mr-1">{{ $criterion->urutan }}.</span>
                                                    {{ $criterion->nama_kriteria }}
                                                </label>
                                                <span class="text-[10px] sm:text-xs font-bold px-2 py-0.5 bg-white border border-gray-200 rounded-md text-gray-600 whitespace-nowrap">
                                                    Max: {{ $criterion->nilai_max }}
                                                </span>
                                            </div>

                                            <input type="hidden" name="criteria[{{ $index }}][criteria_id]" value="{{ $criterion->id }}">

                                            <div class="relative">
                                                {{-- Selection Buttons for all ranges --}}
                                                <div class="grid grid-cols-5 gap-2 sm:gap-3">
                                                    @for($i = $criterion->nilai_min; $i <= $criterion->nilai_max; $i++)
                                                        <button type="button"
                                                            {{ $isLocked ? 'disabled' : '' }}
                                                            class="score-btn w-full h-11 sm:h-12 rounded-xl border-2 font-bold text-sm sm:text-base transition-all duration-200 flex items-center justify-center touch-manipulation
                                                                    {{ (old('criteria.' . $index . '.nilai', $existingValue) == $i && $existingValue !== '') ? 'bg-scout-primary border-scout-primary text-white shadow-md transform scale-105' : 'bg-white border-gray-200 text-gray-700 hover:border-scout-primary/50' }}
                                                                    {{ $isLocked ? 'cursor-not-allowed opacity-90' : '' }}"
                                                            onclick="{{ $isLocked ? '' : "selectScore(this, 'criteria-{$index}', {$i})" }}">
                                                            {{ $i }}
                                                        </button>
                                                    @endfor
                                                </div>
                                                <input type="hidden" id="criteria-{{ $index }}" name="criteria[{{ $index }}][nilai]"
                                                    value="{{ old('criteria.' . $index . '.nilai', $existingValue) }}" class="criteria-input"
                                                    onchange="calculateTotal()">
                                            </div>
                                            <div class="mt-1.5 text-right">
                                                <span class="text-[10px] text-gray-400">Rentang skor: {{ $criterion->nilai_min }} - {{ $criterion->nilai_max }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Total Preview (Sticky Bottom for Mobile) -->
                                <div class="mt-6 bg-emerald-50/70 border border-emerald-200 p-4 rounded-xl flex items-center justify-between shadow-sm">
                                    <span class="text-sm sm:text-base font-black text-emerald-950">Total Skor Akhir</span>
                                    <span class="text-2xl sm:text-3xl font-black text-emerald-800 font-mono" id="total-preview">0.00</span>
                                </div>
                            </div>
                        @else
                            <!-- Simple Scoring -->
                            <div class="mb-8">
                                <label class="block text-lg font-bold text-gray-800 mb-2">Nilai Akhir (0 - 100)</label>
                                <div class="relative max-w-xs">
                                    <input type="number" name="nilai" min="0" max="100" step="0.01"
                                        {{ $isLocked ? 'readonly disabled' : '' }}
                                        value="{{ old('nilai', $existingScore?->nilai) }}" placeholder="0.00"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-scout-primary focus:border-scout-primary text-2xl font-bold text-scout-primary {{ $isLocked ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                </div>
                            </div>
                        @endif

                        <!-- Catatan -->
                        <div class="mb-8 mt-6">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Catatan Evaluasi Juri</label>
                            <textarea name="catatan" rows="3" placeholder="Catatan kelebihan atau saran penyempurnaan..."
                                {{ $isLocked ? 'readonly' : '' }}
                                class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-scout-primary focus:border-scout-primary text-sm {{ $isLocked ? 'bg-gray-100 cursor-not-allowed' : '' }}">{{ old('catatan', $existingScore?->catatan) }}</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100 items-center justify-between">
                            <div class="flex gap-2 w-full sm:w-auto">
                                @if($prevReguId)
                                    <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $prevReguId]) }}"
                                        class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200 transition text-center flex-1 sm:flex-none">
                                        &larr; Sebelumnya
                                    </a>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2.5 w-full sm:w-auto justify-center">
                                @if(!$isLocked)
                                    <!-- Tombol Simpan Draf -->
                                    <button type="submit" name="action" value="draft"
                                        class="px-5 py-2.5 bg-white border-2 border-scout-primary text-scout-primary hover:bg-scout-primary/5 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center">
                                        <i data-lucide="file-edit" class="w-4 h-4 mr-1.5"></i>
                                        Simpan Draf
                                    </button>

                                    <!-- Tombol Finalisasi & Kunci Nilai -->
                                    <button type="submit" name="action" value="finalize"
                                        onclick="return confirm('Apakah Anda yakin ingin mengunci dan memfinalisasi nilai ini? Form tidak dapat disunting kembali tanpa izin Administrator.');"
                                        class="px-6 py-2.5 bg-scout-primary hover:bg-scout-primary/90 text-white rounded-xl font-black text-xs shadow-lg transition flex items-center justify-center">
                                        <i data-lucide="lock" class="w-4 h-4 mr-1.5 text-scout-accent"></i>
                                        Finalisasi & Kunci
                                    </button>
                                @else
                                    <span class="text-xs font-bold text-gray-500 py-2.5 px-3 bg-gray-100 rounded-xl flex items-center">
                                        <i data-lucide="shield-check" class="w-4 h-4 mr-1.5 text-emerald-600"></i> Form Nilai Terkunci
                                    </span>
                                @endif
                            </div>

                            <div class="flex gap-2 w-full sm:w-auto justify-end">
                                @if($nextReguId)
                                    <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $nextReguId]) }}"
                                        class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200 transition text-center flex-1 sm:flex-none">
                                        Selanjutnya &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Audit Trail Log History -->
                    @if($auditLogs && $auditLogs->isNotEmpty())
                        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5">
                                <i data-lucide="history" class="w-3.5 h-3.5"></i> Riwayat Mutasi & Audit Nilai
                            </h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto text-xs">
                                @foreach($auditLogs as $log)
                                    <div class="p-2.5 rounded-lg bg-gray-50 border border-gray-100 flex items-start justify-between gap-3">
                                        <div>
                                            <span class="font-bold text-gray-900">{{ strtoupper($log->action) }}</span>
                                            <span class="text-gray-500">oleh {{ $log->user->name ?? 'Sistem' }}</span>
                                            @if($log->reason)
                                                <p class="text-gray-600 mt-0.5 text-[11px] italic">"{{ $log->reason }}"</p>
                                            @endif
                                        </div>
                                        <div class="text-right whitespace-nowrap text-[10px] text-gray-400">
                                            <div>{{ $log->created_at->format('d/m/Y H:i') }}</div>
                                            <div>IP: {{ $log->ip_address }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>
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