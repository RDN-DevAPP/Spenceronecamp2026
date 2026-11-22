@extends('layouts.peserta')

@section('title', 'Babak 1: QR-Hunt Trivia - Scout Cyber Quiz Arena')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/cyber-quiz.js'])
@endpush

@section('content')
    @php
        $qrPayload = [
            'regu' => [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
            ],
            'session' => [
                'id' => $session->id,
                'score_round_1' => (int) ($session->score_round_1 ?? 0),
            ],
            'totalQr' => $totalQr,
            'availableQr' => $availableQr,
            'recentClaims' => $recentClaims,
            'routes' => [
                'roadmap' => route('peserta.cerdas-cermat.index'),
                'scan' => route('peserta.cerdas-cermat.round-1.scan'),
                'answer' => route('peserta.cerdas-cermat.round-1.answer'),
            ],
        ];
    @endphp

    <div
        id="cyber-quiz-root"
        data-component="PesertaQrHunt"
        data-payload-b64="{{ base64_encode(json_encode($qrPayload)) }}"
        v-pre
    >
        <div class="max-w-4xl mx-auto py-12 px-4 text-center text-gray-500 font-semibold">
            Menyiapkan Pemindai Kamera QR-Hunt...
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endpush
