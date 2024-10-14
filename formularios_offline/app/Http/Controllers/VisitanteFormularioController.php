<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Formularios\MultiplaEscolha;
use App\Models\Respostas\FormularioResposta;
use App\Models\Respostas\resposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class VisitanteFormularioController extends Controller
{
    public function index(Request $request)
    {
        $formulariosRespondidos = json_decode($request->cookie('formularios_respondidos', '[]'), true);

        $formularios = Formulario::query()
            ->where('status', Formulario::LIBERADO)
            ->whereNotIn('id', $formulariosRespondidos)
            ->with('professor')
            ->paginate(10);

        return view('Visitantes.index', compact('formularios'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $formularioID = $request->input('formulario');
            $respostas = session()->get('respostas', []);

            if(!data_get($respostas, $formularioID)) throw new \Exception('Não foi possível verificar as respostas');

            $formulario = Formulario::query()
                ->where('id', $formularioID)
                ->where('status', Formulario::LIBERADO)
                ->firstOrFail();

            $nome =  data_get($respostas, $formularioID.'.nome', $request->input('aluno'));

            $formularioResposta = new FormularioResposta();
            $formularioResposta->fill(['nome_aluno' => $nome]);
            $formularioResposta->formulario()->associate($formulario);
            $formularioResposta->save();

            collect($respostas[$formularioID])->each(function ($resposta, $questaoId) use ($formularioResposta) {
                if ($questaoId === 'nome') return null;

                $respostaModel = new resposta();
                $respostaModel->questao()->associate($questaoId);
                $respostaModel->formularioResposta()->associate($formularioResposta);

                $tipo = FormularioQuestao::query()->where('id', $questaoId)->value('tipo');
                if ($tipo === FormularioQuestao::TEXTO_LIVRE) {
                    $respostaModel->fill(['resposta' => $resposta]);
                } else {
                    $opcaoMultiplaEscolha =  MultiplaEscolha::query()->where('id', $resposta)->first();
                    $respostaModel->resposta()->associate($opcaoMultiplaEscolha);
                }
                $respostaModel->save();
            });

            $formulariosRespondidos = json_decode($request->cookie('formularios_respondidos', '[]'), true);
            $formulariosRespondidos[] = $formularioID;

            DB::commit();

            session()->forget('respostas.' . $formularioID);
            session()->flash('success', 'Formulário enviado com sucesso');

            return response()->noContent()->cookie('formularios_respondidos', json_encode($formulariosRespondidos), 60 * 24 * 30);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao tentar enviar o formulário'], 500);
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

            $nomeAluno = $resposta['aluno'] ?? null;
            $questoesRespostas = $resposta['questoes'] ?? [];

            $respostasFormulario = data_get($respostasSalvas, $formularioId, []);

            if ($nomeAluno) {
                $respostasFormulario['nome'] = $nomeAluno;
            }

            foreach ($questoesRespostas as $questaoId => $respostaQuestao) {
                $respostasFormulario[$questaoId] = $respostaQuestao;
            }

            $respostasSalvas[$formularioId] = $respostasFormulario;

            session()->put('respostas', $respostasSalvas);

            return response()->noContent();
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface | \Exception $e) {
            return response()->json(['error' => 'Erro ao tentar salvar a resposta'], 500);
        }
    }
}
