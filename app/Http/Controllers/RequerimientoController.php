<?php

namespace App\Http\Controllers;

use App\Notifications\EstadoUpdated;
use App\Requerimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequerimientoController extends Controller
{

    /**
     * Muestra las ordenes de pedidos para un Centro
     *
     * @param  \App\centro  $centro
     * @param Int Estado
     * @return \Illuminate\Http\Response
     */
    public function showCentro($centroId, $estadoId = null)
    {
        $centro = \App\Centro::findOrFail($centroId);
        $requerimientos = $centro->getRequerimientosByEstado($estadoId);

        return view('requerimiento.index.show')->with(compact('centro', 'requerimientos'));
    }

    /**
     * Muestra las ordenes de pedidos pendiente y permite validarlas
     *
     * @return void
     */
    public function validarPedidos()
    {
        $empresa = Auth::user()->userable;
        $centros = $empresa->centros()->whereHas('requerimientos', function ($query) {
            $query->where('estado', 'ESPERANDO VALIDACION');
        })->get();

        return view('requerimiento.validar_pedidos')->with(compact('centros'));

    }

    /**
     * Cambia el estado del requerimiento a "VALIDADO".
     *
     * @return void
     */
    public function aceptar(Request $request)
    {
        $requerimientoId = json_decode($request->input('requerimiento'), true);

        $requerimiento = Requerimiento::find($requerimientoId['id']);
        $requerimiento->estado = "VALIDADO";
        $requerimiento->save();

        $users = $requerimiento->getUserByRequerimiento();

        foreach ($users as $user) {
            $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
        }

        return back()->with(['msg' => ['title' => '¡Orden aceptada exitosamente!', 'text' => 'La Orden de Pedido fue aceptada']]);
    }


    /**
     * Cambia el estado del requerimiento a "RECHAZADO"
     *
     * @return void
     */
    public function rechazar(Request $request)
    {
        $requerimientoId = json_decode($request->input('requerimiento'), true);

        $requerimiento = Requerimiento::find($requerimientoId['id']);
        $requerimiento->estado = "RECHAZADO";
        $requerimiento->save();

        $users = $requerimiento->getUserByRequerimiento();

        foreach ($users as $user) {
            $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
        }

        return back()->with(['msg' => ['title' => '¡Orden rechazada exitosamente!', 'text' => 'La Orden de Pedido fue rechazada']]);;
    }

    /**
     * Cambia el estado de todos los requerimientos a "VALIDADO".
     *
     * @return void
     */
    public function aceptarTodos(Request $request)
    {
        $empresa = Auth::user()->userable;
        $centros = $empresa->centros()->whereHas('requerimientos', function ($query) {
            $query->where('estado', 'ESPERANDO VALIDACION');
        })->get();

        foreach ($centros as $centro) {
            $requerimientos = $centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get();
            foreach ($requerimientos as $requerimiento) {
                $requerimiento->estado = 'VALIDADO';
                $requerimiento->save();

                $users = $requerimiento->getUserByRequerimiento();

                foreach ($users as $user) {
                    $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
                }
            }
        }

        $msg = ['title' => '¡Ordenes aceptadas exitosamente!', 'text' => 'Todas las Ordenes fueron aceptadas'];
        return back()->with(compact('msg'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa = Auth::user()->userable->empresa()->firstOrFail();
        $productos = $empresa->productos()->get();;

        return view('requerimiento.create')->with(compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $centro = Auth::user()->userable;
        $requerimiento = new Requerimiento;
        $requerimiento->nombre = $request->input('nombre');
        $centro->requerimientos()->save($requerimiento);

        foreach ($request->input('cantidad') as $key => $cantidad) {
            if ($cantidad > 0) {
                $producto = $requerimiento->productos()->save(\App\Producto::where('id', $request->input('id')[$key])->firstOrFail());
                $requerimiento->productos()->updateExistingPivot($producto->id, ['cantidad' => $cantidad]);
            }
        }

        return back()->with(['msg' => 'Exito']);

    }

    /**
     * Lista de Requerimientos segun su estado y productos
     *
     * @return \Illuminate\Http\Response
     */
    public function verificar()
    {
        $requerimientos = Requerimiento::where('estado', 'VALIDADO')->get();

        if ($requerimientos->count() > 0) {
            $requerimientoId = $requerimientos->map(function ($requerimiento) {
                return $requerimiento->id;
            });
            $productos = DB::table('producto_requerimiento')
                ->join('productos', 'producto_requerimiento.producto_id', '=', 'productos.id')
                ->select(DB::raw('productos.sku, productos.detalle, SUM(producto_requerimiento.cantidad) as cantidad'))
                ->whereIn('producto_requerimiento.requerimiento_id', $requerimientoId)
                ->groupBy('productos.sku', 'productos.detalle')
                ->get();
        } else {
            $productos = collect([]);
        }


        return view('compass.verificar_index')->with(compact('productos'));
    }

    /**
     * Cambia los Estado del Requerimiento a En Bodega
     *
     * @return \Illuminate\Http\Response
     */
    public function doVerificar()
    {
        $requerimientos = Requerimiento::where('estado', 'VALIDADO')->get();
        $requerimientos->map(function($requerimiento) {
            $requerimiento->estado = "EN BODEGA";
            $requerimiento->save();
        });
        $msg = [
            'meta' => [
                'title' => '¡Ordenes de Pedido enviados a Bodega!',
                'message' => 'Las Ordenes de pedido fueron enviadas a los Usuarios de Bodega'
            ]
        ];

        return redirect()->route('pedidos.indexEmpresa')->with(compact('msg'));
    }


    /**
     * Lista de Ordenes de Pedidos verificadas por Centros
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCajas()
    {
        $centros = \App\Centro::whereHas('requerimientos', function ($query) {
            $query->where('estado', 'EN BODEGA');
        })->get();

        return view('compass.cajas_index')->with(compact('centros'));
    }

    /**
     * Muesta informacion para un requerimiento por su Id
     *
     * @return \Illuminate\Http\Response
     */
    public function showCaja($requerimientoId)
    {
        $requerimiento = Requerimiento::findOrFail($requerimientoId);

        return view('compass.cajas_show')->with(compact('requerimiento'));
    }

    /**
     * Muestra el formulario para reutilizar una orden de pedido
     *
     * @param Int $requerimientoId
     * @return \Illuminate\Http\Response
     */
    public function edit($requerimientoId)
    {
        $empresa = Auth::user()->userable->empresa()->firstOrFail();
        $requerimiento = Requerimiento::findOrFail($requerimientoId);
        $productos = $requerimiento->productos()->get()->union($empresa->productos()->get());

        return view('requerimiento.edit')->with(compact('requerimiento', 'productos'));
    }

    /**
     * Actualiza el Requerimiento a DESPACHADO, junto a informacion adicional de Compass
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Requerimiento $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function despachar(Request $request, \App\Requerimiento $requerimiento)
    {
        //TODO: Generar Guia de Despacho

        $productos = collect($request->input('productos'));
        $reales = collect($request->input('real'));
        $observaciones = collect($request->input('observaciones'));

        $productos->map(function($item, $index) use ($requerimiento, $reales, $observaciones) {
            $producto = json_decode($item, true);
            $requerimiento->productos()->updateExistingPivot($producto['id'], ['real' => $reales[$index], 'observacion' => $observaciones[$index]]);
        });

        $requerimiento->estado = "DESPACHADO";
        if ($requerimiento->saveOrFail()) {
            $msg = [
                'meta' => [
                    'title' => '¡Orden de Pedido Despachada!',
                    'message' => 'La Orden de Pedido fue despachada sin problemas'
                ]
            ];

            return redirect()->route('pedidos.indexEmpresa')->with(compact('msg'));
        }
    }

    /**
     * Detalles de un Requerimiento en especifico
     *
     * @param \App\Requerimiento $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Requerimiento $requerimiento)
    {
        $centro = $requerimiento->centro;
        $empresa = $centro->empresa;
        $productos = $requerimiento->productos;

        return view('requerimiento.show')->with(compact('requerimiento', 'centro', 'empresa', 'productos'));
    }
    
    /**
     * Cambia el estado de un Requerimiento a ENTREGADO
     *
     * @para \App\Requerimiento $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function entregado(\App\Requerimiento $requerimiento)
    {
        $requerimiento->estado = "ENTREGADO";
        if ($requerimiento->saveOrFail()) {
            $msg = [
                'meta' => [
                    'title' => '¡Orden de Pedido Recibida!',
                    'message' => 'La Orden de Pedido fue Recibida sin problemas'
                ]
            ];

            return back()->with(compact('msg'));
        } else {
            $msg = [
                'meta' => [
                    'title' => '¡Error!',
                    'message' => 'La Orden de Pedido no pudo ser actualizada al estado Recibida'
                ]
            ];

            return back()->with(compact('msg'));
        }
    }
    

}
