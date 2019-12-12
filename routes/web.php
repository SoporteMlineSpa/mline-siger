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

Auth::routes();

Route::get('/notificaciones', 'ShowNotification')->name('notifications');

Route::resource('empresas', 'EmpresaController');
Route::resource('centros', 'CentroController');

Route::group(['prefix' => 'cliente'], function() {

    Route::get('/', 'HomeController@index')->name('cliente.home');

    Route::group(['prefix' => 'pedidos'], function() {

        Route::get('crear', 'RequerimientoController@create')->name('requerimientos.create');
        Route::post('store', 'RequerimientoController@store')->name('requerimientos.store');
        Route::get('editar/{requerimiento}', 'RequerimientoController@edit')->name('requerimientos.edit');

        Route::get('validar-pedidos', 'RequerimientoController@validarPedidos')->name('pedidos.validar');
        Route::get('{centro}/{estado?}', 'RequerimientoController@showCentro')->name('pedidos.centro');
        Route::get('/', 'RequerimientoController@index')->name('requerimientos.index');

        Route::post('aceptar', 'RequerimientoController@aceptar')->name('pedidos.aceptar');
        Route::post('rechazar', 'RequerimientoController@rechazar')->name('pedidos.rechazar');
        Route::post('aceptar-todos', 'RequerimientoController@aceptarTodos')->name('pedidos.aceptarTodos');
    });

});

Route::group(['prefix' => 'compass'], function() {

    Route::get('/', 'HomeController@index')->name('compass.home');

    Route::resource('productos', 'ProductoController');

    Route::group(['prefix' => 'pedidos'], function() {
        Route::get('/', 'RequerimientoController@index')->name('compass.pedidos.index');
        Route::get('verificar', 'RequerimientoController@verificar')->name('compass.pedidos.verificar');

        Route::get('armar', 'RequerimientoController@indexCajas')->name('compass.pedidos.cajasIndex');
        Route::get('armar/{requerimientoId}', 'RequerimientoController@show')->name('compass.pedidos.show');
    });

});
