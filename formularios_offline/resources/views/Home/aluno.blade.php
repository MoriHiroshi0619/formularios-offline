{{--não tera mais uma tela só pra alunos--}}
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2>Aluno</h2>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-sm-4">
                @include('Components.link-card', [
                    'titulo' => 'Formulários',
                    'icone' => 'bi bi-files',
                    'link' => route('formulario.index'),
                    'descricao' => 'Realize os formularios disponiveis para você',
                    'action' => 'realizar-formulario',
                    'imagem' => asset('img/Forms-bro.png')
                ])
            </div>

            <div class="col-sm-4">
                @include('Components.link-card', [
                    'titulo' => 'Notas',
                    'icone' => 'bi bi-card-checklist',
                    'link' => route('formulario.create'),
                    'descricao' => 'Verifique suas notas de formulários',
                    'action' => 'ver-notas',
                    'imagem' => asset('img/Mathematics-amico.svg')
                ])
            </div>
        </div>

    </div>
</div>
