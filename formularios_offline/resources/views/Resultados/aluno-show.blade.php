@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('resultado.index') }}">Resultados</a></li>
            <li class="breadcrumb-item"><a href="{{ route('resultado.show', $formulario->id) }}">Formulário - Resultado</a></li>
            <li class="breadcrumb-item active" aria-current="page">Respostas - Aluno</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Respostas - {{ $formulario->nome_formulario }} ({{ $respostaAluno->nome_aluno }})
                    <i class="bi bi-pencil"></i>
                </h2>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            @foreach( $formulario->questoes as $questao )
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>{{ $questao->questao }}</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $resposta = $respostaAluno->respostas->firstWhere('questao_id', $questao->id);
                        @endphp

                        @if($questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA)
                            <ul>
                                @foreach($questao->opcoesMultiplasEscolhas as $opcao)
                                    <li>
                                        <input type="radio" disabled {{ $resposta && $resposta->resposta_id == $opcao->id ? 'checked' : '' }}>
                                        {{ $opcao->opcao_resposta }}
                                    </li>
                                @endforeach
                            </ul>
                        @elseif($questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE)
                            <div class="form-group">
                                <textarea class="form-control" rows="5" disabled>{{ $resposta ? $resposta->resposta : 'Sem resposta' }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row pt-2">
        <div class="col-sm-12">
            <a href="{{ route('resultado.show', $formulario->id) }}" class="btn btn-primary float-end">
                <i class="bi bi-arrow-left"></i>
                Voltar
            </a>
        </div>
    </div>
@endsection
