<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    /**
     * Retorna los Requerimientos de ese Transporte
     *
     * @return \App\Requerimientos
     */
    public function requerimientos()
    {
        return $this->hasMany('App\Requerimiento');
    }
    
    /**
     * Retorna el Abastecimiento para ese Transporte
     *
     * @return \App\Abastecimiento
     */
    public function abastecimiento()
    {
        return $this->belongsTo('App\Abastecimiento');
    }
    
}
