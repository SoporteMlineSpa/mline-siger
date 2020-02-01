<?php

namespace App\Http\Controllers;

use App\Exports\FormatoRebaja;
use App\Exports\FormatoPack;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
    
    /**
     * Muestra la pantalla para generacion de Packs
     *
     * @return \Illuminate\Http\Response
     */
    public function packs()
    {
        $empresas = \App\Empresa::all();

        return view('reporte.packs')->with(compact('empresas'));
    }

    /**
     * Genera el pack de esa semana para la Empresa seleccionada
     *
     * @return \Illuminate\Http\Response
     */
    public function generarPack(Request $request)
    {
        $empresa = \App\Empresa::find($request->input('empresa'));
        $razon = $empresa->razon_social;

        return Excel::download(new FormatoPack($empresa, $request->input('inicio'), $request->input('fin')), "pack-$razon.xlsx");
    }

    /**
     * Muestra la pantalla para la generacion de rebajas de Productos
     *
     * @return \Illuminate\Http\Response
     */
    public function productos()
    {
        $productos = \App\Producto::all();

        return view('reporte.productos')->with(compact('productos'));
    }

    /**
     * Generar las rebajas de Producto segun producto y rango de tiempo
     *
     * @return \Illuminate\Http\Response
     */
    public function generarRebajas(Request $request)
    {
        $producto = \App\Producto::find($request->input('nuevoProducto'));

        return Excel::download(new FormatoRebaja($producto, $request->input('inicio'), $request->input('fin')), "rebaja-$producto->detalle.xlsx");
    }
    
}
