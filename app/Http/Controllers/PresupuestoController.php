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
    public function indexHolding($year = null, $mes = null, $acumulado = 0)
    {
        $empresas = Auth::user()->userable->empresas()->get();
        $inicial = $empresas->map(function($empresa) {
            return $empresa->getTotalPresupuestoByDate($mes, $year);
        })->reduce(function($carry, $item) {
            return $carry + $item;
        });
        return view('presupuesto.index.holding')->with(compact('presupuestos'));
    }

    /**
     * Lista de Centros con su Presupuesto
     *
     * @return void
     */
    public function indexEmpresa($empresaId = null, Request $request, $acumulado = 0)
    {
        $year = $request->has('year') ? $request->get('year') : null;
        $mes = $request->has('mes') ? $request->get('mes') : null;
        if (is_null($empresaId)) {
            $centros = Auth::user()->userable->centros()->get();
            if ($acumulado == 1) {
                $inicial = Auth::user()->userable->getTotalPresupuestoByDate(null, $year);
            } else {
                $inicial = Auth::user()->userable->getTotalPresupuestoByDate($mes, $year);
            }
        } else {
            $centros = \App\Empresa::find($empresaId)->centros()->get();
            if ($acumulado == 1) {
                $inicial = \App\Empresa::find($empresaId)->getTotalPresupuestoByDate(null, $year);
            } else {
                $inicial = \App\Empresa::find($empresaId)->getTotalPresupuestoByDate($mes, $year);
            }
        }
        $date = Carbon::create($year ?? date("Y"), $mes ?? date("m"));

        $requerimientos = $centros->map(function($centro) use ($date, $year, $mes, $acumulado) {
            $query = $centro->requerimientos();
            if (!is_null($year)) {
                $query = $query->whereYear('created_at', $date->year);
            }
            if (!is_null($mes) && !($acumulado == 1)) {
                $query = $query->whereMonth('created_at', $date->month);
            }
            return $query->get();
        });

        return view('presupuesto.index.empresa')->with(compact('requerimientos', 'inicial', 'date'));
    }

    /**
     * Lista de Cuenta segun su Presupuesto
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCentro($centroId = null, Request $request, $acumulado = 0, $soloMes = 1)
    {
        $year = $request->has('year') ? $request->get('year') : null;
        $mes = $request->has('mes') ? $request->get('mes') : null;
        if (is_null($centroId)) {
            $centro = Auth::user()->userable;
            $inicial = $centro->getTotalPresupuestoByDate($mes, $year);
        } else {
            $centro = \App\Centro::find($centroId);
            $inicial = $centro->getTotalPresupuestoByDate($mes, $year);
        }

        $date = Carbon::create($year ?? date("Y"), $mes ?? date("m"));

        $requerimientos = $centro->requerimientos();
        if (!is_null($year)) {
            $requerimientos = $requerimientos->whereYear('created_at', $date->year);
        }
        if (!is_null($mes)) {
            $requerimientos = $requerimientos->whereMonth('created_at', $date->month);
        }
        $requerimientos = $requerimientos->get();

        return view('presupuesto.index.centro')->with(compact('requerimientos', 'inicial', 'date'));
    }
    
    /**
     * Retorna el CMI de la Empresa
     *
     * @return \Illuminate\Http\Response
     */
    public function cmi($empresaId = null)
    {
        if (is_null($empresaId)) {
            $centros = Auth::user()->userable->centros()->get();
        } else {
            $centros = \App\Empresa::findOrFail($empresaId)->centros()->get();
        }
        $cmi = collect([]);
        foreach ($centros as $centro) {
            $iniciales = $centro->presupuestos()->whereYear("fecha_gestion", date("Y") + 1)->get();
            $totales = $centro->getTotalByMes();
            $cmi->push(collect([$centro, $iniciales, $totales]));
        }

        return view('presupuesto.index.cmi')->with(compact('cmi'));
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
