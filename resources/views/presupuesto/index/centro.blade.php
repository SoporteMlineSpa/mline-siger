@extends('layouts.app')

@section('title', 'Cuenta Corriente | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Cuenta Corriente</h3>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <form action="{{ url()->current() }}" class="form-group form-row">
                            <label class="col-md-2 col-form-label text-md-right">Mes:</label>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm" name="mes">
                                    @for ($i = 0; $i < 12; $i++)
                                        <option value="{{$i}}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <label class="col-md-2 col-form-label text-md-right">Año:</label>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm" name="year">
                                    @for ($i = date("Y"); $i < date("Y") + 6; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-1"><button class="btn btn-primary">Filtrar</button></div>
                        </form>
                    </div>
                </div>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route("presupuesto.indexCentro", ['acumulado' => true])}}">Acumulado</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("presupuesto.indexCentro", ['soloMes' => true])}}">Solo Mes</a></li>
                </ul>
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Concepto</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Id</th>
                                    <th scope="col">Entrada ($)</th>
                                    <th scope="col">Salida ($)</th>
                                    <th scope="col">Saldo ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if(array_key_exists($date->month, $inicial)) {
                                        $saldo = ($inicial[$date->month]->monto / 100);
                                    } else {
                                        $saldo = 0;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $date }}</td>
                                    <td>Presupuesto</td>
                                    <td>Carga Inicial</td>
                                    <td>{{ __($date->year.$date->month) }}</td>
                                    <td>{{ number_format($saldo, 2) }}</td>
                                    <td></td>
                                    <td>{{ number_format($saldo, 2)}}</td>
                                </tr>
                                @foreach ($requerimientos as $pedido)
                                        <tr>
                                            <td>{{$pedido->created_at}}</td>
                                            <td>{{ __($pedido->nombre) }}</td>
                                            <td>Orden de Pedido</td>
                                            <td>
                                                <modal-btn-component
                                                    :button="false"
                                                    title="Orden de Pedido"
                                                    :message='[
                                                    { data: @json($pedido), type: "Object", keys: ["nombre"]},
                                                    { data: @json($pedido->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                                                    ]'>{{$pedido->id}}</modal-btn-component>
                                            </td>
                                            <td></td>
                                            <td>{{ number_format($pedido->getTotal(), 2) }}</td>
                                            <td>{{ number_format(($saldo -= $pedido->getTotal()), 2) }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
