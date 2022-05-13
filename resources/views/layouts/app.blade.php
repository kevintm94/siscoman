<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/69cd288f43.js" crossorigin="anonymous"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/micss.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon" data-bs-target="#offcanvasExample"></span>
            </button>
            <a class="navbar-brand me-auto" href="{{url('/inicio')}}">JEGRILL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="/logout"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Cerrar Sesion') }}
                                </a>

                                <form id="logout-form" action="/logout" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    
    <div class="offcanvas offcanvas-start bg-dark text-white sidebar-nav" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-body p-0">
            <nav class="navbar-dark">
                <ul class="navbar-nav">
                    <li>
                        <div class="text-muted small fw-bold text-uppercase px-3 py-3">
                            MENU
                        </div>
                    </li>
                    @if (Auth::user()->rol == 'ADMIN')
                        <li>
                            <a href="{{ url('/usuarios') }}" class="nav-link px-3">
                                <span class="me-2">
                                    <i class="fas fa-users mr-2"></i>
                                </span>
                                <span>Empleados</span>
                            </a>
                        </li>    
                    @endif
                    @if (Auth::user()->rol == 'ADMIN' || Auth::user()->rol == 'CAJERA')
                        <li>
                            <a href="{{ url('/platos') }}" class="nav-link px-3">
                                <span class="me-2">
                                    <i class="fas fa-utensils"></i>
                                </span>
                                <span>Platos</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/guarniciones') }}" class="nav-link px-3">
                                <span class="me-2">
                                    <i class="fas fa-cheese"></i>
                                </span>
                                <span>Guarniciones</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/bebidas') }}" class="nav-link px-3">
                                <span class="me-2">
                                    <i class="fas fa-wine-bottle"></i>
                                </span>
                                <span>Bebidas</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->rol == 'ADMIN' || Auth::user()->rol == 'CAJERA' || Auth::user()->rol == 'MESERO')
                        <li>
                            <a href="{{ url('/pedidos') }}" class="nav-link px-3">
                                <span class="me-2">
                                    <i class="fas fa-clipboard-list"></i>
                                </span>
                                <span>Pedidos</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->rol == 'ADMIN')
                        <li>
                            <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#subMenu" role="button" aria-expanded="false" aria-controls="subMenu">
                            <span class="me-2"><i class="fas fa-file-alt"></i></span>
                            <span>Reportes</span>
                            <span class="right-icon ms-auto"><i class="fas fa-chevron-down"></i></span>
                            </a>
                            <div class="collapse" id="subMenu">
                                <div>
                                    <ul class="navbar-nav ps-3">
                                        <li>
                                            <a href="{{url('/reporte/pedidos')}}" class="nav-link px-3">
                                                <span>Reporte Pedidos</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('/reporte/platos')}}" class="nav-link px-3">
                                                <span>Reporte Platos</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('/reporte/guaniciones')}}" class="nav-link px-3">
                                                <span>Reporte Guarniciones</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('/reporte/bebidas')}}" class="nav-link px-3">
                                                <span>Reporte Bebidas</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                      @endif
                </ul>
            </nav>
        </div>
    </div>

    <main id="app" class="mt-5 pt-3">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>
    
</body>
</html>
