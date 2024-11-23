@extends('main')

@section('content')
    <div class="container mt-4">
        <h2>Resultados Estatísticos do Formulário: {{ $formulario->nome_formulario }}</h2>

        @foreach($formulario->questoes as $questao)
            <div class="card mt-4">
                <div class="card-body">
                    <h5>{{ $loop->iteration }}. {{ $questao->questao }}</h5>

                    @if( $questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA )
                        <div class="btn-group mb-3" role="group">
                            <button type="button" class="btn btn-secondary" onclick="mostrarGrafico('bar', {{ $questao->id }})">Gráfico de Barras</button>
                            <button type="button" class="btn btn-secondary" onclick="mostrarGrafico('pie', {{ $questao->id }})">Gráfico de Pizza</button>
                        </div>
                        <canvas id="chart-questao-{{ $questao->id }}"></canvas>
                    @elseif( $questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE )
                        <h6 class="mt-3">Respostas:</h6>
                        <button type="button" class="btn btn-primary mb-3" onclick="toggleWordCloud({{ $questao->id }})">Mostrar/Ocultar Nuvem de Palavras</button>
                        <div id="word-cloud-container-{{ $questao->id }}" class="word-cloud-container collapsed">
                            <div id="word-cloud-{{ $questao->id }}" style="width: 100%; height: 400px;"></div>
                        </div>
                        <ul>
                            @foreach( $respostasTexto[$questao->id] as $resposta )
                                <li>{{ $resposta }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <!-- Incluir o Chart.js e o wordcloud2.js localmente -->
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/chartjs-plugin-datalabels.js') }}"></script>
    <script src="{{ asset('js/wordcloud2.js') }}"></script>

    <script>
        Chart.register(ChartDataLabels); // Registrar o plugin

        let charts = {};

        function mostrarGrafico(tipo, questaoId) {
            charts[questaoId].destroy();
            let options = {
                responsive: true,
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = ((value * 100) / sum).toFixed(2) + "%";
                            return percentage;
                        },
                        color: '#fff',
                    }
                }
            };
            if (tipo === 'bar') {
                options.scales = {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                };
            }

            charts[questaoId] = new Chart(document.getElementById('chart-questao-' + questaoId).getContext('2d'), {
                type: tipo,
                data: window['data' + questaoId],
                options: options
            });
        }

        function toggleWordCloud(questaoId) {
            const container = document.getElementById('word-cloud-container-' + questaoId);
            const isCollapsed = container.classList.toggle('collapsed');
            if (!isCollapsed) {
                // Se a div está sendo mostrada, gera a nuvem de palavras
                generateWordCloud(questaoId);
            }
        }

        function generateWordCloud(questaoId) {
            // Verifica se a nuvem de palavras já foi gerada
            if (window['wordCloudGenerated' + questaoId]) {
                return;
            }
            window['wordCloudGenerated' + questaoId] = true;

            var words = window['words' + questaoId];
            var colors = ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b'];

            WordCloud(document.getElementById('word-cloud-' + questaoId), {
                list: words,
                gridSize: 8,
                weightFactor: function (size) {
                    return (size + 2) * 5; // Aumenta o tamanho mínimo das palavras
                },
                fontFamily: 'Times, serif',
                color: function () {
                    return colors[Math.floor(Math.random() * colors.length)];
                },
                rotateRatio: 0.5,
                backgroundColor: '#fff',
                drawOutOfBound: false,
                draw: function drawCanvasWord(item, wordcloudCtx) {
                    wordcloudCtx.save();
                    wordcloudCtx.shadowColor = 'rgba(0,0,0,0.3)';
                    wordcloudCtx.shadowBlur = 3;
                    wordcloudCtx.fillStyle = item.fill;
                    wordcloudCtx.font = item.weight + ' ' + item.size + 'px ' + item.font;
                    wordcloudCtx.translate(item.x, item.y);
                    if (item.rotate) {
                        wordcloudCtx.rotate(item.rotate);
                    }
                    wordcloudCtx.textAlign = 'center';
                    wordcloudCtx.fillText(item.text, 0, 0);
                    wordcloudCtx.restore();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            @foreach($formulario->questoes as $questao)
            @if($questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA)
            @php
                $totalRespostas = array_sum(array_column($estatisticas[$questao->id], 'quantidade'));
            @endphp
            let ctx{{ $questao->id }} = document.getElementById('chart-questao-{{ $questao->id }}').getContext('2d');
            window['data{{ $questao->id }}'] = {
                labels: [
                    @foreach($estatisticas[$questao->id] as $opcao)
                        "{{ $opcao['opcao_resposta'] }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Quantidade de Respostas',
                    data: [
                        @foreach($estatisticas[$questao->id] as $opcao)
                            {{ $opcao['quantidade'] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54,162,235,1)',
                        'rgba(255,206,86,1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    borderWidth: 1
                }]
            };

            // Inicialmente, exibir gráfico de barras
            charts[{{ $questao->id }}] = new Chart(ctx{{ $questao->id }}, {
                type: 'bar',
                data: window['data{{ $questao->id }}'],
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = ((value * 100) / sum).toFixed(2) + "%";
                                return percentage;
                            },
                            color: '#fff',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
            @elseif($questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE)
                window['words{{ $questao->id }}'] = [
                    @foreach($nuvemPalavras[$questao->id] as $palavra => $frequencia)
                ["{{ $palavra }}", {{ $frequencia }}],
                @endforeach
            ];
            @endif
            @endforeach
        });
    </script>
@endpush

@push('styles')
    <style>
        .word-cloud-container {
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.5s ease-out, opacity 0.5s ease-out;
        }

        .word-cloud-container:not(.collapsed) {
            max-height: 500px; /* Ajuste conforme necessário */
            opacity: 1;
        }
    </style>
@endpush
