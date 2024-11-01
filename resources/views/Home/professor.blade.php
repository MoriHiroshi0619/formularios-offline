<div class="row mt-4">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2>Formulários</h2>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-6">
                @include('Components.link-card', [
                    'titulo' => 'Formulários',
                    'icone' => 'bi bi-receipt',
                    'link' => route('formulario.index'),
                    'descricao' => 'Crie e gerêncie seus formulários',
                    'action' => 'formularios',
                    'imagem' => asset('img/Research paper-amico.png')
                ])
            </div>

            <div class="col-sm-6">
                @include('Components.link-card', [
                    'titulo' => 'Resultados',
                    'icone' => 'bi bi-pen',
                    'link' => route('resultado.index'),
                    'descricao' => 'Verifique os resultados dos formulários',
                    'action' => 'correcao',
                    'imagem' => asset('img/Thesis-rafiki.svg')
                ])
            </div>
        </div>
    </div>
</div>
