<!-- Criteria-Based Scoring Form (Multiple Criteria per Regu) -->
<form method="POST" action="{{ route('juri.scores.storeBulk') }}" class="space-y-6">
    @csrf
    <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">

    @foreach ($regu as $reguIndex => $r)
        @php
            $existingScore = $existingScores->get($r->id);
            $hasScore = $existingScore !== null;

            // Build existing criteria scores map
            $existingCriteriaScores = [];
            if ($hasScore && $existingScore->scoreDetails) {
                foreach ($existingScore->scoreDetails as $detail) {
                    $existingCriteriaScores[$detail->scoring_criteria_id] = $detail->nilai;
                }
            }
        @endphp

        <div class="bg-white rounded-xl shadow-lg overflow-hidden {{ $hasScore ? 'ring-2 ring-green-500' : '' }}">
            <!-- Regu Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl font-bold mb-1">{{ $r->nama_regu }}</h3>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $r->jenis === 'putra' ? 'bg-blue-200 text-blue-900' : 'bg-pink-200 text-pink-900' }}">
                                    {{ ucfirst($r->jenis) }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-400 text-blue-900">
                                    Regu {{ $r->nomor_regu }}
                                </span>
                                @if ($hasScore)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-400 text-green-900 flex items-center">
                                        <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                                        Sudah Dinilai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <div class="text-xs text-blue-100 mb-1">Total Nilai</div>
                        <div class="text-2xl font-bold" id="total-{{ $reguIndex }}">0</div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="scores[{{ $reguIndex }}][regu_profile_id]" value="{{ $r->id }}">

            <!-- Criteria Inputs -->
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($criteria as $criteriaIndex => $criterion)
                        @php
                            $existingValue = $existingCriteriaScores[$criterion->id] ?? '';
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-400 transition">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $criterion->urutan }}. {{ $criterion->nama_kriteria }}
                            </label>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs text-gray-500">Range:</span>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                                    {{ $criterion->nilai_min }} - {{ $criterion->nilai_max }}
                                </span>
                            </div>
                            <input type="number" name="scores[{{ $reguIndex }}][criteria][{{ $criteriaIndex }}][nilai]"
                                min="{{ $criterion->nilai_min }}" max="{{ $criterion->nilai_max }}" step="0.01"
                                value="{{ $existingValue }}" placeholder="{{ $criterion->nilai_min }}"
                                data-regu-index="{{ $reguIndex }}"
                                class="criteria-input w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold"
                                oninput="calculateTotal({{ $reguIndex }})">
                            <input type="hidden" name="scores[{{ $reguIndex }}][criteria][{{ $criteriaIndex }}][criteria_id]"
                                value="{{ $criterion->id }}">
                        </div>
                    @endforeach
                </div>

                <!-- Catatan -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="scores[{{ $reguIndex }}][catatan]" rows="2"
                        placeholder="Tambahkan catatan untuk regu ini..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">{{ $existingScore?->catatan ?? '' }}</textarea>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Submit Button -->
    <div
        class="bg-white rounded-xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row gap-3 items-center justify-between sticky bottom-4">
        <a href="{{ route('juri.dashboard') }}"
            class="w-full sm:w-auto px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition-all duration-200 text-center">
            <span class="flex items-center justify-center">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali
            </span>
        </a>
        <button type="submit"
            class="w-full sm:w-auto btn-scout-primary px-6 py-3 rounded-lg font-bold shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
            Simpan Semua Penilaian
        </button>
    </div>
</form>

@push('scripts')
    <script>
        // Calculate total score for a regu
        function calculateTotal(reguIndex) {
            const inputs = document.querySelectorAll(`input[data-regu-index="${reguIndex}"].criteria-input`);
            let total = 0;

            inputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            const totalElement = document.getElementById(`total-${reguIndex}`);
            if (totalElement) {
                totalElement.textContent = total.toFixed(2);

                // Change color based on total
                if (total > 0) {
                    totalElement.classList.add('text-green-400');
                    totalElement.classList.remove('text-white');
                } else {
                    totalElement.classList.add('text-white');
                    totalElement.classList.remove('text-green-400');
                }
            }
        }

        // Calculate totals on page load
        document.addEventListener('DOMContentLoaded', function () {
            const reguIndexes = [...new Set(
                Array.from(document.querySelectorAll('.criteria-input'))
                    .map(input => input.dataset.reguIndex)
            )];

            reguIndexes.forEach(index => {
                calculateTotal(parseInt(index));
            });
        });
    </script>
@endpush