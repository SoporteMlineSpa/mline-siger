<li class="nav-item">
    <a class="nav-link" href="{{ route('cliente.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-tasks fa-fw mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('requerimientos.index')}}">Lista</a>
        @if(Auth::user()->userable instanceof App\Empresa)
            <a class="dropdown-item" href="{{ route('pedidos.validar')}}">Validar</a>
        @endif
        @if(Auth::user()->userable instanceof App\Centro)
            <a class="dropdown-item" href="{{ route('requerimientos.create')}}">Nuevo</a>
        @endif
    </div>
</li>
@if(Auth::user()->userable instanceof App\Holding)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-industry mr-2"></i>
            Empresas
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
        </div>
    </li>
@endif
@if(Auth::user()->userable instanceof App\Empresa)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-warehouse mr-2"></i>
            Centros
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
            <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
        </div>
    </li>
@endif
