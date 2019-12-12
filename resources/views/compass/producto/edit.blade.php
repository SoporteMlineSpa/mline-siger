@extends('layouts.app')

@section('title', 'Editar Producto | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h5 class="card-header font-bold text-xl">Editar Producto</h5>
            <div class="card-body">
                <form action="{{route('productos.update', $producto)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group row">
                        <label class="col-sm-2" for="sku">SKU:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="sku" value="{{$producto->sku}}">
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="detalle">Detalle:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="detalle" value="{{$producto->detalle}}">
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="precio">Precio:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="precio" value="{{$producto->precio}}">
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="sku">Stock:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="stock" value="{{$producto->stock}}">
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="empresas">Empresas Asignadas:</label>
                        <span class="col-sm-6">
                            <select name="empresa[]" class="form-control" multiple>
                                <option value="0" @if($selected->search(0) !== false) selected @endif>Todas las Empresas</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{$empresa->id}}"  @if($selected->search($empresa->id) !== false) selected @endif>{{$empresa->razon_social}}</option>
                                @endforeach
                            </select>
                        </span>
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
