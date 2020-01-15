@extends('layouts.app')

@section('title', 'Lista de Ordenes | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
    @section('home-route', route('compass.home'))
    @section('nav-menu')
        @include('compass.menu')
    @endsection
@else
    @section('home-route', route('cliente.home'))
    @section('nav-menu')
        @include('cliente.menu')
    @endsection
@endif


@section('main')
    <div class="container">

        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
            <div class="card-body">
                <h5 class="card-title h4 text-center border-bottom">{{$centro->nombre}}</h5>
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Folio</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Estado</th>
                            @if ((Auth::user()->userable instanceof \App\Centro))
                                <th scope="col">Libreria</th>   
                            @endif
                            <th scope="col">Fecha de Creacion</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requerimientos as $requerimiento)
                            <tr>
                                <td>
                                    <a href="{{ route('pedidos.show',
                                    $requerimiento) }}">{{
                                    $requerimiento->folio ?? $requerimiento->id }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                                </td>
                                <td>{{ $requerimiento->estado }}</td>
                                @if ((Auth::user()->userable instanceof \App\Centro))
                                    <td>
                                        <agregar-libreria-component
                                            action="{{ route('libreria.editar', $requerimiento) }}"
                                            :library='@json(Auth::user()->hasRequerimiento($requerimiento))'></agregar-libreria-component>
                                    </td>
                                @endif
                                <td>{{ $requerimiento->created_at }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <modal-btn-component
                                            title="Orden de Pedido"
                                            :message='[
                                            { data:
                                            @json($requerimiento->productos),
                                            type: "Array", keys: ["sku",
                                            "detalle",
                                            "pivot", "total"], pivot: "cantidad"},
                                            { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                            ]'>Ver Orden de Pedido</modal-btn-component>
                                        @if (Auth::user()->userable instanceof \App\Centro)
                                            @if ( $requerimiento->estado === 'DESPACHADO')
                                                <a class="btn btn-success" href="{{ route('pedidos.entregado', $requerimiento) }}">Recibido</a>
                                            @endif
                                        @endif
                                        @if ( $requerimiento->estado === 'DESPACHADO')
                                            <modal-btn-component
                                                title="Orden de Pedido"
                                                :message='[
                                                { data: @json([
                                                "nombre" => $requerimiento->nombre_transportista,
                                                "rut" => $requerimiento->rut_transportista,
                                                "contacto" => $requerimiento->contacto_transportista
                                                ])
                                                , type: "Object", keys: ["nombre",
                                                "rut", "contacto"]}
                                                ]'>Ver Transporte</modal-btn-component>
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
                html: '{!! $msg['meta']['msg'] !!}',
                icon: 'success'
            }))();

        </script>
    @endsection
@endif
