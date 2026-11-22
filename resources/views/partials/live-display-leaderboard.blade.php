@php
    $items = array_map(function($item) {
        if (is_array($item)) {
            $nama = $item['nama_regu'] ?? ($item['reguProfile']->nama_regu ?? 'Regu');
            $skor = $item['total_skor'] ?? ($item['total_nilai'] ?? 0);
            $sekolah = $item['sekolah'] ?? ($item['reguProfile']->sekolah ?? 'Pangkalan Gudep SMPN 1 Cerbon');
            $item['nama_regu'] = $nama;
            $item['total_skor'] = $skor;
            $item['sekolah'] = $sekolah;
            return $item;
        }
        return $item;
    }, $data ?? []);

    $top3  = array_slice($items, 0, 3);
    $rest  = array_slice($items, 3);

    $podiumColors = [
        0 => [
            'bg'      => 'from-amber-950/90 via-amber-900/40 to-black/80',
            'border'  => 'border-amber-400/80',
            'badge'   => 'from-amber-400 to-amber-600 border-amber-300 text-amber-950',
            'glow'    => 'shadow-[0_0_40px_rgba(217,119,6,0.35)]',
            'label'   => 'text-amber-400',
            'bar'     => 'bg-gradient-to-r from-transparent via-amber-400 to-transparent',
            'rank'    => '👑 JUARA I',
            'height'  => 'lg:-translate-y-4',
        ],
        1 => [
            'bg'      => 'from-slate-900/90 via-slate-800/30 to-black/80',
            'border'  => 'border-slate-400/50',
            'badge'   => 'from-slate-300 to-slate-500 border-slate-200 text-slate-900',
            'glow'    => 'shadow-xl',
            'label'   => 'text-slate-300',
            'bar'     => 'bg-gradient-to-r from-transparent via-slate-400 to-transparent',
            'rank'    => '🥈 RUNNER-UP 1',
            'height'  => '',
        ],
        2 => [
            'bg'      => 'from-amber-950/80 via-orange-950/20 to-black/80',
            'border'  => 'border-amber-700/60',
            'badge'   => 'from-amber-700 to-amber-900 border-amber-600 text-amber-100',
            'glow'    => 'shadow-xl',
            'label'   => 'text-amber-600',
            'bar'     => 'bg-gradient-to-r from-transparent via-amber-700 to-transparent',
            'rank'    => '🥉 RUNNER-UP 2',
            'height'  => '',
        ],
    ];
@endphp

