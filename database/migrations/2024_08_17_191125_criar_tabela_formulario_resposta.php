<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaFormularioResposta extends Migration
{
    public function up()
    {
        Schema::create('formulario_resposta', function (Blueprint $table) {
            $table->increments('id');
            $table->text("nome_aluno")->nullable(true);
            $table->integer('formulario_id')->unsigned()->nullable(false);
            $table->foreign('formulario_id')->references('id')->on('formularios_formulario');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('respostas_alunos');
    }
}
