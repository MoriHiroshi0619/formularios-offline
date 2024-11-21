@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('resultado.index') }}">Resultados</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formulário - Resultado</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Resultados - Formulário: {{ $formulario->nome_formulario }}
                </h2>

                <button class="btn btn-success"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#pdfs-disponiveis"
                        aria-expanded="false"
                        aria-controls="pdfs-disponiveis"
                        title="Opções de PDFs disponíveis"
                >
                    PDF
                    <span style="font-size: larger; color: inherit"><i class="bi bi-filetype-pdf"></i></span>
                </button>
            </div>
        </div>
    </div>

    <div class="row pt-2">
        <div class="col-sm-12">
            <div class="collapse" id="pdfs-disponiveis">
                <div style="display: flex; gap: 8px; justify-content: center">
                    <a href="{{ route('resultado.gerar-pdf-geral', $formulario->id) }}" class="btn btn-success float-end" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Gerar relatório de alunos
                    </a>
                    <a href="{{ route('resultado.gerar-relatorio', $formulario->id) }}" class="btn btn-success float-end" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Gerar relatório estatístico
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-12 table-responsive-wrapper">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Nome do Aluno</th>
                    <th>Data da Resposta</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $respostas as $resposta )
                    <tr>
                        <td>{{ $resposta->nome_aluno ?? 'Anônimo' }}</td>
                        <td>{{ $resposta->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('resultado.show-aluno', [$formulario->id, $resposta->id]) }}" class="btn btn-primary btn-sm" title="Visualizar Respostas do Aluno">
                                <i class="bi bi-eye"></i>
                                Ver Respostas
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Paginação -->
            <nav class="float-end" aria-label="Navegação de página">
                <ul class="pagination">
                    <li class="page-item {{ $respostas->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $respostas->previousPageUrl() }}" tabindex="-1">
                            &laquo;
                        </a>
                    </li>

                    @for ($i = 1; $i <= $respostas->lastPage(); $i++)
                        <li class="page-item {{ $i == $respostas->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $respostas->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    <li class="page-item {{ $respostas->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $respostas->nextPageUrl() }}">
                            &raquo;
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
