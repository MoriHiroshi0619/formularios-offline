<?php

namespace App\Models\Respostas;

use App\Models\Formularios\Formulario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioResposta extends Model
{
    use SoftDeletes;

    protected $table = 'formulario_resposta';

    protected $fillable = ['nome_aluno'];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }
}
