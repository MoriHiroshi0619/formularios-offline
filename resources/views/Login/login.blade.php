<?php
    if(auth()->check()){
        return redirect()->route('home.index');
    }
?>

@extends('main')

@section('content')
<div class="row pt-2">
    <div class="col-sm-12">
        <h2>Login</h2>
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
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="login[cpf]" id="cpf" class="form-control cpf" inputmode="numeric">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="senha">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" name="login[senha]" id="senha" class="form-control" aria-describedby="basic-addon1">
                            <span class="input-group-text maozinha" data-action="alterar-senha-visibilidade">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex pt-3 justify-content-end">
                <!--Não será mais necessario uma tela para cadastro de usuarios-->
                {{--<a href="{{ route('usuarios.create') }}" class="btn btn-outline-primary me-3">
                    <i class="bi bi-person-plus"></i>
                    Cadastrar
                </a>--}}
                <button type="submit" class="btn btn-primary" data-action="entrar">
                    <div class="div-entrar">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar
                    </div>
                    <div class="d-none d-flex justify-content-center align-items-center gap-2 div-carregar-entrar">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Entrando...
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action="{{ route('login.login') }}"]');
            const submitButton = form.querySelector('button[data-action="entrar"]');

            form.addEventListener('submit', () => {
                submitButton.setAttribute('disabled', 'disabled');
                submitButton.querySelector('.div-entrar').classList.add('d-none');
                submitButton.querySelector('.div-carregar-entrar').classList.remove('d-none');
            });

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    submitButton.removeAttribute('disabled');
                    submitButton.querySelector('.div-entrar').classList.remove('d-none');
                    submitButton.querySelector('.div-carregar-entrar').classList.add('d-none');
                }
            });
        });

        $(document).ready(()=>{
            $('[data-action="alterar-senha-visibilidade"]').on('click', (e)=> {
                let inputSenha = $('#senha');
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
