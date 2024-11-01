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
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    {{-- css global --}}
    <link href="{{ mix('css/projeto.css') }}" rel="stylesheet">
    <!-- fontes -->
    <link href="{{ mix('css/fonts.css') }}" rel="stylesheet">
    {{-- jquery + js do bootstrap    --}}
    <script src="{{ mix('js/app.js') }}"></script>
    {{-- funcoes globais --}}
    <script src="{{ mix('js/functions.js') }}"></script>

    @stack('styles')

</head>
<body>
    {{--header--}}
    <header class="sticky-top">
        <nav class="navbar navbar-expand-sm py-3">
            <div class="container">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    @if( auth()->check() )
                        <div>
                            <a href="{{route('home.index')}}" class="navbar-brand fs-3 titulo" title="voltar para a home">
                                {{ auth()->user()->nome }}
                            </a>
                        </div>
                        <form action="{{ route('login.logout') }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-danger" title="Deslogar do sistema">
                                <i class="bi bi-box-arrow-in-left"></i>
                                Sair
                            </button>

                        </form>
                    @else
                        <div>
                            <a href="{{ route('visitantes.formularios.index') }}" class="navbar-brand fs-3 titulo">
                                Formulários Offline
                            </a>
                        </div>
                        @if(request()->routeIs('visitantes.*'))
                            <div>
                                <a href="{{ route('login.index') }}" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                        Entrar
                                </a>
                            </div>
                        @endif

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
                    <div class="alert alert-success" id="alert-ban-sucesso" role="alert">
                        {{ session()->get('success') }}
                    </div>
                @elseif( session()->has('error') )
                    <div class="alert alert-danger" id="alert-ban-erro" role="alert">
                        <div class="d-flex align-items-center justify-content-between">
                            {{ session()->get('error') }} <i class="bi bi-x-square maozinha" data-action="remover-alert"></i>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {{--conteudo de cada pagina--}}
        @yield('content')
    </main>
    @stack('scripts')
    <script type="application/javascript">
        $(document).ready(()=>{
            $('[data-action="remover-alert"]').on('click', ()=>{
                $('#alert-ban-erro').fadeOut('slow');
            });

            setTimeout(()=>{
                $('#alert-ban-sucesso').fadeOut('slow');
            }, 3000);
        })
    </script>
</body>
</html>
