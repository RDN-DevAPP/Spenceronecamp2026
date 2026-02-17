<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Juri\JuriDashboardController;
use App\Http\Controllers\Juri\JuriScoreController;
use App\Http\Controllers\Peserta\PesertaDashboardController;
use App\Http\Controllers\Peserta\PesertaPosterController;
use App\Http\Controllers\InformasiLombaController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');
Route::get('/jadwal', \App\Http\Controllers\JadwalController::class)->name('jadwal');
Route::get('/informasi-lomba', InformasiLombaController::class)->name('informasi-lomba');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Juri & Admin
Route::middleware(['auth', 'juri'])->prefix('juri')->name('juri.')->group(function () {
    Route::get('/dashboard', [JuriDashboardController::class, 'index'])->name('dashboard');
    Route::get('/lomba/{slug}', [JuriDashboardController::class, 'scoreLomba'])->name('lomba.score');
    Route::get('/lomba/{slug}/regu/{reguId}', [JuriDashboardController::class, 'scoreRegu'])->name('lomba.score.regu');
    Route::post('/scores', [JuriScoreController::class, 'store'])->name('scores.store');
    Route::post('/scores/bulk', [JuriScoreController::class, 'storeBulk'])->name('scores.storeBulk');

    Route::get('/cerdas-cermat', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'index'])->name('cerdas-cermat.index');
    Route::get('/cerdas-cermat/qualifiers', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'qualifiers'])->name('cerdas-cermat.qualifiers');
    Route::post('/cerdas-cermat/{id}/verify-round-2', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'verifyRound2'])->name('cerdas-cermat.verify');
    Route::get('/cerdas-cermat/{session}/{round}', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'show'])->name('cerdas-cermat.show');
    Route::post('/cerdas-cermat/{session}/{round}/grade', [\App\Http\Controllers\Juri\JuriCerdasCermatController::class, 'grade'])->name('cerdas-cermat.grade');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
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

    Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);

    // Scores
    Route::delete('/scores/{score}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'destroy'])->name('scores.destroy');
});

// Peserta (Regu)
Route::middleware(['auth', 'regu'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/poster', [PesertaPosterController::class, 'upload'])->name('poster.upload');
    Route::get('/anggota/create', [PesertaDashboardController::class, 'createAnggota'])->name('anggota.create');
    Route::post('/anggota', [PesertaDashboardController::class, 'storeAnggota'])->name('anggota.store');
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

});
