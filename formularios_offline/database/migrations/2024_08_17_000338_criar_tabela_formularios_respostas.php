<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaFormulariosRespostas extends Migration
{
    public function up()
    {
        Schema::create('formularios_respostas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('resposta')->nullable(false);
            $table->integer('formulario_pergunta_id')->unsigned()->nullable(false);
            $table->foreign('formulario_pergunta_id')->references('id')->on('formularios_perguntas');
            $table->boolean('correta')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down()
    {
        Schema::dropIfExists('formularios_respostas');
    }
}
