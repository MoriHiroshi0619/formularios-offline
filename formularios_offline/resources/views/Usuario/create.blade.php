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
            <h1>Cadastrar Aluno</h1>
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
                    <input type="text" name="aluno[cpf]" class="form-control" id="cpf" placeholder="seu CPF" required>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="aluno[email]" class="form-control" id="email" placeholder="seu email" required>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="aluno[password]" class="form-control" id="senha" placeholder="Sua senha" required>
                </div>
            </div>
            <div class="col-sm-6 px-3 py-2">
                <div class="form-group">
                    <label for="senha2">Confirmar senha</label>
                    <br>
                    <small id="senha2" class="form-text text-muted">Digite a mesma senha anterior para confirmar</small>
                    <input type="password" name="aluno[password2]" class="form-control" id="senha2" placeholder="Confirme sua senha" required>
                </div>
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-sm-2 pt-4">
                <button type="submit" class="btn btn-primary">Criar conta</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(()=>{
            $('#cpf').on('input', (e) => {
                let valor = $(e.target).val().replace(/\D/g, '');
                if (valor.length > 11) valor = valor.slice(0, 11);
                let formato = valor.replace(/(\d{3})(\d)/, '$1.$2');
                formato = formato.replace(/(\d{3})(\d)/, '$1.$2');
                formato = formato.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                $(e.target).val(formato);
            });
        });
    </script>
@endpush
