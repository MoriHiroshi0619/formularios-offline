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
        .statistica {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .questao {
            margin-bottom: 10px;
        }
        .opcao {
            margin-left: 20px;
        }
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 20px 0;
        }
        .page-break {
            page-break-before: always;
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
    <p><strong>Relatório geral estático do formulário:</strong> {{ $formulario->nome_formulario }}</p>
    <p><strong>Impressão realizada por:</strong> {{ auth()->user()->nome ?? 'Sistema' }}</p>
    <p><strong>Data/Hora da impressão:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

@foreach($formulario->questoes as $questao)
    @if($questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA)
        <div class="statistica">
            <h3>{{ $questao->questao }}</h3>
            <ul>
                @foreach($questao->opcoesMultiplasEscolhas as $opcao)
                    <li class="opcao">
                        {{ $opcao->opcao_resposta }}:
                        <strong>{{ $estatisticas[$questao->id][$opcao->id] ?? 0 }}</strong> respostas
                    </li>
                @endforeach
            </ul>
        </div>
        <hr>
    @elseif($questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE)
        <div class="statistica">
            <h3>{{ $questao->questao }}</h3>
            @foreach($respostasTexto[$questao->id] ?? [] as $resposta)
                <p>• {{ $resposta }}</p>
            @endforeach
        </div>

        <hr>
    @endif
@endforeach

<div class="footer">
    Relatório gerado automaticamente por Formulários Offline - UEMS.
</div>

</body>
</html>
