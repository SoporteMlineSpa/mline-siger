@extends('layouts.app')

@section('title', 'Nuevo Producto | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h5 class="card-header font-bold text-xl">Nuevo Producto</h5>
            <div class="card-body">
                <form action="{{ route('productos.store') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2" for="sku">SKU:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="sku">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="detalle">Detalle:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="detalle">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="row">
                        <div class="container table-responsive">
                            <table  class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Empresa</th>
                                        <th scope="col">Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empresas as $empresa)
                                        <tr>
                                            <td>{{ $empresa->razon_social }}</td>
                                            <td>
                                                <input type="hidden"
                                                name="empresas[]"
                                                value="{{$empresa->id}}">
                                                <input class="form-control"type="text" name="precios[]">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <p class="text-danger">Si el precio se deja
                                    vacio, o en 0, entonces ese producto no
                                    estara disponible para esa Empresa</p>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
