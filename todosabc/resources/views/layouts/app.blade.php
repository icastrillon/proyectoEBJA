<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TodosABC') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alfa.css') }}" rel="stylesheet">

    <style>
        
        .mi_tabla {
            overflow-x: hidden !important;
            height: 400px !important;
            display: inline-block !important;
        }

        .lbl {
            background-color: #e6f7ff;
            font-weight: bold;
        }

    </style>
</head>
<body onload="marcar_todos();">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        @if (session('oferta'))
                            TodosABC ({{ session('oferta')->periodo }}): {{ session('oferta')->nombre }}
                        @else
                            TodosABC
                        @endif
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (session('user'))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ strtoupper(session('user')->apellidos) }} {{ strtoupper(session('user')->nombres) }} ( {{ session('user')->zona }} )<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                        @foreach (session('paginas') as $pagina)
                                        <li>
                                            <a href="{{ route($pagina->ruta, ['token' => session('token')]) }}">Paso {{ $loop->index + 1 }}: {{ $pagina->nombre }}</a>
                                        </li>
                                        @endforeach
                                    <li>
                                        <a href="{{ route('customLogout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar sesion
                                        </a>

                                        <form id="logout-form" action="{{ route('customLogout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ url('/customLogin') }}">Ingresar</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/todos.js') }}"></script>
    @if(is_null(session('user')))

    @elseif(session('user')->id_oferta==2 or session('user')->id_oferta==10)
    <script src="{{ asset('js/alfa.js') }}"></script>
    @elseif(session('user')->id_oferta==8 or session('user')->id_oferta==15)
    <script src="{{ asset('js/post.js') }}"></script>
    @elseif(session('user')->id_oferta==6 or session('user')->id_oferta==13)
    <script src="{{ asset('js/basica.js') }}"></script>
    @elseif(session('user')->id_oferta==7 or session('user')->id_oferta==14)
    <script src="{{ asset('js/bachillerato.js') }}"></script>
    @endif
</body>
</html>
