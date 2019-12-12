@extends('layouts.app')

@section('title', 'Editar Empresa | Mline SIGER')

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
            <h3 class="card-header font-bold text-xl">Nueva Empresa</h3>
            <div class="card-body">
                <form action="{{route('empresas.update', $empresa)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')

                    @if ($holdings->count() > 0 )
                        <div class="form-group">
                            <select name="holding">
                                @foreach($holdings as $holding)
                                    <option value="{{$holding->id}}">{{$holding->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="razon_social">Razon Social:</label>
                        <input class="form-control" type="text" name="razon_social" value="{{$empresa->razon_social ?? ''}}">
                    </div>

                    <div class="form-group">
                        <label for="rut">RUT:</label>
                        <input class="form-control" type="text" name="rut" value="{{$empresa->rut ?? ''}}">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Direccion:</label>
                        <input class="form-control" type="text" name="direccion" value="{{$empresa->direccion ?? ''}}">
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
