@extends('layouts.app')

@section('title', 'Crear Centro | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\Empresa))
    @section('home-route', route('cliente.home'))
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@endif

@section('nav-menu')
@if ((Auth::user()->userable instanceof \App\Empresa))
    @include('cliente.menu')
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @include('compass.menu')
@endif
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Nuevo Centro</h3>
            <div class="card-body">
                <form action="{{route('centros.update', $centro)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label class="col-sm-2" for="razon_social">Nombre:</label>
                        <span class="col-sm-6">
                            <input class="form-control" value="{{$centro->nombre}}" required type="text" name="nombre">
                        </span>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2" for="holding">Empresa Dueña:</label>
                        <span class="col-sm-6">
                            <select name="empresa" class="form-control">
                                @foreach ($empresas as $empresa)
                                    <option value="{{$empresa->id}}" @if($empresa->id == $centro->empresa_id) {{_("selected")}} @endif>{{$empresa->razon_social}}</option>
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
