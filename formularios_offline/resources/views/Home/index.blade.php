@extends('main')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">home</li>
        </ol>
    </nav>

    @if( auth()->user()->isAdmin() || auth()->user()->isProfessor() )
        @include('Home.professor')
    @endif
    @if( auth()->user()->isAdmin() || auth()->user()->isAluno() )
        @include('Home.aluno')
    @endif

@endsection
