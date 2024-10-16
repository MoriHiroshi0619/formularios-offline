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
        'password',
        'cpf',
        'tipo'
    ];

    protected $hidden = [
        'password',
    ];

    const string PROFESSOR = "PROFESSOR";

    const string ADMIN = "ADMIN";
    const array TIPO = [
        self::PROFESSOR,
        self::ADMIN
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function setNomeAttribute($nome)
    {
        $this->attributes['nome'] = string()->title($nome);
    }

    public function setSobreNomeAttribute($sobrenome)
    {
        $this->attributes['sobre_nome'] = string()->title($sobrenome);
    }

    public function setCpfAttribute($cpf)
    {
        $this->attributes['cpf'] = string()->replace(['.', '-'], '', $cpf);
    }

    public function isProfessor():bool
    {
        if($this->tipo === $this::PROFESSOR){
            return true;
        }

        return false;
    }

    public function isAdmin():bool
    {
        if($this->tipo === $this::ADMIN){
            return true;
        }

        return false;
    }

}
