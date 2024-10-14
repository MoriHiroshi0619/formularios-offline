<?php

use Illuminate\Support\Facades\Route;


//Cadastrar Usuário | Não será mais necessario
/*Route::prefix('usuarios')->name('usuarios.')->middleware('guest')->group(function () {
    Route::get('create', [\App\Http\Controllers\UsuarioController::class, 'create'])->name('create');
    Route::post('store', [\App\Http\Controllers\UsuarioController::class, 'store'])->name('store');
});*/

Route::prefix('visitantes')->name('visitantes.')->group(function () {
    Route::resource('formularios', \App\Http\Controllers\VisitanteFormularioController::class);
    Route::get('realizar-formulario/{formularioId}', [\App\Http\Controllers\VisitanteFormularioController::class, 'realizarFormulario'])->name('realizar-formulario');
    Route::post('salvar-questao-sessao/${id}', [\App\Http\Controllers\VisitanteFormularioController::class, 'salvarRespostasNaSessao'])->name('salvar-questao-sessao');

//    Route::get('limpar', [\App\Http\Controllers\VisitanteFormularioController::class, 'limparSessao'])->name('limpar');
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
    Route::get('resultado/{formularioId}/resposta/{respostaId}', [\App\Http\Controllers\ResultadoController::class, 'showAluno'])->name('resultado.show-aluno');
    Route::get('resultado/{formulario}/aluno/{respostaAluno}/gerar-pdf', [\App\Http\Controllers\ResultadoController::class, 'gerarPDF'])->name('resultado.gerar-pdf-aluno');
    Route::get('resultado/{formulario}/pdf', [\App\Http\Controllers\ResultadoController::class, 'gerarPdfRespostas'])->name('resultado.gerar-pdf-geral');
});
