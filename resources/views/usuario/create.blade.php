@extends('layouts.app')

@section('title', 'Crear Usuario | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Nuevo Usuario</h3>
            <div class="card-body">
            </div>
        </div>
    </div>
@endsection
