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

Route::get('/', function() {
    if (Auth::check()) {
        if (Auth::user()->userable instanceof \App\CompassRole) {
            return redirect()->to('/compass/');
        } else {
            return redirect()->to('/cliente/');
        }
    } else {
        return redirect()->route('login');
    }
});

Route::group(['middleware' => 'auth'], function() {

    // Notificaciones
    Route::get('/notificaciones', 'ShowNotification')->name('notifications');

    // Global CRUDS
    Route::resource('empresas', 'EmpresaController')->except([
        'show'
    ]);
    Route::resource('centros', 'CentroController')->except([
        'show'
    ]);
    Route::group(['prefix' => 'pedidos'], function() {
        Route::get('lista', 'EmpresaController@indexRequerimientos')->name('pedidos.indexEmpresa');
        Route::get('empresa/{empresa?}/{estado?}', 'CentroController@indexRequerimientos')->name('pedidos.indexCentro');
        Route::get('centro/{centroId}/{estado?}', 'RequerimientoController@showCentro')->name('pedidos.centro');
    });

    // Rutas del Cliente
    Route::group(['prefix' => 'cliente'], function() {

        Route::get('/', 'HomeController@index')->name('cliente.home');

        Route::group(['prefix' => 'pedidos'], function() {

            Route::get('crear', 'RequerimientoController@create')->name('requerimientos.create');
            Route::post('store', 'RequerimientoController@store')->name('requerimientos.store');
            Route::get('editar/{requerimiento}', 'RequerimientoController@edit')->name('requerimientos.edit');

            Route::get('validar-pedidos', 'RequerimientoController@validarPedidos')->name('pedidos.validar');
            Route::get('{centro}/{estado?}', 'RequerimientoController@showCentro')->name('pedidos.centro');

            Route::post('aceptar', 'RequerimientoController@aceptar')->name('pedidos.aceptar');
            Route::post('rechazar', 'RequerimientoController@rechazar')->name('pedidos.rechazar');
            Route::post('aceptar-todos', 'RequerimientoController@aceptarTodos')->name('pedidos.aceptarTodos');
        });

        Route::get('presupuestos/cmi', 'PresupuestoController@cmi')->name('presupuesto.cmi');
        Route::get('presupuesto/empresa/{empresaId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexEmpresa')->name('presupuesto.indexEmpresa');
        Route::get('presupuesto/centro/{centroId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexCentro')->name('presupuesto.indexCentro');
        Route::resource('presupuesto', 'PresupuestoController')->except([
            'index', 'show'
        ]);
    });

    // Rutas de Compass
    Route::group(['prefix' => 'compass'], function() {

        Route::get('/', 'HomeController@index')->name('compass.home');

        Route::resource('productos', 'ProductoController');
        Route::resource('holdings', 'HoldingController')->except([
            'show'
        ]);

        Route::get('usuarios/{tipo?}', 'UserController@index')->name('usuarios.index');
        Route::get('asignar-usuarios', 'UserController@usuariosSinAsignar')->name('usuarios.asignar');
        Route::get('asignacion-usuario/{userId}/{tipo}', 'UserController@asignar')->name('usuario.asignar');
        Route::post('asignacion-usuario', 'UserController@asignacion')->name('usuario.asignacion');
        Route::resource('usuarios', 'UserController')->only([
            'edit', 'update', 'destroy'
        ]);

        Route::group(['prefix' => 'pedidos'], function() {

            Route::get('verificar', 'RequerimientoController@verificar')->name('compass.pedidos.verificar');
            Route::post('verificar', 'RequerimientoController@doVerificar')->name('compass.verificar');

            Route::get('armar', 'RequerimientoController@indexCajas')->name('compass.pedidos.cajasIndex');
            Route::get('armar/{requerimientoId}', 'RequerimientoController@show')->name('compass.pedidos.show');
        });

    });
});
