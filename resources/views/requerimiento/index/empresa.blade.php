@extends('layouts.app')

@section('title', 'Lista de Pedidos | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
    @section('home-route', route('compass.home'))
@else
    @section('home-route', route('cliente.home'))
@endif

@section('nav-menu')
    @if (Auth::user()->userable instanceof \App\CompassRole)
        @include('compass.menu')
    @else
        @include('cliente.menu')
    @endif
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
            <div class="card-body">
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="2">#</th>
                                    <th scope="col" rowspan="2">Nombre</th>
                                    <th class="text-center" scope="row" colspan="7">Estados</th>
                                    <th scope="col" rowspan="2">Ver Todos</th>
                                </tr>
                                <tr>
                                    <th scope="col">Esperando Validacion</th>
                                    <th scope="col">Validado</th>
                                    <th scope="col">En Procesamiento</th>
                                    <th scope="col">En Bodega</th>
                                    <th scope="col">Despachado</th>
                                    <th scope="col">Entregado</th>
                                    <th scope="col">Rechazado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empresas as $empresa)
                                    <tr>
                                        <th scope="row">{{ $loop->index }}</th>
                                        <td>{{ $empresa->razon_social }}</td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 0])}}">
                                                {{ count($empresa->getRequerimientoByEstado('ESPERANDO VALIDACION')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 1])}}">
                                                {{ count($empresa->getRequerimientoByEstado('VALIDADO')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 2])}}">
                                                {{ count($empresa->getRequerimientoByEstado('EN PROCESAMIENTO')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 3])}}">
                                                {{ count($empresa->getRequerimientoByEstado('EN BODEGA')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 4])}}">
                                                {{ count($empresa->getRequerimientoByEstado('DESPACHADO')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 5])}}">
                                                {{ count($empresa->getRequerimientoByEstado('ENTREGADO')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 6])}}">
                                                {{ count($empresa->getRequerimientoByEstado('RECHAZADO')) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 7])}}">
                                                Ver Todos
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
