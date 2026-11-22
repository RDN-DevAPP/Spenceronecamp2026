@extends('layouts.main')

@section('title', 'Lembar Penilaian PBB - ' . $regu->nama_regu)

@section('content')
@php
    $locked = $existingScore && $existingScore->isLocked();
    $existingMap = [];
    if ($existingScore) {
        foreach ($existingScore->scoreDetails as $d) {
            $existingMap[$d->scoring_criteria_id] = (float) $d->nilai;
        }
    }
    $fmt = fn ($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');
    $idx = 0;
@endphp
<div class="min-h-screen bg-[#F8FAF8] py-6 sm:py-10">
    <div class="max-w-4xl mx-auto px-3 sm:px-6">

        {{-- Header lembar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-emerald-950 to-brand-forest text-white p-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-amber-300">Lembar Penilaian U-16 PBB &amp; Komandan Pasukan</p>
                <h1 class="text-lg sm:text-xl font-black uppercase mt-1">{{ $lomba->nama }}</h1>
                <p class="text-xs text-emerald-100/90">Kejuaraan Cabang Forum Baris Indonesia Kabupaten Barito Kuala</p>
            </div>
            <div class="p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div class="bg-gray-50 rounded-xl border border-gray-100 p-3">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block">Nama Satuan / Klub</span>
                    <span class="font-black text-brand-forest uppercase">{{ $regu->nama_regu }}</span>
                    <span class="text-xs text-gray-500 block">{{ $regu->sekolah ?? 'SMPN 1 Cerbon' }} &bull; {{ ucfirst($regu->jenis) }}</span>
                </div>
                <div class="bg-gray-50 rounded-xl border border-gray-100 p-3">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block">Nomor Urut</span>
                    <span class="font-black text-brand-forest text-2xl">{{ $regu->nomor_regu }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">{{ session('error') }}</div>
        @endif
        @if($locked)
            <div class="mb-4 bg-amber-50 border border-amber-300 text-amber-900 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Nilai sudah {{ $existingScore->isVerified() ? 'diverifikasi' : 'dikunci' }} dan tidak dapat diubah tanpa persetujuan Admin.
            </div>
        @endif

        <form method="POST" action="{{ route('juri.scores.storeBulk') }}" id="lembar-form" onsubmit="return confirmEmpty()">
            @csrf
            <input type="hidden" name="mata_lomba_id" value="{{ $lomba->id }}">
            <input type="hidden" name="scores[0][regu_profile_id]" value="{{ $regu->id }}">

            {{-- Total sticky --}}
            <div class="sticky top-16 z-30 mb-4">
                <div class="bg-brand-forest text-white rounded-2xl shadow-lg px-5 py-3 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-100">Total Nilai</span>
                    <span class="text-3xl font-black text-amber-300" id="grand-total">0</span>
                </div>
            </div>

            @foreach($groups as $kelompok => $items)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden mb-4">
                    <div class="px-4 sm:px-5 py-3 bg-emerald-950 text-white flex items-center justify-between">
                        <h2 class="text-sm font-black uppercase tracking-wide">{{ $kelompok }}</h2>
                        <span class="text-xs text-emerald-200">Subtotal: <strong class="text-amber-300 group-total" data-group="{{ $loop->index }}">0</strong> / {{ $fmt($items->sum('nilai_max')) }}</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($items as $c)
                            @php
                                $levels = array_values($c->rubrik ?? []);
                                $saved = $existingMap[$c->id] ?? null;
                                $selIdx = $saved !== null ? collect($levels)->search(fn ($x) => abs((float) $x - $saved) < 0.001) : false;
                                $matched = $selIdx !== false;
                            @endphp
                            <div class="criteria-row p-3.5 sm:p-5 flex flex-col gap-2.5 bg-white hover:bg-emerald-50/20 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                                    <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-900 text-xs font-black flex items-center justify-center shrink-0">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span>{{ $c->nama_kriteria }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 self-start sm:self-auto text-xs">
                                        <span class="text-gray-400 font-medium">Maks {{ $fmt($c->nilai_max) }}</span>
                                        <span class="h-3 w-px bg-gray-200"></span>
                                        <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200/80 font-bold text-emerald-900 text-[11px]">
                                            <span class="text-gray-400 font-normal">Pilihan:</span>
                                            <span class="active-badge font-black text-brand-forest">{{ $saved !== null ? $fmt($saved) : '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="scores[0][criteria][{{ $idx }}][criteria_id]" value="{{ $c->id }}">
                                <input type="hidden" 
                                       name="scores[0][criteria][{{ $idx }}][nilai]" 
                                       class="lvl-select" 
                                       data-group="{{ $loop->parent->index }}" 
                                       value="{{ $saved !== null ? $fmt($saved) : '' }}">

                                {{-- Pilihan Nilai Langsung Klik (9 Jenjang) --}}
                                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-9 gap-1.5 p-1.5 bg-gray-50/80 rounded-xl border border-gray-200/60">
                                    @foreach($labels as $li => $label)
                                        @php
                                            $v = $levels[$li] ?? 0;
                                            $isSelected = ($selIdx === $li);
                                            $shortLabel = match($li) {
                                                0 => 'Kosong',
                                                1 => 'Min',
                                                2 => 'K1',
                                                3 => 'K2',
                                                4 => 'C1',
                                                5 => 'C2',
                                                6 => 'B1',
                                                7 => 'B2',
                                                8 => 'SB',
                                                default => $label
                                            };
                                        @endphp
                                        <button type="button"
                                            @disabled($locked)
                                            data-val="{{ $fmt($v) }}"
                                            data-label="{{ $label }}"
                                            onclick="selectPbbScore(this)"
                                            title="{{ $label }}: {{ $fmt($v) }} Poin"
                                            class="pbb-btn group relative flex flex-col items-center justify-center py-2 px-1 rounded-lg border text-center transition-all select-none cursor-pointer {{ $isSelected ? 'bg-brand-forest text-amber-300 border-emerald-950 ring-2 ring-amber-400 font-black shadow-sm' : 'bg-white hover:bg-emerald-50 text-gray-700 border-gray-200 hover:border-emerald-300 font-medium' }} {{ $locked ? 'cursor-not-allowed opacity-75' : '' }}">
                                            <span class="text-[10px] leading-tight font-extrabold tracking-tight uppercase {{ $isSelected ? 'text-amber-200' : 'text-gray-500 group-hover:text-emerald-800' }}">
                                                {{ $shortLabel }}
                                            </span>
                                            <span class="text-xs sm:text-sm font-black leading-tight mt-0.5 {{ $isSelected ? 'text-amber-300' : 'text-gray-900 group-hover:text-brand-forest' }}">
                                                {{ $fmt($v) }}
                                            </span>
                                        </button>
                                    @endforeach

                                    @if($saved !== null && !$matched)
                                        <button type="button"
                                            @disabled($locked)
                                            data-val="{{ $fmt($saved) }}"
                                            data-label="Kustom"
                                            onclick="selectPbbScore(this)"
                                            class="pbb-btn col-span-3 sm:col-span-5 md:col-span-9 py-1 px-3 rounded-lg border bg-amber-500 text-white border-amber-600 font-black text-xs flex items-center justify-center gap-1.5">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            Nilai Khusus Tersimpan: {{ $fmt($saved) }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @php $idx++; @endphp
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-4 sm:p-5 mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Juri</label>
                <textarea name="scores[0][catatan]" rows="3" maxlength="500" @disabled($locked)
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20"
                    placeholder="Catatan penilaian untuk satuan ini...">{{ $existingScore?->catatan }}</textarea>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 flex flex-col sm:flex-row gap-3 items-center justify-between sticky bottom-3">
                <a href="{{ route('juri.lomba.score', $lomba->slug) }}"
                    class="w-full sm:w-auto px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-bold text-center transition">
                    &larr; Daftar Regu
                </a>
                <div class="flex gap-2 w-full sm:w-auto">
                    @if($prevReguId)
                        <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $prevReguId]) }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-bold transition">&lsaquo; Sebelumnya</a>
                    @endif
                    @if($nextReguId)
                        <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $nextReguId]) }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-bold transition">Berikutnya &rsaquo;</a>
                    @endif
                    @unless($locked)
                        <button type="submit"
                            class="flex-1 sm:flex-none px-6 py-3 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-sm font-black uppercase tracking-wider shadow-md transition cursor-pointer">
                            Simpan Nilai
                        </button>
                    @endunless
                </div>
            </div>
        </form>

        {{-- Kunci / buka kunci --}}
        @if($existingScore)
            <div class="mt-4 bg-white rounded-2xl shadow-sm border border-gray-200/80 p-4 sm:p-5">
                @if(!$locked)
                    <form method="POST" action="{{ route('juri.scores.finalize', $existingScore) }}"
                        onsubmit="return confirm('Kunci nilai? Setelah dikunci nilai tidak dapat diubah tanpa persetujuan Admin.');">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-500 text-emerald-950 rounded-xl text-sm font-black transition cursor-pointer">
                            Kunci &amp; Finalisasi Nilai ({{ $fmt($existingScore->nilai) }})
                        </button>
                        <span class="text-xs text-gray-500 ml-2">Simpan nilai terlebih dahulu sebelum mengunci.</span>
                    </form>
                @elseif(!$existingScore->unlock_requested && !$existingScore->isVerified())
                    <form method="POST" action="{{ route('juri.scores.request-unlock', $existingScore) }}" class="space-y-2">
                        @csrf
                        <label class="block text-sm font-bold text-gray-700">Ajukan buka kunci ke Admin</label>
                        <textarea name="unlock_reason" rows="2" required minlength="5" maxlength="500"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm" placeholder="Alasan pengajuan..."></textarea>
                        <button type="submit" class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-sm font-bold transition cursor-pointer">Ajukan Buka Kunci</button>
                    </form>
                @elseif($existingScore->unlock_requested)
                    <p class="text-sm text-amber-800 font-semibold">Pengajuan buka kunci sedang menunggu persetujuan Admin.</p>
                @endif
            </div>
        @endif

        <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('juri.pbb-rubrik.scan') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-forest hover:bg-emerald-900 text-white rounded-xl text-xs font-bold shadow-xs transition">
                <i data-lucide="scan-line" class="w-4 h-4"></i> Scan Formulir Penilaian Fisik
            </a>
            <a href="{{ route('juri.pbb-rubrik.print') }}" target="_blank" class="text-xs font-bold text-brand-forest hover:underline">Cetak lembar formulir manual</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectPbbScore(btn) {
        if (btn.hasAttribute('disabled') || btn.disabled) return;
        
        const row = btn.closest('.criteria-row');
        if (!row) return;

        const input = row.querySelector('.lvl-select');
        const badge = row.querySelector('.active-badge');
        const val = btn.getAttribute('data-val');

        // Set value
        input.value = val;
        if (badge) {
            badge.textContent = val;
        }

        // Toggle active styling on buttons in this row
        row.querySelectorAll('.pbb-btn').forEach(b => {
            b.classList.remove('bg-brand-forest', 'text-amber-300', 'border-emerald-950', 'ring-2', 'ring-amber-400', 'font-black', 'shadow-sm');
            b.classList.add('bg-white', 'text-gray-700', 'border-gray-200', 'font-medium');
            
            const labelSpan = b.querySelector('span:first-child');
            if (labelSpan) {
                labelSpan.classList.remove('text-amber-200');
                labelSpan.classList.add('text-gray-500');
            }
            const valSpan = b.querySelector('span:last-child');
            if (valSpan) {
                valSpan.classList.remove('text-amber-300');
                valSpan.classList.add('text-gray-900');
            }
        });

        btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200', 'font-medium');
        btn.classList.add('bg-brand-forest', 'text-amber-300', 'border-emerald-950', 'ring-2', 'ring-amber-400', 'font-black', 'shadow-sm');
        
        const activeLabelSpan = btn.querySelector('span:first-child');
        if (activeLabelSpan) {
            activeLabelSpan.classList.remove('text-gray-500');
            activeLabelSpan.classList.add('text-amber-200');
        }
        const activeValSpan = btn.querySelector('span:last-child');
        if (activeValSpan) {
            activeValSpan.classList.remove('text-gray-900');
            activeValSpan.classList.add('text-amber-300');
        }

        recalc();
    }

    function recalc() {
        let total = 0;
        const groups = {};
        document.querySelectorAll('.lvl-select').forEach(s => {
            const v = parseFloat(s.value) || 0;
            total += v;
            groups[s.dataset.group] = (groups[s.dataset.group] || 0) + v;
        });
        document.querySelectorAll('.group-total').forEach(el => {
            el.textContent = +(groups[el.dataset.group] || 0).toFixed(2);
        });
        const grandTotal = document.getElementById('grand-total');
        if (grandTotal) {
            grandTotal.textContent = +total.toFixed(2);
        }
    }

    function confirmEmpty() {
        const empty = Array.from(document.querySelectorAll('.lvl-select')).filter(s => !s.disabled && (s.value === '' || s.value === null));
        if (!empty.length) return true;
        if (!confirm(empty.length + ' poin penilaian belum dipilih dan akan dihitung 0 (Kosong). Lanjutkan simpan?')) {
            const firstEmpty = empty[0].closest('.criteria-row');
            if (firstEmpty) {
                firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstEmpty.classList.add('ring-2', 'ring-rose-400');
                setTimeout(() => firstEmpty.classList.remove('ring-2', 'ring-rose-400'), 2500);
            }
            return false;
        }
        empty.forEach(s => {
            s.value = '0';
        });
        return true;
    }

    document.addEventListener('DOMContentLoaded', recalc);
</script>
@endpush
