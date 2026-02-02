<?php

use Illuminate\Support\Facades\Route;

// 1 controller per role
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\PPK\PpkController;

Route::view('/', 'Landing.Landing')->name('landing');
Route::view('/login', 'Auth.login')->name('login');

// ====================
// DETAIL PUBLIK
// ====================
Route::get('/arsip/{id}', function ($id) {
    return view('LihatDetail', compact('id'));
})->name('arsip.show');

/*
|--------------------------------------------------------------------------
| UNIT ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('unit')
    ->name('unit.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UnitController::class, 'dashboard'])
            ->name('dashboard');

        // ====== ARSIP PBJ ======
        Route::get('/arsip', [UnitController::class, 'arsipIndex'])
            ->name('arsip');

        Route::get('/arsip/{id}/edit', [UnitController::class, 'arsipEdit'])
            ->name('arsip.edit');

        Route::put('/arsip/{id}', [UnitController::class, 'arsipUpdate'])
            ->name('arsip.update');

        // ====== TAMBAH PENGADAAN ======
        Route::get('/pengadaan/tambah', [UnitController::class, 'pengadaanCreate'])
            ->name('pengadaan.create');

        Route::post('/pengadaan/store', [UnitController::class, 'pengadaanStore'])
            ->name('pengadaan.store');
    });

/*
|--------------------------------------------------------------------------
| PPK ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('ppk')
    ->name('ppk.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PpkController::class, 'dashboard'])
            ->name('dashboard');

        // ====== ARSIP PBJ ======
        Route::get('/arsip', [PpkController::class, 'arsipIndex'])
            ->name('arsip');

        Route::get('/arsip/{id}/edit', [PpkController::class, 'arsipEdit'])
            ->name('arsip.edit');

        Route::put('/arsip/{id}', [PpkController::class, 'arsipUpdate'])
            ->name('arsip.update');

        // ====== TAMBAH PENGADAAN ======
        Route::get('/pengadaan/tambah', [PpkController::class, 'pengadaanCreate'])
            ->name('pengadaan.create');

        Route::post('/pengadaan/store', [PpkController::class, 'pengadaanStore'])
            ->name('pengadaan.store');
    });
