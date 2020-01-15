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
    Route::post('/notificaciones', 'SearchNotification')->name('SearchNotification');

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
        Route::get('centro/{centro}/{year?}', 'RequerimientoController@centroIndex')->name('pedidos.centro');
        Route::get('lista/{centro}/{estado?}', 'RequerimientoController@showCentro')->name('pedidos.centroIndex');
        Route::get('{requerimiento}', 'RequerimientoController@show')->name('pedidos.show');

        Route::group(['prefix' => 'liberia', 'middleware' => ['type:\App\Centro']], function() {
            Route::get('index', 'UserController@libreriaIndex')->name('libreria.index');
            Route::put('{requerimiento}', 'UserController@libreriaEdit')->name('libreria.editar');
        });
    });

    // Rutas del Cliente
    Route::group(['prefix' => 'cliente', 'middleware' => ['cliente']], function() {

        Route::get('/', 'HomeController@index')->name('cliente.home');

        Route::group(['prefix' => 'pedidos'], function() {

            Route::group(['middleware' => ['type:\App\Centro']], function() {
                Route::get('crear', 'RequerimientoController@create')->name('requerimientos.create');
                Route::post('store', 'RequerimientoController@store')->name('requerimientos.store');
                Route::get('editar/{requerimiento}', 'RequerimientoController@edit')->name('requerimientos.edit');
                Route::get('recibida/{requerimiento}', 'RequerimientoController@entregado')->name('pedidos.entregado');
            });

            Route::group(['middleware' => ['type:\App\Empresa']], function() {
                Route::get('validar-pedidos', 'RequerimientoController@validarPedidos')->name('pedidos.validar');

                Route::post('aceptar', 'RequerimientoController@aceptar')->name('pedidos.aceptar');
                Route::post('rechazar', 'RequerimientoController@rechazar')->name('pedidos.rechazar');
                Route::post('aceptar-todos', 'RequerimientoController@aceptarTodos')->name('pedidos.aceptarTodos');
            });

        });

        Route::group(['middleware' => ['type:\App\Empresa']], function() {
            Route::get('presupuestos/cmi', 'PresupuestoController@cmi')->name('presupuesto.cmi');
            Route::get('presupuesto/empresa/{empresaId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexEmpresa')->name('presupuesto.indexEmpresa');
            Route::resource('presupuesto', 'PresupuestoController')->except([
                'index', 'show'
            ]);
            Route::get('usuarios-centro/', 'UserController@indexEmpresa')->name('user.indexEmpresa');
            Route::get('usuarios-centro/create', 'UserController@create')->name('user.create');
            Route::post('usuarios-centro/create', 'UserController@storeCentro')->name('user.store');
        });

        Route::get('presupuesto/centro/{centroId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexCentro')->name('presupuesto.indexCentro');
    });

    // Rutas de Compass
    Route::group(['prefix' => 'compass', 'middleware' => ['compass']], function() {

        Route::get('/', 'HomeController@index')->name('compass.home');

        Route::get('index-empresa/{empresa?}', 'ProductoController@indexEmpresa')->name('productos.indexEmpresa');
        Route::resource('productos', 'ProductoController');

        Route::get('asignacion-masiva', 'ProductoController@asignacionMasivaPreciosView')->name('productos.asignacionMasivaView');
        Route::get('formato-precio', 'ProductoController@formatoExcel')->name('productos.formato');
        Route::post('formato-precio', 'ProductoController@asignacionMasivaPrecios')->name('productos.asignacionMasiva');

        Route::get('carga-masiva', 'ProductoController@cargaMasivaView')->name('productos.cargaMasivaView');
        Route::get('formato-productos', 'ProductoController@formatoProductos')->name('productos.formatoProductos');
        Route::post('formato-productos', 'ProductoController@cargaMasiva')->name('productos.asignacionMasivaProductos');

        Route::resource('holdings', 'HoldingController')->except([
            'show'
        ]);

        Route::resource('abastecimientos', 'AbastecimientoController')->except([
            'show'
        ]);

        Route::get('usuarios/{tipo?}', 'UserController@index')->name('usuarios.index');
        Route::get('asignar-usuarios', 'UserController@usuariosSinAsignar')->name('usuarios.asignar');
        Route::get('asignacion-usuario/{userId}/{tipo}', 'UserController@asignar')->name('usuario.asignar');
        Route::post('asignacion-usuario', 'UserController@asignacion')->name('usuario.asignacion');
        Route::resource('usuarios', 'UserController')->only([
            'edit', 'update', 'destroy'
        ]);

        Route::get('cargar-folios', 'FolioController@create')->name('cargarFolios');
        Route::post('cargar-folios', 'FolioController@store')->name('folios.store');

        Route::get('reemplazar-producto/{requerimiento}/{producto}', 'RequerimientoController@cambiarProducto')->name('cajas.cambiar');
        Route::put('reemplazar-producto/{requerimiento}', 'RequerimientoController@reemplazar')->name('cajas.reemplazar');

        Route::group(['prefix' => 'pedidos'], function() {

            Route::get('verificar', 'RequerimientoController@verificar')->name('compass.pedidos.verificar');
            Route::post('verificar', 'RequerimientoController@doVerificar')->name('compass.verificar');

            Route::get('armar', 'RequerimientoController@indexCajas')->name('compass.pedidos.cajasIndex');
            Route::get('armar/{requerimiento}', 'RequerimientoController@showCaja')->name('compass.pedidos.show');
            Route::post('armar-caja/{requerimiento}', 'RequerimientoController@armarCaja')->name('compass.pedidos.armarCaja');

            Route::get('programar-despacho', 'RequerimientoController@programarDespachoView')->name('compass.pedidos.programarDespachos');
            Route::get('despachar', 'RequerimientoController@despacharView')->name('compass.pedidos.despachar');

            Route::post('programar-despacho', 'RequerimientoController@programarDespacho')->name('compass.pedidos.programarDespachos.post');

            Route::post('eliminar-despacho/{id}', 'RequerimientoController@eliminarDespacho')->name('compass.eliminarDespacho');
            Route::post('generar-guia/{id}', 'RequerimientoController@generarGuia')->name('compass.generarGuia');
            Route::post('despachar/{id}', 'RequerimientoController@despachar')->name('compass.despachar');
        });

        Route::group(['prefix' => 'reportes'], function() {
            Route::get('productos_cantidad/{year?}', 'ReportController@productosPorCantidad')->name('reportes.productosCantidad');
        });
    });
});
