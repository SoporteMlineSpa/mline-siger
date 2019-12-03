@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">Lista de Productos</h3>
      <div class="card-body">
        <table id="datatable" class="table">
          <thead>
            <tr>
              <th scope="col">SKU</th>
              <th scope="col">Detalle</th>
              <th scope="col">Precio</th>
              <th scope="col">Accion</th>
            </tr>
          </thead>
          <tbody>
            @foreach($productos as $producto)
              <tr>
                <td>{{ $producto->sku }}</td>
                <td>{{ $producto->detalle }}</td>
                <td>{{ $producto->precio }}</td>
                <td>
                  <a class="btn btn-primary" href="{{route('productos.edit', $producto)}}"><i class="fas fa-edit"></i></a>
                  <delete-btn-component action="{{ route('productos.destroy', $producto) }}"></delete-btn-component>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
