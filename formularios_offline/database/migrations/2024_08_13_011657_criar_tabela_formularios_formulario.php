<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaFormulariosFormulario extends Migration
{
    public function up()
    {
        Schema::create('formularios_formulario', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_formulario', 100)->nullable(false);
            $table->text('descricao_formulario')->nullable();
            $table->integer('usuario_id')->unsigned()->nullable(false);
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formularios_formulario');
    }
}
