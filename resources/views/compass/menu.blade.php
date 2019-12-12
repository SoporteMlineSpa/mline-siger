<li class="nav-item">
    <a class="nav-link" href="{{ route('compass.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-list mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('compass.pedidos.index')}}">Lista</a>
        @if (Auth::user()->userable->name === 'Compras')
            <a class="dropdown-item" href="{{ route('compass.pedidos.verificar')}}">Verificar</a>
        @endif
        @if (Auth::user()->userable->name === 'Despacho')
            <a class="dropdown-item" href="{{ route('compass.pedidos.cajasIndex')}}">Armar Cajas</a>
        @endif
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-th-list mr-2"></i>
        Productos
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('productos.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('productos.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-building mr-2"></i>
        Holdings
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="">Lista</a>
        <a class="dropdown-item" href="">Nuevo</a>
        <a class="dropdown-item" href="">Usuarios</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-industry mr-2"></i>
        Empresas
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
        <a class="dropdown-item" href="">Usuarios</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-warehouse mr-2"></i>
        Centros
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
        <a class="dropdown-item" href="">Usuarios</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-home mr-2"></i>
        Compass
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="">Usuarios</a>
    </div>
</li>
