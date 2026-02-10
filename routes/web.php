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

    /**
     * ✅ FILE VIEWER (UNTUK UNIT & PPK)
     * GET /file-viewer?file=...
     * (Controller sudah anti double-wrap + validasi /storage)
     */
    Route::get('/file-viewer', [UnitController::class, 'fileViewer'])
        ->name('file.viewer');

    /*
    |----------------------------------------------------------------------
    | UNIT ROUTES
    |----------------------------------------------------------------------
    */
    Route::prefix('unit')->name('unit.')->group(function () {

        Route::get('/dashboard', [UnitController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/dashboard/stats', [UnitController::class, 'dashboardStats'])
            ->name('dashboard.stats');

        Route::get('/dashboard/data', [UnitController::class, 'dashboardStats'])
            ->name('dashboard.data');

        Route::get('/arsip', [UnitController::class, 'arsipIndex'])
            ->name('arsip');

        Route::get('/arsippbj', [UnitController::class, 'arsipIndex'])
            ->name('arsippbj');

        Route::get('/arsip-pbj', [UnitController::class, 'arsipIndex'])
            ->name('arsip.pbj');

        Route::get('/arsip/{id}/edit', [UnitController::class, 'arsipEdit'])
            ->name('arsip.edit');

        Route::put('/arsip/{id}', [UnitController::class, 'arsipUpdate'])
            ->name('arsip.update');

        Route::delete('/arsip', [UnitController::class, 'arsipBulkDestroy'])
            ->name('arsip.bulkDestroy');

        Route::delete('/arsip/{id}', [UnitController::class, 'arsipDestroy'])
            ->name('arsip.destroy');

        Route::get('/pengadaan/tambah', [UnitController::class, 'pengadaanCreate'])
            ->name('pengadaan.create');

        Route::post('/pengadaan/store', [UnitController::class, 'pengadaanStore'])
            ->name('pengadaan.store');

        /**
         * ✅ LIHAT dokumen (INLINE) -> showDokumen akan redirect ke file.viewer dengan /storage/...
         */
        Route::get('/arsip/{id}/dokumen/{field}/{file}', [UnitController::class, 'showDokumen'])
            ->where(['field' => '[A-Za-z0-9_\-]+', 'file' => '.+'])
            ->name('arsip.dokumen.show');

        Route::delete('/arsip/{id}/dokumen', [UnitController::class, 'hapusDokumenFile'])
            ->name('arsip.dokumen.hapus');

        Route::get('/arsip/{id}/dokumen-download', [UnitController::class, 'downloadDokumen'])
            ->name('arsip.dokumen.download');

        Route::get('/kelola-akun', [UnitController::class, 'kelolaAkun'])
            ->name('kelola.akun');

        Route::put('/akun', [UnitController::class, 'updateAkun'])
            ->name('akun.update');
    });

    /*
    |----------------------------------------------------------------------
    | PPK ROUTES
    |----------------------------------------------------------------------
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

        /**
         * ✅ LIHAT dokumen PPK
         * PpkController@showDokumen juga harus redirect ke route('file.viewer', ['file' => '/storage/...'])
         */
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
