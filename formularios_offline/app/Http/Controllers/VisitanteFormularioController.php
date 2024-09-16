<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Respostas\FormularioResposta;
use App\Models\Respostas\resposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class VisitanteFormularioController extends Controller
{
    public function index()
    {
        $formularios = Formulario::query()
            ->where('status', Formulario::LIBERADO)
            ->with('professor')
            ->paginate(10);

        return view('Visitantes.index', compact('formularios'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $formularioID = $request->input('formularioId');
            $respostas = session()->get('respostas', []);

            if(!data_get($respostas, $formularioID)) throw new \Exception('Não foi possivel verificar as respostas');

            $formulario = Formulario::query()
                ->where('id', $formularioID)
                ->where('status', Formulario::LIBERADO)
                ->firstOrFail();

            $nome = data_get($respostas, $formularioID.'.nome');

            $formularioResposta = new FormularioResposta();
            $formularioResposta->fill(['nome_aluno' => $nome]);
            $formularioResposta->formulario()->associate($formulario);
            $formularioResposta->save();

            collect($respostas[$formularioID])->each(function ($resposta, $questaoId) use ($formularioResposta) {
                if ( $questaoId === 'nome') return null;
                $respostaModel = new resposta();
                $respostaModel->questao()->associate($questaoId);
                $respostaModel->formularioResposta()->associate($formularioResposta);
                $tipo = FormularioQuestao::query()->where('id', $questaoId)->value('tipo');
                if ( $tipo === FormularioQuestao::TEXTO_LIVRE ){
                    $respostaModel->fill(['resposta' => $resposta]);
                } else {
                    $respostaModel->formularioResposta()->associate($formularioResposta);
                }
                $respostaModel->save();
            });

            session()->forget('respostas.'.$formularioID);
            DB::commit();
            session()->flash('success', 'Formulário enviado com sucesso');
            return response()->noContent();
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface | \Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['error' => 'Erro ao tentar enviar formulario'], 500);
        }
    }

    public function realizarFormulario($formularioId)
    {
        $formulario = Formulario::query()
            ->where('id', $formularioId)
            ->where('status', Formulario::LIBERADO)
            ->with('questoes.opcoesMultiplasEscolhas')
            ->firstOrFail();

        $questoes = $formulario->questoes()->paginate(1);

        return view('Visitantes.realizar-formulario', compact('formulario', 'questoes'));
    }

    public function salvarRespostasNaSessao(Request $request, $formularioId)
    {
        try {
            $resposta = $request->input('resposta', []);
            $respostasSalvas = session()->get('respostas', []);
            if (!$resposta) return null;

            $respostasFormulario = data_get($respostasSalvas, $formularioId, []);
            $respostasFormulario[$resposta['questao']] = $resposta['resposta'];
            $respostasFormulario['nome'] = $resposta['aluno'];

            $respostasSalvas[$formularioId] = $respostasFormulario;

            session()->put('respostas', $respostasSalvas);
            return response()->noContent();
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface | \Exception $e) {
            return response()->json(['error' => 'Erro ao tentar salvar a resposta'], 500);
        }
    }

    /*public function limparSessao(Request $request)
    {
        session()->forget('respostas');
    }*/
}
