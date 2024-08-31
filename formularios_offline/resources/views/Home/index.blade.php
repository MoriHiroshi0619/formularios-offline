@extends('main')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">home</li>
        </ol>
    </nav>

    <br>
    @if( auth()->user()->isAdmin() || auth()->user()->isProfessor() )
        @include('Home.professor')
    @endif
    <br>
    @if( auth()->user()->isAdmin() || auth()->user()->isAluno() )
        @include('Home.aluno')
    @endif

@endsection
