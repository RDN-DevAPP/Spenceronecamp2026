<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Juri\JuriDashboardController;
use App\Http\Controllers\Juri\JuriScoreController;
use App\Http\Controllers\Peserta\PesertaDashboardController;
use App\Http\Controllers\Peserta\PesertaPosterController;
use App\Http\Controllers\InformasiLombaController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SponsorshipRegistrationController;
use App\Http\Controllers\ReguRegistrationController;
use App\Http\Controllers\JuriRegistrationController;
use App\Http\Controllers\DaftarReguController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');
Route::get('/jadwal', \App\Http\Controllers\JadwalController::class)->name('jadwal');
Route::get('/informasi-lomba', InformasiLombaController::class)->name('informasi-lomba');
Route::get('/sponsorship/daftar', [SponsorshipRegistrationController::class, 'create'])->name('sponsorship.daftar');
Route::post('/sponsorship/daftar', [SponsorshipRegistrationController::class, 'store'])->name('sponsorship.store');
Route::get('/formulir-panitia', [\App\Http\Controllers\PanitiaRegistrationController::class, 'create'])->name('panitia.form');
Route::post('/formulir-panitia', [\App\Http\Controllers\PanitiaRegistrationController::class, 'store'])->name('panitia.store');
Route::get('/formulir-panitia/{panitia}/preview', [\App\Http\Controllers\PanitiaRegistrationController::class, 'preview'])->name('panitia.preview');
Route::get('/formulir-panitia/{panitia}/download-pdf', [\App\Http\Controllers\PanitiaRegistrationController::class, 'downloadPdf'])->name('panitia.download-pdf');
Route::get('/formulir-panitia/{panitia}/print', [\App\Http\Controllers\PanitiaRegistrationController::class, 'printSingle'])->name('panitia.print');

