@extends('layouts.app')

@section('title', 'Validar Ordenes de Pedido')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="container">

    <div class="card">
      <div class="card-header">
        <a class="btn btn-success" href="{{ route('cliente.home') }}">Volver</a>
        <a class="btn btn-info" href="">Aceptar Todo</a>
      </div>
      <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          @foreach ($pedidos as $centro)
            <li class="nav-item">
              <a class="nav-link @if($loop->first) active @endif" id="{{$centro['centro']->nombre}}-tab" data-toggle="tab" href="#{{$centro['centro']->nombre}}" role="tab" aria-controls="{{$centro['centro']->nombre}}" aria-selected="true">{{$centro['centro']->nombre}}</a>
            </li>
          @endforeach
        </ul>

        <div class="tab-content" id="myTabContent">
          @foreach ($pedidos as $centro)
            <div class="tab-pane fade show @if($loop->first) active @endif" id="{{$centro['centro']->nombre}}" role="tabpanel" aria-labelledby="{{$centro['centro']->nombre}}-tab">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Ver</th>
                    <th scope="col">Validar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($centro['requerimientos'] as $pedido)
                    <tr>
                      <td>{{$pedido->nombre}}</td>
                      <td>{{$pedido->estado}}</td>
                      <td>
                        <modal-btn-component
                          title="Orden de Pedido"
                          :message='[
                          { data: @json($pedido->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                          ]'>Ver Orden de Pedido</modal-btn-component>
                      </td>
                      <td>
                        <a class="btn btn-success" href="{{ route('pedidos.aceptar', $pedido) }}">Aceptar</a>
                        <a class="btn btn-danger" href="{{ route('pedidos.rechazar', $pedido) }}">Rechazar</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
        </div>
      @endforeach
      </div>

    </div>
  </div>
  </div>
@endsection
