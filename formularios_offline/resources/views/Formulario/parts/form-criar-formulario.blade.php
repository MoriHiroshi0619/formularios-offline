<div class="row pt-2">
    <div class="col-sm-12">

        <div class="row align-items-end">
            <div class="col-sm-8">
                <div class="form-group">
                    <label>Nome do formalário</label>
                    <input type="text" id="nome-formulario" class="form-control" name="formulario[nome]" required>
                </div>
            </div>

            <div class="col-sm-4">
                <!-- Button modal -->
                <button type="button" class="btn btn-primary float-end mt-2" data-bs-toggle="modal" data-bs-target="#adicionar-pergunta-modal">
                    <i class="bi bi-plus-lg"></i>
                    Questão
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="adicionar-pergunta-modal" tabindex="-1" aria-labelledby="modal-titile" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-titile">
                            <i class="bi bi-plus-lg"></i>
                            Questão
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Texto da questão</label>
                                    <textarea class="form-control" name="questao[texto]" rows="7" required></textarea>
                                </div>
                            </div>
                        </div>

                        <label class="mt-3">Estilo da questão</label>
                        <div class="w-100 d-flex align-items-center justify-content-between">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="estilo-questao">
                                <label class="form-check-label" for="estilo-questao" id="estilo-label">Texto livre</label>
                            </div>
                            <button class="btn btn-primary d-none" data-action="adicionar-opcao" title="Adiciona mais uma alternativa">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                        <div class="row mt-4 div-multipla-escolha d-none">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="opcao">Opção 1</label>
                                    <input type="text" class="form-control" name="questao[opcao]" data-ordem="1">
                                </div>
                            </div>
                            {{--inserir mais alternativas para a qeustão--}}

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary">Adicionar Pergunta</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        $(document).ready(()=>{
            //como ao recarregar a pagina fica na memoria os inputs, eu limpo aqui
            if( $('#estilo-questao').is(':checked') ){
                $('[data-action="adicionar-opcao"]').removeClass('d-none');
                $('#estilo-label').text('Multipla escolha');
                $('.div-multipla-escolha').removeClass('d-none');
            }

            $('#estilo-questao').on('change', (e)=>{
                if( e.target.checked ){
                    $('[data-action="adicionar-opcao"]').removeClass('d-none');
                    $('#estilo-label').text('Multipla escolha');
                    $('.div-multipla-escolha').removeClass('d-none');
                }else{
                    $('[data-action="adicionar-opcao"]').addClass('d-none');
                    $('#estilo-label').text('Texto livre');
                    $('.div-multipla-escolha').addClass('d-none');
                }
            });

            $('[data-action="adicionar-opcao"]').on('click', ()=>{
                //MELHORAR LÓGICA AO ADICONAR MAIS ALTERNATIVAS
                let div = $('.div-multipla-escolha');
                let input = div.find('input').first().clone();
                div.append(input);
            });
        });
    </script>
@endpush
