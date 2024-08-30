@extends('main')

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">home</li>
        </ol>
    </nav>

    @if( auth()->user()->ehAluno() )
        @include('Home.aluno')
    @else
        @include('Home.professor')
    @endif

@endsection
