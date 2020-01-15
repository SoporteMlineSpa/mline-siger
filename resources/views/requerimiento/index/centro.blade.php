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
            @foreach ($centros as $centro)
              <tr>
                <th scope="row">{{ $loop->index }}</th>
                <td>{{ $centro->nombre }}</td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '1']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'VALIDADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '2']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'EN PROCESAMIENTO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '3']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'EN BODEGA')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '4']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'DESPACHADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '5']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'ENTREGADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '6']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'RECHAZADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '7']) }}">
                        Ver Todas
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
