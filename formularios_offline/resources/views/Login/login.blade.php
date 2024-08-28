@extends('main')

@section('content')
<div class="row pt-2">
    <div class="col-sm-12">
        <h1>Login</h1>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <form action="{{ route('login.login') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="form-label" for="cpf">CPF</label>
                        <input type="text" name="login[cpf]" id="cpf" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="senha">Senha</label>
                        <input type="password" name="login[senha]" id="senha" class="form-control">
                    </div>
                </div>
            </div>
            <div class="d-flex pt-3 justify-content-end">
                <a href="{{ route('usuarios.create') }}" class="btn btn-outline-primary me-3">
                    Cadastrar
                </a>
                <button type="submit" class="btn btn-primary">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</div>
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
