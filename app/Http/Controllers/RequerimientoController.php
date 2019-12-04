<?php

namespace App\Http\Controllers;

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

        return view('cliente.requerimiento.empresa_index')->with(compact('centros'));

        break;

      case 'App\Centro':
        $centro = $user->userable;
        $requerimientos = $centro->requerimientos()->get();

        return view('cliente.requerimiento.centro_index')->with(compact('requerimientos', 'centro'));

        break;

      case 'App\CompassRole':
        $requerimientos = \App\Requerimiento::where('estado', 'VALIDADO')->get();

        return view('compass.pedidos_index')->with(compact('requerimientos'));
        break;

      default:
        $msg = "Este usuario no esta asignado a ninguna empresa o centro";
        return view('cliente.requerimiento.index')->with(compact('msg'));

        break;
    }
  }

  /**
   * Muestra las ordenes de pedidos para un Centro
   *
   * @param  \App\centro  $centro
   * @return \Illuminate\Http\Response
   */
  public function showCentro($centroId)
  {
    $centro = \App\Centro::findOrFail($centroId);
    $requerimientos = $centro->requerimientos()->get();

    return view('cliente.requerimiento.centro_show')->with(compact('centro', 'requerimientos'));
  }

  /**
   * Muestra las ordenes de pedidos pendiente y permite validarlas
   *
   * @return void
   */
  public function validarPedidos()
  {
    $empresa = Auth::user()->userable;
    $centros = $empresa->centros()->get();
    $pedidos = [];
    foreach ($centros as $centro) {
      $requerimientos = $centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get();
      if (!$requerimientos->isEmpty()) {
        array_push($pedidos, ['centro' => $centro, 'requerimientos' => $requerimientos]);
      } else {
        continue;
      }
    }
    return view('cliente.requerimiento.validar-pedidos')->with(compact('pedidos'));

  }

  //TODO: Cambiar a POST
  /**
   * Cambia el estado del requerimiento a "VALIDADO".
   *
   * @return void
   */
  public function aceptar($requerimientoId)
  {
    $requerimiento = \App\Requerimiento::findOrFail($requerimientoId);

    $requerimiento->estado = "VALIDADO";
    $requerimiento->save();

    return back()->with(['msg' => "Orden de Pedido Aceptada"]);
  }


  //TODO: Cambiar a POST
  /**
   * Cambia el estado del requerimiento a "RECHAZADO"
   *
   * @return void
   */
  public function rechazar($requerimientoId)
  {
    $requerimiento->estado = "RECHAZADO";
    $requerimiento->save();

    return back()->with(['msg' => "Orden de Pedido Rechazado"]);;
  }

  //TODO: Cambiar a POST
  /**
   * Cambia el estado de todos los requerimientos a "VALIDADO".
   *
   * @return void
   */
  public function aceptarTodos($requerimientos)
  {
    foreach ($requerimientos as $requerimiento) {
      $requerimiento->estado = "VALIDADO";
      $requerimiento->save();
    }

    return back()->with(['msg' => 'Ordenes de Pedido Aceptadas']);
  }
  
  
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    try {
      $productos = \App\Producto::all();

      return view('cliente.requerimiento.create')->with(compact('productos'));
    } catch (Exception $e) {
      dd('Not logged in');
    }
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
    $requerimiento->nombre = 'Requerimiento '.Date('d-M-Y');
    $centro->requerimientos()->save($requerimiento);

    foreach ($request->input('cantidad') as $key => $cantidad) {
      if ($cantidad === null) {
        continue;
      } else {
        $producto = $requerimiento->productos()->save(\App\Producto::where('id', $request->input('id')[$key])->firstOrFail());
        $requerimiento->productos()->updateExistingPivot($producto->id, [ 'cantidad' => $cantidad]);
      }
    }


    return back()->with(['msg' => 'Exito']);

  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Requerimiento  $requerimiento
   * @return \Illuminate\Http\Response
   */
  public function show(Requerimiento $requerimiento)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Requerimiento  $requerimiento
   * @return \Illuminate\Http\Response
   */
  public function edit(Requerimiento $requerimiento)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Requerimiento  $requerimiento
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Requerimiento $requerimiento)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Requerimiento  $requerimiento
   * @return \Illuminate\Http\Response
   */
  public function destroy(Requerimiento $requerimiento)
  {
    //
  }

  /**
   * Lista de Requerimientos segun su estado y productos
   *
   * @return \Illuminate\Http\Response
   */
  public function verificar()
  {
    $requerimientos = \App\Requerimiento::where('estado', 'VALIDADO')->get();
    $productos = \App\Producto::whereHas('requerimientos', function ($q) {
      $q->where('estado', 'VALIDADO');
    })->get();

    return view('compass.verificar_index')->with(compact('productos'));
  }
}
