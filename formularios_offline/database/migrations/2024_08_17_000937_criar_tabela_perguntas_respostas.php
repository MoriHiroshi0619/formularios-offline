<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaPerguntasRespostas extends Migration
{
    public function up()
    {
        Schema::create('perguntas_respostas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pergunta_id')->unsigned()->nullable(false);
            $table->foreign('pergunta_id')->references('id')->on('formularios_perguntas');
            $table->integer('resposta_id')->unsigned()->nullable();
            $table->foreign('resposta_id')->references('id')->on('formularios_respostas');
            $table->integer('usuario_id')->unsigned()->nullable(false);
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->string('resposta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {

    }
}
