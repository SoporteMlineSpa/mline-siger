@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">

        <div class="card">
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
                                <td>{{ $requerimiento->nombre }}</td>
                                <td>{{ $requerimiento->estado }}</td>
                                <td>{{ $requerimiento->created_at }}</td>
                                <td>
                                    <modal-btn-component
                                        title="Orden de Pedido"
                                        :message='[
                                        { data: @json($requerimiento->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                                        ]'>Ver Orden de Pedido</modal-btn-component>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
