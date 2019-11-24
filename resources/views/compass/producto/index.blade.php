@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <table id="datatable" class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Familia</th>
        <th scope="col">Detalle</th>
        <th scope="col">Marca</th>
        <th scope="col">Formato</th>
        <th scope="col">Precio</th>
        <th scope="col">Accion</th>
      </tr>
    </thead>
    <tbody>
      @foreach($productos as $producto)
        <tr>
          <th scope="row">{{ $loop->index }}</th>
          <td>{{ $producto->familia }}</td>
          <td>{{ $producto->detalle }}</td>
          <td>{{ $producto->marca }}</td>
          <td>{{ $producto->formato }}</td>
          <td>{{ $producto->precio }}</td>
          <td>
            <a class="btn btn-primary" href="{{route('productos.edit', $producto)}}"><i class="fas fa-edit"></i></a>
            <delete-btn-component action="{{ route('productos.destroy', $producto) }}"></delete-btn-component>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
