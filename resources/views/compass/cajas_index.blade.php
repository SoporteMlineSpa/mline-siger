@extends('layouts.app')

@section('title', 'Armar Cajas')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Armar Cajas</h3>
            <div class="card-body">
                <tabs>
                @foreach ($centros as $centro)
                    <tab title="{{$centro->nombre}}">
                    <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Armar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($centro->requerimientos()->get() as $requerimiento)
                                    <tr>
                                        <td>{{$requerimiento->nombre}}</td>
                                        <td>{{$requerimiento->estado}}</td>
                                        <td><a class="btn btn-primary" href="{{ route('compass.pedidos.show', $requerimiento)}}">Armar Caja</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                    </tab>
                @endforeach
                </tabs>
            </div>
        </div>
    </div>
@endsection
