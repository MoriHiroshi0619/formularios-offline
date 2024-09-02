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
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    Novo Formalário
                    <i class="bi bi-plus-lg"></i>
                </h2>

                <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#tutorial-criar-formulario" aria-expanded="false" aria-controls="tutorial-criar-formulario">
                    <i class="bi bi-question-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="collapse" id="tutorial-criar-formulario">
        <div class="card card-body">
            Ainda tenho que pensar em um texto para descrever o processo de criação de um formulário
        </div>
    </div>

    @include('Formulario.parts.form-criar-formulario')

@endsection
