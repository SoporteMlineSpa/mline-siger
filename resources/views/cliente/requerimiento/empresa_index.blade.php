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
        <!-- Usuario Empresa -->
        @component(
          'partials.datatable',[
            'headers' => ['nombre'],
            'items' => $centros,
            'index' => true,
            'acciones' => [
                [
                'type' => 'link',
                'label' => 'Ver Orden de Pedido',
                'data' => 'pedidos.centro'
                ]
              ]
          ])
        @endcomponent
      </div>
    </div>

  </div>
@endsection
