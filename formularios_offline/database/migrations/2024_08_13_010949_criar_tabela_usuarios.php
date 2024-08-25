<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaUsuarios extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 30);
            $table->string('sobre_nome', 30);
            $table->string('email', 100)->unique();
            $table->string('cpf', 11)->unique()->nullable(false);
            $table->string('password');

            $table->enum('tipo', [
                'PROFESSOR',
                'ALUNO',
                'ADMIN',
            ])->default('ALUNO');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
