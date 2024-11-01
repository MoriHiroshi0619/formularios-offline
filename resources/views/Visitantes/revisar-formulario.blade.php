@extends('main')

@section('content')
    <div class="row pt-2">
        <div class="col-md-12">
            <h2>Revisar Respostas</h2>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @foreach($formulario->questoes as $questao)
                        <div class="questao mb-4">
                            <h5>{{ $questao->questao }}</h5>

                            {{-- Verifica o tipo de questão e mostra a resposta salva --}}
                            @if($questao->tipo === \App\Models\Formularios\FormularioQuestao::MULTIPLA_ESCOLHA)
                                <p><strong>Resposta Escolhida:</strong>
                                    @php
                                        $respostaEscolhida = $respostasSalvas[$questao->id] ?? null;
                                        $opcaoEscolhida = $questao->opcoesMultiplasEscolhas->firstWhere('id', $respostaEscolhida);
                                    @endphp
                                    {{ $opcaoEscolhida->opcao_resposta ?? 'Sem resposta' }}
                                </p>
                            @elseif($questao->tipo === \App\Models\Formularios\FormularioQuestao::TEXTO_LIVRE)
                                <p><strong>Resposta:</strong> {{ $respostasSalvas[$questao->id] ?? 'Sem resposta' }}</p>
                            @endif
                            {{-- Botão para corrigir a resposta --}}
                            <a href="{{ url('visitantes/realizar-formulario/' . $formulario->id . '?page=' . ($loop->index + 1)) }}" class="btn btn-warning">
                                Corrigir
                            </a>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-2">
        <div class="col-sm-12 text-center">
            <button id="submit-form" class="btn btn-primary">
                Enviar Respostas
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {
            $('#submit-form').click(async () => {
                let confirmacao = await Swal.fire({
                    title: "Enviar Respostas?",
                    text: "Tem certeza de que deseja enviar suas respostas?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, enviar!",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                });

                if (confirmacao.isConfirmed) {
                    try {
                        let formularioId = '{{ $formulario->id }}';
                        await axios.post('{{ route('visitantes.formularios.store') }}', { formulario: formularioId });
                        window.location.href = '{{ route('visitantes.formularios.index') }}';
                    } catch (e) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Erro ao enviar respostas',
                            text: 'status: ' + e.response.status + ' - ' + e.response.statusText,
                        });
                    }
                }
            });
        });
    </script>
@endpush
