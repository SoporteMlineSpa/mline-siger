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
                    <h3 class="card-header font-bold text-xl">Datos de Orden de Pedido</h3>
                    <div class="card-body">
                        <div class="row">
                            <div class="card col-md-5">
                                <div class="card-body">
                                    <h4 class="card-title text-xl font-bold border-bottom">Datos Empresa</h4>
                                    <b>Razon Social: </b>{{ $empresa->razon_social }}<b class="ml-3">RUT Empresa: </b>{{ $empresa->rut }} <br />
                                    <b>Direccion: </b>{{ $empresa->direccion }} <br />
                                    <b>Punto de Abastecimiento: </b>{{ $empresa->abastecimiento->nombre }}
                                </div>
                            </div>
                            <div class="card col-md-4 offset-1">
                                <div class="card-body">
                                    <h4 class="card-title text-xl font-bold border-bottom">Datos Centro</h4>
                                    <b>Nombre: </b>{{ $centro->nombre }}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="card col-md-5">
                                <div class="card-body">
                                    <h4 class="card-title text-xl font-bold border-bottom">Datos Orden de Pedido</h4>
                                    <b>Nombre: </b>{{ $requerimiento->nombre }} <br />
                                    <b>Fecha de Creacion: </b>{{ $requerimiento->created_at }} <br />
                                    <b>Estado: </b>{{ $requerimiento->estado }} <br />
                                    <b>Ultima Actualizacion: </b>{{ $requerimiento->updated_at }} <br />
                                    <b>Total: </b>$ {{ number_format($requerimiento->getTotal(), 0) }}
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="container table-responsive">
                                <table id="datatable" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">Detalle</th>
                                            <th scope="col">Cantidad Solicitada</th>
                                            <th scope="col">Cantidad Despachada</th>
                                            <th scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productos as $producto)
                                            <tr>
                                                <td>{{ $producto->sku }}</td>
                                                <td>{{ $producto->detalle }}</td>
                                                <td>{{ $producto->pivot->cantidad ?? 0 }}</td>
                                                <td>{{ $producto->pivot->real ?? 'Sin Despachar' }}</td>
                                                <td>{{ $producto->pivot->observacion ?? 'Sin Observaciones' }}</td>
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
