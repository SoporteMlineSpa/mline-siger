@extends('layouts.app')

@section('title', 'Crear Empresa | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <form-component
    :form="{
      action: '{{ route('empresas.store') }}',
      method: 'POST',
      items: [
        {name: 'empresa', label: 'Empresa:', type: 'select', options: [
          @foreach($empresas as $empresa)
            { label: '{{$empresa->nombre}}', value: {{$empresa->id}} },
          @endforeach
        ]},
        {name: 'nombre', label: 'Nombre', type: 'text'}
      ]
    }"
    ></form-component>
@endsection
