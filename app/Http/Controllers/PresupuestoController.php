<?php

namespace App\Http\Controllers;

use App\Presupuesto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresupuestoController extends Controller
{
    /**
     * Lista de Empresas con su Presupuesto
     *
     * @return \Illuminate\Http\Response
     */
    public function indexHolding()
    {
        $empresas = Auth::user()->userable->empresas()->get();
        $presupuestos = $empresas->map(function($empresa) {
            return $empresa->presupuesto()->get();
        });
        return view('presupuesto.index.holding')->with(compact('presupuestos'));
    }

    /**
     * Lista de Centros con su Presupuesto
     *
     * @return void
     */
    public function indexEmpresa($empresaId = null, $mes = null, $year = null, $soloMes = false)
    {
        if ($empresaId === null) {
            $centros = Auth::user()->userable->centros()->get();
            $inicial = Auth::user()->userable->getTotalPresupuestoByDate($mes, $year);
        } else {
            $centros = \App\Empresa::find($empresaId)->centros()->get();
            $inicial = \App\Empresa::find($empresaId)->getTotalPresupuestoByDate($mes, $year);
        }
        $date = Carbon::create($year ?? date("Y"), $mes ?? date("m"));

        $requerimientos = $centros->map(function($centro) use ($date) {
            return $centro->requerimientos()->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->get();
        });

        return view('presupuesto.index.empresa')->with(compact('requerimientos', 'inicial'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        switch (get_class(Auth::user()->userable)) {
        case 'App\Holding':
            $empresas = Auth::user()->userable->empresas()->get();
            $presupuestos = collect($empresas->map(function($empresa) {
                return collect(['empresa' => $empresa, 'presupuesto' => $empresa->presupuestos()->get()]);
            }));
            return view('presupuesto.create')->with(compact('presupuestos'));
            break;
        case 'App\Empresa':
            $centros = Auth::user()->userable->centros()->get();
            $presupuestos = collect($centros->map(function($centro) {
                return collect(['centro' => $centro, 'presupuesto' => $centro->presupuestos()->get()]);
            }));
            return view('presupuesto.create')->with(compact('centros'));
            break;
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
        $presupuestos = collect($request->input('input'));
        $year = $request->input('year');

        $presupuestos->map(function ($presupuesto) use ($year) {
            $centro = \App\Centro::find($presupuesto['centro']['id']);
            array_shift($presupuesto['presupuesto']);
            $presupuestos = collect($presupuesto['presupuesto']);
            $presupuestos->map(function($presupuesto, $index) use ($centro, $year) {
                $centro->presupuestos()->create([
                    "monto" => $presupuesto * 100,
                    "fecha_gestion" => Carbon::create($year, $index, 1)
                ]);
            });
        });

        return response()->json([
            "meta" => [
                "title" => 'Presupuesto Guardado',
                "msg" => 'El Presupuesto fue guardado exitosamente'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Presupuesto $presupuesto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Presupuesto $presupuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Presupuesto $presupuesto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Presupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presupuesto $presupuesto)
    {
        //
    }
}
