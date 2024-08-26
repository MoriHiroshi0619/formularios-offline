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
    @stack('styles')
</head>
<body>
    <div class="container">
        @if( auth()->user() )
            <div class="row pt-4">
                <div class="col-md-12">
                    {{ auth()->user()->nome }} | <a href="{{ route('login.logout') }}">Sair</a>
                </div>
            </div>
        @endif
        <div class="row pt-4">
            <div class="col-md-12">
                @if( session()->has('success') )
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success') }}
                    </div>
                @elseif( session()->has('error') )
                    <div class="alert alert-danger" role="alert">
                        {{ session()->get('error') }}
                    </div>
                @endif
            </div>
        </div>
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
