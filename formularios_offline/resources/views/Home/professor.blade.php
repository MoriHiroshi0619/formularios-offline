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
                    'action' => 'formularios'
                ])

            </div>
        </div>
    </div>
</div>
