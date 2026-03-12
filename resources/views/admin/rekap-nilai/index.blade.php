@extends('layouts.main')

@section('title', 'Rekap Nilai Cerdas Cermat - Admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Rekap Nilai Cerdas Cermat</h1>
                <p class="text-gray-500 mt-1">Lihat ranking dan nilai semua babak</p>
            </div>
            <a href="{{ route('juri.cerdas-cermat.index') }}"
                class="text-gray-600 hover:text-gray-900 border border-gray-300 bg-white px-4 py-2 rounded-md font-medium shadow-sm flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200" id="rekapTabs">
            <nav class="flex space-x-4" aria-label="Tabs">
                <button onclick="showTab('babak1')" id="tab-babak1"
                    class="tab-btn text-scout-primary border-scout-primary px-4 py-2 font-bold text-sm border-b-2 transition">
                    Rekap Babak 1
                </button>
                <button onclick="showTab('babak2')" id="tab-babak2"
                    class="tab-btn text-gray-500 hover:text-gray-700 px-4 py-2 font-bold text-sm border-b-2 border-transparent transition">
                    Rekap Babak 2
                </button>
                <button onclick="showTab('babak3')" id="tab-babak3"
                    class="tab-btn text-gray-500 hover:text-gray-700 px-4 py-2 font-bold text-sm border-b-2 border-transparent transition">
                    Rekap Babak 3
                </button>
            </nav>
        </div>

        {{-- Babak 1 Tab --}}
        <div id="content-babak1" class="tab-content">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-green-50 px-6 py-4 border-b border-green-200">
                    <h2 class="text-lg font-bold text-green-800 flex items-center">
                        <i data-lucide="award" class="w-5 h-5 mr-2"></i>
                        Babak 1 - Pilihan Ganda
                    </h2>
                </div>
                @if($rekapBabak1->isEmpty())
                    <div class="p-8 text-center text-gray-500 italic">Belum ada data nilai Babak 1.</div>
                @else
                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-gray-200">
                        @foreach($rekapBabak1 as $index => $item)
                            <div class="p-4 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-scout-accent text-scout-primary' : 'bg-gray-200 text-gray-700' }} font-black text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-scout-primary">{{ $item->score_round_1 }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">Poin</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Desktop --}}
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Ranking</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Regu</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rekapBabak1 as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        {{ $index + 1 }}
                                        @if($index === 0) <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded font-bold">🥇</span> @endif
                                        @if($index === 1) <span class="ml-2 text-xs bg-gray-100 text-gray-800 px-2 py-0.5 rounded font-bold">🥈</span> @endif
                                        @if($index === 2) <span class="ml-2 text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded font-bold">🥉</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }} ({{ ucfirst($item->reguProfile->jenis ?? 'Lainnya') }})</div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-lg font-black text-scout-primary">
                                        {{ $item->score_round_1 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Babak 2 Tab --}}
        <div id="content-babak2" class="tab-content hidden">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200">
                    <h2 class="text-lg font-bold text-yellow-800 flex items-center">
                        <i data-lucide="award" class="w-5 h-5 mr-2"></i>
                        Babak 2 - Isian Singkat
                    </h2>
                </div>
                @if($rekapBabak2->isEmpty())
                    <div class="p-8 text-center text-gray-500 italic">Belum ada data nilai Babak 2.</div>
                @else
                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-gray-200">
                        @foreach($rekapBabak2 as $index => $item)
                            <div class="p-4 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-scout-accent text-scout-primary' : 'bg-gray-200 text-gray-700' }} font-black text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-scout-primary">{{ $item->score_round_2 }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">Poin</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Desktop --}}
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Ranking</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Regu</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rekapBabak2 as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        {{ $index + 1 }}
                                        @if($index === 0) <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded font-bold">🥇</span> @endif
                                        @if($index === 1) <span class="ml-2 text-xs bg-gray-100 text-gray-800 px-2 py-0.5 rounded font-bold">🥈</span> @endif
                                        @if($index === 2) <span class="ml-2 text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded font-bold">🥉</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }} ({{ ucfirst($item->reguProfile->jenis ?? 'Lainnya') }})</div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-lg font-black text-scout-primary">
                                        {{ $item->score_round_2 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Babak 3 Tab --}}
        <div id="content-babak3" class="tab-content hidden">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                    <h2 class="text-lg font-bold text-blue-800 flex items-center">
                        <i data-lucide="award" class="w-5 h-5 mr-2"></i>
                        Babak 3 - Uraian (Final)
                    </h2>
                </div>
                @if($rekapBabak3->isEmpty())
                    <div class="p-8 text-center text-gray-500 italic">Belum ada data nilai Babak 3.</div>
                @else
                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-gray-200">
                        @foreach($rekapBabak3 as $index => $item)
                            <div class="p-4 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-scout-accent text-scout-primary' : 'bg-gray-200 text-gray-700' }} font-black text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-scout-primary">{{ $item->score_round_3 }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">Poin</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Desktop --}}
                    <table class="hidden md:table min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Ranking</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Regu</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rekapBabak3 as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        {{ $index + 1 }}
                                        @if($index === 0) <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded font-bold">🥇</span> @endif
                                        @if($index === 1) <span class="ml-2 text-xs bg-gray-100 text-gray-800 px-2 py-0.5 rounded font-bold">🥈</span> @endif
                                        @if($index === 2) <span class="ml-2 text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded font-bold">🥉</span> @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu ?? 'Regu #' . $item->regu_id }}</div>
                                        <div class="text-xs text-gray-500">Regu {{ $item->reguProfile->nomor_regu ?? '-' }} ({{ ucfirst($item->reguProfile->jenis ?? 'Lainnya') }})</div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-lg font-black text-scout-primary">
                                        {{ $item->score_round_3 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabName) {
        // Hide all content
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Reset all tabs
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('text-scout-primary', 'border-scout-primary');
            el.classList.add('text-gray-500', 'border-transparent');
        });
        // Show selected content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        // Highlight selected tab
        const tabBtn = document.getElementById('tab-' + tabName);
        tabBtn.classList.remove('text-gray-500', 'border-transparent');
        tabBtn.classList.add('text-scout-primary', 'border-scout-primary');

        // Re-init lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endpush
@endsection
