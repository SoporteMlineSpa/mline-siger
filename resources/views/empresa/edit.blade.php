@extends('layouts.app')

@section('title', 'Editar Empresa | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\Holding))
    @section('home-route', route('cliente.home'))
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@endif

@section('nav-menu')
    @if ((Auth::user()->userable instanceof \App\Holding))
        @include('cliente.menu')
    @elseif (Auth::user()->userable instanceof \App\CompassRole)
        @include('compass.menu')
    @endif
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Editar Empresa</h3>
            <div class="card-body">
                <form action="{{route('empresas.update', $empresas)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')

                    @if ($holdings->count() > 0 )
                        <div class="form-group row">
                            <label class="col-sm-2" for="holding">Holding:</label>
                            <div class="col-sm-6">
                                <select name="holding" class="form-control">
                                    <option value="">Sin Holding</option>
                                    @foreach($holdings as $holding)
                                        <option value="{{$holding->id}}" @if($holding->id == $empresas->holding_id) {{_("selected")}} @endif>{{$holding->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-2" for="razon_social">Razon Social:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="razon_social" value="{{$empresas->razon_social ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="rut">RUT:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="rut" value="{{$empresas->rut ?? ''}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2" for="direccion">Direccion:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="direccion" value="{{$empresas->direccion ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="abastecimiento">Punto de Abastecimiento:</label>
                        <div class="col-sm-6">
                            <select name="abastecimiento" class="form-control">
                                @foreach($abastecimientos as $abastecimiento)
                                    <option value="{{$abastecimiento->id}}" @if($abastecimiento->id == $empresas->abastecimiento_id) {{_("selected")}} @endif>{{$abastecimiento->nombre}}</option>
                                @endforeach
                            </select>
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
