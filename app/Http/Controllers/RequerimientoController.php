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
     * Muestra el control para las ordenes de pedido para un Centro
     *
     * @param \App\Centro $centroId
     * @param Int year
     * @return \Illuminate\Http\Response
     */
    public function centroIndex($centroId, $year = null)
    {
        $centro = \App\Centro::findOrFail($centroId);
        $requerimientos = $centro->requerimientos();
        if (!is_null($year)) {
            $requerimientos = $requerimientos
                ->whereYear('created_at', $year)
                ->get();

            $estados = $requerimientos
                ->groupBy([function($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                }, 'estado']);

            $requerimientos = $requerimientos
                ->groupBy(function($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                });
        } else {
            $requerimientos = $requerimientos
                ->whereYear('created_at', date("Y"))
                ->get();

            $estados = $requerimientos
                ->groupBy([function($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                }, 'estado']);

            $requerimientos = $requerimientos
                ->groupBy(function($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                });
        }

        $counts = collect([]);
        $products = collect([]);
        $totals = collect([]);
        for ($i = 1; $i < 13; $i++) {
            $key = str_pad($i, 2, "0", STR_PAD_LEFT);
            if ($requerimientos->has($key)) {
                $counts->push(count($requerimientos[$key]));
                $products->push($requerimientos[$key]->reduce(function($carry, $requerimiento) {
                    return $carry + $requerimiento->productos->count();
                }));
                $totals->push($requerimientos[$key]->reduce(function($carry, $requerimiento) {
                    return $carry + $requerimiento->getTotal();
                }));
            } else {
                $counts->push(0);
                $products->push(0);
                $totals->push(0);
            }
        }

        $counts->push($counts->reduce(function($carry, $item) {
            return $carry + $item;
        }));
        $products->push($counts->reduce(function($carry, $item) {
            return $carry + $item;
        }));
        $totals->push($totals->reduce(function($carry, $item) {
            return $carry + $item;
        }));

        return view('requerimiento.index.index_mes')->with(compact('centro', 'counts', 'products', 'totals', 'estados'));
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
        $requerimientoId = $request->input('requerimiento');

        $requerimiento = Requerimiento::find($requerimientoId);
        $requerimiento->estado = "VALIDADO";
        $requerimiento->save();

        $users = $requerimiento->getUserByRequerimiento();

        foreach ($users as $user) {
            $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
        }

        return response()->json(['title' => '¡Orden aceptada exitosamente!', 'msg' => 'La Orden de Pedido fue aceptada']);
    }


    /**
     * Cambia el estado del requerimiento a "RECHAZADO"
     *
     * @return void
     */
    public function rechazar(Request $request)
    {
        $requerimientoId = $request->input("requerimiento");
        if ($request->has('observaciones')) {
            $observaciones = $request->input('observaciones');
        } else {
            $observaciones = null;
        }

        $requerimiento = Requerimiento::find($requerimientoId);
        $requerimiento->estado = "RECHAZADO";
        $requerimiento->observaciones = $observaciones;
        $requerimiento->saveOrFail();

        return response()->json(['title' => '¡Orden rechazada exitosamente!', 'msg' => 'La Orden de Pedido fue rechazada']);;
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
        return response()->json($msg);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa = Auth::user()->userable->empresa()->firstOrFail();
        $centro = Auth::user()->userable;
        $productos = $empresa->productos()->get();;
        $presupuesto = $centro->getTotalPresupuestoByDate(date("m"), date("Y"))->monto;

        $nombre = date("Y-m-d") . ' '
            .Auth::user()->userable->empresa->razon_social . ': '
            .Auth::user()->userable->nombre . ':  '
            .(is_null(\App\Requerimiento::latest()->first()) ? 0 : \App\Requerimiento::latest()->first()->id);

        return view('requerimiento.create')->with(compact('empresa', 'presupuesto', 'centro', 'productos', 'nombre'));
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
        $requerimiento->nombre = date("Y-m-d") . ' '
            .Auth::user()->userable->empresa->razon_social . ': '
            .Auth::user()->userable->nombre . ': '
            .(is_null(\App\Requerimiento::latest()->first()) ? 0 : \App\Requerimiento::latest()->first()->id);
        $centro->requerimientos()->save($requerimiento);

        foreach ($request->input('pedido') as $pedido) {
            $producto = $centro->empresa->productos->where('id', $pedido['id'])->first();
            $requerimiento->productos()->attach($producto, ["cantidad" => $pedido['cantidad'], "precio" => $producto->pivot->precio]);
        }

        $msg = [
            'meta' => [
                'title' => 'Orden de Pedido realizada exitosamente',
                'msg' => 'Una nueva Orden de Pedido fue realizada'
            ]
        ];

        return response()->json($msg);

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


        return view('compass.verificar_index')->with(compact('productos', 'requerimientos'));
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
                'msg' => 'Las Ordenes de pedido fueron enviadas a los Usuarios de Bodega'
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
            $query->where('estado', 'EN BODEGA')->where('folio', null)->where('transporte_id', null);
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
        $bodegueros = \App\Bodeguero::all();

        return view('compass.cajas_show')->with(compact('requerimiento', 'bodegueros'));
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
        $centro = Auth::user()->userable;
        $presupuesto = $centro->getTotalPresupuestoByDate(date("m"), date("Y"))->monto;
        $requerimiento = Requerimiento::findOrFail($requerimientoId);
        $productos = $empresa->productos()->get();
        $productosLibreria = $requerimiento->productos()->get()->map(function($producto) use ($productos) {
            $precio = ($productos->first(function($item) use ($producto) {
                return $item->id == $producto->id;
            }))->pivot->precio;

            return [
                "id" => $producto->id,
                "sku" => $producto->sku,
                "detalle" => $producto->detalle,
                "cantidad" => $producto->pivot->cantidad,
                "precio" => $precio,
                "subtotal" => $producto->pivot->cantidad * $precio
            ];
        });
        $nombre = date("Y-m-d") . ' '
            .Auth::user()->userable->empresa->razon_social . ': '
            .Auth::user()->userable->nombre . ': '
            .\App\Requerimiento::latest()->first()->id;

        return view('requerimiento.edit')->with(compact('empresa', 'presupuesto', 'centro', 'productos', 'productosLibreria', 'nombre'));
    }

    /**
     * Actualiza el Requerimiento a DESPACHADO, junto a informacion adicional de Compass
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Requerimiento $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function armarCaja(Request $request, \App\Requerimiento $requerimiento)
    {
        $requerimiento = \App\Requerimiento::find($requerimiento->id);
        $productos = collect($request->input('productos'));
        $reales = collect($request->input('real'));
        $vencimientos = collect($request->input('vencimiento'));
        $observaciones = collect($request->input('observaciones'));

        $noFolios = ceil($productos->count() / 29);
        $folio = \App\Folio::where('activo', true)->latest()->first();
        $folioActual = $folio->ultimo + $noFolios;

        if (($folio->ultimo >= $folio->hasta) || ($folioActual >= $folio->hasta)) {
            $msg = [
                'meta' => [
                    'title' => '¡Sin Folios Disponibles!',
                    'msg' => 'Se necesitan cargar Folios para poder generar la guia de despacho'
                ]
            ];

            $folio->activo = false;
            $folio->saveOrFail();
            return redirect()->route('cargarFolios')->with(compact('msg'));
        } else {
            if ($folioActual == $folio->hasta) {
                $folio->activo = false;
            }
            $folios = collect([]);
            for ($i = $folio->ultimo; $i < ($folio->ultimo + $noFolios); $i++) {
                $folios->push($i);
            }
            $folio->ultimo = $folioActual;
            $folio->saveOrFail();
        }

        $requerimiento->folio = $folios;

        $productos->map(function($item, $index) use ($requerimiento, $reales, $observaciones, $vencimientos) {
            $producto = json_decode($item, true);
            $requerimiento->productos()->updateExistingPivot($producto['id'], ['real' => $reales[$index], 'fecha_vencimiento' => $vencimientos[$index], 'observacion' => $observaciones[$index]]);
        });

        $requerimiento->saveOrFail();

        $msg = [
            'meta' => [
                'title' => '¡Orden de Pedido Armada!',
                'msg' => 'La Orden de Pedido fue armada sin problemas'
            ]
        ];

        return redirect()->route('compass.pedidos.cajasIndex')->with(compact('msg'));
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
                    'msg' => 'La Orden de Pedido fue Recibida sin problemas'
                ]
            ];

            return back()->with(compact('msg'));
        } else {
            $msg = [
                'meta' => [
                    'title' => '¡Error!',
                    'msg' => 'La Orden de Pedido no pudo ser actualizada al estado Recibida'
                ]
            ];

            return back()->with(compact('msg'));
        }
    }

    /**
     * Muestra el formulario para reemplzar un producto por otro
     *
     * @return Illuminate\Http\Response
     */
    public function cambiarProducto(\App\Requerimiento $requerimiento, \App\Producto $producto)
    {
        $empresa = \App\Empresa::findOrFail($requerimiento->centro->empresa->id);
        $productos = $empresa->productos()->get();

        return view('compass.cajas_reemplazar')->with(compact('productos', 'requerimiento', 'producto'));
    }

    /**
     * Reemplazar un productos por otro en el Requerimiento
     *
     * @return \Illuminate\Http\Response
     */
    public function reemplazar(\App\Requerimiento $requerimiento, Request $request)
    {

        $productoReemplazado = $requerimiento->productos->where('id', $request->input('productoReemplazadoId'))->first();
        $nuevoProducto = $requerimiento->centro->empresa->productos->where('id', $request->input('nuevoProducto'))->first();

        if (!is_null($productoReemplazado) && !is_null($nuevoProducto)) {
            $requerimiento->productos()->detach($productoReemplazado);
            $requerimiento->productos()->attach($nuevoProducto, [
                'cantidad' => $productoReemplazado->pivot->cantidad,
                'precio' => $nuevoProducto->pivot->precio,
                'observacion' => 'En reemplazo de '.$productoReemplazado->detalle]);

            $requerimiento->save();
            return redirect()->route('compass.pedidos.show', $requerimiento);
        } else {
            return redirect()->route('compass.pedidos.show', $requerimiento);
        }
    }

    /**
     * Muestra la pantalla para programar despachos
     *
     * @return \Illuminate\Http\Response
     */
    public function programarDespachoView()
    {
        $requerimientos = Requerimiento::doesntHave('transporte')->where('estado', 'EN BODEGA')->where('folio', '>', 0)->get();
        $abastecimientos = \App\Abastecimiento::all();

        return view('compass.programar_despachos')->with(compact('requerimientos', 'abastecimientos'));
    }


    /**
     * Programa los requerimientos para ser Despachados
     *
     * @return \Illuminate\Http\Response
     */
    public function programarDespacho(Request $request)
    {
        $despacho = new \App\Transporte;
        $despacho->nombre = $request->input('nombre');
        $despacho->rut = $request->input('rut');
        $despacho->contacto = $request->input('contacto');
        $despacho->fecha_programada = \Carbon\Carbon::createFromFormat("Y-m-d", $request->input('fecha_despacho'));
        $despacho->despachado = false;
        $despacho->abastecimiento()->associate($request->input('destino'));
        if ($despacho->saveOrFail()) {
            foreach ($request->input('seleccionados') as $index => $seleccionado) {
                if ($seleccionado) {
                    $requerimiento = \App\Requerimiento::find($request->input('requerimientos')[$index]);
                    $requerimiento->transporte()->associate($despacho);

                    $requerimiento->save();
                }
            }
        }

        $msg = [
            "meta" => [
                "title" => "¡Despacho Programado Exitosamente!",
                "msg" => "El Despacho ha sido programado exitosamente"
            ]
        ];

        return back()->with(compact('msg'));
    }

    /**
     * Muestra la pantalla para Despachar
     *
     * @return \Illuminate\Http\Response
     */
    public function despacharView()
    {
        $despachos = \App\Transporte::where('despachado', false)->get();

        return view('compass.despachar')->with(compact('despachos'));
    }

    /**
     * Elimina un despacho programado
     *
     * @return \Illuminate\Http\Response
     */
    public function eliminarDespacho($id)
    {
        $despacho = \App\Transporte::find($id);
        $despacho->requerimientos()->get()->map(function($requerimiento) {
            $requerimiento->transporte()->dissociate();
            $requerimiento->save();
        });

        $despacho->delete();

        $msg = [
            "meta" => [
                "title" => "Despacho programado eliminado",
                "msg" => "El despacho fue eliminado exitosamente"
            ]
        ];

        return response()->json($msg);
    }


    /**
     * Realiza un despacho
     *
     * @return \Illuminate\Http\Response
     */
    public function despachar($id)
    {
        $despacho = \App\Transporte::find($id);
        $exito = $despacho->requerimientos()->get()->map(function($requerimiento) {
            $requerimiento->estado = "DESPACHADO";
            return $requerimiento->saveOrFail();
        });

        $despacho->despachado = true;
        $despacho->save();

        $msg = [
            "meta" => [
                "title" => "Requerimientos despachados",
                "msg" => "Los Requerimientos fueron despachados exitosamente"
            ]
        ];

        return response()->json($msg);
    }

    /**
     * Generar las guias de despacho para cada Requerimientos
     *
     * @return \Illuminate\Http\Response
     */
    public function generarGuia($id)
    {
        $despacho = \App\Transporte::find($id);

        $exito = $despacho->requerimientos()->get()->map(function($requerimiento) {
            return $requerimiento->generarGuiaDespacho();
        });

        if ($exito->contains(false)) {
            $msg = [
                "meta" => [
                    "title" => "Guias de Despacho Electronica Generadas",
                    "msg" => "No todas las guias de despachos pudieron ser generadas"
                ]
            ];
        } else {
            $msg = [
                "meta" => [
                    "title" => "Guias de Despacho Electronica Generadas",
                    "msg" => "Todas las guias de despacho fueron generadas exitosamente"
                ]
            ];
        }

        return response()->json($msg);
    }

    
    /**
     * Descarga todas las guias de despacho de ese Requerimiento
     *
     * @return Download
     */
    public function descargarGuia(\App\Requerimiento $requerimiento)
    {
        $file = public_path()."/storage/OUTZIP/$requerimiento->nombre.zip";
        $zip = new \ZipArchive();
        if ($zip->open($file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            foreach($requerimiento->guia as $guia){
                $relativeName = basename($guia['url']);
                $zip->addFile($guia['url'], $relativeName);
            };

            $zip->close();
        }

        return response()->download($file);
    }

}
