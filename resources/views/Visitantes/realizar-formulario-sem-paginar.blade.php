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
                        <li><strong>Leia as Perguntas:</strong></li>
                        <p>Cada pergunta foi formulada para coletar suas opiniões ou informações específicas. Leia com atenção antes de responder.</p>
                        <li><strong>Responda às Perguntas:</strong></li>
                        <p>Dependendo do tipo de pergunta, você verá campos de <strong>resposta dissertativa</strong> ou opções de <strong>múltipla escolha</strong>.</p>
                        <li><strong>Envio do Formulário:</strong></li>
                        <p>Ao finalizar, revise suas respostas e clique em <button class="btn btn-primary btn-sm">Enviar Respostas</button> para enviar suas respostas.</p>
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
                    <input id="nome_aluno" class="form-control" name="nome_aluno" placeholder="Seu nome" value="{{ old('nome_aluno') }}" required>
                </div>
            </div>
        </div>
    @endif

    {{-- Formulário de Respostas --}}
    <div class="mt-3">
        @foreach($questoes as $index => $questao)
            <div class="card mb-4">
                <div class="card-body">
                    <h5>{{ $index + 1 }}. {{ $questao->questao }}</h5>
                    <hr>
                    @if($questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA)
                        <div class="form-group">
                            <div class="d-flex flex-wrap">
                                @foreach($questao->opcoesMultiplasEscolhas as $opcao)
                                    <div class="form-check me-4">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="resposta[{{ $questao->id }}]"
                                               value="{{ $opcao->id }}"
                                               id="opcao{{ $opcao->id }}">
                                        <label class="form-check-label" for="opcao{{ $opcao->id }}">
                                            {{ $opcao->opcao_resposta }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE)
                        <div class="form-group">
                            <textarea class="form-control" name="resposta[{{ $questao->id }}]" rows="4"></textarea>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Botão de Enviar --}}
    <div class="mt-4 w-100 d-flex justify-content-center align-items-center">
        <button id="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Enviar Respostas
        </button>
    </div>
@endsection

@push('styles')
    <style>
        @media (min-width: 768px) {
            .form-check {
                width: auto;
            }
        }
        @media (max-width: 767px) {
            .form-check {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {
            // Função para capturar as respostas
            function capturarRespostas() {
                let respostas = {
                    aluno: $('#nome_aluno').val(),
                    formulario: '{{ $formulario->id }}',
                    respostas: {}
                };

                // Captura respostas de radio
                $('input[type="radio"]:checked').each(function () {
                    let questaoId = $(this).attr('name').replace('resposta[', '').replace(']', '');
                    respostas.respostas[questaoId] = $(this).val();
                });

                // Captura respostas de textarea
                $('textarea').each(function () {
                    let questaoId = $(this).attr('name').replace('resposta[', '').replace(']', '');
                    respostas.respostas[questaoId] = $(this).val();
                });

                return respostas;
            }

            $('#submit').click(async (e) => {
                e.preventDefault();
                let respostas = capturarRespostas();

                @if(!$formulario->anonimo)
                if (!respostas.aluno) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Nome não informado',
                        text: 'Por favor, informe seu nome antes de enviar as respostas.',
                        showConfirmButton: true,
                    });
                    return;
                }
                @endif


                await Swal.fire({
                    title: 'Confirmar Envio',
                    text: 'Você revisou suas respostas? Deseja enviar agora?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, enviar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    preConfirm: () => {
                        Swal.showLoading();
                        return axios.post('{{ route('visitantes.formularios.store') }}', respostas)
                            .then(() => {
                                Swal.close();
                                $('#submit').attr('disabled', true);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Formulário Enviado',
                                    text: 'Suas respostas foram enviadas com sucesso!',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = '{{ route('visitantes.formularios.index') }}';
                                });
                            })
                            .catch((e) => {
                                $('#submit').attr('disabled', false);
                                Swal.hideLoading();
                                Swal.showValidationMessage(
                                    e.response.data.error || 'Erro desconhecido.'
                                );
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });


        });
    </script>
@endpush
