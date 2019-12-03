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
        <h5 class="card-title h4 text-center border-bottom">{{$centro->nombre}}</h5>
        @component(
          'partials.datatable',[
          'headers' => ['nombre', 'estado'],
          'items' => $requerimientos,
          'index' => true,
          ])
        @endcomponent
      </div>
    </div>

  </div>
@endsection
