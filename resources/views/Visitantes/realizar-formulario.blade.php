@php
    $respostasSalvas = session('respostas.' . $formulario->id, []);
@endphp

@extends('main')

@section('content')
    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Realizar Formulário
                    <i class="bi bi-pencil"></i>
                </h2>

                <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#tutorial-realizar-formulario" aria-expanded="false" aria-controls="tutorial-realizar-formulario">
                    <i class="bi bi-question-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row pt-2">
        <div class="col-sm-12">
            <div class="collapse" id="tutorial-realizar-formulario">
                <div class="card card-body">
                    <h5><strong>Como Preencher o Formulário</strong></h5>
                    <ol>
                        <li><strong>Leia a Pergunta:</strong></li>
                        <p>Cada pergunta foi formulada para coletar suas opiniões ou informações específicas. Leia com atenção antes de responder.</p>
                        <li><strong>Responda a Pergunta:</strong></li>
                        <p>Dependendo do tipo de pergunta, você verá campos de <strong>resposta dissertativa</strong> ou opções de <strong>múltipla escolha</strong>.</p>
                        <li><strong>Envio do Formulário:</strong></li>
                        <p>Ao finalizar, revise suas respostas e clique em <button class="btn btn-primary btn-sm">Salvar</button> para enviar suas respostas.</p>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(!$formulario->anonimo)
        <div class="row py-2">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="nome_aluno">Nome:</label>
                    <input id="nome_aluno" class="form-control" name="nome_aluno" placeholder="Seu nome" value="{{ $respostasSalvas['nome'] ?? '' }}" required>
                </div>
            </div>
        </div>
    @endif

    <div class="card mt-1">
        <div class="card-body">
            @foreach($questoes as $questao)
                <h5>{{ $questao->questao }}</h5>

                @if($questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA)
                    <div class="form-group">
                        @foreach($questao->opcoesMultiplasEscolhas as $opcao)
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="resposta[{{ $questao->id }}]"
                                       value="{{ $opcao->id }}"
                                       id="opcao{{ $opcao->id }}"
                                    {{ isset($respostasSalvas[$questao->id]) && $respostasSalvas[$questao->id] == $opcao->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="opcao{{ $opcao->id }}">
                                    {{ $opcao->opcao_resposta }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @elseif($questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE)
                    <div class="form-group">
                        <textarea class="form-control" name="resposta[{{ $questao->id }}]" rows="10">{{ $respostasSalvas[$questao->id] ?? '' }}</textarea>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="mt-4">
        <nav class="pagination-container">
            {{-- Paginação --}}
            <ul class="pagination">
                <li class="page-item {{ $questoes->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $questoes->previousPageUrl() }}" tabindex="-1">&laquo;</a>
                </li>

                @for($i = 1; $i <= $questoes->lastPage(); $i++)
                    <li class="page-item {{ $i == $questoes->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $questoes->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                <li class="page-item {{ $questoes->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $questoes->nextPageUrl() }}">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="w-100 d-flex justify-content-center align-items-center">
        @if( $questoes->hasMorePages() )
            <button id="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Salvar e ir para o próximo
            </button>
        @else
            <button id="review" class="btn btn-success">
                <i class="bi bi-eye"></i> Revisar antes de enviar
            </button>
        @endif
    </div>

@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {
            // Função para capturar as respostas da página atual
            function capturarRespostas() {
                let respostas = {
                    aluno: $('#nome_aluno').val(),
                    questoes: {}
                };

                // Captura respostas de radio
                $('input[type="radio"]:checked').each(function () {
                    let questaoId = $(this).attr('name').replace('resposta[', '').replace(']', '');
                    respostas.questoes[questaoId] = $(this).val();
                });

                // Captura respostas de textarea
                $('textarea').each(function () {
                    let questaoId = $(this).attr('name').replace('resposta[', '').replace(']', '');
                    respostas.questoes[questaoId] = $(this).val();
                });

                return respostas;
            }

            // Função para salvar a resposta e ir para a próxima página
            $('#submit').click(async () => {
                let respostas = capturarRespostas();

                @if(!$formulario->anonimo)
                if (!respostas.aluno) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Nome do aluno não informado',
                        text: 'Por favor, informe seu nome para salvar as respostas',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                @endif

                    try {
                    let formularioId = '{{ $formulario->id }}';
                    await axios.post('{{ route('visitantes.salvar-questao-sessao', $formulario->id) }}', { resposta: respostas });
                    window.location.href = '{{ $questoes->nextPageUrl() }}';
                } catch (e) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Erro ao salvar resposta',
                        text: 'status: ' + e.response.status + ' - ' + e.response.statusText,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });

            // Evento para revisar todas as respostas
            $('#review').click(async () => {
                let respostas = capturarRespostas();
                try {
                    let formularioId = '{{ $formulario->id }}';
                    await axios.post('{{ route('visitantes.salvar-questao-sessao', $formulario->id) }}', { resposta: respostas });
                    window.location.href = '{{ route('visitantes.revisar-formulario', $formulario->id) }}';
                } catch (e) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Erro ao salvar resposta',
                        text: 'status: ' + e.response.status + ' - ' + e.response.statusText,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

    </script>
@endpush
