<?php

use Illuminate\Support\Facades\Route;

Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('create', [\App\Http\Controllers\UsuarioController::class, 'create'])->name('create');
    Route::post('store', [\App\Http\Controllers\UsuarioController::class, 'store'])->name('store');
});

//LOGIN
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/', [\App\Http\Controllers\LoginController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
    Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class)->except('create', 'store');
});
