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
    public function index()
    {
        //todo: tentar controlar com cookies e user agent para desmostrar o formulario para alunos que já responderam
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
            $formularioID = $request->input('formulario');
            $respostas = session()->get('respostas', []);

            if(!data_get($respostas, $formularioID)) throw new \Exception('Não foi possivel verificar as respostas');

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
                if ( $questaoId === 'nome') return null;
                $respostaModel = new resposta();
                $respostaModel->questao()->associate($questaoId);
                $respostaModel->formularioResposta()->associate($formularioResposta);
                $tipo = FormularioQuestao::query()->where('id', $questaoId)->value('tipo');
                if ( $tipo === FormularioQuestao::TEXTO_LIVRE ){
                    $respostaModel->fill(['resposta' => $resposta]);
                } else {
                    $opcaoMultiplaEscolha =  MultiplaEscolha::query()->where('id', $resposta)->first();
                    $respostaModel->resposta()->associate($opcaoMultiplaEscolha);
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
            // Capturar a entrada de resposta
            $resposta = $request->input('resposta', []);
            $respostasSalvas = session()->get('respostas', []);

            if (!$resposta) return null;

            // Extrair nome e as questões da resposta
            $nomeAluno = $resposta['aluno'] ?? null;
            $questoesRespostas = $resposta['questoes'] ?? [];

            // Recuperar as respostas salvas do formulário específico
            $respostasFormulario = data_get($respostasSalvas, $formularioId, []);

            // Atualizar o nome do aluno se ele foi enviado
            if ($nomeAluno) {
                $respostasFormulario['nome'] = $nomeAluno;
            }

            // Atualizar as respostas de questões
            foreach ($questoesRespostas as $questaoId => $respostaQuestao) {
                $respostasFormulario[$questaoId] = $respostaQuestao;
            }

            // Salvar o estado atualizado das respostas
            $respostasSalvas[$formularioId] = $respostasFormulario;

            // Atualizar a sessão com as novas respostas
            session()->put('respostas', $respostasSalvas);

            return response()->noContent();
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface | \Exception $e) {
            return response()->json(['error' => 'Erro ao tentar salvar a resposta'], 500);
        }
    }
}
