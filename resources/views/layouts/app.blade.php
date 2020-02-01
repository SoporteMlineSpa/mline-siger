<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Style -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body class="bg-grey-lightest font-sans leading-normal tracking-normal">
        <div id="app">
            <header class="navbar navbar-dark bg-dark flex-md-nowrap p-0 shadow">
                @auth
                    <button
                        class="btn btn-outline-light"
                        type="button"
                        data-toggle="collapse"
                        data-target="#sidenav"
                        aria-expanded="false"
                        aria-controls="sidenav"
                        id="sidenavBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                @endif
                <a class="navbar-brand" href="@yield('home-route')">
                    <div class="d-flex flex-row align-items-baseline justify-content-center h3">
                        <img src="{{ asset('img/logocompassc1.png')}}" class="w-64 img-fluid" alt="Logo de MLine"/>
                        <span class="h1 text-primary ml-2 mb-1">SIGER</span>
                    </div>
                </a>

                @auth
                    <ul class="nav ml-auto text-light">

                        <li class="nav-item">
                            <a href="{{ route('notifications') }}" class="nav-link">
                                <i class="fas fa-bell"></i>
                                <span class="badge badge-danger">{{ count(Auth::user()->unreadNotifications) }}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="user-dropdown"
                               href=""
                               class="nav-link dropdown-toggle"
                               role="button"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                Hola, {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="user-dropdown">
                                <form action="{{ route('logout') }}" method="POST" accept-charset="utf-8">
                                    @csrf

                                    <button class="dropdown-item" type="submit">Salir</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                @endif
            </header>

            <div class="container-fluid">
                <div class="row">
                    <div id="sidenav" class="col-md-2 collapse">
                        <nav class="d-none d-md-block bg-light sidebar">
                            <div class="sidebar-sticky">
                                <div class="nav flex-column">
                                    @hasSection('nav-menu')
                                        @yield('nav-menu')
                                    @endif
                                </div>
                            </div>
                        </nav>
                    </div>

                    <main class="col pt-4">
                        @hasSection('main')
                            @yield('main')
                        @endif
                        @hasSection('content')
                            @yield('content')
                        @endif
                    </main>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer class="row border-top bg-light mt-5 p-5 align-items-center justify-content-center">
          <a href="https://www.mline.cl/web/" target="_blank" class="d-flex flex-row align-items-center">
            <img alt="Logo de Mline SPA" src="{{ asset('img/logo-mline.png') }}" class="w-32 img-fluid" />
            <i class="fas fa-copyright"></i>
            Todos los derechos reservados. {{date("Y")}}
          </a>
        </footer>
        <!-- /Footer -->

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" charset="utf-8"></script>
        @yield('js')

        @if (\Session::has('msg'))
            @php
                $msg = \Session::get('msg');
            @endphp
                <script charset="utf-8">
                    (Swal.fire({
                            title: '{{ isset($msg["meta"]["title"]) ? $msg["meta"]["title"] : "Accion Realizada" }}',
                      html: '{!! isset($msg["meta"]["msg"]) ? $msg["meta"]["msg"] : "Todo listo" !!}'
            }))();

                </script>
        @endif
    </body>
</html>

