@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formulario.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formularios - cadastrar</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Novo Formalário
                    <i class="bi bi-plus-lg"></i>
                </h2>

                <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#tutorial-criar-formulario" aria-expanded="false" aria-controls="tutorial-criar-formulario">
                    <i class="bi bi-question-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="collapse" id="tutorial-criar-formulario">
        <div class="card card-body">
            <h5><strong>Como Criar o Formulário de Perguntas</strong></h5>
            <ol>
                <li>
                    <strong>Escolha o Tipo de Pergunta:</strong>
                </li>
                <p>
                    Marque a opção de <strong>Texto Livre</strong> para permitir que o usuário responda com uma resposta dissertativa.
                    Se preferir, marque <strong>Múltipla Escolha</strong> para que o usuário selecione entre várias opções de resposta.
                </p>

                <li>
                    <strong>Digite o Enunciado da Pergunta:</strong>
                </li>
                <p>
                    No campo de texto, insira a pergunta que deseja que os usuários respondam.
                </p>

                <li>
                    <strong>Adicione Opções de Resposta (se for Múltipla Escolha):</strong>
                </li>
                <p>
                    Insira as opções de resposta nos campos fornecidos. Lembre-se de que é necessário fornecer <strong>pelo menos duas opções</strong> para perguntas de múltipla escolha.
                    Caso precise de mais opções, clique no botão <button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i></button> para inserir campos adicionais.
                    Para remover uma opção, clique no botão <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>.
                    (Se houver apenas uma opção restante, o campo de texto será limpo, mas a opção não será removida).
                </p>

                <li>
                    <strong>Formulário Anônimo (Opcional):</strong>
                </li>
                <p>
                    Marque a caixa de seleção <strong>Anônimo</strong> se deseja que o formulário seja respondido de forma anônima. Ao ativar esta opção, os alunos não serão obrigados a fornecer seu nome ao responder. Isso é útil para pesquisas ou formulários em que a privacidade total é necessária.
                </p>

                <li>
                    <strong>Salvar a Pergunta:</strong>
                </li>
                <p>
                    Depois de preencher todos os campos e opções desejadas, clique no botão <button class="btn btn-primary btn-sm"> Adicionar Pergunta</button>.
                    A pergunta será adicionada à lista de perguntas do formulário exibida abaixo.
                </p>

                <li>
                    <strong>Salvar o Formulário:</strong>
                </li>
                <p>
                    Após adicionar todas as perguntas e definir as configurações, clique no botão <button class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Salvar</button> para finalizar a criação.
                </p>
            </ol>
        </div>
    </div>

    @include('Formulario.parts.form-criar-formulario')

@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready( () => {

            $(document).on('click', '[data-action="salvar-formulario"]', async () => {
                let formulario = {
                    nome: $('#nome-formulario').val(),
                    anonimo: $('#anonimo-formulario').is(':checked'),
                    perguntas: []
                };

                // Verificação se o nome do formulário foi preenchido
                if(!formulario.nome || formulario.nome.trim() === ''){
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Erro ao salvar formulário',
                        text: 'O nome do formulário não pode estar vazio.'
                    });
                    return;
                }

                // Loop para capturar as perguntas e suas opções
                let erroPergunta = false;
                $('.formulario-questoes .questao-div').each((index, element) => {
                    let tipo = $(element).find('input[name="formulario[tipo]"]').val();
                    let texto = $(element).find('input[name="formulario[questao]"]').val().trim();
                    let opcoes = [];

                    // Verificação se o texto da pergunta foi preenchido
                    if (!texto || texto === '') {
                        erroPergunta = true;
                        return false; // Para o loop
                    }

                    // Captura das opções de múltipla escolha, se aplicável
                    if (tipo === 'Multipla escolha') {
                        $(element).find('.questao-opcoes li').each((index, opcaoElement) => {
                            opcoes.push($(opcaoElement).find('input').val().trim());
                        });
                    }

                    // Adiciona a pergunta ao formulário
                    formulario.perguntas.push({
                        tipo: tipo,
                        texto: texto,
                        opcoes: opcoes
                    });
                });

                // Se houver erro na verificação das perguntas
                if (erroPergunta) {
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Erro ao salvar formulário',
                        text: 'Todas as perguntas devem ter um texto preenchido.'
                    });
                    return;
                }

                try {
                    await axios.post('{{ route('formulario.store') }}', { formulario })
                    window.location.href = '{{ route('formulario.index') }}';
                }catch (e) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Erro ao salvar formulário',
                        text: e.message
                    });
                }
            });
        });
    </script>
@endpush

