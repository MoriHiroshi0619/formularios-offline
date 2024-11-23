@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Resultados</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Resultados dos Formulários
                    <i class="bi bi-bar-chart-line"></i>
                </h2>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        @if( $formulariosFinalizados->isEmpty() )
            <div class="col-sm-12">
                <div class="alert alert-warning" role="alert">
                    Não há formulários finalizados no momento.
                </div>
            </div>
        @else
            <div class="col-md-12 table-responsive-wrapper">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Formulário</th>
                        <th>Qtd. pessoas participaram</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $formulariosFinalizados as $formulario )
                        <tr>
                            <td class="text-center">{{ $formulario->id }}</td>
                            <td class="long-title">{{ $formulario->nome_formulario ?? 'Anônimo' }}</td>
                            <td class="text-center">{{ $formulario->respostas->count() }}</td>
                            <td class="text-center">{{ $formulario->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 align-items-center justify-content-evenly flex-sm-wrap">
                                    <a href="{{ route('resultado.show', $formulario->id) }}" class="btn btn-primary btn-sm" title="Ver Resultados">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('resultado.estatisticas', $formulario->id) }}" class="btn btn-success btn-sm" title="Ver estatística">
                                        <i class="bi bi-bar-chart"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Paginação -->
                <nav class="float-end" aria-label="Navegação de página">
                    <ul class="pagination">
                        <li class="page-item {{ $formulariosFinalizados->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $formulariosFinalizados->previousPageUrl() }}" tabindex="-1">
                                &laquo;
                            </a>
                        </li>
                        @for ($i = 1; $i <= $formulariosFinalizados->lastPage(); $i++)
                            <li class="page-item {{ $i == $formulariosFinalizados->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $formulariosFinalizados->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $formulariosFinalizados->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $formulariosFinalizados->nextPageUrl() }}">
                                &raquo;
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
@endsection
