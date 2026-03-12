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

// Public Pages
Route::get('/daftar-regu', DaftarReguController::class)->name('daftar-regu');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register-regu', [ReguRegistrationController::class, 'create'])->name('register.regu');
    Route::post('/register-regu', [ReguRegistrationController::class, 'store'])->name('register.regu.submit');
    Route::get('/register-juri', [JuriRegistrationController::class, 'create'])->name('register.juri');
    Route::post('/register-juri', [JuriRegistrationController::class, 'store'])->name('register.juri.submit');
    Route::get('/api/randomize-regu/{id}/members', [\App\Http\Controllers\Admin\AdminRandomizeReguController::class, 'getTeamMembers'])->name('api.randomize-regu.members');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Juri & Admin
Route::middleware(['auth', 'juri'])->prefix('juri')->name('juri.')->group(function () {
    Route::get('/dashboard', [JuriDashboardController::class, 'index'])->name('dashboard');
    Route::get('/lomba/{slug}', [JuriDashboardController::class, 'scoreLomba'])->name('lomba.score');
    Route::get('/lomba/{slug}/regu/{reguId}', [JuriDashboardController::class, 'scoreRegu'])->name('lomba.score.regu');
    Route::post('/scores', [JuriScoreController::class, 'store'])->name('scores.store');
    Route::post('/scores/bulk', [JuriScoreController::class, 'storeBulk'])->name('scores.storeBulk');
    Route::post('/scores/{score}/approve', [JuriScoreController::class, 'approveDelete'])->name('scores.approve');
    Route::post('/scores/{score}/reject', [JuriScoreController::class, 'rejectDelete'])->name('scores.reject');

    Route::get('/cerdas-cermat', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'index'])->name('cerdas-cermat.index');
    Route::get('/cerdas-cermat/qualifiers', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'qualifiers'])->name('cerdas-cermat.qualifiers');
    Route::post('/cerdas-cermat/{id}/verify-round-2', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'verifyRound2'])->name('cerdas-cermat.verify');
    Route::get('/cerdas-cermat/{session}/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'show'])->name('cerdas-cermat.show');
    Route::post('/cerdas-cermat/{session}/{round}/grade', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'grade'])->name('cerdas-cermat.grade');
    Route::post('/cerdas-cermat/{session}/{round}/finalize', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'finalize'])->name('cerdas-cermat.finalize');
    Route::post('/cerdas-cermat/start-round/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'startRound'])->name('cerdas-cermat.start-round');
    Route::post('/cerdas-cermat/reset-round/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'resetRound'])->name('cerdas-cermat.reset-round');
    Route::post('/cerdas-cermat/{id}/reset-session', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'resetSession'])->name('cerdas-cermat.reset-session');
    Route::delete('/cerdas-cermat/destroy-all', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'destroyAll'])->name('cerdas-cermat.destroy-all');
    Route::delete('/cerdas-cermat/{id}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'destroy'])->name('cerdas-cermat.destroy');

    // Rekap Nilai (Accessible by Juri)
    Route::get('/rekap-nilai', [\App\Http\Controllers\Admin\AdminRekapNilaiController::class, 'index'])->name('rekap-nilai.index');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('sponsorships/{sponsorship}/approve', [\App\Http\Controllers\Admin\AdminSponsorshipController::class, 'approve'])->name('sponsorships.approve');
    Route::resource('sponsorships', \App\Http\Controllers\Admin\AdminSponsorshipController::class);

    // Cerdas Cermat
    Route::post('cerdas-cermat/import', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'import'])->name('cerdas-cermat.import');
    Route::delete('cerdas-cermat/destroy-all', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'destroyAll'])->name('cerdas-cermat.destroyAll');

    // Session Management (Reset Scores)
    Route::get('cerdas-cermat/sessions', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'sessions'])->name('cerdas-cermat.sessions');
    Route::delete('cerdas-cermat/sessions/{id}', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'resetSession'])->name('cerdas-cermat.resetSession');
    Route::post('cerdas-cermat/settings', [\App\Http\Controllers\Admin\AdminCerdasCermatController::class, 'updateSettings'])->name('cerdas-cermat.settings');

    Route::resource('cerdas-cermat', \App\Http\Controllers\Admin\AdminCerdasCermatController::class)
        ->parameters(['cerdas-cermat' => 'question']);

    Route::get('mata-lomba/{mataLomba}/kriteria', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'show'])->name('kriteria.show');
    Route::get('mata-lomba/{mataLomba}/kriteria/create', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'create'])->name('kriteria.create');
    Route::post('mata-lomba/{mataLomba}/kriteria', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'store'])->name('kriteria.store');

    Route::get('kriteria/{kriteria}/edit', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'edit'])->name('kriteria.edit');
    Route::put('kriteria/{kriteria}', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('kriteria/{kriteria}', [\App\Http\Controllers\Admin\AdminKriteriaController::class, 'destroy'])->name('kriteria.destroy');

    Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);
    Route::put('users/{user}/mata-lomba', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateMataLomba'])->name('users.update-mata-lomba');

    // Mata Lomba management
    Route::put('mata-lomba/{mata_lomba}', [\App\Http\Controllers\Admin\AdminMataLombaController::class, 'update'])->name('mata-lomba.update');
    Route::delete('mata-lomba/{mata_lomba}', [\App\Http\Controllers\Admin\AdminMataLombaController::class, 'destroy'])->name('mata-lomba.destroy');

    // Scores
    Route::get('/scores', [\App\Http\Controllers\Admin\AdminScoreController::class, 'index'])->name('scores.index');
    Route::delete('/scores/{score}', [\App\Http\Controllers\Admin\AdminScoreController::class, 'destroy'])->name('scores.destroy');
    Route::post('/toggle-reveal-juara-umum', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'toggleRevealJuaraUmum'])->name('toggle.reveal');
    Route::post('/toggle-show-financial-report', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'toggleShowFinancialReport'])->name('toggle.financial-report');

    // Informasi Lomba
    Route::get('informasi-lomba', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'index'])->name('informasi-lomba.index');
    Route::get('informasi-lomba/{mataLomba}/edit', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'edit'])->name('informasi-lomba.edit');
    Route::put('informasi-lomba/{mataLomba}', [\App\Http\Controllers\Admin\AdminInformasiLombaController::class, 'update'])->name('informasi-lomba.update');

    // Jadwal
    Route::post('jadwal/settings', [\App\Http\Controllers\Admin\AdminJadwalController::class, 'updateSettings'])->name('jadwal.settings');
    Route::resource('jadwal', \App\Http\Controllers\Admin\AdminJadwalController::class);

    // Laporan Keuangan
    Route::resource('financial-reports', \App\Http\Controllers\Admin\AdminFinancialReportController::class);

    // Daftar Siswa
    Route::get('siswa', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'index'])->name('siswa.index');
    Route::post('siswa', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'store'])->name('siswa.store');
    Route::post('siswa/import-csv', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'importCsv'])->name('siswa.import-csv');
    Route::put('siswa/{siswa}', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'update'])->name('siswa.update');
    Route::delete('siswa/delete-all/{kelas}', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'deleteAll'])->name('siswa.delete-all');
    Route::delete('siswa/{siswa}', [\App\Http\Controllers\Admin\AdminSiswaController::class, 'destroy'])->name('siswa.destroy');

    // Pengacakan Regu
    Route::get('randomize-regu', [\App\Http\Controllers\Admin\AdminRandomizeReguController::class, 'index'])->name('randomize-regu.index');
    Route::post('randomize-regu', [\App\Http\Controllers\Admin\AdminRandomizeReguController::class, 'randomize'])->name('randomize-regu.randomize');
    Route::delete('randomize-regu', [\App\Http\Controllers\Admin\AdminRandomizeReguController::class, 'reset'])->name('randomize-regu.reset');

    // Rekap Nilai Admin
    Route::get('rekap-nilai', [\App\Http\Controllers\Admin\AdminRekapNilaiController::class, 'index'])->name('rekap-nilai.index');
});

