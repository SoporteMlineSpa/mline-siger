@extends('layouts.app')

@section('title', 'Verificar Pedidos')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Verificar Ordenes de Pedidos</h3>
            <div class="card-body">
                <div class="d-flex flex-row mb-2">
                    <a class="btn btn-success mr-2" href="{{ route('compass.home') }}">Volver</a>
                    <form action="{{ route('compass.verificar')}}" method="POST" accept-charset="utf-8">
                        @csrf

                        <button class="btn btn-primary">Enviar a Bodega</button>
                    </form>
                </div>
                <div class="container mt-2">
                <div class="table-responsive">
                    <table id="datatable" class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Cantidad Solicitada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                                <tr>
                                    <td>{{ $producto->sku }}</td>
                                    <td>{{ $producto->detalle }}</td>
                                    <td>{{ $producto->cantidad}}</td>
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
