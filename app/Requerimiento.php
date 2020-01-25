<?php

namespace App;

use App\Notifications\EstadoUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Requerimiento extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::saved( function ($model) {
            $users = $model->getUserByRequerimiento();
            $users->map(function ($user) use ($model) {
                $user->notify((new EstadoUpdated($model))->delay(\Carbon\Carbon::now()->addSeconds(60)));
            });
        });
    }

    /**
     * Retorna los productos relacionados a ese Requerimietno
     *
     * @return App\Producto
     */
    public function productos()
    {
        return $this->belongsToMany('App\Producto')->withPivot('cantidad', 'precio', 'real', 'observacion', 'fecha_vencimiento');
    }

    /**
     * Retorna el Centro al que pertenece ese requerimiento
     *
     * @return App\Centro
     */
    public function centro()
    {
        return $this->belongsTo('App\Centro');
    }

    /**
     * Retorna el transporte de ese Requerimiento
     *
     * @return \App\Requerimiento
     */
    public function transporte()
    {
        return $this->belongsTo('App\Transporte');
    }

    /**
     * Retorna el Bodeguero encargado de ese Requerimiento
     *
     * @return \App\Bodeguero
     */
    public function bodeguero()
    {
        return $this->belongsTo('App\Bodeguero');
    }
    


    /**
     * Retorna los Usuarios con este Requerimiento en su libreria
     *
     * @return App\User
     */
    public function users()
    {
        return $this->belongsToMany('App\Requerimiento')->withPivot('nombre');
    }


    /**
     * Retornar lista de Usuarios relacionados a ese Requerimiento
     *
     * @return \App\User
     */
    public function getUserByRequerimiento()
    {
        $users = collect([]);

        $centro = $this->centro()->firstOrFail();
        $centroUser = $centro->users()->firstOrFail();
        $users->push($centroUser);

        $empresa = $centro->empresa()->firstOrFail();
        $empresaUser = $empresa->users()->firstOrFail();
        $users->push($empresaUser);

        $compassUsers = \App\User::whereHasMorph(
            'userable',
            ['App\CompassRole'],
            function ($query) {
                $query->where('name', 'like', 'Compras')->orWhere('name', 'like', 'Despacho');
            }
        )->get();
        foreach ($compassUsers as $user) {
            $users->push($user);
        }

        return $users;
    }

    /**
     * Retorna el Total de ese Requerimiento
     *
     * @return Int
     */
    public function getTotal()
    {
        $productos = $this->productos()->get();
        $total = $productos->map(function($producto) {
            return $producto->pivot->cantidad * $producto->pivot->precio;
        })->reduce(function($carry, $item) {
            return ($carry + $item);
        });

        return $total;
    }

    /**
     * Genera un txt con los datos para la guia de despacho electronica
     *
     * @return Boolean
     */
    public function generarGuiaDespacho()
    {

        $folio = str_pad($this->folio, 10, "0", STR_PAD_LEFT);
        $fecha = date("Y-m-d");
        $rutReceptor = str_replace(".", "", $this->centro->empresa->rut);
        $razonSocialReceptor = $this->centro->empresa->razon_social;
        $giroReceptor = $this->centro->empresa->giro;
        $direccionReceptor = $this->centro->direccion;
        $comunaReceptor = $this->centro->comuna;
        $ciudadReceptor = $this->centro->ciudad;
        $direccionDestino = $this->transporte->abastecimiento->nombre;
        $comunaDestino = $this->transporte->abastecimiento->comuna;
        $ciudadDestino = $this->transporte->abastecimiento->ciudad;
        $transporteRut = str_replace(".", "", $this->transporte->rut);
        $transporteNombre = $this->transporte->nombre;
        $montoTotal = $this->getTotal();
        // $folio debe tener un largo de 10 caracteres (rellenar al inicio)
        // $fecha debe tener un largo de 8 caracteres en el formato aaaammdd
        // $rutReceptor debe tener un largo de 9 caracteres incluyendo el
        // digito verificador
        // $razonSocialReceptor debe tener un largo de 100 caracteres
        // $direccionReceptor debe tener un largo de 60 caracteres
        // $comunaReceptor debe tener un largo de 20 caracteres
        // $ciudadReceptor debe tener un largo de 15 caracteres

        $txt = "
XXX INICIO DOCUMENTO\n
========== AREA IDENTIFICACION DEL DOCUMENTO\n
Tipo Documento Tributario Electronico            : 52\n
Folio Documento                                  : $folio\n
Fecha de Emision                                 : $fecha\n
Indicador No rebaja                              :\n
Tipo de Despacho                                 : 2\n
Indicador de Traslado                            : 6\n
Tipo Impresion                                   :\n
Indicador de servicio                            :\n
Indicador de Montos Brutos                       :\n
Indicador de Montos Netos                        :\n
Forma de Pago                                    :\n
Forma de Pago Exportacion                        :\n
Fecha de Cancelacion                             :\n
Monto Cancelado                                  :\n
Saldo Insoluto                                   :\n
Fecha de Pago                                    :\n
Fecha de Pago                                    :\n
Fecha de Pago                                    :\n
Fecha de Pago                                    :\n
Periodo Desde                                    :\n
Periodo Hasta                                    :\n
Medio de Pago                                    :\n
Tipo de Cuenta de Pago                           :\n
Numero de Cuenta de Pago                         :\n
Banco de Pago                                    :\n
Codigo Terminos de Pago                          :\n
Glosa del Termino de Pago                        :\n
Dias del Termino de Pago                         :\n
Fecha de Vencimiento                             :\n
========== AREA EMISOR\n
Rut Emisor                                       : 96652910-k\n
Razon Social Emisor                              : COMPASS CATERING SA\n
Giro del Emisor                                  : Servicios de Alimentacion\n
Telefono                                         : 225910600\n
Correo Emisor                                    :\n
ACTECO                                           : 602300\n
Codigo Emisor Traslado Excepcional               :\n
Folio Autorizacion                               :\n
Fecha Autorizacion                               :\n
Direccion de Origen Emisor                       : AV. DEL VALLE N° 787, OF. 501\n
Comuna de Origen Emisor                          : HUECHARUBA\n
Ciudad de Origen Emisor                          : SANTIAGO\n
Nombre Sucursal                                  :\n
Codigo Sucursal                                  : \n
Codigo Adicional Sucursal                        :\n
Codigo Vendedor                                  :\n
Identificador Adicional del Emisor               :\n
RUT Mandante                                     :\n
========== AREA RECEPTOR\n
Rut Receptor                                     : $rutReceptor\n
Codigo Interno Receptor                          : \n
Nombre o Razon Social Receptor                   : $razonSocialReceptor\n
Numero Identificador Receptor Extranjero         :\n
Nacionalidad del Receptor Extranjero             :\n
Identificador Adicional Receptor Extranjero      :\n
Giro del negocio del Receptor                    : $giroReceptor\n
Contacto                                         : \n
Correo Receptor                                  :\n
Direccion Receptor                               : $direccionReceptor\n
Comuna Receptor                                  : $comunaReceptor\n
Ciudad Receptor                                  : $ciudadReceptor\n
Direccion Postal Receptor                        :\n
Comuna Postal Receptor                           :\n
Ciudad Postal Receptor                           :\n
Rut Solicitante de Factura                       :\n
========== AREA TRANSPORTES\n
Patente                                          : \n
RUT Transportista                                :\n
Rut Chofer                                       : $transporteRut\n
Nombre del Chofer                                : $transporteNombre\n
Direccion Destino                                : $direccionDestino\n
Comuna Destino                                   : $comunaDestino\n
Ciudad Destino                                   : $ciudadDestino\n
Modalidad De Ventas                              :\n
Clausula de Venta Exportacion                    :\n
Total Clausula de Venta Exportacion              :\n
Via de Transporte                                :\n
Nombre del Medio de Transporte                   :\n
RUT Compania de Transporte                       :\n
Nombre Compania de Transporte                    :\n
Identificacion Adicional Compania de Transporte  :\n
Booking                                          :\n
Operador                                         :\n
Puerto de Embarque                               :\n
Identificador Adicional Puerto de Embarque       :\n
Puerto Desembarque                               :\n
Identificador Adicional Puerto de Desembarque    :\n
Tara                                             :\n
Unidad de Medida Tara                            :\n
Total Peso Bruto                                 :\n
Unidad de Peso Bruto                             :\n
Total Peso Neto                                  :\n
Unidad de Peso Neto                              :\n
Total Items                                      :\n
Total Bultos                                     :\n
Codigo Tipo de Bulto                             :\n
Codigo Tipo de Bulto                             :\n
Codigo Tipo de Bulto                             :\n
Codigo Tipo de Bulto                             :\n
Flete                                            :\n
Seguro                                           :\n
Codigo Pais Receptor                             :\n
Codigo Pais Destino                              :\n
========== AREA TOTALES\n
Tipo Moneda Transaccion                          :\n
Monto Neto                                       : 0\n
Monto Exento                                     : 0\n
Monto Base Faenamiento de Carne                  :\n
Monto Base de Margen de  Comercializacion        :\n
Tasa IVA                                         : 0\n
IVA                                              : 19\n
IVA Propio                                       :\n
IVA Terceros                                     :\n
Impuesto Adicional                               :\n
Impuesto Adicional                               :\n
Impuesto Adicional                               :\n
Impuesto Adicional                               :\n
Impuesto Adicional                               :\n
Impuesto Adicional                               :\n
IVA no Retenido                                  :\n
Credito Especial Emp. Constructoras              :\n
Garantia Deposito Envases                        :\n
Valor Neto Comisiones                            :\n
Valor Exento Comisiones                          :\n
IVA Comisiones                                   :\n
Monto Total                                      : $montoTotal\n
Monto No Facturable                              :\n
Monto Periodo                                    :\n
Saldo Anterior                                   :\n
Valor a Pagar                                    :\n
========== AREA OTRA MONEDA\n
Tipo Moneda                                      :\n
Tipo Cambio                                      :\n
Monto Neto Otra Moneda                           :\n
Monto Exento Otra Moneda                         :\n
Monto Base Faenamiento de Carne Otra Moneda      :\n
Monto Margen Comerc. Otra Moneda                 :\n
IVA Otra Moneda                                  :\n
IVA Propio                                       :\n
Tasa Imp. Otra Moneda                            :\n
Valor Imp. Otra Moneda                           :\n
IVA No Retenido Otra Moneda                      :\n
Monto Total Otra Moneda                          :\n
";

        $txt .= "
========== DETALLE DE PRODUCTOS Y SERVICIOS\n
";
        $lineasProductos = $this->productos()->get()->map(function ($producto, $index) {
            // $numLinea debe tener un largo de 4 caracteres
            // $numLineaSII debe tener un largo de 4 caracteres
            // $sku debe tener un largo de 35 caracteres (rellenar al final)
            // $detalle debe tener un largo de 80 caracteres (rellenar al final)
            // $cantidad debe tener un largo de 18 caracteres: 12 enteros y 6
            // decimales
            // $precio debe tener un largo de 18 caracteres: 12 enteros y 4
            // decimales
            // $total debe tener un largo de 18 caracteres: 12 enteres y 4 decimales
            $sku = str_pad($producto->sku, 35);
            $detalle = str_pad($producto->detalle, 80);
            $real = explode(".", $producto->pivot->real);
            $cantidad = str_pad($real[0], 12, "0", STR_PAD_LEFT) .
                '.' .
                str_pad((isset($real[1]) ? $real[1] : "0"), 5, "0");
            $precio = str_pad($producto->pivot->precio, 18, "0", STR_PAD_LEFT);
            $monto = explode(".", strval($producto->pivot->precio * $producto->pivot->real));
            $total = str_pad($monto[0], 12, "0", STR_PAD_LEFT) .
                '.' .
                str_pad((isset($monto[1]) ? $monto[1] : "0"), 5, "0");
            $vencimiento = '2020-10-29';

            return "
                                                 INTERNO   $sku                                                                                                                                                                                                                                                                                                                                                         $detalle                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $cantidad                                                                                                                                                                                                                                                                                                                           $precio          $vencimiento                                                                                                                                                                                                                                                                                                                                                                                                                                      $total
";
        });

        foreach ($lineasProductos as $lineas) {
            $txt .= $lineas;
        }

        $txt .= "
========== FIN DETALLE\n
========== SUB TOTALES INFORMATIVO\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
========== DESCUENTOS Y RECARGOS\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
========== COMISIONES Y OTROS CARGOS\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
\n
========== CAMPOS PERSONALIZADOS\n
COLUMNAS_DETALLE                                 : 5\n
COLUMNA_DETALLE_1                                : Cantidad\n
ANCHO_COLUMNA_1                                  : 3.0cm\n
DATO_COLUMNA_1                                   : D30\n
ALINEACION_COLUMNA_1                             : C\n
COLUMNA_DETALLE_2                                : DESCRIPCION ITEM\n
ANCHO_COLUMNA_2                                  : 8.0cm\n
DATO_COLUMNA_2                                   : D26\n
ALINEACION_COLUMNA_2                             : L\n
COLUMNA_DETALLE_3                                : Unidad de Medida\n
ANCHO_COLUMNA_3                                  : 2.5cm\n
DATO_COLUMNA_3                                   : D49\n
ALINEACION_COLUMNA_3                             : C\n
COLUMNA_DETALLE_4                                : PRECIO UNITARIO\n
ANCHO_COLUMNA_4                                  : 3.0cm\n
DATO_COLUMNA_4                                   : D46\n
ALINEACION_COLUMNA_4                             : R\n
COLUMNA_DETALLE_5                                : MONTO ITEM\n
ANCHO_COLUMNA_5                                  : 4.1cm\n
DATO_COLUMNA_5                                   : D88\n
ALINEACION_COLUMNA_5                             : R\n
ID_INTERNO                                       : 80618498\n
CARRO                                            : JF4286\n
IMPRESORA                                        : ZPTO\n
SEIMPRIME(1_SI_-2_NO)                            : 1\n
ORIGEN_DESTINO                                   : ASIAN VISION-PTO. SAN ANTONIO\n
NAVE                                             :\n
PUERTO                                           :\n
DESTINO                                          :\n
TEXTO_VARIABLE                                   :\n
CopiaNormal                                      : 2\n
CopiaCedible                                     : C\n
CopiaCedible                                     : C\n
XXX FIN DOCUMENTO\n
";

        $filename = "POR" . date("Ymd") . $this->folio . $this->centro->id . ".txt";
        return Storage::put($filename, $txt);
    }

}
