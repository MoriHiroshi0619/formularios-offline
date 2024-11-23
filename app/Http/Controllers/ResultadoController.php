<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Formularios\FormularioQuestao;
use App\Models\Respostas\FormularioResposta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ResultadoController extends Controller
{
    public function index()
    {
        $formulariosFinalizados = \App\Models\Formularios\Formulario::query()
            ->where('status', 'FINALIZADO')
            ->orderBy('finalizado_em', 'desc')
            ->paginate(10);

        return view('Resultados.index', compact('formulariosFinalizados'));
    }

    public function show(Request $request, $id)
    {
        // Carregar o formulário finalizado
        $formulario = Formulario::query()->with('questoes')
            ->where('id', $id)
            ->where('status', Formulario::FINALIZADO)
            ->firstOrFail();

        // Buscar respostas de alunos para o formulário
        $respostas = FormularioResposta::query()->where('formulario_id', $id)
            ->paginate(10);

        return view('Resultados.formulario-show', compact('formulario', 'respostas'));
    }

    public function showAluno(Request $request, $formularioId, $respostaId)
    {
        // Carrega o formulário com as questões e respostas
        $formulario = Formulario::query()->with('questoes')
            ->where('id', $formularioId)
            ->firstOrFail();

        // Carrega as respostas do aluno específico
        $respostaAluno = FormularioResposta::query()->with('respostas.questao')
            ->where('formulario_id', $formularioId)
            ->where('id', $respostaId)
            ->firstOrFail();

        return view('Resultados.aluno-show', compact('formulario', 'respostaAluno'));
    }

    public function exibirRelatorioEstatistico(Request $request, $formularioId)
    {
        $formulario = Formulario::with('questoes.opcoesMultiplasEscolhas')->findOrFail($formularioId);
        $respostas = FormularioResposta::with('respostas.questao', 'respostas.resposta')->where('formulario_id', $formularioId)->get();

        $overview = [];

        Carbon::setLocale('pt_BR');

        // Detalhes do Formulário
        $overview['nome_formulario'] = $formulario->nome_formulario;
        $overview['data_criacao'] = $formulario->created_at;
        $overview['data_ultima_liberacao'] = $formulario->liberado_em;
        $overview['data_ultimo_encerramento'] = $formulario->finalizado_em;
        $overview['anonimo'] = $formulario->anonimo;

        // Duração Ativa
        $inicio = $formulario->created_at;
        $fim = $formulario->finalizado_em ?? now();

        $overview['duracao_ativa'] = $inicio->diffForHumans($fim, [
            'parts' => 3,
            'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
        ]);

        // Estatísticas de Respostas
        $overview['total_respostas'] = $respostas->count();
        $overview['data_primeira_resposta'] = $respostas->min('created_at');
        $overview['data_ultima_resposta'] = $respostas->max('created_at');

        // Estatísticas de Questões
        $overview['total_questoes'] = $formulario->questoes->count();
        $overview['questoes_multipla_escolha'] = $formulario->questoes->where('tipo', FormularioQuestao::MULTIPLA_ESCOLHA)->count();
        $overview['questoes_texto_livre'] = $formulario->questoes->where('tipo', FormularioQuestao::TEXTO_LIVRE)->count();

        $estatisticas = [];
        $respostasTexto = [];
        $nuvemPalavras = [];

        foreach ($formulario->questoes as $questao) {
            if ($questao->tipo === FormularioQuestao::MULTIPLA_ESCOLHA) {
                foreach ($questao->opcoesMultiplasEscolhas as $opcao) {
                    $estatisticas[$questao->id][$opcao->id] = [
                        'opcao_resposta' => $opcao->opcao_resposta,
                        'quantidade' => 0,
                    ];
                }
            } elseif ($questao->tipo === FormularioQuestao::TEXTO_LIVRE) {
                $respostasTexto[$questao->id] = [];
                $nuvemPalavras[$questao->id] = [];
            }
        }

        foreach ($respostas as $resposta) {
            foreach ($resposta->respostas as $respostaQuestao) {
                if ($respostaQuestao->questao->tipo === FormularioQuestao::MULTIPLA_ESCOLHA) {
                    if (isset($estatisticas[$respostaQuestao->questao_id][$respostaQuestao->resposta_id])) {
                        $estatisticas[$respostaQuestao->questao_id][$respostaQuestao->resposta_id]['quantidade']++;
                    }
                } elseif ($respostaQuestao->questao->tipo === FormularioQuestao::TEXTO_LIVRE) {
                    $respostasTexto[$respostaQuestao->questao_id][] = $respostaQuestao->resposta;

                    // Processar as palavras para a nuvem de palavras
                    $palavras = str_word_count(strtolower(strip_tags($respostaQuestao->resposta)), 1);
                    foreach ($palavras as $palavra) {
                        // Remover palavras comuns (stop words)
                        if (in_array($palavra, ['de', 'a', 'e', 'o', 'que', 'do', 'da', 'em', 'um', 'para', 'é', 'com', 'es', 'no', 'na', 'os', 'as', 'dos', 'das', 'ou', 'se', 'por', 'como', 'mas', 'foi', 'ao', 'ele', 'das', 'tem', 'à', 'mais', 'quando', 'muito', 'nos', 'já', 'eu', 'sua', 'são', 'também', 'pelo', 'pela', 'até', 'isso', 'ela', 'entre', 'era', 'depois', 'sem', 'mesmo', 'aos', 'ter', 'seus', 'quem', 'nas', 'me', 'esse', 'eles', 'está', 'você', 'tinha', 'foram', 'essa', 'num', 'nem', 'suas', 'meu', 'às', 'minha', 'têm', 'numa', 'pelos', 'elas', 'havia', 'seja', 'qual', 'será', 'nós', 'tenho', 'lhe', 'deles', 'essas', 'esses', 'pelas', 'este', 'fosse', 'dele', 'tu', 'te', 'vocês', 'vos', 'lhes', 'meus', 'minhas', 'teu', 'tua', 'teus', 'tuas', 'nosso', 'nossa', 'nossos', 'nossas', 'dela', 'delas', 'esta', 'estes', 'estas', 'aquele', 'aquela', 'aqueles', 'aquelas', 'isto', 'aquilo', 'estou', 'está', 'estamos', 'estão', 'estive', 'esteve', 'estivemos', 'estiveram', 'estava', 'estávamos', 'estavam', 'estivera', 'estivéramos', 'esteja', 'estejamos', 'estejam', 'estivesse', 'estivéssemos', 'estivessem', 'estiver', 'estivermos', 'estiverem', 'hei', 'há', 'havemos', 'hão', 'houve', 'houvemos', 'houveram', 'h'])) {
                            continue;
                        }
                        // Contar as palavras
                        if (isset($nuvemPalavras[$respostaQuestao->questao_id][$palavra])) {
                            $nuvemPalavras[$respostaQuestao->questao_id][$palavra]++;
                        } else {
                            $nuvemPalavras[$respostaQuestao->questao_id][$palavra] = 1;
                        }
                    }
                }
            }
        }

        // Filtrar palavras com menos de 2 ocorrências e ordenar por frequência
        foreach ($nuvemPalavras as $questaoId => $palavras) {
            // Filtrar palavras com menos de 2 ocorrências
            $palavrasFiltradas = array_filter($palavras, function($count) {
                return $count >= 2;
            });

            // Ordenar as palavras por frequência
            arsort($palavrasFiltradas);

            // Atualizar o array de palavras da questão com as palavras filtradas e ordenadas
            $nuvemPalavras[$questaoId] = $palavrasFiltradas;
        }

        return view('Resultados.estatisticas', compact('formulario', 'estatisticas', 'respostasTexto', 'nuvemPalavras', 'overview'));
    }


    public function gerarPDF($formularioId, $respostaAlunoId)
    {
        $formulario = Formulario::with('questoes')->findOrFail($formularioId);
        $respostaAluno = FormularioResposta::with('respostas')->findOrFail($respostaAlunoId);

        $pdf = PDF::loadView('pdfs.resposta-aluno', compact('formulario', 'respostaAluno'));

        return $pdf->stream("respostas-aluno-{$respostaAluno->nome_aluno}.pdf");
    }

    public function gerarPdfRespostas($formularioId)
    {
        $formulario = Formulario::with('respostas')->findOrFail($formularioId);
        $respostas = FormularioResposta::where('formulario_id', $formularioId)->get();

        $pdf = Pdf::loadView('pdfs.respostas-gerais', compact('formulario', 'respostas'));
        return $pdf->stream('respostas-formulario-' . $formularioId . '.pdf');
    }

    public function gerarRelatorioEstatistico(Request $request, $formularioId)
    {
        $formulario = Formulario::with('questoes.opcoesMultiplasEscolhas')->findOrFail($formularioId);
        $respostas = FormularioResposta::with('respostas')->where('formulario_id', $formularioId)->get();

        $estatisticas = [];
        $respostasTexto = [];

        foreach ($formulario->questoes as $questao) {
            if ($questao->tipo === 'MULTIPLA_ESCOLHA') {
                foreach ($questao->opcoesMultiplasEscolhas as $opcao) {
                    $estatisticas[$questao->id][$opcao->id] = 0;
                }
            } elseif ($questao->tipo === 'TEXTO_LIVRE') {
                $respostasTexto[$questao->id] = [];
            }
        }

        foreach ($respostas as $resposta) {
            foreach ($resposta->respostas as $respostaQuestao) {
                if ($respostaQuestao->questao->tipo === FormularioQuestao::MULTIPLA_ESCOLHA) {
                    if (isset($estatisticas[$respostaQuestao->questao_id][$respostaQuestao->resposta_id])) {
                        $estatisticas[$respostaQuestao->questao_id][$respostaQuestao->resposta_id]++;
                    }
                } elseif ($respostaQuestao->questao->tipo === FormularioQuestao::TEXTO_LIVRE) {
                    $respostasTexto[$respostaQuestao->questao_id][] = $respostaQuestao->resposta;
                }
            }
        }

        $pdf = PDF::loadView('pdfs.relatorio-estatistico', compact('formulario', 'estatisticas', 'respostasTexto'));

        return $pdf->stream('estatisticas-formulario-' . $formularioId . '.pdf');
    }

}
