<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\PPK\PpkController;

/**
 * =========================
 * LANDING (GUEST)
 * =========================
 */
Route::view('/', 'Landing.Index')->name('landing');

/**
 * =========================
 * LOGIN (GET + POST)
 * =========================
 */
Route::middleware('guest')->group(function () {

    // halaman login
    Route::view('/login', 'Auth.login')->name('login');

    // proses login (✅ FIX: pakai email + password)
    Route::post('/login', function (Request $request) {

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember'); // checkbox "ingat kata sandi" (kalau ada)

        if (Auth::attempt([
    'email' => $request->email,
    'password' => $request->password,
], $remember)) {

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->role === 'ppk') {
        return redirect('/#ppk');
    }

    if ($user->role === 'unit') {
        return redirect('/#unit');
    }

    // fallback jika role tidak dikenali atau null
    Auth::logout();
    return redirect()->route('login')->with('error', 'Role tidak dikenali atau belum ditetapkan.');
}

        // kalau gagal login, balik ke form + param error (biar UI kamu tetap sama)
        return redirect()
            ->route('login', ['error' => 1])
            ->with('error', 'Username atau Password Salah!')
            ->withInput($request->only('email'));
    })->name('login.post');

});

/**
 * =========================
 * AFTER LOGIN REDIRECT (SMART)
 * =========================
 * /home jadi gerbang:
 * - role ppk  -> ppk.dashboard
 * - role unit -> unit.dashboard
 */
Route::get('/home', function () {
    $user = Auth::user();

    // kalau belum login, balik ke login
    if (!$user) return redirect()->route('login');

    // sesuaikan nama kolom role di user kamu
    if ($user->role === 'ppk') {
        return redirect()->route('ppk.dashboard');
    }

    if ($user->role === 'unit') {
        return redirect()->route('unit.dashboard');
    }

    // fallback kalau role tidak dikenali
    Auth::logout();
    return redirect()->route('login')->with('error', 'Role tidak dikenali.');
})->middleware('auth')->name('home');

/**
 * Default redirect setelah login (opsional)
 * kalau kamu pakai redirect ke /dashboard setelah login
 */
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

/**
 * =========================
 * LOGOUT
 * =========================
 */
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('landing');
})->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| PUBLIC DETAIL (kalau perlu)
|--------------------------------------------------------------------------
*/
Route::get('/arsip/{id}', function ($id) {
    return view('Landing.LihatDetail', compact('id'));
})->name('arsip.detail');


/*
|--------------------------------------------------------------------------
| UNIT ROUTES
|--------------------------------------------------------------------------
| Dashboard Unit: /unit/dashboard
*/
Route::prefix('unit')
    ->middleware(['auth', 'role:unit'])   // ✅ penting
    ->name('unit.')
    ->group(function () {

        // Dashboard Unit (jadi homepage setelah login)
        Route::get('/dashboard', [UnitController::class, 'dashboard'])->name('dashboard');

        // Arsip PBJ
        Route::get('/arsip', [UnitController::class, 'arsipIndex'])->name('arsip');
        Route::get('/arsip/{id}/edit', [UnitController::class, 'arsipEdit'])->name('arsip.edit');
        Route::put('/arsip/{id}', [UnitController::class, 'arsipUpdate'])->name('arsip.update');

        // Tambah Pengadaan
        Route::get('/pengadaan/tambah', [UnitController::class, 'pengadaanCreate'])->name('pengadaan.create');
        Route::post('/pengadaan/store', [UnitController::class, 'pengadaanStore'])->name('pengadaan.store');
    });


/*
|--------------------------------------------------------------------------
| PPK ROUTES
|--------------------------------------------------------------------------
| Dashboard PPK: /ppk/dashboard
*/
Route::prefix('ppk')
    ->middleware(['auth', 'role:ppk'])    // ✅ penting
    ->name('ppk.')
    ->group(function () {

        // Dashboard PPK (jadi homepage setelah login)
        Route::get('/dashboard', [PpkController::class, 'dashboard'])->name('dashboard');

        // Arsip PBJ
        Route::get('/arsip', [PpkController::class, 'arsipIndex'])->name('arsip');
        Route::get('/arsip/{id}/edit', [PpkController::class, 'arsipEdit'])->name('arsip.edit');
        Route::put('/arsip/{id}', [PpkController::class, 'arsipUpdate'])->name('arsip.update');

        // Tambah Pengadaan
        Route::get('/pengadaan/tambah', [PpkController::class, 'pengadaanCreate'])->name('pengadaan.create');
        Route::post('/pengadaan/store', [PpkController::class, 'pengadaanStore'])->name('pengadaan.store');
    });
