<?php

namespace App\Models\Formularios;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formulario extends Model
{
    use SoftDeletes;

    protected $table = 'formularios_formulario';

    protected $fillable = [
        'nome_formulario',
        'descricao_formulario',
    ];

    public function professor()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function questoes()
    {
        return $this->hasMany(FormularioQuestao::class);
    }

    public function respostas()
    {
        return $this->hasMany(RespotaAluno::class);
    }
}
