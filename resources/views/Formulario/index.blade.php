@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formularios</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Formalários
                    <i class="bi bi-receipt"></i>
                </h2>

                <a class="btn btn-primary float-end" href="{{ route('formulario.create') }}">
                    <i class="bi bi-plus-lg"></i>
                    Criar formulário
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('formulario.index') }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-group mb-0">
                        <label for="nome_formulario">Nome do Formulário</label>
                        <input type="text" name="nome_formulario" class="form-control" id="nome_formulario"
                               value="{{ request('nome_formulario', session('filtros.nome_formulario', '')) }}">
                    </div>

                    <div class="form-group mb-0 ms-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Todos</option>
                            <option value="CRIADO" {{ request('status', session('filtros.status')) == 'CRIADO' ? 'selected' : '' }}>Criado</option>
                            <option value="LIBERADO" {{ request('status', session('filtros.status')) == 'LIBERADO' ? 'selected' : '' }}>Liberado</option>
                            <option value="FINALIZADO" {{ request('status', session('filtros.status')) == 'FINALIZADO' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                    </div>

                    <div class="d-flex ms-3 align-items-center">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="{{ route('formulario.index', ['nome_formulario' => '', 'status' => '']) }}" class="btn btn-secondary ms-2">Limpar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        @if( $formularios->isEmpty() )
            <div class="col-sm-12">
                <div class="alert alert-warning" role="alert">
                    Não existe formulários
                </div>
            </div>
        @else
            <div class="col-md-12 table-responsive-wrapper">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Qtd. Questões</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($formularios as $formulario)
                        <tr>
                            <td class="text-center">{{ $formulario->id }}</td>
                            <td class="long-title">{{ $formulario->nome_formulario }}</td>
                            <td class="text-center">{{ $formulario->questoes->count() }}</td>
                            <td class="text-center">{{ $formulario->status }}</td>
                            <td class="text-center">{{ $formulario->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 align-items-center justify-content-evenly flex-sm-wrap">
                                    <a href="{{ route('formulario.show', $formulario->id) }}" class="btn btn-primary btn-sm" title="Visualizar Formulario">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm" data-action="deletar-formulario" data-id="{{ $formulario->id }}" title="Deletar Formulario">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Paginação -->
                <nav class="float-end" aria-label="Navegação de página">
                    <ul class="pagination">
                        <li class="page-item {{ $formularios->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $formularios->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $formularios->onFirstPage() ? 'true' : 'false' }}">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        @for ($i = 1; $i <= $formularios->lastPage(); $i++)
                            <li class="page-item {{ $i == $formularios->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $formularios->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <li class="page-item {{ $formularios->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $formularios->nextPageUrl() }}">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {

            $('[data-action="deletar-formulario"]').on('click', (e) => {
                let id = $(e.target).closest('button').data('id');
                Swal.fire({
                    title: "Atenção!",
                    text: "Deseja mesmo apagar o formulário?",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#a6a6a6",
                    cancelButtonText: "Cancelar",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Apagar!",
                    reverseButtons: true
                }).then( async (result) => {
                    if (!result.isConfirmed) return;
                    try{
                        await axios.delete(`/formulario/${id}`);
                        window.location.reload();
                    }catch (e) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Erro ao apagar formulário',
                            text: 'status: ' + e.response.status + ' - ' + e.response.statusText,
                        })
                    }
                });
            })

        })
    </script>
@endpush
