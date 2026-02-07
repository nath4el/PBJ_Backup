<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\PPK\PpkController;

/**
 * =========================
 * LANDING (GUEST)
 * =========================
 */
Route::view('/', 'Landing.Index')->name('landing');

// samakan penamaan view login (pilih salah satu yang bener di projectmu)
Route::view('/login', 'Auth.login')->name('login'); // kalau file: resources/views/Auth/Login.blade.php

/**
 * =========================
 * HOMEPAGE
 * =========================
 */
Route::view('/home', 'Home.index')->name('home');
Route::view('/home-preview', 'Home.index')->name('home.preview');

/**
 * =========================
 * PBJ (Publik & Home)
 * =========================
 * Landing: /ArsipPBJ       -> name: ArsipPBJ
 * Home   : /home/ArsipPBJ  -> name: home.pbj
 */
Route::view('/ArsipPBJ', 'Landing.pbj')->name('ArsipPBJ');
Route::view('/home/ArsipPBJ', 'Home.pbj')->name('home.pbj');

// Alias (biar link lama gak error)
Route::redirect('/home/arsippbj', '/home/ArsipPBJ')->name('home.arsippbj');
Route::redirect('/home/arsip-pbj', '/home/ArsipPBJ');

/**
 * =========================
 * DETAIL PUBLIK (Arsip)
 * =========================
 */
Route::get('/arsip/{id}', function ($id) {
    return view('Landing.LihatDetail', compact('id'));
})->name('arsip.detail');

/**
 * Default redirect setelah login
 */
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

/**
 * Logout
 */
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout');


/*
|--------------------------------------------------------------------------
| UNIT ROUTES (AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('unit')
    ->middleware('auth')
    ->name('unit.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UnitController::class, 'dashboard'])->name('dashboard');

        // ====== ARSIP PBJ ======
        Route::get('/arsip', [UnitController::class, 'arsipIndex'])->name('arsip');

        Route::get('/arsip/{id}/edit', [UnitController::class, 'arsipEdit'])->name('arsip.edit');

        Route::put('/arsip/{id}', [UnitController::class, 'arsipUpdate'])->name('arsip.update');

        // ====== TAMBAH PENGADAAN ======
        Route::get('/pengadaan/tambah', [UnitController::class, 'pengadaanCreate'])->name('pengadaan.create');

        Route::post('/pengadaan/store', [UnitController::class, 'pengadaanStore'])->name('pengadaan.store');
    });

/*
|--------------------------------------------------------------------------
| PPK ROUTES (AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('ppk')
    ->middleware('auth')
    ->name('ppk.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PpkController::class, 'dashboard'])->name('dashboard');

        // ====== ARSIP PBJ ======
        Route::get('/arsip', [PpkController::class, 'arsipIndex'])->name('arsip');

        Route::get('/arsip/{id}/edit', [PpkController::class, 'arsipEdit'])->name('arsip.edit');

        Route::put('/arsip/{id}', [PpkController::class, 'arsipUpdate'])->name('arsip.update');

        // ====== TAMBAH PENGADAAN ======
        Route::get('/pengadaan/tambah', [PpkController::class, 'pengadaanCreate'])->name('pengadaan.create');

        Route::post('/pengadaan/store', [PpkController::class, 'pengadaanStore'])->name('pengadaan.store');
    });
