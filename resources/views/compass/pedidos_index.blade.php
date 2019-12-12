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
                <div class="d-flex flex-row">
                    <a class="btn btn-success mr-2" href="{{ route('compass.home') }}">Volver</a>
                @if (Auth::user()->userable->name === 'Compras')
                    <a class="btn btn-primary" href="{{ route('compass.pedidos.verificar')}}">Verificar</a>
                @endif
                </div>
                <div class="container mt-2">
                <div class="table-responsive">
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
        </div>
    </div>
@endsection
