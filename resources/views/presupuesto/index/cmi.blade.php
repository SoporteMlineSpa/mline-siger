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
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="2">Centro</th>
                                    @for ($i = 1; $i < 13; $i++)
                                        <th class="text-center border" colspan="3">{{ ucfirst(\Carbon\Carbon::createFromDate(2020, $i, 1)->monthName) }}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    @for ($i = 1; $i < 13; $i++)
                                        <th>Real ($)</th>
                                        <th>Presupuesto ($)</th>
                                        <th>Desviacion ($)</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($cmi); $i++)
                                    <tr>
                                        <th scope="row">{{ $cmi[$i][0]->nombre }}</th>
                                        @for ($j = 0; $j < 12; $j++)
                                            <td>{{ $real = ($cmi[$i][2]->has($j+1) ? $cmi[$i][2][$j + 1][$j + 1] : 0) }}</td>
                                            <td>{{ $presupuesto = ($cmi[$i][1][$j]->monto / 100) }}</td>
                                            <td>{{ $mes = $presupuesto - $real}}</td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                            <tfoot>
                                    <th scope="row">Total</th>
                                    @for ($j = 0; $j < 12; $j++)
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endfor
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
