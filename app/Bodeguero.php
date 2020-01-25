<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodeguero extends Model
{
    /**
     * Retorna los Requerimientos que armó ese Bodeguero
     *
     * @return \App\Requerimiento
     */
    public function requerimientos()
    {
        return $this->hasMany('App\Requerimiento');
    }
    
}
