@extends('layouts.main')

@section('title', 'Penilaian ' . $lomba->nama . ' - Juri')

@section('content')
<div class="min-h-screen bg-[#F8FAF8] py-6 sm:py-10">
    <div class="max-w-4xl mx-auto px-3 sm:px-6">
        <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-700">Lembar Penilaian PBB</p>
                <h1 class="text-2xl font-black text-brand-forest uppercase tracking-tight">{{ $lomba->nama }}</h1>
                <p class="text-xs text-gray-500">Pilih regu untuk membuka lembar penilaian. Nilai maksimal: {{ rtrim(rtrim(number_format($totalMax, 2, '.', ''), '0'), '.') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('juri.pbb-rubrik.print') }}" target="_blank"
                    class="px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl text-xs font-bold text-gray-700 transition">Cetak Lembar Kosong</a>
                <a href="{{ route('juri.dashboard') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700 transition">&larr; Dashboard</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm divide-y divide-gray-100 overflow-hidden">
            @forelse($regu as $r)
                @php $s = $existingScores->get($r->id); @endphp
                <a href="{{ route('juri.lomba.score.regu', ['slug' => $lomba->slug, 'reguId' => $r->id]) }}"
                    class="flex items-center justify-between gap-3 p-4 hover:bg-emerald-50/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-forest text-amber-300 flex items-center justify-center font-black text-sm shrink-0">{{ $r->nomor_regu }}</div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $r->nama_regu }}</p>
                            <p class="text-[11px] text-gray-500">{{ ucfirst($r->jenis) }} &bull; {{ $r->sekolah ?? 'SMPN 1 Cerbon' }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if($s)
                            <span class="text-lg font-black text-brand-forest">{{ rtrim(rtrim(number_format($s->nilai, 2, '.', ''), '0'), '.') }}</span>
                            <span class="block text-[10px] font-bold {{ $s->isLocked() ? 'text-amber-700' : 'text-emerald-700' }}">
                                {{ $s->isVerified() ? 'Terverifikasi' : ($s->isLocked() ? 'Terkunci' : 'Tersimpan') }}
                            </span>
                        @else
                            <span class="text-xs font-bold text-gray-400">Belum dinilai</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-sm text-gray-400">Belum ada regu terdaftar.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
