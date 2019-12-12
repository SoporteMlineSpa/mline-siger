@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="container">

    <div class="card">
      <div class="card-header"><a class="btn btn-success" href="{{ route('pedidos.validar') }}">Validar Ordenes de Pedidos</a></div>
      <div class="card-body">
        <table id="datatable" class="table">
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
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '0']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '1']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'VALIDADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '2']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'EN PROCESAMIENTO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '3']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'EN BODEGA')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '4']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'DESPACHADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '5']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'ENTREGADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '6']) }}">
                        {{ count($centro->requerimientos()->where('estado', 'RECHAZADO')->get()) }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '7']) }}">
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
@endsection
