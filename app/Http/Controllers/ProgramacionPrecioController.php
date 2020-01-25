<?php

namespace App\Http\Controllers;

use App\ProgramacionPrecio;
use Illuminate\Http\Request;

class ProgramacionPrecioController extends Controller
{

    /**
     * Muestra la pantalla para programar la asignacion masiva
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $empresas = \App\Empresa::all();
        return view('compass.producto.asignacion_masiva')->with(compact('empresas'));
    }

    /**
     * Descargar formato de Excel para carga masiva de Precios
     *
     * @return Excel
     */
    public function formato()
    {
        return Excel::download(new FormatoAsignacionPrecios, 'formato.xlsx');
    }

    /**
     * Crea la programacion para la asignacion de precios
     *
     * @return \Illuminate\Http\Response
     */
    public function crearProgramacion(Request $request)
    {
        $programacion = new ProgramacionPrecio;
        $programacion->empresa_id = $request->input('empresa');
        $programacion->precios = $request->file('formato')->store('programaciones');
        $programacion->fecha = $request->input('fecha');

        if ($programacion->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => "Asignacion de precios Programada",
                    "msg" => "La Asignacion de precios ha sido programada para el " . $programacion->fecha
                ]
            ];

            return back()->with(compact('msg'));
        }
    }
    
}
