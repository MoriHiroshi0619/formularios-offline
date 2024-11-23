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
                        <canvas id="chart-questao-{{ $questao->id }}" width="400" height="200"></canvas>
                    @elseif( $questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE )
                        <h6 class="mt-3">Respostas:</h6>
                        <div id="word-cloud-{{ $questao->id }}" style="width: 100%; height: 400px;"></div>
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
    <!-- Incluir o Chart.js -->
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
                    let words{{ $questao->id }} = [
                            @foreach($nuvemPalavras[$questao->id] as $palavra => $frequencia)
                        ["{{ $palavra }}", {{ $frequencia }}],
                        @endforeach
                    ];

                    WordCloud(document.getElementById('word-cloud-{{ $questao->id }}'), {
                        list: words{{ $questao->id }},
                        gridSize: Math.round(16 * $('#word-cloud-{{ $questao->id }}').width() / 1024),
                        weightFactor: function (size) {
                            return size * 10;
                        },
                        fontFamily: 'Times, serif',
                        color: 'random-dark',
                        rotateRatio: 0.5,
                        backgroundColor: '#fff',
                    });
                @endif
            @endforeach
        });
    </script>
@endpush

@push('styles')
    <style>
        #word-cloud-{{ $questao->id }} {
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
    </style>
@endpush

