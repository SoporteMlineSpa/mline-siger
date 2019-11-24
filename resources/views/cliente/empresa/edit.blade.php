@extends('layouts.app')

@section('title', 'Editar Empresa | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <form-component
    :form="{
      action: '{{ route('empresa.store') }}',
      method: 'POST',
      items: [
        {name: 'holding', label: 'Holding', type: 'select', options: [
          @foreach($holdings as $holding)
            { label: '{{$holding->nombre}}', value: {{$holding->id}} },
          @endforeach
          ], value: '{{$empresa->holding->nombre}}'},
        {name: 'nombre', label: 'Nombre', type: 'text', value: '{{$empresa->nombre}}'}
      ]
    }"
    ></form-component>
@endsection

@section('js')
@endsection
