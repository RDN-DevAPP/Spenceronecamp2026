@extends('layouts.peserta')

@section('title', 'Babak 3: Final Coin Royale - Scout Cyber Quiz Arena')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/cyber-quiz.js'])
@endpush

@section('content')
    @php
        $coinPayload = [
            'regu' => [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
            ],
            'isFinalist' => $isFinalist,
            'coinRoyale' => $coinRoyale,
            'routes' => [
                'roadmap' => route('peserta.cerdas-cermat.index'),
                'bet' => route('peserta.cerdas-cermat.round-3.bet'),
            ],
        ];
    @endphp

    <div
        id="cyber-quiz-root"
        data-component="PesertaCoinRoyale"
        data-payload-b64="{{ base64_encode(json_encode($coinPayload)) }}"
        v-pre
    >
        <div class="max-w-5xl mx-auto py-12 px-4 text-center text-gray-500 font-semibold">
            Memuat Arena Final Coin Royale...
        </div>
    </div>
@endsection
