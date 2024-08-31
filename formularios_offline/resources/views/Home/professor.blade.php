<div class="row mt-4">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2>Professor</h2>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-4">
                @include('Components.link-card', [
                    'titulo' => 'Formulários',
                    'icone' => 'bi bi-receipt',
                    'link' => route('formulario.index'),
                    'descricao' => 'Crie e gerêncie seus formulários',
                    'action' => 'formularios',
                    'imagem' => asset('img/Research paper-amico.png')
                ])
            </div>

            <div class="col-sm-4">
                @include('Components.link-card', [
                    'titulo' => 'Correção',
                    'icone' => 'bi bi-pen',
                    'link' => route('formulario.create'),
                    'descricao' => 'Corrija os formulários de seus alunos',
                    'action' => 'correcao',
                    'imagem' => asset('img/Thesis-rafiki.svg')
                ])
            </div>

            <div class="col-sm-4">
                @include('Components.link-card', [
                    'titulo' => 'Alunos',
                    'icone' => 'bi bi-people',
                    'link' => route('formulario.create'),
                    'descricao' => 'Atualize e gerêncie seus alunos',
                    'action' => 'alunos',
                    'imagem' => asset('img/Webinar-amico.png')
                ])
            </div>
        </div>
    </div>
</div>
