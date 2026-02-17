<!-- Simple Scoring Form (Single Score per Regu) -->
<form method="POST" action="{{ route('juri.scores.storeBulk') }}" class="bg-white rounded-xl shadow-lg overflow-hidden">
    @csrf
    <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                <tr>
                    <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                        Regu
                    </th>
                    <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                        Kategori
                    </th>
                    <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                        No. Regu
                    </th>
                    <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                        Nilai (0-100)
                    </th>
                    <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                        Catatan
                    </th>
                    <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($regu as $r)
                    @php
                        $existingScore = $existingScores->get($r->id);
                        $hasScore = $existingScore !== null;
                    @endphp
                    <tr class="hover:bg-scout-surface transition-colors {{ $hasScore ? 'bg-green-50' : '' }}">
                        <td class="px-4 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $r->nama_regu }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $r->jenis === 'putra' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($r->jenis) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-scout-accent text-scout-primary">
                                {{ $r->nomor_regu }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <input type="number" name="scores[{{ $loop->index }}][nilai]" min="0" max="100" step="0.01"
                                value="{{ $existingScore?->nilai ?? '' }}" placeholder="0.00"
                                class="w-28 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-scout-accent focus:border-scout-accent text-sm">
                        </td>
                        <td class="px-4 py-4">
                            <input type="text" name="scores[{{ $loop->index }}][catatan]"
                                value="{{ $existingScore?->catatan ?? '' }}" placeholder="Opsional"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-scout-accent focus:border-scout-accent text-sm">
                            <input type="hidden" name="scores[{{ $loop->index }}][regu_profile_id]" value="{{ $r->id }}">
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if ($hasScore)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                                    Dinilai
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    <i data-lucide="minus" class="w-3 h-3 mr-1"></i>
                                    Belum
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-200">
        @foreach ($regu as $r)
            @php
                $existingScore = $existingScores->get($r->id);
                $hasScore = $existingScore !== null;
            @endphp
            <div class="p-4 {{ $hasScore ? 'bg-green-50' : 'bg-white' }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-gray-900 mb-1">{{ $r->nama_regu }}</h3>
                        <div class="flex items-center gap-2">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $r->jenis === 'putra' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($r->jenis) }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-scout-accent text-scout-primary">
                                Regu {{ $r->nomor_regu }}
                            </span>
                        </div>
                    </div>
                    @if ($hasScore)
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                            Dinilai
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                            <i data-lucide="minus" class="w-3 h-3 mr-1"></i>
                            Belum
                        </span>
                    @endif
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nilai (0-100)</label>
                        <input type="number" name="scores[{{ $loop->index }}][nilai]" min="0" max="100" step="0.01"
                            value="{{ $existingScore?->nilai ?? '' }}" placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-scout-accent focus:border-scout-accent text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <input type="text" name="scores[{{ $loop->index }}][catatan]"
                            value="{{ $existingScore?->catatan ?? '' }}" placeholder="Tambahkan catatan..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-scout-accent focus:border-scout-accent text-sm">
                        <input type="hidden" name="scores[{{ $loop->index }}][regu_profile_id]" value="{{ $r->id }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Submit Button -->
    <div
        class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row gap-3 items-center justify-between">
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
            Simpan Semua Nilai
        </button>
    </div>
</form>