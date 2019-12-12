@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl">Dashboard</div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-chart-line"></i> Reportes:</h3>
                        No tienes reportes disponibles
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-bell"></i> Ultimas Notificaciones:</h3>
                        @component('partials.notifications', ['notifications' => $notifications])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