{{-- ============================================================
     MOBILE: Ranked List (shown only on < 1024px)
============================================================ --}}
<div class="block lg:hidden space-y-2">

    {{-- TOP 3 MOBILE CARDS --}}
    @foreach($top3 as $i => $item)
    @php $pc = $podiumColors[$i]; @endphp
    <div class="relative bg-gradient-to-r {{ $pc['bg'] }} border {{ $pc['border'] }} {{ $pc['glow'] }} rounded-2xl overflow-hidden flex items-center gap-3 p-3 {{ $i === 0 ? 'ring-1 ring-amber-400/40' : '' }}">
        {{-- Top accent line --}}
        <div class="absolute top-0 inset-x-0 h-0.5 {{ $pc['bar'] }}"></div>

        {{-- Rank Badge --}}
        <div class="shrink-0 flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br {{ $pc['badge'] }} border-2 shadow-lg">
            @if($i === 0)
                <span class="text-xl leading-none">👑</span>
            @else
                <span class="text-xl font-black leading-none">{{ $i + 1 }}</span>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="{{ $pc['label'] }} text-[9px] font-black uppercase tracking-widest mb-0.5">{{ $pc['rank'] }}</div>
            <div class="text-white font-black text-sm leading-tight truncate">
                @if(!$isRevealed)
                    <span class="sensor-placeholder text-amber-400 font-mono tracking-widest text-xs">
                        <span class="shuffling-text">##########</span>
                    </span>
                    <span class="censored-target hidden">{{ $item['nama_regu'] }}</span>
                @else
                    {{ $item['nama_regu'] }}
                @endif
            </div>
            <div class="text-emerald-400/80 text-[10px] mt-0.5">{{ $item['sekolah'] ?? 'Pangkalan Gudep' }}</div>
        </div>

        {{-- Score --}}
        <div class="shrink-0 text-right bg-black/40 rounded-xl px-3 py-2 border border-white/10">
            <div class="text-[9px] text-gray-400 uppercase font-bold">Skor</div>
            <div class="text-lg font-mono font-black {{ $i === 0 ? 'text-amber-400' : 'text-white' }}">
                {{ number_format($item['total_skor'] ?? 0, 1) }}
            </div>
            {{-- Medals --}}
            <div class="flex items-center justify-end gap-1 mt-0.5">
                @if(($item['emas'] ?? 0) > 0)
                    <span class="text-[9px] font-bold text-amber-400">🥇{{ $item['emas'] }}</span>
                @endif
                @if(($item['perak'] ?? 0) > 0)
                    <span class="text-[9px] font-bold text-slate-300">🥈{{ $item['perak'] }}</span>
                @endif
                @if(($item['perunggu'] ?? 0) > 0)
                    <span class="text-[9px] font-bold text-amber-600">🥉{{ $item['perunggu'] }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{-- Rank 4+ on mobile --}}
    @if(count($rest) > 0)
        <div class="mt-2 space-y-1.5">
            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 px-1 flex items-center gap-1.5">
                <span class="flex-1 h-px bg-white/10"></span>
                Peringkat 4 ke bawah
                <span class="flex-1 h-px bg-white/10"></span>
            </div>
            @foreach($rest as $idx => $item)
                <div class="flex items-center gap-3 bg-emerald-950/30 border border-white/5 rounded-xl px-3 py-2">
                    <span class="shrink-0 w-6 h-6 rounded-lg bg-black/60 border border-white/10 text-slate-400 flex items-center justify-center text-[10px] font-mono font-black">#{{ $idx + 4 }}</span>
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-slate-100 text-xs block truncate">{{ $item['nama_regu'] }}</span>
                    </div>
                    <span class="text-xs font-mono font-black text-amber-400 shrink-0">{{ number_format($item['total_skor'] ?? 0, 1) }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ============================================================
     DESKTOP: Podium 3-Column (shown only on >= 1024px)
============================================================ --}}
<div class="hidden lg:block space-y-6">
    <div class="grid grid-cols-3 gap-6 items-end pt-8">

        {{-- Juara 2 --}}
        @if(isset($top3[1]))
            <div class="bg-gradient-to-t {{ $podiumColors[1]['bg'] }} border-2 {{ $podiumColors[1]['border'] }} {{ $podiumColors[1]['glow'] }} rounded-3xl p-6 text-center {{ $podiumColors[1]['height'] }} transition duration-300 backdrop-blur-md relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1 {{ $podiumColors[1]['bar'] }}"></div>
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br {{ $podiumColors[1]['badge'] }} border-2 shadow-lg mb-3">
                    <span class="text-2xl font-black">2</span>
                </div>
                <div class="{{ $podiumColors[1]['label'] }} text-xs uppercase tracking-widest font-black mb-1">Runner-up 1</div>
                <div class="text-2xl font-extrabold text-white mb-2 min-h-[36px] flex items-center justify-center">
                    @if(!$isRevealed)
                        <div class="sensor-placeholder flex items-center justify-center space-x-2 text-amber-400 font-mono tracking-widest">
                            <span class="shuffling-text">########</span>
                        </div>
                        <div class="censored-target hidden">{{ $top3[1]['nama_regu'] }}</div>
                    @else
                        <span>{{ $top3[1]['nama_regu'] }}</span>
                    @endif
                </div>
                <div class="text-xs text-emerald-300/80 mb-3">{{ $top3[1]['sekolah'] ?? 'Pangkalan Gudep' }}</div>
                <div class="flex items-center justify-center gap-2 mb-2 text-xs font-bold">
                    @if(($top3[1]['emas'] ?? 0) > 0) <span class="text-amber-400">🥇{{ $top3[1]['emas'] }}</span> @endif
                    @if(($top3[1]['perak'] ?? 0) > 0) <span class="text-slate-300">🥈{{ $top3[1]['perak'] }}</span> @endif
                    @if(($top3[1]['perunggu'] ?? 0) > 0) <span class="text-amber-600">🥉{{ $top3[1]['perunggu'] }}</span> @endif
                </div>
                <div class="bg-black/50 border border-white/10 rounded-xl py-2 px-4 inline-block">
                    <span class="text-slate-400 text-xs block">Total Skor</span>
                    <span class="text-xl font-mono font-black text-amber-400">{{ number_format($top3[1]['total_skor'] ?? 0, 1) }}</span>
                </div>
            </div>
        @else
            <div class="border-2 border-dashed border-white/10 rounded-3xl p-6 text-center text-slate-500 text-sm">Belum ada data Juara 2</div>
        @endif

        {{-- Juara 1 --}}
        @if(isset($top3[0]))
            <div class="bg-gradient-to-t {{ $podiumColors[0]['bg'] }} border-2 {{ $podiumColors[0]['border'] }} {{ $podiumColors[0]['glow'] }} rounded-3xl p-8 text-center {{ $podiumColors[0]['height'] }} transition duration-300 backdrop-blur-md relative overflow-hidden pulsing-champion">
                <div class="absolute top-0 inset-x-0 h-2 {{ $podiumColors[0]['bar'] }}"></div>
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br {{ $podiumColors[0]['badge'] }} border-4 shadow-xl mb-3 animate-pulse">
                    <span class="text-3xl">👑</span>
                </div>
                <div class="{{ $podiumColors[0]['label'] }} text-xs uppercase tracking-widest font-black mb-1">JUARA UMUM I</div>
                <div class="text-3xl font-black text-white mb-2 min-h-[44px] flex items-center justify-center">
                    @if(!$isRevealed)
                        <div class="sensor-placeholder flex items-center justify-center space-x-2 text-amber-400 font-mono tracking-widest">
                            <span class="shuffling-text text-2xl">##########</span>
                        </div>
                        <div class="censored-target hidden">{{ $top3[0]['nama_regu'] }}</div>
                    @else
                        <span class="text-amber-300 drop-shadow-[0_2px_10px_rgba(245,158,11,0.5)]">{{ $top3[0]['nama_regu'] }}</span>
                    @endif
                </div>
                <div class="text-sm text-emerald-200/90 mb-3">{{ $top3[0]['sekolah'] ?? 'Pangkalan Gudep' }}</div>
                <div class="flex items-center justify-center gap-2 mb-3 text-sm font-bold">
                    @if(($top3[0]['emas'] ?? 0) > 0) <span class="text-amber-400">🥇{{ $top3[0]['emas'] }}</span> @endif
                    @if(($top3[0]['perak'] ?? 0) > 0) <span class="text-slate-300">🥈{{ $top3[0]['perak'] }}</span> @endif
                    @if(($top3[0]['perunggu'] ?? 0) > 0) <span class="text-amber-600">🥉{{ $top3[0]['perunggu'] }}</span> @endif
                </div>
                <div class="bg-amber-500/10 border border-amber-400/30 rounded-2xl py-3 px-6 inline-block">
                    <span class="text-amber-300 text-xs block font-semibold">Total Akumulasi Skor</span>
                    <span class="text-3xl font-mono font-black text-amber-400">{{ number_format($top3[0]['total_skor'] ?? 0, 1) }}</span>
                </div>
            </div>
        @else
            <div class="border-2 border-dashed border-white/10 rounded-3xl p-6 text-center text-slate-500 text-sm">Belum ada data Juara 1</div>
        @endif

        {{-- Juara 3 --}}
        @if(isset($top3[2]))
            <div class="bg-gradient-to-t {{ $podiumColors[2]['bg'] }} border-2 {{ $podiumColors[2]['border'] }} {{ $podiumColors[2]['glow'] }} rounded-3xl p-6 text-center {{ $podiumColors[2]['height'] }} transition duration-300 backdrop-blur-md relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1 {{ $podiumColors[2]['bar'] }}"></div>
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br {{ $podiumColors[2]['badge'] }} border-2 shadow-lg mb-3">
                    <span class="text-2xl font-black">3</span>
                </div>
                <div class="{{ $podiumColors[2]['label'] }} text-xs uppercase tracking-widest font-black mb-1">Runner-up 2</div>
                <div class="text-2xl font-extrabold text-white mb-2 min-h-[36px] flex items-center justify-center">
                    @if(!$isRevealed)
                        <div class="sensor-placeholder flex items-center justify-center space-x-2 text-amber-400 font-mono tracking-widest">
                            <span class="shuffling-text">########</span>
                        </div>
                        <div class="censored-target hidden">{{ $top3[2]['nama_regu'] }}</div>
                    @else
                        <span>{{ $top3[2]['nama_regu'] }}</span>
                    @endif
                </div>
                <div class="text-xs text-emerald-300/80 mb-3">{{ $top3[2]['sekolah'] ?? 'Pangkalan Gudep' }}</div>
                <div class="flex items-center justify-center gap-2 mb-2 text-xs font-bold">
                    @if(($top3[2]['emas'] ?? 0) > 0) <span class="text-amber-400">🥇{{ $top3[2]['emas'] }}</span> @endif
                    @if(($top3[2]['perak'] ?? 0) > 0) <span class="text-slate-300">🥈{{ $top3[2]['perak'] }}</span> @endif
                    @if(($top3[2]['perunggu'] ?? 0) > 0) <span class="text-amber-600">🥉{{ $top3[2]['perunggu'] }}</span> @endif
                </div>
                <div class="bg-black/50 border border-white/10 rounded-xl py-2 px-4 inline-block">
                    <span class="text-slate-400 text-xs block">Total Skor</span>
                    <span class="text-xl font-mono font-black text-amber-400">{{ number_format($top3[2]['total_skor'] ?? 0, 1) }}</span>
                </div>
            </div>
        @else
            <div class="border-2 border-dashed border-white/10 rounded-3xl p-6 text-center text-slate-500 text-sm">Belum ada data Juara 3</div>
        @endif

    </div>

    {{-- Rank 4+ Desktop --}}
    @if(count($rest) > 0)
        <div class="bg-black/40 border border-white/10 rounded-2xl p-4 backdrop-blur-sm">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 px-2 flex items-center gap-2">
                <span class="text-emerald-400">≡</span>
                <span>Peringkat 4 dan Seterusnya</span>
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($rest as $idx => $item)
                    <div class="bg-[#1B4332]/20 border border-white/5 rounded-xl px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3 truncate">
                            <span class="w-7 h-7 rounded-lg bg-black/60 border border-white/10 text-slate-300 flex items-center justify-center text-xs font-mono font-bold shrink-0">
                                #{{ $idx + 4 }}
                            </span>
                            <div class="truncate">
                                <span class="font-bold text-slate-100 text-sm block truncate">{{ $item['nama_regu'] }}</span>
                                <span class="text-[11px] text-slate-400 truncate block">{{ $item['sekolah'] ?? 'Pangkalan' }}</span>
                            </div>
                        </div>
                        <div class="text-right pl-3 shrink-0">
                            <span class="text-xs font-mono font-bold text-amber-400">{{ number_format($item['total_skor'] ?? 0, 1) }}</span>
                            <span class="text-[10px] text-slate-500 block">skor</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
