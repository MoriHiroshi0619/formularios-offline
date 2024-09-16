<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarColunaLiberadoEmEFinalizadoEmNaTabelaFormulariosFormulario extends Migration
{
    public function up()
    {
        Schema::table('formularios_formulario', function (Blueprint $table) {
            $table->timestamp('liberado_em')->nullable();
            $table->timestamp('finalizado_em')->nullable();
        });
    }

    public function down()
    {
        Schema::table('formularios_formulario', function (Blueprint $table) {
            $table->dropColumn('liberado_em');
            $table->dropColumn('finalizado_em');
        });
    }
}
