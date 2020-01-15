@if ($type === 0)
    <table id="datatable" class="table">
        <thead>
            <tr>
                <th scope="col">Folio</th>
                <th scope="col">Nombre</th>
                <th scope="col">Estado</th>
                @if ((Auth::user()->userable instanceof \App\Centro))
                    <th scope="col">Libreria</th>   
                @endif
                <th scope="col">Fecha de Creacion</th>
                <th scope="col">Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requerimientos as $requerimiento)
                <tr>
                    <td>
                        <a href="{{ route('pedidos.show',
                        $requerimiento) }}">{{
                        $requerimiento->folio ?? $requerimiento->id }}</a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                    </td>
                    <td>{{ $requerimiento->estado }}</td>
                    @if ((Auth::user()->userable instanceof \App\Centro))
                        <td>
                            <agregar-libreria-component
                                action="{{ route('libreria.editar', $requerimiento) }}"
                                :library='@json(Auth::user()->hasRequerimiento($requerimiento))'></agregar-libreria-component>
                        </td>
                    @endif
                    <td>{{ $requerimiento->created_at }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <modal-btn-component
                                title="Orden de Pedido"
                                :message='[
                                { data:
                                @json($requerimiento->productos),
                                type: "Array", keys: ["sku",
                                "detalle", "precio",
                                "pivot", "total"], pivot: "cantidad"},
                                { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                ]'>Ver Orden de Pedido</modal-btn-component>
                            @if (Auth::user()->userable instanceof \App\Centro)
                                @if ( $requerimiento->estado === 'DESPACHADO')
                                    <a class="btn btn-success" href="{{ route('pedidos.entregado', $requerimiento) }}">Recibido</a>
                                @endif
                            @endif
                            @if ( $requerimiento->estado === 'DESPACHADO')
                                <modal-btn-component
                                    title="Orden de Pedido"
                                    :message='[
                                    { data: @json([
                                    "nombre" => $requerimiento->nombre_transportista,
                                    "rut" => $requerimiento->rut_transportista,
                                    "contacto" => $requerimiento->contacto_transportista
                                    ])
                                    , type: "Object", keys: ["nombre",
                                    "rut", "contacto"]}
                                    ]'>Ver Transporte</modal-btn-component>
                                @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif ($type === 1)
    <table id="datatable" class="table table-sm">
        <thead>
            <tr>
                <th scope="col" rowspan="2">#</th>
                <th scope="col" rowspan="2">Nombre</th>
                <th class="text-center" scope="row" colspan="7">Estados</th>
                <th scope="col" rowspan="2">Ver Todos</th>
            </tr>
            <tr>
                <th scope="col">Esperando Validacion</th>
                <th scope="col">Validado</th>
                <th scope="col">En Procesamiento</th>
                <th scope="col">En Bodega</th>
                <th scope="col">Despachado</th>
                <th scope="col">Entregado</th>
                <th scope="col">Rechazado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($centros as $centro)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    <td>{{ $centro->nombre }}</td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '0']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '1']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'VALIDADO')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '2']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'EN PROCESAMIENTO')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '3']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'EN BODEGA')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '4']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'DESPACHADO')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '5']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'ENTREGADO')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '6']) }}">
                            {{ count($centro->requerimientos()->where('estado', 'RECHAZADO')->get()) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.centro', ['centro' => $centro->id, 'estado' => '7']) }}">
                            Ver Todas
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@elseif ($type === 2)
    <table id="datatable" class="table table-sm">
        <thead>
            <tr>
                <th scope="col" rowspan="2">#</th>
                <th scope="col" rowspan="2">Nombre</th>
                <th class="text-center" scope="row" colspan="7">Estados</th>
                <th scope="col" rowspan="2">Ver Todos</th>
            </tr>
            <tr>
                <th scope="col">Esperando Validacion</th>
                <th scope="col">Validado</th>
                <th scope="col">En Procesamiento</th>
                <th scope="col">En Bodega</th>
                <th scope="col">Despachado</th>
                <th scope="col">Entregado</th>
                <th scope="col">Rechazado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas as $empresa)
                <tr>
                    <th scope="row">{{ $loop->index + 1}}</th>
                    <td>{{ $empresa->razon_social }}</td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 0])}}">
                            {{ count($empresa->getRequerimientoByEstado('ESPERANDO VALIDACION')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 1])}}">
                            {{ count($empresa->getRequerimientoByEstado('VALIDADO')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 2])}}">
                            {{ count($empresa->getRequerimientoByEstado('EN PROCESAMIENTO')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 3])}}">
                            {{ count($empresa->getRequerimientoByEstado('EN BODEGA')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 4])}}">
                            {{ count($empresa->getRequerimientoByEstado('DESPACHADO')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 5])}}">
                            {{ count($empresa->getRequerimientoByEstado('ENTREGADO')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 6])}}">
                            {{ count($empresa->getRequerimientoByEstado('RECHAZADO')) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 7])}}">
                            Ver Todos
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
