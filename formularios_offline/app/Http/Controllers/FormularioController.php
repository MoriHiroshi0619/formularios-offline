<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Formularios\MultiplaEscolha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormularioController extends Controller
{
    public function index()
    {
        return view('formulario.index');
    }

    public function create()
    {
        return view('formulario.create');
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $formulario = new Formulario();
            $formulario->nome_formulario = $request->input('formulario.nome');
            $formulario->professor()->associate( auth()->user() );
            $formulario->save();
            $perguntas = collect($request->input('formulario.perguntas'));

            $perguntas->each( function ($pergunta) use ($formulario){
                $formulario_pergunta = new FormularioQuestao();
                $formulario_pergunta->fill([
                    'questao' => data_get($pergunta, 'texto'),
                    'tipo' => data_get($pergunta, 'tipo') === 'Texto livre' ? 'TEXTO' : 'MULTIPLA_ESCOLHA',
                ]);

                $formulario_pergunta->formulario()->associate($formulario);
                $formulario_pergunta->save();
                if(data_get($pergunta, 'tipo') === 'Multipla escolha'){
                    $opcoes = collect(data_get($pergunta, 'opcoes'));
                    $opcoes->each(function ($opcao) use ($formulario_pergunta){
                        $opcao_pergunta = new MultiplaEscolha();
                        $opcao_pergunta->opcao_resposta = $opcao;
                        $opcao_pergunta->questao()->associate($formulario_pergunta);
                        $opcao_pergunta->save();
                    });
                }
            });
            DB::commit();
            session()->flash('success', 'Formulário cadastrado com sucesso!');
            return response()->noContent();
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['error' => 'Erro ao tentar salvar o formulário'], 500);
        }
    }

    public function show(Request $request, $formularioId)
    {
        $formulario = Formulario::query()
            ->where('id', $formularioId)
            ->with('questoes.opcoesMultiplasEscolhas')
            ->first();
        return view('Formulario.show', compact('formulario'));
    }
}
