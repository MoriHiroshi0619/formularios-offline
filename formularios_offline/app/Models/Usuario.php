<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'usuarios';
    protected $fillable = [
        'nome',
        'sobre_nome',
        'email',
        'senha',
        'cpf',
        'tipo'
    ];

    protected $hidden = [
        'password',
    ];
}
