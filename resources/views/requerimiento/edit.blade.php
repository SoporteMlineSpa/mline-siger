@extends('layouts.app')

@section('title', 'Crear Orden de Pedido | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="card">
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->userable->nombre }}: Nueva Orden de Pedido</h3>
        <form class="card-body" action="{{ route('requerimientos.store') }}" method="POST">
            @csrf
            <div class="form-group row align-items-end">
                <label class="col-md-2 text-right" for="nombre">Identificador:</label>
                <span class="col-md-6">
                    <input class="form-control-plaintext" value="{{ $nombre }}"
                    type="text" name="nombre">
                </span>
                <div class="col-md">
                    <button class="btn btn-success" type="submit">Solicitar</button>
                </div>
            </div>
            <div class="row align-items-end">
                <div class="alert alert-info container table-responsive">
                    Esta Orden de Pedido incluye los siguientes productos:
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requerimiento->productos()->get() as $producto)
                                <tr>
                                    <td>{{$producto->sku}}</td>
                                    <td>{{$producto->detalle}}</td>
                                    <td>{{$producto->pivot->cantidad}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="container table-responsive">
                    <table class="table" id="datatable-requerimiento">
                        <thead>
                            <tr>
                                <th scope="col">SKU</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr>
                                    <td>{{$producto->sku}}
                                        <input type="hidden" value="{{$producto->id}}" name="id[]"/>
                                    </td>
                                    <td>{{$producto->detalle}}</td>
                                    <td>
                                        <input type="text" name="cantidad[]" maxlength="13" value="{{$producto->pivot->cantidad}}" class="border rounded p-1" style="width: 7rem;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
    </div>

@endsection
