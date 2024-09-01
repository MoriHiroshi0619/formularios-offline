@extends('main')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formulario.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Formularios - cadastrar</li>
        </ol>
    </nav>

    <div class="row pt-2">
        <div class="col-md-12">
            <h2>
                Novo Formalário
                <i class="bi bi-plus-lg"></i>
            </h2>
        </div>
    </div>

@endsection