// Public Pages
Route::get('/daftar-regu', DaftarReguController::class)->name('daftar-regu');
Route::get('/verifikasi-regu/{regu}', [\App\Http\Controllers\Peserta\PesertaDashboardController::class, 'verifikasiRegu'])->name('verifikasi-regu');
Route::get('/verifikasi-panitia/{id}', [\App\Http\Controllers\PanitiaRegistrationController::class, 'verify'])->name('verifikasi-panitia');
Route::get('/verifikasi-anggota/{id}', function($id) {
    $anggota = \App\Models\AnggotaRegu::with('reguProfile')->findOrFail($id);
    $regu = $anggota->reguProfile;
    return view('verifikasi-anggota', compact('anggota', 'regu'));
})->name('verifikasi-anggota');
Route::get('/verifikasi-sertifikat', [\App\Http\Controllers\VerifikasiSertifikatController::class, 'index'])->name('verifikasi-sertifikat.index');
Route::get('/verifikasi-sertifikat/{token}', [\App\Http\Controllers\VerifikasiSertifikatController::class, 'show'])->name('verifikasi-sertifikat');
Route::get('/live-display', [\App\Http\Controllers\LiveDisplayController::class, 'index'])->name('live-display');
Route::get('/panggung', [\App\Http\Controllers\LiveDisplayController::class, 'index'])->name('panggung');
Route::get('/api/live-display/data', [\App\Http\Controllers\LiveDisplayController::class, 'getData'])->name('api.live-display.data');
Route::post('/live-display/vote/{regu}', [\App\Http\Controllers\LiveDisplayController::class, 'vote'])->name('live-display.vote');
Route::post('/live-display/vote/{regu}/paid', [\App\Http\Controllers\LiveDisplayController::class, 'paidVote'])->name('live-display.vote.paid');
Route::get('/live-display/vote/paid/{transactionCode}/status', [\App\Http\Controllers\LiveDisplayController::class, 'checkPaidVoteStatus'])->name('live-display.vote.paid.status');
Route::post('/live-display/vote/paid/{transactionCode}/sandbox-simulate', [\App\Http\Controllers\LiveDisplayController::class, 'simulateDanaSandbox'])->name('live-display.vote.paid.simulate');
Route::post('/api/dana/webhook', [\App\Http\Controllers\LiveDisplayController::class, 'danaWebhook'])->name('api.dana.webhook');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register-regu', [ReguRegistrationController::class, 'create'])->name('register.regu');
    Route::post('/register-regu', [ReguRegistrationController::class, 'store'])->name('register.regu.submit');
    Route::get('/register-juri', [JuriRegistrationController::class, 'create'])->name('register.juri');
    Route::post('/register-juri', [JuriRegistrationController::class, 'store'])->name('register.juri.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Juri & Admin
Route::middleware(['auth', 'juri'])->prefix('juri')->name('juri.')->group(function () {
    Route::get('/dashboard', [JuriDashboardController::class, 'index'])->name('dashboard');
    Route::get('/lomba/{slug}', [JuriDashboardController::class, 'scoreLomba'])->name('lomba.score');
    Route::get('/lomba/{slug}/regu/{reguId}', [JuriDashboardController::class, 'scoreRegu'])->name('lomba.score.regu');
    Route::get('/lembar-pbb/cetak', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'print'])->name('pbb-rubrik.print');
    Route::get('/lembar-pbb/scan', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'scan'])->name('pbb-rubrik.scan');
    Route::post('/lembar-pbb/simpan-nilai-scan', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'saveScannedScore'])->name('pbb-rubrik.save-scan');
    Route::post('/scores', [JuriScoreController::class, 'store'])->name('scores.store');
    Route::post('/scores/bulk', [JuriScoreController::class, 'storeBulk'])->name('scores.storeBulk');
    Route::post('/scores/{score}/finalize', [JuriScoreController::class, 'finalizeScore'])->name('scores.finalize');
    Route::post('/scores/{score}/request-unlock', [JuriScoreController::class, 'requestUnlock'])->name('scores.request-unlock');
    Route::post('/scores/{score}/approve', [JuriScoreController::class, 'approveDelete'])->name('scores.approve');
    Route::post('/scores/{score}/reject', [JuriScoreController::class, 'rejectDelete'])->name('scores.reject');

    Route::get('/cerdas-cermat', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'index'])->name('cerdas-cermat.index');
    Route::get('/cerdas-cermat/state', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'state'])->name('cerdas-cermat.state');
    Route::post('/cerdas-cermat/round-status/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'setRoundStatus'])->name('cerdas-cermat.round-status');
    Route::post('/cerdas-cermat/coin-royale/reset-all', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'coinRoyaleResetAll'])->name('cerdas-cermat.coin-royale.reset-all');
    Route::post('/cerdas-cermat/coin-royale/{roundNumber}/question', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'coinRoyaleUpdateQuestion'])->name('cerdas-cermat.coin-royale.question');
    Route::post('/cerdas-cermat/coin-royale/{roundNumber}/open-betting', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'coinRoyaleOpenBetting'])->name('cerdas-cermat.coin-royale.open-betting');
    Route::post('/cerdas-cermat/coin-royale/{roundNumber}/lock-betting', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'coinRoyaleLockBetting'])->name('cerdas-cermat.coin-royale.lock-betting');
    Route::post('/cerdas-cermat/coin-royale/{roundNumber}/open-question', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'coinRoyaleOpenQuestion'])->name('cerdas-cermat.coin-royale.open-question');
    Route::post('/cerdas-cermat/coin-royale/{roundNumber}/grade', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'coinRoyaleGradeRound'])->name('cerdas-cermat.coin-royale.grade');
    Route::get('/cerdas-cermat/qualifiers', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'qualifiers'])->name('cerdas-cermat.qualifiers');
    Route::get('/cerdas-cermat/violations', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'getViolations'])->name('cerdas-cermat.violations');
    Route::post('/cerdas-cermat/finalize-round/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'finalizeRound'])->name('cerdas-cermat.finalize-round');
    Route::post('/cerdas-cermat/start-round/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'startRound'])->name('cerdas-cermat.start-round');
    Route::post('/cerdas-cermat/reset-round/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'resetRound'])->name('cerdas-cermat.reset-round');
    Route::post('/cerdas-cermat/select-finalists', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'selectFinalists'])->name('cerdas-cermat.select-finalists');
    Route::post('/cerdas-cermat/qualifiers/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'updateQualifiers'])->name('cerdas-cermat.update-qualifiers');
    Route::post('/cerdas-cermat/generate-unlock-code', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'generateUnlockCode'])->name('cerdas-cermat.generate-unlock-code');
    Route::get('/cerdas-cermat/{session}/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'show'])->name('cerdas-cermat.show');
    Route::post('/cerdas-cermat/{session}/{round}/grade', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'grade'])->name('cerdas-cermat.grade');
    Route::post('/cerdas-cermat/{session}/{round}/finalize', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'finalize'])->name('cerdas-cermat.finalize');
    Route::post('/cerdas-cermat/{id}/reset-session', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'resetSession'])->name('cerdas-cermat.reset-session');
    Route::post('/cerdas-cermat/{session}/instant-unlock', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'instantUnlock'])->name('cerdas-cermat.instant-unlock');
    Route::delete('/cerdas-cermat/{id}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'destroy'])->name('cerdas-cermat.destroy');

    // Rekap Nilai (Accessible by Juri)
    Route::get('/rekap-nilai', [\App\Http\Controllers\Admin\AdminRekapNilaiController::class, 'index'])->name('rekap-nilai.index');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('admin.permission:dashboard')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/toggle-reveal-juara-umum', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'toggleRevealJuaraUmum'])->name('toggle.reveal');
        Route::post('/toggle-show-financial-report', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'toggleShowFinancialReport'])->name('toggle.financial-report');
        Route::post('/toggle-combine-gender', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'toggleCombineGender'])->name('toggle.combine-gender');
    });

    Route::middleware('admin.permission:sponsorships')->group(function () {
        Route::get('sponsorships/settings', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'settings'])->name('sponsorships.settings');
        Route::post('sponsorships/settings', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'updateSettings'])->name('sponsorships.settings.update');
        Route::post('sponsorships/{sponsorship}/approve', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'approve'])->name('sponsorships.approve');
        Route::resource('sponsorships', \App\Http\Controllers\Admin\AdminSponsorshipController::class);
    });

    // Cerdas Cermat
    Route::middleware('admin.permission:cerdas_cermat')->group(function () {
        // Babak 1: QR-Hunt Trivia
        Route::post('cerdas-cermat/qr-questions', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'storeQrQuestion'])->name('cerdas-cermat.qr.store');
        Route::post('cerdas-cermat/qr-questions/import-csv', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'importQrCsv'])->name('cerdas-cermat.qr.import-csv');
        Route::get('cerdas-cermat/qr-questions/template-csv', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'downloadQrTemplateCsv'])->name('cerdas-cermat.qr.template-csv');
        Route::post('cerdas-cermat/qr-questions/{qrQuestion}/update', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'updateQrQuestion'])->name('cerdas-cermat.qr.update');
        Route::post('cerdas-cermat/qr-questions/{qrQuestion}/reset', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'resetQrQuestion'])->name('cerdas-cermat.qr.reset');
        Route::delete('cerdas-cermat/qr-questions/{qrQuestion}', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'destroyQrQuestion'])->name('cerdas-cermat.qr.destroy');
        Route::get('cerdas-cermat/qr-questions/print-pdf', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'printQrPdf'])->name('cerdas-cermat.qr.print-pdf');

        // Babak 2: Cipher Word-Search
        Route::post('cerdas-cermat/cipher-grids/preview', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'previewCipherGrid'])->name('cerdas-cermat.cipher.preview');
        Route::post('cerdas-cermat/cipher-grids/import-words-csv', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'importCipherWordsCsv'])->name('cerdas-cermat.cipher.import-words-csv');
        Route::get('cerdas-cermat/cipher-grids/template-csv', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'downloadCipherTemplateCsv'])->name('cerdas-cermat.cipher.template-csv');
        Route::post('cerdas-cermat/cipher-grids', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'storeCipherGrid'])->name('cerdas-cermat.cipher.store');
        Route::put('cerdas-cermat/cipher-grids/{cipherGrid}', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'updateCipherGrid'])->name('cerdas-cermat.cipher.update');
        Route::post('cerdas-cermat/cipher-grids/{cipherGrid}/reset', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'resetCipherGridWords'])->name('cerdas-cermat.cipher.reset');
        Route::delete('cerdas-cermat/cipher-grids/{cipherGrid}', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'destroyCipherGrid'])->name('cerdas-cermat.cipher.destroy');
        Route::get('cerdas-cermat/cipher-grids/print-pdf/{cipherGrid?}', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'printCipherPdf'])->name('cerdas-cermat.cipher.print-pdf');

        // Babak 3: Coin Royale Setup
        Route::post('cerdas-cermat/coin-royale/rounds', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'saveCoinRoyaleRounds'])->name('cerdas-cermat.coin-royale.rounds');
        Route::post('cerdas-cermat/coin-royale/{roundNumber}/image', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'uploadCoinRoyaleImage'])->name('cerdas-cermat.coin-royale.upload-image');
        Route::delete('cerdas-cermat/coin-royale/{roundNumber}/image', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'deleteCoinRoyaleImage'])->name('cerdas-cermat.coin-royale.delete-image');
        Route::post('cerdas-cermat/coin-royale/import-csv', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'importCoinRoyaleCsv'])->name('cerdas-cermat.coin-royale.import-csv');
        Route::get('cerdas-cermat/coin-royale/template-csv', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'downloadCoinRoyaleTemplateCsv'])->name('cerdas-cermat.coin-royale.template-csv');

        Route::post('cerdas-cermat/import', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'import'])->name('cerdas-cermat.import');
        Route::delete('cerdas-cermat/destroy-all', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'destroyAll'])->name('cerdas-cermat.destroyAll');

        // Session Management (Reset Scores)
        Route::get('cerdas-cermat/sessions', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'sessions'])->name('cerdas-cermat.sessions');
        Route::delete('cerdas-cermat/sessions/destroy-all', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'destroyAllSessions'])->name('cerdas-cermat.sessions.destroyAll');
        Route::delete('cerdas-cermat/sessions/{id}', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'resetSession'])->name('cerdas-cermat.resetSession');
        Route::post('cerdas-cermat/settings', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'updateSettings'])->name('cerdas-cermat.settings');

        Route::resource('cerdas-cermat', \App\Http\Controllers\Admin\AdminCerdasCermatController::class)
            ->parameters(['cerdas-cermat' => 'question']);
    });

    // Informasi Lomba & Kriteria
    Route::middleware('admin.permission:informasi_lomba')->group(function () {
        Route::get('mata-lomba/{mataLomba}/kriteria', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'show'])->name('kriteria.show');
        Route::get('mata-lomba/{mataLomba}/kriteria/create', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'create'])->name('kriteria.create');
        Route::post('mata-lomba/{mataLomba}/kriteria', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'store'])->name('kriteria.store');

        Route::get('kriteria/{kriteria}/edit', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'edit'])->name('kriteria.edit');
        Route::put('kriteria/{kriteria}', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'update'])->name('kriteria.update');
        Route::delete('kriteria/{kriteria}', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'destroy'])->name('kriteria.destroy');

        Route::put('mata-lomba/{mata_lomba}', [\App\Http\Controllers\Admin\AdminMataLombaController::class, 'update'])->name('mata-lomba.update');
        Route::delete('mata-lomba/{mata_lomba}', [\App\Http\Controllers\Admin\AdminMataLombaController::class, 'destroy'])->name('mata-lomba.destroy');

        Route::get('informasi-lomba', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'index'])->name('informasi-lomba.index');
        Route::get('informasi-lomba/create', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'create'])->name('informasi-lomba.create');
        Route::post('informasi-lomba', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'store'])->name('informasi-lomba.store');
        Route::get('informasi-lomba/{mataLomba}/edit', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'edit'])->name('informasi-lomba.edit');
        Route::put('informasi-lomba/{mataLomba}', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'update'])->name('informasi-lomba.update');
        Route::delete('informasi-lomba/{mataLomba}', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'destroy'])->name('informasi-lomba.destroy');
    });

    // Khusus Admin Utama (Super Admin) - Manajemen Pengguna
    Route::middleware('admin.permission:users')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);
        Route::put('users/{user}/mata-lomba', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateMataLomba'])->name('users.update-mata-lomba');
    });

    // Scores
    Route::middleware('admin.permission:scores')->group(function () {
        Route::get('/scores', [\App\Http\Controllers\Admin\AdminScoreController::class, 'index'])->name('scores.index');
        Route::post('/scores/{score}/approve-unlock', [\App\Http\Controllers\Admin\AdminScoreController::class, 'approveUnlock'])->name('scores.approve-unlock');
        Route::post('/scores/{score}/reject-unlock', [\App\Http\Controllers\Admin\AdminScoreController::class, 'rejectUnlock'])->name('scores.reject-unlock');
        Route::post('/scores/{score}/verify', [\App\Http\Controllers\Admin\AdminScoreController::class, 'verify'])->name('scores.verify');
        Route::delete('/scores/{score}', [\App\Http\Controllers\Admin\AdminScoreController::class, 'destroy'])->name('scores.destroy');
    });

    // Jadwal
    Route::middleware('admin.permission:jadwal')->group(function () {
        Route::post('jadwal/settings', [\App\Http\Controllers\Admin\AdminJadwalController::class, 'updateSettings'])->name('jadwal.settings');
        Route::resource('jadwal', \App\Http\Controllers\Admin\AdminJadwalController::class);
    });

    // Laporan Keuangan
    Route::middleware('admin.permission:financial_reports')->group(function () {
        Route::resource('financial-reports', \App\Http\Controllers\Admin\AdminFinancialReportController::class);
    });

    // Daftar Siswa
    Route::middleware('admin.permission:siswa')->group(function () {
        Route::get('siswa', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'index'])->name('siswa.index');
        Route::post('siswa', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'store'])->name('siswa.store');
        Route::post('siswa/import-csv', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'importCsv'])->name('siswa.import-csv');
        Route::put('siswa/{siswa}', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'update'])->name('siswa.update');
        Route::delete('siswa/delete-all/{kelas}', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'deleteAll'])->name('siswa.delete-all');
        Route::delete('siswa/{siswa}', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    // Rekap Nilai Admin & Export Dokumen Resmi PDF
    Route::middleware('admin.permission:rekap_nilai')->group(function () {
        Route::get('rekap-nilai', [\App\Http\Controllers\Admin\AdminRekapNilaiController::class, 'index'])->name('rekap-nilai.index');
        Route::get('export/berita-acara', [\App\Http\Controllers\Admin\AdminPdfExportController::class, 'beritaAcara'])->name('export.berita-acara');
        Route::get('export/piagam/{reguId?}', [\App\Http\Controllers\Admin\AdminPdfExportController::class, 'piagamPenghargaan'])->name('export.piagam');
    });

    // Cetak ID Card (Admin Only)
    Route::middleware('admin.permission:id_cards')->group(function () {
        Route::get('id-cards', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'index'])->name('id-cards.index');
        Route::get('id-cards/print-all', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'printAll'])->name('id-cards.print-all');
        Route::get('id-cards/panitia/print-all', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'printPanitiaAll'])->name('id-cards.panitia.print-all');
        Route::post('id-cards/panitia', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'storePanitia'])->name('id-cards.panitia.store');
        Route::put('id-cards/panitia/{panitia}', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'updatePanitia'])->name('id-cards.panitia.update');
        Route::delete('id-cards/panitia/{panitia}', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'destroyPanitia'])->name('id-cards.panitia.destroy');
        Route::get('id-cards/panitia/{panitia}/print', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'printPanitiaSingle'])->name('id-cards.panitia.print');
        Route::get('id-cards/{regu}/print', [\App\Http\Controllers\Admin\AdminIdCardController::class, 'print'])->name('id-cards.print');
    });

    // Verifikasi QR Code ID Card (Admin Only)
    Route::middleware('admin.permission:verifikasi_qr')->group(function () {
        Route::get('verifikasi-qr', [\App\Http\Controllers\Admin\AdminVerifikasiQrController::class, 'index'])->name('verifikasi-qr.index');
        Route::get('verifikasi-qr/lookup', [\App\Http\Controllers\Admin\AdminVerifikasiQrController::class, 'lookup'])->name('verifikasi-qr.lookup');
        Route::post('verifikasi-qr/check-in/{id}', [\App\Http\Controllers\Admin\AdminVerifikasiQrController::class, 'toggleCheckIn'])->name('verifikasi-qr.check-in');
        Route::post('verifikasi-qr/cancel-check-in/{id}', [\App\Http\Controllers\Admin\AdminVerifikasiQrController::class, 'cancelCheckIn'])->name('verifikasi-qr.cancel-check-in');
    });

    // Lembar Penilaian Juri PBB (Admin dapat mengedit setiap poin)
    Route::middleware('admin.permission:pbb_rubrik')->group(function () {
        Route::get('lembar-pbb', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'index'])->name('pbb-rubrik.index');
        Route::put('lembar-pbb', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'update'])->name('pbb-rubrik.update');
        Route::post('lembar-pbb/muat-template', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'loadDefault'])->name('pbb-rubrik.load-default');
        Route::get('lembar-pbb/cetak', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'print'])->name('pbb-rubrik.print');
        Route::get('lembar-pbb/scan', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'scan'])->name('pbb-rubrik.scan');
        Route::post('lembar-pbb/simpan-nilai-scan', [\App\Http\Controllers\Admin\AdminPbbRubrikController::class, 'saveScannedScore'])->name('pbb-rubrik.save-scan');
    });

    // Manajemen Sertifikat Peserta, Panitia & Juara Lomba
    Route::middleware('admin.permission:sertifikat')->group(function () {
        Route::get('sertifikat', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'peserta'])->name('sertifikat.index');
        Route::get('sertifikat/peserta', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'peserta'])->name('sertifikat.peserta');
        Route::post('sertifikat/settings', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'updateSettings'])->name('sertifikat.update-settings');
        Route::get('sertifikat/download-single/{anggota}', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadSingle'])->name('sertifikat.download-single');
        Route::get('sertifikat/download-regu/{regu}', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadRegu'])->name('sertifikat.download-regu');
        Route::get('sertifikat/download-sample', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadSample'])->name('sertifikat.download-sample');
        Route::get('sertifikat/panitia/download-all', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadPanitiaAll'])->name('sertifikat.panitia.all');
        Route::get('sertifikat/panitia/{panitia}/download', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadPanitiaSingle'])->name('sertifikat.panitia.single');
        Route::get('sertifikat/juara/download', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadJuaraLomba'])->name('sertifikat.juara.download');
        Route::get('sertifikat/juara/lomba/{mataLomba}/download-all', [\App\Http\Controllers\Admin\AdminSertifikatController::class, 'downloadJuaraLombaAll'])->name('sertifikat.juara.lomba-all');
    });

    // Data Voting Regu Terfavorit (Admin Only)
    Route::middleware('admin.permission:voting')->group(function () {
        Route::get('voting', [\App\Http\Controllers\Admin\AdminVotingController::class, 'index'])->name('voting.index');
        Route::post('voting/settings', [\App\Http\Controllers\Admin\AdminVotingController::class, 'updateSettings'])->name('voting.update-settings');
        Route::post('voting/reset-free', [\App\Http\Controllers\Admin\AdminVotingController::class, 'resetFreeVotes'])->name('voting.reset-free');
        Route::post('voting/transactions/{transaction}/approve', [\App\Http\Controllers\Admin\AdminVotingController::class, 'approveTransaction'])->name('voting.transactions.approve');
        Route::post('voting/transactions/{transaction}/sync', [\App\Http\Controllers\Admin\AdminVotingController::class, 'syncTransactionStatus'])->name('voting.transactions.sync');
        Route::post('voting/transactions/{transaction}/reject', [\App\Http\Controllers\Admin\AdminVotingController::class, 'rejectTransaction'])->name('voting.transactions.reject');
        Route::delete('voting/transactions/{transaction}', [\App\Http\Controllers\Admin\AdminVotingController::class, 'destroyTransaction'])->name('voting.transactions.destroy');
        Route::delete('voting/destroy-all', [\App\Http\Controllers\Admin\AdminVotingController::class, 'destroyAll'])->name('voting.destroy-all');
        Route::delete('voting/{vote}', [\App\Http\Controllers\Admin\AdminVotingController::class, 'destroy'])->name('voting.destroy');
    });
});

// Peserta (Regu)
Route::middleware(['auth', 'regu'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/anggota/create', [PesertaDashboardController::class, 'createAnggota'])->name('anggota.create');
    Route::post('/anggota', [PesertaDashboardController::class, 'storeAnggota'])->name('anggota.store');
    Route::get('/anggota/{id}/edit', [PesertaDashboardController::class, 'editAnggota'])->name('anggota.edit');
    Route::put('/anggota/{id}', [PesertaDashboardController::class, 'updateAnggota'])->name('anggota.update');
    Route::delete('/anggota/{id}', [PesertaDashboardController::class, 'destroyAnggota'])->name('anggota.destroy');
    Route::post('/anggota/{id}/upload-foto', [PesertaDashboardController::class, 'uploadAnggotaFoto'])->name('anggota.upload-foto');
    Route::delete('/anggota/{id}/delete-foto', [PesertaDashboardController::class, 'deleteAnggotaFoto'])->name('anggota.delete-foto');

    // Photo & Poster Uploads
    Route::post('/foto-regu', [PesertaDashboardController::class, 'uploadFotoRegu'])->name('foto-regu.upload');
    Route::delete('/foto-regu', [PesertaDashboardController::class, 'deleteFotoRegu'])->name('foto-regu.delete');
    Route::post('/upload-photo', [PesertaDashboardController::class, 'uploadPhoto'])->name('upload.photo');
    Route::delete('/delete-photo', [PesertaDashboardController::class, 'deletePhoto'])->name('photo.delete');
    Route::post('/photo-description', [PesertaDashboardController::class, 'updateDescription'])->name('photo.description');
    Route::post('/poster', [PesertaPosterController::class, 'upload'])->name('poster.upload');

    // Portofolio Karya Lomba (Hasta Karya, Tapak Kemah, Masak Rimba)
    Route::get('/hasta-karya', [\App\Http\Controllers\Peserta\PesertaKaryaController::class, 'hastaKarya'])->name('hasta-karya');
    Route::post('/karya/{slug}', [\App\Http\Controllers\Peserta\PesertaKaryaController::class, 'storeOrUpdate'])->name('karya.store');
    Route::delete('/karya/{slug}', [\App\Http\Controllers\Peserta\PesertaKaryaController::class, 'destroy'])->name('karya.destroy');

    // ID Card Digital
    Route::get('/id-cards', [\App\Http\Controllers\Peserta\PesertaDashboardController::class, 'idCards'])->name('id-cards');

    // Cerdas Cermat
    Route::get('/cerdas-cermat', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'index'])->name('cerdas-cermat.index');
    Route::post('/cerdas-cermat/register', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'register'])->name('cerdas-cermat.register');
    Route::get('/cerdas-cermat/round-1', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'round1'])->name('cerdas-cermat.round-1');
    Route::post('/cerdas-cermat/round-1/scan', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'scanQr'])->name('cerdas-cermat.round-1.scan');
    Route::post('/cerdas-cermat/round-1/answer', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'answerQr'])->name('cerdas-cermat.round-1.answer');
    Route::post('/cerdas-cermat/round-1', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'submitRound1'])->name('cerdas-cermat.round-1.submit');
    Route::get('/cerdas-cermat/round-2', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'round2'])->name('cerdas-cermat.round-2');
    Route::post('/cerdas-cermat/round-2/claim', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'claimCipherWord'])->name('cerdas-cermat.round-2.claim');
    Route::post('/cerdas-cermat/round-2', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'submitRound2'])->name('cerdas-cermat.round-2.submit');
    Route::get('/cerdas-cermat/round-3', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'round3'])->name('cerdas-cermat.round-3');
    Route::post('/cerdas-cermat/round-3/bet', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'placeCoinRoyaleBet'])->name('cerdas-cermat.round-3.bet');
    Route::post('/cerdas-cermat/round-3', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'submitRound3'])->name('cerdas-cermat.round-3.submit');
    Route::get('/cerdas-cermat/check-status', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'checkStatus'])->name('cerdas-cermat.check-status');
    Route::post('/cerdas-cermat/autosave', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'autosave'])->name('cerdas-cermat.autosave');
    Route::post('/cerdas-cermat/verify-unlock-code', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'verifyUnlockCode'])->name('cerdas-cermat.verify-unlock-code');
    Route::post('/cerdas-cermat/report-violation', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'reportViolation'])->name('cerdas-cermat.report-violation');

    // Sistem Administrasi Regu (6 Buku Pokok)
    Route::prefix('administrasi')->name('administrasi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'index'])->name('index');
        
        // 1. Buku Anggota
        Route::get('/buku-anggota', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'bukuAnggota'])->name('buku-anggota');
        Route::put('/buku-anggota/{id}', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'updateAnggotaDetail'])->name('buku-anggota.update');
        Route::get('/buku-anggota/print', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'printBukuAnggota'])->name('buku-anggota.print');

        // 2. Buku Keuangan
        Route::get('/buku-keuangan', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'bukuKeuangan'])->name('buku-keuangan');
        Route::post('/buku-keuangan', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'storeKeuangan'])->name('buku-keuangan.store');
        Route::delete('/buku-keuangan/{id}', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'destroyKeuangan'])->name('buku-keuangan.destroy');
        Route::get('/buku-keuangan/print', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'printBukuKeuangan'])->name('buku-keuangan.print');

        // 3. Buku Logbook / Jurnal Kegiatan
        Route::get('/buku-logbook', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'bukuLogbook'])->name('buku-logbook');
        Route::post('/buku-logbook', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'storeLogbook'])->name('buku-logbook.store');
        Route::delete('/buku-logbook/{id}', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'destroyLogbook'])->name('buku-logbook.destroy');
        Route::get('/buku-logbook/print', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'printBukuLogbook'])->name('buku-logbook.print');

        // 4. Buku Notulen Rapat
        Route::get('/buku-notulen', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'bukuNotulen'])->name('buku-notulen');
        Route::post('/buku-notulen', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'storeNotulen'])->name('buku-notulen.store');
        Route::delete('/buku-notulen/{id}', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'destroyNotulen'])->name('buku-notulen.destroy');
        Route::get('/buku-notulen/print', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'printBukuNotulen'])->name('buku-notulen.print');

        // 5. Buku Presensi
        Route::get('/buku-presensi', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'bukuPresensi'])->name('buku-presensi');
        Route::post('/buku-presensi', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'storePresensi'])->name('buku-presensi.store');
        Route::delete('/buku-presensi/{id}', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'destroyPresensi'])->name('buku-presensi.destroy');
        Route::get('/buku-presensi/print', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'printBukuPresensi'])->name('buku-presensi.print');

        // 6. Buku Inventaris
        Route::get('/buku-inventaris', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'bukuInventaris'])->name('buku-inventaris');
        Route::post('/buku-inventaris', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'storeInventaris'])->name('buku-inventaris.store');
        Route::delete('/buku-inventaris/{id}', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'destroyInventaris'])->name('buku-inventaris.destroy');
        Route::get('/buku-inventaris/print', [\App\Http\Controllers\Peserta\PesertaAdministrasiController::class, 'printBukuInventaris'])->name('buku-inventaris.print');
    });

});
