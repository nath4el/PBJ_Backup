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

// Login Routes (GET untuk tampilkan form, POST untuk proses login)
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

        // Setelah login berhasil → langsung ke home (sesuai permintaanmu)
        return redirect()->route('home');
    }

    return back()
        ->withErrors(['email' => 'Email atau kata sandi salah.'])
        ->withInput($request->only('email'));
})->name('login');

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout');

// Homepage & Preview
Route::view('/home', 'Home.index')->name('home');
Route::view('/home-preview', 'Home.index')->name('home.preview');

// Arsip Publik
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

    // Jika ada akses ke /dashboard, redirect ke home (atau bisa dihapus jika tidak dipakai)
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | UNIT ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('unit')
        ->name('unit.')
        ->group(function () {
            Route::get('/dashboard', [UnitController::class, 'dashboard'])->name('dashboard');
            Route::get('/arsip', [UnitController::class, 'arsipIndex'])->name('arsip');
            Route::get('/arsip/{id}/edit', [UnitController::class, 'arsipEdit'])->name('arsip.edit');
            Route::put('/arsip/{id}', [UnitController::class, 'arsipUpdate'])->name('arsip.update');
            Route::get('/pengadaan/tambah', [UnitController::class, 'pengadaanCreate'])->name('pengadaan.create');
            Route::post('/pengadaan/store', [UnitController::class, 'pengadaanStore'])->name('pengadaan.store');
        });

    /*
    |--------------------------------------------------------------------------
    | PPK ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('ppk')
        ->name('ppk.')
        ->group(function () {
            Route::get('/dashboard', [PpkController::class, 'dashboard'])->name('dashboard');
            Route::get('/arsip', [PpkController::class, 'arsipIndex'])->name('arsip');
            Route::get('/arsip/{id}/edit', [PpkController::class, 'arsipEdit'])->name('arsip.edit');
            Route::put('/arsip/{id}', [PpkController::class, 'arsipUpdate'])->name('arsip.update');
            Route::get('/pengadaan/tambah', [PpkController::class, 'pengadaanCreate'])->name('pengadaan.create');
            Route::post('/pengadaan/store', [PpkController::class, 'pengadaanStore'])->name('pengadaan.store');
            // ✅ KELOLA AKUN (PPK)
            Route::get('/kelola-akun', [PpkController::class, 'kelolaAkun'])->name('kelola.akun');

            // ✅ SIMPAN PERUBAHAN AKUN (PPK)
            Route::put('/akun', [PpkController::class, 'updateAkun'])->name('akun.update');
        });
});