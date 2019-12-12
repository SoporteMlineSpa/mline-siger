@extends('layouts.app')

@section('title', 'Notificaciones')

@section('home-route', $home)

@section('nav-menu')
    @include($menu)
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl">Notificaciones</div>
            <div class="card-body">
                @component('partials.notifications', ['notifications' => $notifications])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
