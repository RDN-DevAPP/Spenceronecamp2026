@extends('layouts.peserta')

@section('title', 'Babak 2: Cipher Word-Search - Scout Cyber Quiz Arena')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/cyber-quiz.js'])
@endpush

@section('content')
    @php
        $cipherPayload = [
            'regu' => [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
            ],
            'session' => [
                'id' => $session->id,
                'score_round_2' => (int) ($session->score_round_2 ?? 0),
            ],
            'grids' => $grids,
            'routes' => [
                'roadmap' => route('peserta.cerdas-cermat.index'),
                'claim' => route('peserta.cerdas-cermat.round-2.claim'),
            ],
        ];
    @endphp

    <div
        id="cyber-quiz-root"
        data-component="PesertaCipherSearch"
        data-payload-b64="{{ base64_encode(json_encode($cipherPayload)) }}"
        v-pre
    >
        <div class="max-w-5xl mx-auto py-12 px-4 text-center text-gray-500 font-semibold">
            Menyiapkan Arena Cipher Word-Search...
        </div>
    </div>
@endsection
