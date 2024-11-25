<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;

class ResetUserPassword extends Command
{
    protected $signature = 'user:reset-password';

    protected $description = 'Reset a user\'s password';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $cpf = $this->ask('Digite o CPF do usuário que deseja redefinir a senha');

        if (strlen($cpf) != 11 || !is_numeric($cpf)) {
            $this->error('O CPF deve conter 11 dígitos numéricos');
            return 1;
        }

        $usuario = Usuario::query()->where('cpf', $cpf)->first();

        if (!$usuario) {
            $this->error('Usuário não encontrado com o CPF fornecido');
            return 1;
        }

        $this->info('Usuário ' . $usuario->nome . ' ' . $usuario->sobre_nome . ' encontrado');

        $password = $this->secret('Digite a nova senha para o usuário');
        $password2 = $this->secret('Digite a nova senha novamente');

        if ($password !== $password2) {
            $this->error('As senhas não coincidem');
            return 1;
        }

        $usuario->password = $password;
        $usuario->save();

        $this->info('Senha do usuário atualizada com sucesso!');
        return 0;
    }
}
