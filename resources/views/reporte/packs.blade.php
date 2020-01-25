@extends('layouts.app')

@section('title', 'Generar Packs | MLine SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Generar Packs</h3>
            <div class="card-body">
                <div class="d-flex flex-row mb-2">
                </div>
                <div class="container mt-2">
                    <form action="{{ route('reportes.packs.generar') }}" method="POST" accept-charset="utf-8">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2" for="inicio">Fecha de Inicio:</label>
                            <span class="col-sm-6">
                                <input class="form-control" required type="date" name="inicio">
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2" for="fin">Fecha de Fin:</label>
                            <span class="col-sm-6">
                                <input class="form-control" required type="date" name="fin">
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2" for="empresa">Empresa:</label>
                            <span class="col-sm-6">
                                <select name="empresa" class="form-control">
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                                    @endforeach
                                </select>
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <button class="btn btn-primary">Generar Pack</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
