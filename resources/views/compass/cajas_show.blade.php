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
                <b>Nombre:</b> {{$requerimiento->nombre}} <br>
                <b>Fecha de Creacion:</b> {{$requerimiento->created_at}} <br>
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th scope="col">SKU</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Cantidad Solicitada</th>
                            <th scope="col">Real</th>
                            <th scope="col">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requerimiento->productos()->get() as $producto)
                            <tr>
                                <td>{{$producto->sku}}</td>
                                <td>{{$producto->detalle}}</td>
                                <td>{{$producto->pivot->cantidad}}</td>
                                <td><input class="form-control form-control-sm" type="text"></td>
                                <td><textarea name="observaciones" class="form-control form-control-sm"></textarea></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
