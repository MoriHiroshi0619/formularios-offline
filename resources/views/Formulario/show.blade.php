@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formulario.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formularios - visualizar</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="long-title" title="{{ $formulario->nome_formulario }}">
                    Formulario: {{ $formulario->nome_formulario }}
                </h2>

                <button class="btn btn-danger" type="button" data-action="deletar-formulario">
                    <i class="bi bi-trash"></i>
                    Apagar
                </button>
            </div>
        </div>
    </div>

    @if($formulario->anonimo)
        <div class="row pt-2">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    Este formulário é anônimo.
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if ($formulario->questoes->isEmpty())
                <div class="alert alert-warning" role="alert">
                    Este formulário não possui questões cadastradas.
                </div>
            @else
                <div class="formulario-questoes">
                    @foreach($formulario->questoes as $questao)
                        <div class="questao-div">
                            <div class="questao-tipo">
                                <span><b>{{ $questao->tipo === 'TEXTO' ? 'Texto Livre' : 'Múltipla Escolha' }}:</b></span>
                            </div>
                            <div class="questao-texto">
                                <span>{{ $questao->questao }}</span>
                            </div>

                            @if($questao->tipo === 'MULTIPLA_ESCOLHA')
                                <div>
                                    <b>Opções:</b>
                                    <ol class="questao-opcoes">
                                        @foreach($questao->opcoesMultiplasEscolhas as $opcao)
                                            <li>{{ $opcao->opcao_resposta }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif
                        </div>
                        <hr>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @php
        $buttonIcon = '';
        $buttonText = '';
        if( $formulario->isCriado() ) {
            $buttonIcon = 'bi-unlock';
            $buttonText = 'Liberar';
        } elseif( $formulario->isLiberado() ) {
            $buttonIcon = 'bi-lock';
            $buttonText = 'Finalizar';
        } elseif( $formulario->isFinalizado() ) {
            $buttonIcon = 'bi-unlock';
            $buttonText = 'Liberar novamente';
        }
    @endphp

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-end gap-2 align-items-sm-center flex-wrap">
                <h4 class="m-0 long-title" title="{{ $formulario->nome_formulario }}">
                    Status: {{ $formulario->status }}
                </h4>

                <button class="btn btn-primary" type="button" data-action="mudar-status" data-status="{{ $formulario->status }}">
                    <div class="div-status">
                        <i class="bi {{ $buttonIcon }}"></i>
                        {{ $buttonText }}
                    </div>
                    <div class="d-none d-flex justify-content-center align-items-center gap-2 div-carregar-status">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Processando...
                    </div>
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {

            $('[data-action="deletar-formulario"]').on('click', () => {
                Swal.fire({
                    title: "Atenção!",
                    text: "Deseja mesmo apagar o formulário?",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#a6a6a6",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Apagar!",
                    reverseButtons: true
                }).then( async (result) => {
                    if (!result.isConfirmed) return;
                    try{
                        await axios.delete('{{ route('formulario.destroy', $formulario->id) }}');
                        window.location.href = '{{ route('formulario.index') }}';
                    }catch (e) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Erro ao apagar formulário',
                            text: e.message
                        })
                    }
                });
            })

            $('[data-action="mudar-status"]').on('click', async (e) => {
                let $button = $(e.target).closest('button');
                let status = $button.data('status');
                let novoStatus = '';
                switch (status){
                    case 'CRIADO':
                        novoStatus = 'LIBERADO';
                        break;
                    case 'LIBERADO':
                        novoStatus = 'FINALIZADO';
                        break;
                    case 'FINALIZADO':
                        novoStatus = 'LIBERADO';
                        break;
                    default:
                        break;
                }
                let texto = ''
                if(status === 'FINALIZADO'){
                    texto = 'liberar novamente';
                } else if(status === 'LIBERADO'){
                    texto = 'finalizar';
                } else if(status === 'CRIADO'){
                    texto = 'liberar';
                }

                Swal.fire({
                    title: "Atenção!",
                    text: `Deseja mesmo ${texto} o formulário?`,
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#a6a6a6",
                    confirmButtonColor: "#2291f1",
                    confirmButtonText: `${texto}!`,
                    cancelButtonText: "Cancelar",
                    reverseButtons: true
                }).then( async (result) => {
                    if (!result.isConfirmed) return;
                    let url = '';
                    switch (novoStatus){
                        case 'LIBERADO':
                            url = `/formulario/liberar/{{ $formulario->id }}`;
                            break;
                        case 'FINALIZADO':
                            url = `/formulario/encerrar/{{ $formulario->id }}`;
                            break;
                    }

                    $button.attr('disabled', 'disabled');
                    $button.find('.div-status').addClass('d-none');
                    $button.find('.div-carregar-status').removeClass('d-none');

                    try{
                        await axios.put(url, { status: novoStatus });
                        window.location.reload();
                    }catch (e) {
                        $button.removeAttr('disabled');
                        $button.find('.div-status').removeClass('d-none');
                        $button.find('.div-carregar-status').addClass('d-none');

                        await Swal.fire({
                            icon: 'error',
                            title: 'Erro ao mudar status do formulário',
                            text: e.message
                        });
                    }
                });
            });


        })
    </script>
@endpush
