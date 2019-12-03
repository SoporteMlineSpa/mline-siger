@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="container">

    <div class="card">
      <div class="card-header"><a class="btn btn-success" href="{{ route('requerimientos.create') }}">Crear Orden de Pedido</a></div>
      <div class="card-body">
        <!-- Usuario Punto de Abastecimiento -->
        <h5 class="card-title h4 text-center border-bottom">{{$abastecimiento->nombre}}</h5>
        @component(
          'partials.datatable',[
            'headers' => ['detalle', 'estado'],
            'items' => $requerimiento,
            'index' => true,
            'acciones' => [
                [
                'type' => 'modal-btn',
                'label' => 'Ver Orden de Pedido',
                'data' => '[
                      { data: @json($requerimiento), type: "Object", keys: ["nombre", "estado"] },
                      { data: @json($requerimiento->productos()), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                    ]'
                ]
              ]
          ])
        @endcomponent
      </div>
    </div>

  </div>
@endsection

@section('js')
@endsection
