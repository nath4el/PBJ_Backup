<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'landing.index')->name('landing');
Route::view('/login', 'auth.login')->name('login');
Route::view('/ppk/dashboard', 'ppk.dashboard');

Route::get('/arsip/{id}', function ($id) {
    return view('LihatDetail', compact('id'));
})->name('arsip.show');
Route::view('/unit/dashboard', 'unit.dashboard');

