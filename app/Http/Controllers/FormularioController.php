<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Formularios\MultiplaEscolha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormularioController extends Controller
{
    public function index(Request $request)
    {
        $nomeFormulario = $request->input('nome_formulario', session('filtros.nome_formulario', ''));
        $status = $request->input('status', session('filtros.status', ''));

        session([
            'filtros.nome_formulario' => $nomeFormulario,
            'filtros.status' => $status,
        ]);

        $query = Formulario::query()->with('questoes');

        if ($nomeFormulario) {
            $query->where('nome_formulario', 'ilike', '%' . $nomeFormulario . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        $formularios = $query->orderBy('id', 'desc')->paginate(10);

        return view('Formulario.index', compact('formularios'));
    }


    public function create()
    {
        return view('Formulario.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $formulario = new Formulario();
            $formulario->nome_formulario = $request->input('formulario.nome');
            $formulario->anonimo = $request->boolean('formulario.anonimo');
            $formulario->professor()->associate(auth()->user());
            $formulario->save();

            $perguntas = collect($request->input('formulario.perguntas'));

            $perguntas->each(function ($pergunta) use ($formulario) {
                $textoPergunta = data_get($pergunta, 'texto');

                if (!isset($textoPergunta) || trim($textoPergunta) === '') {
                    return;
                }

                $formulario_pergunta = new FormularioQuestao();
                $formulario_pergunta->fill([
                    'questao' => $textoPergunta,
                    'tipo' => data_get($pergunta, 'tipo') === 'Texto livre' ? 'TEXTO' : 'MULTIPLA_ESCOLHA',
                ]);
                $formulario_pergunta->formulario()->associate($formulario);
                $formulario_pergunta->save();

                if (data_get($pergunta, 'tipo') === 'Multipla escolha') {
                    $opcoes = collect(data_get($pergunta, 'opcoes'));

                    if ($opcoes->filter(fn($opcao) => trim($opcao) !== '')->count() >= 2) {
                        $opcoes->each(function ($opcao) use ($formulario_pergunta) {
                            if (trim($opcao) !== '') {
                                $opcao_pergunta = new MultiplaEscolha();
                                $opcao_pergunta->opcao_resposta = $opcao;
                                $opcao_pergunta->questao()->associate($formulario_pergunta);
                                $opcao_pergunta->save();
                            }
                        });
                    }
                }
            });

            DB::commit();
            session()->flash('success', 'Formulário cadastrado com sucesso!');
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao tentar salvar o formulário'], 500);
        }
    }

    public function replicar(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $formularioParaReplicar = Formulario::query()->findOrFail($id);
            $formulario = $formularioParaReplicar->replicate(["status"]);
            $formulario->status = Formulario::CRIADO;
            $formulario->save();

            $formularioParaReplicar->questoes->each(function ($questao) use ($formulario) {
                $questaoReplicada = $questao->replicate();
                $questaoReplicada->formulario()->associate($formulario);
                $questaoReplicada->save();

                if ($questao->tipo === 'MULTIPLA_ESCOLHA') {
                    $questao->opcoesMultiplasEscolhas->each(function ($opcao) use ($questaoReplicada) {
                        $opcaoReplicada = $opcao->replicate();
                        $opcaoReplicada->questao()->associate($questaoReplicada);
                        $opcaoReplicada->save();
                    });
                }
            });
            DB::commit();
            session()->flash('success', 'Formulário replicado com sucesso!');
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao tentar replicar o formulário'], 500);
        }
    }

    public function show($formularioId)
    {
        $formulario = Formulario::query()
            ->where('id', $formularioId)
            ->with('questoes.opcoesMultiplasEscolhas')
            ->first();
        return view('Formulario.show', compact('formulario'));
    }

    public function destroy($formularioId)
    {
        try{
            DB::beginTransaction();
            MultiplaEscolha::query()
                ->whereHas('questao', fn ($query) => $query->where('formulario_id', $formularioId))
                ->delete();

            FormularioQuestao::query()
                ->where('formulario_id', $formularioId)
                ->delete();

            Formulario::query()->findOrFail($formularioId)->delete();
            DB::commit();
            session()->flash('success', 'Formulário excluído com sucesso!');
            return response()->noContent();
        }catch (\Exception $e){
            dd($e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'Erro ao tentar excluir o formulário'], 500);
        }
    }

    public function liberarFormulario($formularioId)
    {
        $formulario = Formulario::query()->findOrFail($formularioId);
        $formulario->status = Formulario::LIBERADO;
        $formulario->liberado_em = now();
        $formulario->save();
        session()->flash('success', 'Formulário liberado com sucesso!');
        return response()->noContent();
    }

    public function encerrarFormulario($formularioId)
    {
        $formulario = Formulario::query()->findOrFail($formularioId);
        $formulario->status = Formulario::FINALIZADO;
        $formulario->finalizado_em = now();
        $formulario->save();
        session()->flash('success', 'Formulário encerrado com sucesso!');
        return response()->noContent();
    }
}
