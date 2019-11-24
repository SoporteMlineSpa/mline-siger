@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="container">

    <div class="card">
      <div class="card-header"><a class="btn btn-success" href="{{ route('pedidos.create') }}">Crear Orden de Pedido</a></div>
      <div class="card-body">
        @if (isset($empresa))
          <!-- Usuario Empresa -->
          <div class="row">
            <div class="col-2">
              <v-select
                :options="[
                @foreach ($requerimientos as $abastecimiento)
                  { 'label': '{{$abastecimiento->nombre}}', 'value': {{$abastecimiento->id}} },
                @endforeach
                ]"
                ></v-select>
            </div>
            <div class="col">
              <table id="datatable" class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($abastecimiento as $requerimiento)
                    <tr id="{{$requerimiento->id}}">
                      <th scope="row">{{$loop->index}}</th>
                      <td>{{$requerimiento->nombre}}</td>
                      <td>{{$requerimiento->estado}}</td>
                      <td><button class="btn btn-primary">Ver Orden de Pedido</button></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @elseif (!isset($empresa) && isset($abastecimientos))

          <!-- Usuario Punto de Abastecimiento -->
          <h5 class="card-title h4 text-center border-bottom">{{$abastecimientos->nombre}}</h5>
          <table id="datatable" class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Detalle</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($requerimientos as $requerimiento)
                <tr>
                  <th scope="row">{{$loop->index}}</th>
                  <td>{{$requerimiento->nombre}}</td>
                  <td>{{$requerimiento->estado}}</td>
                  <td><button class="btn btn-primary">Ver Orden de Pedido</button></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="alert alert-danger">{{$msg}}</div>
        @endif
      </div>
    </div>

  </div>
@endsection

@section('js')
@endsection
