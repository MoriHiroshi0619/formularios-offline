@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formulario.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formularios - visualizar</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="long-title" title="{{ $formulario->nome_formulario }}">
                    <i class="bi bi-info-circle"></i>
                    Formulario {{ $formulario->nome_formulario }}
                </h2>

                <button class="btn btn-danger" type="button" data-action="deletar-formulario">
                    <i class="bi bi-trash"></i>
                    Apagar
                </button>
            </div>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col-md-12">
            @if ($formulario->questoes->isEmpty())
                <div class="alert alert-warning" role="alert">
                    Este formulário não possui questões cadastradas.
                </div>
            @else
                <div class="formulario-questoes">
                    @foreach($formulario->questoes as $questao)
                        <div class="questao-div">
                            <div class="questao-tipo">
                                <span><b>{{ $questao->tipo === 'TEXTO' ? 'Texto Livre' : 'Múltipla Escolha' }}:</b></span>
                            </div>
                            <div class="questao-texto">
                                <span>{{ $questao->questao }}</span>
                            </div>

                            @if($questao->tipo === 'MULTIPLA_ESCOLHA')
                                <div>
                                    <b>Opções:</b>
                                    <ol class="questao-opcoes">
                                        @foreach($questao->opcoesMultiplasEscolhas as $opcao)
                                            <li>{{ $opcao->opcao_resposta }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif
                        </div>
                        <hr>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
