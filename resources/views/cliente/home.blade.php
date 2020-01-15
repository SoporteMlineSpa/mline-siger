@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Dashboard</h3>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-tachometer-alt"></i> Accesos Directo:</h3>
                        <div class="d-flex flex-row justify-content-around align-items-end">
                            @if (Auth::user()->userable instanceof \App\Centro)
                                <a class="btn btn-outline-primary" href="{{ route('requerimientos.create') }}">
                                    <i class="fas fa-tasks"></i>
                                    Nueva Orden de Pedido
                                </a>
                            @else
                                <a class="btn btn-outline-primary" href="{{ route('pedidos.validar')}}">
                                    <i class="fas fa-tasks"></i>
                                    Validar Ordenes de Pedido
                                </a>
                            @endif
                            @if (Auth::user()->userable instanceof \App\Centro)
                                <a class="btn btn-outline-primary" href="{{ route('libreria.index') }}">
                                    <i class="fas fa-list"></i>
                                    Libreria Ordenes de Pedido
                                </a>
                            @else
                                <a class="btn btn-outline-primary" href="{{ route('presupuesto.create') }}">
                                    <i class="fas fa-wallet"></i>
                                    Cargar Presupuesto
                                </a>
                            @endif
                            @if (Auth::user()->userable instanceof \App\Centro)
                                <a class="btn btn-outline-primary" href="{{ route('presupuesto.indexCentro') }}">
                                    <i class="fas fa-money-check-alt"></i>
                                    Revisar Cuenta Corriente
                                </a>
                            @else
                                <a class="btn btn-outline-primary" href="{{ route('presupuesto.indexEmpresa') }}">
                                    <i class="fas fa-money-check-alt"></i>
                                    Revisar Cuenta Corriente
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-chart-line"></i> Reportes:</h3>
                        <div class="container">
                            <div class="row">
                                @switch (get_class(Auth::user()->userable))
                                @case('App\Centro')
                                <div class="col">
                                    @component('partials.index',
                                        ['type' => 0,
                                        'requerimientos' =>
                                        Auth::user()->userable
                                        ->requerimientos()
                                        ->whereYear('created_at', date("Y"))
                                        ->whereMonth('created_at', date("m"))
                                        ->get()])
                                    @endcomponent
                                </div>
                                @break
                                @case('App\Empresa')
                                <div class="col">
                                    @component('partials.index',
                                        ['type' => 1,
                                        'centros' =>
                                        Auth::user()->userable->centros()->get()])
                                    @endcomponent
                                </div>
                                @break
                                @case('App\Holding')
                                <div class="col">
                                    @component('partials.index',
                                        ['type' => 2,
                                        'empresas' =>
                                        Auth::user()->userable->empresas()->get()])
                                    @endcomponent
                                </div>
                                @break
                            @endswitch
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-bell"></i> Ultimas Notificaciones:</h3>
                        @component('partials.notifications', ['notifications' => $notifications])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
