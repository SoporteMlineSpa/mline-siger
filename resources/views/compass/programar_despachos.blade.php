@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl">{{
            Auth::user()->getNombreRelacionado() }}: Programar Despachos</div>
            <div class="card-body">
                @if ($requerimientos->count() > 0)

                    <form class="container" action="{{ route('compass.pedidos.programarDespachos') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <label class="col-md col-form-label text-right" for="nombre">Nombre Transportista:</label>
                                    <span class="col-md"><input class="form-control" name="nombre" type="text"></span>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-md col-form-label text-right" for="rut">RUT Transportista:</label>
                                    <span class="col-md"><input class="form-control" name="rut" type="text"></span>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-md col-form-label text-right" for="contacto">Contacto Transportista:</label>
                                    <span class="col-md"><input class="form-control" name="contacto" type="text"></span>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-md col-form-label text-right" for="fecha">Fecha de Despacho:</label>
                                    <span class="col-md"><input class="form-control" name="fecha_despacho" type="date"></span>
                                </div>
                                <div class="form-group form-row">
                                    <label class="col-md col-form-label text-right" for="destino">Punto de Abastecimiento</label>
                                    <span class="col-md">
                                        <select class="form-control" name="destino">
                                            @foreach ($abastecimientos as $abastecimiento)
                                                <option value="{{ $abastecimiento->id }}">{{ $abastecimiento->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-md-4 mx-auto"><button class="btn btn-primary" type="submit">Programar Despacho</button></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col container table-sm">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Seleccionar</th>
                                            <th scope="col">Folio</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Centro</th>
                                            <th scope="col">Empresa</th>
                                            <th scope="col">Fecha de Solicitud</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requerimientos as $requerimiento)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="seleccionados[]">
                                                        <label class="form-check-label">
                                                            Incluir
                                                        </label>
                                                        <input name="requerimientos[]" type="hidden" value="{{ $requerimiento->id }}">
                                                    </div>
                                                </td>
                                                <th scope="row">{{ $requerimiento->id }}</th>
                                                <td>{{ $requerimiento->nombre }}</td>
                                                <td>{{ $requerimiento->centro->nombre }}</td>
                                                <td>{{ $requerimiento->centro->empresa->razon_social }}</td>
                                                <td>{{ $requerimiento->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                @else 
                    <div class="alert alert-dark">Sin <a class="alert-link" href="{{ route('compass.pedidos.cajasIndex')}}">Cajas</a> disponibles para despachar</div>
                @endif
            </div>
        </div>
    </div>
@endsection
