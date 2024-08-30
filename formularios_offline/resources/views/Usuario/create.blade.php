@extends('main')
@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar - usuário</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <h2>Cadastrar Aluno</h2>
        </div>
    </div>
    <form action="{{ route('usuarios.store') }}" method="POST" class="pt-3">
        {{ csrf_field() }}
        <div class="row align-items-end">
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="aluno[nome]" class="form-control" id="nome" placeholder="seu nome" required>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" name="aluno[sobre_nome]" class="form-control" id="sobrenome" placeholder="seu sobrenome" required>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" name="aluno[cpf]" class="form-control cpf" id="cpf" placeholder="seu CPF" required>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <div class="input-group">
                        <input type="password" name="aluno[password]" class="form-control" id="senha" placeholder="Sua senha" required>
                        <span class="input-group-text maozinha" data-action="alterar-senha-visibilidade">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="senha2">Confirmar senha</label>
                    <br>
                    <small id="senha2" class="form-text text-muted">Digite a mesma senha anterior para confirmar</small>
                    <div class="input-group">
                        <input type="password" name="aluno[password2]" class="form-control" id="senha2" placeholder="Confirme sua senha" required>
                        <span class="input-group-text maozinha" data-action="alterar-senha-visibilidade">
                            <i class="bi bi-eye-slash"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-sm-2 pt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Criar conta
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(()=>{
            $('[data-action="alterar-senha-visibilidade"]').on('click', (e)=> {
                let inputSenha = $(e.currentTarget).closest('.input-group').find('input');
                let icone = $(e.currentTarget).find('i');
                if( inputSenha.attr('type') === 'password' ){
                    inputSenha.attr('type', 'text');
                    icone.removeClass('bi-eye-slash').addClass('bi bi-eye-fill');
                }else{
                    inputSenha.attr('type', 'password');
                    icone.removeClass('bi bi-eye-fill').addClass('bi-eye-slash');
                }
            });
        });
    </script>
@endpush
