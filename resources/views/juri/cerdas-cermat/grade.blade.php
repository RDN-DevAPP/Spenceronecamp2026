@extends('layouts.main')

@section('content')
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Penilaian Babak {{ $round }}</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-indigo-600 font-bold">{{ $session->reguProfile->nama_regu }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter">Regu {{ $session->reguProfile->nomor_regu }}</span>
                    </div>
                    @if($isFinalized)
                        <div class="inline-flex items-center mt-3 px-3 py-1.5 rounded-xl bg-green-50 text-green-700 text-xs font-black border border-green-100 shadow-sm uppercase tracking-wider">
                            <i data-lucide="lock" class="w-3.5 h-3.5 mr-2"></i> Nilai Sudah Difinalkan
                        </div>
                    @endif
                </div>
                <a href="{{ route('juri.cerdas-cermat.index') }}"
                    class="group flex items-center px-5 py-2.5 bg-white border-2 border-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:border-indigo-100 hover:text-indigo-600 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali
                </a>
            </div>

            <form action="{{ route('juri.cerdas-cermat.grade', [$session->id, $round]) }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-100">
                            @forelse($answers as $index => $answer)
                                <div class="p-6 {{ $index % 2 === 0 ? 'bg-white' : 'bg-indigo-50/10' }} transition-colors">
                                    <div class="flex items-start mb-4">
                                        <div class="flex-shrink-0 mr-4">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600 text-white font-black text-sm shadow-lg">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-bold text-sm text-gray-900 mb-3 leading-relaxed">{{ $answer->question->question }}</p>
                                            <div class="flex flex-wrap gap-2">
                                                <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-green-50 border border-green-100 text-green-800 text-[10px] font-bold">
                                                    <span class="text-green-500/60 mr-1.5 uppercase tracking-tighter">Kunci:</span> {{ $answer->question->correct_answer }}
                                                </div>
                                                <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-100 text-gray-500 text-[10px] font-bold">
                                                    Max: {{ $answer->question->score }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <div class="text-[9px] text-gray-400 uppercase font-black tracking-[0.2em] mb-2 px-1">Jawaban Peserta:</div>
                                        <div class="text-base font-mono font-black text-indigo-900 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 break-words shadow-inner">
                                            {{ $answer->answer_text ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-gray-50 to-white p-4 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                                        <label for="score_mobile_{{ $answer->id }}" class="text-xs font-black text-gray-600 uppercase tracking-wider">Nilai Juri:</label>
                                        <div class="flex items-center gap-2">
                                            <input type="number" name="scores[{{ $answer->id }}]" id="score_mobile_{{ $answer->id }}"
                                                min="0" max="{{ $answer->question->score }}" value="{{ $answer->score ?? 0 }}"
                                                class="focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-20 rounded-xl text-lg font-black border-gray-200 text-center py-2 {{ $isFinalized ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'text-indigo-600 shadow-sm' }}"
                                                aria-label="Nilai untuk soal {{ $index + 1 }}" {{ $isFinalized ? 'readonly' : '' }}>
                                            <span class="text-xs text-gray-400 font-bold">/ {{ $answer->question->score }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-20 text-center text-gray-400 font-medium italic">
                                    Tidak ada jawaban untuk dinilai.
                                </div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th scope="col" class="px-8 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] w-12">No</th>
                                    <th scope="col" class="px-6 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] w-1/3">Soal & Kunci Kontrol</th>
                                    <th scope="col" class="px-6 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] w-1/3">Jawaban Peserta</th>
                                    <th scope="col" class="px-8 py-5 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] w-32">Pemberian Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($answers as $index => $answer)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-indigo-50/10' }} hover:bg-gray-50/50 transition-colors">
                                        <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-400 font-black align-top">
                                            {{ sprintf('%02d', $index + 1) }}
                                        </td>
                                        <td class="px-6 py-6 text-sm text-gray-900 align-top">
                                            <p class="font-bold mb-4 leading-relaxed text-gray-900">{{ $answer->question->question }}</p>
                                            <div class="flex items-center gap-2">
                                                <div class="text-[10px] font-black bg-green-50 text-green-700 px-3 py-1 rounded-lg border border-green-100 uppercase tracking-tighter">
                                                    Kunci: <span class="ml-1">{{ $answer->question->correct_answer }}</span>
                                                </div>
                                                <div class="text-[10px] font-black bg-gray-100 text-gray-500 px-2 py-1 rounded-lg uppercase tracking-tighter">
                                                    Max: {{ $answer->question->score }} pts
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 align-top">
                                            <div class="text-lg font-mono font-black text-indigo-900 bg-indigo-50/50 p-5 rounded-2xl border border-indigo-100 shadow-inner break-words">
                                                {{ $answer->answer_text ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap align-top">
                                            <div class="flex flex-col items-center">
                                                <div class="relative group">
                                                    <input type="number" name="scores[{{ $answer->id }}]" id="score_{{ $answer->id }}"
                                                        min="0" max="{{ $answer->question->score }}" value="{{ $answer->score ?? 0 }}"
                                                        class="focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-24 rounded-xl text-xl font-black border-gray-200 text-center py-3 {{ $isFinalized ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'text-indigo-600 shadow-lg' }}"
                                                        aria-label="Nilai untuk soal {{ $index + 1 }}" {{ $isFinalized ? 'readonly' : '' }}>
                                                </div>
                                                <span class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-widest">/ {{ $answer->question->score }} Poin</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-medium italic">
                                            Tidak ada jawaban untuk dinilai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(!$isFinalized)
                    <div class="mt-8 mb-4 sticky bottom-4 sm:bottom-8 z-30 px-4 sm:px-0">
                        <div class="max-w-4xl mx-auto">
                            <div class="bg-gradient-to-br from-indigo-900 to-blue-900 p-4 sm:p-5 shadow-2xl rounded-3xl border border-white/20 flex flex-col sm:flex-row items-center justify-between gap-4 backdrop-blur-xl">
                                <div class="hidden sm:flex items-center gap-4">
                                    <div class="bg-white/10 p-2.5 rounded-xl">
                                        <i data-lucide="info" class="w-5 h-5 text-indigo-200"></i>
                                    </div>
                                    <div class="text-left leading-tight">
                                        <p class="text-white font-black text-sm uppercase tracking-wider">Simpan Progress</p>
                                        <p class="text-indigo-200 text-[10px] font-medium italic">Pastikan seluruh nilai sudah benar.</p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="w-full sm:w-auto bg-white text-indigo-900 px-10 py-4 rounded-2xl font-black hover:bg-gray-100 transition-all shadow-xl flex items-center justify-center uppercase tracking-widest text-xs active:scale-95">
                                    <i data-lucide="save" class="w-5 h-5 mr-3"></i>
                                    Update Penilaian
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>

            {{-- Finalize button (separate from grade form) --}}
            @if(!$isFinalized && $answers->isNotEmpty())
                <div class="mt-8 flex justify-center px-4 sm:px-0 mb-12">
                    <form action="{{ route('juri.cerdas-cermat.finalize', [$session->id, $round]) }}" method="POST"
                        onsubmit="return confirm('PERHATIAN: Setelah difinalkan, nilai tidak dapat diubah lagi. Yakin ingin memfinalkan sekarang?')" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto bg-rose-50 text-rose-600 px-8 py-4 rounded-2xl font-black hover:bg-rose-600 hover:text-white transition-all shadow-lg flex items-center justify-center border-2 border-rose-100 uppercase tracking-widest text-xs">
                            <i data-lucide="lock" class="w-5 h-5 mr-3"></i>
                            Finalkan & Kunci Nilai
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
        </div>
    </div>
@endsection