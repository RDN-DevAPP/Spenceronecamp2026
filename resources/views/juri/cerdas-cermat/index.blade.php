@extends('layouts.main')

@push('styles')
    <style>
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(93, 64, 55, 0.3);
        }

        .session-card-reveal:hover {
            border-color: #5D4037;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
    </style>
@endpush

@section('content')
    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="bg-scout-primary p-2 rounded-xl text-scout-light shadow-md">
                        <i data-lucide="award" class="w-6 h-6 sm:w-8 sm:h-8"></i>
                    </div>
                    <span class="text-scout-primary">Penilaian Cerdas Cermat</span>
                </h1>
                <a href="{{ route('juri.rekap-nilai.index') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 btn-scout-primary rounded-xl font-bold text-sm transition-all hover:scale-105 active:scale-95 shadow-md">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 mr-2"></i>
                    Rekap Nilai
                </a>
            </div>

            {{-- Kontrol Babak --}}
            <div class="bg-white shadow-xl sm:rounded-3xl border border-scout-secondary mb-8 p-6 sm:p-8 group/kontrol overflow-hidden relative">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                    <i data-lucide="settings" class="w-48 h-48 text-scout-primary"></i>
                </div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4 relative z-10">
                    <h2 class="text-xl font-bold text-scout-primary flex items-center">
                        <i data-lucide="settings-2"
                            class="w-6 h-6 mr-3 text-scout-accent group-hover/kontrol:rotate-180 transition-transform duration-700"></i>
                        Kontrol Jalannya Babak
                    </h2>
                    <form action="{{ route('juri.cerdas-cermat.destroy-all') }}" method="POST"
                        onsubmit="return confirm('PERINGATAN KRITIS: Anda akan menghapus SELURUH sesi dan jawaban peserta beserta nilainya. Lanjutkan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="group flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-xl font-bold text-xs hover:bg-red-600 hover:text-white transition-all border border-red-200 shadow-sm">
                            <i data-lucide="trash-2" class="mr-2 w-4 h-4 group-hover:shake"></i>
                            Reset Seluruh Sesi
                        </button>
                    </form>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                    {{-- Babak 1 Card --}}
                    <div class="card-scout rounded-xl overflow-hidden shadow-lg relative h-full flex flex-col group hover-lift transition-all duration-300">
                        <div class="bg-scout-primary text-white p-5 border-b-4 border-scout-accent relative overflow-hidden flex-shrink-0">
                            <div class="absolute top-0 right-0 p-2 opacity-10 transform translate-x-2 -translate-y-2">
                                <i data-lucide="layers" class="w-16 h-16"></i>
                            </div>
                            <div class="flex items-center relative z-10">
                                <i data-lucide="layers" class="w-6 h-6 mr-3 text-scout-accent"></i>
                                <h3 class="text-lg font-bold text-white">Babak 1: Pilihan Ganda</h3>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-white flex-1 flex flex-col justify-center">
                            @if($round1Started)
                                <div class="flex flex-col items-center space-y-6">
                                    <div class="relative">
                                        <div class="absolute -inset-4 bg-green-500/20 rounded-full blur-xl animate-pulse"></div>
                                        <span class="relative inline-flex items-center px-6 py-2.5 rounded-full bg-green-50 text-green-700 text-xs font-black border border-green-200 shadow-sm uppercase tracking-widest">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-ping"></span>
                                            Berjalan
                                        </span>
                                    </div>
                                    <form action="{{ route('juri.cerdas-cermat.reset-round', 1) }}" method="POST"
                                        onsubmit="return confirm('Reset status Babak 1? Ini hanya mereset tombol Mulai, tidak menghapus jawaban.')">
                                        @csrf
                                        <button type="submit"
                                            class="text-[11px] text-gray-500 hover:text-red-600 transition-all font-bold flex items-center uppercase tracking-tighter bg-gray-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-200">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 mr-1.5"></i> Reset Status
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="py-2">
                                    <form action="{{ route('juri.cerdas-cermat.start-round', 1) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Yakin memulai Babak 1? Peserta akan dapat mengerjakan soal.')"
                                            class="w-full btn-scout-primary px-4 py-4 rounded-xl font-bold transition-all shadow-md active:scale-95 text-sm flex items-center justify-center group/btn uppercase tracking-widest">
                                            <i data-lucide="play-circle" class="w-5 h-5 mr-3 group-hover/btn:scale-110 transition-transform"></i> 
                                            Mulai Lomba
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="text-[9px] uppercase font-black text-gray-600 mb-3 tracking-[0.2em] flex items-center justify-between">
                                    <span>Siap Berlomba</span>
                                    <span class="bg-scout-secondary/20 text-scout-primary px-2 py-0.5 rounded border border-scout-secondary/30">{{ $readyRound1->count() }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2 max-h-24 overflow-y-auto p-0.5 custom-scrollbar">
                                    @forelse($readyRound1 as $r)
                                        <span class="px-2.5 py-1 text-scout-primary bg-scout-secondary/20 rounded-md text-[10px] font-bold border border-scout-secondary/30 cursor-default">
                                            {{ $r->reguProfile->nama_regu }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic py-1">Menunggu peserta...</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Babak 2 Card --}}
                    <div class="card-scout rounded-xl overflow-hidden shadow-lg relative h-full flex flex-col group hover-lift transition-all duration-300">
                        <div class="bg-scout-primary text-white p-5 border-b-4 border-scout-accent relative overflow-hidden flex-shrink-0">
                            <div class="absolute top-0 right-0 p-2 opacity-10 transform translate-x-2 -translate-y-2">
                                <i data-lucide="edit-3" class="w-16 h-16"></i>
                            </div>
                            <div class="flex items-center relative z-10">
                                <i data-lucide="edit-3" class="w-6 h-6 mr-3 text-scout-accent"></i>
                                <h3 class="text-lg font-bold text-white">Babak 2: Isian Singkat</h3>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-white flex-1 flex flex-col justify-between">
                            @if($round2Started)
                                <div class="flex flex-col items-center space-y-6">
                                    <div class="relative">
                                        <div class="absolute -inset-4 bg-green-500/20 rounded-full blur-xl animate-pulse"></div>
                                        <span class="relative inline-flex items-center px-6 py-2.5 rounded-full bg-green-50 text-green-700 text-xs font-black border border-green-200 shadow-sm uppercase tracking-widest">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-ping"></span>
                                            Berjalan
                                        </span>
                                    </div>
                                    <form action="{{ route('juri.cerdas-cermat.reset-round', 2) }}" method="POST"
                                        onsubmit="return confirm('Reset status Babak 2?')">
                                        @csrf
                                        <button type="submit"
                                            class="text-[11px] text-gray-500 hover:text-red-600 transition-all font-bold flex items-center uppercase tracking-tighter bg-gray-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-200">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 mr-1.5"></i> Reset Status
                                        </button>
                                    </form>
                                </div>
                            @else
                                @php $round2Locked = !$round1Started || $totalVerifiedRound2 < 5; @endphp
                                <div class="py-2">
                                    @if($round2Locked)
                                        <div class="w-full bg-gray-50 text-gray-400 px-4 py-8 rounded-xl border border-gray-200 flex flex-col items-center justify-center">
                                            <i data-lucide="lock" class="w-8 h-8 mb-3 opacity-50"></i>
                                            <span class="text-[10px] font-black uppercase tracking-tighter text-center leading-tight">
                                                {{ !$round1Started ? 'Selesaikan Babak 1' : 'Butuh 5 verifikasi (' . $totalVerifiedRound2 . '/5)' }}
                                            </span>
                                        </div>
                                    @else
                                        <form action="{{ route('juri.cerdas-cermat.start-round', 2) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Yakin memulai Babak 2?')"
                                                class="w-full btn-scout-primary px-4 py-4 rounded-xl font-bold transition-all shadow-md active:scale-95 text-sm flex items-center justify-center group/btn uppercase tracking-widest">
                                                <i data-lucide="play-circle" class="w-5 h-5 mr-3 group-hover/btn:scale-110 transition-transform"></i> 
                                                Mulai Babak 2
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="text-[9px] uppercase font-black text-gray-600 mb-3 tracking-[0.2em] flex items-center justify-between">
                                    <span>Siap Dinilai</span>
                                    <span class="bg-scout-secondary/20 text-scout-primary px-2 py-0.5 rounded border border-scout-secondary/30">{{ $readyRound2->count() }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2 max-h-24 overflow-y-auto p-0.5 custom-scrollbar">
                                    @forelse($readyRound2 as $r)
                                        <span class="px-2.5 py-1 text-scout-primary bg-scout-secondary/20 rounded-md text-[10px] font-bold border border-scout-secondary/30 cursor-default">
                                            {{ $r->reguProfile->nama_regu }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic py-1">Menunggu peserta...</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Babak 3 Card --}}
                    <div class="card-scout rounded-xl overflow-hidden shadow-lg relative h-full flex flex-col group hover-lift transition-all duration-300">
                        <div class="bg-scout-primary text-white p-5 border-b-4 border-scout-accent relative overflow-hidden flex-shrink-0">
                            <div class="absolute top-0 right-0 p-2 opacity-10 transform translate-x-2 -translate-y-2">
                                <i data-lucide="award" class="w-16 h-16"></i>
                            </div>
                            <div class="flex items-center relative z-10">
                                <i data-lucide="award" class="w-6 h-6 mr-3 text-scout-accent"></i>
                                <h3 class="text-lg font-bold text-white">Babak 3: Soal Uraian</h3>
                            </div>
                        </div>

                        <div class="p-6 bg-white flex-1 flex flex-col justify-between">
                            @if($round3Started)
                                <div class="flex flex-col items-center space-y-6">
                                    <div class="relative">
                                        <div class="absolute -inset-4 bg-green-500/20 rounded-full blur-xl animate-pulse"></div>
                                        <span class="relative inline-flex items-center px-6 py-2.5 rounded-full bg-green-50 text-green-700 text-xs font-black border border-green-200 shadow-sm uppercase tracking-widest">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-ping"></span>
                                            Berjalan
                                        </span>
                                    </div>
                                    <form action="{{ route('juri.cerdas-cermat.reset-round', 3) }}" method="POST"
                                        onsubmit="return confirm('Reset status Babak 3?')">
                                        @csrf
                                        <button type="submit"
                                            class="text-[11px] text-gray-500 hover:text-red-600 transition-all font-bold flex items-center uppercase tracking-tighter bg-gray-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-gray-200 hover:border-red-200">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 mr-1.5"></i> Reset Status
                                        </button>
                                    </form>
                                </div>
                            @else
                                @php $round3Locked = !$round2Started || $totalGradedRound2 < 3; @endphp
                                <div class="py-2">
                                    @if($round3Locked)
                                        <div class="w-full bg-gray-50 text-gray-400 px-4 py-8 rounded-xl border border-gray-200 flex flex-col items-center justify-center">
                                            <i data-lucide="lock" class="w-8 h-8 mb-3 opacity-50"></i>
                                            <span class="text-[10px] font-black uppercase tracking-tighter text-center leading-tight">
                                                {{ !$round2Started ? 'Selesaikan Babak 2' : 'Butuh 3 penilaian B2 (' . $totalGradedRound2 . '/3)' }}
                                            </span>
                                        </div>
                                    @else
                                        <form action="{{ route('juri.cerdas-cermat.start-round', 3) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Yakin memulai Babak 3?')"
                                                class="w-full btn-scout-primary px-4 py-4 rounded-xl font-bold transition-all shadow-md active:scale-95 text-sm flex items-center justify-center group/btn uppercase tracking-widest">
                                                <i data-lucide="crown" class="w-5 h-5 mr-3 group-hover/btn:rotate-12 transition-transform"></i> 
                                                Mulai Babak 3
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="text-[9px] uppercase font-black text-gray-600 mb-3 tracking-[0.2em] flex items-center justify-between">
                                    <span>Finalis</span>
                                    <span class="bg-scout-secondary/20 text-scout-primary px-2 py-0.5 rounded border border-scout-secondary/30">{{ $readyRound3->count() }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2 max-h-24 overflow-y-auto p-0.5 custom-scrollbar">
                                    @forelse($readyRound3 as $r)
                                        <span class="px-2.5 py-1 text-scout-primary bg-scout-secondary/20 rounded-md text-[10px] font-bold border border-scout-secondary/30 cursor-default">
                                            {{ $r->reguProfile->nama_regu }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic py-1">Menunggu finalis...</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8 flex flex-col sm:flex-row justify-center sm:justify-end items-center px-4 sm:px-0 gap-4">
                <a href="{{ route('juri.cerdas-cermat.qualifiers') }}"
                    class="w-full sm:w-auto bg-white text-scout-primary hover:text-white hover:bg-scout-primary px-8 py-4 rounded-xl font-black shadow-md hover:shadow-lg transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center text-sm border-2 border-scout-primary uppercase tracking-wider">
                    <i data-lucide="user-check" class="mr-3 w-5 h-5"></i>
                    Verifikasi Lolos Babak 2
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 overflow-hidden">
                <div class="p-0 sm:p-2 bg-gradient-to-b from-gray-50 to-white">
                    <div class="overflow-x-auto">
                        <!-- Mobile View -->
                        <div class="md:hidden divide-y divide-gray-100 bg-white">
                            @forelse($sessions as $session)
                                <div class="p-6 transition-all hover:bg-scout-secondary/30 border-l-4 border-transparent hover:border-scout-primary group">
                                    <div class="flex justify-between items-start mb-4 gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-lg font-black text-gray-900 leading-tight mb-1 truncate">{{ $session->reguProfile->nama_regu }}</div>
                                            <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 uppercase tracking-tighter">
                                                Regu {{ $session->reguProfile->nomor_regu }}
                                            </div>
                                            <div class="mt-2 text-[10px] text-gray-500 font-medium italic flex items-center flex-wrap gap-1">
                                                <i data-lucide="users" class="w-3 h-3 text-scout-primary"></i>
                                                <span class="truncate">{{ $session->name_1 }}, {{ $session->name_2 }}, {{ $session->name_3 }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                             <span class="px-3 py-1 text-[10px] font-black rounded-full shadow-sm tracking-wide uppercase 
                                                {{ $session->status === 'finished' ? 'bg-green-100 text-green-700' : 'bg-scout-secondary text-scout-primary' }}">
                                                {{ str_replace('_', ' ', $session->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-5">
                                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-200 shadow-sm text-center">
                                            <div class="text-[9px] text-scout-primary uppercase font-black tracking-[0.1em] mb-1">Babak 2</div>
                                            <div class="text-xl font-black text-gray-900 flex items-center justify-center gap-1.5">
                                                {{ $session->score_round_2 ?? 0 }}
                                                @if($session->is_finalized_round_2)
                                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-green-500"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-200 shadow-sm text-center">
                                            <div class="text-[9px] text-scout-primary uppercase font-black tracking-[0.1em] mb-1">Babak 3</div>
                                            <div class="text-xl font-black text-gray-900 flex items-center justify-center gap-1.5">
                                                {{ $session->score_round_3 ?? 0 }}
                                                @if($session->is_finalized_round_3)
                                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-green-500"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-3">
                                        <div class="grid grid-cols-2 gap-3">
                                            @if(in_array($session->status, ['round_2_done', 'round_3_ongoing', 'round_3_done', 'finished']))
                                                <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 2]) }}"
                                                    class="flex items-center justify-center font-black btn-scout-primary px-3 py-3 rounded-xl transition-all text-xs shadow-md active:scale-95">
                                                    <i data-lucide="edit-2" class="w-3.5 h-3.5 mr-2"></i> NILAI B2
                                                    @if($session->is_finalized_round_2) <i data-lucide="lock" class="w-3 h-3 ml-1.5 opacity-70"></i> @endif
                                                </a>
                                            @endif
                                            
                                            @if(in_array($session->status, ['round_3_done', 'finished']))
                                                <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 3]) }}"
                                                    class="flex items-center justify-center font-black btn-scout-primary px-3 py-3 rounded-xl transition-all text-xs shadow-md active:scale-95">
                                                    <i data-lucide="crown" class="w-3.5 h-3.5 mr-2"></i> NILAI B3
                                                    @if($session->is_finalized_round_3) <i data-lucide="lock" class="w-3 h-3 ml-1.5 opacity-70"></i> @endif
                                                </a>
                                            @endif
                                        </div>

                                        <div class="flex gap-2">
                                            <form action="{{ route('juri.cerdas-cermat.reset-session', $session->id) }}"
                                                method="POST" class="flex-1"
                                                onsubmit="return confirm('Reset progress regu ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full flex items-center justify-center font-black text-amber-700 bg-amber-50/50 hover:bg-amber-100 px-4 py-3 rounded-xl transition-all text-[10px] sm:text-xs border border-amber-100 uppercase tracking-wider">
                                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 mr-2"></i> Reset
                                                </button>
                                            </form>

                                            <form action="{{ route('juri.cerdas-cermat.destroy', $session->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus sesi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center justify-center font-bold text-rose-600 bg-rose-50/50 hover:bg-rose-100 h-[46px] w-[46px] rounded-xl transition-all border border-rose-100">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-20 text-center">
                                    <div class="bg-gray-50 inline-flex p-4 rounded-full mb-4">
                                        <i data-lucide="inbox" class="w-12 h-12 text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">Kosong</h3>
                                    <p class="text-sm text-gray-400">Belum ada sesi yang terdaftar untuk dinilai.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Desktop View -->
                        <table class="hidden md:table min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Regu Peserta</th>
                                    <th class="px-6 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                    <th class="px-6 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Skor Babak 2</th>
                                    <th class="px-6 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Skor Babak 3</th>
                                    <th class="px-8 py-5 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Navigasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($sessions as $session)
                                    <tr class="hover:bg-scout-secondary/20 transition-all group">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-base font-black text-gray-900 leading-tight mb-1 group-hover:text-scout-primary transition-colors">{{ $session->reguProfile->nama_regu }}</div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px] font-bold uppercase tracking-tighter">Regu {{ $session->reguProfile->nomor_regu }}</span>
                                                <div class="text-[10px] text-gray-400 font-medium italic truncate max-w-[200px]">
                                                    {{ $session->name_1 }}, {{ $session->name_2 }}, {{ $session->name_3 }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <span class="px-3 py-1 text-[10px] font-black rounded-full shadow-sm tracking-wide uppercase 
                                                {{ $session->status === 'finished' ? 'bg-green-100 text-green-700' : 'bg-scout-secondary text-scout-primary' }}">
                                                {{ str_replace('_', ' ', $session->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl font-black text-gray-900">{{ $session->score_round_2 ?? 0 }}</span>
                                                @if($session->is_finalized_round_2) <i data-lucide="lock" class="w-4 h-4 text-green-500"></i> @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl font-black text-gray-900">{{ $session->score_round_3 ?? 0 }}</span>
                                                @if($session->is_finalized_round_3) <i data-lucide="lock" class="w-4 h-4 text-green-500"></i> @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-3 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                                                @if(in_array($session->status, ['round_2_done', 'round_3_ongoing', 'round_3_done', 'finished']))
                                                    <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 2]) }}"
                                                        class="flex items-center font-black text-scout-primary bg-scout-secondary/30 hover:bg-scout-primary hover:text-white px-4 py-2 rounded-xl transition-all text-[11px] border border-scout-secondary shadow-sm">
                                                        NILAI B2 @if($session->is_finalized_round_2) <i data-lucide="lock" class="w-3 h-3 ml-2"></i> @endif
                                                    </a>
                                                @endif

                                                @if(in_array($session->status, ['round_3_done', 'finished']))
                                                    <a href="{{ route('juri.cerdas-cermat.show', [$session->id, 3]) }}"
                                                        class="flex items-center font-black text-scout-primary bg-scout-secondary/30 hover:bg-scout-primary hover:text-white px-4 py-2 rounded-xl transition-all text-[11px] border border-scout-secondary shadow-sm">
                                                        NILAI B3 @if($session->is_finalized_round_3) <i data-lucide="lock" class="w-3 h-3 ml-2"></i> @endif
                                                    </a>
                                                @endif

                                                <form action="{{ route('juri.cerdas-cermat.reset-session', $session->id) }}"
                                                    method="POST" onsubmit="return confirm('Reset progress regu ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 text-amber-600 bg-amber-50 hover:bg-amber-600 hover:text-white rounded-xl transition-all border border-amber-100"
                                                        title="Reset Progress Sesi">
                                                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('juri.cerdas-cermat.destroy', $session->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus sesi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white rounded-xl transition-all border border-rose-100"
                                                        title="Hapus Sesi">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-20 text-center text-gray-400 font-medium italic tracking-wide">
                                            Belum ada sesi yang dapat dinilai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Upgrade onsubmit confirm()
        const confirmForms = document.querySelectorAll('form[onsubmit*="return confirm"]');
        
        confirmForms.forEach(form => {
            const onsubmitAttr = form.getAttribute('onsubmit');
            const match = onsubmitAttr.match(/return confirm\(['"](.*?)['"]\)/);
            if (match) {
                const message = match[1];
                form.removeAttribute('onsubmit');
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#5D4037',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });

        // Upgrade onclick confirm()
        const confirmButtons = document.querySelectorAll('[onclick*="return confirm"]');
        
        confirmButtons.forEach(btn => {
            const onclickAttr = btn.getAttribute('onclick');
            const match = onclickAttr.match(/return confirm\(['"](.*?)['"]\)/);
            if (match) {
                const message = match[1];
                btn.removeAttribute('onclick');
                
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#5D4037',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = btn.closest('form');
                            if (form) {
                                form.submit();
                            } else if (btn.tagName.toLowerCase() === 'a') {
                                window.location.href = btn.href;
                            }
                        }
                    });
                });
            }
        });
    });
</script>
@endpush