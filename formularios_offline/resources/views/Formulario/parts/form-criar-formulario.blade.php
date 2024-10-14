<div class="row pt-2">
    <div class="col-sm-12">

        <div class="row align-items-end">
            <div class="col-sm-8">
                <div class="form-group">
                    <label>Nome do formulário</label>
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
                                <label class="form-label" for="opcao">Opção 1</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="questao[opcao]" data-ordem="1">
                                    <span class="bg-danger input-group-text maozinha text-white" data-action="remover-opcao">
                                        <i class="bi bi-trash"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 div-opcao-correte d-none">
                            <div class="col-sm-12">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" data-action="adicionar-pergunta">
                            Adicionar Pergunta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="formulario-questoes d-none">
        </div>

        <div id="salvar" class="row d-none">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary float-end" data-action="salvar-formulario">
                    <i class="bi bi-save"></i>
                    Salvar
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        $(document).ready(() => {
            if ($('#estilo-questao').is(':checked')) {
                $('[data-action="adicionar-opcao"]').removeClass('d-none');
                $('#estilo-label').text('Multipla escolha');
                $('.div-multipla-escolha').removeClass('d-none');
            }

            $('#estilo-questao').on('change', (e) => {
                if (e.target.checked) {
                    $('[data-action="adicionar-opcao"]').removeClass('d-none');
                    $('#estilo-label').text('Multipla escolha');
                    $('.div-multipla-escolha').removeClass('d-none');
                } else {
                    $('[data-action="adicionar-opcao"]').addClass('d-none');
                    $('#estilo-label').text('Texto livre');
                    $('.div-multipla-escolha').addClass('d-none');
                }
            });

            $('[data-action="adicionar-opcao"]').on('click', () => {
                if ($('.div-multipla-escolha').find('.col-sm-12').length >= 5) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Máximo de 5 opções por pergunta',
                        icon: "warning",
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                let divCol = $('.div-multipla-escolha').find('.col-sm-12').last();
                let ordem = parseInt(divCol.find('input').data('ordem')) + 1;
                let novaOpcao = divCol.clone();
                novaOpcao.find('label').text(`Opção ${ordem}`);
                novaOpcao.find('input').data('ordem', ordem).val('');
                $('.div-multipla-escolha').append(novaOpcao);
            });

            $(document).on('click', '[data-action="remover-opcao"]', (e) => {
                let divCol = $(e.currentTarget).closest('.col-sm-12');
                let totalOpcoes = $('.div-multipla-escolha').find('.col-sm-12').length;

                if (totalOpcoes === 1) {
                    divCol.find('input').val('');
                    return;
                }

                divCol.remove();
                $('.div-multipla-escolha').find('.col-sm-12').each((index, element) => {
                    $(element).find('label').text(`Opção ${index + 1}`);
                    $(element).find('input').data('ordem', index + 1);
                });
            });

            $('[data-action="adicionar-pergunta"]').on('click', () => {
                let texto = $('[name="questao[texto]"]').val();
                let estilo = $('#estilo-questao').is(':checked') ? 'Multipla escolha' : 'Texto livre';
                let opcoes = [];

                if (estilo === 'Multipla escolha') {
                    $('.div-multipla-escolha').find('.col-sm-12').each((index, element) => {
                        let opcao = $(element).find('input').val();
                        if (opcao.trim() !== '') {
                            opcoes.push(opcao);
                        }
                    });

                    if (opcoes.length < 2) {
                        Swal.fire({
                            title: 'Atenção',
                            text: 'Uma pergunta de múltipla escolha deve ter no mínimo 2 opções',
                            icon: "warning",
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                }

                $('.formulario-questoes').removeClass('d-none');
                $('#salvar').removeClass('d-none');

                let novaQuestaoHTML = `
                    <div class="questao-div">
                        <div class="questao-tipo">
                            <span><b>${estilo}:</b></span>
                            <input type="hidden" name="formulario[tipo]" value="${estilo}">
                        </div>
                        <div class="questao-texto">
                            <span>${texto}</span>
                            <input type="hidden" name="formulario[questao]" value="${texto}">
                        </div>
                        ${estilo === 'Multipla escolha' ? `
                            <div>
                                <span><b>Opções:</b></span>
                                <ol class="questao-opcoes">
                                    ${opcoes.map((opcao, index) => `
                                        <li>
                                            <span>${opcao}</span>
                                            <input type="hidden" name="formulario[questao][opcao][${index}]" value="${opcao}">
                                        </li>`).join('')}
                                </ol>
                            </div>` : ''}
                        <button class="btn btn-danger btn-sm d-inline-flex align-items-center" style="width: fit-content; float: right;" data-action="remover-questao">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <hr>`;

                $('.formulario-questoes').append(novaQuestaoHTML);

                $('[name="questao[texto]"]').val('');
                $('.div-multipla-escolha .col-sm-12:not(:first)').remove();
                $('[name="questao[opcao]"]').val('');
                $('.div-multipla-escolha').addClass('d-none');
                $('#estilo-questao').prop('checked', false).trigger('change');
            });

            $(document).on('click', '[data-action="remover-questao"]', function () {
                $(this).closest('.questao-div').next('hr').remove();
                $(this).closest('.questao-div').remove();
                if ($('.formulario-questoes .questao-div').length === 0) {
                    $('.formulario-questoes').addClass('d-none');
                    $('#salvar').addClass('d-none');
                }
            });

        });
    </script>
@endpush
