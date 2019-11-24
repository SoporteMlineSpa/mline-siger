<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>


    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/8ca19da716.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Style -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  </head>
  <body class="bg-grey-lightest font-sans leading-normal tracking-normal">
    <div id="app">

      <nav id="header" class="bg-white fixed w-full z-10 pin-t shadow">


        <div class="w-full container mx-auto flex flex-wrap items-center mt-0 pt-3 pb-3 md:pb-0">

          <div class="w-1/2 pl-2 md:pl-0">
            <a class="text-black text-base xl:text-xl no-underline hover:no-underline font-bold"  href="@yield('home-route')"> 
              SIGER
            </a>
          </div>
          <div class="w-1/2 pr-0">
            <div class="flex relative inline-block float-right">

              <div class="relative text-sm">
                @auth
                  <div class="dropdown">
                    <button id="userButton" type="button" id="dropdownMenuBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="flex items-center focus:outline-none mr-3">
                      <span class="hidden md:inline-block">Hola, {{ Auth::user()->name }} <i class="fas fa-caret-down"></i></span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuBtn">
                      <div class="dropdown-item">Mi Cuenta</div>
                      <div class="dropdown-item">Notificaciones</div>
                      <div class="dropdown-item">
                          <a
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                            >Salir</a>
                      </div>
                    </div>
                    
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </div>
                  </form>
                @endauth
              </div>

              <div class="block lg:hidden pr-4">
                <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-grey border-grey-dark hover:text-black hover:border-teal appearance-none focus:outline-none">
                  <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                </button>
              </div>

            </div>
          </div>

          @hasSection('nav-menu')
          <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-white z-20" id="nav-content">
            <ul class="list-reset lg:flex flex-1 items-center px-4 md:px-0">
              @yield('nav-menu')
            </ul>
          </div>
          @endif

        </div>
      </nav>

      <!--Container-->
      <main class="container w-full mx-auto pt-32">
        <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">
          @hasSection('content')
            @yield('content')
          @endif
          @hasSection('main')
            @yield('main')
          @endif
        </div>
      </main> 
      <!--/container-->

      <footer class="bg-white border-t border-grey-light shadow">	
        <div class="container max-w-md mx-auto flex py-8">
          <span class="mx-auto">
          Mline Siger. {{ date('Y') }}
          </span>
        </div>
      </footer>

    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')
  </body>
</html>
