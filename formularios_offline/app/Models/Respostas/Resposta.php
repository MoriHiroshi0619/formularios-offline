<?php

namespace App\Models\Respostas;

use App\Models\Formularios\FormularioQuestao;
use App\Models\Formularios\MultiplaEscolha;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resposta extends Model
{
    use SoftDeletes;

    protected $table = 'respostas';

    protected $fillable = ['resposta'];

    public function formularioResposta()
    {
        return $this->belongsTo(FormularioResposta::class);
    }

    public function questao()
    {
        return $this->belongsTo(FormularioQuestao::class);
    }

    public function resposta()
    {
        return $this->belongsTo(MultiplaEscolha::class);
    }
}
