<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulários Offline</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- bootstrap --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- jquery --}}
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('styles')
</head>
<body>
    {{--header--}}
    <header class="sticky-top">
        <nav class="navbar bg-light navbar-expand-sm">
            <div class="container">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    @if( auth()->check() )
                        <div>
                            <a href="{{route('home.index')}}" class="navbar-brand">
                                <b>{{ auth()->user()->nome }}</b>
                            </a>
                        </div>
                        <form action="{{ route('login.logout') }}" method="POST" class="me-2">
                            @csrf
                            <input type="submit" class="btn btn-outline-danger" value="Sair">
                        </form>
                    @else
                        <div>
                            <a href="#" class="navbar-brand">
                                Formulários Offline
                            </a>
                        </div>
                   @endif
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row pt-4">
            <div class="col-md-12">
                {{--mensagem padrão em todas paginas--}}
                @if( session()->has('success') )
                    <div class="alert alert-success alerta" role="alert">
                        {{ session()->get('success') }}
                    </div>
                @elseif( session()->has('error') )
                    <div class="alert alert-danger alerta" role="alert">
                        {{ session()->get('error') }}
                    </div>
                @endif
            </div>
        </div>
        {{--conteudo de cada pagina--}}
        @yield('content')
    </main>
    @stack('scripts')
    <script type="application/javascript">
        //sumir com o alerta depois de um tempo
        $(document).ready(()=>{
            setTimeout(()=>{
                $('.alerta').fadeOut('slow');
            }, 3000);
        })
    </script>
</body>
</html>
