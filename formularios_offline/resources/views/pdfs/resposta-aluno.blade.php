<!DOCTYPE html>
<html>
<head>
    <title>Respostas do Aluno</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 40px; }
        .header img { max-width: 100px; }
        .title { text-align: center; margin-bottom: 30px; }
        .title h2 { font-size: 24px; margin: 0; }
        .subtitle { text-align: center; margin-bottom: 40px; }
        .subtitle p { margin: 0; font-size: 18px; }
        .info { margin-bottom: 20px; }
        .info strong { font-size: 16px; }
        .questao { margin-bottom: 20px; border: 1px solid #000; padding: 10px; border-radius: 8px; }
        .questao h4 { margin: 0 0 10px 0; font-size: 18px; }
        .questao p { margin: 0; font-size: 16px; }
        .questao ul { list-style-type: none; padding: 0; }
        .questao li { margin-bottom: 5px; }
        .footer { text-align: right; margin-top: 50px; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('img/uems_logo.png') }}" alt="UEMS">
    <h1>Formulários Online</h1>
</div>

<div class="title">
    <h2>Respostas - {{ $formulario->nome_formulario }}</h2>
</div>
<div class="subtitle">
    <p>Aluno: <strong>{{ $respostaAluno->nome_aluno }}</strong></p>
</div>

<div class="info">
    <p><strong>Impressão realizada por:</strong> {{ auth()->user()->nome ?? 'Sistema' }}</p>
    <p><strong>Data/Hora da impressão:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

@foreach($formulario->questoes as $questao)
    <div class="questao">
        <h4>{{ $questao->questao }}</h4>

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
            <p>{{ $resposta ? $resposta->resposta : 'Sem resposta' }}</p>
        @endif
    </div>
@endforeach

<div class="footer">
    <p>Documento gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
