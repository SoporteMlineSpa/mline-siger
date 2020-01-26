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
     * Retorna el folio del Requerimiento
     *
     * @param string $value
     * @return Collection
     */
    public function getFolioAttribute($value)
    {
        return collect(explode(",", str_replace(["[", "]"], "", $value)));
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
        $folios = $this->folio;
        $productos = $this->productos()->get()->chunk(29);

        $assert = $folios->map(function($folio, $index) use ($productos) {
            $folio = str_pad($folio, 10, "0", STR_PAD_LEFT);
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
XXX INICIO DOCUMENTO
========== AREA IDENTIFICACION DEL DOCUMENTO
Tipo Documento Tributario Electronico            : 52
Folio Documento                                  : $folio
Fecha de Emision                                 : $fecha
Indicador No rebaja                              :
Tipo de Despacho                                 : 2
Indicador de Traslado                            : 6
Tipo Impresion                                   :
Indicador de servicio                            :
Indicador de Montos Brutos                       :
Indicador de Montos Netos                        :
Forma de Pago                                    :
Forma de Pago Exportacion                        :
Fecha de Cancelacion                             :
Monto Cancelado                                  :
Saldo Insoluto                                   :
Fecha de Pago                                    :
Fecha de Pago                                    :
Fecha de Pago                                    :
Fecha de Pago                                    :
Periodo Desde                                    :
Periodo Hasta                                    :
Medio de Pago                                    :
Tipo de Cuenta de Pago                           :
Numero de Cuenta de Pago                         :
Banco de Pago                                    :
Codigo Terminos de Pago                          :
Glosa del Termino de Pago                        :
Dias del Termino de Pago                         :
Fecha de Vencimiento                             :
========== AREA EMISOR
Rut Emisor                                       : 96652910-k
Razon Social Emisor                              : COMPASS CATERING SA
Giro del Emisor                                  : Servicios de Alimentacion
Telefono                                         : 225910600
Correo Emisor                                    :
ACTECO                                           : 602300
Codigo Emisor Traslado Excepcional               :
Folio Autorizacion                               :
Fecha Autorizacion                               :
Direccion de Origen Emisor                       : AV. DEL VALLE N° 787, OF. 501
Comuna de Origen Emisor                          : HUECHARUBA
Ciudad de Origen Emisor                          : SANTIAGO
Nombre Sucursal                                  :
Codigo Sucursal                                  : 
Codigo Adicional Sucursal                        :
Codigo Vendedor                                  :
Identificador Adicional del Emisor               :
RUT Mandante                                     :
========== AREA RECEPTOR
Rut Receptor                                     : $rutReceptor
Codigo Interno Receptor                          : 
Nombre o Razon Social Receptor                   : $razonSocialReceptor
Numero Identificador Receptor Extranjero         :
Nacionalidad del Receptor Extranjero             :
Identificador Adicional Receptor Extranjero      :
Giro del negocio del Receptor                    : $giroReceptor
Contacto                                         : 
Correo Receptor                                  :
Direccion Receptor                               : $direccionReceptor
Comuna Receptor                                  : $comunaReceptor
Ciudad Receptor                                  : $ciudadReceptor
Direccion Postal Receptor                        :
Comuna Postal Receptor                           :
Ciudad Postal Receptor                           :
Rut Solicitante de Factura                       :
========== AREA TRANSPORTES
Patente                                          : 
RUT Transportista                                :
Rut Chofer                                       : $transporteRut
Nombre del Chofer                                : $transporteNombre
Direccion Destino                                : $direccionDestino
Comuna Destino                                   : $comunaDestino
Ciudad Destino                                   : $ciudadDestino
Modalidad De Ventas                              :
Clausula de Venta Exportacion                    :
Total Clausula de Venta Exportacion              :
Via de Transporte                                :
Nombre del Medio de Transporte                   :
RUT Compania de Transporte                       :
Nombre Compania de Transporte                    :
Identificacion Adicional Compania de Transporte  :
Booking                                          :
Operador                                         :
Puerto de Embarque                               :
Identificador Adicional Puerto de Embarque       :
Puerto Desembarque                               :
Identificador Adicional Puerto de Desembarque    :
Tara                                             :
Unidad de Medida Tara                            :
Total Peso Bruto                                 :
Unidad de Peso Bruto                             :
Total Peso Neto                                  :
Unidad de Peso Neto                              :
Total Items                                      :
Total Bultos                                     :
Codigo Tipo de Bulto                             :
Codigo Tipo de Bulto                             :
Codigo Tipo de Bulto                             :
Codigo Tipo de Bulto                             :
Flete                                            :
Seguro                                           :
Codigo Pais Receptor                             :
Codigo Pais Destino                              :
========== AREA TOTALES
Tipo Moneda Transaccion                          :
Monto Neto                                       : 0
Monto Exento                                     : 0
Monto Base Faenamiento de Carne                  :
Monto Base de Margen de  Comercializacion        :
Tasa IVA                                         : 0
IVA                                              : 19
IVA Propio                                       :
IVA Terceros                                     :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
IVA no Retenido                                  :
Credito Especial Emp. Constructoras              :
Garantia Deposito Envases                        :
Valor Neto Comisiones                            :
Valor Exento Comisiones                          :
IVA Comisiones                                   :
Monto Total                                      : $montoTotal
Monto No Facturable                              :
Monto Periodo                                    :
Saldo Anterior                                   :
Valor a Pagar                                    :
========== AREA OTRA MONEDA
Tipo Moneda                                      :
Tipo Cambio                                      :
Monto Neto Otra Moneda                           :
Monto Exento Otra Moneda                         :
Monto Base Faenamiento de Carne Otra Moneda      :
Monto Margen Comerc. Otra Moneda                 :
IVA Otra Moneda                                  :
IVA Propio                                       :
Tasa Imp. Otra Moneda                            :
Valor Imp. Otra Moneda                           :
IVA No Retenido Otra Moneda                      :
Monto Total Otra Moneda                          :
";

            $txt .= "
========== DETALLE DE PRODUCTOS Y SERVICIOS
";
            $lineasProductos = $productos->get($index)->map(function ($producto, $index) {
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
========== FIN DETALLE
========== SUB TOTALES INFORMATIVO




















========== DESCUENTOS Y RECARGOS











































========== COMISIONES Y OTROS CARGOS




















========== CAMPOS PERSONALIZADOS
COLUMNAS_DETALLE                                 : 5
COLUMNA_DETALLE_1                                : Cantidad
ANCHO_COLUMNA_1                                  : 3.0cm
DATO_COLUMNA_1                                   : D30
ALINEACION_COLUMNA_1                             : C
COLUMNA_DETALLE_2                                : DESCRIPCION ITEM
ANCHO_COLUMNA_2                                  : 8.0cm
DATO_COLUMNA_2                                   : D26
ALINEACION_COLUMNA_2                             : L
COLUMNA_DETALLE_3                                : Unidad de Medida
ANCHO_COLUMNA_3                                  : 2.5cm
DATO_COLUMNA_3                                   : D49
ALINEACION_COLUMNA_3                             : C
COLUMNA_DETALLE_4                                : PRECIO UNITARIO
ANCHO_COLUMNA_4                                  : 3.0cm
DATO_COLUMNA_4                                   : D46
ALINEACION_COLUMNA_4                             : R
COLUMNA_DETALLE_5                                : MONTO ITEM
ANCHO_COLUMNA_5                                  : 4.1cm
DATO_COLUMNA_5                                   : D88
ALINEACION_COLUMNA_5                             : R
ID_INTERNO                                       : 80618498
CARRO                                            : JF4286
IMPRESORA                                        : ZPTO
SEIMPRIME(1_SI_-2_NO)                            : 1
ORIGEN_DESTINO                                   : ASIAN VISION-PTO. SAN ANTONIO
NAVE                                             :
PUERTO                                           :
DESTINO                                          :
TEXTO_VARIABLE                                   :
CopiaNormal                                      : 2
CopiaCedible                                     : C
CopiaCedible                                     : C
XXX FIN DOCUMENTO
";

            $filename = "POR" . date("Ymd") . $folio . $this->centro->id . ".txt";
            return Storage::put($filename, $txt);

        });

        return !$assert->contains(false);
    }

}
