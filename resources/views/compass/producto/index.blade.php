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
        <div class="container mt-2">
        <div class="table-responsive">
            <table id="datatable" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">SKU</th>
                  <th scope="col">Detalle</th>
                  <th scope="col">Precio</th>
                  <th scope="col">Stock</th>
                  <th scope="col">Accion</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productos as $producto)
                  <tr>
                    <td>{{ $producto->sku }}</td>
                    <td>{{ $producto->detalle }}</td>
                    <td>{{ $producto->precio }}</td>
                    <td>{{ $producto->stock }}</td>
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
    </div>
  </div>
@endsection

@if (\Session::has('msg'))
    @php
        $msg = \Session::get('msg');
    @endphp
    @section('js')
        <script charset="utf-8">
            (Swal.fire({
                title: '{{$msg['meta']['title']}}',
                html: '{!! $msg['meta']['message'] !!}',
                icon: 'success'
            }))();

        </script>
    @endsection
@endif
