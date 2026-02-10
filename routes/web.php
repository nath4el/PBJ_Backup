<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\PPK\PpkController;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'Landing.Index')->name('landing');

// Login (GET form, POST proses)
Route::get('/login', function () {
    return view('Auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->route('home');
    }

    return back()
        ->withErrors(['email' => 'Email atau kata sandi salah.'])
        ->withInput($request->only('email'));
})->name('login.post');

// Logout (POST)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout');

// Logout (GET fallback)
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout.get');

// Homepage & Preview
Route::view('/home', 'Home.index')->name('home');
Route::view('/home-preview', 'Home.index')->name('home.preview');

// Arsip Publik (Landing)
Route::view('/ArsipPBJ', 'Landing.pbj')->name('ArsipPBJ');
Route::view('/home/ArsipPBJ', 'Home.pbj')->name('home.pbj');

// Redirect alias lama
Route::redirect('/home/arsippbj', '/home/ArsipPBJ')->name('home.arsippbj');
Route::redirect('/home/arsip-pbj', '/home/ArsipPBJ');

// Detail arsip publik
Route::get('/arsip/{id}', function ($id) {
    return view('Landing.LihatDetail', compact('id'));
})->name('arsip.detail');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | UNIT ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('unit')->name('unit.')->group(function () {

        // Dashboard Unit
        Route::get('/dashboard', [UnitController::class, 'dashboard'])
            ->name('dashboard');

        /**
         * ✅ API Dashboard Unit (AJAX)
         * dipakai untuk filter tahun summary + chart
         * GET /unit/dashboard/stats?tahun=2025
         */
        Route::get('/dashboard/stats', [UnitController::class, 'dashboardStats'])
            ->name('dashboard.stats');

        /**
         * (opsional) jika sebelumnya sudah pakai /dashboard/data
         * GET /unit/dashboard/data?tahun=2025
         */
        Route::get('/dashboard/data', [UnitController::class, 'dashboardStats'])
            ->name('dashboard.data');

        /**
         * ✅ Arsip Unit (utama)
         * GET /unit/arsip
         */
        Route::get('/arsip', [UnitController::class, 'arsipIndex'])
            ->name('arsip');

        /**
         * ✅ Alias rute lama
         */
        Route::get('/arsippbj', [UnitController::class, 'arsipIndex'])
            ->name('arsippbj');

        Route::get('/arsip-pbj', [UnitController::class, 'arsipIndex'])
            ->name('arsip.pbj');

        /**
         * ✅ Edit & Update arsip
         */
        Route::get('/arsip/{id}/edit', [UnitController::class, 'arsipEdit'])
            ->name('arsip.edit');

        Route::put('/arsip/{id}', [UnitController::class, 'arsipUpdate'])
            ->name('arsip.update');

        /**
         * ✅ Hapus arsip
         */
        Route::delete('/arsip', [UnitController::class, 'arsipBulkDestroy'])
            ->name('arsip.bulkDestroy');

        Route::delete('/arsip/{id}', [UnitController::class, 'arsipDestroy'])
            ->name('arsip.destroy');

        /**
         * ✅ Tambah Pengadaan
         */
        Route::get('/pengadaan/tambah', [UnitController::class, 'pengadaanCreate'])
            ->name('pengadaan.create');

        Route::post('/pengadaan/store', [UnitController::class, 'pengadaanStore'])
            ->name('pengadaan.store');

        /**
         * ✅ Lihat/Download dokumen via controller
         */
        Route::get('/arsip/{id}/dokumen/{field}/{file}', [UnitController::class, 'showDokumen'])
            ->where(['field' => '[A-Za-z0-9_\-]+', 'file' => '.+'])
            ->name('arsip.dokumen.show');

        /**
         * ✅ Hapus dokumen file
         */
        Route::delete('/arsip/{id}/dokumen', [UnitController::class, 'hapusDokumenFile'])
            ->name('arsip.dokumen.hapus');

        /**
         * (opsional) endpoint download lama
         */
        Route::get('/arsip/{id}/dokumen-download', [UnitController::class, 'downloadDokumen'])
            ->name('arsip.dokumen.download');

        /**
         * ✅ Kelola akun Unit
         */
        Route::get('/kelola-akun', [UnitController::class, 'kelolaAkun'])
            ->name('kelola.akun');

        Route::put('/akun', [UnitController::class, 'updateAkun'])
            ->name('akun.update');
    });


    /*
    |--------------------------------------------------------------------------
    | PPK ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('ppk')->name('ppk.')->group(function () {

        Route::get('/dashboard', [PpkController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/arsip', [PpkController::class, 'arsipIndex'])
            ->name('arsip');

        Route::get('/arsip/{id}/edit', [PpkController::class, 'arsipEdit'])
            ->name('arsip.edit');

        Route::put('/arsip/{id}', [PpkController::class, 'arsipUpdate'])
            ->name('arsip.update');

        Route::get('/pengadaan/tambah', [PpkController::class, 'pengadaanCreate'])
            ->name('pengadaan.create');

        Route::post('/pengadaan/store', [PpkController::class, 'pengadaanStore'])
            ->name('pengadaan.store');

        Route::get('/arsip/{id}/dokumen/{field}/{file}', [PpkController::class, 'showDokumen'])
            ->where(['field' => '[A-Za-z0-9_\-]+', 'file' => '.+'])
            ->name('arsip.dokumen.show');

        Route::get('/arsip/{id}/dokumen-download', [PpkController::class, 'downloadDokumen'])
            ->name('arsip.dokumen.download');

        Route::get('/kelola-akun', [PpkController::class, 'kelolaAkun'])
            ->name('kelola.akun');

        Route::put('/akun', [PpkController::class, 'updateAkun'])
            ->name('akun.update');
    });

});
