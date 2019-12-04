@extends('layouts.app')

@section('title', 'Nuevo Producto | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h5 class="card-header font-bold text-xl">Nuevo Producto</h5>
      <div class="card-body">
        <form-component
          :form="{
          action: '{{ route('productos.store') }}',
          method: 'POST',
          items: [
          {name: 'sku', label: 'Familia', type: 'text'},
          {name: 'detalle', label: 'Detalle', type: 'text'},
          {name: 'stock', label: 'Stock', type: 'number'},
          {name: 'precio', label: 'Precio', type: 'text'},
          ]
          }"
          ></form-component>
      </div>
    </div>
  </div>
@endsection
