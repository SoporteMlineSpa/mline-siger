<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Holding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Listado de Empresas y sus Requerimientos
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRequerimientos($estadoId = null)
    {
        $empresas = collect([]);
        if (Auth::user()->userable instanceof \App\Holding) {
            $empresasUser = Auth::user()->userable->empresas()->get();
            foreach ($empresasUser as $empresa) {
                $empresas->push($empresa);
            }
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = \App\Empresa::all();
        }

        return view('requerimiento.index.empresa')->with(compact('empresas'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();

        return view('empresa.index')->with(compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->userable instanceof \App\Holding) {
            $holdings = Auth::user()->userable;
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $holdings = \App\Holding::all();
        } else {
            $holdings = [];
        }
        $abastecimientos = \App\Abastecimiento::all();

        return view('empresa.create')->with(compact('holdings', 'abastecimientos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = new Empresa;
        $empresa->razon_social = $request->input('razon_social');
        $empresa->rut = $request->input('rut');
        $empresa->direccion = $request->input('direccion');

        ($empresa->abastecimiento()->associate(\App\Abastecimiento::find($request->input('abastecimiento'))));
        if ($request->has('holding')) {
            $empresa->holding()->associate(\App\Holding::find($request->input('holding')));
        }
        if ($empresa->saveOrFail()) {
            $msg = [
                'data' => [
                    'empresa' => [
                        'type' => 'Empresa',
                        'id' => $empresa->id,
                        'attributes' => $empresa,
                    ],
                    'usuario' => [
                        'type' => 'Usuario',
                        'id' => Auth::id(),
                        'attributes' => Auth::user()
                    ]
                ],
                'meta' => [
                    'title' => '¡Empresa guardada exitosamente!',
                    'message' => 'Una nueva Empresa fue creada con los siguientes datos:<br /><b>Razon Social:</b>'.$empresa->razon_social.'<br/><b>RUT:</b>'.$empresa->rut.'<br/><b>Direccion:</b>'.$empresa->direccion.'<br/><b>Punto de Abastecimiento:</b>'.$empresa->abastecimiento()->get('nombre')->first()->nombre
                ]
            ];
            return redirect()->route('empresas.index')->with(compact('msg'));
        } else {
            $msg = [
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando Empresa',
                    'detail' => 'Ocurrio un error guardando la Empresa:'.$e,
                    'source' => $e
                ]
            ];
            return redirect()->route('empresas.index')->with(compact('msg'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        if (Auth::user()->userable instanceof \App\Holding) {
            $holdings = Auth::user()->userable;
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $holdings = \App\Holding::all();
        } else {
            $holdings = [];
        }

        $abastecimientos = \App\Abastecimiento::all();

        return view('empresa.edit')->with(compact('empresa', 'holdings', 'abastecimientos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        $empresa->razon_social = $request->input('razon_social');
        $empresa->rut = $request->input('rut');
        $empresa->direccion = $request->input('direccion');
        $empresa->abastecimiento()->associate(\App\Abastecimiento::find($request->input('abastecimiento')));
        if ($request->has('holding')) {
            $empresa->holding()->associate(Holding::find($request->input('holding')));
        }
        if ($empresa->saveOrFail()) {
            $msg = [
                'data' => [
                    'empresa' => [
                        'type' => 'Empresa',
                        'id' => $empresa->id,
                        'attributes' => $empresa,
                    ],
                    'usuario' => [
                        'type' => 'Usuario',
                        'id' => Auth::id(),
                        'attributes' => Auth::user()
                    ]
                ],
                'meta' => [
                    'title' => '¡Empresa guardada exitosamente!',
                    'message' => 'La Empresa fue actualizada con los siguientes datos:<br /><b>Holding:</b>'.$empresa->holding()->get('nombre')->first()->nombre.'<br/><b>Razon Social:</b>'.$empresa->razon_social.'<br/><b>RUT:</b>'.$empresa->rut.'<br/><b>Direccion:</b>'.$empresa->direccion.'<br/><b>Punto de Abastecimiento:</b>'.$empresa->abastecimiento()->get('nombre')->first()->nombre
                ]
            ];
            return redirect()->route('empresas.index')->with(compact('msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        try {
            $empresa->delete();

            return response()->json([
                'data' => [
                    'empresa' => [
                        'type' => 'Empresa',
                        'id' => $empresa->id,
                        'attributes' => $empresa,
                    ],
                ],
                'meta' => [
                    'title' => '¡Empresa eliminada exitosamente!',
                    'message' => 'La Empresa <b>'.$empresa->nombre.'</b> ha sido borrada.<br />La pagina se recargara'
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error eliminando empresa',
                    'detail' => 'Ocurrio un error eliminando la empresa',
                    'source' => $e
                ]
            ], 500);
        }
    }
}
