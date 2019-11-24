<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
      if ($user->empresa()->exists()) {

        $empresa = $user->empresa()->firstOrFail();
        $abastecimientos = $empresa->abastecimientos()->get();
        $requerimientos = [];

        foreach ($abastecimientos as $abastecimiento) {
          array_push($requerimientos, ["abastecimiento" => $abastecimiento, "requerimientos" => $abastecimiento->requerimientos()->get()]);
        }

        dd($requerimientos);
        return view('cliente.requerimiento.index')->with(compact('requerimientos', 'empresa'));

      } elseif ($user->abastecimiento()->exists()) {

        $abastecimientos = $user->abastecimiento()->firstOrFail();
        $requerimientos = $abastecimientos->requerimientos()->get();

        return view('cliente.requerimiento.index')->with(compact('requerimientos', 'abastecimientos'));

      } else {

        $msg = "Este usuario no esta asignado a ninguna empresa o punto de abastecimiento";

        return view('cliente.requerimiento.index')->with(compact('msg'));
      }
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
        //
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
}
