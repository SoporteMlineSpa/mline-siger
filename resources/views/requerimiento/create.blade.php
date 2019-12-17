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
      <div class="row align-items-end">
          <div class="form-group mx-2">
              <label for="nombre">Nombre:</label>
              <input type="text" name="nombre" class="form-control"/>
          </div>
          <div class="form-group">
              <button class="btn btn-success">Guardar</button>
          </div>
      </div>
      <div class="row">
          <div class="container">
              <div class="table-responsive">
                  <table class="table table-sm" id="datatable">
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
                          <td>{{$producto->sku}}
                            <input type="hidden" value="{{$producto->id}}" name="id[]"/>
                          </td>
                          <td>{{$producto->detalle}}</td>
                          <td>
                            <input type="text" name="cantidad[]" class="border rounded">
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
              </div>
          </div>
      </div>
    </form>
  </div>
  </div>

@endsection
