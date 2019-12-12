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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        switch (get_class($user->userable)) {
        case 'App\Empresa':
            $empresa = $user->userable;
            $centros = $empresa->centros()->get();

            return view('requerimiento.empresa_index')->with(compact('centros'));

            break;

        case 'App\Centro':
            $centro = $user->userable;
            $requerimientos = $centro->requerimientos()->get();

            return view('requerimiento.centro_index')->with(compact('requerimientos', 'centro'));

            break;

        case 'App\CompassRole':
            $requerimientos = Requerimiento::where('estado', 'VALIDADO')->get();

            return view('compass.pedidos_index')->with(compact('requerimientos'));
            break;

        default:
            $msg = "Este usuario no esta asignado a ninguna empresa o centro";
            return view('requerimiento.index')->with(compact('msg'));

            break;
        }
    }

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
        $requerimientos = null;
        switch ($estadoId) {
        case 0:
            $requerimientos = $centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get();
            break;
        case 1:
            $requerimientos = $centro->requerimientos()->where('estado', 'VALIDADO')->get();
            break;
        case 2:
            $requerimientos = $centro->requerimientos()->where('estado', 'EN PROCESAMIENTO')->get();
            break;
        case 3:
            $requerimientos = $centro->requerimientos()->where('estado', 'EN BODEGA')->get();
            break;
        case 4:
            $requerimientos = $centro->requerimientos()->where('estado', 'DESPACHADO')->get();
            break;
        case 5:
            $requerimientos = $centro->requerimientos()->where('estado', 'ENTREGADO')->get();
            break;
        case 6:
            $requerimientos = $centro->requerimientos()->where('estado', 'RECHAZADO')->get();
            break;
        default:
            $requerimientos = $centro->requerimientos()->get();
            break;

        }

        return view('requerimiento.centro_show')->with(compact('centro', 'requerimientos'));
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
            if ($cantidad === null) {
                continue;
            } else {
                $producto = $requerimiento->productos()->save(\App\Producto::where('id', $request->input('id')[$key])->firstOrFail());
                $requerimiento->productos()->updateExistingPivot($producto->id, [ 'cantidad' => $cantidad]);
            }
        }

        $users = $requerimiento->getUserByRequerimiento();

        foreach ($users as $user) {
            $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
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
                $requerimiento->estado = "EN PROCESAMIENTO";
                $requerimiento->save();
                $requerimiento->getUserByRequerimiento()->map(function ($user) {
                    $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
                });

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
     * Lista de Ordenes de Pedidos verificadas por Centros
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCajas()
    {
        $centros = \App\Centro::whereHas('requerimientos', function ($query) {
            $query->where('estado', 'VALIDADO');
        })->get();

        return view('compass.cajas_index')->with(compact('centros'));
    }

    /**
     * Muesta informacion para un requerimiento por su Id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($requerimientoId)
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
        $productosRequerimiento = $requerimiento->productos()->get();
        $productos = $productosRequerimiento->union($empresa->productos()->get());

        return view('requerimiento.edit')->with(compact('requerimiento', 'productos'));
    }

}
