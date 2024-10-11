<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use App\Models\Respostas\FormularioResposta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


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

}
