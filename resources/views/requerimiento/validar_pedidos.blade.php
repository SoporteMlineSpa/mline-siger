@extends('layouts.app')

@section('title', 'Validar Ordenes de Pedido')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-row">
                    <a class="btn btn-success mr-2" href="{{ route('cliente.home') }}">Volver</a>
                    @if (count($centros) > 0)
                    <form action="{{ route('pedidos.aceptarTodos')}}" method="POST" accept-charset="utf-8">
                        @csrf

                        <button class="btn btn-primary">Aceptar Todo</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if (count($centros) > 0)
                    <tabs>
                    @foreach ($centros as $centro)
                        <tab title="{{$centro->nombre}}">
                        <table id="datatable" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get() as $requerimiento)
                                    <tr>
                                        <th scope="row">{{ $loop->index }}</th>
                                        <td>{{ $requerimiento->nombre }}</td>
                                        <td>{{ $requerimiento->estado }}</td>
                                        <td class="d-flex flex-row">
                                            <modal-btn-component
                                                title="Orden de Pedido"
                                                class="mr-2"
                                                :message='[
                                                { data: @json($requerimiento->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                                                ]'>
                                                Ver Orden de Pedido
                                            </modal-btn-component>
                                            <form action="{{ route('pedidos.aceptar') }}" method="POST" accept-charset="utf-8">
                                                @csrf
                                                <input type="hidden" value="{{ $requerimiento }}" name="requerimiento" id="requerimiento"/>
                                                <button class="btn btn-primary mr-2">Aceptar</button>
                                            </form>
                                            <form action="{{ route('pedidos.rechazar') }}" method="POST" accept-charset="utf-8">
                                                @csrf
                                                <input type="hidden" value="{{ $requerimiento }}" name="requerimiento" id="requerimiento"/>
                                                <button class="btn btn-danger">Rechazar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </tab>
                    @endforeach
                    </tabs>
                @else
                    <div class="alert alert-dark">No hay Ordenes de Pedidos pendientes</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if (\Session::has('msg'))
        @php
            $msg = \Session::get('msg')
        @endphp
        <script charset="utf-8">
            (Swal.fire({
                icon: "success",
                title: "{{$msg['title']}}",
                text: "{{$msg['text']}}"
            }))()
        </script>
    @endif
@endsection
