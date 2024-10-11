<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            font-size: 22px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
        .resposta {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .questao {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('img/uems_logo.png') }}" alt="Logo">
    <h1>Formulários Online - UEMS</h1>
    <p>Relatório de respostas do formulário: <strong>{{ $formulario->nome_formulario }}</strong></p>
    <p>Data de Geração: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
</div>

@foreach($respostas as $respostaAluno)
    <div class="resposta">
        <h2>Aluno: {{ $respostaAluno->nome_aluno }}</h2>
        <p>Data da Resposta: {{ $respostaAluno->created_at->format('d/m/Y H:i') }}</p>

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
                    <div>
                        Resposta: <strong>{{ $resposta ? $resposta->resposta : 'Sem resposta' }}</strong>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endforeach

<div class="footer">
    Relatório gerado automaticamente por Formulários Online.
</div>

</body>
</html>
