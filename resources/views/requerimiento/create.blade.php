@extends('layouts.app')

@section('title', 'Crear Orden de Pedido | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="card">
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->userable->nombre }}: Nueva Orden de Pedido</h3>
        <crear-requerimiento-component
            :index-productos='@json($productos)'
            presupuesto="{{ $presupuesto }}"
            :empresa='@json($empresa)'
            :centro='@json($centro)'
            nombre="{{ $nombre }}"
            :libreria="null"
            action="{{ route('requerimientos.store') }}"
            ></crear-requerimiento-component>
        <form class="card-body" action="{{ route('requerimientos.store') }}" method="POST"></form>
    </div>
@endsection
