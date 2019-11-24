@extends('layouts.app')

@section('title', 'Lista de Empresas | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <table id="datatable" class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nombre</th>
        <th scope="col">Accion</th>
      </tr>
    </thead>
    <tbody>
      @foreach($empresas as $empresa)
        <tr>
          <th scope="row">{{ $loop->index }}</th>
          <td>{{ $empresa->nombre }}</td>
          <td>
            <a class="btn btn-primary" href="{{route('empresas.edit', $empresa)}}"><i class="fas fa-edit"></i></a>
            <delete-btn-component action="{{ route('empresas.destroy', $empresa) }}"></delete-btn-component>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
