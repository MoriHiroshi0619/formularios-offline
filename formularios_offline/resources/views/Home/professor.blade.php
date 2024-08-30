<div class="row mt-4">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2>Olá, {{ auth()->user()->nome }}</h2>
                <p>Seja bem-vindo ao sistema de gerenciamento para professores</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-4">

                <a class="animate-on-hover" href="{{ route('formulario.index') }}">
                    <div class="card btn btn-outline-primary">
                        <div class="card-body">
                            <h5 class="card-title">
                                Formulários
                                <i class="bi bi-receipt"></i>
                            </h5>
                            <p class="card-text">
                                Crie e gerencie formulários para seus alunos
                            </p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
