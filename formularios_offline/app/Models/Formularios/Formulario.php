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

    const CRIADO = "CRIADO";
    const LIBERADO = "LIBERADO";
    const FINALIZADO = "FINALIZADO";
    const STATUS = [
        self::CRIADO,
        self::LIBERADO,
        self::FINALIZADO
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

    public function isCriado()
    {
        if($this->status === $this::CRIADO){
            return true;
        }

        return false;
    }

    public function isLiberado()
    {
        if($this->status === $this::LIBERADO){
            return true;
        }

        return false;
    }

    public function isFinalizado()
    {
        if($this->status === $this::FINALIZADO){
            return true;
        }

        return false;
    }
}
