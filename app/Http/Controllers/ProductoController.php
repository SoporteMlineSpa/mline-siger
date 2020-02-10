<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Exports\FormatoAsignacionPrecios;
use App\Exports\FormatoProductos;
use App\Imports\PreciosMasivaImport;
use App\Imports\ProductosMasiva;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();

        return view('compass.producto.index')->with(compact('productos'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexEmpresa(\App\Empresa $empresa)
    {
        $empresas = \App\Empresa::all();
        if (isset($empresa)) {
            $productos = $empresa->productos()->get();
        } else {
            $productos = \App\Producto::all();
        }

        return view('compass.producto.index_empresa')->with(compact('productos', 'empresas', 'empresa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas = \App\Empresa::all();
        return view('compass.producto.create')->with(compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producto = new Producto;
        $producto->sku = $request->input('sku');
        $producto->detalle = $request->input('detalle');
        $producto->costo = $request->input('costo');

        if ($producto->saveOrFail()) {

            $msg = [
                'meta' => [
                    'title' => '¡Producto guardado exitosamente!',
                    'msg' => '
                    Un nuevo producto fue creado con los siguientes datos:<br />
                    <b>SKU:</b>'.$producto->sku.'<br />
                    <b>Detalle:</b>'.$producto->detalle
                    ]
            ];
            return back()->with(compact('msg'));

        } else {
            $msg = [
                "meta" => [

                    'title' => 'Error guardando producto',
                    'msg' => 'Ocurrio un error guardando el producto'
                ]
            ];
            return back()->with(compact('msg'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $empresas = \App\Empresa::all();
        $precios = $empresas->map(function($empresa) use ($producto) {
            $precio = $empresa->productos()->find($producto->id);
            return is_null($precio) ? 0 : $precio->pivot->precio;
        });

        return view('compass.producto.edit')->with(compact('producto', 'empresas', 'precios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $producto->sku = $request->input('sku');
        $producto->detalle = $request->input('detalle');
        $producto->costo = $request->input('costo');

        if ($producto->saveOrFail()) {

            $empresas = $request->input('empresas');

            foreach ($empresas as $index => $id) {
                if (is_int($request->input('precios')[$index]) && $request->input('precios')[$index] > 0) {
                    $producto->empresas()->attach($id, ['precio' => $request->input('precios')[$index]]);
                }
            }

            $msg = [
                'data' => [
                    'producto' => [
                        'type' => 'Producto',
                        'id' => $producto->id,
                        'attributes' => $producto,
                    ]
                ],
                'meta' => [
                    'title' => '¡Producto guardado exitosamente!',
                    'msg' => 'Un nuevo producto fue creado con los siguientes datos:<br /><b>SKU:</b>'.$producto->sku.'<br /><b>Detalle:</b>'.$producto->detalle
                ]
            ];
            return redirect()->route('productos.index')->with(compact('msg'));
        } else {

            $msg = [
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando producto',
                    'detail' => 'Ocurrio un error guardando el producto:'.$e,
                    'source' => $e
                ]
            ];
            return redirect()->route('productos.index')->with(compact('msg'));
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();

            return response()->json([
                'data' => [
                    'producto' => [
                        'type' => 'Producto',
                        'id' => $producto->id,
                        'attributes' => $producto,
                    ],
                ],
                'meta' => [
                    'title' => '¡Producto eliminado exitosamente!',
                    'msg' => 'El producto <b>'.$producto->detalle.'</b> ha sido borrado.<br />La pagina se recargara'
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error eliminando producto',
                    'detail' => 'Ocurrio un error eliminando el producto',
                    'source' => $e
                ]
            ], 500);
        }
    }

    /**
     * Descargar formato de Excel para carga masiva de Precios
     *
     * @return Excel
     */
    public function formatoExcel()
    {
        return Excel::download(new FormatoAsignacionPrecios, 'formato.xlsx');
    }


    /**
     * Descargar formato de Excel para carga masiva de Productos
     *
     * @return Excel
     */
    public function formatoProductos()
    {
        return Excel::download(new FormatoProductos, 'productos.xlsx');
    }

    /**
     * Realiza la carga masiva de productos
     *
     * @return \Illuminate\Http\Response
     */
    public function cargaMasivaView()
    {
        return view('compass.producto.carga_masiva');
    }

    /**
     * Realiza la carga masiva de productos
     *
     * @return \Illuminate\Http\Response
     */
    public function cargaMasiva(Request $request)
    {
        Excel::import(new ProductosMasiva, $request->file('productos'));

        $msg = [
            "meta" => [
                "title" => "Carga Masiva Exitosa",
                "msg" => "La carga masiva se realizo correctamente"
            ]
        ];

        return back()->with(compact('msg'));
    }
}
