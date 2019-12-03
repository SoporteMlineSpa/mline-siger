<?php



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
  return redirect()->route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'cliente'], function() {

  Route::get('/', function() {
    return view('cliente.home');
  })->name('cliente.home');

  Route::resource('empresas', 'EmpresaController');

  Route::group(['prefix' => 'pedidos'], function() {
    Route::get('validar-pedidos', 'RequerimientoController@validarPedidos')->name('pedidos.validar');
  //TODO: Cambiar a POST
    Route::get('aceptar/{requerimiento}', 'RequerimientoController@aceptar')->name('pedidos.aceptar');
  //TODO: Cambiar a POST
    Route::get('rechazar/{requerimiento}', 'RequerimientoController@rechazarPedidos')->name('pedidos.rechazar');
    Route::get('{abastecimiento}', 'RequerimientoController@showCentro')->name('pedidos.centro');
  });

  Route::resource('requerimientos', 'RequerimientoController');

});

Route::group(['prefix' => 'compass'], function() {

  Route::get('/', function() {
    return view('compass.home');
  })->name('compass.home');

  Route::resource('productos', 'ProductoController');

  Route::group(['prefix' => 'ordenes'], function() {
    Route::get('/', 'RequerimientoController@index')->name('compass.pedidos.index');
    Route::get('verificar', 'RequerimientoController@verificar')->name('compass.pedidos.verificar');
  });

});
