@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formularios</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Formalários
                    <i class="bi bi-receipt"></i>
                </h2>

                <a class="btn btn-primary float-end" href="{{ route('formulario.create') }}">
                    <i class="bi bi-plus-lg"></i>
                    Criar formulário
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @if( $formularios->isEmpty() )
            <div class="col-sm-12">
                <div class="alert alert-warning" role="alert">
                    Você ainda não possui formulários cadastrados
                </div>
            </div>
        @else
            {{--paginação aqui--}}
            ainda não pensei nesse ponto
        @endif
    </div>


@endsection
