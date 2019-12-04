@extends('layouts.app')

@section('title', 'Verificar Pedidos')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">Verificar Ordenes de Pedidos</h3>
      <div class="card-body">
        <table id="datatable" class="table">
          <thead>
            <tr>
              <th scope="col">SKU</th>
              <th scope="col">Detalle</th>
              <th scope="col">Stock</th>
              <th scope="col">Cantidad Solicitada</th>
              <th scope="col">Diferencia</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productos as $producto)
              <tr>
                <td>{{ $producto->sku }}</td>
                <td>{{ $producto->detalle }}</td>
                <td>{{ $producto->stock }}</td>
                <td>{{ $cantidad = random_int(1, 10)}}</td>
                <td>{{ $producto->stock - $cantidad }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
