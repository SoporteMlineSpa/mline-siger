@extends('layouts.app')

@section('title', 'Lista de Ordenes | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
    @section('home-route', route('compass.home'))
@else
    @section('home-route', route('cliente.home'))
@endif

@section('nav-menu')
    @if (Auth::user()->userable instanceof \App\CompassRole)
        @include('compass.menu')
    @else
        @include('cliente.menu')
    @endif
@endsection

@section('main')
    <div class="container">

        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
            <div class="card-body">
                <h5 class="card-title h4 text-center border-bottom">{{$centro->nombre}}</h5>
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
                                <td>
                                    <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                                </td>
                                <td>{{ $requerimiento->estado }}</td>
                                <td>{{ $requerimiento->created_at }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <modal-btn-component
                                            title="Orden de Pedido"
                                            :message='[
                                            { data: @json($requerimiento->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                                            ]'>Ver Orden de Pedido</modal-btn-component>
                                        @if (Auth::user()->userable instanceof \App\Centro)
                                            <a class="btn btn-info" href="{{ route('requerimientos.edit', $requerimiento) }}">Usar</a>
                                            @if ( $requerimiento->estado === 'DESPACHADO')
                                            <a class="btn btn-success" href="{{ route('pedidos.entregado', $requerimiento) }}">Recibido</a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
