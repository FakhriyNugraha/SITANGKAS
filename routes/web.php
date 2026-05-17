<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFuzzyIndicatorController;
use App\Http\Controllers\Admin\AdminImportController;
use App\Http\Controllers\Admin\AdminKnnTrainingController;
use App\Http\Controllers\Admin\AdminMaterialController;
use App\Http\Controllers\Admin\AdminResultController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\LevelController;
use App\Http\Controllers\User\MaterialController;
use App\Http\Controllers\User\SimulationController;
use App\Http\Controllers\User\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::get('/belajar', [LevelController::class, 'index'])->name('user.levels.index');
    Route::get('/belajar/{category}', [LevelController::class, 'show'])->name('user.levels.show');
    Route::post('/belajar/{category}/mulai', [LevelController::class, 'start'])->name('user.levels.start');

    Route::get('/simulations', fn () => redirect()->route('user.levels.index'))->name('user.simulations.index');
    Route::get('/simulations/{simulation}', [SimulationController::class, 'show'])->name('user.simulations.show');
    Route::post('/simulations/{simulation}/answer', [SimulationController::class, 'answer'])->name('user.simulations.answer');
    Route::get('/simulations/{simulation}/feedback/{case}', [SimulationController::class, 'feedback'])->name('user.simulations.feedback');
    Route::post('/simulations/{simulation}/next', [SimulationController::class, 'next'])->name('user.simulations.next');
    Route::get('/simulations/{simulation}/result', [SimulationController::class, 'result'])->name('user.simulations.result');

    Route::get('/materials', [MaterialController::class, 'index'])->name('user.materials.index');
    Route::get('/materials/{material:slug}', [MaterialController::class, 'show'])->name('user.materials.show');

    Route::get('/history', [HistoryController::class, 'index'])->name('user.history.index');
    Route::get('/history/{session}', [HistoryController::class, 'show'])->name('user.history.show');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/indicators', [AdminFuzzyIndicatorController::class, 'index'])->name('indicators.index');
    Route::get('/indicators/create', [AdminFuzzyIndicatorController::class, 'create'])->name('indicators.create');
    Route::post('/indicators', [AdminFuzzyIndicatorController::class, 'store'])->name('indicators.store');
    Route::get('/indicators/{indicator}/edit', [AdminFuzzyIndicatorController::class, 'edit'])->name('indicators.edit');
    Route::put('/indicators/{indicator}', [AdminFuzzyIndicatorController::class, 'update'])->name('indicators.update');
    Route::delete('/indicators/{indicator}', [AdminFuzzyIndicatorController::class, 'destroy'])->name('indicators.destroy');

    Route::get('/materials', [AdminMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [AdminMaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [AdminMaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}/edit', [AdminMaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{material}', [AdminMaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{material}', [AdminMaterialController::class, 'destroy'])->name('materials.destroy');

    Route::get('/knn-training', [AdminKnnTrainingController::class, 'index'])->name('knn.index');
    Route::get('/knn-training/create', [AdminKnnTrainingController::class, 'create'])->name('knn.create');
    Route::post('/knn-training', [AdminKnnTrainingController::class, 'store'])->name('knn.store');
    Route::get('/knn-training/{knn}/edit', [AdminKnnTrainingController::class, 'edit'])->name('knn.edit');
    Route::put('/knn-training/{knn}', [AdminKnnTrainingController::class, 'update'])->name('knn.update');
    Route::delete('/knn-training/{knn}', [AdminKnnTrainingController::class, 'destroy'])->name('knn.destroy');

    Route::get('/imports', [AdminImportController::class, 'index'])->name('imports.index');
    Route::post('/imports/cases', [AdminImportController::class, 'importCases'])->name('imports.cases');
    Route::post('/imports/indicators', [AdminImportController::class, 'importIndicators'])->name('imports.indicators');
    Route::post('/imports/knn', [AdminImportController::class, 'importKnn'])->name('imports.knn');

    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/results', [AdminResultController::class, 'index'])->name('results.index');
    Route::get('/results/{user}', [AdminResultController::class, 'show'])->name('results.show');
});
