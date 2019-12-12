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
        {name: 'holding', label: 'Holding', type: 'select', options: [
          @foreach($holdings as $holding)
            { label: '{{$holding->nombre}}', value: {{$holding->id}} },
          @endforeach
        ]},
        {name: 'nombre', label: 'Nombre', type: 'text'}
      ]
    }"
    ></form-component>
@endsection

@section('js')
@endsection
