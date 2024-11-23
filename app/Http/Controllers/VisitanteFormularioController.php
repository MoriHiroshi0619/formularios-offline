<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Formularios\MultiplaEscolha;
use App\Models\Respostas\FormularioResposta;
use App\Models\Respostas\Resposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class VisitanteFormularioController extends Controller
{
    public function index(Request $request)
    {
        //$formulariosRespondidos = json_decode($request->cookie('formularios_respondidos', '[]'), true);

        $formularios = Formulario::query()
            ->where('status', Formulario::LIBERADO)
            /*->when($formulariosRespondidos, fn ($query, $formulariosRespondidos) =>
                $query->whereNotIn('id', $formulariosRespondidos)
            )*/
            ->with('professor')
            ->paginate(10);

        return view('Visitantes.index', compact('formularios'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $dados = $request->all();

            $formularioID = $dados['formulario'] ?? null;
            $respostas = $dados['respostas'] ?? [];

            if (!$formularioID || empty($respostas)) {
                throw new \Exception('Dados incompletos.');
            }

            $formulario = Formulario::query()
                ->where('id', $formularioID)
                ->where('status', Formulario::LIBERADO)
                ->firstOrFail();

            $nome = $formulario->anonimo ? null : ($dados['aluno'] ?? null);

            if (!$formulario->anonimo && !$nome) {
                throw new \Exception('Nome do aluno não informado.');
            }

            $formularioResposta = new FormularioResposta();
            $formularioResposta->fill(['nome_aluno' => $nome]);
            $formularioResposta->formulario()->associate($formulario);
            $formularioResposta->save();

            foreach ($respostas as $questaoId => $resposta) {
                $respostaModel = new Resposta();
                $respostaModel->questao()->associate($questaoId);
                $respostaModel->formularioResposta()->associate($formularioResposta);

                $tipo = FormularioQuestao::query()->where('id', $questaoId)->value('tipo');
                if ($tipo === FormularioQuestao::TEXTO_LIVRE) {
                    $respostaModel->fill(['resposta' => $resposta]);
                } else {
                    $opcaoMultiplaEscolha = MultiplaEscolha::query()->where('id', $resposta)->first();
                    if (!$opcaoMultiplaEscolha) {
                        throw new \Exception('Opção de múltipla escolha inválida.');
                    }
                    $respostaModel->resposta()->associate($opcaoMultiplaEscolha);
                }
                $respostaModel->save();
            }

            DB::commit();
            session()->flash('success', 'Formulário enviado com sucesso');
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function realizarFormulario($formularioId)
    {
        $formulario = Formulario::query()
            ->where('id', $formularioId)
            ->where('status', Formulario::LIBERADO)
            ->with(['questoes.opcoesMultiplasEscolhas'])
            ->firstOrFail();

        $questoes = $formulario->questoes()->get();

        return view('Visitantes.realizar-formulario-sem-paginar', compact('formulario', 'questoes'));
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

    public function revisarFormulario($formularioId)
    {
        $formulario = Formulario::with('questoes.opcoesMultiplasEscolhas')->findOrFail($formularioId);

        $respostasSalvas = session()->get('respostas.' . $formularioId, []);

        return view('Visitantes.revisar-formulario', compact('formulario', 'respostasSalvas'));
    }


}
