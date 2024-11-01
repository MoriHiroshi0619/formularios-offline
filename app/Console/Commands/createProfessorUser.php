<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class createProfessorUser extends Command
{
    protected $signature = 'user:teacher';
    protected $description = 'Create a new default teacher user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $usuario = new \App\Models\Usuario();
        $usuario->fill([
            'nome' => 'Lach',
            'sobre_nome' => 'Ricardo',
            'cpf' => '00000000000',
            'tipo' => 'PROFESSOR',
            'password' => 'senha',
        ]);
        $usuario->save();
        $this->info('Teacher user created successfully!');
        return 0;
    }
}
