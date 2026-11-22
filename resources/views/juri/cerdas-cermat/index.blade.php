@extends('layouts.main')

@section('title', 'Panel Kontrol Juri & Wasit Coin Royale - Spencerone Camp 2026')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/cyber-quiz.js'])
@endpush

@section('content')
    @php
        $juriPayload = array_merge($arenaData, [
            'routes' => [
                'state' => route('juri.cerdas-cermat.state'),
                'roundStatusBase' => url('/juri/cerdas-cermat/round-status'),
                'selectFinalists' => route('juri.cerdas-cermat.select-finalists'),
                'coinRoyaleBase' => url('/juri/cerdas-cermat/coin-royale'),
                'coinRoyaleResetAll' => route('juri.cerdas-cermat.coin-royale.reset-all'),
                'liveDisplay' => route('live-display', ['tab' => 'quiz']),
            ],
        ]);
    @endphp

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                id="cyber-quiz-root"
                data-component="JuriQuizPanel"
                data-payload-b64="{{ base64_encode(json_encode($juriPayload)) }}"
                v-pre
            >
                <div class="bg-white rounded-2xl p-10 text-center text-gray-500 font-semibold border border-gray-200">
                    Memuat Panel Kontrol Dewan Juri...
                </div>
            </div>
        </div>
    </div>
@endsection
