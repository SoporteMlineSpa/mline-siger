@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="container">

    <div class="card">
      @if (isset($abastecimientos))
        <div class="card-header"><a class="btn btn-success" href="{{ route('pedidos.validarPedidos') }}">Validar Requerimientos</a></div>
        <div class="card-body">
          <!-- Usuario Empresa -->
          <div class="row">
            <div class="col">
              @if (!isset($requerimientos))
                <table id="datatable" class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Nombre</th>
                      <th scope="col">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($abastecimientos as $abastecimiento)
                      <tr>
                        <th scope="row">{{$loop->index}}</th>
                        <td>{{$abastecimiento->nombre}}</td>
                        <td><a href="{{ route('pedidos.abastecimiento', $abastecimiento)}}">Ver Ordenes de Pedido</a></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @else
              @endif
            </div>
          </div>
        @elseif (!isset($empresa) && isset($abastecimiento))

        @else
          <div class="alert alert-danger">{{$msg ?? ''}}</div>
        @endif
        </div>
    </div>

  </div>
@endsection

@section('js')
@endsection
