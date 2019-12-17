<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    /**
     * Los usuarios asociados a ese Centro
     *
     */
    public function users()
    {
        return $this->morphMany('App\User', 'userable');
    }

    /**
     * La Empresa asociada a ese Centro
     *
     */
    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    /**
     * Los Requerimientos asociados a ese Centro
     *
     * @return App\Requerimiento
     */
    public function requerimientos()
    {
        return $this->hasMany('App\Requerimiento');
    }

    /**
     * Retorna el Presupuesto de ese Centro
     *
     * @return App\Presupuesto
     */
    public function presupuestos()
    {
        return $this->morphMany('App\Presupuesto', 'presupuesteable');
    }

    /**
     * Retorna los Requerimientos de ese Centro segun el Id de Estado
     *
     * @return \App\Requerimiento
     */
    public function getRequerimientosByEstado($estadoId = null)
    {
        switch ($estadoId) {
        case 0:
            return $this->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get();
            break;
        case 1:
            return $this->requerimientos()->where('estado', 'VALIDADO')->get();
            break;
        case 2:
            return $this->requerimientos()->where('estado', 'EN PROCESAMIENTO')->get();
            break;
        case 3:
            return $this->requerimientos()->where('estado', 'EN BODEGA')->get();
            break;
        case 4:
            return $this->requerimientos()->where('estado', 'DESPACHADO')->get();
            break;
        case 5:
            return $this->requerimientos()->where('estado', 'ENTREGADO')->get();
            break;
        case 6:
            return $this->requerimientos()->where('estado', 'RECHAZADO')->get();
            break;
        default:
            return $this->requerimientos()->get();
            break;
        }
    }

}
