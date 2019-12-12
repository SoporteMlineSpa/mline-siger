<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Holding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->userable instanceof \App\Empresa) {
            $empresas = Auth::user()->userable;
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = \App\Empresa::all();
        } else {
            $empresa = [];
        }
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
            $holdings = \App\Empresa::all();
        } else {
            $holdings = [];
        }

        return view('empresa.create')->with(compact('holdings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $holding = Holding::find($request->input('holding'));
            $empresa = new Empresa();
            $empresa->nombre = $request->input('nombre');
            $empresa->holding()->associate($holding);
            $empresa->saveOrFail();
            $empresa->users()->attach(Auth::user());

            return response()->json([
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
                    'message' => 'Una nueva empresa fue creada con los siguientes datos:<br /> <b>Nombre:</b>'.$empresa->nombre.'<br /><b>Holding:</b>'.$holding->nombre
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando empresa',
                    'detail' => 'Ocurrio un error guardando la empresa:'.$e,
                    'source' => $e
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
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

        return view('empresa.edit')->with(compact('empresa', 'holdings'));
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
        $empresa = new Empresa();
        $empresa->razon_social = $request->input('razon_social');
        $empresa->rut = $request->input('rut');
        $empresa->direccion = $request->input('direccion');
        if (null !== $request->input('holding')) {
            $empresa->holding()->associate(Holding::find($request->input('holding')));
        }
        $empresa->saveOrFail();
        $empresa->users()->attach(Auth::user());

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
                'message' => 'Una nueva empresa fue creada con los siguientes datos:<br /> <b>Nombre:</b>'.$empresa->nombre.'<br /><b>Holding:</b>'.$holding->nombre
            ]
        ];
        return back()->with(compact($msg));
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
