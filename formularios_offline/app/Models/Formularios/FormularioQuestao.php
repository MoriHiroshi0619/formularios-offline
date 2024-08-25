<?php

namespace App\Models\Formularios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormularioQuestao extends Model
{
    use SoftDeletes;

    protected $table = 'formularios_questoes';

    protected $fillable = [
        'questao',
        'tipo',
    ];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class);
    }

    public function opcoesMultiplasEscolhas()
    {
        return $this->hasMany(MultiplaEscolha::class);
    }
}
