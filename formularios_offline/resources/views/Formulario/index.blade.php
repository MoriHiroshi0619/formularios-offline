@php
    $formularios = \App\Models\Formularios\Formulario::query()->with('questoes')->orderBy('created_at')->paginate(10);
@endphp

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
        @if( $formularios->isEmpty() )
            <div class="col-sm-12">
                <div class="alert alert-warning" role="alert">
                    Você ainda não possui formulários cadastrados
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
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Apagar!",
                    reverseButtons: true
                }).then( async (result, e) => {
                    if (!result.isConfirmed) return;
                    try{
                        await axios.delete(`/formulario/${id}`);
                        window.location.href = '{{ route('formulario.index') }}';
                    }catch (e) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Erro ao apagar formulário',
                            text: e.message
                        })
                    }
                });
            })

        })
    </script>
@endpush
