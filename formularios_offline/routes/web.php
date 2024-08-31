<?php

use Illuminate\Support\Facades\Route;


//Cadastrar Usuário
Route::prefix('usuarios')->name('usuarios.')->middleware('guest')->group(function () {
    Route::get('create', [\App\Http\Controllers\UsuarioController::class, 'create'])->name('create');
    Route::post('store', [\App\Http\Controllers\UsuarioController::class, 'store'])->name('store');
});

//LOGIN
Route::prefix('login')->name('login.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [\App\Http\Controllers\LoginController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
    });
    Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
    Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class)->except('create', 'store');

    Route::resource('formulario', \App\Http\Controllers\FormularioController::class)->middleware('professor');
});
