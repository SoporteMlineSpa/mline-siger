<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;

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
        try {
            $producto = new Producto();
            $producto->sku = $request->input('sku');
            $producto->detalle = $request->input('detalle');
            $producto->stock = $request->input('stock');
            $producto->precio = $request->input('precio');
            $producto->saveOrFail();
            if (in_array(0, $request->input('empresa'))) {
                $empresas = \App\Empresa::where('id', '>', 0)->get('id');
                $producto->empresas()->attach($empresas);
            } else {
                $producto->empresas()->attach($request->input('empresa'));
            }


            return response()->json([
                'data' => [
                    'producto' => [
                        'type' => 'Producto',
                        'id' => $producto->id,
                        'attributes' => $producto,
                    ]        ],
                    'meta' => [
                        'title' => '¡Producto guardado exitosamente!',
                        'message' => '
                        Un nuevo producto fue creado con los siguientes datos:<br />
                        <b>Familia:</b>'.$producto->familia.'<br />
                        <b>Detalle:</b>'.$producto->detalle.'<br />
                        <b>Marca:</b>'.$producto->marca.'<br />
                        <b>Formato:</b>'.$producto->formato.'<br />
                        <b>Precio:</b>'.$producto->precio.'<br />
                        '
        ]
      ], 201);
    } catch (Exception $e) {
      return response()->json([
        'errors' => [
          'status' => '500',
          'title' => 'Error guardando producto',
          'detail' => 'Ocurrio un error guardando el producto:'.$e,
          'source' => $e
        ]
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Producto  $producto
   * @return \Illuminate\Http\Response
   */
  public function show(Producto $producto)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Producto  $producto
   * @return \Illuminate\Http\Response
   */
  public function edit(Producto $producto)
  {
    //
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
    //
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
          'message' => 'El producto <b>'.$producto->detalle.'</b> ha sido borrado.<br />La pagina se recargara'
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
}
