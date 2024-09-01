<?php

namespace App\Models\Formularios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultiplaEscolha extends Model
{
    use SoftDeletes;

    protected $table = 'formularios_respostas_multiplas_escolhas';

    protected $fillable = [
        'opcao_resposta',
    ];

    protected $casts = [
        'eh_a_correta' => 'boolean',
    ];

    public function questao()
    {
        return $this->belongsTo(FormularioQuestao::class, 'formulario_questao_id');
    }
}
