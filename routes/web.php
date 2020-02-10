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

Route::get('/generar-guia/', function(){
    $txt = file_get_contents(storage_path('app/POR2020012800000000012.txt'));
    return (Storage::disk('ftp')->put("INTXT/POR2020012800000000012.txt", $txt));
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
        Route::get('descargar/{requerimiento}', 'RequerimientoController@descargarGuia')->name('pedidos.descargar');
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

            Route::group(['middleware' => ['type:\App\Centro', 'create']], function() {
                Route::get('crear', 'RequerimientoController@create')->name('requerimientos.create');
                Route::post('store', 'RequerimientoController@store')->name('requerimientos.store');
                Route::get('editar/{requerimiento}', 'RequerimientoController@edit')->name('requerimientos.edit');
                Route::get('recibida/{requerimiento}', 'RequerimientoController@entregado')->name('pedidos.entregado');
            });

            Route::group(['middleware' => ['type:\App\Empresa', 'validar']], function() {
                Route::get('validar-pedidos', 'RequerimientoController@validarPedidos')->name('pedidos.validar');

                Route::post('aceptar', 'RequerimientoController@aceptar')->name('pedidos.aceptar');
                Route::post('rechazar', 'RequerimientoController@rechazar')->name('pedidos.rechazar');
                Route::post('aceptar-todos', 'RequerimientoController@aceptarTodos')->name('pedidos.aceptarTodos');
                Route::post('rechazar-todos', 'RequerimientoController@rechazarTodos')->name('pedidos.rechazarTodos');
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
            Route::get('usuarios-centro/{usuario}', 'UserController@editCentro')->name('user.editCentro');
            Route::put('usuarios-centro/{usuario}', 'UserController@updateCentro')->name('user.updateCentro');
            Route::delete('usuarios-centro/{usuario}', 'UserController@destroy')->name('user.destroyCentro');
        });

        Route::get('presupuesto/centro/{centroId?}/{mes?}{year?}{acumulado?}', 'PresupuestoController@indexCentro')->name('presupuesto.indexCentro');
    });

    // Rutas de Compass
    Route::group(['prefix' => 'compass', 'middleware' => ['compass']], function() {

        Route::get('/', 'HomeController@index')->name('compass.home');

        Route::get('index-empresa/{empresa?}', 'ProductoController@indexEmpresa')->name('productos.indexEmpresa');
        Route::resource('productos', 'ProductoController');

        Route::get('asignacion-masiva', 'ProgramacionPrecioController@show')->name('productos.asignacionMasivaView');
        Route::get('formato-precio', 'ProgramacionPrecioController@formato')->name('productos.formato');
        Route::post('formato-precio', 'ProgramacionPrecioController@crearProgramacion')->name('productos.asignacionMasiva');

        Route::get('carga-masiva', 'ProductoController@cargaMasivaView')->name('productos.cargaMasivaView');
        Route::get('formato-productos', 'ProductoController@formatoProductos')->name('productos.formatoProductos');
        Route::post('formato-productos', 'ProductoController@cargaMasiva')->name('productos.asignacionMasivaProductos');

        Route::resource('holdings', 'HoldingController')->except([
            'show'
        ]);

        Route::resource('abastecimientos', 'AbastecimientoController')->except([
            'show'
        ]);

        Route::resource('bodegueros', 'BodegueroController')->except([
            'show'
        ]);

        Route::resource('horarios', 'HorarioController')->except([
            'index'
        ]);

        Route::get('estado/empresa/{empresa}', 'EmpresaController@habilitarForm')->name('empresas.habilitar.get');
        Route::get('estado/centro/{centro}', 'CentroController@habilitarForm')->name('centro.habilitar.get');
        Route::post('habilitar/empresa/{empresa}', 'EmpresaController@habilitar')->name('empresas.habilitar');
        Route::post('habilitar/centro/{centro}', 'CentroController@habilitar')->name('centro.habilitar');

        Route::get('usuarios/{tipo?}', 'UserController@index')->name('usuarios.index');
        Route::get('asignar-usuarios', 'UserController@usuariosSinAsignar')->name('usuarios.asignar');
        Route::get('asignacion-usuario/{userId}/{tipo}', 'UserController@asignar')->name('usuario.asignar');
        Route::post('asignacion-usuario', 'UserController@asignacion')->name('usuario.asignacion');
        Route::get('users/{usuario}', 'UserController@edit')->name('usuarios.edit');
        Route::put('users/{usuario}', 'UserController@update')->name('usuarios.update');
        Route::delete('users/{usuario}', 'UserController@destroy')->name('usuarios.destroy');

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
            Route::get('generar-formatos-despacho/{transporte}', 'TransporteController@generarFormatosDespacho')->name('compass.pedidos.formatosDespacho');

            Route::post('programar-despacho', 'RequerimientoController@programarDespacho')->name('compass.pedidos.programarDespachos.post');

            Route::post('eliminar-despacho/{id}', 'RequerimientoController@eliminarDespacho')->name('compass.eliminarDespacho');
            Route::post('generar-guia/{id}', 'RequerimientoController@generarGuia')->name('compass.generarGuia');
            Route::post('despachar/{id}', 'RequerimientoController@despachar')->name('compass.despachar');
        });

        Route::group(['prefix' => 'reportes'], function() {
            Route::get('productos_cantidad/{year?}', 'ReportController@productosPorCantidad')->name('reportes.productosCantidad');
            Route::get('packs', 'ReportController@packs')->name('reportes.packs');
            Route::post('packs', 'ReportController@generarPack')->name('reportes.packs.generar');

            Route::get('productos', 'ReportController@productos')->name('reportes.productos');
            Route::post('productos', 'ReportController@generarRebajas')->name('reportes.productos.generar');
        });
    });
});
