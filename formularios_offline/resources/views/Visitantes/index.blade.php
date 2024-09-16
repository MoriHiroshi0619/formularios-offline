@extends('main')

@section('content')
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Visitantes
                    <i class="bi bi-people"></i>
                </h2>
            </div>
        </div>
    </div>

    <div class="visitante-formularios">
        @foreach($formularios as $formulario)
            <a class="formulario maozinha" href="{{ route('visitantes.realizar-formulario', $formulario->id) }}">
                <div class="formulario-header">
                    <span title="Identificador do formulário">
                        {{ $formulario->nome_formulario }}
                    </span>
                </div>
                <div class="formulario-footer">
                    <span title="Dia e horário em que este formulário foi liberado">
                        {{ \Carbon\Carbon::parse($formulario->liberado_em)->format('d/m/y H:i') }}
                    </span>
                    <span title="Criador do formulário">
                        {{ $formulario->professor->nome ?? 'N/A' }}
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    <nav id="navegacao" class="float-end d-none" aria-label="Navegação de página">
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

@endsection
@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {
            let temFormulario = $('.formulario').length > 0
            if(temFormulario){
                $('#navegacao').removeClass('d-none')
            }
        })
    </script>
@endpush
