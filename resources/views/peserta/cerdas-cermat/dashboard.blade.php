@extends('layouts.peserta')

@section('title', 'Status Cerdas Cermat')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-scout-secondary">
            <div class="bg-scout-primary px-6 py-4 border-b border-scout-secondary flex items-center">
                <div class="bg-scout-accent p-2 rounded-full mr-3 text-scout-primary">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Status Lomba Cerdas Cermat</h2>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Round 1 Score -->
                    <div class="bg-green-50 rounded-lg p-6 border border-green-100 text-center">
                        <div class="text-green-800 font-bold text-lg mb-2">Babak 1</div>
                        <div class="text-3xl font-bold text-green-600 mb-1">{{ $session->score_round_1 }}</div>
                        <div class="text-xs text-green-700 uppercase tracking-wide">Poin</div>
                    </div>

                    <!-- Round 2 Score -->
                    <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-100 text-center">
                        <div class="text-yellow-800 font-bold text-lg mb-2">Babak 2</div>
                        <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $session->score_round_2 }}</div>
                        <div class="text-xs text-yellow-700 uppercase tracking-wide">Poin</div>
                    </div>

                    <!-- Round 3 Score -->
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-100 text-center">
                        <div class="text-blue-800 font-bold text-lg mb-2">Babak 3</div>
                        <div class="text-3xl font-bold text-blue-600 mb-1">{{ $session->score_round_3 }}</div>
                        <div class="text-xs text-blue-700 uppercase tracking-wide">Poin</div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i data-lucide="activity" class="w-5 h-5 mr-2 text-scout-primary"></i>
                        Status Tim
                    </h3>
                    <div class="flex items-center">
                        @if($session->status === 'registered')
                            <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-800 font-bold text-sm">Terdaftar</span>
                            <span class="ml-4 text-gray-600 text-sm">Menunggu dimulainya Babak 1.</span>
                        @elseif($session->status === 'round_1_done')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-bold text-sm">Selesai Babak
                                1</span>
                            <div class="ml-auto">
                                <a href="{{ route('peserta.cerdas-cermat.round-2') }}"
                                    class="btn-scout-primary py-2 px-4 rounded shadow text-sm font-bold flex items-center hover:bg-scout-accent hover:text-scout-primary transition">
                                    <i data-lucide="play" class="w-4 h-4 mr-2"></i> Lanjut Babak 2
                                </a>
                            </div>
                        @elseif($session->status === 'round_2_ongoing')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-bold text-sm">Sedang Babak
                                2</span>
                            <div class="ml-auto">
                                <a href="{{ route('peserta.cerdas-cermat.round-2') }}"
                                    class="btn-scout-primary py-2 px-4 rounded shadow text-sm font-bold flex items-center hover:bg-scout-accent hover:text-scout-primary transition">
                                    <i data-lucide="play" class="w-4 h-4 mr-2"></i> Lanjut Mengerjakan
                                </a>
                            </div>
                        @elseif($session->status === 'round_2_done')
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-bold text-sm">Selesai Babak
                                2</span>
                            <div class="ml-auto">
                                <a href="{{ route('peserta.cerdas-cermat.round-3') }}"
                                    class="btn-scout-primary py-2 px-4 rounded shadow text-sm font-bold flex items-center hover:bg-scout-accent hover:text-scout-primary transition">
                                    <i data-lucide="play" class="w-4 h-4 mr-2"></i> Lanjut Babak Final
                                </a>
                            </div>
                        @elseif($session->status === 'round_3_ongoing')
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 font-bold text-sm">Sedang Babak
                                Final</span>
                            <div class="ml-auto">
                                <a href="{{ route('peserta.cerdas-cermat.round-3') }}"
                                    class="btn-scout-primary py-2 px-4 rounded shadow text-sm font-bold flex items-center hover:bg-scout-accent hover:text-scout-primary transition">
                                    <i data-lucide="play" class="w-4 h-4 mr-2"></i> Lanjut Mengerjakan
                                </a>
                            </div>
                        @elseif($session->status === 'finished')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 font-bold text-sm">Selesai
                                Lomba</span>
                            <span class="ml-4 text-gray-600 text-sm">Terima kasih telah berpartisipasi.</span>
                        @else
                            <span
                                class="px-3 py-1 rounded-full bg-scout-accent text-scout-primary font-bold text-sm uppercase">{{ str_replace('_', ' ', $session->status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('peserta.dashboard') }}"
                        class="text-scout-primary hover:underline font-medium text-sm">
                        &larr; Kembali ke Dashboard Utama
                    </a>
                </div>
            </div>

            @if(isset($leaderboardRound1) && $leaderboardRound1->isNotEmpty())
                <div class="border-t border-gray-200 p-8 bg-gray-50">
                    <h3 class="font-bold text-xl text-gray-800 mb-6 flex items-center">
                        <i data-lucide="trophy" class="w-6 h-6 mr-2 text-scout-accent"></i>
                        Klasemen Babak 1
                    </h3>

                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-scout-primary text-white">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-6">Peringkat
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">Regu</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold">Skor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($leaderboardRound1 as $index => $item)
                                    <tr class="{{ $item->id === $session->id ? 'bg-yellow-50' : '' }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ $index + 1 }}
                                            @if($index < 5)
                                                <span
                                                    class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Lolos</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu }}</div>
                                            <div class="text-xs">Regu {{ $item->reguProfile->nomor_regu }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 text-right font-bold">
                                            {{ $item->score_round_1 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 text-center">
                        * Hanya 5 regu dengan skor tertinggi yang berhak melaju ke Babak 2.
                    </p>
                </div>
            @endif

            @if(isset($leaderboardRound2) && $leaderboardRound2->isNotEmpty())
                <div class="border-t border-gray-200 p-8 bg-gray-50">
                    <h3 class="font-bold text-xl text-gray-800 mb-6 flex items-center">
                        <i data-lucide="trophy" class="w-6 h-6 mr-2 text-scout-accent"></i>
                        Klasemen Babak 2
                    </h3>

                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-scout-primary text-white">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-6">Peringkat</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">Regu</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold">Skor Babak 2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($leaderboardRound2 as $index => $item)
                                    <tr class="{{ $item->id === $session->id ? 'bg-yellow-50' : '' }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ $index + 1 }}
                                            @if($index < 3)
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Lolos Final</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu }}</div>
                                            <div class="text-xs">Regu {{ $item->reguProfile->nomor_regu }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 text-right font-bold">
                                            {{ $item->score_round_2 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 text-center">
                        * Hanya 3 regu dengan skor tertinggi di Babak 2 yang berhak melaju ke Babak Final.
                    </p>
                </div>
            @endif

            @if(isset($leaderboardFinal) && $leaderboardFinal->isNotEmpty())
                <div class="border-t border-gray-200 p-8 bg-gray-50">
                    <h3 class="font-bold text-xl text-gray-800 mb-6 flex items-center">
                        <i data-lucide="crown" class="w-6 h-6 mr-2 text-yellow-500"></i>
                        Klasemen Akhir
                    </h3>

                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-scout-primary text-white">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-6">Peringkat</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">Regu</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold">Total Skor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($leaderboardFinal as $index => $item)
                                    <tr class="{{ $item->id === $session->id ? 'bg-yellow-50' : '' }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ $index + 1 }}
                                            @if($index === 0)
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">JUARA 1</span>
                                            @elseif($index === 1)
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">JUARA 2</span>
                                            @elseif($index === 2)
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">JUARA 3</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="font-bold text-gray-900">{{ $item->reguProfile->nama_regu }}</div>
                                            <div class="text-xs">Regu {{ $item->reguProfile->nomor_regu }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 text-right font-bold">
                                            {{ $item->total_score }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection