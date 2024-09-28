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
                        <li>
                            <strong>Leia a Pergunta:</strong>
                        </li>
                        <p>
                            Cada pergunta foi formulada para coletar suas opiniões ou informações específicas. Leia com atenção antes de responder.
                        </p>

                        <li>
                            <strong>Responda a Pergunta:</strong>
                        </li>
                        <p>
                            Dependendo do tipo de pergunta, você poderá ver campos de <strong>resposta dissertativa</strong> (onde pode escrever livremente) ou opções de <strong>múltipla escolha</strong> (onde você seleciona uma resposta dentre várias opções).
                        </p>

                        <li>
                            <strong>Respostas de Múltipla Escolha:</strong>
                        </li>
                        <p>
                            Em perguntas com múltipla escolha, selecione a opção que melhor reflete sua opinião ou situação. Você pode alterar sua escolha enquanto o formulário estiver aberto.
                        </p>

                        <li>
                            <strong>Respostas Dissertativas:</strong>
                        </li>
                        <p>
                            Em perguntas dissertativas, utilize o campo de texto para fornecer uma resposta mais detalhada. Lembre-se de ser claro e objetivo.
                        </p>

                        <li>
                            <strong>Envio do Formulário:</strong>
                        </li>
                        <p>
                            Ao finalizar, revise suas respostas e clique no botão <button class="btn btn-primary btn-sm">Salvar</button> para salvar suas respostas.
                        </p>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row py-2">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="nome_aluno">Nome:</label>
                <input id="nome_aluno" class="form-control" name="nome_aluno" placeholder="seu sobrenome" value="{{ $respostasSalvas['nome'] ?? '' }}" required>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <nav class="pagination-container">
            {{-- Paginação --}}
            <ul class="pagination">
                <li class="page-item {{ $questoes->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $questoes->previousPageUrl() }}" tabindex="-1">
                        &laquo;
                    </a>
                </li>

                @for($i = 1; $i <= $questoes->lastPage(); $i++)
                    <li class="page-item {{ $i == $questoes->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $questoes->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                <li class="page-item {{ $questoes->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $questoes->nextPageUrl() }}">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    </div>

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
                        <textarea class="form-control" name="resposta[{{ $questao->id }}]" rows="10">
                            {{ $respostasSalvas[$questao->id] ?? '' }}
                        </textarea>
                    </div>
                @endif
            @endforeach
        </div>
    </div>


    <div class="row pt-2">
        <div class="col-sm-12">
            <button id="submit" class="btn btn-primary float-end">
                <i class="bi bi-save"></i>
                salvar
            </button>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {
            $('#submit').click(() => {
                let nomeAluno = $('#nome_aluno').val();
                if(!nomeAluno){
                    Swal.fire({
                        icon: 'error',
                        title: 'Nome do aluno não informado',
                        text: 'Por favor, informe seu nome para salvar as respostas',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                Swal.fire({
                    title: "Deseja enviar suas respostas agora?",
                    text: "Por favor verifique se todos os campos foram preenchidos",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, enviar respostas!",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            let formularioId = '{{ $formulario->id }}';
                            await axios.post('{{ route('visitantes.formularios.store') }}', { formulario: formularioId, aluno: nomeAluno });
                            window.location.href = '{{ route('visitantes.formularios.index') }}';
                        }catch (e) {
                            await Swal.fire({
                                icon: 'error',
                                title: 'Erro ao enviar respostas',
                                text: 'status: ' + e.response.status + ' - ' + e.response.statusText,
                            });
                        }
                    }
                });
            })

            $('body').on('click', '.page-item', async (e) => {
                e.preventDefault();
                let respostas = null
                $('input[type="radio"]:checked').each(function () {
                    let questaoId = $(this).attr('name').replace('resposta[', '').replace(']', '');
                    respostas = {
                        questao: questaoId,
                        resposta: $(this).val(),
                        aluno: $('#nome_aluno').val()
                    }
                });

                $('textarea').each(function () {
                    let questaoId = $(this).attr('name').replace('resposta[', '').replace(']', '');
                    respostas = {
                        questao: questaoId,
                        resposta: $(this).val(),
                        aluno: $('#nome_aluno').val()
                    }
                });

                try{
                    if(respostas) await axios.post('{{ route('visitantes.salvar-questao-sessao', $formulario->id) }}', { resposta: respostas });
                    window.location.href = $(e.target).attr('href');
                }catch (e){
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
