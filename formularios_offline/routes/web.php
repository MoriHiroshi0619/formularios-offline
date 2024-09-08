<?php

use Illuminate\Support\Facades\Route;


//Cadastrar Usuário | Não será mais necessario
/*Route::prefix('usuarios')->name('usuarios.')->middleware('guest')->group(function () {
    Route::get('create', [\App\Http\Controllers\UsuarioController::class, 'create'])->name('create');
    Route::post('store', [\App\Http\Controllers\UsuarioController::class, 'store'])->name('store');
});*/

//todo: rotas de visitantes para prencher o formulario
//todo: corrigir redirecionamento de rota para visitante em vez de login em /Users/hiroshi/projeto_extensao/formularios_offline/app/Http/Middleware/Authenticate.php
Route::prefix('visitantes')->name('visitantes.')->middleware('guest')->group(function () {

});


//LOGIN
Route::prefix('login')->name('login.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [\App\Http\Controllers\LoginController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
    });
    Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'professor'])->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

    Route::resource('formulario', \App\Http\Controllers\FormularioController::class);
    Route::put('formulario/liberar/{id}', [\App\Http\Controllers\FormularioController::class, 'liberarFormulario'])->name('liberar-formulario');
    Route::put('formulario/encerrar/{id}', [\App\Http\Controllers\FormularioController::class, 'encerrarFormulario'])->name('encerrar-formulario');

    Route::resource('resultado', \App\Http\Controllers\ResultadoController::class);
});
