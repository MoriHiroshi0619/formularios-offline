@extends('main')

@section('content')
<div class="row pt-2">
    <div class="col-md-12">
        <h1>Login</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('login.login') }}" method="POST">
            {{ csrf_field() }}
            <div class="row form-group">
                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="login[email]" id="email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="senha">Senha</label>
                    <input type="password" name="login[senha]" id="senha" class="form-control">
                </div>
                <div class="col-md-4 pt-3">
                    <button type="submit" class="btn btn-primary">
                        Entrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
