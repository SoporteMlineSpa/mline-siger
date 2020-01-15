<li class="nav-item">
    <a class="nav-link" href="{{ route('cliente.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-tasks fa-fw mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        @switch(get_class(Auth::user()->userable))
            @case('App\Centro')
                <a class="dropdown-item" href="{{route('pedidos.centro', Auth::user()->userable->id)}}">Lista</a>
                <a class="dropdown-item" href="{{ route('requerimientos.create')}}">Nuevo</a>
                <a class="dropdown-item" href="{{
                route('libreria.index')}}">Libreria</a>
            @break
            @case('App\Empresa')
                <a class="dropdown-item" href="{{route('pedidos.indexCentro')}}">Lista</a>
                <a class="dropdown-item" href="{{ route('pedidos.validar')}}">Validar</a>
            @break
            @case('App\Holding')
                <a class="dropdown-item" href="{{route('pedidos.indexEmpresa')}}">Lista</a>
            @break
        @endswitch
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-wallet mr-2"></i>
        Presupuesto
    </a>
    <div class="dropdown-menu">
        @if(Auth::user()->userable instanceof App\Holding)
            <a class="dropdown-item" href="{{route('presupuesto.create')}}">Cargar</a>
            <a class="dropdown-item" href="{{route('presupuesto.indexHolding')}}">Cuenta Corriente</a>
        @elseif(Auth::user()->userable instanceof App\Empresa)
            <a class="dropdown-item" href="{{route('presupuesto.create')}}">Cargar</a>
            <a class="dropdown-item" href="{{route('presupuesto.indexEmpresa')}}">Cuenta Corriente</a>
            <a class="dropdown-item" href="{{route('presupuesto.cmi')}}">Consolidado</a>
        @else
            <a class="dropdown-item" href="{{route('presupuesto.indexCentro')}}">Cuenta Corriente</a>
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
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-users mr-2"></i>
            Usuarios de Centro
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('user.indexEmpresa')}}">Lista</a>
            <a class="dropdown-item" href="{{route('user.create')}}">Nuevo</a>
        </div>
    </li>
@endif
