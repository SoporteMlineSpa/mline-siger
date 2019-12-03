@extends('layouts.app')

@section('title', 'Crear Orden de Pedido | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="card">
    <header class="card-header">
      <h4 class="card-title h1">Nueva Orden de Pedido</h4>
    </header>
    <form class="card-body" action="{{ route('requerimientos.store') }}" method="POST">
      @csrf
      <button class="btn btn-success">Guardar</button>
      <table class="table" id="datatable">
        <thead>
          <tr>
            <th scope="col">SKU</th>
            <th scope="col">Detalle</th>
            <th scope="col">Cantidad</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($productos as $producto)
            <tr>
              <td>{{$producto->sku}}</td>
              <td>{{$producto->detalle}}</td>
              <td>
                <input type="hidden" value="{{$producto->id}}" name="id[]"/>
                <input type="text" name="cantidad[]" class="form-control">
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </form>
  </div>
  </div>

@endsection
