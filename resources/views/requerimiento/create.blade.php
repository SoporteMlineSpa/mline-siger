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
            <div class="row">
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-sm" id="datatable-requerimiento">
                            <thead>
                                <tr>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Detalle</th>
                                    <th scope="col">Precio</th>
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
                                        <td>{{ $producto->pivot->precio }}</td>
                                        <td>
                                            <input type="text" name="cantidad[]" maxlength="13" class="border rounded p-1" style="width: 7rem;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>

@endsection
