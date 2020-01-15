@extends('layouts.app')

@section('title', 'Armar Cajas')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Armar Cajas</h3>
            <div class="card-body">
                <form action="{{ route('compass.pedidos.armarCaja', $requerimiento) }}" method="POST" class="container mt-2">
                    @csrf

                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col text-right">Empresa:</div>
                                        <div class="col font-bold">{{ $requerimiento->centro->empresa->razon_social }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-right">RUT Empresa:</div>
                                        <div class="col font-bold">{{ $requerimiento->centro->empresa->rut }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-right">Centro:</div>
                                        <div class="col font-bold">{{ $requerimiento->centro->nombre }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-right">Nombre del Pedido:</div>
                                        <div class="col font-bold">{{ $requerimiento->nombre }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-right">Fecha de Creacion:</div>
                                        <div class="col font-bold">{{ $requerimiento->created_at }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 mx-auto">
                                            <button type="submit" class="btn btn-block btn-success">Despachar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="datatable-requerimiento" class="table">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Cantidad Solicitada</th>
                                <th scope="col">Real</th>
                                <th scope="col">Observaciones</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requerimiento->productos()->get() as $producto)
                                <tr>
                                    <input type="hidden" value="{{$producto}}" name="productos[]"/>
                                    <td>{{$producto->sku}}</td>
                                    <td>{{$producto->detalle}}</td>
                                    <td>{{$producto->pivot->cantidad}}</td>
                                    <td><input class="form-control form-control-sm" name="real[]" value="{{$producto->pivot->cantidad}}" type="text"></td>
                                    <td><input class="form-control form-control-sm" type="text" name="observaciones[]" value="{{$producto->pivot->observacion}}"></td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('cajas.cambiar', [$requerimiento, $producto]) }}">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    @endsection
