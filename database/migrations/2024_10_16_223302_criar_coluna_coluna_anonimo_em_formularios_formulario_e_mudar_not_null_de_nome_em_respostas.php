<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarColunaColunaAnonimoEmFormulariosFormularioEMudarNotNullDeNomeEmRespostas extends Migration
{
    public function up()
    {
        Schema::table('formularios_formulario', function (Blueprint $table) {
            $table->boolean('anonimo')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('formularios_formulario', function (Blueprint $table) {
            $table->dropColumn('anonimo');
        });

    }
}