// Peserta (Regu)
Route::middleware(['auth', 'regu'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/poster', [PesertaPosterController::class, 'upload'])->name('poster.upload');
    Route::get('/anggota/{id}/edit', [PesertaDashboardController::class, 'editAnggota'])->name('anggota.edit');
    Route::put('/anggota/{id}', [PesertaDashboardController::class, 'updateAnggota'])->name('anggota.update');
    Route::delete('/anggota/{id}', [PesertaDashboardController::class, 'destroyAnggota'])->name('anggota.destroy');
    Route::post('/upload-photo', [PesertaDashboardController::class, 'uploadPhoto'])->name('upload.photo');
    Route::delete('/delete-photo', [PesertaDashboardController::class, 'deletePhoto'])->name('photo.delete');

    // Cerdas Cermat
    Route::get('/cerdas-cermat', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'index'])->name('cerdas-cermat.index');
    Route::post('/cerdas-cermat/register', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'register'])->name('cerdas-cermat.register');
    Route::get('/cerdas-cermat/round-1', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'round1'])->name('cerdas-cermat.round-1');
    Route::post('/cerdas-cermat/round-1', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'submitRound1'])->name('cerdas-cermat.round-1.submit');
    Route::get('/cerdas-cermat/round-2', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'round2'])->name('cerdas-cermat.round-2');
    Route::post('/cerdas-cermat/round-2', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'submitRound2'])->name('cerdas-cermat.round-2.submit');
    Route::get('/cerdas-cermat/round-3', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'round3'])->name('cerdas-cermat.round-3');
    Route::post('/cerdas-cermat/round-3', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'submitRound3'])->name('cerdas-cermat.round-3.submit');
    Route::get('/cerdas-cermat/check-status', [\App\Http\Controllers\Peserta\PesertaCerdasCermatController::class, 'checkStatus'])->name('cerdas-cermat.check-status');

});
