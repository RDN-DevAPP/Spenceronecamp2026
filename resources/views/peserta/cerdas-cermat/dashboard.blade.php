@extends('layouts.peserta')

@section('title', 'Scout Cyber Quiz Arena - Roadmap Cerdas Cermat')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/cyber-quiz.js'])
@endpush

@section('content')
    @php
        $roadmapPayload = [
            'regu' => [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
                'jenis' => $regu->jenis,
            ],
            'session' => [
                'id' => $session->id,
                'name_1' => $session->name_1,
                'name_2' => $session->name_2,
                'name_3' => $session->name_3,
                'score_round_1' => (int) ($session->score_round_1 ?? 0),
                'score_round_2' => (int) ($session->score_round_2 ?? 0),
                'score_round_3' => (int) ($session->score_round_3 ?? 0),
                'coin_initial' => (int) ($session->coin_initial ?? 0),
                'coin_balance' => (int) ($session->coin_balance ?? 0),
                'is_verified_round_3' => (bool) $session->is_verified_round_3,
            ],
            'anggota' => $anggota->map(fn($a) => [
                'id' => $a->id,
                'nama' => $a->nama,
                'jabatan' => $a->jabatan,
                'kelas' => $a->kelas,
            ])->values(),
            'roundStatuses' => $roundStatuses,
            'myRank' => $myRank,
            'qrStats' => $qrStats,
            'cipherStats' => $cipherStats,
            'routes' => [
                'register' => route('peserta.cerdas-cermat.register'),
                'dashboardAnggota' => route('peserta.anggota.create'),
                'round1' => route('peserta.cerdas-cermat.round-1'),
                'round2' => route('peserta.cerdas-cermat.round-2'),
                'round3' => route('peserta.cerdas-cermat.round-3'),
                'checkStatus' => route('peserta.cerdas-cermat.check-status'),
            ],
        ];
    @endphp

    <div
        id="cyber-quiz-root"
        data-component="PesertaRoadmapView"
        data-payload-b64="{{ base64_encode(json_encode($roadmapPayload)) }}"
        v-pre
    >
        <div class="max-w-4xl mx-auto py-12 px-4 text-center text-gray-500 font-semibold">
            Memuat Roadmap Scout Cyber Quiz Arena...
        </div>
    </div>
@endsection
