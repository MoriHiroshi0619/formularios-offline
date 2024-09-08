<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaRespostas extends Migration
{

    public function up()
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formulario_resposta_id')->unsigned()->nullable(false);
            $table->foreign('formulario_resposta_id')->references('id')->on('formulario_resposta');
            $table->integer('questao_id')->unsigned()->nullable(false);
            $table->foreign('questao_id')->references('id')->on('formularios_questoes');
            $table->integer('resposta_id')->unsigned()->nullable();
            $table->foreign('resposta_id')->references('id')->on('formularios_respostas_multiplas_escolhas');
            $table->text('resposta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('respostas');
    }
}
