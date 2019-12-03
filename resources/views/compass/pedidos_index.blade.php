@extends('layouts.app')

@section('title', 'Lista de Pedidos')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
      <div class="card-body">
        <a class="btn btn-success" href="{{ route('compass.pedidos.verificar')}}">Verificar</a>
        <table id="datatable" class="table">
          <thead>
            <tr>
              <th scope="col">Nombre</th>
              <th scope="col">Estado</th>
              <th scope="col">Fecha de Creacion</th>
              <th scope="col">Accion</th>
            </tr>
          </thead>
          <tbody>
            @foreach($requerimientos as $requerimiento)
              <tr>
                <td>{{ $requerimiento->nombre }}</td>
                <td>{{ $requerimiento->estado }}</td>
                <td>{{ $requerimiento->created_at }}</td>
                <td>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
