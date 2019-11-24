@extends('layouts.app')

@section('title', 'Nuevo Producto | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <form-component
    :form="{
      action: '{{ route('productos.store') }}',
      method: 'POST',
      items: [
        {name: 'familia', label: 'Familia', type: 'text'},
        {name: 'detalle', label: 'Detalle', type: 'text'},
        {name: 'marca', label: 'Marca', type: 'text'},
        {name: 'formato', label: 'Formato', type: 'text'},
        {name: 'precio', label: 'Precio', type: 'text'},
      ]
    }"
    ></form-component>
@endsection
