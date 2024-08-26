<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class createAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create a new default admin user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $usuario = new \App\Models\Usuario();
        $usuario->fill([
            'nome' => 'admin',
            'sobre_nome' => 'admin',
            'email' => 'admin@gmail.com',
            'cpf' => '12345678901',
            'tipo' => 'ADMIN',
            'password' => bcrypt('admin'),
        ]);
        $usuario->save();
        $this->info('Admin user created successfully!');
        return 0;
    }
}
