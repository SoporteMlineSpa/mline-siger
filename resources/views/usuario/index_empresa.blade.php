@extends('layouts.app')

@section('title', 'Lista de Usuarios | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Usuarios</h3>
            <div class="card-body">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Centro de Cultivo</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->userable->nombre }}</td>
                                <td>
                                    <a class="btn btn-primary" href=""><i class="fas fa-edit"></i></a>
                                    <delete-btn-component action=""></delete-btn-component>
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
