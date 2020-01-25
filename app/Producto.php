<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['sku', 'detalle', 'costo', 'created_at', 'updated_at'];
    /**
     * Devuelve los requerimientos que contienen a ese Producto
     *
     * @return App\Requerimiento
     */
    public function requerimientos()
    {
        return $this->belongsToMany('App\Requerimiento')->withPivot('cantidad', 'precio', 'real', 'observacion', 'fecha_vencimiento');
    }

    /**
     * Retorna las Empresas para ese producto
     *
     * @return App\Empresas
     */
    public function Empresas()
    {
        return $this->belongsToMany('App\Empresa')->withPivot('precio');
    }

    /**
     * Retorna la cantidad solicitada de ese Producto segun el Mes y el Año
     *
     * @param Int $year
     * @param Int $mes
     * @return Int
     */
    public function getCantidadByDate($year = null, $mes = null)
    {
        $requerimientos = $this->requerimientos();
        $requerimientos = $requerimientos->whereYear('requerimientos.created_at', $year ?? date("Y"));
        if (!is_null($mes)) {
            $requerimientos = $requerimientos->whereMonth('requerimientos.created_at', $mes);
        }

        $requerimientos = $requerimientos->get();
        if ($requerimientos->count() > 0) {
            $cantidad = $requerimientos->reduce(function ($carry, $requerimiento) {
                return $carry + $requerimiento->pivot->cantidad;
            });
        } else {
            $cantidad = 0;
        }

        return $cantidad;
    }

    /**
     * Retorna los Requerimientos con ese Producto segun el Mes y el Año
     *
     * @param Int $year
     * @param Int $mes
     * @return Int
     */
    public function getRequerimientosByDate($year = null, $mes = null)
    {
        $requerimientos = $this->requerimientos();
        $requerimientos = $requerimientos->whereYear('requerimientos.created_at', $year ?? date("Y"));
        if (!is_null($mes)) {
            $requerimientos = $requerimientos->whereMonth('requerimientos.created_at', $mes);
        }

        $requerimientos = $requerimientos->get();
        $data = $requerimientos->map(function($requerimiento) {
            return [
                "nombre" => $requerimiento->nombre,
                "fecha" => \Carbon\Carbon::parse($requerimiento->created_at)->format("d-M-Y"),
                "centro" => $requerimiento->centro->nombre,
                "empresa" => $requerimiento->centro->empresa->razon_social
            ];
        });

        return $data;
    }
}
