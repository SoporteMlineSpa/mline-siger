<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Muestra el Reporte de Productos por Cantidad por fecha
     *
     * @return \Illuminate\Http\Response
     */
    public function productosPorCantidad($mes = null, $year = null)
    {
        $productos = \App\Producto::all();
        $report = $productos->map(function($producto) {
            $requerimientos = collect([]);
            $cantidad = collect([]);
            for ($i = 1; $i < 13; $i++) {
                $requerimientos->push($producto->getRequerimientosByDate(null, $i));
                $cantidad->push($producto->getCantidadByDate(null, $i));
            }
            $cantidad->push($cantidad->reduce(function($carry, $mes) {
                return $carry + $mes;
            }));

            return [
                "producto" => $producto,
                "empresas" => $producto->empresas()->get(),
                "requerimientos" => $requerimientos,
                "cantidad" => $cantidad
            ];
        });

        return view('reporte.productos_mes')->with(compact('report'));
    }
    
}
