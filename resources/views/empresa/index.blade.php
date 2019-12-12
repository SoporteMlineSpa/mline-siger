@extends('layouts.app')

@section('title', 'Lista de Empresas | Mline SIGER')

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
            <h3 class="card-header font-bold text-xl">Lista de Empresas</h3>
            <div class="card-body">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Razon Social</th>
                            <th scope="col">RUT</th>
                            <th scope="col">Direccion</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empresas as $empresa)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $empresa->razon_social }}</td>
                                <td>{{ $empresa->rut }}</td>
                                <td>{{ $empresa->direccion }}</td>
                                <td>
                                    <a class="btn btn-primary" href="{{route('empresas.edit', $empresa)}}"><i class="fas fa-edit"></i></a>
                                    <delete-btn-component action="{{ route('empresas.destroy', $empresa) }}"></delete-btn-component>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@if (\Session::has('msg'))
    @php
        $msg = \Session::get('msg');
    @endphp
    @section('js')
        <script charset="utf-8">
            (Swal.fire({
                title: '{{$msg['meta']['title']}}',
                html: '{!! $msg['meta']['message'] !!}',
                icon: 'success'
            }))();

        </script>
    @endsection
@endif
