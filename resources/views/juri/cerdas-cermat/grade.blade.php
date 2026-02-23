@extends('layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Penilaian Babak {{ $round }}</h1>
                    <p class="text-gray-500">{{ $session->reguProfile->nama_regu }} - Regu
                        {{ $session->reguProfile->nomor_regu }}
                    </p>
                </div>
                <a href="{{ route('juri.cerdas-cermat.index') }}"
                    class="text-gray-600 hover:text-gray-900 border border-gray-300 bg-white px-4 py-2 rounded-md font-medium shadow-sm">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('juri.cerdas-cermat.grade', [$session->id, $round]) }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-200">
                            @forelse($answers as $index => $answer)
                                <div class="p-4 bg-white hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-shrink-0 mr-3">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-scout-primary text-white font-black text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-sm text-gray-900 mb-2 leading-relaxed">{{ $answer->question->question }}</p>
                                            <div class="flex flex-wrap gap-2 text-xs">
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 border border-green-100 text-green-800">
                                                    <span class="text-gray-500 mr-1">Kunci:</span> <span class="font-bold">{{ $answer->question->correct_answer }}</span>
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-gray-50 border border-gray-200 text-gray-600">
                                                    Max: {{ $answer->question->score }} pts
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1">Jawaban Peserta:</div>
                                        <div class="text-base font-mono font-bold text-blue-900 bg-blue-50 p-3 rounded-md border border-blue-100 break-words">
                                            {{ $answer->answer_text ?? '-' }}
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 flex items-center justify-between">
                                        <label for="score_mobile_{{ $answer->id }}" class="text-sm font-bold text-gray-700">Nilai Diberikan:</label>
                                        <div class="flex items-center">
                                            <input type="number" name="scores[{{ $answer->id }}]" id="score_mobile_{{ $answer->id }}"
                                                min="0" max="{{ $answer->question->score }}" value="{{ $answer->score }}"
                                                class="focus:ring-scout-primary focus:border-scout-primary block w-20 rounded-md sm:text-lg font-bold border-gray-300 text-center py-2"
                                                aria-label="Nilai untuk soal {{ $index + 1 }}" required>
                                            <span class="text-sm text-gray-500 ml-2 font-medium">/ {{ $answer->question->score }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500 italic">
                                    Tidak ada jawaban untuk dinilai.
                                </div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">
                                        Soal & Kunci
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">
                                        Jawaban Peserta
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">
                                        Nilai
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($answers as $index => $answer)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium align-top">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 align-top">
                                            <p class="font-medium mb-2">{{ $answer->question->question }}</p>
                                            <div
                                                class="text-xs text-gray-500 bg-gray-100 p-2 rounded border border-gray-200 inline-block">
                                                Kunci: <span
                                                    class="font-bold text-green-700">{{ $answer->question->correct_answer }}</span>
                                                <span class="mx-1 text-gray-300">|</span>
                                                Max: {{ $answer->question->score }} pts
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div
                                                class="text-lg font-mono font-bold text-blue-900 bg-blue-50 p-3 rounded-md border border-blue-100">
                                                {{ $answer->answer_text ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top">
                                            <div class="flex flex-col items-center">
                                                <input type="number" name="scores[{{ $answer->id }}]" id="score_{{ $answer->id }}"
                                                    min="0" max="{{ $answer->question->score }}" value="{{ $answer->score }}"
                                                    class="focus:ring-scout-primary focus:border-scout-primary block w-24 rounded-md sm:text-lg font-bold border-gray-300 text-center"
                                                    aria-label="Nilai untuk soal {{ $index + 1 }}" required>
                                                <span class="text-xs text-gray-400 mt-1">/ {{ $answer->question->score }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                            Tidak ada jawaban untuk dinilai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 flex justify-end sticky bottom-6 z-10">
                    <div class="bg-white p-4 shadow-lg rounded-xl border border-gray-200 flex items-center gap-4">
                        <span class="text-sm text-gray-500">Pastikan semua nilai sudah terisi dengan benar.</span>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md flex items-center">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            Simpan Penilaian
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection