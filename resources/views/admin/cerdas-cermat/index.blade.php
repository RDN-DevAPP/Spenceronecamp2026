@extends('layouts.main')

@section('title', 'Kelola Scout Cyber Quiz Arena - Admin Spencerone Camp 2026')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/cyber-quiz.js'])
@endpush

@section('content')
    @php
        $adminPayload = [
            'activeTab' => in_array($activeTab, ['qr-hunt', 'cipher-grid', 'coin-royale', 'translator', 'settings'], true) ? $activeTab : 'qr-hunt',
            'settings' => $settings,
            'qrQuestions' => $qrQuestions,
            'cipherGrids' => $cipherGrids,
            'coinRoyaleRounds' => $coinRoyaleRounds,
            'roundStatuses' => $roundStatuses,
            'routes' => [
                'sessions' => route('admin.cerdas-cermat.sessions'),
                'qrBase' => url('/admin/cerdas-cermat/qr-questions'),
                'qrStore' => route('admin.cerdas-cermat.qr.store'),
                'qrTemplateCsv' => route('admin.cerdas-cermat.qr.template-csv'),
                'qrImportCsv' => route('admin.cerdas-cermat.qr.import-csv'),
                'qrPrintPdf' => route('admin.cerdas-cermat.qr.print-pdf'),
                'cipherBase' => url('/admin/cerdas-cermat/cipher-grids'),
                'cipherPreview' => route('admin.cerdas-cermat.cipher.preview'),
                'cipherStore' => route('admin.cerdas-cermat.cipher.store'),
                'cipherTemplateCsv' => route('admin.cerdas-cermat.cipher.template-csv'),
                'cipherImportWordsCsv' => route('admin.cerdas-cermat.cipher.import-words-csv'),
                'cipherPrintPdf' => route('admin.cerdas-cermat.cipher.print-pdf'),
                'coinRoyaleBase' => url('/admin/cerdas-cermat/coin-royale'),
                'coinRoyaleRounds' => route('admin.cerdas-cermat.coin-royale.rounds'),
                'coinRoyaleTemplateCsv' => route('admin.cerdas-cermat.coin-royale.template-csv'),
                'coinRoyaleImportCsv' => route('admin.cerdas-cermat.coin-royale.import-csv'),
                'settingsUpdate' => route('admin.cerdas-cermat.settings'),
            ],
        ];
    @endphp

    <div class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                id="cyber-quiz-root"
                data-component="AdminQuizPanel"
                data-payload-b64="{{ base64_encode(json_encode($adminPayload)) }}"
                v-pre
            >
                <div class="bg-white rounded-2xl p-10 text-center text-gray-500 font-semibold border border-gray-200">
                    Memuat Scout Cyber Quiz Arena Admin Builder...
                </div>
            </div>
        </div>
    </div>
@endsection
