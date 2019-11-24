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

  Route::resource('pedidos', 'RequerimientoController');

});

Route::group(['prefix' => 'compass'], function() {

  Route::get('/', function() {
    return view('compass.home');
  })->name('compass.home');

  Route::resource('productos', 'ProductoController');

});
