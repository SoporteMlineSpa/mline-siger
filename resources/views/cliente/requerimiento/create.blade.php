@extends('layouts.app')

@section('title', 'Crear Orden de Pedido | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <table class="table" id="datatable">
    <thead>
      <tr>
        <th scope="col">Familia</th>
        <th scope="col">Detalle</th>
        <th scope="col">Marca</th>
        <th scope="col">Formato</th>
        <th scope="col">Cantidad</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($productos as $producto)
        <tr>
          <td>{{$producto->familia}}</td>
          <td>{{$producto->detalle}}</td>
          <td>{{$producto->marca}}</td>
          <td>{{$producto->formato}}</td>
          <td><input type="text" name="cantidad" class="form-control-sm bg-secondary text-white"></td>
        </tr>
      @endforeach
    </tbody>
  </table>

@endsection
