<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaFormulariosQuestoes extends Migration
{
    public function up()
    {
        Schema::create('formularios_questoes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('questao')->nullable(false);
            $table->enum('tipo', [
                'TEXTO',
                'MULTIPLA_ESCOLHA',
            ])->default('TEXTO');
            $table->integer('formulario_id')->unsigned()->nullable(false);
            $table->foreign('formulario_id')->references('id')->on('formularios_formulario');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formularios_questoes');
    }
}
