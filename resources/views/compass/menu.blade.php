<li class="nav-item">
    <a class="nav-link" href="{{ route('compass.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-list mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('pedidos.indexEmpresa')}}">Lista</a>
        @if (Auth::user()->userable->name === 'Compras')
            <a class="dropdown-item" href="{{ route('compass.pedidos.verificar')}}">Verificar</a>
        @endif
        @if (Auth::user()->userable->name === 'Despacho')
            <a class="dropdown-item" href="{{ route('compass.pedidos.cajasIndex')}}">Armar Cajas</a>
            <a class="dropdown-item" href="{{ route('compass.pedidos.programarDespachos')}}">Programar Despachos</a>
            <a class="dropdown-item" href="{{ route('compass.pedidos.despachar')}}">Despachar</a>
        @endif
    </div>
</li>
@if (Auth::user()->userable->name === 'Compras')
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-th-list mr-2"></i>
            Productos
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('productos.index')}}">Lista</a>
            <a class="dropdown-item"
               href="{{route('productos.indexEmpresa')}}">Lista por Empresa</a>
            <a class="dropdown-item" href="{{route('productos.create')}}">Nuevo</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{route('productos.cargaMasivaView')}}">Carga Masiva</a>
            <a class="dropdown-item" href="{{route('productos.asignacionMasivaView')}}">Asignacion masiva</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCentros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-route mr-2"></i>
            Puntos de Abastecimientos
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('abastecimientos.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('abastecimientos.create')}}">Nuevo</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCentros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-address-card mr-2"></i>
            Bodegueros
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('bodegueros.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('bodegueros.create')}}">Nuevo</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownHoldings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-building mr-2"></i>
            Holdings
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('holdings.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('holdings.create')}}">Nuevo</a>
            <a class="dropdown-item" href="{{route('usuarios.index', 'h')}}">Usuarios</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownEmpresas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-industry mr-2"></i>
            Empresas
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
            <a class="dropdown-item" href="{{route('usuarios.index', 'e')}}">Usuarios</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item"
                href="{{route('horarios.create')}}">Asignar Horarios</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCentros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-warehouse mr-2"></i>
            Centros de Cultivos
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
            <a class="dropdown-item" href="{{route('usuarios.index', 'c')}}">Usuarios</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownUsuarios" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-users mr-2"></i>
            Usuarios
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('usuarios.index', 'r')}}">Usuarios Compass</a>
            <a class="dropdown-item" href="{{route('usuarios.index')}}">Todos los Usuarios</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{route('register')}}">Nuevo Usuario</a>
            <a class="dropdown-item" href="{{route('usuarios.asignar')}}">Asignar Usuario</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownReportes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-list mr-2"></i>
            Reportes
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('reportes.productosCantidad')}}">Productos Por Cantidad</a>
            <a class="dropdown-item" href="{{route('reportes.packs')}}">Generar Packs</a>
            <a class="dropdown-item" href="{{route('reportes.productos')}}">Rebaja de Productos</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('cargarFolios') }}">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Cargar Folios
        </a>
    </li>
@endif
