<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class createCustomAdmin extends Command
{
    protected $signature = 'admin:custom';
    protected $description = 'Create a new admin user in iterative mode';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $nome = $this->ask('Qual é o nome do administrador?');
        $sobreNome = $this->ask('Qual é o sobrenome do administrador?');
        $cpf = $this->ask('Qual é o CPF do administrador?');

        if(strlen($cpf) != 11 || !is_numeric($cpf)){
            $this->error('O CPF deve conter 11 dígitos numéricos');
            return 1;
        }

        $password = $this->secret('Digite a senha para o administrador');
        $password2 = $this->secret('Digite a senha novamente');

        if ($password !== $password2) {
            $this->error('As senhas não coincidem');
            return 1;
        }

        $usuario = new \App\Models\Usuario();
        $usuario->fill([
            'nome' => Str::title($nome),
            'sobre_nome' => Str::title($sobreNome),
            'cpf' => $cpf,
            'tipo' => 'ADMIN',
            'password' => $password,
        ]);
        $usuario->save();

        $this->info('Admin user created successfully!');
        return 0;
    }
}
