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
        return $this->belongsToMany('App\Producto')->withPivot('cantidad', 'precio', 'real', 'observacion');
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
        $fecha = date("Ymd");
        $rutReceptor = str_pad(str_replace(["-", "."], "", $this->centro->empresa->rut), 9, STR_PAD_LEFT);
        $razonSocialReceptor = str_pad($this->centro->empresa->razon_social, 100);
        $giroReceptor = str_pad($this->centro->empresa->giro, 40);
        $direccionReceptor = str_pad($this->centro->direccion, 60);
        $comunaReceptor = str_pad($this->centro->comuna, 20);
        $ciudadReceptor = str_pad($this->centro->ciudad, 15);
        $direccionDestino = str_pad($this->transporte->abastecimiento->nombre, 60);
        $transporteRut = str_replace([".", "-"], "", $this->transporte->rut);
        // $folio debe tener un largo de 10 caracteres (rellenar al inicio)
        // $fecha debe tener un largo de 8 caracteres en el formato aaaammdd
        // $rutReceptor debe tener un largo de 9 caracteres incluyendo el
        // digito verificador
        // $razonSocialReceptor debe tener un largo de 100 caracteres
        // $direccionReceptor debe tener un largo de 60 caracteres
        // $comunaReceptor debe tener un largo de 20 caracteres
        // $ciudadReceptor debe tener un largo de 15 caracteres
        $txt = "E052" . $folio . $fecha . "035000000000000000000000000000      0000000000096651910K000062COMPASS  CATERING SA                                                                                Servicios de alimentacion                                                       00000291C                          000000000291C                                                        LOS ANGELES         LOS ANGELES    152138377                                                   00000000 " . $rutReceptor . "291C                " . $razonSocialReceptor . $giroReceptor . "5500274579                                                                      " . $direccionReceptor . $comunaReceptor . $ciudadReceptor . "                                                                                               00000000         " . $transporteRut . $direccionDestino . "                                   00000000000000000000000000000000000000000000000000000001900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000COMPRAS   15213837-7          GUIADESPACHO      000000000000000001                  1A100000000000000000000000000000000000000552090                     \r\n";

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
            $numLinea = str_pad($index + 2, 4, "0", STR_PAD_LEFT);
            $numLineaSII = str_pad($index + 2, 4, "0", STR_PAD_LEFT);
            $sku = str_pad($producto->sku, 35);
            $detalle = str_pad($producto->detalle, 80);
            $real = explode(".", $producto->pivot->real);
            $cantidad = str_pad($real[0], 12, "0", STR_PAD_LEFT) . str_pad(isset($real[1]) ? $real[1] : '0', 6, "0");
            $precio = str_pad($producto->pivot->precio, 12, "0", STR_PAD_LEFT) . str_pad("0", 6, "0");
            $total = str_pad($producto->pivot->precio * $producto->pivot->real, 18, "0", STR_PAD_LEFT);
            return "D" . $numLinea . $numLineaSII . "INTERN" . $sku . "                                                                                                                                                                    0" . $detalle . $cantidad ."UN  000000000000000000" . $cantidad . "000000000000000000                                   000000000000000000                                   000000000000000000                                   000000000000000000                                   000000000000000000                                   0000000000000000UN  " . $precio . "000000000000000000                  000000000000000000000000000000000 000000000000000000 000000000000000000 000000000000000000 000000000000000000 00000000000000000000000000000000000000000 000000000000000000 000000000000000000 000000000000000000 000000000000000000 000000000000000000      " . $total . "                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           000000000000000000000000000000000000000000000000000000                                                  000000\r\n";
        });

        foreach ($lineasProductos as $lineas) {
            $txt .= $lineas;
        }

        $txt .= "T" . $folio . $folio . "0000000001                                       \r\n";

        $filename = "POR" . date("Ymd") . $this->folio . $this->centro->id . ".txt";
        return Storage::put($filename, $txt);
    }

}
