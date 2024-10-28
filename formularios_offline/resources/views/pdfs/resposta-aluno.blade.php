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
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .resposta {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .questao {
            margin-bottom: 10px;
        }
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="header">
    {{--todo: Eu tentei !! Por algum motivo o plugin DomPdf não está imprimindo imagens quando no contaienr docker--}}
    {{--todo: Tentei imprimir por todas formas, public path, asset, caminho absoluto, url--}}
    {{--<img src="{{ public_path('img/uems_logo.png') }}" alt="Logo">--}}
    <h4>Universidade Estadual de Mato Grosso do Sul</h4>
    <h1>Formulários Offline</h1>
    <p><strong>Relatório individual de respostas do formulário:</strong> {{ $formulario->nome_formulario }}</p>
    <p><strong>Impressão realizada por:</strong> {{ auth()->user()->nome ?? 'Sistema' }}</p>
    <p><strong>Data/Hora da impressão:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

<div class="resposta">
    @if( $respostaAluno->nome_aluno )
        <p><strong>Aluno:</strong> {{ $respostaAluno->nome_aluno }}</p>
    @endif
    <p><strong>Data da Resposta:</strong> {{ $respostaAluno->created_at->format('d/m/Y H:i') }}</p>

    <hr>
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
        <hr>
    @endforeach
</div>

<div class="footer">
    Relatório gerado automaticamente por Formulários Offline - UEMS.
</div>

</body>
</html>
