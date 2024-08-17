<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaFormulariosRespostasMultiplasEscolhas extends Migration
{
    public function up()
    {
        Schema::create('formularios_respostas_multiplas_escolhas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('opcao_resposta')->nullable(false);
            $table->integer('formulario_questao_id')->unsigned()->nullable(false);
            $table->foreign('formulario_questao_id')->references('id')->on('formularios_questoes');
            $table->boolean('eh_a_correta')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down()
    {
        Schema::dropIfExists('formularios_respostas_multiplas_escolhas');
    }
}
