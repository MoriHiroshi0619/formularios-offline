<?php

namespace App\Models\Formularios;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RespotaAluno extends Model
{
    use SoftDeletes;

    protected $table = 'perguntas_respostas';

    protected $fillable = [
        'resposta',
    ];

    public function questao()
    {
        return $this->belongsTo(FormularioQuestao::class);
    }

    public function respostaDaMultiplaEscolnha()
    {
        return $this->belongsTo(MultiplaEscolha::class, 'resposta_id');
    }

    public function aluno()
    {
        return $this->belongsTo(Usuario::class);
    }
}
